<?php

namespace App\Repositories;

use App\Models\Kunjungan;
use App\Repositories\Contracts\KunjunganRepositoryInterface;
use Carbon\Carbon;

class KunjunganRepository implements KunjunganRepositoryInterface
{
    public function __construct(protected Kunjungan $model)
    {
    }

    public function all(array $filters = [])
    {
        return $this->model->with(['tamu', 'operator'])
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $q->whereHas('tamu', function ($q2) use ($filters) {
                    $q2->where('nama', 'like', "%{$filters['search']}%");
                })->orWhere('tujuan', 'like', "%{$filters['search']}%");
            })
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->latest()
            ->get();
    }

    public function findById(int $id)
    {
        return $this->model->with(['tamu', 'operator'])->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data)
    {
        $record = $this->model->findOrFail($id);
        $record->update($data);

        return $record->fresh();
    }

    public function delete(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        return $this->model->with(['tamu', 'operator'])
            ->when(isset($filters['search']), function ($q) use ($filters) {
                $q->whereHas('tamu', function ($q2) use ($filters) {
                    $q2->where('nama', 'like', "%{$filters['search']}%");
                })->orWhere('tujuan', 'like', "%{$filters['search']}%");
            })
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['operator_id']), fn($q) => $q->where('operator_id', $filters['operator_id']))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function checkout(int $id): bool
    {
        return $this->model->findOrFail($id)->update([
            'jam_keluar' => Carbon::now(),
            'status' => 'Selesai',
        ]);
    }

    public function countByStatus(string $status): int
    {
        return $this->model->where('status', $status)->count();
    }

    public function countToday(): int
    {
        return $this->model->whereDate('jam_masuk', Carbon::today())->count();
    }
}
