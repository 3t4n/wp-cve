function ai_cancel_subscription() {
  if (
    confirm(
      "Are you sure you want to cancel your subscription? This action cannot be reversed"
    ) == true
  ) {
    var xmlhttp = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        location.reload();
      }
    };

    xmlhttp.open(
      "POST",
      "https://webator.es/gpt3_api/cancel_subscription.php",
      true
    );

    xmlhttp.setRequestHeader(
      "Content-type",
      "application/x-www-form-urlencoded"
    );

    xmlhttp.send();
  }
}

function show_delete_subscription(x){
	x.disabled = true;
	document.getElementById('ai-cancel-subscription').style.display='block';
}

function ai_edit_settings() {
  var name = document.getElementById("ai-name").value;
  var email = document.getElementById("ai-email").value;
  if (name == "") {
    document.getElementById("ai-name").classList.add("required");
    return;
  } else {
    document.getElementById("ai-name").classList.remove("required");
  }
  if (email == "") {
    document.getElementById("ai-email").classList.add("required");
    return;
  } else {
    document.getElementById("ai-email").classList.remove("required");
  }
  var xmlhttp = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");

  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      location.reload();
    }
  };

  xmlhttp.open("POST", "https://webator.es/gpt3_api/edit_settings.php", true);

  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xmlhttp.send("name=" + name + "&email=" + email);
}

jQuery(document).ready(function ($) {
  $(function () {
    function ai_get_settings() {
      var xmlhttp = window.XMLHttpRequest
        ? new XMLHttpRequest()
        : new ActiveXObject("Microsoft.XMLHTTP");

      xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
          const consulta = JSON.parse(this.responseText);
          var response = consulta.response;

          //Set name and email
          if (response.email) {
            document.getElementById("ai-email").value = response.email;
          }
          if (response.name) {
            document.getElementById("ai-name").value = response.name;
          }

          //Check if has free plan
          if (!response.subscription.has) {
            document.getElementById("ai-free-plan").style.display = "block";
          }
          //HAVE SUBSCRIPTION
          else {
            document.getElementById("ai-subscription-plan").style.display =
              "block";
            //Subscription name
            document.getElementById("ai-subscription-name").innerHTML =
              response.subscription.name;
            //Subscription n_posts
            document.getElementById("ai-subscription-n_posts").innerHTML =
              response.subscription.n_posts;
            //Subscription price
            document.getElementById("ai-subscription-price").innerHTML =
              response.subscription.price;
            //Subscription next payment
            document.getElementById("ai-subscription-next_payment").innerHTML =
              response.subscription.next_payment;

            ai_billing_table(response.subscription.invoices);
          }
        }
      };

      xmlhttp.open(
        "POST",
        "https://webator.es/gpt3_api/get_settings.php",
        true
      );

      xmlhttp.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded"
      );

      xmlhttp.send();
    }

    ai_get_settings();
    function ai_billing_table(invoices) {
      $("#ai-billing-table").DataTable().destroy();
      $("#ai-billing-tbody").empty();
      var billing = "";
      for (var i = 0; i < invoices.length; i += 1) {

        billing += `<tr>
							<td><a href="${invoices[i].pdf}" target="_blank">Invoice link</a></td>
							<td>${invoices[i].money} €</td>`;

        billing += `<td>${invoices[i].date}</td>
							</tr>`;
      }
      $("#ai-billing-table").DataTable().destroy();
      $("#ai-billing-table").find("tbody").append(billing);
      $("#ai-billing-table").DataTable().draw();
      //Order by date clicking twice
      $("#ai-billing-date").click();
      $("#ai-billing-date").click();
    }
  });
});
