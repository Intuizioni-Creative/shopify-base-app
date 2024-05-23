<?php
// Set the configuration
include_once("includes/config.php");

$shop = $_GET['shop'];

$redirect_uri = _APP_URL . '/' . _APP_NAME_URL . '/token.php';
$nonce = bin2hex(random_bytes(12));
$access_mode = 'per-user';

$oauth_url = 'https://' . $shop . '/admin/oauth/authorize?client_id=' . _API_KEY . '&scope=' . _APP_SCOPES . '&redirect_uri=' . urlencode($redirect_uri) . '&state=' . $nonce . '&grant_options[]=' . $access_mode;

header("Location: " . $oauth_url);
exit();
?>