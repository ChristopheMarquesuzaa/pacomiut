<?php

namespace App\Bundle\Extension;

use App\Entity\Departement;
use Doctrine\ORM\EntityManagerInterface;

class GlobalVarExtension extends \Twig\Extension\AbstractExtension implements \Twig\Extension\GlobalsInterface
{
    /**
     * Entity Manager.
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getGlobals()
    {
        return [
         'path' => getenv('APP_URL'),
         'departements' => $this->em->getRepository(Departement::class)->findAll(),
      ];
    }
}
