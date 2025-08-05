<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Carbon\CarbonImmutable;

#[Route('/tasks')]
final class TaskController extends AbstractController
{
    #[Route('', name: 'tasks_index', methods: ['GET'])]
    public function index(TaskRepository $taskRepository): JsonResponse
    {
        $tasks = $taskRepository->findAllActive();

        $data = array_map(fn(Task $task) => [
            'id' => $task->getId(),
            'title' => $task->getTitle(),
            'isDone' => $task->isDone(),
            'created_at' => $task->getCreatedAt()?->format('Y-m-d H:i:s'),
            'updated_at' => $task->getUpdatedAt()?->format('Y-m-d H:i:s'),
            'deleted_at' => $task->getDeletedAt()?->format('Y-m-d H:i:s'),
        ], $tasks);

        return $this->json($data);
    }

    #[Route('', name: 'tasks_create', methods: ['POST'])]
    public function create(Request $request, TaskRepository $repository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'] ?? null;

        if (!$title) {
            return $this->json(['error' => 'Title is required'], 400);
        }

        $now = CarbonImmutable::now();
        $task = (new Task())
            ->setTitle($title)
            ->setIsDone(false)
            ->setCreatedAt($now)
            ->setUpdatedAt($now);

        $repository->save($task);

        return $this->json(['message' => 'Task created', 'id' => $task->getId()], 201);
    }

    #[Route('/{id}/toggle', name: 'tasks_toggle', methods: ['PATCH'])]
    public function toggle(int $id, TaskRepository $repository): JsonResponse
    {
        $task = $repository->find($id);

        if (!$task || $task->getDeletedAt()) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $repository->toggle($task);

        return $this->json([
            'message' => 'Task toggled',
            'isDone' => $task->isDone(),
        ]);
    }

    #[Route('/{id}', name: 'tasks_delete', methods: ['DELETE'])]
    public function delete(int $id, TaskRepository $repository): JsonResponse
    {
        $task = $repository->find($id);

        if (!$task || $task->getDeletedAt()) {
            return $this->json(['error' => 'Task not found'], 404);
        }

        $repository->softDelete($task);

        return $this->json(['message' => 'Task deleted']);
    }
}
