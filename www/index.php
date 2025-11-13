<?php

require 'vendor/autoload.php';

use App\RedisExample;
use App\ElasticExample;
use App\ClickhouseExample;

// Redis
$redis = new RedisExample();
echo "<h2>Redis</h2>";
echo $redis->setValue('user:101', json_encode(['name' => 'Alice', 'age' => 25])) . "<br>";
echo $redis->getValue('user:101') . "<hr>";

// Elasticsearch
$elastic = new ElasticExample();
echo "<h2>Elasticsearch</h2>";
echo $elastic->indexDocument('books', 1, ['title' => '1984', 'author' => 'Orwell']) . "<br>";
echo $elastic->search('books', ['author' => 'Orwell']) . "<hr>";

// ClickHouse аналитика
$click = new ClickhouseExample();
echo "<h2>ClickHouse: Аналитика продаж</h2>";
$click->createSalesTable();

// Добавим тестовые данные
$click->insertSale('2025-11-10', 'iPhone', 2500);
$click->insertSale('2025-11-10', 'AirPods', 500);
$click->insertSale('2025-11-11', 'MacBook', 4000);
$click->insertSale('2025-11-11', 'iPad', 1800);

// Получим средние продажи по дням
echo "<pre>" . $click->getAverageSalesByDay() . "</pre>";
