<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(
        protected UserRepositoryInterface $repo,
        protected AuditLogService $auditLog
    ) {
    }

    public function paginate(int $perPage = 15, array $filters = [])
    {
        return $this->repo->paginate($perPage, $filters);
    }

    public function findById(int $id)
    {
        return $this->repo->findById($id);
    }

    public function create(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->repo->create($data);
        $this->auditLog->log('Create User: ' . $user->username, 'users', $user->id);

        return $user;
    }

    public function update(int $id, array $data)
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user = $this->repo->update($id, $data);
        $this->auditLog->log('Update User: ' . $user->username, 'users', $user->id);

        return $user;
    }

    public function delete(int $id): bool
    {
        $user = $this->repo->findById($id);
        $this->auditLog->log('Delete User: ' . $user->username, 'users', $user->id);

        return $this->repo->delete($id);
    }
}
