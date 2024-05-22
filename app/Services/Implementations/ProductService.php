<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Implementations\ProductRepository;
use App\Services\Contracts\ProductServiceInterface;

class ProductService implements ProductServiceInterface
{
    public function __construct(protected ProductRepository $productRepository)
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

    public function delete($id): bool
    {
        $product = $this->productRepository->show($id);
        return $this->productRepository->destroy($id);
    }

    public function all()
    {
        return $this->productRepository->index();
    }

    public function find($id)
    {
        return $this->productRepository->show($id);
    }
}
