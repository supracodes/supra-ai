<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use function Termwind\render;

class ConfigureCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = <<<SIGNATURE
        configure {--api-key= : The Open AI Apikey} 
                  {--org= : Identifier for this organization} 
                  {--config= : The path to the configuration file}
    SIGNATURE;

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
        if (!$apiKey = $this->option('api-key')) {
            $apiKey = $this->ask('What is your OpenAI API key? (https://platform.openai.com/account/api-keys)');
        }

        if (!$organization = $this->option('org')) {
            $organization = $this->ask('What is your OpenAI organization? (https://platform.openai.com/account/org-settings)');
        }

        $configFile = $this->option('config') ?? home_path('config.json');

        file_put_contents(
            $configFile,
            json_encode([
                'openai' => [
                    'api_key' => $apiKey,
                    'organization' => $organization
                ],
            ], JSON_PRETTY_PRINT)
        );

        render(<<<HTML
            <div class="m-1">
                <div class="text-green-500">Configuration saved</div>
                <div>
                    The configuration file is located at <a href="{$configFile}" class="font-bold text-yellow-500 underline">$configFile</a>
                </div>
            </div>
        HTML);
    }
}
