(function ($) {
  "use strict";

  /**
   * Card Oracle JS code for the public pages
   */

  $(document).ready(function () {
	var cardID;
	let count = 0;

    if ($(".card-oracle-ecabala").length) {
      console.log("E-Cabala");

      var cards = $(".card-oracle-card");
      var cardSelected = false;
      let clickedCards = new Array();
      let positions = $("div.data").data("positions");

      $("#card-oracle-cardsubmit").prop("disabled", true);
      $("#readingsubmit").prop("hidden", true);

      $("button.clicked").click(function () {
        if (!cardSelected) {
          count++;
          cardID = this.value;
          console.log("Postions:" + positions);
          $(".card-oracle-card-body").css("transition", "transform 0s");
          $(".card-oracle-card-body").css("-webkit-transition", "transform 0s");

          // Get desired elements
          var element = document.getElementsByClassName("card-oracle-back");

          // Iterate through the retrieved elements and add the necessary class names.
          for (var i = 0; i < element.length; i++) {
            element[i].classList.add("card-oracle-nohover");
            console.log(element[i].className);
          }

          $(this).closest(".card-oracle-card-body").toggleClass("is-flipped");
          $(this).fadeOut(0);

          clickedCards.push(this.value);
          $("#card-oracle-picks").val(clickedCards.join());

          cardSelected = true;
          $("#card-oracle-cardsubmit").prop("disabled", false);
        }
      });

      $("#card-oracle-cardsubmit").click(function () {
        console.log("Count: " + count);
        console.log("ID: " + cardID);
        $(".card-oracle-card-body").css("transition", "transform 0s");
        $(".card-oracle-cards").children().prop("disabled", false);

        $("#btn" + cardID).prop("disabled", false);
        $("#btn" + cardID).toggle();
        $("#card-body" + cardID).toggleClass("is-flipped");
        $("#card-oracle-cardsubmit").prop("disabled", true);

        if (count < positions) {
          cardSelected = false;

          // Get desired elements
          var element = document.getElementsByClassName("card-oracle-back");

          // Iterate through the retrieved elements and add the necessary class names.
          for (var i = 0; i < element.length; i++) {
            element[i].classList.remove("card-oracle-nohover");
          }

          for (var j = 0; j < cards.length; j++) {
            var target = Math.floor(Math.random() * cards.length - 1) + 1;
            var target2 = Math.floor(Math.random() * cards.length - 1) + 1;
            cards.eq(target).before(cards.eq(target2));
          }

          for (var k = 0; k < cards.length; k++) {
            target = Math.floor(Math.random() * cards.length - 1) + 1;
            target2 = Math.floor(Math.random() * cards.length - 1) + 1;
            cards.eq(target).before(cards.eq(target2));
          }
        } else {
          $("#readingsubmit").prop("hidden", false);
          $("#card-oracle-cardsubmit").prop("hidden", true);
          $(".card-oracle-cards").toggle();
          if ($("#submitbuttondiv").hasClass("hiddenreadingsubmit")) {
            $("#readingsubmit").trigger("click");
          }
        }
      });
    } else {
      console.log("Standard");
      let clickedCards = new Array();
      let reverseCards = new Array();
      let positions = $("div.data").data("positions");

      $("#readingsubmit").prop("disabled", true);

      $("li img").click(function () {
        cardID = $(this).data("value");

        if (cardID !== undefined && count < positions) {
          console.log("Card ID: " + cardID);
          count++;
          $(this).toggleClass("card-oracle-image-hidden");
          $("#card" + cardID).toggleClass("card-oracle-image-hidden");

          clickedCards.push(cardID);
          if ($(this).attr("data-reversed")) {
            reverseCards.push(cardID);
          }

          $("#card-oracle-picks").val(clickedCards.join());
          $("#reverse").val(reverseCards.join());

          if (count == positions) {
            $(".btn-block").show();
            $(".btn-block").css("opacity", "1");
            $("#readingsubmit").click();
            $("#readingsubmit").prop("disabled", false);
            if ($("#submitbuttondiv").hasClass("hiddenreadingsubmit")) {
              $("#readingsubmit").trigger("click");
            }
          }
        }
      });

      $("button.clicked").click(function () {
        $(".card-oracle-card-body").css("transition", "transform .5s");
        $(".card-oracle-card-body").css("-webkit-transition", "transform .5s");

        if (count < positions) {
          count++;
          $(this).closest(".card-oracle-card-body").toggleClass("is-flipped");
          $(this).fadeOut(80);

          clickedCards.push(this.value);
          if ($(this).attr("data-value")) {
            reverseCards.push(this.value);
            console.log(this.value);
          }

          $("#card-oracle-picks").val(clickedCards.join());
          $("#reverse").val(reverseCards.join());

          if (count == positions) {
            $(".btn-block").show();
            $(".btn-block").css("opacity", "1");
            $("#readingsubmit").prop("disabled", false);
            if ($("#submitbuttondiv").hasClass("hiddenreadingsubmit")) {
              $("#readingsubmit").trigger("click");
            }
          }
        }
      });

      $("#card-oracle-question").on("submit", function (e) {
        console.log("Submit button clicked");
      });

      $("#reading-send").click(function (e) {
        e.preventDefault(); // if the clicked element is a link

        $.post(
          $("#ajax_url").val(),
          {
            action: "send_reading_email",
            email: $("#emailaddress").val(),
            emailcontent: $("#emailcontent").val(),
            subscribe: $("#card-oracle-subscribe").is(":checked")
              ? "true"
              : "false",
            security: $("#_wpnonce").val(),
            readingid: $("#readingid").val(),
          },
          function (response) {
            // handle a successful response
            $(".card-oracle-response").html(response.data);
            $("#emailaddress").val("");
            console.log(response);
          }
        );
      });
    }
  });
})(jQuery);
