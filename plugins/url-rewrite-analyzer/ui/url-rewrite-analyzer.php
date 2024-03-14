<?php $option = get_option( 'urap-ui-style' ); ?>
<div class="wrap">
    <div class="modal-change-ui">
        <form action="" method="post">
            <div class="input-wrapper">
                <label class="ui-select <?php echo $option === 'light' ? 'selected' : ''; ?>" for="light"><?php _e( 'Light', 'url-rewrite-analyzer' ); ?></label>
                <input type="radio" name="ui" id="light" value="light">
            </div>
            <div class="input-wrapper">
                <label class="ui-select <?php echo $option === 'dark' ? 'selected' : ''; ?>" for="dark"><?php _e( 'Dark', 'url-rewrite-analyzer' ); ?></label>
                <input type="radio" name="ui" id="dark" value="dark">
            </div>
        </form>
    </div>
    <div class="head-wrapper">
        <h2><?php _e( 'Url Rewrite Analyzer', 'url-rewrite-analyzer' ); ?></h2>
        <button id="update-ui">
            <span><?php _e( 'Change UI', 'url-rewrite-analyser' ); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-image"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>        </button>
        <button id="flush-rewrite-rules">
            <span><?php _e( 'Flush permalinks', 'url-rewrite-analyzer' ); ?></span>
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-refresh-cw"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg>
        </button>
    </div>

    <?php if ( !$rewrite_rules ) : ?>
        <div class="error"><p>
        <?php
        printf(
            __(
                'Pretty permalinks are disabled, you can change this on <a href="%s">the Permalinks settings page</a>.',
                'url-rewrite-analyzer'
            ),
            admin_url( 'options-permalink.php' )
        ); ?></p></div>
    <?php else : ?>

    <div id="_regex-search-bar">
        <div class="prefix-url"><?php echo $url_prefix; ?></div>
        <input type="text" name="" id="regex-tester" placeholder="...">
        <button class="clear">
            <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
    </div>

    <table class="widefat fixed" cellspacing="0">
        <thead>
            <tr>
                <th><?php _e( 'Pattern', 'url-rewrite-analyzer' ); ?></th>
                <th><?php _e( 'Substitution', 'url-rewrite-analyzer' ); ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ( $rewrite_rules_ui as $idx => $rewrite_rule_ui ) : ?>
            <tr id="rewrite-rule-<?php echo $idx; ?>" class="rewrite-rule-line">
                <?php if ( array_key_exists( 'error', $rewrite_rule_ui ) ) : ?>
                    <td colspan="2">
                        <?php echo $rewrite_rule_ui['pattern']; ?>
                        <p class="error"><?php printf( __( 'Error parsing regex: %s', 'url-rewrite-analyzer' ), $rewrite_rule_ui['error'] ); ?></p>
                    </td>
                <?php else : ?>
                    <td><?php echo $rewrite_rule_ui['print']; ?></td>
                    <td>
                        <?php
                        foreach ( $rewrite_rule_ui['substitution_parts'] as $substitution_part_ui ) {
                            if ( $substitution_part_ui['is_public'] ) {
                                echo '<span class="queryvar-public">';
                            } else {
                                echo '<span class="queryvar-unread" title="' . esc_attr( __( 'This query variable is not public and will not be saved', 'url-rewrite-analyzer' ) ) . '">';
                            }
                                printf( "%' 15s: <span class='queryvalue'>%s</span>\n", $substitution_part_ui['query_var'], $substitution_part_ui['query_value_ui'] );
                                echo '</span>';
                        } ?>
                    </td>
                <?php endif; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php endif; ?>
</div>

<div class="urap-btt-wrapper">
    <button>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>                   
    </button>
</div>
