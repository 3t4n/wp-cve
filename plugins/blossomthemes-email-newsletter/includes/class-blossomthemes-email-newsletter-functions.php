<?php
/**
 * Functions of the plugin.
 *
 * @package    Blossomthemes_Email_Newsletter
 * @subpackage Blossomthemes_Email_Newsletter/includes
 * @author    blossomthemes
 */

class Blossomthemes_Email_Newsletter_Functions {
// JavaScript Minifier
	function __construct()
	{
		add_action( 'wp_ajax_bten_get_mailing_list', array( $this, 'bten_get_mailing_list' ) );
		add_action( 'wp_ajax_nopriv_bten_get_mailing_list', array( $this, 'bten_get_mailing_list' ) );
	} 

	function bten_minify_js( $input ) {
	    if(trim($input) === "") return $input;
	    return preg_replace(
	        array(
	            // Remove comment(s)
	            '#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
	            // Remove white-space(s) outside the string and regex
	            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
	            // Remove the last semicolon
	            '#;+\}#',
	            // Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
	            '#([\{,])([\'])(\d+|[a-z_]\w*)\2(?=\:)#i',
	            // --ibid. From `foo['bar']` to `foo.bar`
	            '#([\w\)\]])\[([\'"])([a-z_]\w*)\2\]#i',
	            // Replace `true` with `!0`
	            '#(?<=return |[=:,\(\[])true\b#',
	            // Replace `false` with `!1`
	            '#(?<=return |[=:,\(\[])false\b#',
	            // Clean up ...
	            '#\s*(\/\*|\*\/)\s*#'
	        ),
	        array(
	            '$1',
	            '$1$2',
	            '}',
	            '$1$3',
	            '$1.$3',
	            '!0',
	            '!1',
	            '$1'
	        ),
	    $input);
	}


	
	function bten_minify_css( $input ) {
	    if(trim($input) === "") return $input;
	    // Force white-space(s) in `calc()`
	    if(strpos($input, 'calc(') !== false) {
	        $input = preg_replace_callback('#(?<=[\s:])calc\(\s*(.*?)\s*\)#', function($matches) {
	            return 'calc(' . preg_replace('#\s+#', "\x1A", $matches[1]) . ')';
	        }, $input);
	    }
	    return preg_replace(
	        array(
	            // Remove comment(s)
	            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
	            // Remove unused white-space(s)
	            '#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~+]|\s*+-(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
	            // Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
	            '#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
	            // Replace `:0 0 0 0` with `:0`
	            '#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
	            // Replace `background-position:0` with `background-position:0 0`
	            '#(background-position):0(?=[;\}])#si',
	            // Replace `0.6` with `.6`, but only when preceded by a white-space or `=`, `:`, `,`, `(`, `-`
	            '#(?<=[\s=:,\(\-]|&\#32;)0+\.(\d+)#s',
	            // Minify string value
	            '#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][-\w]*?)\2(?=[\s\{\}\];,])#si',
	            '#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
	            // Minify HEX color code
	            '#(?<=[\s=:,\(]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
	            // Replace `(border|outline):none` with `(border|outline):0`
	            '#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
	            // Remove empty selector(s)
	            '#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s',
	            '#\x1A#'
	        ),
	        array(
	            '$1',
	            '$1$2$3$4$5$6$7',
	            '$1',
	            ':0',
	            '$1:0 0',
	            '.$1',
	            '$1$3',
	            '$1$2$4$5',
	            '$1$2$3',
	            '$1:0',
	            '$1$2',
	            ' '
	        ),
	    $input);
	}

	/**
     * Retrieves the image field.
     *  
     * @link https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
     */
    function blossomthemes_email_newsletter_companion_get_image_field( $id, $name, $image, $label ){
        $output = '';
        $output .= '<div class="widget-upload">';
        $output .= '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br/>';
        $output .= '<input id="' . esc_attr( $id ) . '" class="bten-upload" type="hidden" name="' . esc_attr( $name ) . '" value="' . esc_attr( $image ) . '" placeholder="' . __('No file chosen', 'blossomthemes-email-newsletter') . '" />' . "\n";
        if ( function_exists( 'wp_enqueue_media' ) ) {
            if ( $image == '' ) {
                $output .= '<input id="upload-' . esc_attr( $id ) . '" class="bten-upload-button button" type="button" value="' . __('Upload', 'blossomthemes-email-newsletter') . '" />' . "\n";
            } else {
                $output .= '<input id="upload-' . esc_attr( $id ) . '" class="bten-upload-button button" type="button" value="' . __('Change', 'blossomthemes-email-newsletter') . '" />' . "\n";
            }
        } else {
            $output .= '<p><i>' . __('Upgrade your version of WordPress for full media support.', 'blossomthemes-email-newsletter') . '</i></p>';
        }

        $output .= '<div class="bten-screenshot" id="' . esc_attr( $id ) . '-image">' . "\n";

        if ( $image != '' ) {
            $remove = '<a href="#" class="bten-remove-image">'.__('Remove Image','blossomthemes-email-newsletter').'</a>';
            $attachment_id = $image;
            $image_array = wp_get_attachment_image_src( $attachment_id, 'full');
            if ( $image_array[0] ) {
                $output .= '<img src="' . esc_url( $image_array[0] ) . '" alt="" />' . $remove;
            } else {
                // Standard generic output if it's not an image.
                $output .= '<small>' . __( 'Please upload valid image file.', 'blossomthemes-email-newsletter' ) . '</small>';
            }     
        }
        $output .= '</div></div>' . "\n";
        
        echo $output;
    }

     /**
     * Get an attachment ID given a URL.
     * 
     * @param string $url
     *
     * @return int Attachment ID on success, 0 on failure
     */
    function blossomthemes_email_newsletter_get_attachment_id( $url ) {
        $attachment_id = 0;
        $dir = wp_upload_dir();
        if ( false !== strpos( $url, $dir['baseurl'] . '/' ) ) { // Is URL in uploads directory?
            $file = basename( $url );
            $query_args = array(
                'post_type'   => 'attachment',
                'post_status' => 'inherit',
                'fields'      => 'ids',
                'meta_query'  => array(
                    array(
                        'value'   => $file,
                        'compare' => 'LIKE',
                        'key'     => '_wp_attachment_metadata',
                    ),
                )
            );
            $query = new WP_Query( $query_args );
            if ( $query->have_posts() ) {
                foreach ( $query->posts as $post_id ) {
                    $meta = wp_get_attachment_metadata( $post_id );
                    $original_file       = basename( $meta['file'] );
                    $cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );
                    if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
                        $attachment_id = $post_id;
                        break;
                    }
                }
            }
        }
        return $attachment_id;
    }

    function bten_get_mailing_list()
    {
		$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : '';
		if ( ! isset( $_POST['calling_action'] ) ) {
			echo json_encode( array( 'error' => __( 'Sorry, no platform found.', 'blossomthemes-email-newsletter' ) ) );
			exit;
		}
		
		if ( ! wp_verify_nonce( $nonce, sanitize_text_field( $_POST['calling_action'] ) ) ) {
			echo json_encode( array( 'error' => __( 'Sorry, your nonce did not verify.', 'blossomthemes-email-newsletter' ) ) );
			exit;
		}
		
    	if ($_POST['calling_action'] == 'bten_aweber_auth') 
    	{
    		$aw = new Blossomthemes_Email_Newsletter_AWeber;
        	echo json_encode($aw->bten_get_aw_auth($_POST['bten_aw_auth_code']));
	    }

	    elseif($_POST['calling_action'] == 'bten_aweber_remove_auth')
	    {
	    	$aw = new Blossomthemes_Email_Newsletter_AWeber;    	
	    	echo json_encode($aw->bten_get_aw_remove_auth());
	    }

	    elseif ($_POST['calling_action'] == 'bten_aweber_list') 
	    {
	    	$aw = new Blossomthemes_Email_Newsletter_AWeber;
	    	echo json_encode($aw->bten_get_aw_lists());
	    }

	    elseif ($_POST['calling_action'] == 'bten_mailchimp_list') 
	    {
	    	if(empty($_POST['bten_mc_api_key']))
	    	{
	    		$data[0] = array('name' => 'No Lists Found', 'message' => "Please enter a valid API Key", 'desc' => 'empty api key'); 
	    		echo json_encode($data);
	    	}
	    	else
	    	{
	    		$mc = new BlossomThemes_Email_Newsletter_Settings;
		    	$data = $mc->mailchimp_lists($_POST['bten_mc_api_key']);
		    	if(empty($data['lists']))
		    	{
		    		$data[0] = array('name' => 'No Lists Found', 'message' => "Please enter a valid API Key", 'desc' => 'invalid api key');
		    		echo json_encode($data);
		    	}
		    	else
		    	{
		    		echo json_encode($data['lists']);
		    	}
	    	}	    	
	    }

	    elseif ($_POST['calling_action'] == 'bten_mailerlite_list') 
	    {
	    	if(empty($_POST['bten_ml_api_key']))
	    	{
	    		$data[0] = array('name' => 'No Lists Found', 'message' => "Please enter a valid API Key", 'desc' => 'empty api key'); 
	    		echo json_encode($data);
	    	}
	    	else
	    	{
	    		$ml = new BlossomThemes_Email_Newsletter_Settings;
		    	$data = $ml->mailerlite_lists($_POST['bten_ml_api_key']);

		    	if( empty( $data ) ) {
		    		$data[0] = array('name' => 'No Lists Found', 'message' => "Please enter a valid API Key", 'desc' => 'empty api key'); 
	    			echo json_encode( $data );
		    	} else {
					wp_send_json_success( $data );
		    	}
	    	}

	    }

	    elseif ($_POST['calling_action'] == 'bten_convertkit_list') 
	    {
	    	if(empty($_POST['bten_ck_api_key']))
	    	{
	    		$data[0] = array('name' => 'No Lists Found', 'message' => "Please enter a valid API Key", 'desc' => 'empty api key'); 
	    		echo json_encode($data);
	    	}
	    	else
	    	{
	    		$ck = new BlossomThemes_Email_Newsletter_Settings;
		    	$data = $ck->convertkit_lists($_POST['bten_ck_api_key']);
		    	if(empty($data))
		    	{
		    		$data[0] = array('name' => 'No Lists Found', 'message' => "Please enter a valid API Key", 'desc' => 'invalid api key');
		    		echo json_encode($data);
		    	}
		    	else
		    	{
		    		echo json_encode($data);
		    	}
	    	}	    	
	    }

	elseif ($_POST['calling_action'] == 'bten_getresponse_list') 
	{
		if(empty($_POST['bten_gr_api_key']))
		{
			$data[0] = array('name' => 'No Lists Found', 'message' => "Please enter a valid API Key", 'desc' => 'empty api key'); 
			echo json_encode($data);
		}
		else
		{
			$gt = new Blossomthemes_Email_Newsletter_GetResponse();
			$data = $gt->getresponse_lists($_POST['bten_gr_api_key']);
			if(empty($data))
			{
				$data[0] = array('name' => 'No Lists Found', 'message' => "Please enter a valid API Key", 'desc' => 'invalid api key');
				echo json_encode($data);
			}
			else
			{
				echo json_encode($data);
			}
		}	
	}

	    elseif ($_POST['calling_action'] == 'bten_activecampaign_list') 
	    {
	    	if(empty($_POST['bten_ac_api_url']) || empty($_POST['bten_ac_api_key']))
	    	{
	    		$data[0] = array('name' => 'No Lists Found', 'message' => "Please enter a valid API Key or URL", 'desc' => 'empty api key or url'); 
	    		echo json_encode($data);
	    	}
	    	else
	    	{
	    		$ac = new BlossomThemes_Email_Newsletter_Settings;
		    	$data = $ac->activecampaign_lists( $_POST['bten_ac_api_key'], $_POST['bten_ac_api_url'] );
		    	echo json_encode($data);

	    	}	    	
	    } elseif ( $_POST['calling_action'] == 'bten_sendinblue_list' ) {
			$lists = array();

			$blossomthemes_email_newsletter_settings = get_option( 'blossomthemes_email_newsletter_settings', false );

			if ( ! $blossomthemes_email_newsletter_settings ) {
				return $lists;
			}

			$api_key = isset( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) ? esc_attr( $blossomthemes_email_newsletter_settings['sendinblue']['api-key'] ) : '';

			if ( empty( $api_key ) ) {
				$api_key = isset( $_POST['bten_sendin_api_key'] ) ? $_POST['bten_sendin_api_key'] : '';
			}

			if ( ! empty( $api_key ) ) {
				
				$mailin    = new Blossom_Sendinblue_API_Client();
				$lists     = array();
				$list_data = $mailin->getAllLists();

				if (!empty($list_data['lists'])) {
					foreach ( $list_data['lists'] as $value ) {
						if ( 'Temp - DOUBLE OPTIN' == $value['name'] ) {
							$tempList = $value['id'];
							update_option( 'bten_sib_temp_list', $tempList );
							continue;
						}
						$lists[] = array(
							'id'   => $value['id'],
							'name' => $value['name'],
						);
					}
				}
			}
			if ( count( $lists ) > 0 ) {
				set_transient( 'bten_sib_list_' . md5( $api_key ), $lists, 4 * HOUR_IN_SECONDS );
			}
			wp_send_json_success( $lists );
		}

	    else {
	        echo json_encode(array());
	    }

    	die();
	}

}
new Blossomthemes_Email_Newsletter_Functions;
