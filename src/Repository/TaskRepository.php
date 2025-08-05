<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Task>
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findAllActive(): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.deleted_at IS NULL')
            ->orderBy('t.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function save(Task $task, bool $flush = true): void
    {
        $this->getEntityManager()->persist($task);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Task $task, bool $flush = true): void
    {
        $this->getEntityManager()->remove($task);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function softDelete(Task $task, bool $flush = true): void
    {
        $now = new \DateTimeImmutable();
        $task->setDeletedAt($now);
        $task->setUpdatedAt($now);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function toggle(Task $task, bool $flush = true): void
    {
        $task->setIsDone(!$task->isDone());
        $task->setUpdatedAt(new \DateTimeImmutable());

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
