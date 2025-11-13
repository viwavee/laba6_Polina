<?php
namespace App;

use GuzzleHttp\Client;

class ElasticExample {
    private Client $client;

    public function __construct() {
        $this->client = new Client(['base_uri' => 'http://elastic:9200/']);
    }

    public function addDocument($index, $id, $body) {
        $this->client->put("{$index}/_doc/{$id}", [
            'json' => $body
        ]);
    }

    public function getDocument($index, $id) {
        $response = $this->client->get("{$index}/_doc/{$id}");
        return json_decode((string)$response->getBody(), true);
    }
}
