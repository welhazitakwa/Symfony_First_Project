<?php

namespace App\Controller;

use App\Repository\AeroportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AeroportController extends AbstractController
{
    #[Route('/aeroport', name: 'app_aeroport')]
    public function index(): Response
    {
        return $this->render('aeroport/index.html.twig', [
            'controller_name' => 'AeroportController',
        ]);
    }



    #[Route('/Aero/read',name:"readAero")]
    public function read (AeroportRepository $aeroRepo):Response {
         $aero = $aeroRepo->findAll();
         return $this->render ('Aeroport/read.html.twig', [
            "aero" => $aero ,
         ] ) ;  
    }




    
}
