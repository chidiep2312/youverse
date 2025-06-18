<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\ViolationPostMail;
use App\Models\Post;
use App\Models\ViolationVector;

class GeneratePostEmbedding implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $postId;

    public function __construct($postId)
    {
        $this->postId = $postId;
    }

    public function handle()
    {
        try {
            // Gọi script Python để tạo embedding
            $script1 = base_path('python/embed_post.py');
            $command1 = "python " . escapeshellarg($script1) . " " . escapeshellarg($this->postId);
            shell_exec($command1);

            $script2 = base_path('python/violation_post.py');
            $command2 = "python " . escapeshellarg($script2) . " " . escapeshellarg($this->postId);
            shell_exec($command2);
            // Reload bài viết
            $post = Post::withoutGlobalScopes()->find($this->postId)?->fresh();
            if (!$post || empty($post->vio_embedding)) {
                throw new \Exception("Không có dữ liệu vio_embedding");
            }
            $embedding = $post->vio_embedding;
            if (!is_array($embedding) || !isset($embedding['embeddings']) || !is_array($embedding['embeddings'])) {
                throw new \Exception("Không đúng định dạng embedding");
            }
            $isViolation = $this->checkViolationByEmbedding($embedding, 0.6);
            $post->is_flag = $isViolation ? 1 : 0;
            $post->forceFill(['updated_at' => now()])->save();
            if ($isViolation && $post->user) {
                Mail::to($post->user->email)->queue(new ViolationPostMail(
                    $post->user->name,
                    $post->title,
                    $post->created_at,
                    $post->des
                ));
            }
        } catch (\Throwable $e) {
            // Cập nhật trạng thái lỗi cho bài viết
            Post::withoutGlobalScopes()
                ->where('id', $this->postId)
                ->update(['is_flag' => -1]);
            throw $e; // Laravel sẽ ghi lại vào bảng `failed_jobs`
        }
    }

    private function checkViolationByEmbedding(array $data, float $threshold = 0.6): bool
    {
        $sentenceEmbeddings = $data['embeddings'] ?? [];

        if (!is_array($sentenceEmbeddings) || empty($sentenceEmbeddings)) {
            return false;
        }
        foreach (ViolationVector::all() as $violation) {
            $violationVec = $violation->embedding;
            if (!is_array($violationVec)) continue;
            foreach ($sentenceEmbeddings as $sentenceVec) {
                $similarity = $this->cosineSimilarity($sentenceVec, $violationVec);
                if ($similarity >= $threshold) {
                    return true;
                }
            }
        }

        return false;
    }

    private function cosineSimilarity(array $a, array $b): float
    {
        if (count($a) !== count($b)) return 0;

        $dot = array_sum(array_map(fn($x, $y) => $x * $y, $a, $b));
        $magA = sqrt(array_sum(array_map(fn($x) => $x ** 2, $a)));
        $magB = sqrt(array_sum(array_map(fn($y) => $y ** 2, $b)));

        if ($magA == 0 || $magB == 0) return 0;

        return $dot / ($magA * $magB);
    }
}