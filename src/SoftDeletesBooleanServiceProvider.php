<?php

namespace One23\LaravelSoftDeletesBoolean;

use Illuminate\Support\ServiceProvider;
use One23\LaravelSoftDeletesBoolean\Console\MigrateCommand;

class SoftDeletesBooleanServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MigrateCommand::class,
            ]);
        }
    }

    /**
     * @return void
     */
    public function register() {}
}
