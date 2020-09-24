<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

class PaymentFormDto
{
    /**
     * @var float
     *
     * @Assert\NotBlank
     */
    public $amount;

    /**
     * @var string
     *
     * @Assert\NotBlank
     */
    public $purpose;

    /**
     * @var string
     *
     * @AcmeAssert\LuhnAlgorithm
     */
    public $cardNum;

    public function __construct(array $values)
    {
        $this->amount = $values['amount'];
        $this->purpose = $values['purpose'];
    }
}
