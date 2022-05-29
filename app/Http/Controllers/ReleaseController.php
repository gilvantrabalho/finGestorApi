<?php

namespace App\Http\Controllers;

use App\Models\Release;
use App\Repositories\ReleaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReleaseController extends Controller
{
    private $releaseRepository;

    public function __construct(ReleaseRepository $releaseRepository)
    {
        $this->releaseRepository = $releaseRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'error' => false,
            'response' => [
                'releases' => $this->releaseRepository->getAll(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // id, user_id, transaction_id, operation_type, created_at
        try {
            $credentials = $request->only('user_id', 'transaction_id', 'operation_type');
            $validator = Validator::make($credentials, [
                'user_id' => 'required',
                'transaction_id' => 'required',
                'operation_type' => 'required'
            ], [
                'user_id.required' => 'id_user é um campo obrigatório!',
                'transaction_id.required' => 'transaction_id é um campo obrigatório',
                'operation_type.required' => 'operation_type é um campo obrigatório'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => true,
                    'response' => [
                        'message' => $validator->errors()
                    ]
                ]);
            }

            $dataArray = [
                'user_id' => $request->user_id,
                'transaction_id' => $request->transaction_id,
                'operation_type' => $request->operation_type,
                'created_at' => date('Y-m-d: H:i:s')
            ];

            if (!$this->releaseRepository->create($dataArray)) {
                return response()->json([
                    'error' => true,
                    'response' => [
                        'message' => 'Erro ao tentar cadastrar novo lançamento!'
                    ]
                ]);
            }

            return response()->json([
                'error' => false,
                'response' => [
                    'message' => 'Lançamento cadastrado com sucesso!'
                ]
            ]);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Release  $release
     * @return \Illuminate\Http\Response
     */
    public function show(Release $release)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Release  $release
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Release $release)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Release  $release
     * @return \Illuminate\Http\Response
     */
    public function destroy(Release $release)
    {
        //
    }
}
