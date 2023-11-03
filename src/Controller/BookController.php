<?php

namespace App\Controller;

use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Form\SearchType;
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
         $book = $bookRepo->findBy(['published' => true]);
         $published =0;
         $unpublished =0;
         for ($i=0; $i<count($book); $i++) {
            if ($book[$i]->isPublished() == true ) {
                $published = $published + 1 ;}
            
            if ($book[$i]->isPublished() == false ){
                $unpublished = $unpublished +1 ;
            }
         }
         
         return $this->render ('Book/listBooks.html.twig', [
            "book" => $book ,
            "published" => $published,
            "unpublished" => $unpublished
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

    #[Route('/Book/Edit/{id}', name: 'book_edit')]
    public function edit(ManagerRegistry $doctrine, Request $request, $id): Response
    {
        $em = $doctrine->getManager();
        $book = $em->getRepository(Book::class)->find($id);
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->flush(); 
            return $this->redirectToRoute('listBooks'); 
        }
        return $this->renderForm('Book/edit.html.twig', [
            'form' => $form,
        ]);
    }

   #[Route('/Book/show/details/{id}', name : "showDetails")]
     public function auhtorDetails ($id, BookRepository $bookRepo):Response{
       $book = $bookRepo->findBy(['published' => true]);
        return $this->render('Book/detailsBook.html.twig', [
            'id' => $id,
            'book' => $book,
        ]);
        
    }

    #[Route('/Book/search', name : "searchBook")]
    public function searchBook (BookRepository $bookRepo ,Request $request  ): Response{
        $book = new Book();
        $form = $this->createForm(SearchType::class, $book);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            // $title= $form->get('title')->getData();
            $title = $book->getTitle();
            $books = $bookRepo->search($title);
            return $this->render('book/search.html.twig',[
                'books' => $books,
                'form' => $form->createView(),
            ]) ;
        } else {
                // return $this-> redirectToRoute('listBooks');
                return $this->render('book/search.html.twig',[
                'form' => $form->createView(),
                'books'=> $bookRepo->findAll(),
            ]) ;
            }
    }

    #[Route('/Book/getByCatRomance', name:'getByCatRomance')]
    public function getByCategory(BookRepository $bookRepo):Response {
        $book = $bookRepo->getByCategory();
        return $this->render('Book/listByRomance.html.twig',[
            "book"=>$book,
        ]);
    }

}
