<?php

namespace App\Repositories;

use App\Models\Release;

class ReleaseRepository
{
    public function getAll()
    {
        $selects = [
            'gilvan_santos.user_releases.*',
            'gilvan_santos.users.username',
            'gilvan_santos.transactions.value', 'gilvan_santos.transactions.description'
        ];
        return
            Release::select($selects)
            ->join('gilvan_santos.users', 'gilvan_santos.users.id', '=', 'gilvan_santos.user_releases.user_id')
            ->join('gilvan_santos.transactions', 'gilvan_santos.transactions.id', '=', 'gilvan_santos.user_releases.transaction_id')
            ->get();
    }

    public function create(array $data)
    {
        $new = new Release($data);
        $new->save();
        return $new;
    }
}
