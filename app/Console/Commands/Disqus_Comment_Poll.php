<?php

namespace App\Console\Commands;

use App\Jobs\SendWechatComment;
use Cache;
use Illuminate\Console\Command;

class Disqus_Comment_Poll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:disqus_comment_poll';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disqus评论定时检查更新,微信提醒';

    /**
     * Create a new command instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $sk = env('DISQUS_SK');
        $forum = env('DISQUS_FORUM');
        $limit = 100;
        $cache_key = 'disqus_comment_poll_key';
        $start = Cache::get($cache_key, '');
        $post_url = "https://disqus.com/api/3.0/posts/list.json?forum=$forum&limit=$limit&related=thread&api_secret=$sk&start=$start&order=asc";
        \Log::info($post_url);
        $cu_time = time();
        $rst = json_decode(\App\Helper\Curl::get($post_url), true);
        if ($rst['code'] === 0) {
            \Cache::put($cache_key, $cu_time, 120);
            foreach ($rst['response'] as $item) {
                @$data = [
                    'title' => $item['thread']['title'],
                    'name' => $item['author']['name'],
                    'message' => $item['raw_message'],
                    'time' => date('m-d H:i', strtotime($item['createdAt']) + 3600 * 8)
                ];
                \Log::info('发送数据', $data);
                dispatch(new SendWechatComment($data, $item['url']));
            }
        } else {
            throw new \Exception($rst);
        }
    }
}
