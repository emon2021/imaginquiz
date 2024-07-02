<?php

namespace App\Console\Commands;

use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Console\Command;

class QuizUnplublished extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:unplublished';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
{
    // Get all quizzes that are currently published
    $quizzes = Quiz::where('status', 1)->get();
    $now = Carbon::now()->timezone('Asia/Dhaka');

    foreach ($quizzes as $quiz) {
        $publish_time = Carbon::parse($quiz->publish_time)->timezone('Asia/Dhaka');
        $time_limit = $publish_time->addMinutes(2);

        // If the current time is past the expiration time, unpublish the quiz
        if ($now->greaterThan($time_limit)) {
            $quiz->status = 2;
            $quiz->publish_time = null;
            $quiz->update(); 
        }
    }
}

}
