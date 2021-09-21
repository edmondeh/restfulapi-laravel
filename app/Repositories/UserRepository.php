<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserRepository implements UserRepositoryInterface
{

    public function all()
    {
        return User::all()->map->format();
    }

    public function findById($userId)
    {
        return User::where("id", $userId)
                    ->firstOrFail()->format();
    }

    public function store($request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        return $user->format();
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return $user->format();
    }

    public function delete($id)
    {
        return User::destroy($id);
    }
}