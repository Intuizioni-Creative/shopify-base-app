<?php
$billing = true;
$mysqlTable = '';
$graphqlOp = '';
$graphqlOpCreateQuery = '';

switch (_BILLING_TYPE) {
    case 'one-time':
        $mysqlTable = 'one_time_billings';
        $graphqlField = 'appPurchaseOneTimeCreate';
        $graphqlOp = 'AppPurchaseOneTime';
        $graphqlOpCreateQuery = '
            mutation {
                appPurchaseOneTimeCreate (
                    name: "' . _BILLING_NAME . '"
                    price: {
                        amount: ' . _BILLING_AMOUNT . '
                        currencyCode: ' . _BILLING_CURRENCY_CODE . '
                    }
                    test: ' . _BILLING_TESTING . '
                    returnUrl: "' . _BILLING_RETURN_URL . '"
                )
                {
                    userErrors {
                        field
                        message
                    }
                    appPurchaseOneTime {
                        createdAt
                        id
                    }
                    confirmationUrl
                }
            }';
        break;

    case 'recurring':
        $mysqlTable = 'recurring_billings';
        $graphqlField = 'appSubscriptionCreate';
        $graphqlOp = 'AppSubscription';
        $graphqlOpCreateQuery = '
            mutation {
                appSubscriptionCreate (
                    name: "' . _BILLING_NAME . '"
                    lineItems: {
                        plan: {
                            appRecurringPricingDetails: {
                                price: {
                                    amount: ' . _BILLING_AMOUNT . '
                                    currencyCode: ' . _BILLING_CURRENCY_CODE . '
                                }
                            }
                        }
                    }
                    test: ' . _BILLING_TESTING . '
                    returnUrl: "' . _BILLING_RETURN_URL . '"
                )
                {
                    userErrors {
                        field
                        message
                    }
                    appSubscription {
                        createdAt
                        id
                    }
                    confirmationUrl
                }
            }';
        break;

    case 'none':
		$billing = false;
        break;

    default:
        echo 'Please set the billing type in config.php.';
        die;
        break;
}

if ($billing) {
	// Get the shop informations
	$query = "SELECT * FROM " . $mysqlTable . " WHERE shop_url = '" . $shopify->get_url() . "' LIMIT 1";
	$result = $mysql->query($query);

	$billing_data = $result->fetch_assoc();

	if (isset($_GET['charge_id']) || $result->num_rows > 0) {
		$cid = isset($_GET['charge_id']) ? $_GET['charge_id'] : $billing_data['charge_id'];

		$query = array(
			"query" => '{
				node(id: "gid://shopify/' . $graphqlOp . '/' . $cid . '") {
					... on ' . $graphqlOp . ' {
						id
						status
					}
				}
			}'
		);

		$check_charge = $shopify->graphql_api($query);
		$check_charge = json_decode($check_charge['body'], true);

		if (!empty($check_charge['data']['node'])) {
			if ($check_charge['data']['node']['status'] !== 'ACTIVE') {
				echo "Oh! It looks like you havent't paid out Shopify app yet. So we cen't allow you to use the app. Apologize";
				die;
			}
		} else {
			echo "Woah! Looks like you're trying to create your own charge ID. You cannot do that.";
			die;
		}

		$shop_url = $shopify->get_url();
		$charge_id = $check_charge['data']['node']['id'];
		$charge_id = explode("/", $charge_id);
		$charge_id = $charge_id[array_key_last($charge_id)];

		$gid = $check_charge['data']['node']['id'];

		$status = $check_charge['data']['node']['status'];

		$query = "INSERT INTO " . $mysqlTable . " (shop_url, charge_id, gid, status) VALUES ('" . $shop_url . "', '" . $charge_id . "', '" . $gid . "', '" . $status . "') ON DUPLICATE KEY UPDATE status = '" . $status . "'";
		$mysql->query($query);
	} else {
		$query = array("query" => $graphqlOpCreateQuery);

		$charge = $shopify->graphql_api($query);

		$charge = json_decode($charge['body'], true);
		
		echo "<script>top.window.location = '" . $charge['data'][$graphqlField]['confirmationUrl'] . "'</script>";
		die;
	}
}