<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User; // СВЯЗЬ: Используем модель для поиска в БД
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // СВЯЗЬ: Проверяем зашифрованный пароль
use Illuminate\Validation\ValidationException;



class AuthController extends Controller
{

    public function login(Request $request)
    {
        // 1. ВАЛІДАЦІЯ
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string',
        ]);

        $loginValue = $request->input('login');

        // 2. ВИЗНАЧЕННЯ ТИПУ ВХОДУ (Мульти-логін)
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : (preg_match('/^[0-9]+$/', $loginValue) ? 'phone' : 'username');

        // 3. ПОШУК І ПЕРЕВІРКА ПАРОЛЯ
        $user = User::where($field, $loginValue)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'login' => ['Невірні облікові дані.'],
            ]);
        }

        // 4. ГЕНЕРАЦІЯ ТОКЕНУ (SANCTUM)
        $token = $user->createToken('auth_token')->plainTextToken;

        // 5. ВІДПОВІДЬ (RESPONSE)
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => [
                'name' => $user->name,
                'role' => $user->role,
            ]
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Успішно вийшов з системи']);
    }
}
