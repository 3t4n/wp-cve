<?php

/**
 * @author DemonIa sanchoclo@gmail.com
 * @function mightyforms_run_application
 * @description Render main plugin page, that contain
 * forms list and Mightyforms application (in tabs)
 * @param
 * @returns void
 */


function mightyforms_run_application()
{

    $iframe_src = '';
    $api_key = get_option('mightyforms_api_key');

    if ($api_key) {
        $iframe_src = 'https://app.mightyforms.com?app=wordpress&utm_source=wordpress&utm_medium=affiliate';
    } else {
        $iframe_src = 'https://app.mightyforms.com/login?guest=true&app=wordpress&utm_source=wordpress&utm_medium=affiliate';
    } ?>

    <div class="mf-main-block">
        <div class="mf-message-box">
            <p>
            Look's like you're using Safari. In some cases you may face with signing in problems.
            To resolve it, go to Safari &rarr; Preferences &rarr; Privacy and uncheck "Prevent cross-site tracking"</p>
            <span>&#10005;</span>
        </div>
        <div class="application-box">
            <iframe id="mf" src="<?php echo $iframe_src; ?>" frameborder="0" style="width: 100%;"></iframe>
        </div>
    </div>
<?php
}

add_action('wp_ajax_upsert_user_api_key', 'upsert_user_api_key');

/**
 * @author DemonIa sanchoclo@gmail.com
 * @function upsert_user_api_key
 * @description Needed for set or update user api key in database
 * @param
 * @returns void
 */

function upsert_user_api_key()
{
    try {
        $api_key = esc_sql($_POST['userApiKey']);

        update_option('mightyforms_api_key', $api_key);

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }

    wp_die();
}
