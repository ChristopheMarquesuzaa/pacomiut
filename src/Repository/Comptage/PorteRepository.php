<?php

namespace App\Repository\Comptage;

use App\Entity\Comptage\Porte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Porte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Porte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Porte[]    findAll()
 * @method Porte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PorteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Porte::class);
    }

    // /**
    //  * @return Porte[] Returns an array of Porte objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Porte
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
