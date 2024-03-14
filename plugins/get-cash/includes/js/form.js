jQuery(document).ready(function ($) {
	// console.log("get_cash", get_cash_form_object);
	// console.log(site_url);
	// console.log(cashapp);
	// console.log(venmo);
	// console.log(zelle);
	// console.log(paypal);

	// https://stackoverflow.com/a/19550497
	var site_url = document.location.origin;
	console.log(site_url);

	// $("input[name='GetCashPaymentMethod']").find("option:selected").val();
	$("input[name='GetCashPaymentMethod']").change(function () {
		var selected = $(this).val();
		// var selected = $("input[name='GetCashPaymentMethod']").find("option:selected").val();
		var receiver = $(this).data("receiver");
		console.log(selected, receiver);
		var sender = "Sender";
		var receiverurl = "#";
		if (selected == "cashapp") {
			// receiver = cashapp;
			sender = "Your $cashtag";
			receiverurl = `https://cash.app/${receiver}`;
		} else if (selected == "venmo") {
			// receiver = `@${venmo}`;
			sender = "Your Venmo username";
			receiverurl = `https://venmo.com/${receiver}`;
		} else if (selected == "zelle") {
			// receiver = zelle;
			console.log();
		} else if (selected == "paypal") {
			// receiver = paypal;
			sender = "Your Paypal.me username";
			receiverurl = `https://paypal.me/${receiver}`;
		} else {
		}

		if (typeof receiver != undefined) {
			console.log(receiverurl);
			$("#get-cash-sender").html(`${sender} <span class="required">*</span>`);
			$("#get-cash-form-receiver").html(`<div class="form-row form-row-wide">
				 <label class="get-cash-form-fields get-cash-form-label"><a href="${receiverurl}" target="_blank">Receiver:</a></label>
				 <input class="get-cash-form-fields get-cash-form-input get-cash-readonly" name="GetCashReceiverName" type="text" readonly value="${receiver}">
				</div>`);
		}
	});

	// Variable to hold request
	var request;
	// Bind to the submit event of our form
	$("#get-cash-form-id").submit(function (event) {
		// Prevent default posting of form - put here to work in case of errors
		event.preventDefault();

		// Abort any pending request
		if (request) {
			request.abort();
		}
		// setup some local variables
		var form = $(this).serialize();
		$.ajax({
			url: site_url + "/wp-json/get-cash/v1/form",
			type: "post",
			data: form,
			success: function (response) {
				console.log(response);
				console.log(response.status);
				if (response.status == "success") {
					$("#get-cash-form-id").hide();
					let receiver = response.receiver;
					let receiver_email = response.receiver_email;
					let receiver_no = response.receiver_no;
					let sender = response.sender;
					let sender_email = response.sender_email;
					let sender_no = response.sender_no;
					let payment_method = response.payment_method;
					let currency = response.currency;
					let amount = response.amount;
					let note = response.note;
					let payment_url;
					let payment_qr;
					let payment_img;
					let html;
					console.log(
						receiver,
						receiver_email,
						receiver_no,
						sender,
						sender_email,
						sender_no,
						payment_method,
						currency,
						amount,
						note
					);
					if (
						payment_method.toLowerCase() === "cash app" ||
						payment_method.toLowerCase() === "cashapp"
					) {
						payment_url = `https://cash.app/${receiver}/${amount}`;
						payment_qr = `https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=150x150&chl=https://cash.app/${receiver}/${amount}`;
						payment_img =
							site_url + `/wp-content/plugins/get-cash/images/cashapp.png`;
						html = `Send via ${payment_method}
						<a href="${payment_url}" target="_blank">
						<img class="get-cash-form-img" alt="payment wallet link" src="${payment_img}">
						</a>
						or Scan
						<a href="${payment_url}" target="_blank">
						<img class="get-cash-form-img" alt="payment wallet link" src="${payment_qr}">
						</a>`;
					} else if (payment_method.toLowerCase() === "venmo") {
						payment_url = `https://venmo.com/${receiver}?txn=pay&amount=${amount}&note=${encodeURI(
							note
						)}`;
						payment_qr = `https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=150x150&chl=https://venmo.com/${receiver}?txn=pay&amount=${amount}&note=${encodeURI(
							note
						)}`;
						payment_img =
							site_url + `/wp-content/plugins/get-cash/images/venmo.png`;
						html = `Send via ${payment_method}
						<a href="${payment_url}" target="_blank">
						<img class="get-cash-form-img" alt="payment wallet link" src="${payment_img}">
						</a>
						or Scan
						<a href="${payment_url}" target="_blank">
						<img class="get-cash-form-img" alt="payment wallet link" src="${payment_qr}">
						</a>`;
					} else if (payment_method.toLowerCase() === "paypal") {
						payment_url = `https://paypal.me/${receiver}/${amount}`;
						payment_qr = `https://chart.googleapis.com/chart?cht=qr&chld=L|0&chs=150x150&chl=https://paypal.me/${receiver}/${amount}`;
						payment_img =
							site_url + `/wp-content/plugins/get-cash/images/paypal.png`;
						html = `Send via ${payment_method}
						<a href="${payment_url}" target="_blank">
						<img class="get-cash-form-img" alt="payment wallet link" src="${payment_img}">
						</a>
						or Scan
						<a href="${payment_url}" target="_blank">
						<img class="get-cash-form-img" alt="payment wallet link" src="${payment_qr}">
						</a>`;
					} else {
						html = `Please use the following details to send via ${payment_method}:<br>
						<div>Receiver Information: <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverName" type="text" readonly value="${receiver}"><span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span><br></div>`;
						if (receiver_email)
							html += `<div>Receiver Email: <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverEmail" type="text" readonly value="${receiver_email}"><span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span><br></div>`;
						if (receiver_no)
							html += `<div>Receiver Phone: <input class="get-cash-form-fields get-cash-form-input get-cash-readonly copytxt" name="GetCashReceiverNo" type="text" readonly value="${receiver_no}"><span class="position-relative copybtn" style="float: right;z-index: 1;">Copy</span><br></div>`;
						html += `Amount: <strong>${amount}</strong><br>
						Note: <strong>${note}</strong>`;
						// html = `Please use the following details to send via ${payment_method}:<br>
						// Receiver Information: <strong>${receiver}</strong><br>`;
						// if (receiver_email) html += `Receiver Email: <strong>${receiver_email}</strong><br>`;
						// if (receiver_no) html += `Receiver Phone: <strong>${receiver_no}</strong><br>`;
						// html += `Amount: <strong>${amount}</strong><br>
						// Note: <strong>${note}</strong>`;
					}

					$("#get-cash-form-result").html(html);
				} else {
					$("#get-cash-form-id").hide();
					$("#get-cash-form-result").html(response.message);
				}
			},
			error: function (jqXHR, textStatus, errorThrown) {
				console.error(textStatus, errorThrown);
			},
		});
	});
});
