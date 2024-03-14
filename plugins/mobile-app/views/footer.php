<?php
/** @var bool $show_form */
/** @var string $active_tab */

if ( ! empty( $show_form ) ) { ?>
	<?php do_action( 'canvas_settings', $active_tab ); ?>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
	<?php
}
?>

</div>
</div><!--#wrap-->
