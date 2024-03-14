"use strict";

(function ($) {
  var wooOrderData = null;

  window.setWooOrder = function setWooOrder(data) {
    wooOrderData = data;
  };

  function setFormData() {
    if (wooOrderData == null) return;
    $("#epakaFormIframe")[0].contentWindow.iframeSession = wooOrderData.api_session;

    if (wooOrderData.dimensions != null) {
      $("#epakaFormIframe").contents().find("#ZamowieniePaczka0Waga").val(wooOrderData.dimensions.weight);
      $("#epakaFormIframe").contents().find("#ZamowieniePaczka0Dlugosc").val(wooOrderData.dimensions.length);
      $("#epakaFormIframe").contents().find("#ZamowieniePaczka0Szerokosc").val(wooOrderData.dimensions.width);
      $("#epakaFormIframe").contents().find("#ZamowieniePaczka0Wysokosc").val(wooOrderData.dimensions.height);
    }

    $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaImie").val(wooOrderData.woo_order_address.first_name);
    $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaNazwisko").val(wooOrderData.woo_order_address.last_name);
    $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaKod").val(wooOrderData.woo_order_address.postcode);
    $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaMiasto").val(wooOrderData.woo_order_address.city);
    $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaUlica").val(wooOrderData.woo_order_address.street);
    $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaNrdomu").val(wooOrderData.woo_order_address.house_number);
    $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaNrlokalu").val(wooOrderData.woo_order_address.flat_number);
    $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaTelefon").val(wooOrderData.woo_order_billing_address.phone);
    $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaEmail").val(wooOrderData.woo_order_billing_address.email);
    $("#epakaFormIframe").contents().find("#ZamowienieZawartosc").val(wooOrderData.content);

    if (wooOrderData.woo_order_address.country != "PL") {
      // console.log($("#epakaFormIframe").contents().find("wysylamzagranice"));
      $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaKraj")[0].selectize.setValue(wooOrderData.woo_order_address.country);
      $("#epakaFormIframe").contents().find("#wysylamzagranice").prop("checked", true);
    }

    $("#epakaFormIframe").contents().find("#ZamowienieKurierId" + wooOrderData.shipping_method).click(); // $("#epakaFormIframe")[0].contentWindow.selected_courier = wooOrderData.shipping_method;
    // $("#epakaFormIframe")[0].contentWindow.updateStep(1, 4, true);

    $("#epakaFormIframe")[0].contentWindow.countPrices(function () {
      $("#epakaFormIframe")[0].contentWindow.preference_flag = 1;
      $("#epakaFormIframe")[0].contentWindow.inputsHoudini($("#epakaFormIframe")[0].contentWindow.selected_courier, false);

      if (wooOrderData.epaka_point != "" && wooOrderData.epaka_point_description != "") {
        $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaPaczkomat").val(wooOrderData.epaka_point);
        $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaPaczkomatOpis").val(wooOrderData.epaka_point_description);
      }

      setTimeout(function () {
        $("#epakaFormIframe")[0].contentWindow.preference_flag = 0;
      }, 1000);
      $("#epakaFormIframe")[0].contentWindow.getDatesAndServices($("#epakaFormIframe")[0].contentWindow.selected_courier, undefined, $("#epakaFormIframe")[0].contentWindow.extras);
    });

    $("#epakaFormIframe")[0].contentWindow.document.getElementById("ZamowienieZlozForm").onsubmit = function (e) {
      e.preventDefault();
      var dataArray = $("#epakaFormIframe").contents().find("#ZamowienieZlozForm").serializeArray();
      dataArray.push({
        name: "data[WooCommerceData][id]",
        value: wooOrderData.woo_order_id
      });
      $.post(epaka_admin_object.api_endpoint + "/send-order?token=" + epaka_admin_object.admin_token, dataArray, function (data) {
        window.location.reload();
      }).fail(function () {
        alert("Nie udało się złożyć zamówienia");
      });
    };

    if (wooOrderData.epaka_point != "" && wooOrderData.epaka_point_description != "") {
      $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaPaczkomat").val(wooOrderData.epaka_point);
      $("#epakaFormIframe").contents().find("#ZamowienieOdbiorcaPaczkomatOpis").val(wooOrderData.epaka_point_description);
    }
  }

  $(document).on('iframebeforeready', function () {});
  $(document).on('iframeready', function () {});
  $(document).on('iframeformjsready', function () {
    setFormData(); // przewiniecie do popupu wysylania

    $("#epakaFormIframe")[0].contentWindow.document.getElementById("submit-button").onclick = function () {
      var observer = setInterval(function () {
        if ($("#epakaFormIframe")[0].contentWindow.submitPopup) {
          clearTimeout(observer);
          var offset = $("#epaka-iframe-order-header").offset();
          $("html, body").animate({
            scrollTop: offset.top - 100
          }, 250);
        }
      }, 250);
    };
  });
})(jQuery);