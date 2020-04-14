<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\StorePostCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostCommentController extends AppBaseController
{
    public function __construct()
    {
        $this->middleware('auth:api')->only(['store', 'update', 'destroy']);
    }


    /**
     * Display a listing of the resource.
     *
     * @param Post $post
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Post $post, Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->sendResponse(
            CommentResource::collection($post->comments()->with('user')->get()),
            'data retrived successfully');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Post $post
     * @param StorePostCommentRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Post $post, StorePostCommentRequest $request): \Illuminate\Http\JsonResponse
    {
        $comment = $post->comments()->create($request->all());
        return $this->sendResponse(
            new CommentResource($comment),
            'post comment has created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
