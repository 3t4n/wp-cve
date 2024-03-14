<?php

class YrmShowReviewNotice {
	
	public function __toString() {
		$content = '';
		$allowToShow = $this->allowToShowUsageDays();
		
		if(!$allowToShow) {
			return $content;
		}

		$contet = $this->getReviewContent('usageDayes');
		
		return $contet;
	}

	private function allowToShowUsageDays() {
		$shouldOpen = true;

		$dontShowAgain = get_option('YrmDontShowReviewNotice');
		$periodNextTime = get_option('YrmShowNextTime');

		if($dontShowAgain) {
			return !$shouldOpen;
		}

		// When period next time does not exits it means the user is old
		if(!$periodNextTime) {
			$usageDays = $this->getMainTableCreationDate();
			update_option('YrmUsageDays', $usageDays);

			/*When very old user*/
			if($usageDays > YRM_SHOW_REVIEW_PERIOD) {
				return $shouldOpen;
			}

			$remainingDays = (int)(YRM_SHOW_REVIEW_PERIOD - $usageDays);
			$timeDate = new DateTime('now');
			$timeDate->modify('+'.esc_attr($remainingDays).' day');
			$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));

			update_option('YrmShowNextTime', $timeNow);

			return !$shouldOpen;
		}
		$currentData = new DateTime('now');
		$timeNow = $currentData->format('Y-m-d H:i:s');
		$timeNow = strtotime($timeNow);

		return $periodNextTime < $timeNow;
	}

	private function getReviewContent($type) {
		$content = $this->getMaxOpenDaysMessage($type);
		$allowedTag = ReadMoreAdminHelper::getAllowedTags();
		ob_start();
		?>
			<div id="welcome-panel" class="welcome-panel yrm-review-block">
				<div class="welcome-panel-content">
					<?php echo wp_kses($content, $allowedTag); ?>
				</div>
			</div>
		<?php
		$reviewContent = ob_get_contents();
		ob_end_clean();

		return $reviewContent;
	}

	private function getMainTableCreationDate() {
		global $wpdb;

		$query = $wpdb->prepare('SELECT table_name, create_time FROM information_schema.tables WHERE table_schema="%s" AND  table_name="%s"', DB_NAME, $wpdb->prefix.'expm_maker');
		$results = $wpdb->get_results($query, ARRAY_A);

		if(empty($results)) {
			return 0;
		}

		$createTime = $results[0]['create_time'];
		$createTime = strtotime($createTime);
		update_option('YrmInstallDate', $createTime);
		$diff = time()-$createTime;
		$days  = floor($diff/(60*60*24));

		return $days;
	}

	private function getPopupUsageDays() {
		$installDate = get_option('YrmInstallDate');

		$timeDate = new DateTime('now');
		$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));

		$diff = $timeNow-$installDate;

		$days  = floor($diff/(60*60*24));

		return $days;
	}

	private  function getMaxOpenDaysMessage($type) {
		$getUsageDays = $this->getPopupUsageDays();
		$firstHeader = '<h1 class="yrm-review-h1"><strong class="yrm-review-strong">Wow!</strong> You’ve been using <a href="https://wordpress.org/support/plugin/expand-maker/reviews/" target="_blank"><b>Read More</b></a> on your site for '.esc_attr($getUsageDays).' days</h1>';
		$popupContent = $this->getMaxOepnContent($firstHeader, $type);

		$popupContent .= $this->showReviewBlockJs();

		return $popupContent;
	}

	private function getMaxOepnContent($firstHeader, $type) {
		$ajaxNonce = wp_create_nonce('yrmReviewNotice');
		$allowedTag = ReadMoreAdminHelper::getAllowedTags();
		ob_start();
		?>
			<style>
				.yrm-buttons-wrapper .press{
					box-sizing:border-box;
					cursor:pointer;
					display:inline-block;
					font-size:1em;
					margin:0;
					padding:0.5em 0.75em;
					text-decoration:none;
					transition:background 0.15s linear
				}
				.yrm-buttons-wrapper .press-grey {
					background-color:#9E9E9E;
					border:2px solid #9E9E9E;
					color: #FFF;
				}
				.yrm-buttons-wrapper .press-grey:hover {
					color: #9E9E9E;
					background: #ffffff;
				}
				.yrm-buttons-wrapper .press-lightblue {
					background-color:#03A9F4;
					border:2px solid #03A9F4;
					color: #FFF;
				}
				.yrm-buttons-wrapper .press-lightblue:hover {
					color: #03A9F4;
					background-color: #FFF;
				}
				.yrm-review-wrapper, .yrm-review-block {
					text-align: center;
					padding: 20px;
					background: white;
				}
				.yrm-review-block .welcome-panel-content {
					min-height: auto !important;
				}
				.yrm-review-wrapper p {
					color: black;
				}
				.yrm-review-h1 {
					font-size: 22px;
					font-weight: normal;
					line-height: 1.384;
				}
				.yrm-review-h2{
					font-size: 20px;
					font-weight: normal;
				}
				:root {
					--main-bg-color: #1ac6ff;
				}
				.yrm-review-strong{
					color: var(--main-bg-color);
				}
				.yrm-review-mt20{
					margin-top: 20px
				}
			</style>
			<div class="yrm-review-wrapper">
				<div class="yrm-review-description">
					<?php echo wp_kses($firstHeader, $allowedTag); ?>
					<h2 class="yrm-review-h2">This is really great for your website score.</h2>
					<p class="yrm-review-mt20">Have your input in the development of our plugin, and we’ll provide better conversions for your site!<br /> Leave your 5-star positive review and help us go further to the perfection!</p>
				</div>
				<div class="yrm-buttons-wrapper">
					<button class="press press-grey yrm-button-1 yrm-already-did-review" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>">I already did</button>
					<button class="press press-lightblue yrm-button-3 yrm-already-did-review" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>" onclick="window.open('<?php echo YRM_REVIEW_URL; ?>')">You worth it!</button>
					<button class="press press-grey yrm-button-2 yrm-show-popup-period" data-ajaxnonce="<?php echo esc_attr($ajaxNonce); ?>" data-message-type="<?php echo esc_attr($type); ?>">Maybe later</button>
				</div>
			</div>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

	private function showReviewBlockJs() {
		ob_start();
		?>
			<script type="text/javascript">
				jQuery('.yrm-already-did-review').each(function () {
					jQuery(this).on('click', function () {
						var ajaxNonce = jQuery(this).attr('data-ajaxnonce');

						var data = {
							action: 'yrm_dont_show_review_notice',
							ajaxNonce: ajaxNonce
						};
						jQuery.post(ajaxurl, data, function(response,d) {
							console.log(d);
							console.log(response);
							if(jQuery('.yrm-review-block').length) {
								jQuery('.yrm-review-block').remove();
							}
						});
					});
				});

				jQuery('.yrm-show-popup-period').on('click', function () {
					var ajaxNonce = jQuery(this).attr('data-ajaxnonce');
					var messageType = jQuery(this).attr('data-message-type');

					var data = {
						action: 'yrm_change_review_show_period',
						messageType: messageType,
						ajaxNonce: ajaxNonce
					};
					jQuery.post(ajaxurl, data, function(response,d) {
						if(jQuery('.yrm-review-block').length) {
							jQuery('.yrm-review-block').remove();
						}
					});
				});
			</script>
		<?php
		$script = ob_get_contents();
		ob_end_clean();

		return $script;
	}

	public static function setInitialDates() {
		$usageDays = get_option('YrmUsageDays');
		if(!$usageDays) {
			update_option('YrmUsageDays', 0);

			$timeDate = new DateTime('now');
			$installTime = strtotime($timeDate->format('Y-m-d H:i:s'));
			update_option('YrmInstallDate', $installTime);
			$timeDate->modify('+'.YRM_SHOW_REVIEW_PERIOD.' day');

			$timeNow = strtotime($timeDate->format('Y-m-d H:i:s'));
			update_option('YrmShowNextTime', $timeNow);
		}
	}

	public static function deleteInitialDates() {
		delete_option('YrmUsageDays');
		delete_option('YrmInstallDate');
		delete_option('YrmShowNextTime');
	}
}