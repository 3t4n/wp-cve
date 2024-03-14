
const stripe = Stripe(
  "pk_live_51LcZjBFnusA8ZhWluRs2Plg5H4jo72uRvc0qJ3xOyYVwTTaaBrvF5T7RG5HKyDBrnS8I43eS5CplI8edvvd1yzKJ00q7fztJE3"
);
let elements;

checkStatus();

// Fetches a payment intent and captures the client secret

async function ai_post_initialize(price, n_posts) {
  setLoading(true);

  var xmlhttp = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");

  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const response = JSON.parse(this.responseText);

      console.log(response.clientSecret);

      const clientSecret = response.clientSecret;

      elements = stripe.elements({ clientSecret });

      const paymentElement = elements.create("payment");

      paymentElement.mount("#payment-element");

      setLoading(false);
    } else if (this.readyState == 4 && this.status != 200) {
      console.log("Error");
    }
  };

  xmlhttp.open("POST", "https://webator.es/gpt3_api/single-posts-checkout.php", true);

  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xmlhttp.send("price=" + price + "&n_posts=" + n_posts);
}

async function handleSubmit(e) {
  e.preventDefault();

  setLoading(true);

  const { error } = await stripe.confirmPayment({
    elements,

    confirmParams: {
      // Make sure to change this to your payment completion page

      return_url: window.location.href,
    },
  });

  // This point will only be reached if there is an immediate error when

  // confirming the payment. Otherwise, your customer will be redirected to

  // your `return_url`. For some payment methods like iDEAL, your customer will

  // be redirected to an intermediate site first to authorize the payment, then

  // redirected to the `return_url`.

  if (error.type === "card_error" || error.type === "validation_error") {
    showMessage(error.message);
  } else {
    showMessage("An unexpected error occurred.");
  }

  setLoading(false);
}

// Fetches the payment intent status after payment submission

async function checkStatus() {
  const clientSecret = new URLSearchParams(window.location.search).get(
    "payment_intent_client_secret"
  );

  if (!clientSecret) {
    return;
  }

  const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

  switch (paymentIntent.status) {
    case "succeeded":
      console.log(paymentIntent);

      showMessage("Payment succeeded!");

      console.log(paymentIntent.client_secret);

      break;

    case "processing":
      showMessage("Your payment is processing.");

      break;

    case "requires_payment_method":
      showMessage("Your payment was not successful, please try again.");

      break;

    default:
      showMessage("Something went wrong.");

      break;
  }
}

// ------- UI helpers -------

function showMessage(messageText) {
  const messageContainer = document.querySelector("#payment-message");

  messageContainer.classList.remove("hidden");

  messageContainer.textContent = messageText;

  setTimeout(function () {
    messageContainer.classList.add("hidden");

    messageText.textContent = "";
  }, 4000);
}

// Show a spinner on payment submission

function setLoading(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner

    document.querySelector("#submit").disabled = true;

    document.querySelector("#spinner").classList.remove("hidden");

    document.querySelector("#button-text").classList.add("hidden");
  } else {
    document.querySelector("#submit").disabled = false;

    document.querySelector("#spinner").classList.add("hidden");

    document.querySelector("#button-text").classList.remove("hidden");
  }
}

/*

-

-

-

-

-

-

ENDCHECKOUT

-

-

-

-

-

-

-

*/

function show_pay(pro = false) {
  const div = document.createElement("div");

  div.setAttribute("class", "popup-container");

  div.setAttribute("id", "mail-pop");


    price = document.querySelector("#price_text").getAttribute("data-price");
    n_posts = document.getElementById("n_posts").value;
  div.innerHTML =
    `

	<div class="popup" id="boxpop">

		<form id="payment-form" class="bg-white">

    <h3 class="text-center mb-5">${n_posts} posts</h3>

      <div id="payment-element"></div>

      <button id="submit">

        <div class="spinner hidden" id="spinner"></div>

        <span id="button-text">Pay <strong>` +
    price +
    `€</strong></span>

      </button>

    </form>

  </div>`;

  document.getElementById("ai-payment-pop-cont").appendChild(div);

  ai_post_initialize(price, n_posts);

  document
    .querySelector("#payment-form")
    .addEventListener("submit", handleSubmit);

  document.getElementById("mail-pop").onclick = function (e) {
    container = document.getElementById("boxpop");

    if (container !== e.target && !container.contains(e.target)) {
      document.getElementById("mail-pop").remove();
    }
  };
}


/*
var range = document.querySelector("#n_posts");

range.addEventListener(
  "input",
  function () {
    document.getElementById("n_posts_text").innerHTML =
      numberWithCommas(this.value)/1000 + " posts";

    document.getElementById("n_posts").innerHTML =
      n_posts(this.value) + " posts aprox";

    document.getElementById("price_text").innerHTML =
      this.value + "€";
  },
  false
);
*/
// Variables iniciales
let pricePerPost = 0.3; // En euros
let maxDiscount = 0.20; // 20% máximo

// Función para calcular el descuento y el precio total
function updateValues() {
    let nPosts = document.getElementById("n_posts").value; // Obtiene el número de posts
    let discount = Math.min((nPosts - 10) * 0.002, maxDiscount); // Calcula el descuento
    let totalPrice = (nPosts * pricePerPost) * (1 - discount); // Calcula el precio total

    // Actualiza los valores en la página
    document.getElementById("n_posts_text").innerText = `${nPosts} posts`;
    document.getElementById("price_text").innerText = `${totalPrice.toFixed(2)}€`;
    document.getElementById("price_text").setAttribute('data-price', totalPrice.toFixed(2));
    document.getElementById("ai-percent").innerText = `-${(discount * 100).toFixed(2)}%`;
}

// Ejecuta la función al cambiar el valor del rango
document.getElementById("n_posts").addEventListener('input', updateValues);


// Ejecuta la función al cambiar el valor del rango
document.getElementById("n_posts").addEventListener('input', updateValues);


function get_if_sub() {
	var xmlhttp = window.XMLHttpRequest
	  ? new XMLHttpRequest()
	  : new ActiveXObject("Microsoft.XMLHTTP");
  
	xmlhttp.onreadystatechange = function () {
	  if (this.readyState == 4 && this.status == 200) {
		const consulta = JSON.parse(this.responseText);
  
		if (!consulta.response.subscription_id) {
			document.getElementById("pills-single-posts").innerHTML =
			  "Only available to subscribers";
		}
  
	  } else if (this.readyState == 4 && this.status != 200) {
		document.getElementById("form-errors").innerHTML = "Algo salió mal";
  
		document.getElementById("form-errors").style.display = "block";
	  }
	};
  
	xmlhttp.open("POST", "https://webator.es/gpt3_api/get-new-info.php", true);
  
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  
	xmlhttp.send();
  }
  
  get_if_sub();