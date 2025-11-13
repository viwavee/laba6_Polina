<?php
require __DIR__ . '/vendor/autoload.php';

use App\RedisExample;
use App\ElasticExample;
use App\ClickhouseExample;

// Redis
echo "<h2>🚀 Redis Example</h2>";
$redis = new RedisExample();
$redis->setValue('user:101', json_encode(['name' => 'Alice', 'age' => 25]));
echo $redis->getValue('user:101');

// Elasticsearch
echo "<h2>🔍 Elasticsearch Example</h2>";
$elastic = new ElasticExample();
$elastic->addDocument('users', 1, ['name' => 'Alice', 'age' => 25]);
print_r($elastic->getDocument('users', 1));

// ClickHouse
echo "<h2>📊 ClickHouse Example</h2>";
$clickhouse = new ClickhouseExample();
echo "<h3>Вариант 18 — Средние продажи по дням</h3>";
echo "<pre>";
echo "Создаём таблицу sales:\n";
echo $clickhouse->createSalesTable();

$sample = [
	['sale_time' => '2025-11-10 10:15:00', 'amount' => 120.5],
	['sale_time' => '2025-11-10 14:30:00', 'amount' => 80.0],
	['sale_time' => '2025-11-11 09:20:00', 'amount' => 200.0],
	['sale_time' => '2025-11-11 17:45:00', 'amount' => 50.0],
	['sale_time' => '2025-11-12 12:00:00', 'amount' => 300.0],
	['sale_time' => '2025-11-12 13:00:00', 'amount' => 150.0],
];

echo "Вставляем пример данных:\n";
echo $clickhouse->insertSales($sample);

echo "\nРезультат — средние продажи по дням:\n";
$result = $clickhouse->getAverageSalesPerDay();
if (is_array($result) && isset($result['data'])) {

	foreach ($result['data'] as $row) {
		echo $row['day'] . " => " . $row['avg_sales'] . "\n";
	}
} else {

	print_r($result);
}
echo "</pre>";
