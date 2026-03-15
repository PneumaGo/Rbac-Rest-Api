<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{

    // 1. МЕТОД ПЕРЕВІРКИ ПРАВ НА ПРОСМОТР СПИСКА ЮЗЕРОВ (метод index в UserController)
    public function viewAny(User $currentUser): bool
    {

        return $currentUser->isRoot() || $currentUser->isAdmin();
    }

    // 2. МЕТОД ПЕРЕВІРКИ ПРАВ НА ПРОСМОТР ДЕТАЛЕЙ ОДНОГО ЮЗЕРА (метод show в UserController)
    public function view(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->isRoot() || $currentUser->isAdmin()) {
            return true;
        }

        return $currentUser->id === $targetUser->id;
    }

    // 3. МЕТОД ПЕРЕВІРКИ ПРАВ НА СТВОРЕННЯ НОВОГО ЮЗЕРА (метод store в UserController)
    public function create(User $currentUser, string $roleToCreate): bool
    {
        if ($currentUser->isRoot()) {
            return true;
        }

        if ($currentUser->isAdmin()) {
            return $roleToCreate === 'operator';
        }

        return false;
    }

    // 4. МЕТОД ПЕРЕВІРКИ ПРАВ НА РЕДАГУВАННЯ ІНФОРМАЦІЇ ПРО ЮЗЕРА (метод update в UserController)
    public function update(User $currentUser, User $targetUser, string $newRole = null): bool
    {
        if ($currentUser->isRoot()) {
            return true;
        }

        if ($currentUser->id === $targetUser->id) {
            if ($newRole && $newRole !== $currentUser->role) {
                return false;
            }
            return true;
        }

        if ($currentUser->isAdmin()) {
            return $targetUser->isOperator();
        }

        return false;
    }

    // 5. МЕТОД ПЕРЕВІРКИ ПРАВ НА ВИДАЛЕННЯ ЮЗЕРА (метод destroy в UserController)
    public function delete(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->isRoot()) {
            return $currentUser->id !== $targetUser->id;
        }

        if ($currentUser->isAdmin()) {
            return $targetUser->isOperator();
        }

        return false;
    }

    // 6. МЕТОДЫ ДЛЯ ВОССТАНОВЛЕНИЯ И ПОЛНОГО УДАЛЕНИЯ (force delete) - НЕ ИСПОЛЬЗУЕМ, НО ОБЯЗАТЕЛЬНО ДОЛЖНЫ БЫТЬ В ПОЛИТИКЕ
    public function restore(User $currentUser, User $targetUser): bool
    {
        return false;
    }


    public function forceDelete(User $user, User $model): bool
    {
        return false;
    }
}
