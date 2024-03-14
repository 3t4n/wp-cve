
var acc = document.getElementsByClassName("wrld-accordion");
var i;

for (i = 0; i < acc.length; i++) {
	acc[i].addEventListener("click", function () {
		this.classList.toggle("wrld-active");
		var panel = this.nextElementSibling;
		if (panel.style.maxHeight) {
			panel.style.maxHeight = null;
		} else {
			panel.style.maxHeight = panel.scrollHeight + "px";
		}
	});
}


var slideIndex = 1;
showDivs(slideIndex, 1);

function plusDivs(ele, n) {
	var c_ind = jQuery(ele).attr('data-slideindex');
	var c_index = parseInt(c_ind) + n;
	jQuery(ele).attr('data-slideindex', c_index);
	showDivs(c_index, ele);
}

function showDivs(n, ele) {

	if (ele == 1) {
		var x = document.getElementsByClassName("mySlides");
	}
	else {
		const parent = ele.parentNode;
		var x = [].slice.call(parent.children).filter(function (child) {

			return child.tagName.toLowerCase() !== "button";
		});
	}
	if (ele == 1) {
		var i;

		if (n > x.length) {
			slideIndex = 1
		}
		if (n < 1) {
			slideIndex = x.length
		}
		for (i = 0; i < x.length; i++) {
			x[i].style.display = "none";
		}
		x[slideIndex - 1].style.display = "block";
	} else {
		var i;

		if (n > x.length) {
			jQuery(ele).attr('data-slideindex', 1);
		}
		if (n < 1) { jQuery(ele).attr('data-slideindex', x.length); }

		//console.log(x.length);
		var myindex = jQuery(ele).attr('data-slideindex');
		myindex = parseInt(myindex);
		for (i = 0; i < x.length; i++) {
			x[i].style.display = "none";
		}
		x[myindex - 1].style.display = "block";
	}
}

jQuery(document).ready(function () {
	jQuery('.wrld-slider-btn-left').each(function (i, obj) {
		obj.click();
		//console.log(obj);
	});
});

function changeSlider() {
	jQuery('.wrld-slider-btn-nxt').each(function (i, obj) {
		obj.click();
	});
};

setInterval(() => {
	changeSlider();
	//console.log("changes triggered");
}, 2000);

jQuery(".wrld-slider-container").hover(function () {
	//console.log("hover");
	jQuery(this).find('.wrld-slider-btn-right').removeClass("wrld-slider-btn-nxt");
}, function () {
	//console.log("removed");
	jQuery(this).find('.wrld-slider-btn-right').addClass("wrld-slider-btn-nxt");
});

//whats new tab modal script
const body = document.querySelector("body");
function wrldShowmodal(ele, parent) {
	let index = 1;
	const hasWrldSingleHideClass = Array.from(parent.children).some(child => child.classList.contains('wrld-single-hide'));

	if (hasWrldSingleHideClass) {
		//Hide buttons
		jQuery('.wrld-modal-slider-btn-color').css('display', 'none');
		jQuery('.wrld-modal-slider-btn-padding').css('display', 'inline-block');

	} else {
		//show buttons
		jQuery('.wrld-modal-slider-btn-color').css('display', 'inline-block');
		jQuery('.wrld-modal-slider-btn-padding').css('display', 'none');
	}
	jQuery('#wrld-modal-gutenberg').css('display', 'block');

	jQuery('#wrld-modal-img').attr('src', ele.src);
	body.style.overflow = "hidden";

	const images = parent.querySelectorAll('img');
	jQuery('.wrld-modal-slider-btn-right').on('click', function () {
		const modalImg = document.querySelector('.wrld-modal-img');
		index++;
		if (images.length == index) {
			index = 0;
		}
		modalImg.src = images[index].src;
	});

	jQuery('.wrld-modal-slider-btn-left').on('click', function () {
		const modalImg = document.querySelector('.wrld-modal-img');
		index--;
		if (0 > index) {
			index = images.length - 1;
		}
		modalImg.src = images[index].src;
	});
}

jQuery('#wrld-modal-close').on('click', function () {
	jQuery('#wrld-modal-gutenberg').css('display', 'none');
	body.style.overflow = "auto";
});

jQuery('.wrld-modal').on('click', function (e) {
	if (!jQuery(e.target).hasClass('wrld-modal-img') && !jQuery(e.target).hasClass('wrld-modal-slider-btn-color')) {
		jQuery('#wrld-modal-gutenberg').css('display', 'none');
		body.style.overflow = "auto";
	}
});

jQuery('.wrld-accordion').on('click', function () {
	$is_beackon = jQuery(this).children('.accordiantext').attr('data-beacon');
	$meta_name = jQuery(this).children('.accordiantext').attr('data-metaname');

	if ($is_beackon == 0) {
		jQuery(this).children('.accordiantext').find('span').hide();
		jQuery(this).children('.accordiantext').attr('data-beacon', 1)
		jQuery.ajax({
			type: 'POST',
			url: wrld_admin_settings_data.wp_ajax_url,
			data: {
				'action': 'wrld_gutenberg_block_visit',
				'option_key': $meta_name,
				'wp_nonce': wrld_admin_settings_data.nonce
			},
			dataType: 'json',
			success: function (data) {
				// This outputs the result of the ajax request
				//console.log(data);
			},
			error: function (errorThrown) {
				//console.log(errorThrown);
			}
		});
	}
});