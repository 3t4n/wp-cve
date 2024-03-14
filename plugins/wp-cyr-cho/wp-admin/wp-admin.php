<?php
/**
* Cyr-Cho (Cyrillic Slugs)
*
* @author Kaloyan K. Tsvetkov <kaloyan@kaloyan.info>
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*/

/////////////////////////////////////////////////////////////////////////////

/**
* @internal prevent from direct calls
*/
if (!defined('ABSPATH')) {
	return ;
	}

/////////////////////////////////////////////////////////////////////////////		

/**
* ...
* @see wp_admin_page
*/
require_once WP_CYRCHO_DIR . '/lib/wp-admin-page/class.wp-admin-page.php';

/////////////////////////////////////////////////////////////////////////////		

/**
* @internal check if class is already loaded
*/
if (class_exists('wp_cyrcho_admin')) {
	return ;
	}

/////////////////////////////////////////////////////////////////////////////

/**
* The administration panel class
*
* This class handles the pages attached to the backend
*/
Class wp_cyrcho_admin Extends wp_admin_page {

	/**
	* Constructor
	*
	* Instantiates the page controller and places the required
	* plugin hooks for the loaded page controller
	*
	* @param boolean $init
	*/
	Function wp_cyrcho_admin($init = 0) {

		// page ?
		//
		if (!$init) {
			parent::wp_admin_page();
			return;
			}

		// menu ?
		//
		wp_admin_page::admin_menu(
			'',
			array(

				// dashboard
				//
				'options-general.php' => array(
					'submenu' => array(
						'cyrcho_settings' => array(
							'file' => __FILE__,
							'class' => __CLASS__,
							'page_title' => 'Cyr-Cho &rsaquo; Settings',
							'menu_title' => 'Cyr-Cho',
							),				
						)
					),
				)
			);
		}

	// -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- 

	/**
	* Convert Slugs
	*/
	Function index() {

		global $wpdb;
		
		$posts_slugs_count = $wpdb->get_var("
			SELECT Count(*) FROM `{$wpdb->posts}` WHERE `post_name` LIKE '%\%%'
			");
		$terms_slugs_count = $wpdb->get_var("
			SELECT Count(*) FROM `{$wpdb->terms}` WHERE `slug` LIKE '%\%%'
			");
		?>

<div class="wrap">
	<div id="icon-edit" class="icon32"><br /></div>
	<h2>Cyr-Cho &rarr; <?php _e( 'Convert' ) ; ?></h2>

<?php	wp_admin_page::ok('Convert sucessful.', 'convert'); wp_admin_page::error('', 1); ?>

	<form id="cyrcho-edit" action="" method="post">
		<input type="hidden" name="action" value="save" />

		<table class="form-table">
		<tr valign="top">
		<th scope="row"><?php _e('Convert Slugs') ?></th>
		<td>

			<fieldset>
				<legend class="hidden"><?php _e('Convert Slugs') ?></legend>

				<label for="post_slugs">
					<input name="post_slugs" type="checkbox" id="post_slugs" value="1" />
					<strong><?php _e('Post Slugs') ?></strong></label><br />

					<?php _e('Those are the slugs for the blog posts and pages.') ?><br />
					<?php printf(__('There seem to be %s Cyrillic post slugs.'), "<b>{$posts_slugs_count}</b>"); ?><br />
					<br />

				<label for="term_slugs">
					<input name="term_slugs" type="checkbox" id="term_slugs" value="1" />
					<strong><?php _e('Term Slugs') ?></strong></label><br />

					<?php _e('Those are the terms and categories\' nice names, as well as any other taxonomy.') ?><br />
					<?php printf(__('There seem to be %s Cyrillic term slugs.'), "<b>{$terms_slugs_count}</b>"); ?><br />
					<br />

			</fieldset>

			<span class="setting-description"><?php
				_e('The above data are the stats for the use of what looks like Cyrillic slugs.'); echo '<br/>';
				_e('Use the check-boxes to select which ones you want converted, and hit the "Convert" button below.');
				?>
			</span>
		
		</td>
		</tr>
		</table>

		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Convert'); ?>" />
		</p>

	</form>

	<br class="clear" />
</div>
		<?php
		}

	/**
	* Do convert the slugs
	*/
	Function action_save() {

		global $wpdb;
		
		// do convert post slugs
		//
		if (isset($_POST['post_slugs']) && $_POST['post_slugs']) {
			
			$posts = $wpdb->get_results("
				SELECT `ID`, `post_name`
				FROM `{$wpdb->posts}`
				WHERE `post_name` LIKE '%\%%'
				");

			foreach ($posts as $post) {

				$wpdb->query("
					UPDATE `{$wpdb->posts}`
						SET `post_name` = '"
					 . $wpdb->escape(
						sanitize_title(
							urlDecode($post->post_name)
							)
						) . "'
					WHERE ID = "
						. $wpdb->escape($post->ID)
					);
				add_post_meta($post->ID, '_wp_old_slug', $post->post_name);
				}
			}

		// do convert term slugs
		//
		if (isset($_POST['term_slugs']) && $_POST['term_slugs']) {
			
			$terms = $wpdb->get_results("
				SELECT `term_id`, `slug`
				FROM `{$wpdb->terms}`
				WHERE `slug` LIKE '%\%%'
				");

			foreach ($terms as $term) {

				$wpdb->query("
					UPDATE `{$wpdb->terms}`
						SET `slug` = '"
					. $wpdb->escape(
						sanitize_title(
							urlDecode($term->slug)
							)
						) . "'
					WHERE term_id = "
						. $wpdb->escape($term->term_id)
					);
				}
			}

		wp_admin_page::redirect(
			remove_query_arg(
				array('converted'),
				$_SERVER['REQUEST_URI']
				)
			. '&converted=1',
			1);
		}

	//--end-of-class--
	}
