<?php
// Checking the shopify token

// Get the shop information
$query = "SELECT * FROM shops WHERE shop_url = '" . $parameters['shop'] . "' LIMIT 1";
$result = $mysql->query($query);

// Check if the number of rows is less than 1, if it's less than 1, then that mean we need to redirect to the installation page
if ($result->num_rows < 1) {
    header("Location: install.php?shop=" . $_GET['shop']);
    exit();
}

$store_data = $result->fetch_assoc();

// Set the shop url and token
$shopify->set_url($parameters['shop']);
$shopify->set_token($store_data['access_token']);

// Check if shop response have error
$query = array("query" => "
{
    shop {
        id
        name
    }
}");

$shop = $shopify->graphql_api($query);
$response = json_decode($shop['body'], true);

if (array_key_exists('errors', $response)) {
	var_dump($response);
    die;
}
?>