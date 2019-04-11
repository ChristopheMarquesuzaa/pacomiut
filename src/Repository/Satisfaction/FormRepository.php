<?php

namespace App\Repository\Satisfaction;

use App\Entity\Satisfaction\Form;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Form|null find($id, $lockMode = null, $lockVersion = null)
 * @method Form|null findOneBy(array $criteria, array $orderBy = null)
 * @method Form[]    findAll()
 * @method Form[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Form::class);
    }

    public function findCompterReponses($dpt)
    {
        return $this->createQueryBuilder('r')
                    ->select('COUNT(r)')
                    ->where('r.dpt=:dpt')
                    ->setParameter('dpt', $dpt)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function findDpt($dpt)
    {
        return $this->createQueryBuilder('d')
                    ->where('d.dpt=:dpt')
                    ->setParameter('dpt', $dpt)
                    ->getQuery()
                    ->getResult();
    }

//    /**
//     * @return Form[] Returns an array of Form objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Form
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
