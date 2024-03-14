<?php do_action( 'isell_download_page_before' ); ?>

<?php if ( $show ): ?>

<p><?php echo $other_text; ?> <a href="<?php echo $download_link ?>" /><?php echo $link_text ?></a></p>

<?php endif; ?>

<?php if ( $auto_start ): ?>

<script>window.location="<?php echo $download_link ?>";</script>

<?php endif; ?>

<?php do_action( 'isell_download_page_after' ); ?>