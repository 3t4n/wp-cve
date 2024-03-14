<?php
/**
 * WordPress unit test plugin.
 *
 * @package     multi-pages-generator
 * @subpackage  Tests
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       3.3.11
 */

class Test_MPG_Upload extends WP_Ajax_UnitTestCase {

	private $project_id;

	public function setUp(): void {
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

	public function test_good_file_upload_csv() {

		$this->_setRole( 'editor' );

		$_POST['securityNonce'] = wp_create_nonce( MPG_BASENAME );
		$_POST['projectId'] = $this->project_id;
		$_POST['worksheetId'] = '';
		// use path to good_file.csv
		$_POST['fileUrl'] = MPG_MAIN_DIR . 'tests/data/good_file.csv';

		try {
			// Trigger the AJAX action
			$this->_handleAjax('mpg_download_file_by_url');
		} catch (WPAjaxDieContinueException $e) {
			// We expected this, do nothing.
		}

		$response = json_decode( $this->_last_response );
		$this->assertIsObject( $response );
		$this->assertTrue( $response->success );
		$this->assertEquals( MPG_MAIN_DIR . '/temp/unlinked_file.csv', $response->data->path );
	}

	public function test_bad_file_upload_csv() {

		$this->_setRole( 'editor' );

		$_POST['securityNonce'] = wp_create_nonce( MPG_BASENAME );
		$_POST['projectId'] = $this->project_id;
		$_POST['worksheetId'] = '';
		// use path to good_file.csv
		$_POST['fileUrl'] = MPG_MAIN_DIR . 'tests/data/bad_file.php';

		try {
			// Trigger the AJAX action
			$this->_handleAjax('mpg_download_file_by_url');
		} catch (WPAjaxDieContinueException $e) {
			// We expected this, do nothing.
		}

		$response = json_decode( $this->_last_response );
		$this->assertIsObject( $response );
		$this->assertTrue( ! $response->success );
		$this->assertObjectHasAttribute( 'error', $response );
		$this->assertTrue( strpos( $response->error, 'Unsupported file extension' ) !== false );
	}
}
