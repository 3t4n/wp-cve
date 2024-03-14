jQuery(document).ready(function ($) {
  $(".sptp-generator-tabs .spf-wrapper").css("visibility", "hidden");
  $('.sptp_filter_members').find("option:nth-of-type(2), option:nth-of-type(3), option:nth-of-type(4)").attr('disabled', 'disabled');

 $(`.sptp_image_grayscale option,
    .sptp-inline-repeater-social option,
    .sptp-repeater-select option`).each(function( i, item ) {
    const regex = new RegExp( 'Pro' );
    if ( regex.test( item.innerText ) ) {
      $(item).attr('disabled', 'disabled');
    }
 })

  $('.spf--typography').find('.spf--font-family, .spf--font-style-select, .spf--font-size, .spf--line-height, .spf--text-align, .spf--text-transform, .spf--letter-spacing, .spf--margin-top').attr('disabled', 'disabled');
  $('.sptp_typography_pro').css('pointer-events', 'none');
  $('.spf--block-preview').css('cursor', 'auto');
  $('.spf--block-preview .spf--toggle').hide();
  $('.spf--block-preview').css('pointer-events', 'none');

  var select_value_layout = $(
    ".sptp-layout-preset .spf--sibling.spf--image.spf--active"
  )
    .find("input")
    .val();
  if (select_value_layout === "carousel") {
	  $(".spf-nav-metabox li.spf-menu-item-carousel-settings").show();
  } else {
	  $(".spf-nav-metabox li.spf-menu-item-carousel-settings").hide();
  }

  $(document).on(
    "click",
    ".sptp-layout-preset .spf--sibling.spf--image",
    function (event) {
      event.stopPropagation();
      var select_value = $(this)
        .find("input")
        .val();

      if (select_value !== "carousel") {
		  $(".spf-nav-metabox li.spf-menu-item-carousel-settings").hide();
        $(".spf-nav-metabox li.spf-menu-item-general-settings a").click();
      } else {
		  $(".spf-nav-metabox li.spf-menu-item-carousel-settings").show();
      }
		var membersPadding = (select_value === "list") ? '20' : '0';
		// Update members padding
		$('.members_padding .spf--input input[name="_sptp_generator[item_padding][right]"]').val(membersPadding);
    }
  );
	// Function to update icon type
	function updateIconType(selector, regex, type) {
		var str = "";
		$(selector + ' option:selected').each(function () {
			str = $(this).val();
		});
		var src = $(selector + ' .spf-fieldset img').attr('src');
		var result = src.match(regex);
		if (result && result[1]) {
			src = src.replace(result[1], str);
			$(selector + ' .spf-fieldset img').attr('src', src);
		}
		if (type.includes(str)) {
			$(selector + ' .spt-pro-notice').hide();
		} else {
			var noticeText = "This is a <a href='https://getwpteam.com/pricing/' target='_blank'>Pro Feature!</a>";
			$(selector + ' .spt-pro-notice').html(noticeText).show();
		}
	}
	$('.carousel_navigation_position').on('change', function () {
		updateIconType(".carousel_navigation_position", /navigation-position\/(.+)\.svg/,'top-right');
	});
	if ($('.carousel_navigation_position').length > 0) {
		updateIconType(".carousel_navigation_position", /navigation-position\/(.+)\.svg/, 'top-right');
	}

  $(".sptp-generator-tabs .spf-wrapper").css("visibility", "visible");
  $(".sptp-generator-tabs .spf-wrapper li").css("opacity", 1);

	$('.sptp-live-demo-icon').on('click', function (event) {
		event.stopPropagation();
		// Add any additional code here if needed
	});

  $(document).on("click", "#copy-shortcode, #copy-tag", function () {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp
      .val(
        $(this)
          .parent()
          .find("input")
          .val()
      )
      .select();
    document.execCommand("copy");
    $(this).append('<span class="copy-alert">copied</span>');
    setTimeout(function () {
      $(".copy-alert")
        .fadeOut()
        .empty();
    }, 1000);
    $temp.remove();
  });

  $('.sptp-shortcode-selectable, .post-type-sptp_generator .column-shortcode input').on('click',function (e) {
    e.preventDefault();
    /* Get the text field */
    var copyText = $(this);
    /* Select the text field */
    copyText.select();
    document.execCommand("copy");
    jQuery(".sptp-after-copy-text").animate({
      opacity: 1,
      bottom: 25
    }, 300);
    setTimeout(function () {
      jQuery(".sptp-after-copy-text").animate({
        opacity: 0,
      }, 200);
      jQuery(".sptp-after-copy-text").animate({
        bottom: -50
      }, 0);
    }, 2000);
  });
});
