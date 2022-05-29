<?php 

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserRepository {

    private $table = 'gilvan_santos.users';

    public function getAll()
    {
        return DB::table($this->table)->get();
    }

    public function getByUsernameAndPassword(string $username, string $password)
    {
        return DB::table($this->table)
            ->where('username', $username)
            ->where('password', $password)
            ->first();
    }

    public function create($dataArray)
    {
        $new = new User($dataArray);
        $new->save();
        return $new;
    }

    public function update(int $id, $dataArray)
    {
        return DB::table($this->table)
            ->where('id', $id)
            ->update($dataArray);
    }
} 