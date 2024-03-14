jQuery(document).ready(function ($) {

	$("#before-content").on('keyup', function () {
		if ($(this).val().indexOf("script") > -1)
			$(this).val($(this).val().replace("script", "span"));
		findTags($);
	}).on('keydown', function (e) {
		if (e.keyCode == 8) {
			if ($(this).val().indexOf("script") > -1)
				$(this).val($(this).val().replace("script", "span"));
			findTags($);
		}
	});

	$("#wrapper-tag").on('keyup', function () {
		if ($(this).val().indexOf("script") > -1)
			$(this).val($(this).val().replace("script", "span"));
	}).on('keydown', function (e) {
		if (e.keyCode == 8)
			if ($(this).val().indexOf("script") > -1)
				$(this).val($(this).val().replace("script", "span"));
	});

	$("#link-wrapper").on('keyup', function () {
		if ($(this).val().indexOf("script") > -1)
			$(this).val($(this).val().replace("script", "span"));
	}).on('keydown', function (e) {
		if (e.keyCode == 8)
			if ($(this).val().indexOf("script") > -1)
				$(this).val($(this).val().replace("script", "span"));
	});

	$("#wrapper-id").keydown(function (e) {
		if (e.keyCode == 32) { //32 - Space
			$(this).val($(this).val() + "-");
			return false;
		}
	}).change(function (e) {
		$(this).val(function (i, v) {
			return v.replace(" ", "-");
		});
	});

	$("#sh_pagelinks_options_form input").blur(function () {
		processChanges($);
	});

	processChanges($);

});



function findTags($) {

	openingTags = $("#before-content").val();
	tempElement = document.createElement('div');
	$(tempElement).append(openingTags);

	if ($(tempElement).html().indexOf(openingTags) > -1) {
		closingTags = $(tempElement).html().replace(openingTags, '');
		$("#after-content").val(closingTags);
	}

}



function processChanges($) {

	var changes = "";
	var sep = $("#seperator").val();
	var wrapper = $("#wrapper-tag").val();

	if (wrapper != "")
		changes += '<' + $("#wrapper-tag").val() + ' class="' + $("#wrapper-class").val() + '" id="' + $("#wrapper-id").val() + '">\n\t';

	changes += $("#before-content").val();
	changes += '\n\t ' + processChangesAddLink('<a href="#" class="' + $('#firstpageclass').val() + '">' + $("#firstpage").val() + '</a>') + ' ' + sep;
	changes += '\n\t ' + processChangesAddLink('<a href="#" class="' + $('#previouspageclass').val() + '">' + $("#link-before").val() + $("#previouspagelink").val() + $("#link-after").val() + '</a>') + ' ' + sep;
	changes += '\n\t ' + $("#elipsis").val() + ' ' + sep;

	if ($("#pages-to-scroll-count").length > 0)
		numbers = $("#pages-to-scroll-count").val();
	else
		numbers = 3;

	for (var i = 1; i <= numbers; i++) {
		changes += '\n\t ' + processChangesAddLink(processChangesAddNumber(i)) + ' ' + sep;
	};

	changes += '\n\t ' + $("#elipsis").val() + ' ' + sep;
	changes += '\n\t ' + processChangesAddLink('<a href="#" class="' + $('#nextpageclass').val() + '">' + $("#link-before").val() + $("#nextpagelink").val() + $("#link-after").val() + '</a>') + ' ' + sep;
	changes += '\n\t ' + processChangesAddLink('<a href="#" class="' + $('#lastpageclass').val() + '">' + $("#lastpage").val() + '</a>') + ' ';

	if ($("#view-single-link").is(":checked"))
		changes += sep + '\n\t ' + processChangesAddLink('<a href="#">' + $("#text-single-link").val() + '</a>');

	changes += '\n\t ' + $("#after-content").val();

	if (wrapper != "")
		changes += '\n</' + $("#wrapper-tag").val() + '>\n';

	changes = '<strong>Browser</strong><BR />' + changes.replace("\n", "") + '<BR /><BR /><strong>HTML</strong><BR /><xmp style="width: 50%;">' + changes + '</xmp>';

	$("div.anatomy_holder").html(changes);

}



function processChangesAddLink(page) {

	var changes = "",
		outterWrap = jQuery("#link-wrapper-outter").val(),
		wrap = jQuery("#link-wrapper").val();

	if (wrap != "")
		page = page.replace('>', '><' + wrap + ' class="' + jQuery("#link-wrapper-class").val() + '">');

	if (wrap != "")
		page = page.replace('</a>', '</' + wrap + '></a>');

	if (outterWrap != "")
		changes += '<' + outterWrap + ' class="' + jQuery("#link-wrapper-outter-class").val() + '">';

	changes += page;

	if (outterWrap != "")
		changes += '</' + outterWrap + '>';

	return changes;

}



function processChangesAddNumber(page) {

	var changes = "";
	var format = jQuery("#pagelink").val();

	format = format.replace("%page%", page);
	format = format.replace("%title%", "Title");
	changes = '<a href="#">' + format + '</a>';

	return changes;

}