<?php

namespace App\Repository;

use App\Entity\DocumentUpload;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentUpload|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentUpload|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentUpload[]    findAll()
 * @method DocumentUpload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentUploadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentUpload::class);
    }

    // /**
    //  * @return DocumentUpload[] Returns an array of DocumentUpload objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocumentUpload
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
