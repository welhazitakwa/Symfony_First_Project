<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }



       #[Route('/Book/list', name:"listBooks" )]
    public function read (BookRepository $bookRepo):Response {
         $book = $bookRepo->findAll();
         return $this->render ('Book/listBooks.html.twig', [
            "book" => $book ,
         ] ) ;  
    }



        #[Route('/Book/Add', name:"add_book")]
         public function add(ManagerRegistry $doctrine , Request $request): Response{
          $em = $doctrine->getManager();
          $book = new Book();
          $form= $this->createForm(BookType::class, $book);
          $form ->handleRequest($request);
            if ($form->isSubmitted()) {
                 $book->setPublished( true);
                 $author = $book->getAuthor(); 
                 $author->setNbBooks($author->getNbBooks() + 1);
                 $em->persist($book);
                 $em->flush();
                 return $this->redirectToRoute("listBooks");
            } else {
            return $this->renderForm("Book/createBook.html.twig", [
            "form" => $form ,
         ]);
          }  
    }

    #[Route('/Book/delete/{id}', name:"book_delete")]
    public function delete (ManagerRegistry $doctrine , $id, BookRepository $bookRepo) : Response {
        $em = $doctrine->getManager();
        $bookDel= $bookRepo->find($id);
        $em->remove($bookDel);
        $em->flush();

        return $this->redirectToRoute("listBooks");
    }




}
