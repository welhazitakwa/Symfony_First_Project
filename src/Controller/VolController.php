<?php

namespace App\Controller;

use App\Entity\Vol;
use App\Form\VolType;
use App\Repository\VolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class VolController extends AbstractController
{
    #[Route('/vol', name: 'app_vol')]
    public function index(): Response
    {
        return $this->render('vol/index.html.twig', [
            'controller_name' => 'VolController',
        ]);
    }


    #[Route('/Vol/read',name:"readVol")]
    public function read (VolRepository $volRepo):Response {
         $vol = $volRepo->findAll();
         return $this->render ('Vol/read.html.twig', [
            "vols" => $vol ,
         ] ) ;  
    }

    
    #[Route('/Vol/Add', name:"add_vol")]
         public function add(ManagerRegistry $doctrine , Request $request): Response{
          $em = $doctrine->getManager();
          $vol = new Vol();
          $form= $this->createForm(VolType::class, $vol);
          $form ->handleRequest($request);
            if ($form->isSubmitted()) {
                 $aero = $vol->getAeroport(); 
                 $aero->setNbVols($aero->getNbVols() + 1);
                 $em->persist($vol);
                 $em->flush();
                 return $this->redirectToRoute("readVol");
            } else {
            return $this->renderForm("Vol/create.html.twig", [
            "form" => $form ,
         ]);
          }  
    }
}
