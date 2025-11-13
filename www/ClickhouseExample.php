<?php

namespace App;

use App\Helpers\ClientFactory;

class ClickhouseExample
{
    private $client;

    public function __construct()
    {
        $this->client = ClientFactory::make('http://localhost:8123/');
    }

    public function query($sql)
    {
        $response = $this->client->post('', [
            'body' => $sql
        ]);
        return $response->getBody()->getContents();
    }

    /**
     * Создаём таблицу продаж
     */
    public function createSalesTable()
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS sales (
                date Date,
                product String,
                amount Float64
            ) ENGINE = MergeTree()
            ORDER BY date
        ";
        return $this->query($sql);
    }

    /**
     * Добавляем запись о продаже
     */
    public function insertSale($date, $product, $amount)
    {
        $sql = "
            INSERT INTO sales (date, product, amount)
            VALUES ('$date', '$product', $amount)
        ";
        return $this->query($sql);
    }

    /**
     * Средние продажи по дням
     */
    public function getAverageSalesByDay()
    {
        $sql = "
            SELECT date, avg(amount) AS avg_sales
            FROM sales
            GROUP BY date
            ORDER BY date
        ";
        return $this->query($sql);
    }
}
