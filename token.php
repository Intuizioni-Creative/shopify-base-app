<?php
// Set the configuration
include_once("includes/config.php");

// MySQL database connection
include_once("includes/mysql_connect.php");

$parameters = $_GET;
$shop_url = $parameters['shop'];
$hmac = $parameters['hmac'];
$parameters = array_diff_key($parameters, array('hmac' => ''));
ksort($parameters);

$new_hmac = hash_hmac('sha256', http_build_query($parameters), _SECRET_KEY);

if (hash_equals($hmac, $new_hmac)) {
    // This is coming from Shopify and it\'s legit
    $access_token_endpoint = 'https://' . $shop_url . '/admin/oauth/access_token';
    $var = array(
        "client_id" => _API_KEY,
        "client_secret" => _SECRET_KEY,
        "code" => $parameters['code']
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $access_token_endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, count($var));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($var));
    $response = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($response, true);

    $query = "INSERT INTO shops (shop_url, access_token, install_date) VALUES ('" . $shop_url . "', '" . $response['access_token'] . "', NOW()) ON DUPLICATE KEY UPDATE access_token = '" . $response['access_token'] . "'";
    if ($mysql->query($query)) {
        echo "<script>top.window.location = 'https://" . $shop_url . "/admin/apps'</script>";
        die();
    }
} else {
    echo 'This is not coming from Shopify and probably someone is hacking';
}
?>