<?php

$mid = "G-2SJVD8680E";  
$secret  = "ZsOSwY0sSlWNKzIm6gj5Lw"; 
$debug = 0;

// reading the client id from ga cookie
if(isset($_COOKIE["_ga"])) {
  list(, , $id1, $id2) = explode(".", $_COOKIE["_ga"], 4);
  $client_id = "$id1.$id2";
} else {
  exit("client id undefined");
}

// reading session id from ga session cookie
$ga4_session_cookie = "_ga_" . str_replace("G-", "", $mid);
if(isset($_COOKIE[$ga4_session_cookie])) {
  $cookie_parts = explode(".", $_COOKIE[$ga4_session_cookie]);
  $session_id = $cookie_parts[2];
}

// get the url and passed parameters
// we can make a custom one too
$url = $_SERVER["QUERY_STRING"];
parse_str($url, $params);
if (empty($params)) {
  exit("event parameters missing");
}

// create event parameters
$event_params = array();
foreach ($params as $key => $value) {
  $event_params[$key] = is_numeric($value) ? (int) $value : $value;
}
if(isset($session_id)) {
  $event_params['session_id'] = (int) $session_id;
  $event_params['engagement_time_msec'] = (int) 1000;
}
if($debug == 1) {
  $event_params['debug_mode'] = (int) 1;
}

// create payload
$data = array(
  "client_id" => $client_id,
  "non_personalized_ads" => false,
  "events" => array(
    array(
      "name" => "email_open",
      "params" => $event_params
    )
  )
);
$json = json_encode($data);

// define url with params
$url = "https://www.google-analytics.com/mp/collect?api_secret=".$secret."&measurement_id=".$mid;

// create HTTP header
$headers = array(
  "Content-Type: application/json"
);

// create HTTP request object
$options = array(
  "http" => array(
    "method" => "POST",
    "header" => implode("\r\n", $headers),
    "content" => $json
  )
);


$context = stream_context_create($options);

// send POST request
$response = file_get_contents($url, false, $context);

// download the debug output
if($debug == 1) {
  $file = fopen("email.json", "w");
  fwrite($file, $json);
  fclose($file);
  echo "$json";
}
?>