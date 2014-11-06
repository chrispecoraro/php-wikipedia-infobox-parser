<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class GetInfoBox extends Command {
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
    protected $description = 'Parses Wikipedia Infobox.';
    private $endpoint = 'wikipedia.org/w/api.php';
    private $protocol = 'http';
    private $locale = 'en';
    private $expectedReponseHeader = 'application/json';
    private $parameters =
        [
            'format' => 'json',
            'action' => 'query',
            'prop' => 'revisions',
            'rvprop' => 'content'
        ];

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
        $this->parameters['titles'] = $this->argument('title');
        $url = $this->buildApiURL($this->protocol, $this->locale, $this->endpoint, $this->parameters);
        $client = new GuzzleHttp\Client();
        $response = $client->get($url);

        if ($this->isSuccessful($response->getStatusCode()) && $this->isJson($response->getHeader('content-type'), $this->expectedReponseHeader)) {

            if (!empty($response->json()['query']) && !empty($response->json()['query']['pages'])) {
                $revision = array_pop($response->json()['query']['pages']);
                preg_match("/\\{\\{Infobox.*?\\}\\}/s", $revision['revisions'][0]['*'], $infobox);
                if (count($infobox) === 1) {
                    $lines = explode("\n", $infobox[0]);
                    $returnArray = [];
                    foreach ($lines as $line) {
                        if (strpos($line, '=')) {
                            list($key, $value) = explode('=', $line);
                            $returnArray[trim(str_replace('|', '', $key))] = trim($value);
                            echo '"' . $key . '","' . $value . "\"\n";
                        }
                    }

                } else {
                    if (count($infobox) > 1) {
                        Log::warning('more than one infobox found');
                    } else //$infofox is < 1
                    {
                        Log::warning('Infobox not found');
                    }
                }

            }
        } else {
            Log::warning('request not in json format');
        }
    }

    /**
     * @return string
     */
    protected function buildApiURL($protocol, $locale, $endpoint, $parameters)
    {
        return $protocol . '://' . $locale . '.' . $endpoint . '?' . http_build_query($parameters);
    }

    /**
     * @param $response
     * @return bool
     */
    protected function isSuccessful($code)
    {
        return $code == self::SUCCESS;
    }

    /**
     * @param $response
     * @return bool
     */
    protected function isJson($header, $expectedHeader)
    {

        return strpos($header, $expectedHeader) !== false;
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
