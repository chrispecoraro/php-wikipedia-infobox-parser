<?php
namespace Chrispecoraro\WikipediaInfoboxParser;

use GuzzleHttp\Client as GuzzleClient;

/**
 * Class Infobox
 * @package Chrispecoraro
 */
class Infobox
{
    const SUCCESS = 200;


    private static $endpoint = 'wikipedia.org/w/api.php';
    private static  $protocol = 'http';
    private static  $locale = 'en';
    private static  $expectedReponseHeader = 'application/json';
    private static  $parameters =
        [
            'format' => 'json',
            'action' => 'query',
            'prop' => 'revisions',
            'rvprop' => 'content'
        ];


    /**
     * @return string
     */
    protected static function buildApiURL($protocol, $locale, $endpoint, $parameters)
    {
        return $protocol . '://' . $locale . '.' . $endpoint . '?' . http_build_query($parameters);
    }

    /**
     * @param $response
     * @return bool
     */
    protected static function isSuccessful($code)
    {
        return $code == self::SUCCESS;
    }

    /**
     * @param $response
     * @return bool
     */
    protected static function isJson($header, $expectedHeader)
    {

        return strpos($header, $expectedHeader) !== false;
    }

    /**
     * @param null $page
     */
    public static function getInfobox($page = null){
        self::$parameters['titles'] = $page;
        $url = self::buildApiURL(self::$protocol, self::$locale, self::$endpoint, self::$parameters);
        $client = new GuzzleClient();
        $response = $client->get($url);


        if (self::isSuccessful($response->getStatusCode()) && self::isJson($response->getHeader('content-type'), self::$expectedReponseHeader)) {

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
                        }
                    }
                    echo json_encode($returnArray);
                } else {
                    if (count($infobox) > 1) {
                        \Log::warning('more than one infobox found');
                    } else //$infofox is < 1
                    {
                        \Log::warning('Infobox not found');
                    }
                }

            }
        } else {
            \Log::warning('request not in json format');
        }

    }


}
