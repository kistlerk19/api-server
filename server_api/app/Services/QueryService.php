<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class QueryService
{
    public function getTopCustomersBySpending()
    {
        return DB::select("
            SELECT
                c.customer_id,
                c.name,
                c.email,
                c.country,
                SUM(oi.quantity * oi.unit_price) AS total_spent
            FROM
                customers c
            JOIN
                orders o ON c.customer_id = o.customer_id
            JOIN
                order_items oi ON o.order_id = oi.order_id
            GROUP BY
                c.customer_id, c.name, c.email, c.country
            ORDER BY
                total_spent DESC
        ");
    }


    public function getMonthlySalesReport()
    {
        return DB::select("
            SELECT
                DATE_FORMAT(o.order_date, '%Y-%m') AS month,
                SUM(oi.quantity * oi.unit_price) AS total_sales,
                COUNT(DISTINCT o.order_id) AS order_count,
                SUM(oi.quantity) AS items_sold
            FROM
                orders o
            JOIN
                order_items oi ON o.order_id = oi.order_id
            WHERE
                o.status IN ('Shipped', 'Delivered')
            GROUP BY
                DATE_FORMAT(o.order_date, '%Y-%m')
            ORDER BY
                month
        ");
    }

    public function getProductsNeverOrdered()
    {
        return DB::select("
            SELECT
                p.product_id,
                p.name,
                p.category,
                p.price
            FROM
                products p
            LEFT JOIN
                order_items oi ON p.product_id = oi.product_id
            WHERE
                oi.order_item_id IS NULL
        ");
    }

    public function getAverageOrderValueByCountry()
    {
        return DB::select("
            SELECT
                c.country,
                AVG(order_total.total) AS average_order_value
            FROM
                customers c
            JOIN
                orders o ON c.customer_id = o.customer_id
            JOIN
                (
                    SELECT
                        order_id,
                        SUM(quantity * unit_price) AS total
                    FROM
                        order_items
                    GROUP BY
                        order_id
                ) AS order_total ON o.order_id = order_total.order_id
            GROUP BY
                c.country
            ORDER BY
                average_order_value DESC
        ");
    }

    public function getFrequentBuyers()
    {
        return DB::select("
            SELECT
                c.customer_id,
                c.name,
                c.email,
                c.country,
                COUNT(o.order_id) AS order_count,
                SUM(oi.quantity * oi.unit_price) AS total_spent
            FROM
                customers c
            JOIN
                orders o ON c.customer_id = o.customer_id
            JOIN
                order_items oi ON o.order_id = oi.order_id
            GROUP BY
                c.customer_id, c.name, c.email, c.country
            HAVING
                COUNT(o.order_id) > 1
            ORDER BY
                order_count DESC, total_spent DESC
        ");
    }
}
