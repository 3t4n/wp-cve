<?php
if ( $visibility !== 'yes' ) {
	echo '<div class="lrw-empty-space" style="height: ' . $height . 'px; min-height: ' . $height . 'px;"></div>';
} else {
	echo '<div class="lrw-empty-space" data-height="' . $height . '" data-break-desktop="' . $desktop . '" data-height-desktop="' . $d_height . '" data-break-tablet="' . $tablet . '" data-height-tablet="' . $t_height . '" data-break-phone="' . $phone . '" data-height-phone="' . $p_height . '"></div>';
}
