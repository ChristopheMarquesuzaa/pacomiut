<?php

namespace App\Controller\Tutorat;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TutoratController extends AbstractController
{
    /**
     * @Route("/tutorat/tutorat", name="tutorat")
     */
    public function index()
    {
        return $this->render('tutorat/index.html.twig');
    }
}
