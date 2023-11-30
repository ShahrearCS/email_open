<?php

$measurement_id = 'G-2SJVD8680E';
$api_secret = 'ZsOSwY0sSlWNKzIm6gj5Lw';

function getCookie($name) {
    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
    foreach ($cookies as $cookie) {
        $parts = explode('=', trim($cookie));
        $cookieName = $parts[0];
        $cookieValue = $parts[1];
        if ($cookieName === $name) {
            return $cookieValue;
        }
    }
    return null;
}

$gaCookie = getCookie('_ga');
list(, , $id1, $id2) = $gaCookie ? explode('.', $gaCookie) : [];
$clientId = $id1 && $id2 ? $id1 . '.' . $id2 : null;

if ($clientId) {
    $url = "https://www.google-analytics.com/mp/collect?measurement_id={$measurement_id}&api_secret={$api_secret}";

    $data = [
        'client_id' => $clientId,
        'events' => [
            [
                'name' => 'email_open',
                'params' => [],
            ],
        ],
    ];

    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => json_encode($data),
        ],
    ];

    $context = stream_context_create($options);
    file_get_contents($url, false, $context);
}

?>
