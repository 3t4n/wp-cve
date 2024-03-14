<table class="form-table">
    <tbody>
        <tr>
            <th scope="row">
                <label for="">Agent Name</label>
            </th>
            <td>
                <input type="text" name="agent_name" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'agent_name', true ) ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Agent Title</label>
            </th>
            <td>
                <input type="text" name="agent_title" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'agent_title', true ) ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Agent Type</label>
            </th>
            <td>
                <?php $agent_type = get_post_meta( $post->ID, 'agent_type', true ); ?>
                <?php if ( ! $agent_type ) : ?>
                    <p><input type="radio" name="agent_type" value="number" checked="checked"> WhatsApp Number</p>
                <?php else: ?>
                    <p><input type="radio" name="agent_type" value="number" <?php checked( 'number', $agent_type ); ?> > WhatsApp Number</p>
                <?php endif; ?>
                <p><input type="radio" name="agent_type" value="group" <?php checked( 'group', $agent_type ); ?> > WhatsApp Group</p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Agent WhatsApp Number</label>
            </th>
            <td>
                <input type="number" name="agent_number" class="regular-text" step="1" value="<?php echo esc_attr( get_post_meta( $post->ID, 'agent_number', true ) ); ?>">
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Agent WhatsApp Group ID</label>
            </th>
            <td>
                <input type="text" name="agent_group_id" class="regular-text" value="<?php echo esc_attr( get_post_meta( $post->ID, 'agent_group_id', true ) ); ?>">
                <p class="description">Enter WhatsApp Group ID</p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Pre-defined Message</label>
            </th>
            <td>
                <?php
                    $pre_defined_message = get_post_meta( $post->ID, 'pre_defined_message', true );

                    if ( ! $pre_defined_message ) {
                        $pre_defined_message = 'Hi, I was visiting {{url}} and I want more information…';
                    }
                ?>
                <textarea name="pre_defined_message" class="regular-text" style="height:120px;"><?php echo esc_textarea( $pre_defined_message ); ?></textarea>
                <p class="description">Use <code>{{url}}</code> for display current URL.</p>
            </td>
        </tr>

        <?php if ( get_post_meta( $post->ID, 'agent_number', true ) ) : ?>
        <tr>
            <td colspan="2">
                <hr>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="">Share Agent Link</label>
            </th>
            <td>
                <?php
                    $agent_number = get_post_meta( $post->ID, 'agent_number', true );

                    echo wp_sprintf(
                        '<input type="text" class="regular-text" value="%s" readonly="readonly" />',
                        esc_attr( add_query_arg( 'tochatbe_agent_share', $agent_number, home_url( '/' ) ) )
                    );
                ?>
                <p class="description">You can copy and share the link with users/ customers.</p>
            </td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="tochatbe-ad-box">
    <p>Do you use <a href="https://texts.com/" target="_blank">texts.com</a> as your inbox. Now you can send all your leads from your WhatsApp widget. <a href="https://tochat.be/click-to-chat/2023/10/25/texts-com-inbox-for-wordpress/" target="_blank">Learn more.</a><br />This is the best widget for yout <a href="https://texts.com/" target="_blank">texts.com</a> inbox. We will send all your leads to your inbox.</p>
</div>