<?php


namespace App\Repository;


use App\Entity\History;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

class HistoryRepository extends AbstractRepository
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

    /**
     * @param int $limit
     * @return History[]
     */
    public function getLastRecords(int $limit): array
    {
        return $this->findBy([], ['executed' => 'desc'], $limit);
    }

    protected function getEntityClassName(): string
    {
        return History::class;
    }
}