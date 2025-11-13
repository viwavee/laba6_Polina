<?php
namespace App;

use Predis\Client as PredisClient;

class RedisExample {
    private PredisClient $client;

    public function __construct() {
        $this->client = new PredisClient([
            'scheme' => 'tcp',
            'host'   => getenv('REDIS_HOST') ?: 'redis',
            'port'   => getenv('REDIS_PORT') ?: 6379,
        ]);
    }

    public function setValue($key, $value) {
        $val = is_string($value) ? $value : json_encode($value);
        return $this->client->set($key, $val);
    }

    public function getValue($key) {
        return $this->client->get($key);
    }
}
