<?php

namespace app\components\dto;

use app\components\Exceptions\ModelException;
use Closure;
use Exception;
use Prophecy\Exception\Doubler\MethodNotFoundException;
use ReflectionClass;
use ReflectionException;
use TypeError;

class DTO
{
    public object $dto;
    private array $methods = [];
    private array $properties;

    public static function handle(string $dtoClass, array $data): self
    {
        $self = new self();
        $self->dto = new $dtoClass;
        try {
            $self->createAccessMethods($dtoClass);
            $self->load($data);
        } catch (ReflectionException $e) {

        }

        return $self;
    }

    public function __call($method, $args)
    {
        if(is_callable($this->methods[$method])) {
            return call_user_func_array($this->methods[$method], $args);
        }

        throw new MethodNotFoundException("Method not found", self::class, $method);
    }

    /**
     * @throws ReflectionException
     */
    private function createAccessMethods(string $dtoClass)
    {
        $reflection = new ReflectionClass($dtoClass);
        $properties = $reflection->getProperties();
        $this->properties = $properties;

        foreach ($properties as $property) {
            $this->methods["get".ucfirst($property->name)] = Closure::bind(
                function () use ($property, $reflection) {
                    $prop = $reflection->getProperty($property->name);
                    $prop->setAccessible(true);
                    return $prop->getValue($this->dto);
                },
                $this,
                get_class()
            );

            $this->methods["set".ucfirst($property->name)] = Closure::bind(
                function ($value) use ($property, $reflection) {
                    $prop = $reflection->getProperty($property->name);
                    $prop->setAccessible(true);
                    $prop->setValue($this->dto, $value);
                },
                $this,
                get_class()
            );

        }
    }

    private function load(array $data)
    {
        $errors = [];

        foreach ($this->properties as $property) {

            $propName = $property->name;
            if(method_exists($this->dto, "attributes")) {
                $propName = $this->dto->attributes()[$propName] ?? $propName;
            }

            try {
                $this->{"set".ucfirst($property->name)}($data[$property->name] ?? null);
            } catch (TypeError $ex) {
                if(!isset($data[$property->name])) {
                    $message = "Поле \"$propName\" обязательно для заполнения";
                    $this->addError($errors, $property->name, $message);
                    continue;
                }
                $type = $ex->getMessage();
                $regexp = '!\$'.$property->name.' must be ([a-zA-Z]+)!';
                preg_match($regexp, $type, $matches);
                $type = $matches[1] ?? "unknown";

                switch ($type) {
                    case 'string':
                        $type = 'строкой';
                        break;
                    case 'int':
                        $type = "целым числом";
                        break;
                    case 'float':
                        $type = "плавающим числом";
                        break;
                    case 'bool':
                        $type = "булевым значением";
                        break;
                    case 'boolean':
                        $type = "булевым значением";
                        break;
                    case 'array':
                        $type = "массивом";
                        break;
                }

                $this->addError($errors, $property->name, "Поле \"$propName\" должно быть $type");
            }
        }

        if($errors != []) {
            throw new ModelException($errors);
        }
    }

    public function toArray(): array
    {
        $data = [];
        foreach ($this->properties as $property) {
            $data[$property->name] = $this->{"get" . ucfirst($property->name)}();
        }
        return $data;
    }


    public function addError(array &$errors, string $fieldName, string $message): void
    {
        $errors[$fieldName] = $errors[$fieldName] ?? [];
        $errors[$fieldName][] = $message;
    }

    private function __construct() {}
    private function __clone() {}
    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserializable a singleton.");
    }


}