<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;
class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:publish-scheduled-posts';
    protected $description ='Tự động đăng bài viết đến thời gian hẹn';
    /**
     * The console command description.
     *
     * @var string
     */
  

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();

        $posts = Post::where('status', 'drafted')
                     ->whereNotNull('scheduled_at')
                     ->where('scheduled_at', '<=', $now)
                     ->get();

        foreach ($posts as $post) {
            $post->status = 'published';
            $post->update();

            $this->info("Xuất bản bài viết ID: {$post->id}, Title: {$post->title}");
              Log::info("Đã xuất bản bài viết ID: {$post->id}, Title: {$post->title}");
        }

        $this->info('Hoàn thành kiểm tra và xuất bản bài viết.');
    }
}