//alert(pingenerator.hook);

jQuery(document).ready(function () {
  jQuery(".pg-save-button").click(function (e) {
    savePinTitle(e);
  });

  function savePinTitle(e) {
    //alert("Inside jquery");
    e.preventDefault();

    var postID = e.target.value;
    var pinTitle = document.getElementById(`pinTitle${postID}`).value;

    jQuery.ajax({
      url: pingenerator.ajaxurl,
      type: "POST",
      data: {
        action: "pin_generator_save_pin_title", // the name of your PHP function!
        postID: postID, // the postID the button click is on
        pinTitle: pinTitle,
      },
      success: function (data) {
        //Do something with the result from server
        //console.log(data);
        document.getElementById(`statusDiv${postID}`).innerHTML = "Text saved";
      },
      error: function (data) {
        console.log("Error saving title");
        document.getElementById(`statusDiv${postID}`).innerHTML =
          data.responseJSON.data;
      },
    });
  }

  jQuery(".show-pin-checkbox").change(function (e) {
    var postID = e.target.value;
    var isChecked = e.target.checked;

    jQuery.ajax({
      url: pingenerator.ajaxurl,
      type: "POST",
      data: {
        action: "pin_generator_save_show_pin", // the name of your PHP function!
        postID: postID, // the postID the button click is on
        showPin: isChecked ? true : false,
      },
      success: function (data) {
        //Do something with the result from server
        //console.log(data);
        if (isChecked) {
          document.getElementById(`statusDiv${postID}`).innerHTML =
            "This pin will be displayed in this post.";
        } else {
          document.getElementById(`statusDiv${postID}`).innerHTML =
            "This pin wil NOT be displayed in this post.";
        }
      },
      error: function (data) {
        console.log("Error saving checkbox");
        document.getElementById(`statusDiv${postID}`).innerHTML =
          data.responseJSON.data;
      },
    });
  });

  jQuery(".pg-generate-button").click(function (e) {
    var postID = e.target.value;
    var pinTitle = document.getElementById(`pinTitle${postID}`).value;

    document.getElementById(`statusDiv${postID}`).innerHTML =
      "Generating new pin...";

    jQuery.ajax({
      url: pingenerator.ajaxurl,
      type: "POST",
      data: {
        action: "pin_generator_generate_pin", // the name of your PHP function!
        postID: postID, // the postID the button click is on
        pinTitle: pinTitle,
      },
      success: function (res) {
        // Update the pin generated image
        if (res.data != 0) {
          // Update status
          document.getElementById(`statusDiv${postID}`).innerHTML =
            "New pin image created.";

          // Update demo image src and href
          document.getElementById(`pinGenImage${postID}`).src = res.data;
          document.getElementById(`pinGenImageAnchor${postID}`).href = res.data;
        }
      },
      error: function (data) {
        console.log("Error generating pin");
        console.log(data);
        document.getElementById(`statusDiv${postID}`).innerHTML =
          data.responseJSON.data;
      },
    });
  });

  // Add color picker code to color pickers
  jQuery(document).ready(function ($) {
    $(".pg-color-field").wpColorPicker();
  });
});
