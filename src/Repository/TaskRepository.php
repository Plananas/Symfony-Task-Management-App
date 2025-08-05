<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Carbon\CarbonImmutable;

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
        $this->_em->persist($task);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function remove(Task $task, bool $flush = true): void
    {
        $this->_em->remove($task);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function softDelete(Task $task, bool $flush = true): void
    {
        $now = CarbonImmutable::now();
        $task->setDeletedAt($now);
        $task->setUpdatedAt($now);

        if ($flush) {
            $this->_em->flush();
        }
    }

    public function toggle(Task $task, bool $flush = true): void
    {
        $task->setIsDone(!$task->isDone());
        $task->setUpdatedAt(CarbonImmutable::now());

        if ($flush) {
            $this->_em->flush();
        }
    }
}
