<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
use App\Events\PostCreated;

class PostController extends Controller
{
    /**
     * Return the latest post created
     */
    public function index()
    {
        return response()->json(Post::latest()->get());
    }

    /**
     * Insert a new post that should become from an external api.
     * Trigger the event and send to pusher in order to get in the frontend in realtime. 
     */
    public function createPost()
    {
        $postData = PostService::getPostFrom("mock-social-network");

        if ($postData) {
            $post = Post::create(
                [
                    'author' => $postData['author'],
                    'title' => $postData['title'], 
                    'content' => $postData['content']
                ]
            );

            broadcast(new PostCreated($post))->toOthers();

            return response()->json($post);
        }

        return response()->json(['error' => 'can not get new post'], 500);
    }
}
