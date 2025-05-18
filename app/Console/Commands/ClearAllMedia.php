<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ClearAllMedia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:clearAll';
    protected $description = 'Delete ALL media files and records';

    public function handle()
    {
        Media::all()->each->delete();
        $this->info('All media records and files have been deleted!');
    }
}
