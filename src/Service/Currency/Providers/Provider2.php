<?php

// src/Service/Currency/Providers/Provider2.php
namespace App\Service\Currency\Providers;

class Provider2
{
    private $url;

    private $content = null;

    private $rowCount = 0;

    const symbol = [
        'DOLAR' => 'USD',
        'AVRO' => 'EUR',
        'İNGİLİZ STERLİNİ' => 'GBP',
    ];

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getContentByUrl()
    {
        if ($this->url) {
            $this->content = json_decode(file_get_contents($this->url));
        }

        return $this->getContent();
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getContentByRow()
    {
        if (! isset($this->content[$this->rowCount])) {
            return false;
        }

        $row = $this->content[$this->rowCount];

        $this->rowCount++;

        $this->price = $row->oran;
        $this->code = self::symbol[$row->kod];

        return $row;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getCode()
    {
        return $this->code;
    }

}