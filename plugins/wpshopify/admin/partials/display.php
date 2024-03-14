<div class="wrap wps-admin-wrap">
   <style>
        .shopwp-exp-notice {
            background: white;
			position: fixed;
			bottom: 0;
			right: 0;
			width: 590px;
			height: 360px;
		    z-index: 99;
	        box-shadow: rgb(0 0 0 / 10%) 0px 0px 0px 1px;
display: none;
        }

        .shopwp-exp-notice p {
            font-size: 16px;
        }
	
		.shopwp-exp-notice .dashicon {
			margin-left: 7px;
    			font-size: 17px;
		}

        .shopwp-exp-notice p:first-of-type {
		    margin-top: 0;
        }

        .shopwp-exp-notice > div {
			position: relative;
            padding: 20px;
            max-width: 610px;
            margin: 0 auto;
        }

        .shopwp-exp-notice .close-icon {
            position: absolute;
			top: 15px;
		    right: 15px;
            font-weight: bold;
            background: #ececec;
            padding: 8px 15px;
			transition: all ease 0.2s;
        }

        .shopwp-exp-notice .close-icon img {
		    font-size: 11px;
        }

        .shopwp-exp-notice .close-icon:hover {
            background: #e6e6e6;
			cursor: pointer;
		}

		.shopwp-exp-notice .components-button {
			font-size: 16px;
		}

      @keyframes shimmer {
         0% {
            opacity: 0.6;
         }

         100% {
            opacity: 1;
         }
      }
   </style>
	
   
   <div id="shopwp-skeleton-loader" style="position: absolute;top: 20px;left: 50%;transform: translate(-50%, 0);width: 100%;margin: -21px 0 0 0;">

      <header class="wps-skeleton-header" style="width: 100%;height:150px;background: rgb(255, 255, 255);box-sizing: border-box;border-radius: 3px;border: 1px solid rgb(226, 228, 231);"></header>

      <svg width="570" height="460" aria-labelledby="loading-aria" preserveAspectRatio="none" style="animation: shimmer 0.3s ease-out 0s alternate infinite none running;margin:-154px auto 0;" display="block"><rect width="100%" height="100%" clip-path="url(#clip-path)" fill="url(&quot;#fill&quot;)"/><defs><linearGradient id="fill"><stop offset=".6" stop-color="#e3e3e3"><animate attributeName="offset" values="-2; -2; 1" keyTimes="0; 0.25; 1" dur="1s" repeatCount="indefinite"/></stop><stop offset="1.6" stop-color="#e3e3e3"><animate attributeName="offset" values="-1; -1; 2" keyTimes="0; 0.25; 1" dur="1s" repeatCount="indefinite"/></stop><stop offset="2.6" stop-color="#e3e3e3"><animate attributeName="offset" values="0; 0; 3" keyTimes="0; 0.25; 1" dur="1s" repeatCount="indefinite"/></stop></linearGradient><clipPath id="clip-path"><rect rx="0" ry="0"/><rect x="8" y="168" rx="2" ry="2" width="600" height="326"/><rect x="18" y="122" rx="2" ry="2" width="94" height="16"/><rect x="198" y="41" rx="2" ry="2" width="262" height="41"/><circle cx="153" cy="62" r="32"/><rect x="130" y="122" rx="2" ry="2" width="94" height="16"/><rect x="243" y="122" rx="2" ry="2" width="94" height="16"/><rect x="358" y="122" rx="2" ry="2" width="94" height="16"/><rect x="475" y="123" rx="2" ry="2" width="87" height="15"/></clipPath></defs></svg>

   </div>

   <div id="shopwp-admin-app"></div>
   <div id="shopwp-visual-builder-app"></div>
   <div id="shopwp-admin-content"></div>
   <div id="shopwp-admin-footer"></div>

</div>



<div class="shopwp-exp-notice">

	<script>
			var expNoticedClosed = localStorage.getItem('shopwp-exp-notice-closed');

			if (!expNoticedClosed) {
				jQuery('.shopwp-exp-notice').fadeIn();
			}
	</script>


    <div>
<div class="close-icon"><span>✖</spam> Close</div>
        <p>Important!</p>
		<p>This plugin will stop working on March 1st, 2024. Please <a href="https://wpshop.io/purchase" target="_blank">upgrade to ShopWP Pro</a> to continue using the plugin.</p>
        <p style="">Thanks everyone.</p>
		<a href="https://wpshop.io/purchase" class="components-button is-primary" target="_blank">Upgrade to ShopWP Pro <span class="dashicon dashicons dashicons-external"></span></a>
        <p style="margin-top:15px;margin-bottom: 3px;font-size: 15px;font-weight:bold;">Andrew, Creator of ShopWP</p>
		<a href="mailto:hello@wpshop.io" style="display:block;margin-top:3px;font-size: 15px;">hello@wpshop.io</a>
    </div>

	<script>
		jQuery('.shopwp-exp-notice .close-icon').on('click', function() {
			localStorage.setItem('shopwp-exp-notice-closed', 'true');
			jQuery('.shopwp-exp-notice').fadeOut();
		});
	</script>
<div>