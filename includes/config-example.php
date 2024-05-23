<?php
// Shopify Configuration
DEFINE('_API_KEY', ''); // Shopify App API Key
DEFINE('_SECRET_KEY', ''); // Shopify App Secret Key
DEFINE('_APP_SCOPES', '');  // Shopify scopes (for example: read_products,write_products,read_orders,write_orders,read_script_tags,write_script_tags)

// Application Server Configuration
DEFINE('_APP_URL', 'https://'); // Your App URL
DEFINE('_APP_NAME_URL', ''); // Your App subdirectory

// Database Configuration
DEFINE('_DATABASE_SERVER', 'localhost');
DEFINE('_DATABASE_USERNAME', 'root');
DEFINE('_DATABASE_PASSWORD', '');
DEFINE('_DATABASE_NAME', '');

// GraphQL Configuration
DEFINE('_GRAPHQL_API_URL', '/admin/api/2024-01/graphql.json');

// Billing Configuration
DEFINE('_BILLING_TYPE', 'none'); //one-time, recurring, none
DEFINE('_BILLING_NAME', '');
DEFINE('_BILLING_AMOUNT', '');
DEFINE('_BILLING_CURRENCY_CODE', 'USD');
DEFINE('_BILLING_TESTING', 'true');
DEFINE('_BILLING_RETURN_URL', ''); // (for example: https://admin.shopify.com/store/app-name-dev/apps/app-name/app-name)
?>