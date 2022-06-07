<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    //  Retornar todos os usuário
    public function index()
    {
        return response()->json([
            'users' => $this->userRepository->getAll()
        ]);
    }

    //  Procurar usuário por nome e senha
    public function search(string $username, string $password)
    {
        if (!$username && !$password) {
            return response()->json([
                'error' => true,
                'response' => [
                    'message' => 'Informe todos os dados obrigatórios!'
                ]
            ]);
        }

        $user = $this->userRepository->getByUsernameAndPassword($username, md5($password));

        if (!$user) {
            return response()->json([
                'error' => true,
                'response' => [
                    'message' => 'Nenhum usuário foi encontrado!'
                ]
            ]);
        }

        return response()->json([
            'error' => false,
            'response' => [
                'message' => 'Usuário encontrado!',
                'user' => $user
            ]
        ]);
    }

    // Cadastrar um usuário
    public function store(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');
            $validator = Validator::make($credentials, [
                'username' => 'string|required',
                'password' => 'string|required',
            ],[
                'username.required' => 'Nome de usuário é um campo obrigatório!',
                'password.required' => 'Senha é um campo obrigatório',
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
                'username' => $request->username,
                'password' => md5($request->password),
                'status' => config('constants.USER.ATIVO')
            ];
    
            $newUser = $this->userRepository->create($dataArray);

            if (!$newUser) {
                return response()->json([
                    'error' => true,
                    'response' => [
                        'message' => 'Erro ao tentar cadastrar novo usuário!'
                    ]
                ]);
            }

            return response()->json([
                'error' => false,
                'response' => [
                    'message' => 'Usuário cadastrado com sucesso!',
                    'user' => $newUser
                ]
            ]);

        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    // Editar um usuário
    public function update(Request $request, int $id)
    {
        try {

            $credentials = $request->only('username', 'status');
            $validator = Validator::make($credentials, [
                'username' => 'string|required',
                'status' => 'required'
            ],[
                'username.required' => 'Nome de usuário é um campo obrigatório!',
                'status.required' => 'Status é um campo obrigatório' 
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
                'username' => $request->username,
                'status' => $request->status
            ];
    
            if (!$this->userRepository->update($id, $dataArray)) {
                return response()->json([
                    'error' => true,
                    'response' => [
                        'message' => 'Erro ao tentar editar o usuário!'
                    ]
                ]);
            }

            return response()->json([
                'error' => false,
                'response' => [
                    'message' => 'Usuário editado com sucesso!'
                ]
            ]);

        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

}

