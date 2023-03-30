<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Poem;
use Illuminate\Support\Str;

class GeneratePoemSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:poem-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $poems = Poem::all();

        foreach ($poems as $poem) {
            $slug = Str::slug($poem->title);

            // Check if the generated slug already exists
            $count = Poem::where('slug', $slug)->where('id', '!=', $poem->id)->count();
            if ($count > 0) {
                $slug .= '-' . $poem->id;
            }

            $poem->slug = $slug;
            $poem->save();
        }

        $this->info('Poem slugs generated successfully!');
    }
}
