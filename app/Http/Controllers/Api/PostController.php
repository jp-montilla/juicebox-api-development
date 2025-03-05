<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponse;
use App\Exceptions\NotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(private PostService $postService)
    {
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection($this->postService->getAllPosts());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        $post = $this->postService->storePost($request);
        return ApiResponse::sendResponse($post, 'Post created!', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $post = $this->postService->findPost($id);
        return ApiResponse::sendResponse($post, 'Post retrieved!', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, $id)
    {
        $post = $this->postService->updatePost($request, $id);
        return ApiResponse::sendResponse($post, 'Post updated!', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->postService->deletePost($id);
        return ApiResponse::sendResponse(null, 'Post deleted!', 200);
    }
}
