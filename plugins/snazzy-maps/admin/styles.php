<?php
namespace SnazzyMaps;
defined( 'ABSPATH' ) OR exit;
include_once(plugin_dir_path(__FILE__) . _DS . 'helpers.php');

class SnazzyMaps_Styles {
    //Removed closures for PHP 5.0.x support
    public static function _getStyleIndex(&$styles, $id){
        foreach((array)$styles as $index => $style){
            if($style['id'] == $id){
                return $index;
            }
        }
        return null;
    }
    public static function _getStyle(&$styles, $id){
        $index = \SnazzyMaps\SnazzyMaps_Styles::_getStyleIndex($styles, $id);
        return !is_null($index) ? $styles[$index] : null;
    }

    public static function _styleAction(&$style, $action){
        return \SnazzyMaps\SnazzyMaps_Helpers::esc_rel_url("?page=snazzy_maps&tab=0&action=$action&style=" . $style['id'] . "&_wpnonce=" . wp_create_nonce($action . "_" . $style['id']));
    }

    public static function admin_styles_head($tab){   
        
        $styles = get_option('SnazzyMapStyles', null);
        if($styles == null){
            $styles = array();
        }        
        
        
        //When a new style is selected we have to go through some checks
        if(isset($_POST['new_style']) && check_admin_referer('snazzy_maps_save_style')){
			$json = new \SnazzyMaps\SnazzyMaps_Services_JSON();
            $newStyle = \SnazzyMaps\SnazzyMaps_Main::object_to_array($json->decode(urldecode($_POST['new_style'])));
            if(!\SnazzyMaps\SnazzyMaps_Styles::_getStyle($styles, $newStyle['id'])){
                $styles[] = $newStyle;
                update_option('SnazzyMapStyles', $styles);
            }
            
			//Redirect to the next page
			if(!headers_sent()){
                wp_safe_redirect(\SnazzyMaps\SnazzyMaps_Helpers::esc_rel_url("?page=snazzy_maps&tab=0"));
				exit();
			}   
        }
    }

    public static function admin_styles_tab($tab){
        
        $styles = get_option('SnazzyMapStyles', null);
        if($styles == null){
            $styles = array();
        }
                
                
        //Delete the specified style from the array
        if(isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'delete_style' && wp_verify_nonce($_GET['_wpnonce'], 'delete_style_' . sanitize_text_field($_GET['style']))){
            $index = \SnazzyMaps\SnazzyMaps_Styles::_getStyleIndex($styles, sanitize_text_field($_GET['style']));
            $defaultStyle = get_option('SnazzyMapDefaultStyle', null);  
            if(!is_null($index)){                
                $oldStyle = $styles[$index];
                array_splice($styles, $index, 1);    
                update_option('SnazzyMapStyles', $styles);     
                
                //Delete the default style when it is removed from this list
                if(!is_null($defaultStyle) && $defaultStyle['id'] == $oldStyle['id']){
                    delete_option('SnazzyMapDefaultStyle');
                }
            }
        }
        
        //Enable the specified style
        if(isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'enable_style' && wp_verify_nonce($_GET['_wpnonce'], 'enable_style_' . sanitize_text_field($_GET['style']))){
            $index = \SnazzyMaps\SnazzyMaps_Styles::_getStyleIndex($styles, sanitize_text_field($_GET['style']));
            if(!is_null($index)){
                update_option('SnazzyMapDefaultStyle', $styles[$index]);
            }        
        }
        
        //Disable the specified style        
        if(isset($_GET['action']) && sanitize_text_field($_GET['action']) == 'disable_style' && wp_verify_nonce($_GET['_wpnonce'], 'disable_style_' . sanitize_text_field($_GET['style']))){
            $index = \SnazzyMaps\SnazzyMaps_Styles::_getStyleIndex($styles, sanitize_text_field($_GET['style']));
            $defaultStyle = get_option('SnazzyMapDefaultStyle', null);    
            if(!is_null($index) && !is_null($defaultStyle) 
                && $styles[$index]['id'] == $defaultStyle['id']){
                delete_option('SnazzyMapDefaultStyle');
            }        
        }
        
        
        $defaultStyle = get_option('SnazzyMapDefaultStyle', null);
?>
            
        <?php if (count($styles) > 0) { ?>
            <div class="results row">
                <?php foreach((array)$styles as $index => $style){ 
                    $isEnabled = !is_null($defaultStyle) && $defaultStyle['id'] == $style['id'];
                ?>        
                    <div class="style col-sm-6 col-md-4 <?php echo esc_attr($isEnabled ? 'enabled' : '');?>">
                        <div class="sm-style">
                            <div class="sm-map">
                                <img src="<?php echo esc_url($style['imageUrl']); ?>"
                                     alt="<?php echo esc_attr($style['name']); ?>"/>
                                <?php
                                if($isEnabled) {
                                ?>    
                                    <span class="overlay-icon">
                                        <span class="icon-checkmark"></span>
                                    </span>
                                <?php 
                                } ?>
                            </div>
                            <div class="sm-content info">
                                <h3>
                                    <a href="<?php echo esc_url($style['url']); ?>" class="title" target="_blank">
                                        <?php echo esc_html($style['name']); ?>
                                    </a>
                                </h3>
                                <div class="author">                            
                                    by <?php echo esc_html($style['createdBy']['name']);?></span>
                                </div>
                                <div class="stats">
                                    <div class="views">
                                        <span class="icon-eye"></span>
                                        <?php echo esc_html($style['views']); ?> views
                                    </div>
                                    <div class="favorites">
                                        <span class="icon-star"></span>
                                        <?php echo esc_html($style['favorites']); ?> favorites
                                    </div>
                                </div>
                                <?php
                                if($isEnabled){
                                ?>                    
                                    <a href="<?php echo \SnazzyMaps\SnazzyMaps_Styles::_styleAction($style, 'disable_style'); ?>" 
                                        class="button button-secondary button-large">Disable</a>
                                <?php
                                }
                                else{ 
                                ?>                    
                                    <a href="<?php echo \SnazzyMaps\SnazzyMaps_Styles::_styleAction($style, 'enable_style'); ?>" 
                                        class="button button-primary button-large">Enable</a>
                                <?php 
                                } ?>
                                <a href="<?php echo \SnazzyMaps\SnazzyMaps_Styles::_styleAction($style, 'delete_style'); ?>" 
                                    class="delete button button-error button-large">Remove</a>
                            </div>
                        </div>
                    </div>     
                <?php } ?>
            </div>
        <?php }else{ ?>            
            <div class="nothing">
                <p>Looks like you haven't picked any styles yet.</p>
                <p><a href="?page=snazzy_maps&tab=1">Explore</a> and choose some styles for your site!</p>
            </div>
        <?php } ?>

<?php 
	} 
}
?>