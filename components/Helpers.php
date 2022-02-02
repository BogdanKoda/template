<?php

namespace app\components;

use Exception as phpException;
use Yii;
use yii\base\Exception;
use yii\helpers\Url;

class Helpers
{
	public static function getDate(): int
    {
		//return date("Y-m-d\TH:i:s");
        return time();
	}
	
	public static function hashPassword(string $password): string
	{
		return sha1("aa6e897a68@" . md5("298aa#%461dvb6968sada8%&*ada" . $password));
	}
	
	/**
	 * @throws Exception
	 */
	public static function generateToken(): string
	{
		return Yii::$app->security->generateRandomString(245) . time();
	}

    /**
     * @throws phpException
     */
    public static function generateNumber(int $length): int
    {
        $min = pow(10, $length-1);
        $max = pow(10, $length) - 1;

        return random_int($min, $max);
    }

    public static function baseImage(): string
    {
        return Url::base(true) . "/" . "images/logo.jpg";
    }


}