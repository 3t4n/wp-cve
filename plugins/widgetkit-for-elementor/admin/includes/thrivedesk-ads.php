<?php
class WKFE_Dashboard_Sidebar
{
    private static $instance;

    public static function init()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->wkfe_dashboard_sidebar_content();
    }
    public function wkfe_dashboard_sidebar_content()
    {
?>
        <div wk-sticky="offset: 40">
            <div class="td-banner">
                <a href="https://www.thrivedesk.com/?ref=widgetkit"><img style="max-width:260px; margin-bottom:20px" src="<?php echo plugins_url('../assets/images/td-banner.png', __FILE__) ?>"></a>
            </div>
        </div>
<?php
    }
}
?>