<?php

namespace App\Repositories;

use App\Repositories\BaseRepository;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    /**
     * @param array $data
     * @return \Illuminate\Support\Collection|null
     */
    public function store(array $data = [])
    {
        DB::beginTransaction();

        try {
            $this->model->name = $data['name'] ?? null;
            $this->model->email = $data['email'] ?? null;
            $this->model->password = bcrypt($data['password']) ?? null;

            $this->model->save();

            $token = $this->model->createToken('myapptoken')->plainTextToken;

        } catch (Exception $e) {
            DB::rollBack();

            return null;
        }

        DB::commit();
        return collect(['user' => $this->model, 'token' => $token]);
    }

    /**
     * @param array $data
     * @return \Illuminate\Support\Collection|null
     */
    public function login(array $data = [])
    {
        try {
            $user = $this->model->where('email', $data['email'])->first();

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return null;
            }

            $token = $user->createToken('myapptoken')->plainTextToken;

        } catch (Exception $e) {

            return null;
        }

        return collect(['user' => $user, 'token' => $token]);
    }

}
