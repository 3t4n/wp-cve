<script>
let is_default_api = <?= json_encode($api_key === EOD_DEFAULT_API) ?>;

function check_token_on_example_page(type, props){
    eod_check_token_capability(type, props, function(error){
        if(error){
            // Forbidden
            if(error.error_code === 'forbidden'){
                if(jQuery('.tab.active .eod_error.forbidden').length === 0){
                    let text = is_default_api ? 'You are using a free version of an API key that does not support this ticker.' :
                        'Your current plan does not include these features.';
                    jQuery('.tab.active .eod_shortcode_form').append('<div class="eod_error forbidden">Warning: '+text+'</div>');
                }
            }else{
                jQuery('.tab.active .eod_error.forbidden').remove();
            }
            
            // Unauthenticated
            if(error.error_code === 'unauthenticated'){
                if(jQuery('.tab.active .eod_error.unauthenticated').length === 0)
                    jQuery('.tab.active .eod_shortcode_form').append('<div class="eod_error unauthenticated">Warning: Your API key is invalid or no longer maintained. Make sure that the correct key is specified in the settings.</div>');
            }else{
                jQuery('.tab.active .eod_error.unauthenticated').remove();
            }
        }else{
            jQuery('.tab.active .eod_error').remove();
        }
    });
}
</script>
<div class="wrap">
    <div class="eod_page with_sidebar">
        <?php eod_include( 'admin/template/header.php' ); ?>
        <div>
            <nav id="header_nav" class="nav-tab-wrapper">
                <a href="?page=eod-examples&e=ticker" class="nav-tab">Ticker String</a>
                <a href="?page=eod-examples&e=fundamental" class="nav-tab">Fundamentals</a>
                <a href="?page=eod-examples&e=financials" class="nav-tab">Financial Table</a>
                <a href="?page=eod-examples&e=news" class="nav-tab">Financial News</a>
                <a href="?page=eod-examples&e=converter" class="nav-tab">Currency Converter (Crypto + Forex)</a>
            </nav>
            <div id="tabs_wrapper">
                <div class="tab">
                    <?php include( plugin_dir_path( __FILE__ ) . 'examples/ticker.php'); ?>
                </div>
                <div class="tab">
                    <?php include( plugin_dir_path( __FILE__ ) . 'examples/fundamental.php'); ?>
                </div>
                <div class="tab">
                    <?php include( plugin_dir_path( __FILE__ ) . 'examples/financials.php'); ?>
                </div>
                <div class="tab">
                    <?php include( plugin_dir_path( __FILE__ ) . 'examples/news.php'); ?>
                </div>
                <div class="tab">
                    <?php include( plugin_dir_path( __FILE__ ) . 'examples/converter.php'); ?>
                </div>
            </div>
        </div>
        <div class="eod_sidebar">
            <?php include( plugin_dir_path( __FILE__ ) . 'sidebar.php'); ?>
        </div>
    </div>
</div>

<script>
jQuery(document).on('click', '#header_nav a', function(e){
    e.preventDefault();
    jQuery('#header_nav .nav-tab-active').removeClass('nav-tab-active');
    jQuery('#tabs_wrapper .tab.active').removeClass('active');
    jQuery('#tabs_wrapper .tab').eq(jQuery(this).index()).addClass('active');
    jQuery(this).addClass('nav-tab-active');
    window.history.pushState("", "", '/wp-admin/admin.php'+jQuery(this).attr('href'));
})
jQuery(function() {
    let current_tab_slug = '<?= isset($_GET['e']) ? $_GET['e'] : '' ?>', $current_tab_a;

    // Define current tab element
    if( current_tab_slug )
        $current_tab_a = jQuery('#header_nav .nav-tab[href="?page=eod-examples&e='+current_tab_slug+'"]');
    else
        $current_tab_a = jQuery('#header_nav .nav-tab').eq(0);

    let current_index = $current_tab_a.index();

    // Show tab
    $current_tab_a.addClass('nav-tab-active');
    jQuery('#tabs_wrapper .tab').eq( current_index ).addClass('active');
});


</script>