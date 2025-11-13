<?php
namespace App;

use GuzzleHttp\Client;

class ClickhouseExample {
    private Client $client;

    public function __construct() {
        $this->client = new Client(['base_uri' => 'http://clickhouse:8123/']);
    }

    public function query($sql) {
        $response = $this->client->post('', [
            'body' => $sql
        ]);
        return (string)$response->getBody();
    }

    public function createSalesTable(): string {
                $sql = <<<SQL
CREATE TABLE IF NOT EXISTS sales (
    sale_time DateTime,
    amount Float64
) ENGINE = MergeTree()
ORDER BY sale_time
SQL;
        return $this->query($sql);
    }


    public function insertSales(array $rows): string {
        if (empty($rows)) {
            return '';
        }
        $values = array_map(function($r) {
            $time = $r['sale_time'];
            $amt = (float)$r['amount'];
            return "('{$time}', {$amt})";
        }, $rows);

        $sql = "INSERT INTO sales (sale_time, amount) VALUES " . implode(',', $values);
        return $this->query($sql);
    }

    public function getAverageSalesPerDay(?string $from = null, ?string $to = null) {
        $where = [];
        if ($from) {
            $where[] = "sale_time >= '{$from}'";
        }
        if ($to) {
            $where[] = "sale_time < '{$to}'";
        }
        $whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

        $sql = "SELECT toDate(sale_time) AS day, avg(amount) AS avg_sales FROM sales {$whereSql} GROUP BY day ORDER BY day FORMAT JSON";

        $res = $this->query($sql);
        $decoded = json_decode($res, true);
        return $decoded === null ? $res : $decoded;
    }
}
