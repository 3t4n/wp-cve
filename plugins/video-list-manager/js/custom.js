jQuery(document).ready(function($){
	//Set width for each element .tntVideoItem
	var tntMarginLeft = 20;
	var tntVideoListWidth = $('.tntVideoList').width();
	var tntColumns = $('.tntVideoList').attr('rel');
	var tntSpace = tntColumns - 1;
	var tntVideoItemWidth = (tntVideoListWidth - (tntMarginLeft * tntSpace)) / tntColumns;

	var tntVideoWidth = $('.tntVideoList').attr("width");
	var tntVideoHeight = $('.tntVideoList').attr("height");
	
	$('.tntVideoList .tntVideoItem').each(function(){
		if($(this).hasClass("noML") == false)
		{
			$(this).css("margin-left", tntMarginLeft);	
		}
		$(this).css('width', tntVideoItemWidth);
		$(this).find("img").css('width', tntVideoItemWidth);
		$(this).find("img").css('height', tntVideoItemWidth/1.77);
	});

	$('.tntVideoList .tntVideoItem a.videoLink').click(function(e){
		e.preventDefault();
		$(this).colorbox({iframe:true, width:tntVideoWidth, height:tntVideoHeight});
	});
});	