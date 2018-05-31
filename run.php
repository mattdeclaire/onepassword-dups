<?php

$domain = readline("Enter your signin address (e.g. subdomain.1password.com): ");
$email = readline("Enter your signin email: ");
$secret = readline("Enter your secret key: ");

$sess_id = `op signin $domain $email $secret --output=raw`;
$json = `op list items --session=$sess_id`;
$data = json_decode($json);

$items = [];
foreach ($data as $item) {
    $uuid = $item->uuid;
    unset($item->uuid);

    $json = json_encode($item);
    $hash = md5($json);

    $item->uuid = $uuid;
    
    if (!isset($items[$hash])) {
        $items[$hash] = [];
    }

    $items[$hash][] = $item;
}

$items = array_filter($items, function($items) {
    return count($items) > 1;
});

echo json_encode($items, JSON_PRETTY_PRINT);