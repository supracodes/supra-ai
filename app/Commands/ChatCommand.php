<?php

namespace App\Commands;

use Ahc\CliSyntax\Highlighter;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;
use LaravelZero\Framework\Commands\Command;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;
use OpenAI\Responses\Models\RetrieveResponse;
use PhpPkg\CliMarkdown\CliMarkdown;
use Spatie\LaravelMarkdown\MarkdownRenderer;
use Spatie\ShikiPhp\Shiki;
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
//        $this->models = collect(OpenAI::models()->list()->data)
//            ->filter(fn (RetrieveResponse $model) => str($model->id)->contains('3.5'))
//            ->map(fn (RetrieveResponse $model) => $model->id)
//            ->values();
        $this->models = collect([]);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $model = 'gpt-3.5-turbo-0301';

        $questions = [
            [
                'role' => 'user',
                'content' => $this->argument('question')
            ]
        ];

        $clear = function () {
            terminal()->clear();
        };

        do {
            render(<<<HTML
                <div class="p-1">
                    <span>Asking question:</span>
                    <span class="text-yellow-500 ml-1 italic">Please wait...</span>
                </div>
            HTML);

            $response = OpenAI::chat()->create([
                'model' => $model,
                'messages' => $questions
            ]);

            $clear();

            foreach ($response->choices as $choice) {
                $responseText = $choice->message->content;
                $markdownResponse = app(CliMarkdown::class)->render($responseText);

                $this->newLine(2);
                $this->line($markdownResponse);

                $questions = [
                    ...$questions,
                    [
                        'role' => $choice->message->role,
                        'content' => $responseText
                    ]
                ];
            }

            $questions[] = [
                'role' => 'user',
                'content' => $this->ask('Type your next question')
            ];
        } while($this->lastQuestion() != '!stop');
    }

    private function lastQuestion()
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
