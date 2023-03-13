<?php

declare(strict_types=1);

namespace NunoMaduro\LaravelPot\Console\Commands;

use Illuminate\Console\Command;

/**
 * @internal
 */
final class PotListCommand extends PotCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'pot:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lists the container bindings / instances';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $bindings = $this->container->getBindings();

        $this->section('Bindings');

        foreach ($bindings as $abstract => $_) {
            $instance = $this->tryToResolve($abstract);

            $this->item($abstract, $instance);
        }

        $instances = (fn () => $this->instances)->call($this->container);

        $this->section('Instances');

        foreach ($instances as $abstract => $instance) {
            if (isset($bindings[$abstract])) {
                continue;
            }

            $this->item($abstract, $instance);
        }

        $this->newLine();

        return self::SUCCESS;
    }
}
