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
        $quiz = Quiz::all();
        foreach ($quiz as $q) {
            if ($q->status == 1) {
                $publish_time = $q->publish_time;
                $expired_time = $q->expired_time;

                $time_limit = Carbon::parse($publish_time)->addHours($expired_time);

                if ($publish_time < $time_limit) {
                    $q->status = 2;
                    $q->publish_time = null;
                    $q->update();
                }
            }
        }
    }
}
