<?php


namespace App\Repository;


use App\Entity\History;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class HistoryRepository extends ServiceEntityRepository
{
    /**
     * @param History $entity
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function addResult(History $entity)
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }
}