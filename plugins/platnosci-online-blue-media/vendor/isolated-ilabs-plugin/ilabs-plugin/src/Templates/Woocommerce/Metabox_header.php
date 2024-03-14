<?php

declare (strict_types=1);
namespace {
    /**
     * @var string $metabox_header
     */
    ?>

<div class="ilabs-metabox-panel ilabs-form">
    <h3 class="ilabs-metabox-header ilabs-form-name ilabs-form-<?php 
    echo \sanitize_title($metabox_header);
    ?>">
		<?php 
    \esc_html_e($metabox_header);
    ?>
    </h3>
<?php 
}
