<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendWechatComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    private $url;

    /**
     * Create a new job instance.
     * @param array $data
     */
    public function __construct(array $data, $url)
    {
        $this->data = $data;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $userId = env('WECHAT_BLOG_OPEN_ID');
        $templateId = env('WECHAT_BLOG_TEMPLATE_ID');
        $wechat = app('wechat');
        $rst = $wechat->notice->uses($templateId)->withUrl($this->url)->andData($this->data)->andReceiver($userId)->send();
        \Log::info('Wechat消息推送:' . $rst);
    }
}
