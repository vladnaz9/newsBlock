<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, News::class);
    }

    /**
     * @return News[] Returns an array of News objects
     */
    public function findByCategoryId(int $categoryId): array
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.category = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return News[] Returns an array of News objects
     */
    public function findByTags(string $tag): array
    {
        $qb = $this->createQueryBuilder('n');


        $sql = <<<SQL
SELECT n.id,
    tt
FROM news n
    LEFT JOIN LATERAL json_array_elements_text(n.tags) AS tt ON TRUE
WHERE tt :: TEXT = '{$tag}' :: TEXT
SQL;
        $newsIds = $this->getEntityManager()->getConnection()->fetchFirstColumn($sql);

        return $qb
            ->where($qb->expr()->in('n.id', $newsIds))
            ->getQuery()
            ->getResult();

//        return $qb
////            ->Where($qb->expr()->in($qb->expr()->literal($tag),'n.tags'))
////            ->where("tags::JSONB ? $tag")
//            ->where("$literalTag  = any(SELECT news.tags FROM App\Entity\News news)")
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult();
    }


    // /**
    //  * @return News[] Returns an array of News objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?News
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function search(string $strFromSearch)
    {
        $qb = $this->createQueryBuilder('n');
//        $str = $qb->expr()->literal($strFromSearch);

        return $qb
            ->andWhere($qb->expr()->like($qb->expr()->lower('n.title'), $qb->expr()->lower("'%$strFromSearch%'")))
            ->orWhere($qb->expr()->like('n.body', "'%$strFromSearch%'"))
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
