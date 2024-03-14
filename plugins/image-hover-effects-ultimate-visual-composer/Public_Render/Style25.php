<?php

namespace OXI_FLIP_BOX_PLUGINS\Public_Render;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Style_1
 * Content of Flipbox Plugins
 *
 * @author $biplob018
 */

/**
 * Description of Create
 *
 * @author biplo
 */
use OXI_FLIP_BOX_PLUGINS\Page\Public_Render;

class Style25 extends Public_Render {

    public function default_render() {
        $styleid = $this->oxiid;
        $styledata = explode('|', $this->dbdata['css']);
        $styledata = array_map('esc_attr', explode('|', $this->dbdata['css']));
        $listdata = $this->child;
        ?>
        <div class="oxilab-flip-box-wrapper">
            <?php
            foreach ($listdata as $value) {
                if (!empty($value['files'])):
                    $filesdata = explode("{#}|{#}", $value['files']);
                    ?>
                    <div class="<?php echo esc_attr($styledata[43]); ?> oxilab-flip-box-padding-<?php echo esc_attr($styleid); ?>"
                         sa-data-animation="<?php echo esc_attr($styledata[55]); ?>"
                         sa-data-animation-offset="100%"
                         sa-data-animation-delay="0ms"
                         sa-data-animation-duration=" <?php echo esc_attr(($styledata[57] * 1000)); ?>ms"
                         >
                        <div class="<?php echo ($this->admin == 'admin') ? 'oxilab-ab-id' : ''; ?> oxilab-flip-box-body-<?php echo esc_attr($styleid); ?> oxilab-flip-box-body-<?php echo esc_attr($styleid); ?>-<?php echo esc_attr($value['id']); ?>">
                            <?php
                            if ($filesdata[11] != '') {
                                echo '<a href="' . esc_attr($filesdata[11]) . '" target="' . esc_attr($styledata[53]) . '">';
                            }
                            ?>
                            <div class="oxilab-flip-box-body-absulote">
                                <div class="<?php echo esc_attr($styledata[1]); ?>">
                                    <div class="oxilab-flip-box-style-data <?php echo esc_attr($styledata[3]); ?>">
                                        <div class="oxilab-flip-box-style">
                                            <div class="oxilab-flip-box-front">
                                                <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>">
                                                    <img src="<?php echo esc_url($filesdata[5]); ?>" <?php
                                                            if (isset($filesdata[15])): echo 'alt="' . $filesdata[19] . '"';
                                                            endif;
                                                            ?>>
                                                </div>
                                            </div>
                                            <div class="oxilab-flip-box-back">
                                                <div class="oxilab-flip-box-back-<?php echo esc_attr($styleid); ?>">
                                                    <div class="oxilab-flip-box-back-<?php echo esc_attr($styleid); ?>-data">
                                                        <div class="oxilab-heading">
                                                            <?php  $this->text_render($filesdata[17]); ?>
                                                            <div class="oxilab-span">

                                                            </div>
                                                        </div>
                                                        <div class="oxilab-info">
                                                            <?php  $this->text_render($filesdata[7]); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if ($filesdata[11] != '') {
                                echo '</a>';
                            }
                             $this->admin_edit_panel($value['id']);
                            ?>
                        </div>

                        <?php
                        if ($filesdata[13] != '') {
                            $this->inline_css .= '.oxilab-flip-box-body-' . $styleid . '-' . $value['id'] . ' .oxilab-flip-box-back{
background: linear-gradient(' . $styledata[15] . ', ' . $styledata[15] . '), url("' . $filesdata[13] . '");
-moz-background-size: 100% 100%;
-o-background-size: 100% 100%;
background-size: 100% 100%;
}';
                        }
                        ?>

                    </div>
                    <?php
                endif;
            }
            $this->inline_css .= '.oxilab-flip-box-padding-' . $styleid . '{
                    padding: ' . $styledata[49] . 'px ' . $styledata[51] . 'px;
                    -webkit-transition:  opacity ' . $styledata[57] . 's linear;
                    -moz-transition:  opacity ' . $styledata[57] . 's linear;
                    -ms-transition:  opacity ' . $styledata[57] . 's linear;
                    -o-transition:  opacity ' . $styledata[57] . 's linear;
                    transition:  opacity ' . $styledata[57] . 's linear;
                    -webkit-animation-duration: ' . $styledata[57] . 's;
                    -moz-animation-duration: ' . $styledata[57] . 's;
                    -ms-animation-duration: ' . $styledata[57] . 's;
                    -o-animation-duration: ' . $styledata[57] . 's;
                    animation-duration: ' . $styledata[57] . 's;
                }
                .oxilab-flip-box-body-' . $styleid . '{
                    max-width: ' . $styledata[45] . 'px;
                    width: 100%;
                    margin: 0 auto;
                    position: relative;
                }
                .oxilab-flip-box-body-' . $styleid . ':after {
                    padding-bottom: ' . ($styledata[47] / $styledata[45] * 100) . '%;
                    content: "";
                    display: block;
                }
                .oxilab-flip-box-body-' . $styleid . ' .oxilab-flip-box-front{
                    -webkit-border-radius: ' . $styledata[153] . '%;
                    -moz-border-radius: ' . $styledata[153] . '%;
                    -ms-border-radius: ' . $styledata[153] . '%;
                    -o-border-radius: ' . $styledata[153] . '%;
                    border-radius: ' . $styledata[153] . '%;
                    background-color: ' . $styledata[5] . ';
                    overflow: hidden;
                    -webkit-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -moz-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -ms-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -o-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                }
                .oxilab-flip-box-' . $styleid . '{
                    position: absolute;
                    top: ' . $styledata[71] . 'px;
                    left: ' . $styledata[71] . 'px;
                    right: ' . $styledata[71] . 'px;
                    bottom: ' . $styledata[71] . 'px;
                    border-width: ' . $styledata[159] . 'px;
                    border-style:' . $styledata[161] . ';
                    border-color: ' . $styledata[9] . ';
                    display: block;
                    -webkit-border-radius: ' . $styledata[153] . '%;
                    -moz-border-radius: ' . $styledata[153] . '%;
                    -ms-border-radius: ' . $styledata[153] . '%;
                    -o-border-radius: ' . $styledata[153] . '%;
                    border-radius: ' . $styledata[153] . '%;
                    overflow: hidden;
                }
                .oxilab-flip-box-' . $styleid . ' img{
                    position: absolute;
                    top: ' . $styledata[105] . 'px;
                    left: ' . $styledata[105] . 'px;
                    right: ' . $styledata[105] . 'px;
                    bottom: ' . $styledata[105] . 'px;
                    width: calc(100% - (' . $styledata[105] . 'px + ' . $styledata[105] . 'px ));
                    height: calc(100% - (' . $styledata[105] . 'px + ' . $styledata[105] . 'px ));
                    -webkit-border-radius: ' . $styledata[153] . '%;
                    -moz-border-radius: ' . $styledata[153] . '%;
                    -ms-border-radius: ' . $styledata[153] . '%;
                    -o-border-radius: ' . $styledata[153] . '%;
                    border-radius: ' . $styledata[153] . '%;
                }
                .oxilab-flip-box-body-' . $styleid . ' .oxilab-flip-box-back{
                    -webkit-border-radius: ' . $styledata[153] . '%;
                    -moz-border-radius: ' . $styledata[153] . '%;
                    -ms-border-radius: ' . $styledata[153] . '%;
                    -o-border-radius: ' . $styledata[153] . '%;
                    border-radius: ' . $styledata[153] . '%;
                    background-color: ' . $styledata[15] . ';
                    overflow: hidden;
                    -webkit-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -moz-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -ms-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -o-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                }
                .oxilab-flip-box-back-' . $styleid . '{
                    position: absolute;
                    border-color: ' . $styledata[17] . ';
                    top: ' . $styledata[163] . 'px;
                    left: ' . $styledata[163] . 'px;
                    right: ' . $styledata[163] . 'px;
                    bottom: ' . $styledata[163] . 'px;
                    border-width: ' . $styledata[151] . 'px;
                    border-style:' . $styledata[149] . ';
                    display: block;
                    -webkit-border-radius: ' . $styledata[153] . '%;
                    -moz-border-radius: ' . $styledata[153] . '%;
                    -ms-border-radius: ' . $styledata[153] . '%;
                    -o-border-radius: ' . $styledata[153] . '%;
                    border-radius: ' . $styledata[153] . '%;
                    overflow: hidden;
                }
                .oxilab-flip-box-back-' . $styleid . '-data{
                    position: absolute;
                    left: 0%;
                    right: 0;
                    top: 50%;
                    padding: ' . $styledata[101] . 'px ' . $styledata[103] . 'px;
                    -webkit-transform: translateY(-50%);
                    -ms-transform: translateY(-50%);
                    -moz-transform: translateY(-50%);
                    -o-transform: translateY(-50%);
                    transform: translateY(-50%);
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-heading{
                    display: block;
                    position: relative;
                    color:  ' . $styledata[31] . ';
                    text-align: ' . $styledata[189] . ';
                    font-size: ' . $styledata[181] . 'px;
                    font-family: ' . $this->font_familly($styledata[183]) . ';
                    font-weight: ' . $styledata[187] . ';
                    font-style:' . $styledata[185] . ';
                    padding:' . $styledata[191] . 'px ' . $styledata[197] . 'px ' . $styledata[193] . 'px ' . $styledata[195] . 'px;
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-heading .oxilab-span{
                    position: absolute;
                    left: 50%;
                    bottom: 0;
                    background-color: ' . $styledata[7] . ';
                    width: ' . $styledata[155] . 'px;
                    min-height: 2px;
                    height: ' . $styledata[157] . 'px;
                    -webkit-transform: translateX(-50%);
                    -ms-transform: translateX(-50%);
                    -moz-transform: translateX(-50%);
                    -o-transform: translateX(-50%);
                    transform: translateX(-50%);
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-info{
                    display: block;
                    color: ' . $styledata[19] . ';
                    text-align: ' . $styledata[115] . ';
                    font-size: ' . $styledata[107] . 'px;
                    font-family: ' . $this->font_familly($styledata[109]) . ';
                    font-weight: ' . $styledata[113] . ';
                    font-style:' . $styledata[111] . ';
                    padding:' . $styledata[117] . 'px ' . $styledata[123] . 'px ' . $styledata[119] . 'px ' . $styledata[121] . 'px;
                }
                ' . $styledata[199] . '';
            ?>

        </div>
        <?php
    }

}
