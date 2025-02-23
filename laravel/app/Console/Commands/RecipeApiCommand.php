<?php

namespace App\Console\Commands;

use App\Services\RecipeApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class RecipeApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-api-call {count=1 : Number of recipes to fetch}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a daily API call to the Spoonacular API to fetch recipes';

    /**
     * Service to interact with the Spoonacular API
     *
     * @var RecipeApiService
     */
    private RecipeApiService $recipeService;

    /**
     * Create a new command instance.
     *
     * @param RecipeApiService $recipeService
     */
    public function __construct(RecipeApiService $recipeService)
    {
        parent::__construct();
        $this->recipeService = $recipeService;
    }

    /**
     * Execute the console command.
     * @return int Command status
     */
    public function handle(): int
    {
        $count = $this->argument('count');
        if (!is_numeric($count) || $count < 1) {
            $this->error('Invalid count argument. Please provide a number greater than 0.');
            return Command::FAILURE;
        }

        if ($count > 5) {
            $this->error('You can only fetch up to 5 recipes at a time.');
            return Command::FAILURE;
        }

        $count = (int) $count;

        $lastCallDate = Cache::get('last_api_call_date');
        $today = now()->format('Y-m-d');

        if ($lastCallDate === $today) {
            $this->error('API call already made today. Please try again tomorrow
            and keep in mind that you can only make one API call per day.
            Otherwise, it costs money.');
            return Command::FAILURE;
        }

        Cache::put(
            'last_api_call_date',
            $today,
            now()->addDays(2)
        );

        $this->info("Fetching {$count} recipes...");
        $result = $this->recipeService
            ->fetchAndStoreRecipes($count);

        if (isset($result['error']) && is_string($result['error'])) {
            $this->error($result['error']);
            return Command::FAILURE;
        }

        $this->info("Successfully fetched {$count} recipes.");
        return Command::SUCCESS;
    }
}
