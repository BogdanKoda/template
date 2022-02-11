<?php

namespace app\components\excel;

class ExcelFontBuilder implements IExcelBuilder
{
    private array $font = [
        "name" => "Calibri",
        "size" => 11,
        "bold" => false,
        "italic" => false,
        "underline" => false,
        "strike" => false,
    ];

    public static function init(): ExcelFontBuilder
    {
        return new self;
    }

    public function setFont(string $font): ExcelFontBuilder
    {
        $this->font["name"] = $font;
        return $this;
    }

    public function setSize(int $size): ExcelFontBuilder
    {
        $this->font["size"] = $size;
        return $this;
    }

    public function useBoldStyle(): ExcelFontBuilder
    {
        $this->font["bold"] = true;
        return $this;
    }

    public function useItalicStyle(): ExcelFontBuilder
    {
        $this->font["italic"] = true;
        return $this;
    }

    public function useUnderlineStyle(): ExcelFontBuilder
    {
        $this->font["underline"] = true;
        return $this;
    }

    public function useStrikeStyle(): ExcelFontBuilder
    {
        $this->font["strike"] = true;
        return $this;
    }

    public function build(): array
    {
        return $this->font;
    }

    private function __construct() {}
    private function __clone() {}
}