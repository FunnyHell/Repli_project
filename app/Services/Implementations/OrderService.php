<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Implementations\OrderRepository;
use App\Services\Contracts\OrderServiceInterface;

class OrderService implements OrderServiceInterface
{
    public function __construct(protected OrderRepository $repository)
    {
    }
    public function create(array $data, $imagePath = null)
    {
        return $this->repository->create($data);
    }

    public function update(array $data, $id, $imagePath = null)
    {
        return $this->repository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->repository->destroy($id);
    }

    public function all()
    {
        return $this->repository->index();
    }

    public function find($id)
    {
        return $this->repository->show($id);
    }
}
