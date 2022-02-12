<?php

namespace app\components\excel;

use app\models\extend\BaseModel;

interface IExcelSaveTemplate
{
    public static function handle(Excel $xls);
    public function load(array $data, string $classModel);
}