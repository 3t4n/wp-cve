<?php
/**
 * WordPress unit test plugin.
 *
 * @package     multi-pages-generator
 * @subpackage  Tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.3.11
 */

class Test_MPG_Ajax extends WP_Ajax_UnitTestCase {

	private $project_id;

	public function setUp() :void {
		parent::setUp();
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
		$this->project_id = $project_id;
	}
	public function test_ajax_response_logs_subscriber() {

		$this->_setRole( 'subscriber' );
		$_GET['projectId'] = $this->project_id;

		try {
			// Trigger the AJAX action
			$this->_handleAjax('mpg_get_log_by_project_id');
		} catch (WPAjaxDieContinueException $e) {
			// We expected this, do nothing.
		}

		$response = json_decode( $this->_last_response );
		$this->assertIsObject( $response );
		$this->assertObjectHasAttribute( 'errors', $response );
		$this->assertObjectHasAttribute( 'error_data', $response );
		$this->assertEquals( 401, $response->error_data->rest_forbidden->status );
	}

	public function test_ajax_response_logs_admin() {

		$this->_setRole( 'administrator' );
		$_GET['projectId'] = $this->project_id;

		try {
			// Trigger the AJAX action
			$this->_handleAjax('mpg_get_log_by_project_id');
		} catch (WPAjaxDieContinueException $e) {
			// We expected this, do nothing.
		}


		$response = json_decode( $this->_last_response );
		$this->assertIsObject( $response );
		$this->assertObjectHasAttribute( 'draw', $response );
		$this->assertObjectHasAttribute( 'data', $response );
	}

	public function test_ajax_response_logs_editor() {

		$this->_setRole( 'editor' );
		$_GET['projectId'] = $this->project_id;

		try {
			// Trigger the AJAX action
			$this->_handleAjax('mpg_get_log_by_project_id');
		} catch (WPAjaxDieContinueException $e) {
			// We expected this, do nothing.
		}


		$response = json_decode( $this->_last_response );
		$this->assertIsObject( $response );
		$this->assertObjectHasAttribute( 'draw', $response );
		$this->assertObjectHasAttribute( 'data', $response );
	}
}
