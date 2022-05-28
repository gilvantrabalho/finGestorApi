<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{

    public function getAllByUserId(int $user_id)
    {
        return Transaction::where('user_id', $user_id)
            ->where('status', config('constants.TRANSACTION.ATIVO'))
            ->get();
    }

    public function create($dataTransaction)
    {
        $new = new Transaction($dataTransaction);
        $new->save();
        return $new;
    }

    public function disable(int $id)
    {
        return Transaction::where('id', $id)
            ->update([
                'status' => config('constants.TRANSACTION.INATIVO')
            ]);
    }

    public function getById(int $id)
    {
        return Transaction::where('id', $id)->first();
    }

    public function updateData(int $id, array $arrayData)
    {
        return Transaction::where('id', $id)
            ->update($arrayData);
    }

    public function filterByDescription(int $user_id, string $description)
    {
        return Transaction::where('user_id', $user_id)
            ->where('status', config('constants.TRANSACTION.INATIVO'))
            ->where('description', 'like', "{$description}%")->get();
    }
}
