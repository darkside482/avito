<?php

namespace App\Handler;

use App\DTO\PaymentDto;
use App\Entity\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Predis\Client as RedisClient;

class PaymentHandler
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RedisClient
     */
    private $redis;

    /**
     * @var int
     */
    private $redisPaymentStandardTimeout;

    /**
     * PaymentHandler constructor.
     * @param EntityManagerInterface $em
     * @param int $redisPaymentStandardTimeout
     */
    public function __construct(EntityManagerInterface $em, RedisClient $redis, int $redisPaymentStandardTimeout)
    {
        $this->em = $em;
        $this->redis = $redis;
        $this->redisPaymentStandardTimeout = $redisPaymentStandardTimeout;
    }


    public function registerPayment(PaymentDto $dto): string
    {
        $payment = new Payment();
        $payment
            ->setAmount($dto->getAmount())
            ->setPurpose($dto->getPurpose())
            ->setShopUrl($dto->getShopUrl())
            ->setCreatedAt(new \DateTime('now'));

        $this->em->persist($payment);
        $this->em->flush();

        $redisKey = 'payments:'.$payment->getId();

        $this->redis->hset($redisKey, 'amount', $payment->getAmount());
        $this->redis->hset($redisKey, 'purpose', $payment->getPurpose());
        $this->redis->hset($redisKey, 'shopUrl', $payment->getShopUrl());
        $this->redis->expire($redisKey, $this->redisPaymentStandardTimeout);

        return $payment->getId();
    }
}
