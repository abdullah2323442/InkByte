<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index()
    {
        $categories = Cache::remember('categories', now()->addDays(3), function () {
            return Category::with('publishedPosts')->take(10)->get();
        });

        $posts = Post::published()->latest()->paginate(10);

        return view(
            'posts.index',
            [
                'categories' => $categories,
                'posts' => $posts,
            ]
        );
    }


    public function show(Post $post)
    {
        if (!$post->published) {
            return abort(404);
        }

        return view(
            'posts.show',
            [
                'post' => $post
            ]
        );
    }
}
