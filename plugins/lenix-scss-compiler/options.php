<?php
class Lenix_Scss_Compiler_Settings {

    private $options;

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    public function add_plugin_page(){
 
        add_options_page(
            'Lenix scss compiler', 
            'Lenix scss compiler', 
            'manage_options', 
            'lenix-scss-compiler', 
            array( $this, 'create_admin_page' )
        );
    }

    public function create_admin_page(){
        $this->options = get_option( 'lenix_scss_options' );
        ?>
        <div class="wrap">
            <h2><?php _e('SCSS Settings','lenix-scss'); ?></h2>   
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'lenix_scss_options_group' );   
                do_settings_sections( 'lenix_scss_options' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    public function page_init()
    {        
        register_setting(
            'lenix_scss_options_group',
            'lenix_scss_options',
            array( $this, 'sanitize' )
        );

        // Paths to Directories
        add_settings_section(
            'lenix_scss_section',
            __('SCSS Folders To Compile','lenix-scss'),
            array( $this, 'print_lenix_section_info' ),
            'lenix_scss_options'
        );  
		
        add_settings_field(
            'lenix_scss_disable_compiler',
            __('Disable Compiler','lenix-scss'),
            array( $this, 'input_checkbox_callback' ),
            'lenix_scss_options',
            'lenix_scss_section',
            array(
                'name' => 'lenix_scss_disable_compiler',
            )
        );

        add_settings_field(
            'lenix_scss_scss_dirs',
            __('Scss Folders','lenix-scss'),
            array( $this, 'input_text_callback' ),
            'lenix_scss_options',
            'lenix_scss_section',
            array(
                'name' => 'lenix_scss_dirs',
            )
        );      

         
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input ) {
        foreach( ['scss_dir', 'css_dir'] as $dir ){
            if( !empty( $input[$dir] ) ) {
                $input[$dir] = sanitize_text_field( $input[$dir] );

                // Add a trailing slash if not already present
                if(substr($input[$dir], -1) != '/'){
                    $input[$dir] .= '/';
                }
            }
        }
			
        return $input;
    }

    /** 
     * Print the Section text
     */
    public function print_lenix_section_info() {
		echo '
		<div dir="ltr">
			<p>This plugin is for DEVELOPERS</p>
			<h3>How It Works?</h3>
			<p>Choose a source folder for SCSS and a target folder for CSS.<br>
			Write the SCSS code in the file on the source folder, and it automatically creates a CSS file in the target folder.</p>
			<p>--pay attention!<br>
			If the file already exists in the destination folder - it will be overwritten by the SCSS file</p>
		</div>';
    }

    /** 
	 * Text Fields' Callback
     */
    public function input_text_callback( $args ) {
        printf(
            '<input type="hidden" id="%s" name="lenix_scss_options[%s]" value="%s" />',
            esc_attr( $args['name'] ), esc_attr( $args['name'] ), esc_attr( $this->options[$args['name']])
        );

		echo "<div>";
			echo "<table class='folders-output' dir='ltr'>";
			
				$op_str = $this->options = get_option( 'lenix_scss_options' )['lenix_scss_dirs'];
				parse_str($op_str, $op_arr);
			
				$class_hide = empty($op_arr['dirs']) ? 'hide' : '';
				echo "<tr class='$class_hide'><td></td><td>scss folder</td><td>css folder</td></tr>";
				
				if(!empty($op_arr['dirs'])){
					foreach($op_arr['dirs'] as $pos => $dirs){
						
						if( !$dirs['css'] && !$dirs['scss']){
							continue;
						}
						
						$scss_dir_missing = !is_dir(WP_CONTENT_DIR.'/'.$dirs["scss"].'/') || !$dirs["scss"];
						$css_dir_missing = !is_dir(WP_CONTENT_DIR.'/'.$dirs["css"].'/') || !$dirs["css"];				
						
						echo '<tr>';		
							
							echo $scss_dir_missing||$css_dir_missing ? '<td>Directory is missing</td>': '<td></td>';
							
							echo $scss_dir_missing ? '<td class="dir-missing">' : '<td>';
								echo "<input name='dirs[{$pos}][scss]' type='text' value='{$dirs["scss"]}'>";
							echo '</td>'; 
							echo $css_dir_missing ? '<td class="dir-missing">' : '<td>';
								echo "<input name='dirs[{$pos}][css]' type='text' value='{$dirs["css"]}'>";
							echo '</td>'; 
							echo "<td class='remove-row'><span>X</span></td>";
							
							
							
						echo "</tr>";
					}
				}
			echo "</table>";
			echo "<span class='add-folder lenix-scss-btn'>Add Compiler</span>";
		echo "</div>";
		echo "<hr><div class='recompile'>";
		echo "<span style='display: block;margin-bottom: 10px;' class='recompile-now'><h3>Do not compile?</h3>
You can force compression with a click on Recompile Now</span>";
			echo "<span class='recompile-now lenix-scss-btn'>Recompile Now</span>";
		echo "</div>";
		
		?>
		<script>
		function display_first_tr(){
			var container = jQuery(document).find('.folders-output');
			var tr = container.find('tr:first-child');
			var count_rows = container.find('tr').length;
			
			//alert(count_rows);
			if(count_rows > 1){
				tr.removeClass('hide');
			} else {
				tr.addClass('hide');
			}
			
		}
		jQuery(document).on('click','.add-folder',function(){
			
			var btn = jQuery(this);
			var output_container = jQuery('.folders-output');
			var count_rows = output_container.find('tr').length - 1;
			var placeholder = 'themes/your-theme/folder';
			
			output_container.append('<tr><td></td><td><input name="dirs['+count_rows+'][scss]" type="text" placeholder="'+placeholder+'"></td><td><input name="dirs['+count_rows+'][css]" placeholder="'+placeholder+'" type="text"></td><td class="remove-row"><span>X</span></td></tr>');
			
			display_first_tr();
			
		});
		
		jQuery(document).on('change keyup','.folders-output',function(){
			
			var input = jQuery('#lenix_scss_dirs');
			var output_container = jQuery(this);
			var global_input_val = jQuery(output_container).find('input').serialize();
			input.val(global_input_val);
				
		});
		
		jQuery(document).on('click','.remove-row',function(){
			jQuery(this).closest('tr').remove();
			jQuery('.folders-output').trigger('change');
			display_first_tr();
		});
		
		jQuery(document).on('click','.recompile-now',function(){
			var btn = jQuery(this);
			btn.html('Wait...');
			jQuery.get( "?lenix-recompile", function( data ) {
				btn.html('Done');
			});
		});
		
		</script>
		<style>
		table.folders-output td {
			background: #e6e6e6;
		}
		
		table.folders-output tr:first-child td,
		table.folders-output .remove-row {
			color: white;
			background: black;
			font-weight: bold;
		}
		
		table.folders-output .remove-row {
			cursor:pointer;
		}
		
		table.folders-output .dir-missing {
			background: #ca1111;
		}
		
		table.folders-output input {
			padding: 5px 10px;
			border: 0;
		}
		
		table.folders-output tr td:first-child {
			background: none;
		}
		
		.lenix-scss-btn{
			cursor: pointer;
			background: black;
			color: white;
			padding: 10px 10px;
			margin: 2px;
			border: none;
			border-radius: 0;
			display: inline-block;
			font-size: 16px;
		}
		
		table.folders-output tr.hide {
			display: none;
		}
		
		</style>
		<?php
		
    }

    /** 
     * Select Boxes' Callbacks
     */
    public function input_select_callback( $args ) {
        $this->options = get_option( 'lenix_scss_options' );  
        
        $html = sprintf( '<select id="%s" name="lenix_scss_options[%s]">', esc_attr( $args['name'] ), esc_attr( $args['name'] ) );  
            foreach( $args['type'] as $value => $title ) {
                $html .= '<option value="' . esc_attr( $value ) . '"' . selected( $this->options[esc_attr( $args['name'] )], esc_attr( $value ), false) . '>' . esc_attr( $title ) . '</option>';
            }
        $html .= '</select>';  
      
        echo $html;  
    }

    /** 
     * Checkboxes' Callbacks
     */
    public function input_checkbox_callback( $args ) {  
        $this->options = get_option( 'lenix_scss_options' );  
        
        $html = '<input type="checkbox" id="' . esc_attr( $args['name'] ) . '" name="lenix_scss_options[' . esc_attr( $args['name'] ) . ']" value="1"' . checked( 1, isset( $this->options[esc_attr( $args['name'] )] ) ? $this->options[esc_attr( $args['name'] )] : 0, false ) . '/>';   
        $html .= '<label for="' . esc_attr( $args['name'] ) . '"></label>';
      
        echo $html;  
    } 

}

if( is_admin() ) {
    $lenix_scss_settings = new Lenix_Scss_Compiler_Settings();	
	
	
	add_filter('lenix_force_recompile',function($bool){
		if(isset($_GET['lenix-recompile'])){
			$bool = true;
		}
		return $bool;
	});
	
}

add_filter('lenix_disable_recompile',function($bool){
	
	$disable_compiler = get_option( 'lenix_scss_options' );
	if( isset($disable_compiler['lenix_scss_disable_compiler']) && $disable_compiler['lenix_scss_disable_compiler']){
		$bool = true;
	}
	return $bool;
});


add_filter('plugin_action_links', 'create_settings_link_on_plugins_page', 10, 2);
function create_settings_link_on_plugins_page($links, $file) {
  static $this_plugin;

  if( !$this_plugin ) {
	$this_plugin = LENIX_SCSS_FILE;
  }

  if ($file == $this_plugin) {
		$settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=lenix-scss-compiler">Settings</a>';
		array_unshift($links, $settings_link);
	}

	return $links;
}