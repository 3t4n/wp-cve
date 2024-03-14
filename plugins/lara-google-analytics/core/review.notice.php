<?php
namespace Lara\Widgets\GoogleAnalytics;

/**
 * @package    Google Analytics by Lara
 * @author     Amr M. Ibrahim <mailamr@gmail.com>
 * @link       https://www.xtraorbit.com/
 * @copyright  Copyright (c) XtraOrbit Web development SRL 2016 - 2020
 */

if (!defined("ABSPATH"))
    die("This file cannot be accessed directly");

class ReviewNotice {
	
	public static function show_review_notice(){
        
		$current_date_raw = date('Y-m-d');
		$install_date_raw = DataStore::database_get("global_options", "install_date");
		if (!empty($install_date_raw)){
			$install_date = new \DateTime( $install_date_raw );
			$current_date = new \DateTime( $current_date_raw );
			$difference_in_days = (int)$install_date->diff($current_date)->format("%r%a");

			if (isset($difference_in_days) && $difference_in_days >= 14) {
				self::review_notice();
			}

		}else{
			DataStore::database_set("global_options", "install_date", $current_date_raw);
			DataStore::commit();
		}

			
	}
	
	public static function review_notice(){
		
		$message = __('Thank you for using <b>Lara Google Analytics</b> - widget. We hope it has saved your valuable time and efforts!', 'lara-google-analytics');
		$message .= "<br><br>".__('If you have a free moment, and want to help us spread the word and boost our motivation, please do us a BIG favour and give us 5 Stars rating .. The more reviews we get, the more cool features we will add to the plugin :)', 'lara-google-analytics');
		
		$btns = '<a href="https://wordpress.org/support/plugin/lara-google-analytics/reviews/" target="_blank"><button class="btn" id="rating" type="button">';
		$btns .= '<span><i class="fas fa-star fa-fw"></i> <i class="fas fa-star fa-fw"></i> <i class="fas fa-star fa-fw"></i> <i class="fas fa-star fa-fw"></i> <i class="fas fa-star fa-fw"></i></span>&nbsp;&nbsp;';
		$btns .= __('Let\'s do it, You deserve it', 'lara-google-analytics');
		$btns .= '</button></a>';
		
		$btns .= '<button class="btn " data-lrrateresponse="rated" type="button"><i class="far fa-thumbs-up fa-fw"></i>&nbsp;&nbsp;';
		$btns .= __('I already rated it', 'lara-google-analytics');
		$btns .= '</button>';
		
		$btns .= '<button class="btn " data-lrrateresponse="notinterested" type="button"><i class="fas fa-ban fa-fw"></i>&nbsp;&nbsp;';
		$btns .= __('Not Interested', 'lara-google-analytics');
		$btns .= '</button>';		
		

		echo '
		<style>
		#lrgawidget_reviews_notice {
			display: table;
			background-image: url("'.lrgawidget_plugin_dist_url.'/img/xo_footer.png");
			background-position: center bottom;
			background-repeat: repeat-x;
			background-color: #f9f9f9;
			height: 150px;
			padding: 10px;
		}
		
		#lrgawidget_reviews_notice .lrxologo {
			display: table-cell;
			background-image: url("'.lrgawidget_plugin_dist_url.'/img/xtraorbit_logo_small.png");
			background-repeat: no-repeat;
			background-position: center top;
			width: 400px;
			height: 150px;
			margin-top: 15px;
		}
		
		#lrgawidget_reviews_notice .lrnoticelogo {
			display: table-cell;
			vertical-align: middle;
		}	

		#lrgawidget_reviews_notice .lrnoticemessage {
			display: table-cell;
			border: 25px solid transparent;
			vertical-align: top;
		}

		#lrgawidget_reviews_notice .btn {
			background-color: #272e38;
			color: #FFFFFF;
			border: 1px solid transparent;
			cursor: pointer;
			margin: 0px 10px 10px 0px;
			min-height: 40px;
			border-radius: 5px;
		}
		
		#lrgawidget_reviews_notice .btn:hover {
			background-color: #f7981d;
		}		
		
		</style>
		
		<script type="text/javascript">
		(function($) {
			function lrWidgetSettings(arr){
				return $.ajax({
					method: "POST",
					url: lrgawidget_ajax_object.lrgawidget_ajax_url,
					data: arr,
					dataType: "json"
				});
			}
			
			$(document).ready(function(){
				$("[data-lrrateresponse]").on("click", function (e) {
					lrWidgetSettings({action : "lrgawidget_review_response", rresponse: $(this).data("lrrateresponse")}).done(function (data, textStatus, jqXHR) {
						$("#lrgawidget_reviews_notice").slideUp("fast");
					});					
				});
			});
		})(jQuery);
		</script>


		<div id="lrgawidget_reviews_notice" class="notice notice-info">
			<div class="lrnoticelogo"><img src="'.lrgawidget_plugin_dist_url.'/img/lrwidget_logo.png"></div>
			<div class="lrnoticemessage">'.$message.'<br><br>'.$btns.'</div>
			<a href="https://www.xtraorbit.com/wordpress-google-analytics-dashboard-widget/?utm_source=InApp&amp;utm_medium=Rate_Screen" target="_blank"><div class="lrxologo">&nbsp;</div></a>
		</div>';
	}	
}

?>