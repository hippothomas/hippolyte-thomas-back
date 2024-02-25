<?php

namespace App\Repository;

use App\Entity\ApiKey;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApiKey>
 *
 * @method ApiKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiKey[]    findAll()
 * @method ApiKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiKeyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiKey::class);
    }

    /**
     * Find all API key related to a user.
     *
     * @return ApiKey[]
     */
    public function findAllByUser(User $user): array
    {
        return $this->findBy([
            'account' => $user,
        ], [
            'id' => 'DESC',
        ]);
    }

    /**
     * Update the last accessed date.
     */
    public function updateLastAccessed(ApiKey $api_key): void
    {
        $api_key->setLastAccessed(new \DateTime());
        $this->getEntityManager()->persist($api_key);
        $this->getEntityManager()->flush();
    }
}
