<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\CommentRepositoryInterface;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $commentRepo;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepo = $commentRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($postId)
    {
        $comments = $this->commentRepo->all($postId);

        if (is_null($comments)) {
            return response()->json([
                'success' => false,
                'message' => "Error",
                'data' => []
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => "Message",
            'data' => $comments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $postId)
    {
        $request->validate([
            'body' => 'required'
        ]);

        $post = $this->commentRepo->store($request, $postId);

        if (is_null($post)) {
            return response()->json([
                'success' => false,
                'message' => "Error",
                'data' => []
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => "Message",
            'data' => $post
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($postId, $commentId)
    {
        $comment = $this->commentRepo->findById($postId, $commentId);

        if (is_null($comment)) {
            return response()->json([
                'success' => false,
                'message' => "Error",
                'data' => []
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => "Message",
            'data' => $comment
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $productId, $commentId)
    {
        $request->validate([
            'body' => 'required'
        ]);

        $comment = $this->commentRepo->update($request, $productId, $commentId);
        if (is_null($comment))
            return response()->json([
                'success' => false,
                'message' => "Error",
                'data' => []
            ], 404);

        return response()->json([
            'success' => true,
            'message' => "Message",
            'data' => $comment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($productId, $commentId)
    {
        $comment = $this->commentRepo->delete($productId, $commentId);

        if (is_null($comment))
            return response()->json([
                'success' => false,
                'message' => "Error",
                'data' => []
            ], 404);

        return response()->json([
            'success' => true,
            'message' => "Message",
            'data' => $comment
        ]);
    }
}
