<?php

namespace Vimeotheque\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use stdClass;
use Vimeotheque\Helper;
use Vimeotheque\Post\Post_Type;

/**
 * Meta panels callbacks
 * @author CodeFlavors
 * @ignore
 */
class Posts_Import_Meta_Panels{
	/**
	 * Stores the Post_Type object
	 * @var Post_Type
	 */
	private $cpt;

	/**
	 * Constructor. Used by other classes to initialize meta panels
	 *
	 * @param Post_Type $object
	 */
	public function __construct( Post_Type $object ){
		$this->cpt = $object;
	}

	/**
	 * Manual import side panel output
	 */
	public function import_entries_meta(){
		// plugin options
		$_options = \Vimeotheque\Plugin::instance()->get_options();
		// embed options
		$player_opt = Helper::get_embed_options();
		// merge the two together
		$options = array_merge( $_options, $player_opt );

		/**
		 * Allow options override.
         * Allow additional options to be set up in the edit screen.
         *
         * @param array $options    The options array.
		 */
		$options = apply_filters( 'vimeotheque\admin\import_meta_panel\post_options', $options );
		?>

        <?php
            // If templates are enabled, disable option to import description since it is set to always import in post content.
            if( !$_options['enable_templates'] ):
        ?>

		<label for="import_description"><?php _e('Set description as', 'codeflavors-vimeo-video-post-lite')?>:</label>
		<?php 
			$args = [
				'options' => [
					'content' => __('content', 'codeflavors-vimeo-video-post-lite'),
					'excerpt' => __('excerpt', 'codeflavors-vimeo-video-post-lite'),
					'content_excerpt' => __('both', 'codeflavors-vimeo-video-post-lite'),
					'none' => __('none', 'codeflavors-vimeo-video-post-lite')
				],
				'name' => 'import_description',
				'selected' => $options['import_description']
			];
			Helper_Admin::select( $args );
		?><br />

        <?php endif;?>

		<label for="import_status"><?php _e('Import status', 'codeflavors-vimeo-video-post-lite')?>:</label>
		<?php 
			$args = [
				'options' => [
					'publish' => __('Published', 'codeflavors-vimeo-video-post-lite'),
					'draft' => __('Draft', 'codeflavors-vimeo-video-post-lite'),
					'pending' => __('Pending', 'codeflavors-vimeo-video-post-lite')
				],
				'name' => 'import_status',
				'selected' => $options['import_status']
			];
			Helper_Admin::select( $args );
		?><br />

		<?php
		    // If templates are enabled, disable option to import description since it is set to always import in post content.
		    if( !$_options['enable_templates'] ):
		?>

                <label for="import_title"><?php _e('Import titles', 'codeflavors-vimeo-video-post-lite')?> :</label>
                <input type="checkbox" value="1" id="import_title" name="import_title"<?php Helper_Admin::check( $options['import_title'] );?> /><br />

        <?php
            endif;
        ?>


        <?php
		/**
		 * Action that allows setting up additional options in WordPress admin panels.
         *
         * @param array $options The options displayed into the panel.
		 */
         do_action( 'vimeotheque\admin\import_meta_panel\post_options_fields', $options );
        ?>
			
		<div id="cvm-import-videos-submit-c">
		    <?php submit_button(
                    /**
                     * Filter import button text.
                     * @ignore
                     *
                     * @param string $text  The button text
                     */
		            apply_filters(
                        'vimeotheque\admin\import_meta_panel\button_text',
                        __('Import videos', 'codeflavors-vimeo-video-post-lite')
                    ),
                    'primary',
                    'cvm-import-button',
                    true
            );?>
		
		<span class="cvm-ajax-response"></span>
		</div>
		<input type="hidden" name="action_top" id="action_top" value="import" />  
		<?php 
	}
		
	/**
	 * Import categories meta box
	 */
	public function import_categories_meta(){
		
		// include file responsible for meta boxes
		include_once ABSPATH . 'wp-admin/includes/meta-boxes.php';
		
		$post = new stdClass();
		$post->ID = -1;

		$taxonomy = $this->get_category_taxonomy();
		
		post_categories_meta_box(
			$post,
			[
				'title' => __('Categories', 'codeflavors-vimeo-video-post-lite'),
				'args' => [
					'taxonomy' => $taxonomy
				]
			]
		);		
	}
		
	/**
	 * Import tags meta box
	 */
	public function import_tags_meta(){
		// include file responsible for meta boxes
		include_once ABSPATH . 'wp-admin/includes/meta-boxes.php';
		
		$post = new stdClass();
		$post->ID = -1;
		$post->post_type = $this->get_post_type();

		$taxonomy = $this->get_tag_taxonomy();

		$options = \Vimeotheque\Plugin::instance()->get_options();
		if( isset( $options['import_tags'] ) && $options['import_tags'] ){
			_e('Please note that any tags retrieved from Vimeo will also be imported and set as post tags.', 'codeflavors-vimeo-video-post-lite');
		}
		
		post_tags_meta_box(
			$post,
			[
				'title' => __('Tags', 'codeflavors-vimeo-video-post-lite'),
				'args' => [
					'taxonomy' => $taxonomy
				]
			]
		);
	}
		
	/**
	 * Adds the meta boxes into the current screen
	 */
	public function add_metaboxes(){
		$screen = get_current_screen();
		$page_hook = $screen->id;

		$category = get_taxonomy( $this->get_category_taxonomy() );
		$tag = get_taxonomy( $this->get_tag_taxonomy() );

		// meta boxes
		add_meta_box(
		    'cvm-import-feed-entries',
            __('Import', 'codeflavors-vimeo-video-post-lite'),
            [ $this, 'import_entries_meta' ],
            $page_hook,
            'side'
        );

		if( $category ){
			add_meta_box(
			    'tagsdiv-plugin-cat',
                $category->labels->name,
                [ $this, 'import_categories_meta' ],
                $page_hook,
                'side'
            );
		}

		if( $tag ){
			add_meta_box(
			    'tagsdiv-plugin-tag',
                $tag->labels->name,
                [ $this, 'import_tags_meta' ],
                $page_hook,
                'side'
            );
		}
    }

	/**
     * Get the post type
     *
	 * @return string
	 */
    private function get_post_type(){
	    /**
	     * Filter import meta panel post type
         * @ignore
         *
         * @param string $post_type The post type
	     */
	    return apply_filters(
	            'vimeotheque\admin\import_meta_panel\post_type',
                $this->cpt->get_post_type()
        );
    }

	/**
     * Get tag taxonomy
     *
	 * @return string
	 */
	private function get_tag_taxonomy(){
		/**
		 * Filter meta panel tag taxonomy
         * @ignore
         *
         * @param string $taxonomy  The taxonomy
		 */
	    return apply_filters(
		        'vimeotheque\admin\import_meta_panel\tag_taxonomy',
                $this->cpt->get_tag_tax()
        );
	}

	/**
     * Get category taxonomy
     *
	 * @return string
	 */
	private function get_category_taxonomy(){
		/**
		 * Filter meta panel category taxonomy
         *
         * @ignore
		 *
		 * @param string $taxonomy  The category taxonomy
		 */
		return apply_filters(
		        'vimeotheque\admin\import_meta_panel\category_taxonomy',
                $this->cpt->get_post_tax()
        );
	}
}