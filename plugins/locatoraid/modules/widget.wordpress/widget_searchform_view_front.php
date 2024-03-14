<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$search_value = isset($_GET['lpr-search']) ? $_GET['lpr-search'] : '';
?>
<section class="widget locatoraid-search-widget">
<form action="<?php echo $locator_page; ?>" method="get" role="search" class="search-form">
<label for="lpr-search"><?php echo esc_html($label); ?></label>
<input type="text" placeholder="<?php echo esc_attr($label); ?>" name="lpr-search" value="<?php echo esc_attr($search_value); ?>" id="lpr-search" class="search-field" />
<input type="submit" value="<?php echo $btn; ?>" class="search-submit" />
</form>
</section>