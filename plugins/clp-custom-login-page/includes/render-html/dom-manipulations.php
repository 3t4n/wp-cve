<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$logo_contained = get_option( 'clp_logo-contained', false );

$back_to_blog = get_option('clp_form_footer-backtoblog_text', sprintf( _x( '&larr; Back to %s', 'site' ), get_bloginfo( 'title', 'display' ) ));
$back_to_blog = apply_filters('clp_translate_string', $back_to_blog, 'Footer - Back To Blog', CLP_Helper_Functions::get_locale());

ob_start(); ?>

<script>
    (function() {
        // display logo inside or outside of form container
        const logo = document.querySelector('.clp-login-logo');
        const logoContainer = document.querySelector('<?php echo $logo_contained ? '#login' : '.clp-login-form-container';?>');
        logo ? logoContainer.prepend(logo) : null;

        const form = document.querySelector('.clp-login-form-container form');
        // wrap footer elements 
        const nav = document.querySelector('#nav');
        const backtoblog = document.querySelector('#backtoblog');
        const privacyLink = document.querySelector('.privacy-policy-page-link');
        const formFooter = document.querySelector('.clp-form-footer-html');
        const footerWrapper = document.createElement('div');
        footerWrapper.classList.add('clp-form-footer');
        form.parentNode.insertBefore(footerWrapper, form.nextSibling);
        formFooter ? footerWrapper.appendChild(formFooter) : null;
        nav ? footerWrapper.appendChild(nav) : null;
        backtoblog ? footerWrapper.appendChild(backtoblog) : null;
        privacyLink ? footerWrapper.appendChild(privacyLink) : null;

        // change back to site text
        const backTo = document.querySelector('#backtoblog > a');
        backTo ? backTo.innerHTML = '<?php echo esc_html( $back_to_blog );?>' : null;

        <?php 
        // replace checkbox with custom HTML
        if (get_option('clp_input-remember', true)) { ?>
        const forgetLabel = document.querySelector('.forgetmenot label');
        const rememberCheckbox = document.getElementById('rememberme');
        const spanCheckbox = document.createElement('span');
        spanCheckbox.classList.add('checkmark');

        if (forgetLabel) {
            forgetLabel.appendChild(rememberCheckbox);
            forgetLabel.appendChild(spanCheckbox);
        }
        <?php 
        } ?>
    })();
</script>

<?php do_action('clp_add_login_form_footer_js_before_loaded');?>

<?php echo get_option('clp_layout-width', '100') != '100' ? CLP_Render_HTML::form_width_fix() : null; ?>
<script>
    const body = document.querySelector('body.login');
    body.classList.add('loaded');
</script>

<?php 
do_action('clp_add_login_form_footer_js_after_loaded');

$html = ob_get_clean();