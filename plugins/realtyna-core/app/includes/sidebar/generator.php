<?php
// Exit if accessed directly.
if(!defined('ABSPATH')) exit;

if(!class_exists('RTCORE_Sidebar_Generator')):

/**
 * RTCORE Sidebar Generator Class.
 *
 * @class RTCORE_Sidebar_Generator
 * @version	1.0.0
 */
class RTCORE_Sidebar_Generator extends RTCORE_Base
{
    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        add_action('admin_menu', array($this, 'register_menus'));
        add_action('init', array($this, 'register_taxonomy'));
        add_action('init', array($this, 'load_sidebars'));
        add_action('admin_head', array($this, 'menu_highlight'));

        add_action('admin_head', array($this, 'admin_head'));
        add_action('admin_footer', array($this, 'admin_footer'));

        add_filter('manage_edit-realtyna-core-sidebars_columns', array($this, 'manage_columns'));
    }
    
    public function register_menus()
    {
		add_submenu_page(
			'themes.php',
			__('Sidebars', 'realtyna-core'),
			__('Sidebars', 'realtyna-core'),
			'edit_theme_options',
			'edit-tags.php?taxonomy=realtyna-core-sidebars'
		);
	}
    
    public function menu_highlight()
    {
		global $parent_file, $submenu_file;
		
		if('edit-tags.php?taxonomy=realtyna-core-sidebars' === $submenu_file)
        {
			$parent_file = 'themes.php';
		}
	}
    
    public function admin_head()
    {
		if('edit-realtyna-core-sidebars' !== get_current_screen()->id) return;
		?>
        <style type="text/css">#addtag div.form-field.term-slug-wrap, #edittag tr.form-field.term-slug-wrap{display: none;}</style>
        <?php
	}

	public function admin_footer()
    {
		if('edit-realtyna-core-sidebars' !== get_current_screen()->id) return;
		?>
        <script type="text/javascript">
        jQuery(document).ready(function($)
        {
            var $tag = $('#addtag, #edittag');
            $tag.find('tr.form-field.term-name-wrap p, div.form-field.term-name-wrap > p').text('<?php echo esc_js(__('Widget Area/Sidebar Name', 'realtyna-core')); ?>');
            $tag.find('tr.form-field.term-description-wrap p, div.form-field.term-description-wrap > p').text('<?php echo esc_js(__('Optional description for Widget Area/Sidebar', 'realtyna-core')); ?>');
        });
        </script>
        <?php
	}
    
    public function manage_columns($columns)
    {
		unset($columns['slug']);
		unset($columns['posts']);
        
		return $columns;
	}
    
    public function register_taxonomy()
    {
        register_taxonomy(
            'realtyna-core-sidebars',
            array(),
            array(
                'hierarchical'=>false,
                'labels'=>array(
                    'name'=>__('Sidebars', 'realtyna-core'),
                    'singular_name'=>__('Sidebar', 'realtyna-core'),
                    'menu_name'=>_x('Sidebars', 'Admin menu name', 'realtyna-core'),
                    'search_items'=>__('Search Sidebars', 'realtyna-core'),
                    'all_items'=>__('All Sidebars', 'realtyna-core'),
                    'parent_item'=>__('Parent Sidebar', 'realtyna-core'),
                    'parent_item_colon'=>__('Parent Sidebar', 'realtyna-core'),
                    'edit_item'=>__('Edit Sidebar', 'realtyna-core'),
                    'update_item'=>__('Update Sidebar', 'realtyna-core'),
                    'add_new_item'=>__('Add New Sidebar', 'realtyna-core'),
                    'new_item_name'=>__('New Sidebar Name', 'realtyna-core'),
                ),
                'public'=>false,
                'show_in_nav_menus'=>false,
                'show_ui'=>true,
                'capabilities'=>array('edit_theme_options'),
                'query_var'=>false,
                'rewrite'=>false,
            )
        );
    }
    
    public function load_sidebars()
    {
        $sidebars = get_terms('realtyna-core-sidebars', array(
            'hide_empty'=>false,
        ));
        
        // There is no sidebar
        if(!count($sidebars)) return false;
        
        foreach($sidebars as $sidebar)
        {
			register_sidebar(array
            (
                'id'=>'realtyna-core-sidebar-'.sanitize_title($sidebar->term_id),
                'name'=>$sidebar->name,
                'description'=>$sidebar->description,
                'before_widget'=>'<div id="%1$s" class="widget-container %2$s">',
                'after_widget'=>'</div>',
                'before_title'=>'<h3 class="widget-title">',
                'after_title'=>'</h3>',
            ));
		}

		return true;
    }
}

endif;