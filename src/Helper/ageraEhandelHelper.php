<?php
namespace App\Helper;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ageraEhandelHelper
{
    private $client;

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

        $content = $response->getContent();
        //$content = $response->toArray();
        $content = json_decode($content, true);
        //var_dump($content);
        $content = $content['products'];

        return $content;
    }
}
