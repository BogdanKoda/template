<?php

namespace app\components\excel;

use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Exception;

class ExcelSaveActiveRecord implements IExcelSaveTemplate
{
    private int $startRow = 1;
    private int $startColumn = 1;

    private ?array $selectFields = null;

    private Excel $xls;

    public static function handle(Excel $xls): ExcelSaveActiveRecord
    {
        $instance = new self;
        $instance->xls = $xls;
        return $instance;
    }

    public function load(array $data, string $classModel): ExcelSaveActiveRecord
    {
        $model = new $classModel;
        $fields = $this->selectFields ?? $model->fields();

        $column = $this->startColumn;
        $row = $this->startRow + 1;

        $first = true;

        $this->xls->setHeight($row - 1, 25);

        foreach($data as $itemModel) {
            foreach ($fields as $index => $field) {
                if(!is_string($field)) {
                    $field = $index;
                }

                if($first) {
                    $coords = ExcelHelper::coords($column, $row - 1);
                    $this->xls->cellBuilder($coords, $model->attributeLabels()[$field] ?? $field)
                        ->setFont(ExcelFontBuilder::init()->useBoldStyle()->useUnderlineStyle())
                        ->setBgFillType(PHPExcel_Style_Fill::FILL_PATTERN_DARKGRID)
                        ->setBackgroundColor("#AA0000")
                        ->setFontColor("#FFFFFF")
                        ->setHorizontalAlignment("center")
                        ->setVerticalAlignment("center")
                        ->setBorderColor("#000000")
                        ->setBorderType(PHPExcel_Style_Border::BORDER_MEDIUM);
                }

                $coords = ExcelHelper::coords($column, $row);

                $value = $itemModel->{$field} ?? "";
                if(!is_array($value)) {
                    $this->xls->cellBuilder($coords, $value)->useWrap();
                }

                $column++;
            }

            $first = false;
            $column = $this->startColumn;
            $row++;
        }

        return $this;
    }

    public function setSelectFields(...$selectFields): ExcelSaveActiveRecord
    {
        $this->selectFields = $selectFields;
        return $this;
    }

    /**
     * @param int|string $column
     * @return $this
     * @throws Exception
     */
    public function setStartColumn($column): ExcelSaveActiveRecord
    {
        if(is_string($column)) {
            $this->startColumn = Coordinate::columnIndexFromString($column);
        } else {
            $this->startColumn = $column;
        }
        return $this;
    }

    public function setStartRow($row): ExcelSaveActiveRecord
    {
        $this->startRow = $row;
        return $this;
    }
}