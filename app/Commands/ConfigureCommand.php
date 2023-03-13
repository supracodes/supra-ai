<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class ConfigureCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'configure {--config= : The path to the configuration file}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Configure the application';

    /**
     * Execute the console command.
     *
     * @return void
 */
    public function handle(): void
    {
        $apiKey = $this->ask('What is your OpenAI API key? (https://platform.openai.com/account/api-keys)');

        $configFile = $this->option('config') ?? base_path('config.json');

        if (file_exists($configFile)) {
            $this->confirm('Configuration file already exists. Overwrite?', true);
        }

        file_put_contents(
            $configFile,
            json_encode([
                'openai' => [
                    'api_key' => $apiKey,
                ],
            ], JSON_PRETTY_PRINT)
        );

        $this->info("Configuration saved at " . $configFile);
    }
}
