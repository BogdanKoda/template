<?php

return [
	"origins" => ['localhost', 'koda.ruyou.ru'],

    "limit" => 20,
    "cacheDuration" => 2400,
    "refreshTokenExpiredAt" => 86400*60,

    "smsCodeTimeout" => 300,
    "smsCodeExpired" => 600,

    "maxRefreshToken" => 5,
	"log" => $_SERVER["DOCUMENT_ROOT"] . "/log",
	
];
