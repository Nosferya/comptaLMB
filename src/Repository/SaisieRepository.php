<?php

namespace App\Repository;

use App\Entity\Saisie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Saisie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Saisie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Saisie[]    findAll()
 * @method Saisie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaisieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Saisie::class);
    }

//    /**
//     * @return Saisie[] Returns an array of Saisie objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Saisie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
