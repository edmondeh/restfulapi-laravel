<?php

namespace App\Repositories;

use App\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isNull;

class CommentRepository implements CommentRepositoryInterface
{

    public function all($postId)
    {
//        return Post::with('comments')
//            ->where('id', $postId)
//            ->get()
//            ->map(function ($post) {
//                return [
//                    'id' => $post->id,
//                    'title' => $post->title,
//                    'body' => $post->body,
//                    'user' => [
//                        'id' => $post->user->id,
//                        'name' => $post->user->name,
//                        'email' => $post->user->email,
//                    ],
//                    'comments' => $post->comments()->get()->map(function ($comment) {
//                        return [
//                            'id' => $comment->id,
//                            'body' => $comment->body,
//                            'user' => [
//                                'id' => $comment->user->id,
//                                'name' => $comment->user->name,
//                                'email' => $comment->user->email
//                            ]
//                        ];
//                    }),
//                ];
//            });
        return Post::with(['user' ,'comments'])
            ->where('id', $postId)
            ->get()
            ->makeVisible(['user', 'comments']);
    }

    public function findById($postId, $commentId)
    {
        $post = Post::whereRelation(
            'comments', 'id', '=', $commentId
        )->first();
        if ($post->id != $postId)
            return null;
        if (is_null($post))
            return $post;

        $comment = Comment::with(['user', 'commentable'])->where('id', $commentId)->get();
        if (is_null($comment))
            return $comment;

        return $comment->makeVisible(['user', 'commentable']);
//        return [
//            'id' => $comment->id,
//            'body' => $comment->body,
//            'user' => [
//                'id' => $comment->user->id,
//                'name' => $comment->user->name,
//                'email' => $comment->user->email,
//            ],
//            'post' => [
//                'id' => $comment->commentable->id,
//                'title' => $comment->commentable->title,
//                'body' => $comment->commentable->body,
//            ]
//        ];
    }

    public function store($request, $postId)
    {
        $post = Post::find($postId);
        if (is_null($post))
            return $post;

        $comment = $post->comments()->create([
            'body' => $request->body,
            'user_id' => auth()->user()->id
        ]);

        return [
            'id' => $comment->id,
            'body' => $comment->body,
            'user' => [
                'id' => $comment->user->id,
                'name' => $comment->user->name,
                'email' => $comment->user->email
            ]
        ];
    }

    public function update(Request $request, $postId, $commentId)
    {
        $comment = Comment::find($commentId);
        if (is_null($comment))
            return null;
        if ($comment->commentable->id != $postId)
            return null;

        //$post->update($request->all());
        $comment->update($request->all());

        return $comment;
    }

    public function delete($postId, $commentId)
    {
        $comment = Comment::find($commentId);
        if (is_null($comment))
            return null;
        if ($comment->commentable->id != $postId)
            return null;

        return $comment->delete();
    }
}
