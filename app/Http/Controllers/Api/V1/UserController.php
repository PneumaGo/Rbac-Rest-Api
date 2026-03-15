<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class UserController extends Controller
{
    use AuthorizesRequests;

    // 1. СПИСОК ВСІХ КОРИСТУВАЧІВ (GET /users)
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $currentUser = $request->user();

        if ($currentUser->isRoot()) {
            $users = User::all();
        } else {
            $users = User::where('role', '!=', 'root')->get();
        }

        return UserResource::collection($users);
    }

    // 2. ДЕТАЛІ ОДНОГО КОРИСТУВАЧА (GET /users/{id})
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    // 3. СТВОРЕННЯ НОВОГО КОРИСТУВАЧА (POST /users)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|string|unique:users,phone',
            'password' => 'required|string|min:8',
            'role'     => ['required', Rule::in(['admin', 'operator'])],
        ]);

        $this->authorize('create', [User::class, $validated['role']]);

        $user = User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return new UserResource($user);
    }

    // 4. ОНОВЛЕННЯ ІНФОРМАЦІЇ ПРО КОРИСТУВАЧА (PUT/PATCH /users/{id})
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'sometimes|string|max:255',
            'email'    => 'sometimes|email|unique:users,email,' . $user->id,
            'username' => 'sometimes|string|unique:users,username,' . $user->id,
            'phone'    => 'sometimes|string|unique:users,phone,' . $user->id,
            'role'     => ['sometimes', Rule::in(['admin', 'operator'])],
        ]);

        $this->authorize('update', [$user, $request->role]);

        $user->update($validated);

        return new UserResource($user);
    }

    // 5. ВИДАЛЕННЯ КОРИСТУВАЧА (DELETE /users/{id})
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            'message' => 'Користувач успішно видалено'
        ], 200);
    }
}
