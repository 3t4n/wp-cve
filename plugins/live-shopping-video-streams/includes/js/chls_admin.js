function lsch_show_private_key() {
  var x = document.getElementById("channelize_live_shopping_private_key");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
