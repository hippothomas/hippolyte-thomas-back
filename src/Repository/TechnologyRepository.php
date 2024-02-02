<?php

namespace App\Repository;

use App\Entity\Technology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Technology>
 *
 * @method Technology|null find($id, $lockMode = null, $lockVersion = null)
 * @method Technology|null findOneBy(array $criteria, array $orderBy = null)
 * @method Technology[]    findAll()
 * @method Technology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TechnologyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Technology::class);
    }

    public function save(Technology $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Technology $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
