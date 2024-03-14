<!--  -->
<?php 
    $message = esc_html__('If you are re looking for a brand new animated WordPress theme, check please Olena Theme. Here is a short viedo:', 'mxmtzc-domain');
    $button_url = esc_url('https://olena-theme.com.ua/');
    $button_text = 'Download Olena Theme';
?>
<style>
    .olena-notification-wrapper {
        display: flex;
        flex-wrap: wrap;
        padding: 20px;
        background-color: #fff;
        margin-top: 10px;
    }

    .olena-notification-image {
        width: 10%;
    }

    .olena-notification-content {
        width: 90%;
    }

    .olena-notification-image img {
        width: 100%;
        max-width: 100px;
    }

    .olena-notification-install-button-wrapp {
        margin-top: 20px;
    }

    .olena-notification-install-button {
        background: #007cba;
        border: 0;
        border-radius: 2px;
        box-sizing: border-box;
        color: #ffffff;
        display: inline-flex;
        font-size: 14px;
        font-weight: 400;
        margin: 0;
        padding: 16px 25px;
        text-decoration: none;
        transition: box-shadow .1s linear;
    }

    .olena-notification-install-button:hover {
        color: #ffffff;
        background: #015c89;
    }
</style>

<div class="olena-notification-wrapper">
    <div class="olena-notification-image">
        <img src="https://olena-theme.com.ua/wp-content/themes/olena/assets/images/logo.png" alt="<?php echo esc_attr__('Get Olena Theme', 'olena'); ?>" />
    </div>
    <div class="olena-notification-content">
        <h3><?php echo esc_html__('Thanks for using MX Time Zone Clocks plugin!', 'olena'); ?></h3>
        <p><?php echo esc_html($message); ?></p>

        <div>
            <iframe width="560" height="315" src="https://www.youtube.com/embed/XVh9RvAKRWo?si=0pf9m27-tf3hqoxS" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>

        <a href="https://olena-theme.com.ua/" target="_blank"><?php echo esc_html__('View live demo of Olena theme', 'olena'); ?></a>
        <div class="olena-notification-install-button-wrapp">
            <a class="olena-notification-install-button" href="<?php echo esc_attr($button_url); ?>" target="_blank"><?php echo esc_html($button_text); ?></a>
        </div>
    </div>
</div>

<script>
    jQuery(function($) {
        $('.notice.olena-notification').on('click', 'button.notice-dismiss', function(event) {
            event.preventDefault();

            $.post(ajaxurl, {
                action: 'olena_theme_notice_viewed'
            });
        });
    });
</script>