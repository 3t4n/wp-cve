<div id="sticky-social-icons-container" class="<?php echo 'design-' . $design . ' alignment-' . $alignment;
                                                if ($enable_animation) echo ' with-animation';
                                                if ($hide_in_mobile) echo ' hide-in-mobile'; ?>">
	<ul>
		<?php
        $tooltip_alignment = $alignment == 'left' ? 'right' : 'left';

        foreach (json_decode($icons_data) as $icon) {
            $selected_icon = json_decode($icon);


            echo '<li  class="' . str_replace(' ', '-', $selected_icon->icon) . '" len="' . strlen($selected_icon->tooltip_label) . '">';
            echo '<a href="' . $selected_icon->url . '"  ';
            echo ($selected_icon->new_tab ? ' target="_blank" ' : ' ');
            echo ' class="' . str_replace(' ', '-', $selected_icon->icon) . '" ';
            echo ($enable_tooltip && strlen($selected_icon->tooltip_label) > 0 ? ' aria-label="' . ($selected_icon->tooltip_label ? $selected_icon->tooltip_label : '') . '" data-microtip-position="' .  $tooltip_alignment . '" role="tooltip" ' : ' ');
            echo '>';
            echo '<i class="' . $selected_icon->icon . '" ></i>';
            echo '</a></li>';
        }
        ?>
	</ul>
</div>