<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // СВЯЗЬ С AUTHCONTROLLER: Позволяет генерировать токены через $user->createToken()

class User extends Authenticatable
{
    /** * HasApiTokens: Позволяет модели работать с библиотекой Sanctum.
     * Именно этот трейт добавляет методы для создания и проверки Bearer-токенов.
     */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * $fillable: Список полей, которые разрешено заполнять массово.
     * СВЯЗЬ С USERCONTROLLER (метод store): Когда мы пишем User::create($validated),
     * Laravel сверяется с этим списком. Если поля здесь нет, оно не сохранится в БД.
     */
    protected $fillable = [
        'name',
        'username', // Нужно для логина в AuthController
        'email',    // Нужно для логина в AuthController
        'phone',    // Нужно для логина в AuthController
        'password',
        'role',     // Определяет права доступа в UserController
    ];

    /**
     * $hidden: Поля, которые автоматически вырезаются при превращении модели в массив или JSON.
     * СВЯЗЬ С USERRESOURCE: Это дополнительный слой защиты, чтобы пароль никогда не улетел клиенту.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * casts(): Автоматическое преобразование типов.
     * 'password' => 'hashed' говорит Laravel, что пароль всегда должен быть зашифрован.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * СВЯЗЬ С USERCONTROLLER (методы index, store, show, destroy):
     * Эти три метода — "глаза" контроллера. Вместо того чтобы каждый раз писать 
     * $user->role === 'root' в контроллере, мы вызываем эти понятные функции.
     */

    // Проверка: является ли пользователь супер-админом (Root)
    public function isRoot(): bool
    {
        return $this->role === 'root';
    }

    // Проверка: является ли пользователь администратором
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Проверка: является ли пользователь оператором
    public function isOperator(): bool
    {
        return $this->role === 'operator';
    }
}
