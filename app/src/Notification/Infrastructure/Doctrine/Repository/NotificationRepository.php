<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Doctrine\Repository;

use App\Notification\Domain\Repository\NotificationStoreInterface;
use App\Notification\Infrastructure\Doctrine\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Notification>
 */
final class NotificationRepository extends ServiceEntityRepository implements NotificationStoreInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function save(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Notification $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function store(Notification $notification): void
    {
        $this->save($notification, true);
    }

    public function getById(Uuid $id): Notification
    {
        $notification = $this->find($id);

        if (null === $notification) {
            throw new \RuntimeException('No entity with this id found.');
        }

        return $notification;
    }

    public function countNotificationInLastHourByReceiver(Uuid $receiverId): int
    {
        $date = new \DateTime();
        $date->modify('-1hour');

        $queryResult = $this->createQueryBuilder('notification')
            ->select(
                'COUNT(notification.id)'
            )
            ->where('notification.createdAt > :date')
            ->andWhere('notification.receiver = :receiver')
            ->setParameters([
                'date' => $date,
                'receiver' => $receiverId->toBinary(),
            ])
            ->getQuery()
            ->getSingleScalarResult();

        return $queryResult;
    }
}
