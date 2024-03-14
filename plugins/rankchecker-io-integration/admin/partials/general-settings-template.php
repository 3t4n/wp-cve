<?php
$domain = Rankchecker_Api::get_instance()->get_domain( get_option( 'rc_domain_id' ) );

?>
<div class="wrap">

    <h1>Rankchecker Settings</h1>

    <div>
        <h4>Step 1. Select your API Key</h4>

        <form method="post" action="/wp-admin/admin-ajax.php" id="rc_save_api_key">
            <input type="hidden" name="action" value="rc_save_api_key">
            <input required type="password" name="rc_api_key" placeholder="Rankchecker API Token" value="<?= get_option( 'rc_api_key' ) ?>">
            <button type="submit" class="button button-primary">Save</button>
        </form>

        <p style="display: none;" class="rc-alert" id="alert_rc_save_api_key"></p>
    </div>

    <div>
        <h4>Step 2. Connect your site to Rankchecker.io</h4>

		<?php if ( ! is_wp_error( $domain ) ) : ?>

            <p>Domain connected to <?= $domain[ 'name' ] ?> (<?= $domain[ 'address' ] ?>)</p>

            <form method="get" action="/wp-admin/admin-ajax.php" id="rc_attempt_connect_domain">
                <input type="hidden" name="action" value="rc_attempt_connect_domain">
                <button type="submit" class="button button-primary">Reconnect</button>
            </form>

		<?php else: ?>

            <form method="get" action="/wp-admin/admin-ajax.php" id="rc_attempt_connect_domain">
                <input type="hidden" name="action" value="rc_attempt_connect_domain">
                <button type="submit" class="button button-primary">Connect</button>
            </form>

		<?php endif; ?>

        <p style="display: none;" class="rc-alert" id="alert_rc_attempt_connect_domain"></p>

    </div>

	<?php if ( ! is_wp_error( $domain ) && ! $domain[ 'badge' ][ 'status' ] ) : ?>
        <div>
            <h4>Step 3. Your Domain Connected. Visit your Rankchecker Dashboard and recheck badge status</h4>
            <a href="https://rankchecker.io/user/domains/<?= $domain['id']; ?>" target="_blank" class="button button-primary">Rankchecker Dashboard</a>
        </div>
	<?php endif; ?>


</div>

<script>
    jQuery(document).ready(function ($) {

        // Step 1
        $('#rc_save_api_key').submit(function (e) {

            e.preventDefault();

            $('#alert_rc_save_api_key').fadeOut();

            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {
                        $('#alert_rc_save_api_key').html('API Token saved successfully!').fadeIn();
                    } else {
                        $('#alert_rc_save_api_key').html(response.data).fadeIn();
                    }
                }
            });

        });

        // Step 2
        $('#rc_attempt_connect_domain').submit(function (e) {

            e.preventDefault();

            $('#alert_rc_attempt_connect_domain').fadeOut();

            $.ajax({
                url: '/wp-admin/admin-ajax.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function (response) {
                    if (response.success) {

                        $('#alert_rc_attempt_connect_domain').html('Domain Connected successfully!').fadeIn();

                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);

                    } else {
                        $('#alert_rc_attempt_connect_domain').html(response.data).fadeIn();
                    }
                }
            });

        });

    });
</script>