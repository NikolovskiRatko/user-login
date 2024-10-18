<?php

namespace App\Applications\User\DTO;

use App\Applications\User\Model\User;
use Illuminate\Http\Request;

class UserDTO
{
    public string $first_name;
    public string $last_name;
    public string $username;
    public string $email;
    public ?string $avatar_url;
    public ?string $avatar_thumbnail;
    public int $role;
    public int $id;
    public bool $is_disabled;
    public array $permissions_array;

    public function __construct(
        string $username,
        string $first_name,
        string $last_name,
        string $email,
        ?string $avatar_url,
        ?string $avatar_thumbnail,
        int $role,
        int $id = 0,
        bool $is_disabled = false,
        array $permissions_array = []
    ) {
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->username = $username;
        $this->email = $email;
        $this->avatar_url = $avatar_url;
        $this->avatar_thumbnail = $avatar_thumbnail;
        $this->role = $role;
        $this->id = $id;
        $this->is_disabled = $is_disabled;
        $this->permissions_array = $permissions_array;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('username',''),
            $request->input('email'),
            null,
            null,
            $request->input('role',1),
            $request->input('id', 0),
            (bool) $request->input('is_disabled', false),
            $request->input('permissions_array', [])
        );
    }

    public static function fromRequestForCreate(Request $request): self
    {
        return new self(
            $request->input('username',''),
            $request->input('first_name',''),
            $request->input('last_name',''),
            $request->input('email'),
            null,
            null,
            $request->input('role',1),
            id: 0,
            is_disabled: false,
            permissions_array: $request->input('permissions_array', [])
        );
    }

    public static function fromModel(User $user): self
    {
        return new self(
            $user->username,
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->avatar_url,
            $user->avatar_thumbnail,
            $user->role,
            $user->id,
            (bool) $user->is_disabled,
            $user->permissions_array
        );
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'username' => $this->username,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'avatar_url' => $this->avatar_url,
            'avatar_thumbnail' => $this->avatar_thumbnail,
            'role' => $this->role,
            'id' => $this->id,
            'is_disabled' => $this->is_disabled,
            'permissions_array' => $this->permissions_array,
        ];
    }

    public static function fromCollection(iterable $users): array
    {
        return array_map(function (User $user) {
            return self::fromModel($user);
        }, $users->all());
    }
}
