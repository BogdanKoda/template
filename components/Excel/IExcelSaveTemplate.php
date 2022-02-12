<?php

namespace app\components\excel;

interface IExcelSaveTemplate
{
    public static function handle(Excel $xls);
    public function load(array $data, string $classModel);
}