<?php 
namespace Adminz\Admin;

class ADMINZ_Me extends Adminz {
	public $options_group = "adminz_me";
	public $title = "Help & Support";
	static $slug = 'adminz_me';
	function __construct() {
		if(!is_admin()) return;
		
		add_filter( 'adminz_setting_tab', [$this,'register_tab']);	
		add_action( 'adminz_tabs_html',[$this,'tab_html']);	
	}
	function add_tab_info($html){
		ob_start();
		?>
		<div class="author_note">
			<?php if(get_locale() == 'vi'){ ?>
				Cần xây dựng thêm chức năng? | Liên hệ tác giả: <a target="_blank" href="https://m.me/timquen2014">Mesenger</a> <a target="_blank" href="https://zalo.me/0972054206">Zalo</a> <a target="_blank" href="tel:0972054206">0972-054-206</a>
			<?php }else{ ?>
				Need more functions? | Contact author: <a target="_blank" href="https://m.me/timquen2014">Mesenger</a>
			<?php } ?>
		</div>
		<style type="text/css">
			.adminz_tab_content {
				position: relative;
			}
			.author_note{
				position: absolute;
				top: 0;
				right:  0;
			}
		</style>
		<?php
		$html .= ob_get_clean();
		return $html;
	}
	function tab_html(){
		if(!isset($_GET['tab']) or $_GET['tab'] !== self::$slug) return;
		?>
		<form method="post" action="options.php">
			<?php 
			settings_fields($this->options_group);
	        do_settings_sections($this->options_group);
			?>

			<table class="form-table">
				<tr valign="top">
	                <th scope="row">Translate</th>
	                <td>
	                	Compatibility with Poly Lang. All text, setting in plugin you can translate in <strong>Dashboard-> languages-> String translate</strong> and seach adminz

	                </td>
	            </tr>
	            <tr valign="top">
	                <th scope="row">About Author</th>
	                <td>
	                	<a target="_blank" class="adminz_me_link" href="https://quyle91.github.io/administratorz/">Documents</a>
	                	<a target="_blank" class="adminz_me_link small" href="https://wordpress.org/support/plugin/administrator-z/">Report bugs</a>	                	
	                	<a target="_blank" class="adminz_me_link small" href="https://quyle91.github.io">About me</a>
	                </td>
	            </tr>	            	            
	        </table>			
		</form>
		<?php
	}
	function register_tab($tabs) {
 		if(!$this->title) return;
 		$this->title = $this->get_icon_html('headset').$this->title;
        $tabs[self::$slug] = array(
            'title' => $this->title,
            'slug' => self::$slug,
        );
        return $tabs;
    }
}