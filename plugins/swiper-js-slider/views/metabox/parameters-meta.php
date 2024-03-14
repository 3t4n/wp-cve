<?php

$general    = get_post_meta($post->ID, $this->meta_prefix.'setting_general', true);

$general = array(
    "initialSlide"          => $this->s_j_s_checkit('initialSlide', $general, '0'),
    "direction"             => $this->s_j_s_checkit('direction', $general, 'horizontal'),
    "speed"                 => $this->s_j_s_checkit('speed', $general, '300'),
    "autoHeight"            => $this->s_j_s_checkit('autoHeight', $general, 'false'),
    "effect"                => $this->s_j_s_checkit('effect', $general, 'slide'),
    "slidesPerView"         => $this->s_j_s_checkit('slidesPerView', $general, '1'),
    "slidesPerColumn"       => $this->s_j_s_checkit('slidesPerColumn', $general, '1'),
    "spaceBetween"          => $this->s_j_s_checkit('spaceBetween', $general, '0'),
);

?>

<div id="sjs-tab-parameters" class="tabSec generalOptions">
    <table class="autoPlaySettings">
        <tr data-filter-item data-filter-name="initialSlide InitialSlide initialslide initial Initial slide Slide">
            <td>
                <label>initialSlide</label>
            </td>
            <td>
                <span>Index number of initial slide.</span>
                <input 
                    id="initialSlide"
                    name="<?php echo $this->meta_prefix.'setting_general[initialSlide]'; ?>"
                    type="number" 
                    min="0"
                    value="<?php echo $general['initialSlide']; ?>">
            </td>
        </tr>
        <tr data-filter-item data-filter-name="direction Direction">
            <td>
                <label for="">direction</label> 
            </td>
            <td>
                <span>Could be 'horizontal' or 'vertical' (for vertical slider).</span>
                <select name="<?php echo $this->meta_prefix.'setting_general[direction]'; ?>">
                    <option value="horizontal" <?php selected($general['direction'], 'horizontal') ?>>horizontal</option>
                    <option value="vertical" <?php selected($general['direction'], 'vertical') ?>>vertical</option>
                </select>
            </td>
        </tr>
        <tr data-filter-item data-filter-name="speed Speed">
            <td>
                <label for="">speed</label> 
            </td>
            <td>
                <span>Duration of transition between slides (in ms)</span>
                <input 
                    id="speed"
                    name="<?php echo $this->meta_prefix.'setting_general[speed]'; ?>"
                    type="number"
                    min="0" 
                    step="100"
                    value="<?php echo $general['speed']; ?>">
            </td>
        </tr>
        <tr data-filter-item data-filter-name="autoHeight AutoHeight autoheight auto Auto height Height">
            <td>
                <label for="">autoHeight</label> 
            </td>
            <td>
                <span>Set to true and slider wrapper will adopt its height to the height of the currently active slide</span>
                <ul>
                    <li>
                        <label>
                            <input 
                                name="<?php echo $this->meta_prefix.'setting_general[autoHeight]'; ?>"
                                type="radio" 
                                <?php checked( $general['autoHeight'], 'true' ); ?>
                                value="true">True
                        </label>
                    </li>
                    <li>
                        <label>
                            <input 
                                name="<?php echo $this->meta_prefix.'setting_general[autoHeight]'; ?>"
                                type="radio" 
                                <?php checked( $general['autoHeight'], 'false' ); ?>
                                value="false">False
                        </label>
                    </li>
                </ul>
            </td>
        </tr>
        <tr data-filter-item data-filter-name="spaceBetween SpaceBetween spacebetween space Space between Between">
            <td>
                <label for="">spaceBetween</label> 
            </td>
            <td>
                <span>Distance between slides in px.</span>
                <input 
                    id="spaceBetween"
                    name="<?php echo $this->meta_prefix.'setting_general[spaceBetween]'; ?>"
                    type="number"
                    min="0" 
                    value="<?php echo $general['spaceBetween']; ?>">
            </td>
        </tr>
        <tr data-filter-item data-filter-name="effect Effect">
            <td>
                <label for="">effect</label> 
            </td>
            <td>
                <span>Tranisition effect.</span>
                <select name="<?php echo $this->meta_prefix.'setting_general[effect]'; ?>">
                    <option value="slide" <?php selected($general['effect'], 'slide') ?>>slide</option>
                    <option value="fade" <?php selected($general['effect'], 'fade') ?>>fade</option>
                    <option value="cube" <?php selected($general['effect'], 'cube') ?>>cube</option>
                    <option value="coverflow" <?php selected($general['effect'], 'coverflow') ?>>coverflow</option>
                    <option value="flip" <?php selected($general['effect'], 'flip') ?>>flip</option>
                </select>
            </td>
        </tr>
    </table>
</div>