<?php 

$autoplay   = get_post_meta($post->ID, $this->meta_prefix.'setting_autoplay', true);

$autoplay = array(
    "autoplay"             => $this->s_j_s_checkit('autoplay', $autoplay, 'true'),
    "delay"                => $this->s_j_s_checkit('delay', $autoplay, '3000'),
    "stopOnLastSlide"      => $this->s_j_s_checkit('stopOnLastSlide', $autoplay, 'false'),
    "disableOnInteraction" => $this->s_j_s_checkit('disableOnInteraction', $autoplay, 'true'),
    "reverseDirection"     => $this->s_j_s_checkit('reverseDirection', $autoplay, 'false'),
    "waitForTransition"    => $this->s_j_s_checkit('waitForTransition', $autoplay, 'true')
);

?>

<div id="sjs-tab-autoplay" class="tabSec autoPlay">
    <table>
        <tbody>
            <tr data-filter-item data-filter-name="Autoplay autoplay autoplay Auto Play auto play">
                <td>
                    <label>Autoplay</label>
                </td>
                <td>
                    <ul>
                        <li>
                            <label>
                                <input 
                                    type="radio" 
                                    name="<?php echo $this->meta_prefix.'setting_autoplay[autoplay]'; ?>" 
                                    value="true" 
                                    <?php checked( $autoplay['autoplay'], 'true' ); ?>> True
                            </label>
                        </li>
                        <li>
                            <label>
                                <input 
                                    type="radio" 
                                    name="<?php echo $this->meta_prefix.'setting_autoplay[autoplay]'; ?>" 
                                    value="false" 
                                    <?php checked( $autoplay['autoplay'], 'false' ); ?>> False
                            </label>
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>
    
    <div class="<?php echo ($autoplay['autoplay'] == 'false' ? 'hiddenData' : '' ) ?>">
        <table class="autoPlaySettings">
            <tbody>
                <tr data-filter-item data-filter-name="Delay delay">
                    <td>
                        <label for="apDelay">Delay</label>
                    </td>
                    <td>
                        <span>Delay between transitions (in ms).</span>
                        <input 
                            id="apDelay" 
                            name="<?php echo $this->meta_prefix.'setting_autoplay[delay]'; ?>"
                            type="number"
                            min="0"
                            step="100" 
                            value="<?php echo $autoplay['delay']; ?>">
                    </td>
                </tr>
                <tr data-filter-item data-filter-name="stopOnLastSlide StopOnLastSlide stoponlastslide stop Stop on On Last last slide Slide">
                    <td>
                        <label>stopOnLastSlide</label>
                    </td>
                    <td>
                        <span>Enable this parameter and autoplay will be stopped when it reaches last slide (has no effect in loop mode)</span>
                        <ul>
                            <li>
                                <label>
                                    <input 
                                        name="<?php echo $this->meta_prefix.'setting_autoplay[stopOnLastSlide]'; ?>"
                                        type="radio" 
                                        <?php checked( $autoplay['stopOnLastSlide'], 'true' ); ?>
                                        value="true">True
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input 
                                        name="<?php echo $this->meta_prefix.'setting_autoplay[stopOnLastSlide]'; ?>"
                                        type="radio" 
                                        <?php checked( $autoplay['stopOnLastSlide'], 'false' ); ?>
                                        value="false">False
                                </label>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr data-filter-item data-filter-name="disableOnInteraction DisableOnInteraction disableoninteraction disable Disable on On interaction Interaction">
                    <td>
                        <label>disableOnInteraction</label>
                    </td>
                    <td>
                        <span>Set to false and autoplay will not be disabled after user interactions (swipes), it will be restarted every time after interaction</span>
                        <ul>
                            <li>
                                <label>
                                    <input 
                                        name="<?php echo $this->meta_prefix.'setting_autoplay[disableOnInteraction]'; ?>"
                                        type="radio" 
                                        <?php checked( $autoplay['disableOnInteraction'], 'true' ); ?>
                                        value="true">True
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input 
                                        name="<?php echo $this->meta_prefix.'setting_autoplay[disableOnInteraction]'; ?>"
                                        type="radio" 
                                        <?php checked( $autoplay['disableOnInteraction'], 'false' ); ?>
                                        value="false">False
                                </label>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr data-filter-item data-filter-name="reverseDirection ReverseDirection reversedirection reverse Reverse direction Direction">
                    <td>
                        <label>reverseDirection</label>
                    </td>
                    <td>
                        <span>Enables autoplay in reverse direction</span>
                        <ul>
                            <li>
                                <label>
                                    <input 
                                        name="<?php echo $this->meta_prefix.'setting_autoplay[reverseDirection]'; ?>"
                                        type="radio" 
                                        <?php checked( $autoplay['reverseDirection'], 'true' ); ?>
                                        value="true">True
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input 
                                        name="<?php echo $this->meta_prefix.'setting_autoplay[reverseDirection]'; ?>"
                                        type="radio" 
                                        <?php checked( $autoplay['reverseDirection'], 'false' ); ?>
                                        value="false">False
                                </label>
                            </li>
                        </ul>
                    </td>
                </tr>
                <tr data-filter-item data-filter-name="waitForTransition WaitForTransition waitfortransition wait Wait for For transition Transition">
                    <td>
                        <label>waitForTransition</label>
                    </td>
                    <td>
                        <span>When enabled autoplay will wait for wrapper transition to continue. Can be disabled in case of using Virtual Translate when your slider may not have transition</span>
                        <ul>
                            <li>
                                <label>
                                    <input 
                                        name="<?php echo $this->meta_prefix.'setting_autoplay[waitForTransition]'; ?>"
                                        type="radio" 
                                        <?php checked( $autoplay['waitForTransition'], 'true' ); ?>
                                        value="true">True
                                </label>
                            </li>
                            <li>
                                <label>
                                    <input 
                                        name="<?php echo $this->meta_prefix.'setting_autoplay[waitForTransition]'; ?>"
                                        type="radio" 
                                        <?php checked( $autoplay['waitForTransition'], 'false' ); ?>
                                        value="false">False
                                </label>
                            </li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>