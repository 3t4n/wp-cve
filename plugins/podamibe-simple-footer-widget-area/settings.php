<?php
    defined( 'ABSPATH' ) or die( "No script kiddies please!" );
    global $pagenow;
    $widget_area = 8;
    $widths = array( 'contained', 'fullwidth');  
    $options = array( 'solid','dotted', 'dashed','none','hidden','double','groove','ridge','inset','outset','initial','inherit' );  
    $sfwa_widget_setting = get_option('sfwa_widget_setting');
    $sfwa_layout_setting = get_option('sfwa_layout_setting');
?>
    <div class="wrap sfwa-wrap">
        <h2><?php esc_html_e('Simple Footer Widget Area', SFWA_TEXT_DOMAIN); ?></h2>
        <?php
			if (isset($_GET['updated']) && ( 'true' == esc_attr( $_GET['updated'] ) )) echo '<div class="updated" ><p>'. __('Theme Settings updated.',SFWA_TEXT_DOMAIN) .'</p></div>';
			
			if ( isset ( $_GET['tab'] ) ) sfwarea_admin_tabs($_GET['tab']); else sfwarea_admin_tabs('introduction');
		?>
            <div id="poststuff">
                <?php
				if ( $pagenow == 'options-general.php' && $_GET['page'] == 'sfwarea-settings' ){ 
					if ( isset ( $_GET['tab'] ) ) $tab = $_GET['tab']; 
					else $tab = 'introduction'; 
					//echo '<table class="form-table">';
					switch ( $tab ){
                        case 'how' :
                        ?>
                        <h3><?php esc_html_e('', SFWA_TEXT_DOMAIN);?></h3>
                        <div class="postbox">
                            <h3 class="hndle"><span><?php esc_html_e('How to use it?', SFWA_TEXT_DOMAIN);?></span></h3>
                            <div class="inside">
                                <div class="inside_width">
                                    <p>
                                        <?php esc_html_e('Step 1:- Setting Tab',SFWA_TEXT_DOMAIN);?>
                                    </p>
                                    <ol>
                                        <li><?php esc_html_e('Go to setting tab. This section provides few options.', SFWA_TEXT_DOMAIN);?></li>
                                        <li><?php esc_html_e('Widget Area:- Its value defines the number of widget area that appears in Appearance > Widgets. By default its 0.', SFWA_TEXT_DOMAIN);?>
                                        </li>
                                        <li><?php esc_html_e('Creditibility Footer:- If ticked another widget area appears for creditibility section in footer.By default its unchecked.', SFWA_TEXT_DOMAIN);?>
                                        </li>
                                    </ol>
                                    <p>
                                        <?php esc_html_e('Step 2:- Layout Tab',SFWA_TEXT_DOMAIN);?>
                                    </p>
                                    <ol>
                                        <li><?php esc_html_e('Go to layout tab. This section provides options for frontend design.', SFWA_TEXT_DOMAIN);?></li>
                                        <li><?php esc_html_e('General Options:- Change options for overall footer.', SFWA_TEXT_DOMAIN);?>
                                            <ul>
                                                <pre><?php esc_html_e('Title:- Change color of widget title.', SFWA_TEXT_DOMAIN);?></pre>
                                                <pre><?php esc_html_e('Text Color:- Change color of paragraphs inside footer.', SFWA_TEXT_DOMAIN);?></pre>
                                                <pre><?php esc_html_e('Link Color:- Change color of text having link.', SFWA_TEXT_DOMAIN);?></pre>
                                                <pre><?php esc_html_e('Link Hover Color:- Change color of text having link on mouse hover.', SFWA_TEXT_DOMAIN);?></pre>
                                            </ul>
                                        </li>
                                        <li><?php esc_html_e('Footer Options:- This helps in custom design of footer part excluding Credential footer part.', SFWA_TEXT_DOMAIN);?>
                                            <ul>
                                                <pre><?php esc_html_e('Background Color:- Custom background color.', SFWA_TEXT_DOMAIN);?></pre>
                                                <pre><?php esc_html_e('Background image:- Custom background image overwrites background color.', SFWA_TEXT_DOMAIN);?></pre>
                                                <pre><?php esc_html_e('Design options:- Custom control over spacings using padding and margin. Value is taken in pixel.', SFWA_TEXT_DOMAIN);?></pre>
                                            </ul>
                                        </li>
                                        <li><?php esc_html_e('Credential Footer Options:- This helps in custom design of credential footer part.', SFWA_TEXT_DOMAIN);?>
                                            <ul>
                                                <pre><?php esc_html_e('Background Color:- Custom background color.', SFWA_TEXT_DOMAIN);?></pre>
                                                <pre><?php esc_html_e('Background image:- Custom background image overwrites background color.', SFWA_TEXT_DOMAIN);?></pre>
                                                <pre><?php esc_html_e('Design options:- Custom control over spacings using padding and margin. Value is taken in pixel.', SFWA_TEXT_DOMAIN);?></pre>
                                            </ul>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <?php
						break;
						case 'setting' : 
							?>
                        <h3><?php esc_html_e('', SFWA_TEXT_DOMAIN);?></h3>
                            <div class="inside">
                                <form method="post">
                                    <div class="postbox">
                                    <h3 class="hndle"><span><?php esc_html_e('Backend', SFWA_TEXT_DOMAIN);?></span></h3>
                                    <div class="inside">
                                    <?php 
                                                    if (function_exists('wp_nonce_field'))
                                                    wp_nonce_field( "sfwa_page" ); 
                                            ?>
                                        <table class="form-table">
                                            <tbody>
                                                <tr>
                                                    <th scope="row">
                                                        <label for="number_of_widgets_area"><?php esc_html_e('Widget Area', SFWA_TEXT_DOMAIN);?></label>
                                                    </th>
                                                    <td>
                                                        <select name="number_of_widgets_area" id="number_of_widgets_area">
                                                            <?php 
                                                                for($i=0;$i<$widget_area;$i++){
                                                                    $selected = '';
                                                                    if(isset($sfwa_widget_setting['number_of_widgets_area'])){
                                                                        if($sfwa_widget_setting['number_of_widgets_area']==$i){
                                                                            $selected = 'selected';
                                                                        }else{
                                                                            $selected = '';
                                                                        }
                                                                    }
                                                            ?>
                                                                <option value="<?php echo $i;?>" <?php echo $selected; ?>>
                                                                    <?php esc_html_e($i, SFWA_TEXT_DOMAIN);?>
                                                                </option>
                                                                <?php }?>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">
                                                        <label for="creditibility-footer">
                                                            <?php esc_html_e('Creditibility Footer', SFWA_TEXT_DOMAIN);?>
                                                            <p class="description"></p>
                                                        </label>
                                                    </th>
                                                    <td>
                                                        <input type="checkbox" name="creditibility-footer" <?php if($sfwa_widget_setting[ 'creditibility-footer']=='on' ){echo 'checked';} ?>></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                    <div class="postbox">
                                        <h3 class="hndle"><span><?php esc_html_e('Frontend', SFWA_TEXT_DOMAIN);?></span></h3>
                                        <div class="inside">
                                            <p class="description"><?php esc_html_e('Note: By default footer is hooked at the bottom of the page. Use below option to disable it.', SFWA_TEXT_DOMAIN);?></p>
                                            <p class="description"><?php esc_html_e('You can use "[sfwafooter]" shortcode to display footer inside your custom <div>.',SFWA_TEXT_DOMAIN);?></p>
                                            <table class="form-table">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">
                                                            <?php esc_html_e('Hide Footer', SFWA_TEXT_DOMAIN);?>
                                                            <p class="description"><?php esc_html_e('If checked footer is unhooked from bottom of the page', SFWA_TEXT_DOMAIN);?></p>
                                                        </th>
                                                        <td>
                                                            <input type="checkbox" name="footer-hook" <?php if($sfwa_widget_setting[ 'footer-hook']=='on' ){echo 'checked';} ?>>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                        <p class="submit">
                                            <input type="submit" class="button-primary" name="sfwa_submit" value="<?php esc_html_e('Update', SFWA_TEXT_DOMAIN); ?>">
                                        </p>
                                </form>
                            </div>
                        <?php
						break;
						case 'introduction' : 
							?>
                            <h3><?php esc_html_e('', SFWA_TEXT_DOMAIN);?></h3>
                            <div class="postbox">
                                <h3 class="hndle"><span><?php esc_html_e('Introduction', SFWA_TEXT_DOMAIN);?></span></h3>
                                <div class="inside">
                                    <div class="inside_width">
                                        <p class="elementor-message-actions">
                                            <a href="<?php echo esc_url( 'http://shop.podamibenepal.com/downloads/podamibe-simple-footer-widget-area/' ); ?>" class="button button-primary"><?php esc_html_e('Documentation', SFWA_TEXT_DOMAIN);?></a>
                                            <a href="<?php echo esc_url( 'http://shop.podamibenepal.com/downloads/podamibe-simple-footer-widget-area/' ); ?>" class="button button-primary"><?php esc_html_e('Details', SFWA_TEXT_DOMAIN);?></a>
                                            <a href="<?php echo esc_url( 'http://shop.podamibenepal.com/forums/forum/support/' ); ?>" class="button button-primary"><?php esc_html_e('Live Support', SFWA_TEXT_DOMAIN);?></a>
                                        </p><br /><br />
                                        <p>
                                            <?php esc_html_e('Simple Footer Widget Area is a free plugin which allows you to create footer areas from 1 - 7 depending upon the requirement of your theme. This is a widget dependent plugin, needs wordpress widgets for footer content, using which you are able to create footer section in your website.', SFWA_TEXT_DOMAIN);?>
                                        </p>
                                        <p>
                                            <b><?php esc_html_e('Its feature:', SFWA_TEXT_DOMAIN);?></b>
                                        </p>
                                        <ol>
                                            <li><?php esc_html_e('It provides you some set of pre-build widgets for easiness.', SFWA_TEXT_DOMAIN);?></li>
                                            <li><?php esc_html_e('Can be used with most of the themes.', SFWA_TEXT_DOMAIN);?></li>
                                            <li><?php esc_html_e('Multilanguage Translation Ready', SFWA_TEXT_DOMAIN);?></li>
                                            <li><?php esc_html_e('Footer custom layout design', SFWA_TEXT_DOMAIN);?></li>
                                            <li><?php esc_html_e('Footer column custom define from backend.', SFWA_TEXT_DOMAIN);?></li>
                                            <li><?php esc_html_e('Third party widget can be added.', SFWA_TEXT_DOMAIN);?></li>
                                            <li><?php esc_html_e('Easy plugin for theme developer.', SFWA_TEXT_DOMAIN);?></li>
                                        </ol>
                                    
                                        

                                    </div>
                                </div>
                            </div>
                            <div class="postbox">
                                <h3 class="hndle"><span><?php esc_html_e('Why this plugin might not work?', SFWA_TEXT_DOMAIN);?></span></h3>
                                <div class="inside">
                                    <div class="inside_width">
                                        <p>
                                            <?php esc_html_e('Every theme must have &lt;?php wp_footer(); ?&gt; somewhere in the footer.php file.',SFWA_TEXT_DOMAIN);?>
                                        </p>
                                        <p>
                                            <?php esc_html_e('Most themes have it, so if you try this and it doesn’t show, check the theme’s footer file first.',SFWA_TEXT_DOMAIN);?>
                                        </p>
                                        <p>
                                            <?php esc_html_e('For some themes, the footer hook is left blank, or uses a theme-specific hook such as',SFWA_TEXT_DOMAIN);?> <i>twentytfourteen_credits</i>, <i>twentyfifteen_credits</i>, <i>genesis_footer</i>, etc.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php
						break;
                        case 'layout' : 
                        ?>
                                <div>
                                    <h3><?php esc_html_e('Note: Leave empty to use the theme default styles.', SFWA_TEXT_DOMAIN);?></h3>
                                    <div class="inside">
                                        <form method="post">
                                            <?php 
                                                if (function_exists('wp_nonce_field'))
                                                wp_nonce_field( "sfwa_layout" );
                                            ?>
                                                <div class="postbox">
                                                    <h3 class="hndle"><span><?php esc_html_e('General', SFWA_TEXT_DOMAIN);?></span></h3>
                                                    <div class="inside">
                                                        <table class="form-table">
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">
                                                                        <label for="title-color">
                                                                            <?php esc_html_e('Title:', SFWA_TEXT_DOMAIN);?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <input type="text" class="color-field" placeholder="" value="<?php echo $sfwa_layout_setting['title-color']; ?>" name="title-color" />
                                                                    </td>
                                                                    <th scope="row">
                                                                        <label for="title-color">
                                                                            <?php esc_html_e('Text color:', SFWA_TEXT_DOMAIN);?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <input type="text" class="color-field" placeholder="" value="<?php echo $sfwa_layout_setting['text-color']; ?>" name="text-color" />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">
                                                                        <label for="anchor-color">
                                                                            <?php esc_html_e('Default Link Color:', SFWA_TEXT_DOMAIN);?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <input type="text" class="color-field" placeholder="" value="<?php echo $sfwa_layout_setting['anchor-color']; ?>" name="anchor-color" />
                                                                    </td>
                                                                    <th scope="row">
                                                                        <label for="hover-anchor-color">
                                                                            <?php esc_html_e('Default Link Hover Color:', SFWA_TEXT_DOMAIN);?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <input type="text" class="color-field" placeholder="" value="<?php echo $sfwa_layout_setting['hover-anchor-color']; ?>" name="hover-anchor-color" />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">
                                                                        <label for="full_width_footer">
                                                                            <?php esc_html_e('Width', SFWA_TEXT_DOMAIN);?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <select name="full_width_footer" class="sfwa-border-style">
                                                                            <?php foreach($widths as $width){ 
                                                                            ?>
                                                                                <option value="<?php echo $width; ?>" <?php echo $sfwa_layout_setting[ 'full_width_footer']==$width ? 'selected': ''; ?>>
                                                                                    <?php esc_html_e($width, SFWA_TEXT_DOMAIN); ?>
                                                                                </option>
                                                                                <?php
                                                                            } ?>
                                                                        </select>
                                                                    </td>
                                                                    
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="postbox">
                                                    <h3 class="hndle"><span><?php esc_html_e('Footer Options', SFWA_TEXT_DOMAIN);?></span></h3>
                                                    <div class="inside">
                                                        <table class="form-table">
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">
                                                                        <label for="footer-color">
                                                                            <?php esc_html_e('Background Color:', SFWA_TEXT_DOMAIN);?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <input type="text" class="color-field" placeholder="" value="<?php echo $sfwa_layout_setting['footer-color']; ?>" name="footer-color" />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">
                                                                        <label for="credibility-footer-color">
                                                                            <?php esc_html_e('Background Image', SFWA_TEXT_DOMAIN); ?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <div class="image_selector">
                                                                            <!-- Your image container, which can be manipulated with js -->
                                                                            
                                                                            <div class="custom-img-container">
                                                                            <?php if($sfwa_layout_setting['footer_background']){ ?>
                                                                                <img src="<?php echo $sfwa_layout_setting['footer_background']; ?>" alt="" style="max-width:100%;">
                                                                            <?php } ?>
                                                                            </div>
                                                                            
                                                                            <!-- A hidden input to set and post the chosen image id -->
                                                                            <input class="custom-img-id widefat" type="hidden" value="<?php echo $sfwa_layout_setting['footer_background']; ?>" name="footer_background">
                                                                            <!-- Your add & remove image links -->
                                                                            <p class="hide-if-no-js">
                                                                                <button class="upload-custom-img <?php if($sfwa_layout_setting['footer_background']){ echo 'hidden';} ?>">
                                                                                    <?php esc_html_e('Browse', SFWA_TEXT_DOMAIN); ?> </button>
                                                                                <button class="delete-custom-img <?php if(!($sfwa_layout_setting['footer_background'])){ echo 'hidden';} ?>">
                                                                                    <?php esc_html_e('Remove', SFWA_TEXT_DOMAIN); ?> </button>
                                                                            </p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">
                                                                        <label>
                                                                            <?php esc_html_e('Design Options:', SFWA_TEXT_DOMAIN);?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <div class="design_option">
                                                                            <div class="sfwa_margin_option">
                                                                                <div class="sfwa_margin_option_wrapper">
                                                                                    <span class="note"><?php esc_html_e('(in px)', SFWA_TEXT_DOMAIN);?></span>
                                                                                    <label>
                                                                                        <?php esc_html_e('margin', SFWA_TEXT_DOMAIN);?>
                                                                                    </label>
                                                                                    <input class="top" type="text" name="margin[footer-margin-top]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-margin-top']; ?>" />
                                                                                    <input class="right" type="text" name="margin[footer-margin-right]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-margin-right']; ?>" />
                                                                                    <input class="bottom" type="text" name="margin[footer-margin-bottom]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-margin-bottom']; ?>" />
                                                                                    <input class="left" type="text" name="margin[footer-margin-left]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-margin-left']; ?>" />
                                                                                </div>
                                                                                <div class="sfwa_border_option">
                                                                                    <div class="sfwa_border_option_wrapper">
                                                                                        <label>
                                                                                            <?php esc_html_e('border', SFWA_TEXT_DOMAIN);?>
                                                                                        </label>
                                                                                        <input class="top" type="text" name="border[footer-border-top]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-border-top']; ?>" />
                                                                                        <input class="right" type="text" name="border[footer-border-right]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-border-right']; ?>" />
                                                                                        <input class="bottom" type="text" name="border[footer-border-bottom]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-border-bottom']; ?>" />
                                                                                        <input class="left" type="text" name="border[footer-border-left]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-border-left']; ?>" />
                                                                                    </div>
                                                                                    <div class="sfwa_padding_option">
                                                                                        <div class="sfwa_padding_option_wrapper">
                                                                                            <label>
                                                                                                <?php esc_html_e('padding', SFWA_TEXT_DOMAIN);?>
                                                                                            </label>
                                                                                            <input class="top" type="text" name="padding[footer-padding-top]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-padding-top']; ?>" />
                                                                                            <input class="right" type="text" name="padding[footer-padding-right]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-padding-right']; ?>" />
                                                                                            <input class="bottom" type="text" name="padding[footer-padding-bottom]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-padding-bottom']; ?>" />
                                                                                            <input class="left" type="text" name="padding[footer-padding-left]" placeholder="-" value="<?php echo $sfwa_layout_setting['footer-padding-left']; ?>" />
                                                                                        </div>
                                                                                        <!--<div class="logo">
                                                                                            <img src="<?php echo SFWA_URL; ?>/assets/img/logo.png">
                                                                                        </div>-->
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="sfwa_design_option_style">
                                                                            <div class="design_option_style">
                                                                                <label>
                                                                                    <?php esc_html_e('Border Color', SFWA_TEXT_DOMAIN);?>
                                                                                </label>
                                                                                <br />
                                                                                <input type="text" class="color-field" placeholder="" value="<?php echo $sfwa_layout_setting['footer-border-color']; ?>" name="footer-border-color" />
                                                                            </div>
                                                                            <div class="design_option_style">
                                                                                <label>
                                                                                    <?php esc_html_e('Border Style', SFWA_TEXT_DOMAIN);?>
                                                                                </label>
                                                                                <br />
                                                                                <select name="footer_border_style" class="sfwa-border-style">
                                                                                    <?php foreach($options as $option){ 
                                                                                    ?>
                                                                                        <option value="<?php echo $option; ?>" <?php echo $sfwa_layout_setting[ 'footer_border_style']==$option ? 'selected': ''; ?>>
                                                                                            <?php esc_html_e($option, SFWA_TEXT_DOMAIN); ?>
                                                                                        </option>
                                                                                        <?php
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="design_option_style">
                                                                                <label>
                                                                                    <?php esc_html_e('Border Radius', SFWA_TEXT_DOMAIN); ?>
                                                                                </label>
                                                                                <br />
                                                                                <input type="number" placeholder="" value="<?php echo $sfwa_layout_setting['footer-border-radius']; ?>" name="footer-border-radius" min="1" />
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="postbox">
                                                    <h3 class="hndle"><span><?php esc_html_e('Credibility Footer Options', SFWA_TEXT_DOMAIN); ?></span></h3>
                                                    <div class="inside">
                                                        <table class="form-table">
                                                            <tbody>
                                                                <tr>
                                                                    <th scope="row">
                                                                        <label for="credibility-footer-color">
                                                                            <?php esc_html_e('Background Color', SFWA_TEXT_DOMAIN); ?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <input type="text" class="color-field" placeholder="" value="<?php echo $sfwa_layout_setting['credibility-footer-color']; ?>" name="credibility-footer-color" />
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">
                                                                        <label for="credibility-footer-color">
                                                                            <?php esc_html_e('Background Image', SFWA_TEXT_DOMAIN); ?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <div class="image_selector">
                                                                            <!-- Your image container, which can be manipulated with js -->
                                                                            
                                                                            <div class="custom-img-container">
                                                                            <?php if($sfwa_layout_setting['credit_background']){ ?>
                                                                                <img src="<?php echo $sfwa_layout_setting['credit_background']; ?>" alt="" style="max-width:100%;">
                                                                            <?php } ?>
                                                                            </div>
                                                                            
                                                                            <!-- A hidden input to set and post the chosen image id -->
                                                                            <input class="custom-img-id widefat" type="hidden" value="<?php echo $sfwa_layout_setting['credit_background']; ?>" name="credit_background">
                                                                            <!-- Your add & remove image links -->
                                                                            <p class="hide-if-no-js">
                                                                                <button class="upload-custom-img <?php if($sfwa_layout_setting['credit_background']){ echo 'hidden';} ?>">
                                                                                    <?php esc_html_e('Browse', SFWA_TEXT_DOMAIN); ?> </button>
                                                                                <button class="delete-custom-img <?php if(!($sfwa_layout_setting['credit_background'])){ echo 'hidden';} ?>">
                                                                                    <?php esc_html_e('Remove', SFWA_TEXT_DOMAIN); ?> </button>
                                                                            </p>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th scope="row">
                                                                        <label>
                                                                            <?php esc_html_e('Design Options:', SFWA_TEXT_DOMAIN); ?>
                                                                        </label>
                                                                    </th>
                                                                    <td>
                                                                        <div class="design_option">
                                                                            <div class="sfwa_margin_option">
                                                                                <div class="sfwa_margin_option_wrapper">
                                                                                    <span class="note"><?php esc_html_e('(in px)', SFWA_TEXT_DOMAIN); ?></span>
                                                                                    <label>
                                                                                        <?php esc_html_e('margin', SFWA_TEXT_DOMAIN); ?>
                                                                                    </label>
                                                                                    <input class="top" type="text" name="margin[credibility-margin-top]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-margin-top']; ?>" />
                                                                                    <input class="right" type="text" name="margin[credibility-margin-right]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-margin-right']; ?>" />
                                                                                    <input class="bottom" type="text" name="margin[credibility-margin-bottom]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-margin-bottom']; ?>" />
                                                                                    <input class="left" type="text" name="margin[credibility-margin-left]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-margin-left']; ?>" />
                                                                                </div>
                                                                                <div class="sfwa_border_option">
                                                                                    <div class="sfwa_border_option_wrapper">
                                                                                        <label>
                                                                                            <?php esc_html_e('border', SFWA_TEXT_DOMAIN); ?>
                                                                                        </label>
                                                                                        <input class="top" type="text" name="border[credibility-border-top]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-border-top']; ?>" />
                                                                                        <input class="right" type="text" name="border[credibility-border-right]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-border-right']; ?>" />
                                                                                        <input class="bottom" type="text" name="border[credibility-border-bottom]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-border-bottom']; ?>" />
                                                                                        <input class="left" type="text" name="border[credibility-border-left]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-border-left']; ?>" />
                                                                                    </div>
                                                                                    <div class="sfwa_padding_option">
                                                                                        <div class="sfwa_padding_option_wrapper">
                                                                                            <label>
                                                                                                <?php esc_html_e('padding', SFWA_TEXT_DOMAIN); ?>
                                                                                            </label>
                                                                                            <input class="top" type="text" name="padding[credibility-padding-top]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-padding-top']; ?>" />
                                                                                            <input class="right" type="text" name="padding[credibility-padding-right]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-padding-right']; ?>" />
                                                                                            <input class="bottom" type="text" name="padding[credibility-padding-bottom]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-padding-bottom']; ?>" />
                                                                                            <input class="left" type="text" name="padding[credibility-padding-left]" placeholder="-" value="<?php echo $sfwa_layout_setting['credibility-padding-left']; ?>" />
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="sfwa_design_option_style">
                                                                            <div class="design_option_style">
                                                                                <label>
                                                                                    <?php esc_html_e('Border Color', SFWA_TEXT_DOMAIN); ?>
                                                                                </label>
                                                                                <br />
                                                                                <input type="text" class="color-field" placeholder="" value="<?php echo $sfwa_layout_setting['credibility-border-color']; ?>" name="credibility-border-color" />
                                                                            </div>
                                                                            <div class="design_option_style">
                                                                                <label>
                                                                                    <?php esc_html_e('Border Style', SFWA_TEXT_DOMAIN); ?>
                                                                                </label>
                                                                                <br />
                                                                                <select name="credibility_border_style" class="sfwa_border-style">
                                                                                    <?php foreach($options as $option){ ?>
                                                                                        <option value="<?php echo $option; ?>" <?php echo $sfwa_layout_setting[ 'credibility_border_style']==$option ? 'selected': ''; ?>>
                                                                                            <?php esc_html_e($option, SFWA_TEXT_DOMAIN); ?>
                                                                                        </option>
                                                                                        <?php
                                                                                    } ?>
                                                                                </select>
                                                                            </div>
                                                                            <div class="design_option_style">
                                                                                <label>
                                                                                    <?php esc_html_e('Border Radius', SFWA_TEXT_DOMAIN); ?>
                                                                                </label>
                                                                                <br />
                                                                                <input type="number" placeholder="" value="<?php echo $sfwa_layout_setting['credibility-border-radius']; ?>" name="credibility-border-radius" min="1" />
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <p class="submit">
                                                    <input type="submit" class="button-primary" name="sfwa_layout_submit" value="<?php esc_html_e('Update', SFWA_TEXT_DOMAIN); ?>">
                                                </p>
                                        </form>
                                    </div>
                                </div>
                                <?php
						break;
					}
					//echo '</table>';
				}
				?>
            </div>
    </div>
    <?php

function sfwarea_admin_tabs( $current = 'introduction' ) { 
    $tabs = array( 'introduction' => 'About','how' => 'How to use?', 'setting' => 'Settings','layout' => 'Layout' ); 
    $links = array();
    echo '<div id="icon-themes" class="icon32"><br></div>';
    echo '<h2 class="nav-tab-wrapper">';
    foreach( $tabs as $tab => $name ){
        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
        echo "<a class='nav-tab$class' href='?page=sfwarea-settings&tab=$tab'>";
        esc_html_e($name, SFWA_TEXT_DOMAIN);
        echo "</a>";
    }
    echo '</h2>';
}

?>