<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

//    /**
//     * @return Book[] Returns an array of Book objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('b.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Book
//    {
//        return $this->createQueryBuilder('b')
//            ->andWhere('b.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function search ($title){
    $req = $this->createQueryBuilder('b')
                ->where('b.title LIKE :title')
                ->setParameter('title', '%'.$title.'%')
                ->getQuery()
                ->getResult();
        return $req ;
}

public function getByCategory (){

    $em = $this->getEntityManager();
    $category = 'Romance';
    $req= $em->createQuery('select b from App\Entity\Book b where b.category = :category')
                ->setParameter("category", $category);
     $nbLivRomance= $req->getSingleScalarResult();

    return $req->getResult();
}
public function getnbrLivRomance (){

    $em = $this->getEntityManager();
    $category = 'Romance';
    $req= $em->createQuery('select b from App\Entity\Book b where b.category = :category')
             ->setParameter("category", $category);
    $nbLivRomance= $req->getScalarResult();

    return $nbLivRomance;
}
public function getByDateR (){
    $em = $this->getEntityManager();
    $startDate = '2014-01-01';
    $endDate = '2018-12-31';
    $req = $em->createQuery('select b from App\Entity\Book b where b.publicationDate between :startDate and :endDate')
    ->setParameter("startDate",$startDate)
    ->setParameter("endDate",$endDate);
    return $req->getResult();
}
}
