<?php

namespace app\components\excel;

class ExcelCellBuilder
{
    private ExcelCellData $cell;

    /**
     * @var ExcelCellData[] $cells
     */
    private array $cells = [];

    private static ?self $_instance = null;

    private function init($cell, $value)
    {
        $this->cell = new ExcelCellData($cell, $value);
    }

    public static function handle($cell, $value): ExcelCellBuilder
    {
        if(self::$_instance === null) {
            self::$_instance = new self;
        } else {
            self::$_instance->cells[] = self::$_instance->cell;
        }

        self::$_instance->init($cell, $value);

        return self::$_instance;
    }

    public function withMergeCells(string $mergeCells): ExcelCellBuilder
    {
        $this->cell->setMerge($mergeCells);
        return $this;
    }

    public function setVerticalAlignment(string $alignment): ExcelCellBuilder
    {
        $this->cell->setVerticalAlignment($alignment);
        return $this;
    }

    public function setHorizontalAlignment(string $alignment): ExcelCellBuilder
    {
        $this->cell->setHorizontalAlignment($alignment);
        return $this;
    }

    public function setFont(IExcelBuilder $fontBuilder): ExcelCellBuilder
    {
        $this->cell->setFont($fontBuilder->build());
        return $this;
    }

    public function setFontColor(string $color): ExcelCellBuilder
    {
        $this->cell->setFontColor($color);
        return $this;
    }

    public function useWrap(): ExcelCellBuilder
    {
        $this->cell->setWrap(true);
        return $this;
    }

    public function setBorderType(string $borderType): ExcelCellBuilder
    {
        $this->cell->setBorderType($borderType);
        return $this;
    }

    public function setBorderColor(string $borderColor): ExcelCellBuilder
    {
        $this->cell->setBorderColor($borderColor);
        return $this;
    }


    public function setBackgroundColor(string $bgColor): ExcelCellBuilder
    {
        $this->cell->setBackgroundColor($bgColor);
        return $this;
    }

    public function setBgFillType(string $fillType): ExcelCellBuilder
    {
        $this->cell->setFillType($fillType);
        return $this;
    }


    public function cell(): ExcelCellData
    {
        return $this->cell;
    }

    /**
     * @return ExcelCellData[]
     */
    public static function getCellList(): array
    {
        $cells = self::$_instance->cells ?? [];
        $cells[] = self::$_instance->cell;
        return $cells;
    }

    private function __clone() {}
    private function __construct() {}

}