<?php

namespace App\Console\Commands;

use App\Helper\Curl;
use App\Models\Post;
use Illuminate\Console\Command;

class UpdateCommentCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-comment-count';

    /**
     *  统计文章评论数
     *
     * @var string
     */
    protected $description = '统计文章评论数';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = 'http://api.duoshuo.com/threads/counts.json?short_name=larry-cz&threads=';
        $id_list = Post::where(['state_id' => 10])->lists('comment_count', 'post_id');
        $count = 0;
        $total = count($id_list);
        foreach ($id_list as $id => $comment_count) {
            $rst = json_decode(Curl::get($url . $id));
            $comments = $rst->response->{$id}->comments;
            if ($comment_count != $comments) {
                Post::find($id)->update(['comment_count' => $comments]);
                $count++;
            }
        }
        //清空redis
        if($count){
            \Cache::flush();
        }
        $this->info("total $total modify $count");
    }
}
