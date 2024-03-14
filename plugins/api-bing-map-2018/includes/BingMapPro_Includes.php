<?php

namespace BingMapPro_Includes;

if( ! defined('ABSPATH') ) die('No Access to this page');

class BingMapPro_Includes{

    public static function add_feedback_form() {
            ?>
                <style>
                    .bmp-feedback-header{
                        background-color: #00bda5;
                        background-image: linear-gradient(-303deg, #7b7b7b, #00afb2 56%, #00bda5);
                        position: absolute;
                        top: 0px;
                        left: 0px;
                        width: 100%;
                        align-items: center;
                        min-height: 80px;
                    }

                    .bmp-feedback-header h2{
                        color: white;                        
                        padding-left: 15px;
                        font-size: 1.5rem;
                        padding-top: 5px;
                    }

                    #bmp_feedback_wrapper{
                        background: #000;
                        opacity: 0.7;
                        filter: alpha(opacity=70);
                        position: fixed;
                        top: 0;
                        right: 0;
                        bottom: 0;
                        left: 0;
                        z-index: 1000050;
                    }
                    #bmp_feedback_container{
                        display: block;
                        position: fixed;
                        top: 0px;
                        z-index: 1000051;
                        background-color: white;
                        left: 30%;
                        margin: 20px;
                        padding: 20px;
                        margin-top: 50px;                      
                        left: 50%;
                        width: 450px;
                        transform: translateX(-50%);
                    }
                    #bmp_modal_close{
                        width: 16px;
                        height: 16px;
                        float: right;
                        cursor: pointer;
                        margin-top: -35px;
                        margin-right: 20px;
                        color: white;
                    }

                    .bmp-close-path{
                        fill: currentcolor;
                        stroke: currentcolor;
                        stroke-width: 2;
                    }
                    #bmp_close_svg{
                        display: block;
                        -webkit-box-flex: 1;
                        flex-grow: 1;
                    }
                    .bmp-radio-input-container{
                        padding: 5px;
                    }
                    .bmp-feedback-body{
                        margin-top: 90px;
                    }
                    #bmp_feedback_textarea{
                        margin-bottom: 10px;
                        text-align: left;
                        vertical-align: middle;
                        transition-property: all;
                        transition-duration: 0.15s;
                        transition-timing-function: ease-out;
                        transition-delay: 0s;
                        border-radius: 3px;
                        border: 1px solid #cbd6e2;
                        background-color: #f5f8fa;
                        margin-top: 10px;
                        padding: 9px 10px;
                        width: 100%;
                    }
                    .bmp-radio-input-container{
                        font-size: 1rem;
                    }
                    #bmp_email_input{
                        border-radius: 3px;
                        border: 1px solid #cbd6e2;
                        background-color: #f5f8fa;
                        margin-top: 10px;
                        padding: 9px 10px;
                        width: 100%; 
                        margin-bottom: 20px;
                    }
                    .bmp-req-sel{
                        text-decoration: underline;
                    }

                </style>
                <div id="bmp_feedback_wrapper" style="display: none"> </div>
                    <div id="bmp_feedback_container" style="display: none;">
                        <div class="bmp-feedback-header">
                                <h2><?php echo esc_html( __( "We're sorry to see you go", 'bing-map-pro' ) ); ?></h2>
                                <div id="bmp_modal_close" >
                                    <svg id="bmp_close_svg" viewBox="0 0 14 14" xmlns="http://www.w3.org/2000/svg">
                                        <path class="bmp-close-path" d="M14.5,1.5l-13,13m0-13,13,13" transform="translate(-1 -1)"></path>
                                    </svg>
                                </div>
                        </div>
                        <div class="bmp-feedback-body">
                            <div>
                                <strong>
                                    <h3> <?php echo esc_html( __( "If you have a moment, please let us know why you're deactivating the plugin.", 'bing-map-pro' ) ); ?> </h3>
                                </strong>
                            </div>
                            <form id='bmp_deactivate_form' class="bmp-deactivate-form">
                                <?php

                                $radio_buttons = array(
                                    __( "Lack of functionality", 'bing-map-pro' ),
                                    __( "Too difficult to use", 'bing-map-pro' ),
                                    __( "The plugin isn't working", 'bing-map-pro' ),
                                    __( "The plugin isn't useful", 'bing-map-pro' ),
                                    __( 'Temporarily disabling or troubleshooting', 'bing-map-pro' )                                   
                                );

                                $buttons_count = count( $radio_buttons );
                                for ( $i = 0; $i < $buttons_count; $i++ ) {
                                    ?>
                                        <div class="bmp-radio-input-container">
                                            <input
                                                type="radio"
                                                id="bmp_Feedback<?php echo esc_attr( $i ); ?>"
                                                name="bmpfeedback"
                                                value="<?php echo esc_attr( $i ); ?>"
                                                class="bmp-feedback-radio"
                                                required
                                            />
                                            <label for="bmp_Feedback<?php echo esc_attr( $i ); ?>">
                                                <?php echo esc_html( $radio_buttons[ $i ] ); ?>
                                            </label>
                                        </div>
                                    <?php
                                }
                                ?>

                                <textarea name="details" id="bmp_feedback_textarea" class="bmp-feedback-text-area bmp-feedback-text-control"
                                    placeholder="<?php echo esc_html( __( 'Extra Feedback...', 'bing-map-pro' ) ); ?>"></textarea>
                                
                                <div>
                                    <strong>
                                        <h3> <?php echo esc_html( __( "Thank you for your feedback. If you would like to tell us more please add your email and we'll get in touch.", 'bing-map-pro' ) ); ?> </h3>
                                    </strong>
                                </div>

                                <input name="email" type="email" class="bmp-email-input" id="bmp_email_input" placeholder="<?php echo esc_attr( __( 'Email', 'bing-map-pro' ) ); ?>">

                                <div class="bmp-button-container">
                                    <button type="submit" id="bmp_btn_feedback_submit" class="button button-primary">
                                        <div class="bmp-loader-button-content">
                                            <?php echo esc_html( __( 'Submit & deactivate', 'bing-map-pro' ) ); ?>
                                        </div>                                       
                                    </button>
                                    <button type="button" id="bmp_btn_feedback_skip" class="button action">
                                        <?php echo esc_html( __( 'Skip & deactivate', 'bing-map-pro' ) ); ?>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        (function(){

                            var bmp_btn_feedback_close  = document.getElementById('bmp_modal_close');
                            var bmp_btn_uninstall       = document.querySelector('[data-slug="api-bing-map-2018"] .deactivate a');
                            var bmp_btn_uninstall_href  = '';
                            
                            if( bmp_btn_uninstall  !== null )
                                bmp_btn_uninstall_href = bmp_btn_uninstall.getAttribute('href');    

                            var bmp_feedback_wrapper    = document.getElementById('bmp_feedback_wrapper');   
                            var bmp_feedback_container  = document.getElementById('bmp_feedback_container');                 
                            var bmp_btn_feedback_submit = document.getElementById('bmp_btn_feedback_submit');
                            var bmp_btn_feedback_skip   = document.getElementById('bmp_btn_feedback_skip');

                            if( bmp_btn_feedback_close !== null ){
                                bmp_btn_feedback_close.addEventListener('click', function( e ){                               
                                    bmp_feedback_wrapper.style.display = 'none';
                                    bmp_feedback_container.style.display = 'none';
                                });
                            }

                            if( bmp_btn_uninstall !== null ){
                                bmp_btn_uninstall.addEventListener('click', function( e ){
                                    if( bmp_feedback_wrapper !== null && bmp_feedback_container !== null ){

                                        e.preventDefault();
                                        bmp_feedback_wrapper.style.display = 'block';
                                        bmp_feedback_container.style.display = 'block';
                                        return false;
                                    }                                   
                                });
                            }

                            if( bmp_btn_feedback_skip !== null ){
                                bmp_btn_feedback_skip.addEventListener( 'click', function( e ){
                                    window.location.href = bmp_btn_uninstall_href;
                                });                               
                            }

                            if( bmp_btn_feedback_submit !== null && bmp_btn_uninstall !== null ){
                                bmp_btn_feedback_submit.addEventListener( 'click', function( e ){
                                    e.preventDefault();
                                    let bmp_option_val, bmp_other_val, bmp_email_val = '';
                                    let bmp_option = document.querySelector("input[name='bmpfeedback']:checked");
                                    let bmp_other  = document.getElementById('bmp_feedback_textarea');
                                    let bmp_email  = document.getElementById('bmp_email_input');

                                    if( bmp_option == null || typeof bmp_option === 'undefined'){
                                        document.querySelectorAll('.bmp-radio-input-container').forEach( function( item ){
                                            item.classList.add('bmp-req-sel');
                                        });
                                        return;
                                    }

                                    if( bmp_option )
                                        bmp_option_val = bmp_option.value;
                                    if( bmp_other )
                                        bmp_other_val = bmp_other.value;
                                    if( bmp_email )
                                        bmp_email_val = bmp_email.value;

                                    try{
                                        bmp_option_val  = encodeURI( bmp_option_val );
                                        bmp_other_val   = encodeURI( bmp_other_val );
                                        bmp_email_val   = encodeURI( bmp_email_val );
                                    }catch( e ){
                                        console.error('wrong value for uri encoding');
                                    }
                                    
                                    let bmp_append_url = '&feedback=true&option=' + bmp_option_val + '&other=' + bmp_other_val + '&email=' + bmp_email_val;
                                    let bmp_new_href = bmp_btn_uninstall_href.concat( bmp_append_url );
                                    
                                    window.location.href = bmp_new_href;                                  
                                });
                            }

                        }() );
                    </script>               
            <?php
     
    }

    public static function bmp_loading_screen(){
        ?>
    <div class="cssload-loader-inner loaderImg">
		<div class="cssload-cssload-loader-line-wrap-wrap">
			<div class="cssload-loader-line-wrap"></div>
		</div>
		<div class="cssload-cssload-loader-line-wrap-wrap">
			<div class="cssload-loader-line-wrap"></div>
		</div>
		<div class="cssload-cssload-loader-line-wrap-wrap">
			<div class="cssload-loader-line-wrap"></div>
		</div>
		<div class="cssload-cssload-loader-line-wrap-wrap">
			<div class="cssload-loader-line-wrap"></div>
		</div>
		<div class="cssload-cssload-loader-line-wrap-wrap">
			<div class="cssload-loader-line-wrap"></div>
		</div>
	</div>  
    <?php
    }

    public static function bmp_error_screen(){
        ?>
        <div id="ajaxError" class="alert alert-danger alert-dismissible " role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="false">&times;</span>
            </button>
            <strong><?php esc_html_e('Error Occured.', 'bing-map-pro'); ?></strong> <?php esc_html_e('Please try again, or contact the developer.', 'bing-map-pro');?>
        </div>

        <?php
    }

    public static function bmp_donate(){
        ?>
        <div>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="HH7J3U2U9YYQ2">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
            </form>
            <p style="display: inline;"> <b><?php esc_html_e('Any donations will help me keep plugins free, and develop new ones. '.
            'Suggestions, or support are welcome at developer@tuskcode.com', 'bing-map-pro'); ?> </b></p>
                <p>                 
                    <span>
                        <b>
                            <?php esc_html_e( 'An average of 150 hours of development were dedicated for this free plugin', 'bing-map-pro');?> 
                            .
                            <?php esc_html_e( 'Please consider rating the plugin at ', 'bing-map-pro');?> 
                            <a href="https://wordpress.org/plugins/api-bing-map-2018/" target="_blank"> www.wordpress.org/api-bing-map-2018</a> 
                            &#11088;
                            &#11088;
                            &#11088;
                            <?php esc_html_e( 'Thank You :)', 'bing-map-pro');?> 
                        </b>
                    </span>
                    <br />
                    <span> 
                        <?php esc_html_e( 'Free API Key:', 'bing-map-pro');?>
                        <a href="https://www.bingmapsportal.com/" target="_blank"> www.bingmapsportal.com </a> 
                        <?php esc_html_e('and follow the instructions!', 'bing-map-pro');?> 
                    </span>

                </p>
                <p>
                  
                </p>
        </div>
        <?php
    }

    public static function bmp_error_api_key( $bmp_api_key, $bmp_page ){
        global $bmp_api_key;
        if( strcmp( trim( $bmp_api_key ), $bmp_api_key) == 0 ){
            ?>

            <div id='alert_api_key' class="alert alert-danger">
                <h4><strong><?php esc_html_e('You are using a testing API Key.', 'bing-map-pro');?> </strong> </h4>
                <?php
                    if( $bmp_page === 0 ){
                        ?>
                        <h5><?php esc_html_e('Please use your own API Key after finishing testing this plugin.', 'bing-map-pro'); ?> </h5>
                        <h5><?php esc_html_e('Please go to Bing Map Pro ->Settings to update your API KEY.', 'bing-map-pro'); ?> </h5>
                        
                    <?php 
                    }else{
                    ?>
                        <h5><?php esc_html_e('Please use your own API Key after finishing testing this plugin.', 'bing-map-pro'); ?> </h5>
                    <?php
                    }
                    ?>

            </div>

        <?php
        }
    }

    public static function bmp_toggleCkb( $isActive, $id, $name ){
        $mapStatus = $isActive ? 'checked' : '';
        $bmp_On    = esc_html__('Yes', 'bing-map-pro');
        $bmp_Off   = esc_html__('No', 'bing-map-pro');
        return  "<div class='checkbox'> " .                                    
                    "<input type='checkbox' ". $mapStatus . " name='{$name}' ".
                     " id='{$id}' data-width='50' data-size='mini' data-on='{$bmp_On}' data-off='{$bmp_Off}' data-toggle='toggle' />".            
                "</div>"; 
    }

    public static function bmp_pinToggleCkb( $isActive, $id, $name, $pin_id ){
        $mapStatus = $isActive ? 'checked' : '';
        $bmp_On    = esc_html__('Yes', 'bing-map-pro');
        $bmp_Off   = esc_html__('No', 'bing-map-pro');
        return  "<div class='checkbox'> " .                                    
                    "<input onchange='bmp_pin_ckb( this, $pin_id )' data-on='{$bmp_On}' data-off='{$bmp_Off}'  type='checkbox' ". $mapStatus . " name='{$name}' ".
                     " id='{$id}' data-width='50' data-size='mini' data-toggle='toggle' />".            
                "</div>"; 
    }

    public static function bmp_createMeasureType( $types, $selType, $name, $id ){
        ?>
            <select class="form-control"  name="<?php echo esc_attr( $name );?>" id="<?php echo esc_attr($id);?>">
                <?php
                    foreach( $types as $key=>$type ){
                        $type = esc_html( $type );
                        if( $type == $selType ){
                            echo "<option value='{$key}' selected> {$type} </option>";
                        }else{
                            echo "<option value='{$key}' > {$type} </option>";   
                        }
                    }
                ?>
            </select>
    
        <?php
    }

    public static function bmp_createPinIconList( $types, $selType, $name, $id ){
        $name = esc_html( $name );
        ?>
            <select class="form-control" class='bmp-pin-icon-options' name="<?php echo esc_attr( $name );?>" id="<?php echo esc_attr( $id );?>">
                <?php
                    foreach( $types as $key=>$type ){
                        $type = esc_html( $type );
                        if( $key == $selType ){
                            echo "<option id='sel-{$key}' value='{$key}' selected>  {$type} </option>";
                        }else{
                            echo "<option id='sel-{$key}' value='{$key}' > {$type} </option>";   
                        }
                    }
                ?>
            </select>
    
        <?php
    }

    public static function bmp_delete_modal( $type ){
        ?>

        <div class="modal fade bmp-modal-<?php echo esc_attr( $type );?>" id="bmp_modal_<?php echo esc_attr( $type );?>" role="dialog">
                <div class="modal-dialog">
                         
                    <!-- Modal content-->
                    <div class="modal-content">
                    <div class='modal-headline'></div>   
                        <div class="modal-header">
                            
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h3 class="modal-title"> <?php esc_html_e('Delete Map', 'bing-map-pro');?></h3>
                        </div>
                        <div class="modal-body">                      
                            <?php if( $type == 'map' ) { ?>
                                <div class="input-group">
                                   
                                    <p class='h4'><strong>  <span ><i class="fa fa-trash" aria-hidden="true"></i> </span> <?php esc_html_e('Are you sure you want to delete this map?', 'bing-map-pro');?> </strong></p>
                                </div>
                            <?php } ?>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="button button-secondary" data-dismiss="modal"> <?php esc_html_e('No', 'bing-map-pro');?></button>
                            <button type="button" id='btn_delete_<?php echo esc_attr( $type );?>' class="button button-primary" data-dismiss="modal"> <?php esc_html_e('Yes', 'bing-map-pro');?> </button>
                        </div>
                    </div>
                
                </div>
        </div>      


        <?php
    }

    public static function utf8_urldecode($str) {
        return html_entity_decode(preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($str)), null, 'UTF-8');
    }

    public static function bmp_internalization(){
        ?>
        <script>
            var s_edit_cirle_drag_drop = "<?php esc_html_e('Drag the shape to desired location', 'bing-map-pro') ?>";
            var s_Name = "<?php esc_html_e('Name', 'bing-map-pro') ?>";
            var s_Shape_Type = "<?php esc_html_e('Shape Type', 'bing-map-pro') ?>";
            var s_Shape  = "<?php esc_html_e('Shape', 'bing-map-pro') ?>";
            var s_Infobox_Type  = "<?php esc_html_e('Infobox Type', 'bing-map-pro') ?>";
            var s_Action  = "<?php esc_html_e('Action', 'bing-map-pro') ?>";
        </script>
        <?php
    }
}