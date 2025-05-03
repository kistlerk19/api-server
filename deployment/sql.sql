-- 1. Top Customers by Spending
SELECT 
    c.customer_id,
    c.name,
    c.email,
    c.country,
    SUM(oitem.quantity * oitem.unit_price) AS total_spent
FROM 
    customers c
JOIN 
    orders ord ON c.customer_id = ord.customer_id
JOIN 
    order_items oitem ON ord.order_id = oitem.order_id
GROUP BY 
    c.customer_id, c.name, c.email, c.country
ORDER BY 
    total_spent DESC;

-- 2. Monthly Sales Report (Only Shipped/Delivered)
SELECT 
    DATE_FORMAT(ord.order_date, '%Y-%m') AS month,
    SUM(oitem.quantity * oitem.unit_price) AS total_sales,
    COUNT(DISTINCT ord.order_id) AS order_count,
    SUM(oitem.quantity) AS items_sold
FROM 
    orders ord
JOIN 
    order_items oitem ON ord.order_id = oitem.order_id
WHERE 
    ord.status IN ('Shipped', 'Delivered')
GROUP BY 
    DATE_FORMAT(ord.order_date, '%Y-%m')
ORDER BY 
    month;

-- 3. Products Never Ordered
SELECT 
    p.product_id,
    p.name,
    p.category,
    p.price
FROM 
    products p
LEFT JOIN 
    order_items oitem ON p.product_id = oitem.product_id
WHERE 
    oitem.order_item_id IS NULL;

-- 4. Average Order Value by Country
SELECT 
    c.country,
    AVG(order_total.total) AS average_order_value
FROM 
    customers c
JOIN 
    orders ord ON c.customer_id = ord.customer_id
JOIN 
    (
        SELECT 
            order_id, 
            SUM(quantity * unit_price) AS total
        FROM 
            order_items
        GROUP BY 
            order_id
    ) AS order_total ON ord.order_id = order_total.order_id
GROUP BY 
    c.country
ORDER BY 
    average_order_value DESC;

-- 5. Frequent Buyers (More Than One Order)
SELECT 
    c.customer_id,
    c.name,
    c.email,
    c.country,
    COUNT(ord.order_id) AS order_count,
    SUM(oitem.quantity * oitem.unit_price) AS total_spent
FROM 
    customers c
JOIN 
    orders ord ON c.customer_id = ord.customer_id
JOIN 
    order_items oitem ON ord.order_id = oitem.order_id
GROUP BY 
    c.customer_id, c.name, c.email, c.country
HAVING 
    COUNT(ord.order_id) > 1
ORDER BY 
    order_count DESC, total_spent DESC;