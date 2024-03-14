//PROMOTIONS
function ai_post_promotion(type) {
  var xmlhttp = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");

  xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const consulta = JSON.parse(this.responseText);
      console.log(consulta);
      if (consulta.exito) {
        get_info();
        if (type == "review") {
          document.getElementById("ai-post-" + type + "-error").style.display =
            "block";
          document.getElementById("ai-post-" + type + "-error").innerHTML =
            "Promotion is being reviewed";
        }
      } else {
        document.getElementById("ai-post-" + type + "-error").style.display =
          "block";
        document.getElementById("ai-post-" + type + "-error").innerHTML =
          consulta.error;
      }
    } else if (this.readyState == 4 && this.status != 200) {
      document.getElementById("form-errors").innerHTML = "Something went wrong";

      document.getElementById("form-errors").style.display = "block";
    }
  };

  xmlhttp.open("POST", "https://webator.es/gpt3_api/new-promotion.php", true);

  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  if (type == "review") {
    xmlhttp.send(
      "type=" +
        type +
        "&url=" +
        document.getElementById("ai-post-review-text").value
    );
  } else {
    xmlhttp.send("type=" + type);
  }
}
