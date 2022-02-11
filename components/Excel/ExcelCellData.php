<?php

namespace app\components\excel;

class ExcelCellData
{
    private string $value;
    private string $cell;

    private ?string $merge = null;
    private ?string $horizontalAlignment = null;
    private ?string $verticalAlignment = null;
    private ?array $font = null;
    private ?string $fontColor = null;
    private ?bool $wrap = null;
    private ?string $backgroundColor = null;
    private ?string $fillType = null;
    private ?string $borderType = null;
    private ?string $borderColor = null;

    public function __construct($cell, $value)
    {
        $this->cell = $cell;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getCell(): string
    {
        return $this->cell;
    }

    /**
     * @param string $cell
     */
    public function setCell(string $cell): void
    {
        $this->cell = $cell;
    }

    /**
     * @return string|null
     */
    public function getMerge(): ?string
    {
        return $this->merge;
    }

    /**
     * @param string|null $merge
     */
    public function setMerge(?string $merge): void
    {
        $this->merge = $merge;
    }

    /**
     * @return string|null
     */
    public function getHorizontalAlignment(): ?string
    {
        return $this->horizontalAlignment;
    }

    /**
     * @param string|null $horizontalAlignment
     */
    public function setHorizontalAlignment(?string $horizontalAlignment): void
    {
        $this->horizontalAlignment = $horizontalAlignment;
    }

    /**
     * @return string|null
     */
    public function getVerticalAlignment(): ?string
    {
        return $this->verticalAlignment;
    }

    /**
     * @param string|null $verticalAlignment
     */
    public function setVerticalAlignment(?string $verticalAlignment): void
    {
        $this->verticalAlignment = $verticalAlignment;
    }

    /**
     * @return array|null
     */
    public function getFont(): ?array
    {
        return $this->font;
    }

    /**
     * @param array|null $font
     */
    public function setFont(?array $font): void
    {
        $this->font = $font;
    }

    /**
     * @return string|null
     */
    public function getFontColor(): ?string
    {
        return $this->fontColor;
    }

    /**
     * @param string|null $fontColor
     */
    public function setFontColor(?string $fontColor): void
    {
        $this->fontColor = $fontColor;
    }

    /**
     * @return bool|null
     */
    public function getWrap(): ?bool
    {
        return $this->wrap;
    }

    /**
     * @param bool|null $wrap
     */
    public function setWrap(?bool $wrap): void
    {
        $this->wrap = $wrap;
    }

    /**
     * @return string|null
     */
    public function getBackgroundColor(): ?string
    {
        return $this->backgroundColor;
    }

    /**
     * @param string|null $backgroundColor
     */
    public function setBackgroundColor(?string $backgroundColor): void
    {
        $this->backgroundColor = $backgroundColor;
    }

    /**
     * @return string|null
     */
    public function getFillType(): ?string
    {
        return $this->fillType;
    }

    /**
     * @param string|null $fillType
     */
    public function setFillType(?string $fillType): void
    {
        $this->fillType = $fillType;
    }

    /**
     * @return string|null
     */
    public function getBorderType(): ?string
    {
        return $this->borderType;
    }

    /**
     * @param string|null $borderType
     */
    public function setBorderType(?string $borderType): void
    {
        $this->borderType = $borderType;
    }

    /**
     * @return string|null
     */
    public function getBorderColor(): ?string
    {
        return $this->borderColor;
    }

    /**
     * @param string|null $borderColor
     */
    public function setBorderColor(?string $borderColor): void
    {
        $this->borderColor = $borderColor;
    }



}