<?php

namespace app\components\Enums;

use yii2mod\enum\helpers\BaseEnum;

class Gender extends BaseEnum
{

    const MALE = "Мужской";
    const FEMALE = "Женский";

    protected static $list = [
        self::MALE => "MALE",
        self::FEMALE => "FEMALE",
    ];

}