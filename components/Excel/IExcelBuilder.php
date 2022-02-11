<?php

namespace app\components\excel;

interface IExcelBuilder
{
    public function build(): array;
}