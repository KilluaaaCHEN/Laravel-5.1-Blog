<?php
/**
 * Created by PhpStorm.
 * User: larry
 * Date: 15/11/3
 * Time: 16:29
 */

namespace App\Http\Controllers;

use App\Helper\Common;
use App\Models\Post;
use Request;
use URL;
use Input;

class PostController extends Controller
{

    public function index(Request $request)
    {
        $cache_key = URL::full();
        $posts = \Cache::get($cache_key);
        $q = $request::get('q');
        if (!$posts) {
            $query = Post::where(['state_id' => 10])->orderBy('post_id', 'desc');
            if ($q) {
                $search_arr = explode(' ', $q);
                foreach ($search_arr as $item) {
                    $query->where('title', 'like', "%$item%");
                    $query->orWhere('tags', 'like', "%$item%");
                    $query->orWhere('desc', 'like', "%$item%");
                    $query->orWhere('content', 'like', "%$item%");
                }
            }
            $posts = $query->paginate(5);
            \Cache::put($cache_key, $posts, 60 * 24);
        }
        return view('index', [
            'posts' => $posts,
            'title' => '<i class="icon-book"></i> Release',
            'q' => $q
        ]);
    }

    public function view($post_id)
    {
        $cache_key = URL::full();
        $post = \Cache::get($cache_key);
        if (!$post) {
            $post = Post::findOrFail($post_id);
            \Cache::put($cache_key, $post, 1);
        }
        $key = 'Read_' . str_replace('.', '_', Request::getClientIp() . '_' . $post_id);
        if (!\Cache::has($key)) {
            \Cache::put($key, 1, 1);
            $post->read_count++;
            $post->save();
            $post->read_count--;
        }
        return view('post.view', [
            'post' => $post
        ]);
    }

    public function searchTag($tag)
    {
        $query = Post::where(['state_id' => 10])->where('tags', 'like', "%$tag,%")->orderBy('created_at', 'desc');
        $posts = $query->paginate(5);
        $title = "<i class='icon-search'></i> Current Tag : $tag <a href='" . route('home') . "' title='Clean up' class='icon-remove'></a>";
        return view('index', [
            'posts' => $posts,
            'title' => $title,
            'q' => null
        ]);
    }


}