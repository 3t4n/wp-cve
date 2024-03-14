<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<?php
$hbs = stm_get_hb_variants();
$hb = stm_hb_save_name();



$current_hb = (!empty($hbs[$hb])) ? $hb : '';
update_option('stm_last_edited_hb', $current_hb);
?>

<div class="manage-menus">
    <form method="get"
          action="<?php echo esc_url(esc_url(add_query_arg(array('page' => 'pearl_header_builder'), admin_url('admin.php')))); ?>">
        <label for="select-menu-to-edit" class="selected-menu">
			<?php esc_html_e('Select a header to edit:', 'pearl-header-builder'); ?>
        </label>
        <input type="hidden" name="page" value="stm_header_builder">
        <select name="hb" id="select-menu-to-edit">
			<?php foreach ($hbs as $value => $name): ?>
                <option value="<?php echo sanitize_title($value); ?>" <?php selected($value, $hb); ?>>
					<?php echo wp_kses_post($name); ?>
                </option>
			<?php endforeach; ?>
        </select>
        <span class="submit-btn"><input type="submit" class="button" value="<?php esc_html_e('Select'); ?>"></span>
        <span class="add-new-menu-action">
            <?php printf(__('or <a href="%s">create a new header</a>.', 'pearl-header-builder'), esc_url(add_query_arg(array('hb' => $hb, 'page' => 'pearl_header_builder'), admin_url('admin.php')))); ?>
        </span>
    </form>

    <form method="get"
          action="<?php echo esc_url(esc_url(add_query_arg(array('page' => 'pearl_header_builder'), admin_url('admin.php')))); ?>">

        <div class="stm_hb_add_new">
            <input name="hb" type="text" placeholder="<?php esc_html_e('Enter header name', 'pearl-header-builder'); ?>"/>
            <input type="hidden" name="page" value="stm_header_builder">
            <button class="md-raised md-primary md-button md-ink-ripple" type="submit">
				<?php esc_html_e('Add new header', 'pearl-header-builder'); ?>
            </button>
        </div>

    </form>

    <label class="stm_to_get_hb_code__label">
		<?php printf(__('Insert shortcode or action to display your builded header in <a href="%s" target="_blank">header template</a>', 'pearl-header-builder'), add_query_arg(array('file' => 'header.php'), admin_url('theme-editor.php'))); ?>
    </label>



    <div class="stm_to_get_hb_code">
        <pre>&lt;!--<?php echo sanitize_text_field('Header builder BEGIN'); ?>--&gt;
&lt;?php <?php echo wp_specialchars_decode("do_action('stm_hb', array('header' => '{$hb}'));"); ?> ?&gt;
&lt;!--<?php echo sanitize_text_field('Header builder END'); ?>--&gt;
        </pre><?php esc_html_e('OR', 'stm_hb'); ?>
        <pre>&lt;!--<?php echo sanitize_text_field('Header builder BEGIN'); ?>--&gt;
&lt;?php <?php echo wp_specialchars_decode("echo do_shortcode(\"[stm_hb header='{$hb}']\");"); ?> ?&gt;
&lt;!--<?php echo sanitize_text_field('Header builder END'); ?>--&gt;
    </div>

</div>

<script>
    jQuery(document).ready(function () {
		<?php if(!empty($_GET['delete_hb'])):
		$args = array(
            'page' => 'stm_header_builder',
        );
		?>
        window.history.pushState('', '', '<?php echo add_query_arg($args, admin_url()); ?>');
		<?php endif; ?>
    });
</script>