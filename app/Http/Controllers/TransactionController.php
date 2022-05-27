<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TransactionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;    
    }

    public function show(int $user_id): JsonResponse
    {
        return response()->json([
            'response' => [
                'transactions' => $this->transactionRepository->getAllByUserId($user_id)
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->only('user_id', 'type', 'description', 'value');
        
        $validator = Validator::make($data, [
            'user_id' => 'required',
            'type' => 'string|required',
            'description' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'response' => [
                    'erros' => $validator->errors()
                ]
            ]);
        }

        $dataTransaction = [
            'user_id' => $request->user_id,
            'type' => $request->type,
            'description' => $request->description,
            'value' => $request->value,
            'status' => config('constants.TRANSACTION.ATIVO'),
            'created_at' => date('Y-m-d H:i:d'),
            'updated_at' => date('Y-m-d H:i:d')
        ];

        $newTransaction = $this->transactionRepository->create($dataTransaction);

        if (!$newTransaction) {
            return response()->json([
                'error' => true,
                'response' => [
                    'message' => 'Transação não cadastrada!'
                ]
            ]);
        }

        return response()->json([
            'error' => false,
            'response' => [
                'message' => 'Transação cadastrada com sucesso!',
                'transaction' => $newTransaction
            ]
        ]);
    }

    public function update(int $id): JsonResponse
    {
        if (!$id) {
            return response()->json([
                'error' => true,
                'response' => [
                    'message' => 'Informe o id'
                ]
            ]);
        }

        if (!$this->transactionRepository->disable($id)) {
            return response()->json([
                'error' => true,
                'response' => [
                    'message' => 'Erro ao tentar mudar o status da transação!'
                ]
            ]);
        }

        return response()->json([
            'error' => false,
            'response' => [
                'message' => 'Transação desativada com sucesso!'
            ]
        ]);
    }

    public function getById(int $id): JsonResponse
    {
        if (!$id) {
            return response()->json([
                'error' => true,
                'response' => [
                    'message' => 'Informe o id'
                ]
            ]);
        }

        return response()->json([
            'error' => false,
            'response' => [
                'transaction' => $this->transactionRepository->getById($id)
            ]
        ]);
    }

    public function updateData(Request $request, int $id): JsonResponse
    {
        if (!$id) {
            return response()->json([
                'error' => true,
                'response' => [
                    'message' => 'Informe o id'
                ]
            ]);
        }

        $data = $request->only('type', 'description', 'value');
        
        $validator = Validator::make($data, [
            'type' => 'string|required',
            'description' => 'required',
            'value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'response' => [
                    'erros' => $validator->errors()
                ]
            ]);
        }

        $dataTransaction = [
            'type' => $request->type,
            'description' => $request->description,
            'value' => $request->value,
            'updated_at' => date('Y-m-d H:i:d')
        ];

        if (!$this->transactionRepository->updateData($id, $dataTransaction)) {
            return response()->json([
                'error' => true,
                'response' => [
                    'erros' => $validator->errors()
                ]
            ]);
        }

        return response()->json([
            'error' => false,
            'response' => [
                'message' => 'Transação editada com sucesso!'
            ]
        ]);
    }
}
