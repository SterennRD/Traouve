<?php


namespace App\Repository;


use App\Entity\Status;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\Item;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findLastStatus (string $status, int $limit = null) : array
    {
        $qb = $this->createQueryBuilder('i');

        $qb = $qb->innerJoin('i.status', 's')
            ->where($qb->expr()->eq('s.label', ":status"))
            ->orderBy('i.createdAt', 'DESC')
            ->setMaxResults($limit);

        return $qb->setParameter('status', $status)
                ->getQuery()
            ->getResult();
    }

    public function findByCity (int $cityId) : array
    {
        $qb = $this->createQueryBuilder('i');

        $qb = $qb->where($qb->expr()->eq('i.city', $cityId));

        return $qb
            ->getQuery()
            ->getResult();
    }
}