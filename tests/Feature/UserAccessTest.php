<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test; // Нужно импортировать этот класс

class UserAccessTest extends TestCase
{
    use RefreshDatabase;

    #[Test] // Вот так теперь выглядит современная пометка теста
    public function operator_cannot_view_user_list(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($operator)
            ->getJson('/api/v1/users');

        $response->assertStatus(403);
    }

    #[Test]
    public function admin_can_view_user_list(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
            ->getJson('/api/v1/users');

        $response->assertStatus(200);
    }

    #[Test]
    public function admin_cannot_delete_another_admin(): void
    {
        $admin1 = User::factory()->create(['role' => 'admin']);
        $admin2 = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin1)
            ->deleteJson("/api/v1/users/{$admin2->id}");

        $response->assertStatus(403);
    }

    #[Test]
    public function root_can_delete_admin(): void
    {
        $root = User::factory()->create(['role' => 'root']);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($root)
            ->deleteJson("/api/v1/users/{$admin->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('users', ['id' => $admin->id]);
    }
    #[Test]
    public function operator_cannot_promote_themselves_to_admin(): void
    {
        $operator = User::factory()->create(['role' => 'operator']);

        $response = $this->actingAs($operator)
            ->patchJson("/api/v1/users/{$operator->id}", [
                'role' => 'admin'
            ]);

        // Ожидаем 403, так как менять роли может только Root
        $response->assertStatus(403);

        // Проверяем, что в базе роль осталась прежней
        $this->assertEquals('operator', $operator->fresh()->role);
    }
}
