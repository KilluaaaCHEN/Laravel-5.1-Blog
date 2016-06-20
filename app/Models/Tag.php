<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tag';
    protected $primaryKey = 'tag_id';
    protected $guarded = [];
    public $timestamps = false;

    public static function getAllTag()
    {
        $data = json_encode(Tag::lists('tag_name')->toArray());
        return $data;
    }

    public static function updateTag($post_id, $tags)
    {
        $old_tags = PostTag::where(['post_id' => $post_id])->get();
        $old_tags_arr = PostTag::where(['post_id' => $post_id])->lists('tag_id');
        Tag::whereIn('tag_id', $old_tags_arr)->decrement('post_count', 1);
        foreach ($tags as $val) {
            $val = trim($val);
            $tag = Tag::where(['tag_name' => $val])->first();
            if (!$tag) {
                $tag = new Tag(['tag_name' => $val, 'post_count' => 1]);
            } else {
                $tag->post_count++;
            }
            $tag->save();
            $pt = new PostTag(['tag_id' => $tag->tag_id, 'post_id' => $post_id]);
            $pt->save();
        }
        foreach ($old_tags as $pt) {
            if (!in_array($pt->tag_id, $tags)) {
                $pt->delete();
                if($pt->tag->post_count==0) {
                    $pt->tag->delete();
                }
            }
        }

    }
}
