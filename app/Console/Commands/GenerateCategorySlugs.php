<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use Illuminate\Support\Str;

class GenerateCategorySlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'categories:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $categories = Category::whereNull('slug')->orWhere('slug', '')->get();

        foreach ($categories as $category) {
            $category->slug = Str::slug($category->name);
            $category->save();
            $this->info('Slug generated for category: ' . $category->name);
        }

        $this->info('Slug generation completed for all categories.');
    }
}
