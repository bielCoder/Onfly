<?php

namespace App\Http\Controllers;

use App\Classes\Utilities\Response;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private $response;
    private $users;

    public function __construct(Response $response, User $users)
    {
        $this->response = $response;
        $this->users = $users;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $users = $this->users->paginate($request->per_page ?? 10);

            if ($users->total() === 0) {
                return $this->response->error("users", $request->header('Content-Type'), strtoupper($request->method()), "Nenhum usuário encontrado", 404);
            }

            return $this->response->format("users", $request->header('Content-Type'), strtoupper($request->method()), $users, null, null, 200);

        } catch (\Exception $e) {
            return $this->response->error("users", $request->header('Content-Type'), strtoupper($request->method()), $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6'
            ]);

            $user = $this->users->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            return $this->response->format("users", $request->header('Content-Type'), strtoupper($request->method()), $user, null, "Usuário cadastrado com sucesso.", 201);

        } catch (\Exception $e) {
            return $this->response->error("users", $request->header('Content-Type'), strtoupper($request->method()), $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified user.
     */
    public function show(Request $request, string $id)
    {
        try {
            $user = $this->users->find($id);

            if (!$user) {
                return $this->response->error("users", $request->header('Content-Type'), strtoupper($request->method()), "Usuário não encontrado", 404);
            }

            return $this->response->format("users", $request->header('Content-Type'), strtoupper($request->method()), $user, null, null, 200);

        } catch (\Exception $e) {
            return $this->response->error("users", $request->header('Content-Type'), strtoupper($request->method()), $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'name' => 'nullable|string',
                'email' => 'nullable|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:6'
            ]);

            $user = $this->users->find($id);

            if (!$user) {
                return $this->response->error("users", $request->header('Content-Type'), strtoupper($request->method()), "Usuário não encontrado", 404);
            }

            $data = $request->only(['name', 'email']);
            if ($request->filled('password')) {
                $data['password'] = bcrypt($request->password);
            }

            $user->update($data);

            return $this->response->format("users", $request->header('Content-Type'), strtoupper($request->method()), $user, null, "Usuário atualizado com sucesso.", 200);

        } catch (\Exception $e) {
            return $this->response->error("users", $request->header('Content-Type'), strtoupper($request->method()), $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request, string $id)
    {
        try {
            $user = $this->users->find($id);

            if (!$user) {
                return $this->response->error("users", $request->header('Content-Type'), strtoupper($request->method()), "Usuário não encontrado", 404);
            }

            $user->delete();

            return $this->response->format("users", $request->header('Content-Type'), strtoupper($request->method()), null, null, "Usuário removido com sucesso.", 200);

        } catch (\Exception $e) {
            return $this->response->error("users", $request->header('Content-Type'), strtoupper($request->method()), $e->getMessage(), 500);
        }
    }
}
