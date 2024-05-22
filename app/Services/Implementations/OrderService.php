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
    public function create(array $data)
    {
        // TODO: Implement create() method.
    }

    public function update(array $data, $id)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function all()
    {
        // TODO: Implement all() method.
    }

    public function find($id)
    {
        return $this->repository->show($id);
    }
}
