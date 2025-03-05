<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PostService
{
    public function getAllPosts()
    {
        return Cache::remember("all_post_".request()->get('page', 1), 15, function () {
            return Post::with('user')->paginate(15);
        });
    }

    public function storePost($request)
    {
        $post = $request->user()->posts()->create($request->validated());
        return new PostResource($post);
    }

    public function findPost($id)
    {
        $post = $this->findPostById($id);
        return new PostResource($post);
    }

    public function updatePost($request, $id)
    {
        $post = $this->findPostById($id);
        $this->authorizedAction('update', $post);
        $post->update($request->validated());
        return new PostResource($post);
    }

    public function deletePost($id)
    {
        $post = $this->findPostById($id);
        $this->authorizedAction('delete', $post);
        $post->delete();
    }

    private function findPostById($id)
    {
        $post = Post::find($id);
        if (!$post) {
            throw new CustomException('Post not found!', 404);
        }
        return $post;
    }

    private function authorizedAction($action, $post) {
        if (Auth::user()->cannot($action, $post)) {
            throw new CustomException('You do not owned this post!.', 403);
        }
    }
}
