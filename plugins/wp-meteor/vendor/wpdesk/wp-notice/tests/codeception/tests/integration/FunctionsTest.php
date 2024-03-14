<?php

namespace codeception\tests\integration;

use Codeception\TestCase\WPTestCase;
use \WPDesk\Notice\Notice;
use \WPDesk\Notice\PermanentDismissibleNotice;

/**
 * Class TestFunctions
 */
class FunctionsTest extends WPTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

	/**
	 * Test WPDeskWpNotice function.
	 */
	public function testWPDeskWpNotice() {
		$notice = wpdesk_wp_notice( 'test function' );

		$this->assertInstanceOf( Notice::class, $notice );

		$this->expectOutputString( '<div class="notice notice-info"><p>test function</p></div>' );

		$notice->showNotice();
	}

	/**
	 * Test WPDeskWpNoticeInfo function.
	 */
	public function testWPDeskWpNoticeInfo() {
		$notice = wpdesk_wp_notice_info( 'test function' );

		$this->assertInstanceOf( Notice::class, $notice );

		$this->expectOutputString( '<div class="notice notice-info"><p>test function</p></div>' );

		$notice->showNotice();
	}

	/**
	 * Test WPDeskWpNoticeError function.
	 */
	public function testWPDeskWpNoticeError() {
		$notice = wpdesk_wp_notice_error( 'test function' );

		$this->assertInstanceOf( Notice::class, $notice );

		$this->expectOutputString( '<div class="notice notice-error"><p>test function</p></div>' );

		$notice->showNotice();
	}

	/**
	 * Test WPDeskWpNoticeWarning function.
	 */
	public function testWPDeskWpNoticeWarning() {
		$notice = wpdesk_wp_notice_warning( 'test function' );

		$this->assertInstanceOf( Notice::class, $notice );

		$this->expectOutputString( '<div class="notice notice-warning"><p>test function</p></div>' );

		$notice->showNotice();
	}

	/**
	 * Test WPDeskWpNoticeSuccess function.
	 */
	public function testWPDeskWpNoticeSuccess() {
		$notice = wpdesk_wp_notice_success( 'test function' );

		$this->assertInstanceOf( Notice::class, $notice );

		$this->expectOutputString( '<div class="notice notice-success"><p>test function</p></div>' );

		$notice->showNotice();
	}

	/**
	 * Test WPDeskPermanentDismissibleWpNotice function.
	 */
	public function testWPDeskPermanentDismissibleWpNotice() {
        $notice_name = 'test-notice';

		$notice = wpdesk_permanent_dismissible_wp_notice(
			'test function',
			$notice_name,
			Notice::NOTICE_TYPE_INFO
		);

        $security = wp_create_nonce( PermanentDismissibleNotice::OPTION_NAME_PREFIX . $notice_name );

		$this->assertInstanceOf( PermanentDismissibleNotice::class, $notice );

		$this->expectOutputString(
			'<div class="notice notice-info is-dismissible" data-notice-name="' . $notice_name . '" data-security="' . $security . '" id="wpdesk-notice-test-notice"><p>test function</p></div>'
		);

		$notice->showNotice();
	}

	/**
	 * Test WPDeskInitNoticeAjaxHandler function.
	 */
	public function testWPDeskInitWpNoticeAjaxHandler() {
		$ajax_handler = wpdesk_init_wp_notice_ajax_handler();

		$this->assertInstanceOf( \WPDesk\Notice\AjaxHandler::class, $ajax_handler );
	}

}
