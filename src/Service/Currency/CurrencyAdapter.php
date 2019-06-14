<?php

// src/Service/Currency/CurrencyAdapter.php
namespace App\Service\Currency;

class CurrencyAdapter
{
    private $providerClass;

    public function __construct($providerModel)
    {
        $providerClassName = __NAMESPACE__ . '\\Providers\\' . $providerModel->getName();

        $this->providerClass = new $providerClassName($providerModel->getUrl());
    }

    public function getContent()
    {
        if ($this->providerClass->getContent() === NULL) {
            if (! $this->providerClass->getContentByUrl()) {
                return false;
            }
        }

        return $this->providerClass->getContentByRow();
    }

    public function getPrice()
    {
        return $this->providerClass->getPrice();
    }

    public function getCode()
    {
        return $this->providerClass->getCode();
    }
}