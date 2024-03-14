<?php

use \WPDesk\Notice\Notice;
use \WPDesk\Notice\PermanentDismissibleNotice;

/**
 * Class TestFunctions
 */
class TestFunctions extends WP_UnitTestCase
{

    /**
     * Test redeclare functions.
     */
    public function testRedeclareFunctions()
    {
        include __DIR__ . '/../../src/WPDesk/notice-functions.php';
        $this->assertTrue(true);
    }

    /**
     * Test WPDeskWpNotice function.
     */
    public function testWPDeskWpNotice()
    {
        $notice = wpdesk_wp_notice('test function');

        $this->assertInstanceOf(Notice::class, $notice);

        $this->expectOutputString('<div class="notice notice-info"><p>test function</p></div>');

        $notice->showNotice();
    }

    /**
     * Test WPDeskWpNoticeInfo function.
     */
    public function testWPDeskWpNoticeInfo()
    {
        $notice = wpdesk_wp_notice_info('test function');

        $this->assertInstanceOf(Notice::class, $notice);

        $this->expectOutputString('<div class="notice notice-info"><p>test function</p></div>');

        $notice->showNotice();
    }

    /**
     * Test WPDeskWpNoticeError function.
     */
    public function testWPDeskWpNoticeError()
    {
        $notice = wpdesk_wp_notice_error('test function');

        $this->assertInstanceOf(Notice::class, $notice);

        $this->expectOutputString('<div class="notice notice-error"><p>test function</p></div>');

        $notice->showNotice();
    }

    /**
     * Test WPDeskWpNoticeWarning function.
     */
    public function testWPDeskWpNoticeWarning()
    {
        $notice = wpdesk_wp_notice_warning('test function');

        $this->assertInstanceOf(Notice::class, $notice);

        $this->expectOutputString('<div class="notice notice-warning"><p>test function</p></div>');

        $notice->showNotice();
    }

    /**
     * Test WPDeskWpNoticeSuccess function.
     */
    public function testWPDeskWpNoticeSuccess()
    {
        $notice = wpdesk_wp_notice_success('test function');

        $this->assertInstanceOf(Notice::class, $notice);

        $this->expectOutputString('<div class="notice notice-success"><p>test function</p></div>');

        $notice->showNotice();
    }

    /**
     * Test WPDeskPermanentDismissibleWpNotice function.
     */
    public function testWPDeskPermanentDismissibleWpNotice()
    {
        $notice = wpdesk_permanent_dismissible_wp_notice(
            'test function',
            'test-notice',
            Notice::NOTICE_TYPE_INFO
        );

        $this->assertInstanceOf(PermanentDismissibleNotice::class, $notice);

        $this->expectOutputString(
            '<div class="notice notice-info is-dismissible" data-notice-name="test-notice" id="wpdesk-notice-test-notice"><p>test function</p></div>'
        );

        $notice->showNotice();
    }

    /**
     * Test WPDeskInitNoticeAjaxHandler function.
     */
    public function testWPDeskInitWpNoticeAjaxHandler()
    {
        $ajax_handler = wpdesk_init_wp_notice_ajax_handler();

        $this->assertInstanceOf(\WPDesk\Notice\AjaxHandler::class, $ajax_handler);
    }

}
