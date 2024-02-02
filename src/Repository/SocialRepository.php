<?php

namespace App\Repository;

use App\Entity\Social;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Social>
 *
 * @method Social|null find($id, $lockMode = null, $lockVersion = null)
 * @method Social|null findOneBy(array $criteria, array $orderBy = null)
 * @method Social[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Social::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['id' => 'DESC']);
    }

    public function save(Social $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Social $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
