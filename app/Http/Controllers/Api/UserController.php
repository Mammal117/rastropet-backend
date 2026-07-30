<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->query('per_page', 10);

        $query = User::with('role');

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($roleId = $request->query('role_id')) {
            $query->where('role_id', $roleId);
        }

        return UserResource::collection($query->latest()->paginate($perPage));
    }

    public function show(User $user)
    {
        return new UserResource($user->load('role'));
    }

    // Registrar un nuevo usuario "desde adentro" (no es el /auth/register
    // público): lo usan dueños para invitar voluntarios, y admins para
    // crear voluntarios o nuevos admins. Qué rol está permitido según quién
    // lo crea se valida dentro de StoreUserRequest.
    public function store(StoreUserRequest $request)
    {
        $role = Role::where('name', $request->validated('role'))->firstOrFail();

        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'phone' => $request->validated('phone'),
            'password' => Hash::make($request->validated('password')),
            'role_id' => $role->id,
        ]);

        return (new UserResource($user->load('role')))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        return new UserResource($user->load('role'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado correctamente.']);
    }
}