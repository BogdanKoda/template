<?php

namespace app\components\excel;

class ExcelHelper
{
    private static array $alphabet = ["A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"];

    /**
     * @param int $number
     * @return string
     */
    public static function numberToString(int $number): string
    {
        $string = [];
        $len = 0;
        $k = 0;

        for($i = 0; $i <= $number; $i++) {
            if($k > 26 * $len) {
                $string[$len] = isset($string[$len]) ? $string[$len] + 1 : 0;
                while($string[$len] >= 26) {
                    $string[$len] = 0;
                    $len++;
                    $string[$len] = isset($string[$len]) ? $string[$len]+1 : 0;
                }
                $len = 0;
                $k = 0;
            }
            $k++;
        }
        $str = "";

        $string = array_reverse($string);
        foreach($string as $s) {
            $str .= self::$alphabet[$s];
        }

        return $str;
    }

    /**
     * @param int $column
     * @param int $row
     * @return string
     */
    public static function coords(int $column, int $row): string
    {
        return self::numberToString($column) . $row;
    }
}