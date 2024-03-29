<?php

namespace App\Repository;

use App\Entity\Media;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findAll(): array
    {
        return $this->findBy([], ['id' => 'DESC']);
    }

    public function findAllPublished(): mixed
    {
        return $this->createQueryBuilder('p')
                     ->where('p.published IS NOT NULL')
                     ->where('p.published < :now')
                     ->setParameter('now', new \DateTime())
                     ->orderBy('p.id', 'DESC')
                     ->getQuery()
                     ->getResult();
    }

    public function findByMedia(Media $media): mixed
    {
        return $this->createQueryBuilder('p')
                     ->andWhere('p.featureImage = :media')
                     ->orWhere('p.content LIKE :filename')
                     ->setParameter('media', $media)
                     ->setParameter('filename', '%'.$media->getFileName().'%')
                     ->orderBy('p.id', 'DESC')
                     ->getQuery()
                     ->getResult();
    }
}
