<?php

function api_response(int $bus): array
{
    $api = "7fbfbb5f-96df-4555-b1c7-31d0994ab09f";
    $url = sprintf("https://api.um.warszawa.pl/api/action/busestrams_get/?resource_id=f2e5503e-927d-4ad3-9500-4ab9e55deb59&apikey=%s&type=1&line=%s",
        $api, $bus);

    $opts = array('http' => array('header' => "User-Agent: GeoAddressScript 3.7.6\r\n"));
    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);

    return json_decode($response, true);
}