document.getElementById("wpfooter").style.display="none";


function start(email, name) {
  var xmlhttp = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");

  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const consulta = JSON.parse(this.responseText);

      location.reload();
    }
  };

  xmlhttp.open("POST", "https://webator.es/gpt3_api/save-new-mail.php", true);

  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var gdpr = document.getElementById("ai-gdpr").checked
  if(!gdpr){
    email = 'no';
    name = 'no';
  }
  xmlhttp.send("email=" + email + "&name=" + name);
}

function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function get_info() {
  var xmlhttp = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");

  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const consulta = JSON.parse(this.responseText);


      if (consulta.response.localhost) {
        document.getElementById("ai-localhost").style.display = "block";
      }

      if (consulta.response.email != null && consulta.response.email != "") {
        document.getElementById("ai-presentation").style.display = "none";

        document.getElementById("ai-body").style.display = "block";

        document.getElementById("autowriter-content").style.display = "block";
      } else {
        document.getElementById("ai-presentation").style.display = "flex";
      }

      if (consulta.response.first_purchase == "1") {
        if (
          document.body.contains(
            document.getElementById("ai-post-first-purchase")
          )
        ) {
          document.getElementById("ai-post-first-purchase").style.display =
            "block";
        }
      }

      if (consulta.response.fifth_purchase == "1") {
        if (
          document.body.contains(
            document.getElementById("ai-post-fifth-purchase")
          )
        ) {
          document.getElementById("ai-post-fifth-purchase").style.display =
            "block";
        }
      }

	  if (!consulta.response.subscription_id  && !consulta.response.localhost && consulta.response.n_posts < 1  && !(window.location.href.indexOf("admin.php?page=autowriter_upgrade_plan") > -1)) {
        if (
          document.body.contains(
            document.getElementById("ai-banner-buy")
          )
        ) {
          document.getElementById("ai-banner-buy").style.display =
            "block";
        }
      }

      let progress_token = document.getElementById("progress-token");

      if (consulta.response.n_posts > consulta.response.n_posts_sub) {
        progress_token.style.width = "100%";
      } else {
        progress_token.style.width =
          (consulta.response.n_posts / consulta.response.n_posts_sub) * 100 + "%";
      }

      document.getElementById("progress-n-tokens").innerHTML =
        numberWithCommas(consulta.response.n_posts) +
        "/" + consulta.response.n_posts_sub + " posts (Refill in " + consulta.response.next_payment + " days)";
    } else if (this.readyState == 4 && this.status != 200) {
      document.getElementById("form-errors").innerHTML = "Algo salió mal";

      document.getElementById("form-errors").style.display = "block";
    }
  };

  xmlhttp.open("POST", "https://webator.es/gpt3_api/get-new-info.php", true);

  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xmlhttp.send();
}

get_info();

function close_banner(x){
	document.getElementById("ai-banner-" + x).style.display="none";
}