jQuery(document).ready(function($) {


	$('.maxsocial [data-popup]').on('click', function (e) {
		e.preventDefault();

		var url = $(this).attr('href');
		var data = $(this).data('popup');

		var width = data.width;
		var height = data.height;
		var left   = ($(window).width()  - width)  / 2;
		var top    = ($(window).height() - height) / 2;

		var params = "toolbar=0,scrollbars=1, location=0, width=" + width + ",height=" + height + ",left=" + left + ",top=" + top;
		var popup = window.open(url, 'mb-social-share-window', params);

		var popup = window.open(url, 'mb-social-share-window', params);
		popup.focus();

	});

	function mbGetShareCount(el, data)
	{
		var ajax_url = mbsocial.ajaxurl;
		var share_url = data.share_url;
		var network = data.network;
		var collection_id = data.collection_id;
		var nonce = data.nonce;

		var block_data = {
			share_url: share_url,
		 	network: network,
		};

		var data = {
			block_name : 'countBlock',
			block_action : 'ajax_get_count',
			action : 'mbsocial_get_count',
			collection_id: collection_id,
			block_data: block_data,
		};
//console.log(data);
		$.ajax({
			type: "POST",
			url: ajax_url,
			data: data,
			success: function (result) {
				mbPutShareCount(result, el);

			},
		});
	}

	function mbPutShareCount(result, el)
	{

		var resJSON = $.parseJSON(result);

		var data = $(el).data('onload');
		var threshold = parseInt(data.count_threshold);
		var count = parseInt(resJSON.data.count);


		if (count >= threshold)
		{
 			//console.log('generate count block here');
			$(el).find('.mb-label').addClass('.mb-share-count').text(count).removeClass('.mb-label');
		}

	}

	$('.maxsocial .mb-item[data-onload]').each( function () {
			var collection_id = $(this).parents('.maxsocial').data('collection');

 			var data = $(this).data('onload');
 			data.collection_id = collection_id;

 			mbGetShareCount(this, data);

	});

});
