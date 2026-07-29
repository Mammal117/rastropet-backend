<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

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