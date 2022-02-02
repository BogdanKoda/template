<?php

namespace unit;

use app\components\service\CrudActionsImpl;
use app\components\service\CrudActionsService;
use app\components\Strategy\BasicSave;
use app\components\Strategy\SaveStrategy;
use app\models\Users;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class CrudActionsServiceTest extends TestCase
{
    protected CrudActionsImpl $strategy;

    public function setUp(): void
    {
        $this->strategy = new CrudActionsService($this->getMockStrategy([]));
    }

    protected function getMockStrategy(array $controllerData): SaveStrategy
    {
        return new BasicSave(new Users(), $controllerData);
    }


    public function testList()
    {
    }

    public function testDelete()
    {
        try{
            $this->strategy->delete(["id" => 10]);
            $this->assertSame(true, true);
        } catch (Exception $e){
            $this->assertSame(true, false);
        }
    }

    public function testDeleteWithUnexpectedFilter()
    {
        try {
            $this->strategy->delete(["test" => "qwe"]);
            $this->assertSame(true, false);
        }
        catch (InternalErrorException $e){
            $this->assertSame(true, true);
        }
    }

    public function testCreate()
    {

    }

    public function testUpdate()
    {

    }

    public function testGet()
    {

    }
}
