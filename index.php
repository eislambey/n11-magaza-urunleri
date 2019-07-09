<?php

use DiDom\Document;

require 'vendor/autoload.php';

$shopName = "uygunsaatler";

$page = (int) ($_GET['page'] ?? 1);
$url = "https://www.n11.com/magaza/$shopName?pg=$page";

$dom = new Document($url, true);
$items = $dom->find('.resultListGroup .columnContent');

$products = [];
$productCount = (int) $dom->first('.resultText strong')->text();
$pageCount = ceil($productCount / 28) + 1;

foreach ($items as $key => $item) {
    $name = trim($item->first('.productName')->text());
    $img = $item->first('img.lazy')->attr('data-original');

    $price = trim(
        strstr($item->first('ins')->text(), 'TL', true)
    );
    if ($item->has('del')) {
        $oldPrice = str_replace('TL', '₺', $item->first('del')->text());
    } else {
        $oldPrice = $price;
    }
    $link = $item->first('a')->attr('href');

    $products[] = [
        'name' => $name,
        'img' => $img,
        'price' => $price,
        'link' => $link,
        'oldPrice' => $oldPrice,
    ];
}

require 'views/index.phtml';
