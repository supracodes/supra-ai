<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use LaravelZero\Framework\Commands\Command;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;
use OpenAI\Responses\Models\RetrieveResponse;
use function Termwind\render;
use function Termwind\terminal;

class ChatCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'chat {question : The question to ask}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Ask a question to the Supra AI';

    protected Collection $questions;

    protected Collection $models;

    public function __construct()
    {
        parent::__construct();

        $this->questions = collect();
        $this->models = collect(OpenAI::models()->list()->data)
            ->filter(fn (RetrieveResponse $model) => str($model->id)->contains('3.5'))
            ->map(fn (RetrieveResponse $model) => $model->id)
            ->values();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $model = $this->choice('Which model do you want to use?', $this->models->toArray(), 0);

        do {
            $response = $this->askQuestion(
                $this->ask('Type your question?'),
                $model
            );

            terminal()->clear();

            foreach ($response->choices as $choice) {
                $align = $choice->message->role === 'user' ? 'left' : 'right';

                render(<<<HTML
                    <div class="p-1">
                        <div class="text-{$align}">{$choice->message->content}</div>
                        <div class="text-{$align}">{$choice->message->content}</div>
                    </div>
                HTML);
            }
        } while($this->lastQuestion() != '!stop');
    }

    private function lastQuestion(): string
    {
        return $this->questions->last();
    }

    /**
     * @param string $question
     * @param string $model
     * @return CreateResponse
     */
    private function askQuestion(string $question, string $model): CreateResponse
    {
        return OpenAI::chat()->create([
            'model' => $model,
            'messages' => $this->questions->push([
                'role' => 'user',
                'content' => $question
            ])->toArray()
        ]);
    }
}
