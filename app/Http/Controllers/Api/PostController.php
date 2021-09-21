<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\PostRepositoryInterface;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $postRepo;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepo = $postRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->postRepo->allWithUser();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'unique:posts', 'max:255'],
            'body' => ['required'],
        ]);
        if ($request->has('user_id')) {
            $request->validate([
                'user_id' => ['numeric'],
            ]);
        }

        $post = $this->postRepo->store($request);
        if ($post)
        {
            return response()->json([
                'success' => true,
                'message' => "Post with $post->name created sucessfully.",
                'data' => $post
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => "Some error happend.",
            ], 404);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = $this->postRepo->findById($id);
        if ($post) {
            return response()->json([
                'success' => true,
                'message' => "Success",
                'data' => $post
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Some error happend.",
            ], 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => ['required', 'unique:posts', 'max:255'],
            'body' => ['required'],
        ]);

        if ($request->has('user_id')) {
            $request->validate([
                'user_id' => ['numeric'],
            ]);
        }

        $post = $this->postRepo->update($request, $id);
        if ($post)
        {
            return response()->json([
                'success' => true,
                'message' => "Post with $post->name updated sucessfully.",
                'data' => $post
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => "Some error happend.",
            ], 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result = $this->postRepo->delete($id);

        if($result == 1)
        {
            return response()->json([
                'message' => "Post with $id deleted successfully.",
            ]);
        }

        return response()->json([
            'message' => "Some error happend.",
        ], 404);
    }
}
