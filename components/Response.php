<?php

namespace app\components;

use Yii;
use yii\helpers\Url;

class Response
{

    const JUST_SUCCESS = 0;
    const SUCCESS_RESPONSE = 1;
    const MESSAGE_RESPONSE = 2;
    const ERRORS_RESPONSE = 3;

    private static ?self $_instance = null;

    private array $response = [];
    private array $meta = [];
    private int $code = 200;
    private int $responseType = 1;

    public static function success($data): self
    {
        return self::handle(self::SUCCESS_RESPONSE, $data);
    }

    public static function message(string $message): self
    {
        return self::handle(self::MESSAGE_RESPONSE, $message);
    }

    public static function errors(array $errors): self
    {
        return self::handle(self::ERRORS_RESPONSE, $errors);
    }

    public static function status(bool $status = true): self
    {
        return self::handle(self::JUST_SUCCESS, $status);
    }

    private static function handle(int $responseType, $data): self
    {
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        self::$_instance->responseType = $responseType;

        return self::$_instance->init($data);
    }

    private function init($data): self
    {
        $response = [
            'baseUrl' => Url::base(true),
        ];

        switch ($this->responseType) {
            case 0:
                $response['success'] = $data;
                break;
            case 1:
                $response['data'] = $data;
                break;
            case 2:
                $response['message'] = $data;
                break;
            case 3:
                $response['errors'] = $data;
                $this->code = 422;
                break;
        }

        $this->response = $response;

        return $this;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function addMeta(string $key, object $value): self
    {
        $this->meta[$key] = $value;
        return $this;
    }

    public function withMeta(array $meta): Response
    {
        $this->meta = $meta;
        return $this;
    }

    public function return(): array
    {
        Yii::$app->response->setStatusCode($this->code);
        if(!empty($this->meta)) {
            $this->response["meta"] = $this->meta;
        }
        $this->response['success'] = $this->response['success'] ?? $this->code >= 200 && $this->code < 300;

        $response = $this->response;
        $this->reset();
        return $response;
    }

    private function reset()
    {
        $this->response = [];
        $this->code = 200;
        $this->responseType = 1;
    }

    private function __construct() { }
    private function __clone() { }

}