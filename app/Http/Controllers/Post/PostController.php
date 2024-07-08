<?php

namespace App\Http\Controllers\Post;

use App\Events\StorePostEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\Post\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        $posts = PostResource::collection($posts)->resolve();
        return inertia('Post/Index', ['posts' => $posts]);
    }

    public function store(PostRequest $request)
    {
        $validated = $request->validated();
        $post = Post::create($validated);
        broadcast(new StorePostEvent($post))->toOthers();
        return PostResource::make($post)->resolve();
    }
}
