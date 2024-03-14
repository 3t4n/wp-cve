<?php
namespace Adminz\Admin;
use Adminz\Admin\Adminz as Adminz;
/**
 *
 */
class ADMINZ_Icons extends Adminz {
	public $options_group = "adminz_icons";
    public $title = 'Icons & Images';
    static $slug = 'adminz_icons';
    function __construct() {
    	
        add_filter('adminz_setting_tab', [$this, 'register_tab']);  
        add_action( 'adminz_tabs_html',[$this,'tab_html']);

        add_filter( 'nav_menu_item_title',[$this,'add_icon_to_nav'],1,4);
    }    
    function register_tab($tabs) {
        if(!$this->title) return;
        $this->title = $this->get_icon_html('icons').$this->title;
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
        );
        return $tabs;
    }
    function add_icon_to_nav($title, $item, $args, $depth){

        if(is_admin()) return;
        if($item->post_excerpt){
            ob_start();     
            $attr['style'] = ["width"=> "1em","margin-right"=> "0.5em","vertical-align"=>"middle"];
            echo ($this->get_icon_html($item->post_excerpt,$attr));
            $title =ob_get_clean().$title;
        }       
        return $title;
    }
    function tab_html() {
        if(!isset($_GET['tab']) or $_GET['tab'] !== self::$slug) return;
        global $adminz;
        ?>        
        <h3>Supported Icons</h3>
        <div class="notice">
            <p>Shortcode: <code>[adminz_icon icon="clock" max_width="16px" class="footer_icon"]</code></p>
        </div>
        <div>           
            <div style="display: flex; flex-wrap: wrap; justify-content: flex-start;">
                <?php 
                foreach ($this->get_support_icons() as $key=> $icon) {
                    ?>
                    <div class="contactgroupicon" 
                style="width: calc( 10% - 2px)  ; margin: 1px; border-radius: 5px;  box-sizing: border-box; border: 1px solid #ccc; cursor: pointer; ">
                        <div style="margin: 5px; display: flex; align-items: center;">
                            <img class="lazyloading" alt="<?php echo esc_attr($icon); ?>" width="25px"  src="<?php echo plugin_dir_url(ADMINZ_BASENAME). 'assets/icons/'.$icon ; ?>" style="margin-right: 10px; border: 1px solid black;"/> 
                            <small><?php echo substr($icon, 0,strlen ($icon)-4); ?></small> 
                            <small class="tooltip">Copy to clipboard</small>
                        </div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <style type="text/css">
            <?php 
                global $_wp_admin_css_colors;
                $admin_color = get_user_option( 'admin_color' );
                $colors      = $_wp_admin_css_colors[$admin_color]->colors;
            ?>
            @media (max-width: 768px){
                .contactgroupicon {
                    width: calc( 33% - 2px ) !important;                            
                }
            }
            .contactgroupicon{
                
                position: relative;
            }
            .contactgroupicon .tooltip::before{
                content: "";
                width: 8px;
                height: 8px;
                background: inherit;
                display: inline-block;
                bottom: -3px;
                left: 50%;
                position: absolute;
                transform: translateX(-50%) rotate(45deg);
            }
            .contactgroupicon .tooltip{
                position: absolute;
                bottom: calc( 100% + 5px ) ;
                left: 0;
                background-color: <?php echo esc_attr($colors[1]); ?> ;
                padding: 5px;
                color: white;
                border-radius: 5px;
                display: none;
            }
            .contactgroupicon.copied .tooltip,
            .contactgroupicon:hover .tooltip{
                display: block;
            }
            .contactgroupicon.copied,
            .contactgroupicon:hover{                        
                    fill: white;
                    color: white;
                    background-color: <?php echo esc_attr($colors[2]);?>;
            }
        </style>
        <script type="text/javascript">
            jQuery( document ).ready(function() {
                jQuery(document).on('click', '.contactgroupicon', function(e){      
                    e.preventDefault();
                    var icon_text = jQuery(this).find('small').html();
                    var textArea = jQuery('<textarea>'+icon_text+'</textarea>');
                    jQuery(this).append(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        var successful = document.execCommand('copy');
                        var msg = successful ? 'successful' : 'unsuccessful';
                        console.log('Copying text command was ' + msg);
                        jQuery('.contactgroupicon').removeClass('copied');
                        jQuery(this).addClass('copied');
                        jQuery('.contactgroupicon').find('.tooltip').html('Copy to clipboard');
                        jQuery(this).find('.tooltip').html('Copied: '+icon_text);
                    } catch (err) {
                        alert('Oops, unable to copy');
                    }
                    jQuery(this).find('textarea').remove();
                })
            });
        </script>
        <?php
    }
    static function get_support_icon_images(){
        $files = array_map('basename',glob(ADMINZ_DIR.'/assets/icons/images/*'));      
        return $files;
    }

}