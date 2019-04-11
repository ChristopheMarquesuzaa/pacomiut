<?php

namespace App\DataFixtures;

use App\Entity\Departement;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $departement = new Departement();
        $departement->setName('Informatique');
        $departement->setShortname('info');
        $manager->persist($departement);

        $departement = new Departement();
        $departement->setName('Techniques de commercialisation');
        $departement->setShortname('tc');
        $manager->persist($departement);

        $departement = new Departement();
        $departement->setName('Gestion des Entreprises et des Administrations');
        $departement->setShortname('gea');
        $manager->persist($departement);

        $departement = new Departement();
        $departement->setName('Génie industriel et maintenance');
        $departement->setShortname('gim');
        $manager->persist($departement);

        /**
         * Création du compte admin.
         */
        $admin = new User();
        $admin->setEmail('admin@admin.admin');
        $admin->setUsername('Admin');
        $admin->setFirstname('Admin');
        $admin->setSurname('Admin');
        $admin->setPassword('$2y$15$8KReq0wptYA9OFWdwssbHuwuA2T/8ISBhj0V491/SdMP00mSSvJ8m');
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();
    }
}
