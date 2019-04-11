<?php

namespace App\Tests\Comptage\Type;

use App\Entity\Comptage\Visiteur;
use App\Form\Comptage\VisiteurType;
use Symfony\Component\Form\Test\TypeTestCase;

class VisiteurTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'formations' => ['DUT Informatique'],
            'porte' => 'Principale (Amphithéatre)',
            'accompagnateur' => 8,
        ];

        $visiteurToCompare = new Visiteur();

        $form = $this->factory->create(VisiteurType::class, $visiteurToCompare);

        $visiteur = new Visiteur();
        $visiteur->setFormations($formData['formations']);
        $visiteur->setPorte($formData['porte']);
        $visiteur->setAccompagnateur($formData['accompagnateur']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($visiteur, $visiteurToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
