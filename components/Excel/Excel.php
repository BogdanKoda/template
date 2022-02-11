<?php

namespace app\components\excel;

use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PhpOffice\PhpSpreadsheet\Exception as PhpExcelException;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Excel
{
    private Spreadsheet $xls;

    public function __construct()
    {
        $this->xls = new Spreadsheet();
    }

    /**
     * @param string $cell
     * @param string $value
     * @return ExcelCellBuilder
     */
    public function cellBuilder(string $cell, string $value): ExcelCellBuilder
    {
        return ExcelCellBuilder::handle($cell, $value);
    }

    /**
     * @throws PhpExcelException
     */
    private function build(): void
    {
        foreach (ExcelCellBuilder::getCellList() as $cellData){
            $this->xls->getActiveSheet()->setCellValue(
                $cellData->getCell(),
                $cellData->getValue()
            );

            if($merge = $cellData->getMerge()) {
                $this->xls->getActiveSheet()->mergeCells($merge);
            }

            if($horizontal = $cellData->getHorizontalAlignment()) {
                $this->xls->getActiveSheet()->getStyle($cellData->getCell())->getAlignment()->setHorizontal($horizontal);
            }
            if($vertical = $cellData->getVerticalAlignment()) {
                $this->xls->getActiveSheet()->getStyle($cellData->getCell())->getAlignment()->setVertical($vertical);
            }

            if($wrap = $cellData->getWrap()) {
                $this->xls->getActiveSheet()->getStyle($cellData->getCell())->getAlignment()->setWrapText($wrap);
            }

            $styles = [
                "font" => $cellData->getFont() ?? ExcelFontBuilder::init()->build()
            ];
            if($fontColor = $cellData->getFontColor()) {
                $styles["font"]["color"]["rgb"] = strtr($fontColor, ["#" => ""]);
            }

            if($bgColor = $cellData->getBackgroundColor()) {
                $this->xls->getActiveSheet()
                    ->getStyle($cellData->getCell())
                    ->getFill()
                    ->setFillType($cellData->getFillType() ?? PHPExcel_Style_Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB(strtr($bgColor, ["#" => ""]));
            }

            if($borderColor = $cellData->getBorderColor()) {
                $styles["borders"]["outline"] = [
                    "borderStyle" => $cellData->getBorderType() ?? PHPExcel_Style_Border::BORDER_THICK,
                    "color" => [
                        "rgb" => strtr($borderColor, ["#" => ""])
                    ]
                ];
            }

            $this->xls->getActiveSheet()->getStyle($cellData->getCell())->applyFromArray($styles);
        }
    }


    /**
     * @throws Exception
     * @throws PhpExcelException
     */
    public function save(bool $download = false, string $filename = "excel.xlsx") {

        $this->build();

        if($download) {
            header("Cache-Control: no-cache, must-revalidate");
            header("Pragma: no-cache");
            header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition:e attachment; filename=".$filename);
        }

        $writer = new Xlsx($this->xls);
        $writer->save($filename);
    }


    /**
     * @param string $cell
     * @param int $width
     */
    public function setWidth(string $cell, int $width = 0): void
    {
        $column = $this->parseCell($cell)['column'];
        if($width == 0) {
            $this->xls->getActiveSheet()->getColumnDimensionByColumn($column)->setAutoSize(true);
        } else {
            $this->xls->getActiveSheet()->getColumnDimension($column)->setWidth($width);
        }
    }

    /**
     * @param string $cell
     * @param int $height
     */
    public function setHeight(string $cell, int $height): void
    {
        $row = $this->parseCell($cell)["row"];
        $this->xls->getActiveSheet()->getRowDimension($row)->setRowHeight($height);
    }

    /**
     * @param string $cell
     * @return array
     */
    private function parseCell(string $cell): array
    {
        $row = preg_replace("/^[^0-9]/", "", $cell);
        $column = preg_replace("/[0-9]/", "", $cell);

        return [
            "row" => $row,
            "column" => $column,
        ];
    }

}