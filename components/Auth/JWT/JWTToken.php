<?php
namespace app\components\Auth\JWT;

use app\components\Auth\UserAuthData;
use Firebase\JWT\JWT;

class JWTToken
{

    public static function generate(UserAuthData $jwtData): string
    {
        $config = self::getConfig();
        $data = [
            'iat' => $config->getIssuedAt(),
            'iss' => $config->getServerName(),
            'nbf' => $config->getIssuedAt(),
            'exp' => $config->getExpire(),
            'data' => [
                "userId" => $jwtData->getId(),
                "roles" => $jwtData->getRoles()
            ],
        ];

        return JWT::encode($data, $config->getSecretKey(), $config->getAlgorithmEncrypt());
    }

    public static function parse(string $jwt): object
    {
        $config = self::getConfig();
        $token = JWT::decode($jwt, $config->getSecretKey(), [$config->getAlgorithmEncrypt()]);
        return $token->data;
    }

    private static function getConfig(): JWTConfig
    {
        return new JWTConfig();
    }

}