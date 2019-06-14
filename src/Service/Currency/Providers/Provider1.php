<?php

// src/Service/Currency/Providers/Provider1.php
namespace App\Service\Currency\Providers;

class Provider1
{
    private $url;

    private $content = null;

    private $rowCount = 0;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function getContentByUrl()
    {
        if ($this->url) {
            $this->content = json_decode(file_get_contents($this->url));

            $this->content = $this->content->result;
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

        $this->price = $row->amount;
        $this->code = preg_replace('/TRY$/i', '', $row->symbol);

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