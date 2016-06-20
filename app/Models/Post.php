<?php

namespace App\Models;

use App\Helper\Curl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use URL;

class Post extends Model
{
    use SoftDeletes;
    protected $table = 'post';
    protected $primaryKey = 'post_id';
    protected $guarded = [];

    public static function getHotRead()
    {
        $cache_key = 'hot_read';
        $data = \Cache::get($cache_key);
        if (!$data) {
            $query = Post::select(['post_id', 'title', 'read_count'])->where(['state_id' => 10]);
            $data = $query->limit(10)->orderBy('read_count', 'desc')->get();
            \Cache::put($cache_key, $data, 60 * 24);
        }
        return $data;
    }

    public static function getHotTag()
    {
        $cache_key = 'hot_tag';
        $data = \Cache::get($cache_key);
        if (!$data) {
            $query = Tag::select(['post_count', 'tag_name'])->distinct()->join('post_tag', 'tag.tag_id', '=', 'post_tag.tag_id');
            $data = $query->limit(15)->orderBy('post_count', 'desc')->get();
        }
        return $data;
    }


    public static function updateCommentCount($posts)
    {
        $disqus_count_url = 'http://larry666.disqus.com/count-data.js?2=';
        foreach ($posts as $post) {
            $url = route('post.view', ['post_id' => $post->post_id]);
            $result = Curl::get($disqus_count_url . $url . '&rand=' . rand(1, 100));
            if (preg_match('/counts":\[(.*?)]}\);/is', $result, $m)) {
                $result = json_decode($m[1]);
                $post->comment_count = $result->comments;
                $post->save();
            }
        }
    }


    public static function getState($status_id = null)
    {
        $map = [10 => '启用', 20 => '停用', 30 => '草稿'];
        if (array_key_exists($status_id, $map)) {
            return $map[$status_id];
        }
        return $map;
    }

}
