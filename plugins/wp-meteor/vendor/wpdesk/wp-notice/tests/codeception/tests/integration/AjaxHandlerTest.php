<?php

namespace codeception\tests\integration;

use Codeception\TestCase\WPTestCase;
use \WPDesk\Notice\AjaxHandler;
use \WPDesk\Notice\PermanentDismissibleNotice;

class AjaxHandlerTest extends WPTestCase {

	const ASSETS_URL = 'http://test.com/test/assetes/';
	const NOTICE_NAME = 'test_notice_name';
	const WP_DEFAULT_PRIORITY = 10;

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

	public function testHooksWithAssetsURL() {
		$ajaxHandler = new AjaxHandler( self::ASSETS_URL );
		$ajaxHandler->hooks();

		$this->assertEquals(
			self::WP_DEFAULT_PRIORITY,
			has_action( 'admin_enqueue_scripts', [ $ajaxHandler, 'enqueueAdminScripts' ] )
		);
		$this->assertEquals(
			self::WP_DEFAULT_PRIORITY,
			has_action( 'wp_ajax_wpdesk_notice_dismiss', [ $ajaxHandler, 'processAjaxNoticeDismiss' ] )
		);
	}

	public function testHooksWithoutAssetsURL() {
		$ajaxHandler = new AjaxHandler();
		$ajaxHandler->hooks();

		$this->assertEquals(
			self::WP_DEFAULT_PRIORITY,
			has_action( 'admin_head', [ $ajaxHandler, 'addScriptToAdminHead' ] )
		);
		$this->assertEquals(
			self::WP_DEFAULT_PRIORITY,
			has_action( 'wp_ajax_wpdesk_notice_dismiss', [ $ajaxHandler, 'processAjaxNoticeDismiss' ] )
		);
	}

	public function testEnqueueAdminScripts() {
		$this->markTestSkipped( 'Must be revisited. get_current_screen not working.' );
		$ajaxHandler = new AjaxHandler( self::ASSETS_URL );
		$ajaxHandler->hooks();
		do_action( 'admin_enqueue_scripts' );
		$registeredScripts = wp_scripts()->registered;

		$this->assertArrayHasKey( 'wpdesk_notice', $registeredScripts, 'Script not registered!' );
		$this->assertEquals(
			self::ASSETS_URL . 'js/notice.js',
			$registeredScripts['wpdesk_notice']->src,
			'Script src is invalid!'
		);
	}

	public function testAddScriptToAdminHead() {
		$ajaxHandler = new AjaxHandler();
		$ajaxHandler->hooks();

		$this->expectOutputString( '<script type="text/javascript">'
                                   . "\n    "
                                   . file_get_contents( __DIR__ . '/../../../../assets/js/notice.js' )
                                   . '</script>
'
		);

		$ajaxHandler->addScriptToAdminHead();
	}

	public function testProcessAjaxNoticeDismiss() {
		$_POST[ AjaxHandler::POST_FIELD_NOTICE_NAME ] = self::NOTICE_NAME;
        $_POST[ AjaxHandler::POST_FIELD_SECURITY ] = wp_create_nonce( PermanentDismissibleNotice::OPTION_NAME_PREFIX . sanitize_text_field( self::NOTICE_NAME ) );

        $ajaxHandler = new AjaxHandler( self::ASSETS_URL );
		$ajaxHandler->processAjaxNoticeDismiss();

		$this->assertEquals(
			PermanentDismissibleNotice::OPTION_VALUE_DISMISSED,
			get_option( PermanentDismissibleNotice::OPTION_NAME_PREFIX . self::NOTICE_NAME )
		);
	}

    public function testShoulfNotProcessAjaxNoticeDismissWhenInvalidNonce() {
        $_POST[ AjaxHandler::POST_FIELD_NOTICE_NAME ] = self::NOTICE_NAME;
        $_POST[ AjaxHandler::POST_FIELD_SECURITY ] = wp_create_nonce();

        $ajaxHandler = new AjaxHandler( self::ASSETS_URL );
        $ajaxHandler->processAjaxNoticeDismiss();

        $this->assertNotEquals(
            PermanentDismissibleNotice::OPTION_VALUE_DISMISSED,
            get_option( PermanentDismissibleNotice::OPTION_NAME_PREFIX . self::NOTICE_NAME )
        );
    }

}
