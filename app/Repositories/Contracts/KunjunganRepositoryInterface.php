<?php

namespace App\Repositories\Contracts;

interface KunjunganRepositoryInterface
{
    public function all(array $filters = []);

    public function findById(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id): bool;

    public function paginate(int $perPage = 15, array $filters = []);

    public function checkout(int $id): bool;

    public function countByStatus(string $status): int;

    public function countToday(): int;
}
