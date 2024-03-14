<div id="tw-body">  
    <div class="tw-box-wrapper"> 
        <div class="logo-wrap">
            <div class="tw-logo"></div> 
        </div>
        <?php
            if ( isset( $this->message ) ) {
                ?>
                <div class="tw-success"><?php echo esc_html($this->message); ?></div>
                <?php
            }
            if ( isset( $this->errorMessage ) ) {
                ?>
                <div class="tw-error"><?php echo esc_html($this->errorMessage); ?></div>
                <?php
            }
        ?>
        <form id="tw-form" action="options-general.php?page=translatewise-chat" method="post">
            <label>Client key:</label>   
            <input type="text" name="tw-client-key" id="tw-client-key" placeholder="Enter your client key"  <?php echo ( ! current_user_can( 'unfiltered_html' ) ) ? ' disabled="disabled" ' : ''; ?> value = "<?php echo esc_attr($this->settings['tw-client-key']); ?>"></input>
            <label>Active:</label>   
            <input type="checkbox" name="tw-chat-enabled" id="tw-chat-enabled" <?php echo ( ! current_user_can( 'unfiltered_html' ) ) ? ' disabled="disabled" ' : ''; ?> value = "1"  <?php echo checked( 1, $this->settings['tw-chat-enabled'], false ); ?>></input>
            <?php if ( current_user_can( 'unfiltered_html' ) ) : ?>
                <?php wp_nonce_field( $this->plugin->name, $this->plugin->name . '_nonce' ); ?>
                <button>Save</button> 
            <?php endif; ?> 

            <p class="tw-extra-info">
                You need a Askly chat account to access the ‘client key’ and add chat to your website. If you have an account sign in <a href="https://chat.askly.me/sign-in"  target="_blank" >here</a>. Just starting?
                Create your account <a href="https://chat.askly.me/register"  target="_blank" >here</a>.
            </p>
        </form> 
    </div> 
</div>
