<?php // phpcs:ignoreFile ?>
<?php if (!empty($errors) && is_array($errors)) : ?>
    <?php foreach ($errors as $err) : ?>
        <div id="error" class="notice <?php if((empty($success) || !$success)) { ?>notice-error <?php } else { ?> notice-success <?php    } ?>notice-newsletters is-dismissible" data-notice="" bis_skin_checked="1">
            <p><i class="fa <?php if((empty($success) || !$success)) { ?>fa-times <?php } else { ?> fa-check <?php    } ?> fa-fw"></i><?php echo wp_kses_post($err); ?></p>
            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>