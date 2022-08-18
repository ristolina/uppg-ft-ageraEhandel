<?php
namespace App\Helper;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ageraEhandelHelper
{
    private $client;
    private $content;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchData(): array
    {
        $response = $this->client->request(
            'GET',
            'https://dev14.ageraehandel.se/sv/api/product'
        );

        $this->content  = $response->getContent();
        //$content = $response->toArray();
        $this->content  = json_decode($this->content , true);
        //var_dump($content);
        $this->content  = $this->content ['products'];
        $this->content = $this->CleanArray();

        return $this->content;
    }

    /*
    * Clean array and remove entries with insufficient data, returns clean array.
    * Removes entries without 'artiklar_benamning' or without 'artikelkategorier_id'
    */
    private function cleanArray() : array
    {
        $arrayToClean = $this->content;

        for ($i=0; $i < count($arrayToClean) ; $i++)
        {
            if (!array_key_exists("artiklar_benamning", $arrayToClean[$i]))
            {
                unset($arrayToClean[$i]);
            } elseif (!array_key_exists("artikelkategorier_id", $arrayToClean[$i]))
            {
                unset($arrayToClean[$i]);
            }
        }

        return $arrayToClean;
    }
}
