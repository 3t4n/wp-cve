<?php

/*------------------------------------------------------------------------------------*/
/*  Plugin Control Panel ( Thanks & Credit to Nettuts A.K.A http://net.tutsplus.com )
/*  require_once enoty-options.php
/*------------------------------------------------------------------------------------*/
/* error_reporting(0);ini_set('display_errors', 0); */
function register_easynotify_setting()
{
    register_setting( 'easynotify_options_group', 'easynotify_opt', 'easynotify_validate_options' );
}

add_action( 'admin_init', 'register_easynotify_setting' );

function easynotify_add_admin()
{

    if ( isset( $_POST['_wp_http_referer'] ) && strpos( $_REQUEST['_wp_http_referer'], 'post_type=easynotify&page=easynotify_settings' ) !== false && isset( $_REQUEST['_wpnonce'] ) && check_admin_referer( 'easynotify_options_group-options' ) ) {

        if ( is_admin() && ( isset( $_GET['page'] ) == 'easynotify_settings' ) && ( isset( $_GET['post_type'] ) == 'easynotify' ) ) {

            if ( isset( $_REQUEST['action'] ) && 'save' == $_REQUEST['action'] ) {
                $encurtosv = get_option( 'easynotify_opt' );

                foreach ( enoty_get_settings_opt() as $enval ) {

                    if ( isset( $enval['id'] ) ) {

                        if ( isset( $_REQUEST[$enval['id']] ) ) {
                            $encurtosv[$enval['id']] = $_REQUEST[$enval['id']];
                        } else {
                            $encurtosv[$enval['id']] = '';
                        }

                    }

                    update_option( 'easynotify_opt', $encurtosv );
                }

                header( 'Location: edit.php?post_type=easynotify&page=easynotify_settings&saved=true' );
                die;

            }

        }

    }

    add_submenu_page(
        'edit.php?post_type=easynotify',
        __( ENOTIFY_NAME.' Settings', 'easynotify' ),
        __( 'Settings', 'easynotify' ),
        'manage_options',
        'easynotify_settings',
        'easynotify_admin'
    );

}

/*
|--------------------------------------------------------------------------
| REGISTER & ENQUEUE SCRIPTS/STYLES ONLY for a Specific Post Type
|--------------------------------------------------------------------------
 */
if ( is_admin() && ( isset( $_GET['page'] ) == 'easynotify_settings' ) && ( isset( $_GET['post_type'] ) == 'easynotify' ) ) {

    add_action( 'admin_head', 'easynotify_admin_cp_script' );
    add_action( 'admin_enqueue_scripts', 'easynotify_cp_script' );

    function easynotify_cp_script()
    {

        wp_enqueue_style( 'enoty-sldr' );
        wp_enqueue_style( 'enoty-cpstyles' );

        wp_enqueue_style( 'enoty-admin-styles', plugins_url( 'css/admin.css', __FILE__ ) );
        wp_enqueue_script( 'enoty-ibutton-js', plugins_url( 'js/jquery/jquery.ibutton.js', __FILE__ ) );
        wp_enqueue_style( 'enoty-ibutton-css', plugins_url( 'css/ibutton.css', __FILE__ ), false, ENOTIFY_VERSION );

        wp_enqueue_script( 'jquery-ui-core' );
        wp_enqueue_script( 'jquery-ui-slider' );
        wp_enqueue_script( 'jquery-ui-widget' );
        wp_enqueue_script( 'jquery-ui-mouse' );

        wp_enqueue_style( 'enoty-colorpicker' );
        wp_enqueue_script( 'enoty-colorpickerjs' );
        wp_enqueue_script( 'eno-cpscript', plugins_url( 'functions/easynotify-script.js', __FILE__ ) );

    }

    function easynotify_admin_cp_script()
    {
        ?>


<script type="text/javascript">
/*<![CDATA[*/
/* Pretty NextGen Pro */
(function($) {
		jQuery(document).ready(function() {

// -------- RESET SETTINGS (AJAX)
	jQuery('a.enresetnow').click(function() {
		var answer = confirm('Are you sure? This will restore these settings to default.');
			if (answer){
				var cmd = 'reset';
				easynotify_cp_reset(cmd);
		}
			else {}
	});

			function easynotify_cp_reset(cmd) {
				var data = {
				action: 'easynotify_cp_reset',
				security: '<?php echo wp_create_nonce( 'easynotify-nonce' ); ?>',
				cmd: cmd,
				};

				jQuery.post(ajaxurl, data, function(response) {
					if (response == 1) {
						window.location.href = 'edit.php?post_type=easynotify&page=easynotify_settings&reset=true';
						}
					else {
						alert('Ajax request failed, please refresh your browser window.');
						}
					});
			}


		/*  Control Panel info box */
		initSlideboxes();
 		function initSlideboxes()
		{
			setTimeout(function()
			{
				jQuery('.enoinfoboxsaveorreset').slideUp("slow");
			}, 2000);
		};


/* Slider init */
		jQuery(function() {
				 <?php
foreach ( enoty_get_settings_opt() as $enval ) {
            if ( $enval['type'] == 'slider' ) {
                $valtmp = enoty_get_option( $enval['id'] );
                //echo $valtmp;
                ?>

        jQuery( '#<?php echo $enval['id']; ?>_slider' ).slider({
            range: 'min',
            min: 0,
            max: <?php echo $enval['max']; ?>,
			<?php
if ( $enval['usestep'] == '1' ) {?>
			step: <?php echo $enval['step']; ?>,
			<?php }
                ?>
            value: '<?php echo $valtmp; ?>',
            slide: function( event, ui ) {
                jQuery( "#<?php echo $enval['id']; ?>" ).val( ui.value );
            	}
        	});

		 <?php }
        }
        ?>
		});

	// Pattern Selector
	jQuery('.eno_pattern_overlay').on('click', function() {
		var pattern = jQuery(this).attr('id');

		jQuery('.eno_pattern_overlay').removeClass('eno_pattern_selected');
		jQuery(this).addClass('eno_pattern_selected');
		jQuery('#eno_style_pattern').val(pattern);
	});

	});
})(jQuery);
/*]]>*/
</script>

<?php }

}

/*
END REGISTER & ENQUEUE SCRIPTS/STYLES
 */

/*
|--------------------------------------------------------------------------
| MAIN FORM - DISPLAY ELEMENT
|--------------------------------------------------------------------------
 */
function easynotify_admin()
{

    $i          = 0;
    $saveresmsg = '';
    $msgicon    = plugins_url( 'images/confirm-check.png', __FILE__ );

    if ( isset( $_REQUEST['saved'] ) ) {
        echo '<script type="text/javascript">
    jQuery(function () {
    jQuery(".enoinfoboxsaveorreset").show("slow");
    });
    </script>';
        $saveresmsg = 'Settings saved...';}

    if ( isset( $_REQUEST['reset'] ) ) {
        echo '<script type="text/javascript">
    jQuery(function () {
    jQuery(".enoinfoboxsaveorreset").show("slow");
    });
    </script>';
        $saveresmsg = 'Settings reset...';}

    ?>
<div id="enoty_container">
    <div id="header">
      <div class="logo">
       <div class="icon-option-left"></div>
        <div class="enoty-cp-title"><h2><?php echo ENOTIFY_NAME.'  (v '.ENOTIFY_VERSION.')'; ?></h2></div>
      </div>
      <div class="icon-option"> </div>
      <div style="clear: both;"></div>
    </div>

<div id="enotymain">
<div class="enoinfoboxsaveorreset"><?php echo $saveresmsg; ?></div>
<form method="post">
<div class="eno_wrap">
<div class="eno_opts">


<?php settings_fields( 'easynotify_options_group' );?>

<?php
foreach ( enoty_get_settings_opt() as $enval ) {

        switch ( $enval['type'] ) {
            case 'open':
                ?>
	<?php break;
            case 'close':
                ?>

</div>
</div>
<br />


<?php break;

            case 'defaultnotify':
                ?>

<div class="eno_input eno_select">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>

<select name="<?php echo $enval['id']; ?>" id="<?php echo $enval['id']; ?>">
<option id="disabled" value="disabled">Disabled</option>
<?php

                global $post;
                $args = array(
                    'post_type'      => 'easynotify',
                    'order'          => 'ASC',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                );

                $myposts = get_posts( $args );

                foreach ( $myposts as $post ): setup_postdata( $post );?>
	<option id="<?php echo $post->ID; ?>" type="text" value="<?php echo $post->ID; ?>" <?php
    if ( enoty_get_option( $enval['id'] ) == $post->ID ) {echo 'selected="selected"';}
                    ?>/><?php echo esc_html( esc_js( the_title( null, null, false ) ) ); ?></option>
	<?php endforeach;?>
</select>

	<small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;

            case 'text':
				// Avoid user switch version from Pro to Lite
				$arryopt = enoty_get_option( $enval['id'] );
				if ( isset( $arryopt ) && is_array( $arryopt ) ) { break;}
                ?>

<div class="eno_input eno_text <?php
if ( isset( $enval['group'] ) ) {echo $enval['group'];}
                ?>">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>
 	<input name="<?php echo $enval['id']; ?>" id="<?php echo $enval['id']; ?>" type="<?php echo $enval['type']; ?>" value="<?php
if ( enoty_get_option( $enval['id'] ) != '' ) {echo stripslashes( enoty_get_option( $enval['id'] ) );} else {echo $enval['std'];}
                ?>" />
 <small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>

 </div>
<?php
break;

            case 'textarea':
                ?>

<div class="eno_input eno_textarea">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>
 	<textarea style="vertical-align:top !important;" name="<?php echo $enval['id']; ?>" type="<?php echo $enval['type']; ?>" cols="" rows=""><?php
if ( enoty_get_option( $enval['id'] ) != '' ) {echo stripslashes( enoty_get_option( $enval['id'] ) );} else {echo $enval['std'];}
                ?></textarea>
 <small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>

 </div>

<?php
break;

            case 'textareainfo':
                ?>

<div class="eno_input eno_textarea">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>
 	<textarea id="emgwpinfo" style="vertical-align:top !important;" name="<?php echo $enval['id']; ?>" type="<?php echo $enval['type']; ?>" cols="" rows="" readonly><?php echo easynotify_get_wpinfo(); ?></textarea>
 <small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>

 </div>

<?php
break;
            case 'select':
                ?>

<div class="eno_input eno_select">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>

<select name="<?php echo $enval['id']; ?>" id="<?php echo $enval['id']; ?>">
<?php
if ( isset( $enval['options'] ) && is_array( $enval['options'] ) ) {
foreach ( $enval['options'] as $option ) {
                    ?>
		<option <?php
if ( enoty_get_option( $enval['id'] ) == $option ) {echo 'selected="selected"';}
                    ?>><?php echo $option; ?></option><?php }}
                ?>
</select>

	<small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;

            case 'checkbox':
                ?>

<div class="eno_input eno_checkbox <?php
if ( isset( $enval['group'] ) ) {echo $enval['group'];}
                ?>">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>
<?php ( enoty_get_option( $enval['id'] ) == 1 ) ? $checked = 'checked="checked"' : $checked = '';?>
<input name="<?php echo $enval['id']; ?>" id="<?php echo $enval['id']; ?>" class="enotyswitch" type="checkbox" <?php echo $checked; ?> value="1"></input>
	<small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>

 </div>

<?php break;
            case 'slider':
                ?>

<div class="eno_input">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>

    <div id="<?php echo $enval['id']; ?>_slider" ></div><input style="margin-left:10px; width:43px !important;" name="<?php echo $enval['id']; ?>" id="<?php echo $enval['id']; ?>" type="text" value="<?php
if ( enoty_get_option( $enval['id'] ) != '' ) {echo stripslashes( enoty_get_option( $enval['id'] ) );} else {echo $enval['std'];}
                ?>" /> <?php echo $enval['pixopr']; ?>

	<small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;
            case 'color':
                ?>

<div class="eno_input eno_text">
<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>

<div id="<?php echo $enval['id']; ?>_picker" class="colorSelector"><div></div></div>
<input style="margin-left:3px; width:75px !important;" name="<?php echo $enval['id']; ?>" id="<?php echo $enval['id']; ?>" type="text" value="<?php
if ( enoty_get_option( $enval['id'] ) != '' ) {echo stripslashes( enoty_get_option( $enval['id'] ) );} else {echo $enval['std'];}
                ?>" />
<small><?php echo $enval['desc']; ?></small>
<div class="clearfix"></div>
</div>

				  <script type="text/javascript">
				  /*<![CDATA[*/

				 jQuery(document).ready(function($) {

				 jQuery('#<?php echo $enval['id']; ?>_picker').children('div').css('backgroundColor', '<?php echo ( enoty_get_option( $enval['id'] ) ? enoty_get_option( $enval['id'] ) : $enval['std'] ); ?>');
				 jQuery('#<?php echo $enval['id']; ?>_picker').ColorPicker({
					color: '<?php echo ( enoty_get_option( $enval['id'] ) ? enoty_get_option( $enval['id'] ) : $enval['std'] ); ?>',
					onShow: function (colpkr) {
						jQuery(colpkr).fadeIn(500);
						return false;
					},
					onHide: function (colpkr) {
						jQuery(colpkr).fadeOut(500);
						return false;
					},
					onChange: function (hsb, hex, rgb) {
						//jQuery(this).css('border','1px solid red');
						jQuery('#<?php echo $enval['id']; ?>_picker').children('div').css('backgroundColor', '#' + hex);
						jQuery('#<?php echo $enval['id']; ?>_picker').next('input').attr('value','#' + hex);
					}
				  });

				  });

				  /*]]>*/
                  </script>



<?php break;
            case 'pattern':
                ?>

<div class="eno_input">
	<label style="vertical-align:top !important;" for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>
    <input type="hidden" value="<?php
if ( enoty_get_option( $enval['id'] ) != '' ) {echo stripslashes( enoty_get_option( $enval['id'] ) );} else {echo $enval['std'];}
                ?>" name="<?php echo $enval['id']; ?>" id="<?php echo $enval['id']; ?>" />

    <div class="eno_pattern_box">

                	<div style="float: left;" class="eno_pattern_overlay <?php
if ( ! enoty_get_option( $enval['id'] ) || enoty_get_option( $enval['id'] ) == 'none' ) {echo 'eno_pattern_selected';}
                ?>" id="eno_no_pattern"> no pattern </div>

                <?php

                foreach ( easynotify_get_list( 'patterns' ) as $pattern ) {
                    ( enoty_get_option( $enval['id'] ) == $pattern ) ? $sel = 'eno_pattern_selected' : $sel = '';
                    echo '<div class="eno_pattern_overlay '.$sel.'" id="'.$pattern.'" style="background: url('.plugins_url( 'css/images/patterns/', dirname( __FILE__ ) ).$pattern.') repeat top left transparent;"></div>';

                }

                ?>
</div>
	<small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>
</div>


<?php break;

            case 'mailmanager':
                ?>

<div class="eno_input eno_select <?php
if ( isset( $enval['group'] ) ) {echo $enval['group'];}
                ?>">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>

<select name="<?php echo $enval['id']; ?>" id="<?php echo $enval['id']; ?>">
<?php
foreach ( $enval['options'] as $key => $option ) {
                    ?>
		<option value="<?php echo $key; ?>" <?php
if ( enoty_get_option( $enval['id'] ) == $key ) {echo 'selected="selected"';}
                    ?>><?php echo $option; ?></option><?php }
                ?>
</select>

	<small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;

            case 'selectmmlist':
                ?>

<div class="eno_input eno_select <?php
if ( isset( $enval['group'] ) ) {echo $enval['group'];}
                ?>">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>
    <select style="width: 195px; margin-right: 5px;" name="<?php echo $enval['id']; ?>" id="<?php echo $enval['id']; ?>">
	<?php

                if ( enoty_get_option( $enval['id'] ) ) {
                    $listdata = explode( '|', enoty_get_option( $enval['id'] ) );
                    echo '<option value="'.enoty_get_option( $enval['id'] ).'">'.( isset( $listdata[1] ) ? $listdata[1] : 'None' ).'</option>';
                } else {
                    echo '<option value="none">None</option>';
                }

                ?>
		</select>
        <span class="button grablist" data-provider="<?php
if ( isset( $enval['group'] ) ) {echo $enval['group'];}
                ?>">Grab Lists</span>
	<small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;

            case 'textareaauth':

                $is_auth = get_option( 'easy_notify_pro_aweber_auth_info' );

                if ( $is_auth ) {
                    $style   = ' display:none;';
                    $textdis = 'readonly';
                    $stdisc  = '';
                } else {
                    $style   = '';
                    $stdisc  = ' display:none;';
                    $textdis = '';

                }

                ?>
<div class="eno_input eno_textarea <?php
if ( isset( $enval['group'] ) ) {echo $enval['group'];}
                ?>">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>
 	<textarea <?php echo $textdis; ?> id="<?php echo $enval['id']; ?>" style="vertical-align:top !important;" name="<?php echo $enval['id']; ?>" type="<?php echo $enval['type']; ?>" cols="" rows=""><?php
if ( enoty_get_option( $enval['id'] ) != '' ) {echo stripslashes( enoty_get_option( $enval['id'] ) );} else {echo $enval['std'];}
                ?></textarea>
 <small><?php echo $enval['desc']; ?><span style="text-align: center; margin-top:15px;<?php echo $style; ?>" class="button-primary awconnect" data-provider="<?php
if ( isset( $enval['group'] ) ) {echo $enval['group'];}
                ?>">Connect to Aweber</span><span style="text-align: center; margin-top:15px;<?php echo $stdisc; ?>" class="button-primary awdisconnect" data-provider="<?php
if ( isset( $enval['group'] ) ) {echo $enval['group'];}
                ?>">Disconnect</span></small><div class="clearfix"></div>
 </div>

<?php
break;

            case 'textpass':
                ?>

<div class="eno_input eno_text <?php
if ( isset( $enval['group'] ) ) {echo $enval['group'];}
                ?>">
	<label for="<?php echo $enval['id']; ?>"><?php echo $enval['name']; ?></label>
 	<input name="<?php echo $enval['id']; ?>" id="<?php echo $enval['id']; ?>" type="password" value="<?php
if ( enoty_get_option( $enval['id'] ) != '' ) {echo stripslashes( enoty_get_option( $enval['id'] ) );} else {echo $enval['std'];}
                ?>" />
 <small><?php echo $enval['desc']; ?></small><div class="clearfix"></div>

 </div>
<?php
break;

            case 'section':
                $i++;
                ?>

<div class="eno_section">
<?php $imgpth = plugins_url( 'images/trans.png', __FILE__ );?>
<div class="eno_title"><h3><img src="<?php echo $imgpth; ?>" class="inactive" alt="""><?php echo $enval['name']; ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save Changes" class="button button-primary" />
</span><div class="clearfix"></div></div>
<div class="eno_options <?php
if ( isset( $enval['groupfield'] ) ) {echo $enval['groupfield'];}
                ?>">

<?php break;

        }

    }

    ?>

<input type="hidden" name="action" value="save" />
 </div> </div>
 </form>
 </div>

<p class="submit">
<a onClick="return false;" class="enresetnow button-secondary" title="Reset Options" href="#">Reset Options</a>
<span style="color:#666666;margin-left:2px; font-size:11px;"> Use this button to restore these settings to default.</span></p>
 </div>

<?php
}

add_action( 'admin_menu', 'easynotify_add_admin' );

/*
|--------------------------------------------------------------------------
| Sanitize and validate input. Accepts an array, return a sanitized array.
|--------------------------------------------------------------------------
 */
function easynotify_validate_options( $input )
{

// strip html from textboxes
    if ( isset( $input['text'] ) ) {
        $input['text'] = wp_filter_nohtml_kses( $input['text'] );
    }

    if ( isset( $input['textarea'] ) ) {
        $input['textarea'] = wp_filter_nohtml_kses( $input['textarea'] );
    }

    return $input;
}