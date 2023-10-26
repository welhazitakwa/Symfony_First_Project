<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
  private  $authors = array(
array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>
'victor.hugo@gmail.com ', 'nb_books' => 100),
array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
' william.shakespeare@gmail.com', 'nb_books' => 200 ),
array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
'taha.hussein@gmail.com', 'nb_books' => 300),
);

    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    #[Route('/Author/list', name : "list")]
    public function list ():Response{
       
        return $this->render('Author/list.html.twig', [
            'authors' =>$this->authors,
        ]);
        
    }

    
    // #[Route('/Author/show/{name}', name : "show")]
    //  public function showAuthor ($name):Response{
       
    //     return $this->render('Author/show.html.twig', [
    //         'name' => $name,
    //     ]);
        
    // }

    #[Route('/Author/show/details/{id}', name : "showDetails")]
     public function auhtorDetails ($id):Response{
       
        return $this->render('Author/show.html.twig', [
            'id' => $id,
            'authors' =>$this->authors,
        ]);
        
    }
    #[Route('/Author/read',name:"read")]
    public function read (AuthorRepository $authorRepo):Response {
         $author = $authorRepo->findAll();
         return $this->render ('Author/read.html.twig', [
            "author" => $author ,
         ] ) ;  
    }
    #[Route('/Author/AddStatic', name:"addStatic")]
    public function addStatic(ManagerRegistry $doctrine): Response{
          $em = $doctrine->getManager();
          $author = new Author();
          $author->setEmail("testAddStatic@gmail.com");
          $author->setUsername("Add Static");
          $em->persist($author);
          $em->flush();
          return $this->redirectToRoute("read");
    }
    #[Route('/Author/Add', name:"add")]
    public function add(ManagerRegistry $doctrine , Request $request): Response{
          $em = $doctrine->getManager();
          $author = new Author();
          $form= $this->createForm(AuthorType::class, $author);
          $form ->handleRequest($request);
            if ($form->isSubmitted()) {
                 $em->persist($author);
                 $em->flush();
                 return $this->redirectToRoute("read");
            } else {
            return $this->renderForm("Author/create.html.twig", [
            "form" => $form ,
         ]);
          }  
    }

    #[Route('/Author/delete/{id}', name:"author_delete")]
    public function delete (ManagerRegistry $doctrine , $id, AuthorRepository $authorRepo) : Response {
        $em = $doctrine->getManager();
        $authorDel= $authorRepo->find($id);
        $em->remove($authorDel);
        $em->flush();

        return $this->redirectToRoute("read");
    }
    
    #[Route('/Author/deletePub0', name:"supp_pub0")]
    public function deletePub0 (ManagerRegistry $doctrine , AuthorRepository $authorRepo) : Response {
        $em = $doctrine->getManager();
        $author= $authorRepo->findAll();
        for ($i = 0; $i < count($author); $i++) {
            if ( $author[$i]->getNbBooks() == 0) {$em->remove($author[$i]);}
        }
        
        //$em->remove($authorDel);
        $em->flush();

        return $this->redirectToRoute("read");
    }
    
    #[Route('/Author/Edit/{id}', name: 'author_edit')]
    public function edit(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $em = $doctrine->getManager();
        $author = $em->getRepository(Author::class)->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->flush(); 
            return $this->redirectToRoute('read'); 
        }
        return $this->renderForm('Author/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/Author/Tri', name: 'author_tri')]
    public function getTri(AuthorRepository $authorRepo):Response {
         $author = $authorRepo->triQB();
         return $this->render ('Author/read.html.twig', [
            "author" => $author ,
         ] ) ;  
    }
    #[Route('/Author/TriDQL', name: 'author_tridql')]
    public function getTriDQL(AuthorRepository $authorRepo):Response {
         $author = $authorRepo->triDQL();
         return $this->render ('Author/read.html.twig', [
            "author" => $author ,
         ] ) ;  
    }

}
