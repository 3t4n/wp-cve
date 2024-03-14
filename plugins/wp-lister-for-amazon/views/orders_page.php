<?php include_once( dirname(__FILE__).'/common_header.php' ); ?>

<style type="text/css">

	th.column-details {
		width: 25%;
	}

    div#wpla_update_options_wrapper:after {
        content: "";
        display: table;
        clear: both;
    }

    div.wpla_update_option {
        float: left; width: 30%;
        background: #fff;
        border: 1px solid #dcdcde;
        margin: 0 10px;
    }

    div.wpla_update_details {
        padding: 24px;
        position: relative;
    }

    div.wpla_update_details h2 {
        font-size: 20px;
        font-weight: 400;
        letter-spacing: -.32px;
        line-height: 28px;
        margin: 0!important;
        max-width: calc(100% - 48px);
    }

    div.wpla_update_footer {
        align-items: center;
        border-top: 1px solid #dcdcde;
        justify-content: space-between;
        padding: 10px 0;
        margin-top: 20px;
        text-align: left;
    }

    div.wpla_update_footer .button {
        background-color: #fff;
        border-color: #007cba;
        color: #007cba;
        font-size: 13px;
        height: 36px;
        line-height: 30px;
        padding: 2px 14px;
    }

</style>

<div class="wrap">
	<div class="icon32" style="background: url(<?php echo $wpl_plugin_url; ?>img/amazon-32x32.png) no-repeat;" id="wpl-icon"><br /></div>
	<h2>Amazon <?php echo __( 'Orders', 'wp-lister-for-amazon' ) ?></h2>
	<?php echo $wpl_message ?>


	<!-- show profiles table -->
	<?php $wpl_ordersTable->views(); ?>
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="profiles-filter" method="post" action="<?php echo $wpl_form_action; ?>" >
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ) ?>" />
        <input type="hidden" name="order_status" value="<?php echo isset($_REQUEST['order_status']) ? esc_attr($_REQUEST['order_status']) : ''; ?>" />
        <input type="hidden" name="has_wc_order" value="<?php echo isset($_REQUEST['has_wc_order']) ? esc_attr($_REQUEST['has_wc_order']) : ''; ?>" />
        <!-- Now we can render the completed list table -->
		<?php $wpl_ordersTable->search_box( __( 'Search', 'wp-lister-for-amazon' ), 'order-search-input' ); ?>
        <?php $wpl_ordersTable->display() ?>
    </form>

	<br style="clear:both;"/>

	<p>
	<?php
    // We are now tracking the orders cron separately
    $last_run = get_option( 'wpla_orders_cron_last_run', false );

    if ( !$last_run ) $last_run = get_option('wpla_cron_last_run');

    if (  $last_run ) :
    ?>
		<?php echo __( 'Last run', 'wp-lister-for-amazon' ); ?>:
		<?php echo human_time_diff( get_option('wpla_cron_last_run'), current_time('timestamp',1) ) ?> ago &ndash;
	<?php endif; ?>

	<?php if ( wp_next_scheduled( 'wpla_update_schedule' ) ) : ?>
		<?php echo __( 'Next scheduled update', 'wp-lister-for-amazon' ); ?>:
		<?php echo human_time_diff( wp_next_scheduled( 'wpla_update_schedule' ), current_time('timestamp',1) ) ?>
		<?php echo wp_next_scheduled( 'wpla_update_schedule' ) < current_time('timestamp',1) ? 'ago' : '' ?>
	<?php elseif ( get_option('wpla_cron_schedule') == 'external' ) : ?>
		<?php echo __( 'Background updates are executed by an external cron job.', 'wp-lister-for-amazon' ); ?>
	<?php else: ?>
		<?php echo __( 'Automatic background updates are currently disabled.', 'wp-lister-for-amazon' ); ?>
	<?php endif; ?>
	</p>


	<form method="post" action="<?php echo $wpl_form_action; ?>">
        <div class="submit1" style="">
            <?php wp_nonce_field( 'wpla_update_orders' ); ?>
            <input type="hidden" name="action" value="update_amazon_orders" />
            <input type="submit" value="<?php echo __( 'Update orders', 'wp-lister-for-amazon' ) ?>" name="submit" class="button-secondary"
                   title="<?php echo __( 'Update recent orders from Amazon.', 'wp-lister-for-amazon' ) ?>">

            <br />
            <p><a href="#" onclick="jQuery('#wpla_advanced_order_options').toggle();return false;"><?php echo __( 'Advanced Options &darr;', 'wp-lister-for-amazon' ) ?></a></p>
        </div>

        <div id="wpla_advanced_order_options" class="" style="display:none;">
            <h2><?php _e( 'Update Options', 'wp-lister-for-amazon' ); ?></h2>

            <div id="wpla_update_options_wrapper">
                <div class="wpla_update_option">
                    <div class="wpla_update_details">
                        <h2><?php _e( 'Update Timespan', 'wp-lister-for-amazon' ); ?></h2>

                        <p style="margin: 33px 0; text-align: center;">
                            <select name="days" id="wpla_number_of_days" class="required-entry select" style="width:auto;">
                                <option value=""   ><?php echo __( '-- since last updated order --', 'wp-lister-for-amazon' ); ?></option>
                                <option value="1"  >1  <?php echo __( 'day', 'wp-lister-for-amazon' ); ?></option>
                                <option value="2"  >2  <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                                <option value="3"  >3  <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                                <option value="5"  >5  <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                                <option value="7"  >7  <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                                <option value="10" >10 <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                                <option value="14" >14 <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                                <option value="28" >28 <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                                <option value="60" >60 <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                                <option value="90" >90 <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                                <option value="180">180 <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                                <option value="365">365 <?php echo __( 'days', 'wp-lister-for-amazon' ); ?></option>
                            </select>
                        </p>
                    </div>
                </div>

                <div class="wpla_update_option">
                    <div class="wpla_update_details">
                        <h2><?php _e( 'Download Specific Orders', 'wp-lister-for-amazon' ); ?></h2>
                        <p style="text-align: center;">
                            <textarea name="wpla_download_order_numbers" id="wpla_download_order_numbers" rows="3" cols="30" placeholder="Comma-separated Amazon order IDs"></textarea>
                        </p>
                    </div>
                </div>

                <div class="wpla_update_option">
                    <div class="wpla_update_details">
                        <h2><?php _e( 'Download by date', 'wp-lister-for-amazon' ); ?></h2>
                        <p style="margin: 33px 0; text-align: center;">
                            <input type="text" name="wpla_download_order_date" id="wpla_download_order_date" class="datepicker" placeholder="mm/dd/yyyy" />
                        </p>
                    </div>
                </div>
            </div>

            <div class="wpla_update_footer">
                <p>
                    <select name="wpla_download_from_account">
                        <option value="">Select account to download from</option>
                        <?php foreach ( WPLA()->accounts as $account ) : ?>
                            <option value="<?php echo $account->id; ?>"><?php echo $account->title .' ('. $account->market_code .')'; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>

                <p>
                    <input type="submit" value="<?php echo __( 'Update orders', 'wp-lister-for-amazon' ) ?>" name="submit" class="button" />
                </p>
            </div>

        </div>

	</form>


</div>
<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Prevent double-clicking on the Create Order link
        $("a.wpla_link").click(function (event) {
            if ($(this).hasClass("disabled")) {
                event.preventDefault();
            }
            $(this).addClass("disabled");
        });

        $(".datepicker").datepicker({
            maxDate: "+1d",
            dateFormat: "mm/dd/yy"
        });
    });
</script>
