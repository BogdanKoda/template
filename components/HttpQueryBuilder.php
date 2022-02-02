<?php

namespace app\components;

class HttpQueryBuilder
{
    const POST = "POST";
    const GET = "GET";
    const DELETE = "DELETE";
    const PUT = "PUT";
    const PATCH = "PATCH";
    const HEAD = "HEAD";
    const OPTIONS = "OPTIONS";

    private string $url = "";
    private array $options = [];
    private $curl;
    private $response;

    private static ?self $_instance = null;

    public static function url(string $url): HttpQueryBuilder
    {
        if(self::$_instance === null) {
            self::$_instance = new self;
        }
        self::$_instance->url = $url;
        self::$_instance->reset();
        return self::$_instance;
    }

    public function build(): HttpQueryBuilder
    {
        $this->options[CURLOPT_URL] = $this->url;
        $this->options[CURLOPT_POSTFIELDS] = json_encode($this->options[CURLOPT_POSTFIELDS]);
        $this->curl = curl_init();
        curl_setopt_array($this->curl, $this->options);
        return $this;
    }

    public function execute(): HttpQueryBuilder
    {
        $this->response = curl_exec($this->curl);
        curl_close($this->curl);
        return $this;
    }

    public function response()
    {
        return $this->response;
    }


    public function setTypeRequest(string $option): HttpQueryBuilder
    {
        $this->options[CURLOPT_CUSTOMREQUEST] = $option;
        return $this;
    }

    public function followLocation(bool $follow): HttpQueryBuilder
    {
        $this->options[CURLOPT_FOLLOWLOCATION] = $follow;
        return $this;
    }

    public function returnTransfer(bool $returnTransfer): HttpQueryBuilder
    {
        $this->options[CURLOPT_RETURNTRANSFER] = $returnTransfer;
        return $this;
    }

    public function setMaxRedirs(int $maxRedirs): HttpQueryBuilder
    {
        $this->options[CURLOPT_MAXREDIRS] = $maxRedirs;
        return $this;
    }

    public function setTimeout(int $timeout): HttpQueryBuilder
    {
        $this->options[CURLOPT_TIMEOUT] = $timeout;
        return $this;
    }

    public function encoding(string $encoding): HttpQueryBuilder
    {
        $this->options[CURLOPT_ENCODING] = $encoding;
        return $this;
    }

    public function withSslVerify(bool $verify): HttpQueryBuilder
    {
        $this->options[CURLOPT_SSL_VERIFYPEER] = $verify;
        return $this;
    }

    public function withBody(array $body): HttpQueryBuilder
    {
        $this->options[CURLOPT_POSTFIELDS] = $body;
        return $this;
    }

    public function addBody(string $key, object $value): HttpQueryBuilder
    {
        $this->options[CURLOPT_POSTFIELDS][$key] = $value;
        return $this;
    }

    public function withHeaders(array $headers): HttpQueryBuilder
    {
        $this->options[CURLOPT_HTTPHEADER] = $headers;
        return $this;
    }

    public function addHeader(string $key, string $value): HttpQueryBuilder
    {
        $this->options[CURLOPT_HTTPHEADER] = $this->options[CURLOPT_HTTPHEADER] ?? [];
        $this->options[CURLOPT_HTTPHEADER][] = "$key: $value";
        return $this;
    }

    public function reset()
    {
        $this->options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 1,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => self::GET,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER => []
        ];
    }

    private function __construct() {}
    private function __clone() {}



}