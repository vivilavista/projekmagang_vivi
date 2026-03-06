<?php

namespace App\Repositories;

use App\Models\Tamu;
use App\Repositories\Contracts\TamuRepositoryInterface;

class TamuRepository implements TamuRepositoryInterface
{
    public function __construct(protected Tamu $model)
    {
    }

    public function all(array $filters = [])
    {
        return $this->model->when(isset($filters['search']), function ($q) use ($filters) {
            $q->where('nama', 'like', "%{$filters['search']}%")
                ->orWhere('no_hp', 'like', "%{$filters['search']}%");
        })->get();
    }

    public function findById(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->findById($id);
        $record->update($data);

        return $record->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->findById($id)->delete();
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        return $this->model->when(isset($filters['search']), function ($q) use ($filters) {
            $q->where('nama', 'like', "%{$filters['search']}%")
                ->orWhere('no_hp', 'like', "%{$filters['search']}%");
        })->latest()->paginate($perPage)->withQueryString();
    }
}
