<?php $_poll = gdpol_get_poll(); ?>

<form method="post" action="<?php echo $_poll->url( 'gdpol-submit-vote' ); ?>">

	<?php $_poll->render_form_choices(); ?>

    <button type="submit" name="gdpol_poll_submit"><?php _e( 'Submit vote', 'gd-topic-polls' ); ?></button>

	<?php $_poll->render_form_fields(); ?>

</form>

<script type="text/javascript">
    jQuery(document).ready(function() {
        window.wp.gdpol.form.run(<?php echo $_poll->id; ?>);
    });
</script>
