<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Contact>
 *
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

    //    /**
    //     * @return Contact[] Returns an array of Contact objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Contact
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    public function paginate(int $page, int $limit = 4): array
    {
        $limit = abs($limit);
        $page = abs($page);
        $result = [];

        $contacts = $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($contacts);

        $data = $paginator->getIterator()->getArrayCopy(); // Utilisez getIterator() pour obtenir les entités paginées sous forme de tableau

        if (empty($data)) {
            return $result;
        }

        $pages = ceil($paginator->count() / $limit);

        $result = [
            'data' => $data, // Utilisez les entités paginées récupérées du Paginator
            'pages' => $pages,
            'page' => $page,
            'limit' => $limit
        ];

        return $result;
    }

    
}
