<?php

namespace codeception\tests\integration;

use Codeception\TestCase\WPTestCase;
use \WPDesk\Notice\PermanentDismissibleNotice;

class PermanentDismissinleNoticeTest extends WPTestCase {

	const NOTICE_NAME = 'test_notice_name';

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

	public function testAddAction() {
		$notice_priority = 11;

		$notice = new PermanentDismissibleNotice(
			'test',
			'test_name',
			PermanentDismissibleNotice::NOTICE_TYPE_INFO,
			$notice_priority
		);

		$this->assertEquals( $notice_priority, has_action( 'admin_notices', [
			$notice,
			'showNotice',
		], $notice_priority ) );
	}

	public function testUndoDismiss() {
		update_option(
			PermanentDismissibleNotice::OPTION_NAME_PREFIX . self::NOTICE_NAME,
			PermanentDismissibleNotice::OPTION_VALUE_DISMISSED
		);

		$notice = new PermanentDismissibleNotice(
			PermanentDismissibleNotice::NOTICE_TYPE_INFO,
			self::NOTICE_NAME
		);
		$notice->undoDismiss();

		$this->assertEquals(
			'',
			get_option( PermanentDismissibleNotice::OPTION_NAME_PREFIX . self::NOTICE_NAME, '' )
		);
	}

	public function testShowNotice() {
        $notice_name = 'test_name';

		$notice = new PermanentDismissibleNotice(
			'test',
			$notice_name,
			PermanentDismissibleNotice::NOTICE_TYPE_INFO
		);

        $security = wp_create_nonce( PermanentDismissibleNotice::OPTION_NAME_PREFIX . $notice_name );

		$this->expectOutputString(
            '<div class="notice notice-info is-dismissible" data-notice-name="' . $notice_name . '" data-security="' . $security . '" id="wpdesk-notice-test_name"><p>test</p></div>'
		);

		$notice->showNotice();
	}

}
