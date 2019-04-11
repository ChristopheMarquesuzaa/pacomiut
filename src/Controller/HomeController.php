<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/dpt{dpt}", name="dptInfo")
     */
    public function dptInfo($dpt)
    {
        return $this->render('home/indexInfo.html.twig',[
            'dpt' => $dpt,
        ]);
    }
}
