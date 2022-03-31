<?php

namespace FlexIdk\LaravelSoftDeletesBoolean;

use Illuminate\Support\ServiceProvider;
use FlexIdk\LaravelSoftDeletesBoolean\Console\MigrateCommand;

class SoftDeletesBooleanServiceProvider extends ServiceProvider
{
    /**
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MigrateCommand::class
            ]);
        }
    }

    /**
     * @return void
     */
    public function register()
    {
    }
}
