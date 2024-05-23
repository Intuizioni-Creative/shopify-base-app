function checkSessionToken() {
	//Getting session token
	shopify.idToken().then(token => {
		var formData = new FormData();
		formData.append('token', token);

		fetch('verify_session.php', {
			method: 'POST',
			header: {
				'Content-Type': 'application/json'
			},
			body: formData
		})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					axios({
						method: 'POST',
						url: 'authenticated_fetch.php',
						data: {
							shop: data.shop.host,
							query: `query {
								shop {
									email
									name
									url
								}
							}`
						},
						headers: {
							'Content-Type': 'application/json',
							'Authorization': 'Bearer: ' + token
						}
					}).then((response) => {
						// Do whatever you want with these data
						//console.log(response.data);
					});
				} else {
					// Session token error, do something...
				}
			});
	});
}