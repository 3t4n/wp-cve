const modal = document.getElementById("yapeModal")
const btn = document.getElementById("yapeBtn")
const span = document.getElementsByClassName("close")[0]

if (btn && span) {
  btn.onclick = function () {
    modal.style.display = "block"
  }

  span.onclick = function () {
    modal.style.display = "none"
  }

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none"
    }
  }
}

jQuery(document).ready(function($) {
  $("body").append("<div id='yape-pagos-moviles-peru-loader'></div>")
  $("#yape-pagos-moviles-peru-loader").hide();
})

function prepareImage(input) {
  if (input.files[0]) {
    jQuery("#yape-pagos-moviles-peru-loader").show();

    const formData = new FormData()
    
    formData.append('file', input.files[0])
    formData.append('action', ajax_var.action)
    formData.append('nonce', ajax_var.nonce)

    jQuery.ajax({
      url: ajax_var.url,
      type: "POST",
      data: formData,
      cache: false,
      processData: false,
      contentType: false,
      success: function (data) {
        data = JSON.parse(data);
        if (data.url) {
          document.getElementById("yape_img").value = data.url;
        } else {
          alert(data.error);
        }
        jQuery("#yape-pagos-moviles-peru-loader").hide();
      },
    })
  }
}
