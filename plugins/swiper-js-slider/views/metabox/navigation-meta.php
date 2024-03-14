<?php 

$navigation = get_post_meta($post->ID, $this->meta_prefix.'setting_navigation', true);
        
$navigation = array(
    "navigation"            => $this->s_j_s_checkit('navigation', $navigation, 'true'),
    "nextEl"                => $this->s_j_s_checkit('nextEl', $navigation, 'swiper-button-next'),
    "prevEl"                => $this->s_j_s_checkit('prevEl', $navigation, 'swiper-button-prev'),
    "disabledClass"         => $this->s_j_s_checkit('disabledClass', $navigation, 'swiper-button-disabled'),
    "hiddenClass"           => $this->s_j_s_checkit('hiddenClass', $navigation, 'swiper-button-hidden'),
);

?>

<div id="sjs-tab-navigation" class="tabSec navigation">
    <table>
        <tbody>
            <tr data-filter-item data-filter-name="Navigation navigation">
                <td>
                    <label>Navigation</label>
                </td>
                <td>
                    <ul>
                        <li>
                            <label>
                                <input 
                                    type="radio" 
                                    name="<?php echo $this->meta_prefix.'setting_navigation[navigation]'; ?>" 
                                    value="true" 
                                    <?php checked( $navigation['navigation'], 'true' ); ?>> True
                            </label>
                        </li>
                        <li>
                            <label>
                                <input 
                                    type="radio" 
                                    name="<?php echo $this->meta_prefix.'setting_navigation[navigation]'; ?>" 
                                    value="false" 
                                    <?php checked( $navigation['navigation'], 'false' ); ?>> False
                            </label>
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="<?php echo ($navigation['navigation'] == 'false' ? 'hiddenData' : '' ) ?>">
        <table class="autoPlaySettings">
            <tr data-filter-item data-filter-name="nextEl NextEl nextel next Next el El Arrow arrow">
                <td>
                    <label for="nextEl">nextEl</label> 
                </td>
                <td>
                    <span>String with CSS selector or HTML element of the element that will work like "next" button after click on it</span>
                    <input 
                            id="nextEl"
                            name="<?php echo $this->meta_prefix.'setting_navigation[nextEl]'; ?>"
                            type="text" 
                            value="<?php echo $navigation['nextEl']; ?>">
                </td>
            </tr>
            <tr data-filter-item data-filter-name="prevEl PrevEl prevel prev Prev el El Arrow arrow">
                <td>
                    <label for="prevEl">prevEl</label> 
                </td>
                <td>
                    <span>String with CSS selector or HTML element of the element that will work like "prev" button after click on it</span>
                    <input 
                            id="prevEl"
                            name="<?php echo $this->meta_prefix.'setting_navigation[prevEl]'; ?>"
                            type="text" 
                            value="<?php echo $navigation['prevEl']; ?>">
                </td>
            </tr>
            <tr data-filter-item data-filter-name="disabledClass DisabledClass disabledclass disabled Disabled Class class">
                <td>
                    <label for="disabledClass">disabledClass</label> 
                </td>
                <td>
                    <span>CSS class name added to navigation button when it becomes disabled</span>
                    <input 
                            id="disabledClass"
                            name="<?php echo $this->meta_prefix.'setting_navigation[disabledClass]'; ?>"
                            type="text" 
                            value="<?php echo $navigation['disabledClass']; ?>">
                </td>
            </tr>
            <tr data-filter-item data-filter-name="hiddenClass HiddenClass hiddenclass hidden Hidden class Class">
                <td>
                    <label for="hiddenClass">hiddenClass</label> 
                </td>
                <td>
                    <span>CSS class name added to navigation button when it becomes hidden</span>
                    <input 
                            id="hiddenClass"
                            name="<?php echo $this->meta_prefix.'setting_navigation[hiddenClass]'; ?>"
                            type="text" 
                            value="<?php echo $navigation['hiddenClass']; ?>">
                </td>
            </tr>
        </table>
    </div>
</div>