<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class PaymentRepository extends EntityRepository
{
    public function findPaymentsByDateTime(\DateTime $from, \DateTime $to): array
    {
        $queryBuilder = $this->createQueryBuilder('p')
            ->where('p.createdAt > :from')
            ->andWhere('p.createdAt < :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to);

        $query = $queryBuilder->getQuery();

        return $query->getArrayResult();
    }
}