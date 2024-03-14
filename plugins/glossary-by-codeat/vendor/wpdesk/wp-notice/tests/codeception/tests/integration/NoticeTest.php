<?php

namespace codeception\tests\integration;

use Codeception\TestCase\WPTestCase;
use \WPDesk\Notice\Notice;

class NoticeTest extends WPTestCase {

    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function testAddAction() {
		$notice_priority = 11;

		$notice = new Notice( Notice::NOTICE_TYPE_INFO, 'test', false, $notice_priority );

		$this->assertEquals( $notice_priority, has_action( 'admin_notices', [
			$notice,
			'showNotice',
		], $notice_priority ) );

		$this->assertEquals(
			Notice::ADMIN_FOOTER_BASE_PRIORITY + intval( $notice_priority ),
			has_action(
				'admin_footer',
				[ $notice, 'showNotice' ],
				Notice::ADMIN_FOOTER_BASE_PRIORITY + intval( $notice_priority )
			)
		);
	}

	public function testShowNotice() {
		$notice = new Notice( 'test' );

		$this->expectOutputString( '<div class="notice notice-info"><p>test</p></div>' );

		$notice->showNotice();

		$this->assertFalse(
			has_action( 'admin_notices', [ $notice, 'showNotice' ], 10 )
		);
		$this->assertFalse(
			has_action( 'admin_footer', [ $notice, 'showNotice' ], 10 )
		);
	}

	public function testShowNoticeError() {
		$notice = new Notice( 'test', Notice::NOTICE_TYPE_ERROR );

		$this->expectOutputString( '<div class="notice notice-error"><p>test</p></div>' );

		$notice->showNotice();
	}

	public function testShowNoticeWarning() {
		$notice = new Notice( 'test', Notice::NOTICE_TYPE_WARNING );

		$this->expectOutputString( '<div class="notice notice-warning"><p>test</p></div>' );

		$notice->showNotice();
	}

	public function testShowNoticeSuccess() {
		$notice = new Notice( 'test', Notice::NOTICE_TYPE_SUCCESS );

		$this->expectOutputString( '<div class="notice notice-success"><p>test</p></div>' );

		$notice->showNotice();
	}

	public function testShowNoticeDismissible() {
		$notice = new Notice( 'test', Notice::NOTICE_TYPE_INFO, true );

		$this->expectOutputString( '<div class="notice notice-info is-dismissible"><p>test</p></div>' );

		$notice->showNotice();
	}

	public function testNoticeContent() {
		$noticeContent = 'test';

		$notice = new Notice( $noticeContent );

		$this->assertEquals( $noticeContent, $notice->getNoticeContent() );

		$noticeContent = 'test 2';
		$notice->setNoticeContent( $noticeContent );
		$this->assertEquals( $noticeContent, $notice->getNoticeContent() );
	}

	public function testNoticeType() {
		$notice = new Notice( 'test', Notice::NOTICE_TYPE_INFO );

		$this->assertEquals( Notice::NOTICE_TYPE_INFO, $notice->getNoticeType() );

		$notice->setNoticeType( Notice::NOTICE_TYPE_ERROR );
		$this->assertEquals( Notice::NOTICE_TYPE_ERROR, $notice->getNoticeType() );
	}

	public function testDismissible() {
		$notice = new Notice( 'test' );

		$this->assertFalse( $notice->isDismissible() );

		$notice->setDismissible( true );
		$this->assertTrue( $notice->isDismissible() );
	}

	public function testPriority() {
		$notice = new Notice( 'test' );

		$this->assertEquals( 10, $notice->getPriority() );

		$notice->setPriority( 20 );
		$this->assertEquals( 20, $notice->getPriority() );
	}

	public function testAddAttribute() {
		$notice = new Notice( 'test', Notice::NOTICE_TYPE_WARNING );

		$notice->addAttribute( 'id', 'test_id' );

		$this->expectOutputString( '<div class="notice notice-warning" id="test_id"><p>test</p></div>' );

		$notice->showNotice();
	}

	public function testAddAttributeClass() {
		$notice = new Notice( 'test', Notice::NOTICE_TYPE_WARNING );

		$notice->addAttribute( 'class', 'test-class' );

		$this->expectOutputString( '<div class="notice notice-warning test-class"><p>test</p></div>' );

		$notice->showNotice();
	}

}
