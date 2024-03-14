<?php 
// Access WordPress 
$absolute_path = __FILE__;
$path_to_file = explode( 'wp-content', $absolute_path );
$path_to_wp = $path_to_file[0];

require_once( $path_to_wp . '/wp-load.php' );


//Shortcodes Definitions
require_once( 'define.php' );

//Shortcodes Definitions
require_once( 'generate.shortcode.php' );



//Shortcode html
$html_options = null;

$shortcode_html = '

<div id="shortcode-generator">

    <div class="shortcode-content">     
        <div class="label"><strong>FAQ\'s</strong></div>            
        <div class="content"><select id="jw-shortcodes" data-placeholder="' . __("Choose a shortcode", 'jeweltheme') .'">
            <option value="faq"> All FAQ\'s </option>';

            foreach( $jw_faq_shortcode as $shortcode => $options ){

                $shortcode_html .= '<option value="'.$shortcode.'">'.$options['title'].'</option>';
                $html_options .= '<div class="shortcode-options" id="options-'.$shortcode.'" data-name="'.$shortcode.'" data-type="'.$options['type'].'">';
                
                if( !empty($options['attr']) ){
                   foreach( $options['attr'] as $name => $attr_option ){
                    $html_options .= jltmf_option_element( $name, $attr_option, $options['type'], $shortcode );
                }
            }

            $html_options .= '</div>'; 
            
        } 

        $shortcode_html .= '</select></div> <div class="hr"></div>'; ?><!DOCTYPE html>
        <html>
        <head>
            <title></title>

            <!--style-->
            <link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ) . 'css/style.css'; ?>" />

            <!--scripts-->
            <script src="<?php echo plugin_dir_url( __FILE__ ) . 'js/popup.js'; ?>"></script>


        </head>

        <body>  

            <?php echo $shortcode_html . $html_options;  ?>
            
            <code class="shortcode_storage">
                <span id="shortcode-storage-o" style=""></span>
                <span id="shortcode-storage-d"></span>
                <span id="shortcode-storage-c" style=""></span>
            </code>
            
            <a class="btn" id="add-shortcode"><?php echo __( 'Add FAQ', 'jeweltheme' ); ?></a>

        </div>

    </div>
</body>
</html>