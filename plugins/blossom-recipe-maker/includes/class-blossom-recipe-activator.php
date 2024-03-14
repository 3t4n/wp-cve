<?php

/**
 * Fired during plugin activation
 *
 * @link  test.com
 * @since 1.0.0
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/includes
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker_Activator {


	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {

		/**
		*   Assigning templates to taxonomy pages
		*/
		$template_pages = array(
			'recipe-category'       => array(
				'title'    => 'Recipe Category',
				'template' => 'templates/template-recipe-category.php',
			),
			'recipe-cuisine'        => array(
				'title'    => 'Recipe Cuisine',
				'template' => 'templates/template-recipe-cuisine.php',
			),
			'recipe-cooking-method' => array(
				'title'    => 'Recipe Cooking Method',
				'template' => 'templates/template-recipe-cooking-method.php',
			),
		);
		foreach ( $template_pages as $key => $value ) {
			$existing_page = get_page_by_title( $value['title'] );

			if ( ! empty( $existing_page ) && 'page' === $existing_page->post_type && ( $existing_page->post_status == 'publish' ) ) {
				$val = get_post_meta( $existing_page->ID, '_wp_page_template', true );
				if ( $val == $value['template'] ) {
					continue;
				}
			} else {
				$new_page = array(
					'post_title'   => $value['title'],
					'post_content' => '',
					'post_status'  => 'publish',
					'post_type'    => 'page',
				);
				$postID   = wp_insert_post( $new_page );
				update_post_meta( $postID, '_wp_page_template', $value['template'] );
			}
		}

		/**
		*   Assigning Recipe Search Results Template
		*/

		$options = get_option( 'br_recipe_settings', array() );

		$title        = 'Recipe Search Results';
		$shortcode    = 'BLOSSOM_RECIPE_MAKER_SEARCH_RESULTS';
		$existingPage = get_page_by_title( $title );

		if ( isset( $options['pages']['recipe_search'] ) && $options['pages']['recipe_search'] != '' ) {
			$pageID = $options['pages']['recipe_search'];
			$page   = get_post( $pageID );

			if ( $page ) {
				$title        = $page->post_title;
				$existingPage = get_page_by_title( $title );
			}
		}

		if ( ! empty( $existingPage ) && 'page' === $existingPage->post_type && ( $existingPage->post_status == 'publish' ) ) {
			$postID  = $existingPage->ID;
			$content = $existingPage->post_content;

			if ( ! has_shortcode( $content, $shortcode ) ) {
				wp_update_post(
					array(
						'ID'           => $postID,
						'post_content' => '[' . $shortcode . ']',
					)
				);

			}
			if ( ! isset( $options['pages']['recipe_search'] ) || $options['pages']['recipe_search'] != $postID ) {
				$pageID                           = array();
				$pageID['pages']['recipe_search'] = $postID;
				$search_page                      = array_merge( $options, $pageID );
				update_option( 'br_recipe_settings', $search_page );
			}
		} else {
			$newPage = array(
				'post_title'   => $title,
				'post_content' => '[' . $shortcode . ']',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			);
			$postID  = wp_insert_post( $newPage );
			$pageID  = array();

			if ( ! isset( $options['pages']['recipe_search'] ) || $options['pages']['recipe_search'] != $postID ) {
				$pageID['pages']['recipe_search'] = $postID;
				$search_page                      = array_merge( $options, $pageID );
				update_option( 'br_recipe_settings', $search_page );
			}
		}

		/**
		*   Creating a Demo Recipe Post on plugin activation.
		*/

		$options = get_option( '_blossom_recipe_maker', array() );

		if ( isset( $options['demo'] ) && ( $options['demo'] == '1' ) ) {
			return;
		} else {
			/**
			*   Default Settings value.
			*/
			$options          = get_option( 'br_recipe_settings', array() );
			$adjust_servings  = isset( $options['adjust_servings'] ) ? $options['adjust_servings'] : 1;
			$feat_image       = isset( $options['feat_image'] ) ? $options['feat_image'] : 1;
			$act_as_posts     = isset( $options['act_as_posts'] ) ? $options['act_as_posts'] : 1;
			$default_settings = array(
				'adjust_servings' => $adjust_servings,
				'feat_image'      => $feat_image,
				'act_as_posts'    => $act_as_posts,
			);

			$settings = array_merge( $options, $default_settings );
			update_option( 'br_recipe_settings', $settings );

			$options = get_option( '_blossom_recipe_maker', array() );

			$initial_meta = array(

				'details'      => array(
					'servings'         => '2',
					'prep_time'        => '120',
					'cook_time'        => '30',
					'total_time'       => '150',
					'difficulty_level' => 'Medium',
				),

				'notes'        => 'Use this section to add recipe notes or anything you like.',

				'ingredient'   => array(
					array(
						'heading' => 'Main Ingredients',
					),
					array(
						'quantity'   => '1 1/2',
						'unit'       => 'lb',
						'ingredient' => 'Salmon Fillets',
						'notes'      => '',
					),
					array(
						'quantity'   => '1/2',
						'unit'       => 'cup',
						'ingredient' => 'Soy Sauce',
						'notes'      => '',
					),
					array(
						'quantity'   => '2',
						'unit'       => 'tbsp',
						'ingredient' => 'Brown Sugar',
						'notes'      => '',
					),
					array(
						'quantity'   => '1/2',
						'unit'       => 'cup',
						'ingredient' => 'Water',
						'notes'      => '',
					),
					array(
						'quantity'   => '6',
						'unit'       => 'tbsp',
						'ingredient' => 'Vegetable Oil',
						'notes'      => '',
					),
					array(
						'heading' => 'Mixing Ingredients',
					),
					array(
						'quantity'   => '1/2',
						'unit'       => 'tsp',
						'ingredient' => 'Salt',
						'notes'      => 'To your taste',
					),
					array(
						'quantity'   => '1/2',
						'unit'       => 'tsp',
						'ingredient' => 'Lemon Pepper',
						'notes'      => '',
					),
					array(
						'quantity'   => '1/2',
						'unit'       => 'tsp',
						'ingredient' => 'Garlic Powder',
						'notes'      => '',
					),
				),
				'instructions' => array(

					array(
						'description' => 'Season salmon fillets with lemon pepper, garlic powder, and salt.',
						'image'       => '',
					),

					array(
						'description' => 'In a small bowl, stir together soy sauce, brown sugar, water, and vegetable oil until sugar is dissolved. Place fish in a large resealable plastic bag with the soy sauce mixture, seal, and turn to coat. Refrigerate for at least 2 hours.',
						'image'       => '',
					),
					array(
						'description' => 'Preheat grill for medium heat.',
						'image'       => '',
					),
					array(
						'description' => 'Lightly oil grill grate. Place salmon on the preheated grill, and discard marinade. Cook salmon for 6 to 8 minutes per side, or until the fish flakes easily with a fork.',
						'image'       => '',
					),

				),

			);

			$demo_post = array(
				'post_title'   => 'Salmon Demo Recipe',
				'post_content' => 'This is a demo recipe post. Use this like normal recipe post content.',
				'post_status'  => 'draft',
				'post_type'    => 'blossom-recipe',
				'post_author'  => get_current_user_id(),
				'post_excerpt' => 'The excerpt is the summary of the recipe post. It is used on recipe listing templates, where the full recipe should not be displayed.',

			);

			$postID = wp_insert_post( $demo_post );
			update_post_meta( $postID, 'br_recipe', $initial_meta );
			$flag = array();

			if ( ! isset( $options['demo'] ) || $options['demo'] == '' ) {
				$flag['demo'] = '1';
				$demo_recipe  = array_merge_recursive( $options, $flag );
				update_option( '_blossom_recipe_maker', $demo_recipe );
			}

			// Set Featured Image for Demo Recipe
			$url = BLOSSOM_RECIPE_MAKER_URL . '/public/images/salmon-recipe.jpg';
			media_sideload_image( $url, $postID );

			$attachments = get_posts(
				array(
					'numberposts'    => '1',
					'post_parent'    => $postID,
					'post_type'      => 'attachment',
					'post_mime_type' => 'image',
					'order'          => 'ASC',
				)
			);

			if ( sizeof( $attachments ) > 0 ) {
				set_post_thumbnail( $postID, $attachments[0]->ID );
			}
		}
	}
}
