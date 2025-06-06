<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use Log;

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'cpf' => 'required|string|max:14|unique:users', // CPF validation with mask
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20', // Phone validation
            'password' => 'required|string|min:8|confirmed',
        ];

        $messages = [
            'name.required' => __('O campo nome é obrigatório.'),
            'cpf.required' => __('O campo CPF é obrigatório.'),
            'cpf.unique' => __('Já existe um usuário registrado com este CPF.'),
            'email.required' => __('O campo email é obrigatório.'),
            'email.email' => __('O campo email deve ser um endereço de email válido.'),
            'email.unique' => __('Já existe um usuário registrado com este email.'),
            'phone.required' => __('O campo telefone é obrigatório.'),
            'password.required' => __('O campo senha é obrigatório.'),
            'password.min' => __('A senha deve ter pelo menos 8 caracteres.'),
            'password.confirmed' => __('As senhas não conferem.'),
        ];

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  Request $request
     * @return \App\Models\Users
     */
    public function create(Request $request)
    {
        try {
            $response = [
                'success' => false,
                'data' => [],
                'messages' => []
            ];
            $data = array_map('trim', $request->all());
            $validator = $this->validator($data);
            if ($validator->fails()) {
                foreach ($validator->errors()->getMessages() as $message) {
                    $response['messages'] = [
                        'message' => $message[0],
                    ];
                }

                return response()->json($response, 422);
            }
    
            $isRegistered = User::isRegistered($data['cpf']);
            if ($isRegistered) {
                $response['messages'] = [
                    'message' => __('Já existe um usuário registrado com este CPF. Faça login para continuar.'),
                ];

                return response()->json($response, 200);
            }
    
            $user = new User();
            foreach ($data as $column => $value) {
                if ($column !== 'password_confirmation') {
                    $user->{$column} = $value;
                }
            }
    
            $user->email_verified_at = null;
            $user->remember_token = null;
            $user->status = 'ativo'; // Default status
            $user->created_at = Carbon::now();
            $user->updated_at = null;
            $user->deleted_at = null;
            $user->save();
            if (! $user->id) {
                $response['messages'] = [
                    'message' => __('Ocorreu um erro ao salvar o usuário.'),
                ];

                return response()->json($response, 200);
            }

            $response = [
                'success' => true,
                'data' => ['id' => $user->id],
                'messages' => ['message' => __('Usuário registrado com sucesso.')],
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::save('error', $e);

            $response['messages'] = [
                'message' => __('Ops! Ocorreu um erro ao executar esta ação.'),
            ];

            return response()->json($response, 200);
        }
    }
}
