<?php

namespace App\Repository;

use App\Entity\Log;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Log>
 *
 * @method Log|null find($id, $lockMode = null, $lockVersion = null)
 * @method Log|null findOneBy(array $criteria, array $orderBy = null)
 * @method Log[]    findAll()
 * @method Log[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Log::class);
    }

    public function add(Log $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Log $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Log[] Returns an array of Log objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Log
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function countByParams($params = []): int
    {
        $qb = $this->createQueryBuilder('l')
            ->select('count(l.id)');
        
        if ($params['serviceNames']) {
            $qb->andWhere('l.service_name IN (:serviceNames)');
            $qb->setParameter('serviceNames', array_values($params['serviceNames']));
        }

        if ($params['statusCode']) {
            $qb->andWhere('l.response_code = :responseCode');
            $qb->setParameter('responseCode', $params['statusCode']);
        }
        
        if ($params['startDate']) {
            $qb->andWhere('l.timestamp >= :startDate');
            $qb->setParameter('startDate', $params['startDate']);
        }

        if ($params['endDate']) {
            $qb->andWhere('l.timestamp <= :endDate');
            $qb->setParameter('endDate', $params['endDate']);
        }
        
        return $qb->getQuery()->getSingleScalarResult();
    }
}
