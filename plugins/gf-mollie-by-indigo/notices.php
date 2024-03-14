<?php
/*
 *
 */




add_action('admin_notices', 'gf_mollie_rating_request' );
function gf_mollie_rating_request() {
	$rating_request = get_option( 'gf_mollie_rating_request');
   // echo '<h1>time = '.$rating_request.'</h1>';
    if ( ! gf_mollie_show_rating_request() ) {
        return;
    }
    ?>
    <div class="gf_mollie_rating" style="box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
border-left: none;
background-color: #c4c4c4;
color: #000;
padding: 10px;
margin: 10px;
margin-left: 0px;">
        <h2><?php _e( 'Support GF Mollie!', 'gf-mollie-by-indigo'); ?></h2>

	    <p><?php _e( 'Awesome! You\'ve been using GF Mollie by Indigo for more than a month! May we ask you to give it a bit support?', 'gf-mollie-by-indigo'); ?></p>
        <p><?php _e( 'We believe this solution should be free, but if you appreciate our work, please consider donating or rating this plugin!', 'gf-mollie-by-indigo'); ?></p>
        <p><a class="button" href="https://www.indigowebstudio.nl/donate/" target="_blank" style="background: #ff7501;
                    color: #fff;
                    box-shadow: none;
                    border: none;
                    font-size: 20px;
                    padding: 5px 10px;
                    outline: none;

                    display: inline;"><span style="color: #22155a;
line-height: 26px;
padding-right: 10px;" class="dashicons dashicons-heart"></span><?php _e( 'Donate', 'gf-mollie-by-indigo'); ?></a>
            <a class="button" href="https://wordpress.org/support/plugin/gf-mollie-by-indigo/reviews/" target="_blank" style="background: #ff7501;
color: #fff;
box-shadow: none;
border: none;
font-size: 20px;
padding: 5px 10px;
outline: none;
margin-left:20px;
display: inline;"><span style="color: #22155a;
line-height: 26px;
padding-right: 10px;" class="dashicons dashicons-star-filled"></span><?php _e( 'Give a rating', 'gf-mollie-by-indigo'); ?></a></p>
        <p><?php _e( 'We would be happy with your feedback!', 'gf-mollie-by-indigo'); ?></p>
        <p>
            <a href="javascript:void(0);" class="gf_mollie_aks_later" style="text-decoration:underline;color:#000;"><?php _e( 'I want to rate later, ask me next month', 'gf-mollie-by-indigo'); ?>
            </a>
            <br>
            <a href="javascript:void(0);" class="gf_mollie_hide" style="text-decoration:underline;color:#000;">
                <?php _e('I already did', 'gf-mollie-by-indigo')?>
            </a>

        </p>







    </div>
    <script type="text/javascript">
        jQuery(document).ready(function ($) {

            $(".gf_mollie_aks_later").click(function (e) {
                e.preventDefault();

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {action: "gf_mollie_ask_later"},
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(xhr.status + ' ' + xhr.statusText + '---' + textStatus);
                        console.log(textStatus);

                        alert(
                            "Unknown error. Please get in contact with us to solve it info@indigowebstudio.nl"
                        );
                    },
                    success: function (data) {
                        $(".gf_mollie_rating").slideUp("fast");
                        return true;
                    }
                });
            });

            $(".gf_mollie_hide").click(function (e) {
                e.preventDefault();

                $.ajax({
                    url: ajaxurl,
                    type: "POST",
                    data: {action: "gf_mollie_hide"},
                    error: function (xhr, textStatus, errorThrown) {
                        console.log(xhr.status + ' ' + xhr.statusText + '---' + textStatus);
                        console.log(textStatus);


                        alert(
                            "Unknown error. Please get in contact with us to solve it info@indigowebstudio.nl"
                        );
                    },
                    success: function (data) {
                        $(".gf_mollie_rating").slideUp("fast");
                        return true;
                    }
                });
            });




        });
    </script>
    <?php
}


add_action( 'wp_ajax_gf_mollie_hide', 'gf_mollie_hide' );
function gf_mollie_hide() {
	update_option( 'gf_mollie_rating_request', 'no' );
	wp_die(); // this is required to terminate immediately and return a proper response
}

add_action( 'wp_ajax_gf_mollie_ask_later', 'gf_mollie_aks_later' );
function gf_mollie_aks_later() {
	update_option( 'gf_mollie_rating_request', time() );
	wp_die(); // this is required to terminate immediately and return a proper response
}



function gf_mollie_show_rating_request() {
    $rating_request = get_option( 'gf_mollie_rating_request');
    if ( $rating_request == 'no' ) {
        return false;
    }
    if ( $rating_request == '' ) {
        //  Save current date for rating request
        update_option( 'gf_mollie_rating_request', time() );
        return false;
    }
    $diff = 30*24*60*60;
    if ( time() > ($rating_request + $diff)  ) {
	    return true;
    }
    return false;
}


?>