<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GetInfoBox extends Command {

    protected $description = 'Parses Wikipedia Infobox.';

    const SUCCESS = 200;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'infobox:parse';
    /**
     * The console command description.
     *
     * @var string
     */

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        Chrispecoraro\WikipediaInfoboxParser\InfoboxRepository::getInfobox($this->argument('title'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('title', InputArgument::REQUIRED, 'The Title of the page'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
