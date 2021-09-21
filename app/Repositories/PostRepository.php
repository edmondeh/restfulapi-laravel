<?php

namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostRepository implements PostRepositoryInterface
{
    public function all()
    {
        return Post::all()->map->format();
    }

    public function allWithUser()
    {
        return Post::with('user')->get()->map->formatWithUser();
    }

    public function findById($id)
    {
        return Post::where('id', $id)->with('user')->get()->map->formatWithUser();
    }

    public function store($request)
    {
        if ($request->has('user_id')) {
            $user = User::findOrFail($request->user_id);
        };

        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body,
            'user_id' => isset($user->id) ? $user->id : auth()->user()->id,
        ]);

        //$post->user()->associate($user);

        return $post->formatWithUser();
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $post->update($request->all());

        return $post->formatWithUser();
    }

    public function delete($id)
    {
        return Post::destroy($id);
    }
}
