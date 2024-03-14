<?php

declare (strict_types=1);
namespace {
    /**
     * @var string $group_id
     * @var string $group_header
     */
    ?>

<div id="ilabs-metabox-group-nazwa"
     class="ilabs-metabox-group-<?php 
    echo \sanitize_title($group_header);
    ?> panel woocommerce_options_panel">
    <h4 class="ilabs-metabox-group-header">
		<?php 
    \esc_html_e($group_header);
    ?>
    </h4>
<?php 
}
