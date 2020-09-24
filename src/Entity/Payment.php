<?php

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Uuid;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as AcmeAssert;

/**
 * Class Payment
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 * @ORM\Table(name="payments")
 */
class Payment
{
    /**
     * @var UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid")
     */
    private $id;

    /**
     * @var float
     *
     * @Assert\NotBlank
     * @ORM\Column(name="amount", type="float", nullable=false, options={"comment": "Amount of payment"})
     */
    private $amount;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(name="purpose", type="string", nullable=false, options={"comment": "Purpose of payment"})
     */
    private $purpose;

    /**
     * @var string
     *
     * @ORM\Column(name="shop_url", type="string", nullable=true, options={"comment": "Shop url for payment confirm"})
     */
    private $shopUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false, options={"comment": "Payment create datetime"})
     */
    private $createdAt;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
    }

    /**
     * @return UuidInterface
     */
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return $this
     */
    public function setAmount(float $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getPurpose(): string
    {
        return $this->purpose;
    }

    /**
     * @param string $purpose
     * @return $this
     */
    public function setPurpose(string $purpose): self
    {
        $this->purpose = $purpose;
        return $this;
    }

    /**
     * @param string $shopUrl
     * @return $this
     */
    public function setShopUrl(string $shopUrl): self
    {
        $this->shopUrl = $shopUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getShopUrl(): string
    {
        return $this->shopUrl;
    }

    /**
     * @param \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
