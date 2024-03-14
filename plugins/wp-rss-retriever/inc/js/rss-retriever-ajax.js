'use strict';

document.addEventListener('DOMContentLoaded', function() {
	const rss_feeds = document.querySelectorAll('.wprss_ajax');

	rss_feeds.forEach(function(rss_feed) {
		const feed_settings = window[rss_feed.dataset.id];
	     // console.log(feed_settings);

		rss_retriever_fetch_feed(feed_settings)
		  	.then(data => {
		  		// display the feed results on the page
		    	rss_feed.innerHTML = data;
		  	})
		  	.catch(error => {
		    	console.log(error);
		  	});
	});


	function rss_retriever_fetch_feed(feed_settings) {
	  return new Promise((resolve, reject) => {
	    jQuery.ajax({
			type: "post",
			dataType: "json",
			url: feed_settings.ajax_url,
			data: {
				'action':'rss_retriever_ajax_request', 
				'settings' : feed_settings,
				'_ajax_nonce' : feed_settings.nonce
			},
	      success: function(data) {
	      	// console.log(data);
	        resolve(data);
	      },
	      error: function(error) {
	      	// console.log(error.responseText);
	        reject(error);
	      },
	    })
	  })
	};
});
