<?php

namespace App\DTO;

class PaymentDto
{
    /**
     * @var float
     */
    private $amount;

    /**
     * @var string
     */
    private $purpose;

    /**
     * @var string
     */
    private $shopUrl;

    /**
     * @var string
     */
    public $url;

    /**
     * PaymentDto constructor.
     * @param float $amount
     * @param string $purpose
     * @param string $shopUrl
     */
    public function __construct(float $amount, string $purpose, string $shopUrl)
    {
        $this->amount = $amount;
        $this->purpose = $purpose;
        $this->shopUrl = $shopUrl;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getPurpose(): string
    {
        return $this->purpose;
    }

    /**
     * @return string
     */
    public function getShopUrl(): string
    {
        return $this->shopUrl;
    }
}
