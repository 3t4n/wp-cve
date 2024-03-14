<style>
	.tonjoo-ads {
		background-color: #fff;
		padding: 25px;
	}
	.tonjoo-ads .inside {
		overflow-y: auto;
	}
	.tonjoo-ads .inside > div {
		-webkit-box-sizing: border-box;
		  -moz-box-sizing: border-box;
		  box-sizing: border-box;
		display: block;
		float: left;
		width: 16.6666666667%;
		padding: 15px;
	}
	@media (max-width: 1024px) {
		.tonjoo-ads .inside > div {
			width: 33.33333%;
		}
	}
	@media (max-width: 767px) {
		.tonjoo-ads .inside > div {
			width: 100%;
		}
	}
</style>
<div class="wrap">
	<h1></h1>
	<div class="banner">
		
	</div>
	<div class="tonjoo-ads">			
		<script type="text/javascript">
			/**
			 * Setiap dicopy-paste, yang find dan dirubah adalah
			 * - var pluginName
			 * - premium_exist
			 */

			jQuery(function(){					
				var url = 'https://tonjoostudio.com/jsonp/?promo=get';

				// strpos function
				function strpos(haystack, needle, offset) {
					var i = (haystack + '')
						.indexOf(needle, (offset || 0));
					return i === -1 ? false : i;
				}

				jQuery.ajax({url: url, dataType:'jsonp'}).done(function(data){

					if(typeof data =='object') {
						var fristImg, fristUrl;

					    // looping jsonp object
						jQuery.each(data, function(index, value){
							if(strpos(index, "-img") && value !== ''){
						    	jQuery('#plugins').append('<div id="promo_'+index.replace('-img','')+'" style="text-align: center;"><a href="https://tonjoostudio.com" target="_blank"><img src="'+value+'" width="100%" alt="Tonjoo Studio"></a></div>');
						    }
						    if(strpos(index, "-url") && value !== '') {
						    	jQuery("#promo_"+index.replace('-url','')+" a").prop("href",value);
						    }
						});
					}
				});
			});
		</script>
		<div class="inside" id="plugins">
		</div>
	</div>
</div>