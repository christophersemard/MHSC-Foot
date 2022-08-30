<?php

namespace App\Repository;

use App\Entity\SinglePage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SinglePage>
 *
 * @method SinglePage|null find($id, $lockMode = null, $lockVersion = null)
 * @method SinglePage|null findOneBy(array $criteria, array $orderBy = null)
 * @method SinglePage[]    findAll()
 * @method SinglePage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SinglePageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SinglePage::class);
    }

    public function add(SinglePage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SinglePage $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findBySlug($value): array
    {
        return $this->createQueryBuilder('sp')
            ->andWhere('sp.slug = :val')
            ->setParameter('val', $value)
            ->orderBy('sp.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }


    // public function findAllByCategory($category): array
    // {
    //     return $this->createQueryBuilder('sp')
    //         ->andWhere('sp.category = :val')
    //         ->setParameter('val', $category)
    //         ->orderBy('sp.id', 'DESC')
    //         ->getQuery()
    //         ->getResult();
    // }

    //    /**
    //     * @return SinglePage[] Returns an array of SinglePage objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SinglePage
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
