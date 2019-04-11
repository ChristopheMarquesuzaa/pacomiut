<?php

namespace App\Tests\Formulaire\Type;

use App\Entity\Formulaire\Form;
use App\Form\Formulaire\FormType;
use Symfony\Component\Form\Test\TypeTestCase;

class FormTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'sexe' => true,
            'age' => '48',
            'type' => 'eleve',
            'departement' => 'Gironde',
            'ville' => 'Gujan-Mestras',
            'ecole' => 'Lycée de la mer',
            'formation' => 'terminale',
            'motifs' => ['rencontrer_enseignants', 'rencontrer_etudiants'],
            'provenances' => ['forum'],
            'qualite' => 4,
            'utile' => true,
            'candidat' => true,
            'autre' => 'D\'autres remarques ?',
        ];

        $formToCompare = new Form();

        $form = $this->factory->create(FormType::class, $formToCompare);

        $f_form = new Form();
        $f_form->setSexe($formData['sexe']);
        $f_form->setAge($formData['age']);
        $f_form->setType($formData['type']);
        $f_form->setDepartement($formData['departement']);
        $f_form->setVille($formData['ville']);
        $f_form->setEcole($formData['ecole']);
        $f_form->setFormation($formData['formation']);
        $f_form->setMotifs($formData['motifs']);
        $f_form->setProvenances($formData['provenances']);
        $f_form->setQualite($formData['qualite']);
        $f_form->setUtile($formData['utile']);
        $f_form->setCandidat($formData['candidat']);
        $f_form->setAutre($formData['autre']);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($f_form, $formToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
