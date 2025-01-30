<?php 

namespace App\Repository\Services;

use App\Repository\Interfaces\TaskRepositoryInterface;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getTasks(array $data)
    {
        return $this->taskRepository->getTasks($data);
    }

    public function show($id)
    {
        return $this->taskRepository->show($id);
    }

    public function store(array $data)
    {
        return $this->taskRepository->store($data);
    }

    public function update(array $data, $id)
    {
        return $this->taskRepository->update($data, $id);
    }

    public function destroy($id)
    {
        return $this->taskRepository->destroy($id);
    }

}