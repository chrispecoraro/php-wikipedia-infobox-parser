<?php namespace Chrispecoraro\WikipediaInfoboxParser;

use Illuminate\Support\ServiceProvider;


class WikipediaInfoboxParserServiceServiceProvider extends ServiceProvider
{
    /**
     * Registers the service in the IoC Container
     *
     */
    public function register()
    {
        $this->app->bind('infoboxService', function($app)
        {
            return new InfoboxService(
                $app->make('InfoboxInterface')
            );
        });
    }
}
