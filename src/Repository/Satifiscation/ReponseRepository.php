<?php

namespace App\Repository\Satifiscation;

use App\Entity\Formulaire;
use App\Entity\Satifiscation\Reponse;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Reponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reponse[]    findAll()
 * @method Reponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReponseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Reponse::class);
    }

    public function countReponse(Formulaire $formulaire)
    {
        return $this->createQueryBuilder('r')
                    ->select('COUNT(r)')
                    ->where('r.formulaire=:formulaire')
                    ->setParameter('formulaire', $formulaire)
                    ->getQuery()
                    ->getSingleScalarResult();
    }

}
