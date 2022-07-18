<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminBookController extends AbstractController
{
    /**
     * @Route("/admin/books",name="admin_books")
     */

    public function showBooks(BookRepository $bookRepository){
    $books = $bookRepository->findAll();
    return $this -> render('admin/books.html.twig',[
        "books" => $books
    ]);
    }

    /**
     * @Route("/admin/book/{id}", name="admin_book")
     */

    public function showBook($id, BookRepository  $bookRepository){
        $book= $bookRepository->find($id);

        return $this ->render('admin/book.html.twig', [
            "book" => $book
        ]);
    }

    /**
     * @Route("/admin/book/delete/{id}", name="admin_delete_book")
     */

    public function deleteBook($id, BookRepository  $bookRepository, EntityManagerInterface $entityManager){
        $book = $bookRepository->find($id);

        if(!is_null($book)){
            $entityManager->remove($book);
            $entityManager->flush();
        }
        return $this->redirectToRoute('admin_books');

    }

    /**
     * @Route ("/admin/book-insert", name="admin_insert_book")
     */

    public function insertBook(EntityManagerInterface  $entityManager, Request $request){
        $book = new Book();

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($book);
            $entityManager->flush();
        }

        return $this -> render('admin/insert_book.html.twig', [
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/admin/book-update{id}", name="admin_update_book")
     */

    public function updateBook($id, BookRepository $bookRepository, EntityManagerInterface  $entityManager, Request $request){
        $book = $bookRepository->find($id);
        $form = $this->createForm(BookType::class,$book);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($book);
            $entityManager->flush();
        }

        return $this->render("admin/update_book.html.twig", [
            "form"=>$form->createView()
        ]);
    }
}