<?php if (!defined('ABSPATH')) die('Restricted Access'); ?>
<?php $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jsjobs-repaptcha-scripti','https://maps.googleapis.com/maps/api/js?key='.jsjobs::$_configuration['google_map_api_key']);
?>
<script type="text/javascript">
    var ajaxurl = "<?php echo esc_url(admin_url('admin-ajax.php')) ?>";
    jQuery(document).ready(function(){
        var print_link = document.getElementById('print-link');
        if (print_link) {
            var href = '<?php echo esc_url(jsjobs::makeUrl(array('jsjobsme'=>'resume', 'jsjobslt'=>'printresume', 'jsjobsid'=>jsjobs::$_data[0]['personal_section']->id, 'jsjobspageid'=>jsjobs::getPageid()))) ?>';
            print_link.addEventListener('click', function (event) {
                print = window.open(href, 'print_win', 'width=1024, height=800, scrollbars=yes');
                event.preventDefault();
            }, false);
        }
    });
    function showPopupAndSetValues() {
        jQuery("div#full_background").show();
        jQuery("div#popup-main-outer.coverletter").show();
        jQuery("div#popup-main.coverletter").slideDown('slow');
        jQuery("div#full_background").click(function () {
            closePopup();
        });
        jQuery("img#popup_cross").click(function () {
            closePopup();
        });
    }
    function closePopup() {
        jQuery("div#popup-main-outer").slideUp('slow');
        setTimeout(function () {
            jQuery("div#full_background").hide();
            jQuery("div#popup-main").hide();
        }, 700);
    }

    function initialize(lat, lang, div) {
        var myLatlng = new google.maps.LatLng(lat, lang);
        var myOptions = {
            zoom: 8,
            center: myLatlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }
        var map = new google.maps.Map(document.getElementById(div), myOptions);
        var marker = new google.maps.Marker({
            map: map,
            position: myLatlng
        });
    }
    jQuery(document).ready(function () {
        jQuery('div.resume-map div.row-title').click(function (e) {
            e.preventDefault();
            var img1 = '<?php echo JSJOBS_PLUGIN_URL . 'includes/images/resume/show-map.png'; ?>';
            var img2 = '<?php echo JSJOBS_PLUGIN_URL . 'includes/images/resume/hide-map.png'; ?>';
            var pdiv = jQuery(this).parent();
            var mdiv = jQuery(pdiv).find('div.row-value');
            if (jQuery(mdiv).css('display') == 'none') {
                jQuery(mdiv).show();
                jQuery(this).find('img').attr('src', img2);
            } else {
                jQuery(mdiv).hide();
                jQuery(this).find('img').attr('src', img1);
            }
        });
    });
</script>
