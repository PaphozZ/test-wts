<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StorePostRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $this->postService->createPost($request->user(), $request->validated());
        return response()->json($post, 201);
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['date_from', 'date_to', 'sort_by', 'sort_order']);
        $limit = $request->get('limit', 15);
        $offset = $request->get('offset', 0);
        $posts = $this->postService->getAllPosts($filters, $limit, $offset);
        return response()->json($posts);
    }

    public function myPosts(Request $request): JsonResponse
    {
        $filters = $request->only(['date_from', 'date_to', 'sort_by', 'sort_order']);
        $limit = $request->get('limit', 15);
        $offset = $request->get('offset', 0);
        $posts = $this->postService->getUserPosts($request->user(), $filters, $limit, $offset);
        return response()->json($posts);
    }
}
