<?php

namespace App\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DeleteFolderBeforeNowHour extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-folder-before-now-hour';

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
        $path = 'temp/' . date("H", time()-(60*60));
        $ok = Storage::disk('public')->deleteDirectory($path);
        if ($ok){
            $this->info("Delete folder {$path} success");
        }else{
            $this->error("Delete folder {$path} error");
        }
    }
}
