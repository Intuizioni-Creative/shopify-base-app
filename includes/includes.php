<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$parameters = $_GET;

// Set the configuration
include_once("config.php");

// MySQL database connection
include_once("mysql_connect.php");

// Shopify class
include_once("shopify.php");

// Set the Shopify object
$shopify = new Shopify();

// Verifico il token del negozio
include_once("check_token.php");

// Check whether to proceed with payment
include_once("billing.php");
?>