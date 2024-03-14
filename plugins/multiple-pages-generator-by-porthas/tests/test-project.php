<?php
/**
 * WordPress unit test plugin.
 *
 * @package     multi-pages-generator
 * @subpackage  Tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.3.11
 */
class Test_MPG_Project extends WP_UnitTestCase {

	/**
	 * Source file URL.
	 *
	 * @var string $source_file Source file URL.
	 */
	public $source_file = 'https://www.mpgwp.com/storage/World cities.csv';

	/**
	 * Sets up the test methods.
	 */
	public function setUp() :void {
		parent::setUp();
		// avoids error - readfile(/src/wp-includes/js/wp-emoji-loader.js): failed to open stream: No such file or directory
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	}

	/**
	 * Test project.
	 */
	public function test_project_create() {
		do_action( 'init' );

		$user_id = $this->factory->user->create(
			array(
				'role' => 'administrator',
			)
		);
		wp_set_current_user( $user_id );
		$p = $this->factory->post->create_and_get(
			array(
				'post_title'  => 'MPG Template - {{mpg_first}} by {{mpg_second}}',
				'post_author' => $user_id,
				'post_type'   => 'page',
				'post_name'   => 'mpg-template-test',
			)
		);

		// Test create template page.
		do_action( 'save_post', $p->ID, $p );

		$this->assertTrue( $p->ID > 0 );

		$project_id = MPG_ProjectModel::mpg_create_base_carcass( 'PHPUnit Test', 'page', $p->ID, true );

		$this->assertTrue( $project_id > 0 );
	}

	/**
	 * Read dataset hub.
	 */
	public function test_read_dataset_hub() {
		$dataset_hub = MPG_DatasetModel::mpg_read_dataset_hub();
		$this->assertIsArray( $dataset_hub );
	}

	/**
	 * Deploy project from dataset hub.
	 */
	public function test_upload_file() {
		$project = $this->get_project_list();
		$ext     = MPG_Helper::mpg_get_extension_by_path( $this->source_file );
		$this->assertEquals( 'csv', $ext );

		$destination_path = MPG_UPLOADS_DIR . $project->id . '.' . $ext;

		$blog_id = get_current_blog_id();

		if ( is_multisite() && $blog_id > 1 ) {
			$destination_path = MPG_UPLOADS_DIR . $blog_id . '/' . $project->id . '.' . $ext;
		}

		$insert_data = array(
			'source_type' => 'upload_file',
			'source_path' => $destination_path,
		);

		// Download source file.
		$download_dataset = MPG_DatasetModel::download_file( $this->source_file, $destination_path );
		$this->assertTrue( $download_dataset );

		// Get headers.
		$headers = MPG_DatasetController::get_headers( $destination_path );
		$this->assertIsArray( $headers );
		$insert_data['headers'] = json_encode( $headers );

		$url_structure  = '{{mpg_city}}';
		$space_replacer = '-';

		// Generate URLs from dataset.
		$urls_array = MPG_ProjectModel::mpg_generate_urls_from_dataset( $destination_path, $url_structure, $space_replacer );
		$this->assertIsArray( $urls_array );

		$insert_data['url_structure']         = $url_structure;
		$insert_data['space_replacer']        = $space_replacer;
		$insert_data['urls_array']            = json_encode( $urls_array );
		$insert_data['participate_in_search'] = 1;

		$updated = MPG_ProjectModel::mpg_update_project_by_id( $project->id, $insert_data );
		$this->assertTrue( $updated );
	}

	/**
	 * Get redirection rules.
	 */
	public function test_get_redirect_rules() {
		$path  = '/malisheve/';
		$rules = MPG_CoreModel::mpg_get_redirect_rules( $path );
		$this->assertIsArray( $rules );
		$this->assertTrue( ! empty( $rules ) );
	}

	/**
	 * Test render MPG shortcode.
	 */
	public function test_render_shortcode() {
		global $wp;
		$project      = $this->get_project_list();
		$wp->request  = '/bowling-green/';
		$post_content = MPG_CoreModel::mpg_shortcode_replacer( 'MPG Template - {{mpg_city}} by {{mpg_country}}', $project->id );
		$this->assertStringNotContainsString( '{{mpg_city}} by {{mpg_country}}', $post_content );
	}

	/**
	 * Test live periodic data import.
	 */
	public function test_live_periodic_sync() {
		global $wp;
		$project = $this->get_project_list();

		// Delete project transient.
		$key_name = wp_hash( 'dataset_array_' . $project->id );
		delete_transient( $key_name );
		$this->assertFalse( get_transient( $key_name ) );

		// Refetch data.
		$import_dataset = MPG_Helper::mpg_get_dataset_array( $project );
		$this->assertIsArray( $import_dataset );

		$path  = '/kandava/';
		$rules = MPG_CoreModel::mpg_get_redirect_rules( $path );
		$this->assertIsArray( $rules );
	}

	/**
	 * Search in projects.
	 */
	public function test_search_projects() {
		$project = $this->get_project_list();

		$p = $this->factory->post->create_and_get(
			array(
				'post_title' => 'MPG Template - {{mpg_city}} by {{mpg_country}}',
				'post_type'  => 'page',
				'post_name'  => 'mpg-template-test',
			)
		);

		// Test create template page.
		do_action( 'save_post', $p->ID, $p );

		$this->assertTrue( $p->ID > 0 );

		$insert_data = array(
			'participate_in_search' => true,
			'template_id'           => $p->ID,
		);

		$updated = MPG_ProjectModel::mpg_update_project_by_id( $project->id, $insert_data );
		$this->assertTrue( $updated );

		update_option(
			'mpg_search_settings',
			array(
				'mpg_ss_excerpt_length' => 0,
			)
		);

		$result = MPG_SearchController::mpg_search( 'Vushtrri' );
		$this->assertTrue( $result['total'] > 0 );
	}

	/**
	 * Delete projects.
	 */
	public function test_delete_projects() {
		$project = $this->get_project_list();
		if ( isset( $project ) && $project->source_path ) {
			$dataset_path = $project->source_path;
			$delete_file  = MPG_ProjectModel::deleteFileByPath( $dataset_path );
			$this->assertTrue( $delete_file );
		}
		if ( $project->schedule_source_link && $project->schedule_notificate_about && $project->schedule_periodicity && $project->schedule_notification_email ) {
			$remove_cron = MPG_ProjectModel::mpg_remove_cron_task_by_project_id( $project->id, $project );
			$this->assertTrue( $remove_cron );
		}

		$delete_project = MPG_ProjectModel::deleteProjectFromDb( $project->id );
		$this->assertIsInt( $delete_project );

		$flush_cache = MPG_SpintaxModel::flush_cache_by_project_id( $project->id );
		$this->assertTrue( $flush_cache );
	}

	/**
	 * Check skip 302 redirection If the requested path is empty.
	 */
	public function test_check_skip_302_redirection() {
		$path  = '/';
		$rules = MPG_CoreModel::mpg_get_redirect_rules( $path );
		$this->assertEmpty( $rules );
	}

	/**
	 * Test permalink.
	 */
	public function test_cpt_permalink() {
		global $wp_query;
		$p = $this->factory->post->create_and_get(
			array(
				'post_title' => 'MPG Template - {{mpg_city}} by {{mpg_country}}',
				'post_type'  => 'page',
				'post_name'  => 'mpg-template-test',
			)
		);

		// Test create template page.
		do_action( 'save_post', $p->ID, $p );

		$wp_query->queried_object = $p;
		$rules                    = MPG_CoreModel::mpg_get_redirect_rules( '/mpg-template-test/' );
		$this->assertEmpty( $rules );
	}

	/**
	 * Test is post single page.
	 */
	public function test_is_single_page() {
		global $wp_query;
		$p = $this->factory->post->create_and_get(
			array(
				'post_title' => 'MPG Post Test',
				'post_type'  => 'post',
				'post_name'  => 'mpg-post-test',
			)
		);

		// Test create template page.
		do_action( 'save_post', $p->ID, $p );
		$wp_query->is_single = true;
		$rules               = MPG_CoreModel::mpg_get_redirect_rules( '/mpg-post-test/' );
		$this->assertEmpty( $rules );
	}

	/**
	 * Project list.
	 */
	public function get_project_list() {
		$projects     = new ProjectsListManage();
		$project_list = $projects->projects_list();
		return ! empty( $project_list ) ? reset( $project_list ) : array();
	}

}
