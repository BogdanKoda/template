<?php
namespace app\components\Auth\JWT;

use yii\helpers\Url;

final class JWTConfig
{
    // Ключ шифрования
    private string $secretKey;
    // Дата создания
    private int $issuedAt;
    // Время истечения токена
    private int $expire;
    // Хост токена
    private string $serverName;
    // Алгоритм шифрования данный
    private string $algorithmEncrypt;

    public function __construct ()
    {
        $this->secretKey = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
        $this->issuedAt = time();
        $this->expire = $this->issuedAt + 86400*30;
        $this->serverName = Url::base(true);
        $this->algorithmEncrypt = 'HS512';
    }

    /**
     * @return int
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * @return string
     */
    public function getServerName(): string
    {
        return $this->serverName;
    }

    /**
     * @return string
     */
    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
    /**
     * @return int
     */
    public function getIssuedAt(): int
    {
        return $this->issuedAt;
    }

    /**
     * @return string
     */
    public function getAlgorithmEncrypt(): string
    {
        return $this->algorithmEncrypt;
    }



}