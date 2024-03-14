<?php

use \WPDesk\Notice\AjaxHandler;
use \WPDesk\Notice\PermanentDismissibleNotice;

class TestAjaxHandler extends WP_UnitTestCase
{

    const ASSETS_URL = 'http://test.com/test/assetes/';
    const NOTICE_NAME = 'test_notice_name';
    const WP_DEFAULT_PRIORITY = 10;

    public function testHooksWithAssetsURL()
    {
        $ajaxHandler = new AjaxHandler(self::ASSETS_URL);
        $ajaxHandler->hooks();

        $this->assertEquals(
            self::WP_DEFAULT_PRIORITY,
            has_action('admin_enqueue_scripts', [$ajaxHandler, 'enqueueAdminScripts'])
        );
        $this->assertEquals(
            self::WP_DEFAULT_PRIORITY,
            has_action('wp_ajax_wpdesk_notice_dismiss', [$ajaxHandler, 'processAjaxNoticeDismiss'])
        );
    }

    public function testHooksWithoutAssetsURL()
    {
        $ajaxHandler = new AjaxHandler();
        $ajaxHandler->hooks();

        $this->assertEquals(
            self::WP_DEFAULT_PRIORITY,
            has_action('admin_head', [$ajaxHandler, 'addScriptToAdminHead'])
        );
        $this->assertEquals(
            self::WP_DEFAULT_PRIORITY,
            has_action('wp_ajax_wpdesk_notice_dismiss', [$ajaxHandler, 'processAjaxNoticeDismiss'])
        );
    }

    public function testEnqueueAdminScripts()
    {
        $this->markTestSkipped('Must be revisited. get_current_screen not working.');
        $ajaxHandler = new AjaxHandler(self::ASSETS_URL);
        $ajaxHandler->hooks();
        do_action('admin_enqueue_scripts');
        $registeredScripts = wp_scripts()->registered;

        $this->assertArrayHasKey('wpdesk_notice', $registeredScripts, 'Script not registered!');
        $this->assertEquals(
            self::ASSETS_URL . 'js/notice.js',
            $registeredScripts['wpdesk_notice']->src,
            'Script src is invalid!'
        );
    }

    public function testAddScriptToAdminHead()
    {
        $ajaxHandler = new AjaxHandler();
        $ajaxHandler->hooks();

        $this->expectOutputString('<script type="text/javascript">
    jQuery(document).on("click",".notice-dismiss",function(){var a=jQuery(this).closest("div.notice").data("notice-name");var b=jQuery(this).closest("div.notice").data("source");if(""!==a){jQuery.ajax({url:ajaxurl,type:"post",data:{action:"wpdesk_notice_dismiss",notice_name:a,source:b},success:function(c){}})}});jQuery(document).on("click",".notice-dismiss-link",function(){jQuery(this).closest("div.notice").data("source",jQuery(this).data("source"));jQuery(this).closest("div.notice").find(".notice-dismiss").click()});</script>
'
        );

        $ajaxHandler->addScriptToAdminHead();
    }

    public function testProcessAjaxNoticeDismiss()
    {
        $_POST[AjaxHandler::POST_FIELD_NOTICE_NAME] = self::NOTICE_NAME;

        $ajaxHandler = new AjaxHandler(self::ASSETS_URL);
        $ajaxHandler->processAjaxNoticeDismiss();

        $this->assertEquals(
            PermanentDismissibleNotice::OPTION_VALUE_DISMISSED,
            get_option(PermanentDismissibleNotice::OPTION_NAME_PREFIX . self::NOTICE_NAME)
        );
    }

}
