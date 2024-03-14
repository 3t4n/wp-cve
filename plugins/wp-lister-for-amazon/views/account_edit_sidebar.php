<?php
$market = new WPLA_AmazonMarket( $wpl_account->market_id );
$oauth = $market->getOAuthUrl();

$return_to = !empty( $_GET['return_to'] ) ? $_GET['return_to'] : '';
?>
<style type="text/css">

	#side-sortables .postbox input.text_input,
	#side-sortables .postbox select.select {
	    width: 45%;
	}
	#side-sortables .postbox label.text_label {
	    width: 50%;
	}

	#side-sortables .postbox .inside p.desc {
		margin-left: 2%;
	}

</style>

<!-- first sidebox -->
<div class="postbox" id="submitdiv">
    <!--<div title="Click to toggle" class="handlediv"><br></div>-->
    <h3 class="hndle"><span><?php echo __( 'Update', 'wp-lister-for-amazon' ); ?></span></h3>
    <div class="inside">

        <div id="submitpost" class="submitbox">

            <div id="misc-publishing-actions">
                <div class="misc-pub-section">
                    <p>
                        <?php echo __( 'Please don\'t change any account details except for title and brand registry option.', 'wp-lister-for-amazon' ); ?>
                    </p>
                </div>
            </div>

            <div id="major-publishing-actions">
                <div id="publishing-action">
                    <input type="hidden" name="action" value="wpla_save_account" />
                    <?php wp_nonce_field( 'wpla_save_account' ); ?>
                    <input type="hidden" name="wpla_account_id" value="<?php echo $wpl_account->id; ?>" />
                    <input type="hidden" name="return_to" value="<?php echo wpla_clean_attr( $return_to ); ?>" />
                    <input type="submit" value="<?php echo __( 'Update', 'wp-lister-for-amazon' ); ?>" id="publish" class="button-primary" name="save">
                </div>
                <div class="clear"></div>
            </div>

        </div>

    </div>
</div>

<div class="postbox" id="OAuthTokenBox">
    <h3 class="hndle"><span><?php echo __( 'OAuth Access', 'wp-lister-for-amazon' ); ?></span></h3>
    <div class="inside">


        <p>
            <?php echo __( 'Click "Login with Amazon" to sign in to Amazon and grant access for WP-Lister', 'wp-lister-for-amazon' ) ?>
        </p>
        <p>
            <a id="btn_connect" href="<?php echo $oauth; ?>" class="button-primary" target="_blank">Login with Amazon</a>
        </p>
        <p>
            <small>This will open the Sign In page in a new window.</small>
            <small>Please sign in, grant access for WP-Lister and close the new window to come back here and click the button below.</small>
        </p>
        <p>
            <?php echo __( 'After linking WP-Lister with your Amazon account, click here to fetch your token', 'wp-lister-for-amazon' ) ?>
        </p>
        <p>
            <a id="btn_fetch_token" href="<?php echo $wpl_form_action; ?>&amp;action=wpla_fetch_oauth_token&amp;account_id=<?php echo $wpl_account->id ?>&_wpnonce=<?php echo wp_create_nonce( 'wpla_fetch_oauth_token' ); ?>" class="button-secondary"><?php echo __( 'Fetch Token', 'wp-lister-for-amazon' ) ?></a>
        </p>

    </div>
</div>

<div class="postbox dev_box" id="sandbox_postbox" style="display:none;">
    <h3 class="hndle"><span><?php echo __( 'Sandbox Mode', 'wp-lister-for-amazon' ); ?></span></h3>
    <div class="inside">


        <p>
            <?php echo __( 'Enable Sandbox Mode', 'wp-lister-for-amazon' ) ?>
        </p>
        <p>
            <select name="wpla_sandbox_mode">
                <option value="0" <?php echo selected( 0, $wpl_account->sandbox_mode ); ?>>Production Mode</option>
                <option value="1" <?php echo selected( 1, $wpl_account->sandbox_mode ); ?>>Sandbox Mode</option>
            </select>
        </p>

    </div>
</div>