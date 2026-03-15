<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Метод toArray визначає, які дані і в якому форматі підуть в JSON.
     * * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'username' => $this->username,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'role'     => $this->role,

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),

        ];
    }
}
