jQuery(document).ready(function() {
	let wp_testimonials_edits = document.getElementById("the-list").getElementsByClassName("edit");
	for(let i = 0; i < wp_testimonials_edits.length; i++) {
		let wp_testimonials_current_href = wp_testimonials_edits[i].children[0].getAttribute("href");
		wp_testimonials_edits[i].children[0].setAttribute("href", wp_testimonials_current_href + "&post_type=wpt-testimonial");
	}

	jQuery('<br />').insertBefore('.page-title-action');
});