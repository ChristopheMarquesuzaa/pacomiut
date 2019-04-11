<?php

namespace App\Repository\Comptage;

use App\Entity\Comptage\Visiteur;
use App\Entity\Formulaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Visiteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visiteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visiteur[]    findAll()
 * @method Visiteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisiteurRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Visiteur::class);
    }

    public function countEtudiant(Formulaire $formulaire)
    {
        return $this->createQueryBuilder('l')
                    ->select('COUNT(l)')
                    ->where('l.formulaire=:formulaire')
                    ->setParameter('formulaire', $formulaire)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function countAccompagnateur(Formulaire $formulaire)
    {
        return $this->createQueryBuilder('l')
                    ->where('l.formulaire=:formulaire')
                    ->setParameter('formulaire', $formulaire)
                    ->select('SUM(l.accompagnateur) as accompagnateur')
                    ->getQuery()
                    ->getSingleScalarResult();
    }

    public function countByPorte(Formulaire $formulaire)
    {
        return $this->createQueryBuilder('l')
            ->where('l.formulaire=:formulaire')
            ->setParameter('formulaire', $formulaire)
            ->select('COUNT(l)')
            ->groupBy('l.porte')
            ->getQuery()
            ->getScalarResult();
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
//     * @return Visiteur[] Returns an array of Visiteur objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Visiteur
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
