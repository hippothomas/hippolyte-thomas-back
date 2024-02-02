<?php

namespace App\Repository;

use App\Entity\AboutMe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AboutMe>
 *
 * @method AboutMe|null find($id, $lockMode = null, $lockVersion = null)
 * @method AboutMe|null findOneBy(array $criteria, array $orderBy = null)
 * @method AboutMe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AboutMeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AboutMe::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['id' => 'DESC']);
    }

    public function save(AboutMe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(AboutMe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
