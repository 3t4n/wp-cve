<?php

namespace OXI_FLIP_BOX_PLUGINS\Public_Render;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Description of Style_1
 * Content of Flipbox Plugins Plugins
 *
 * @author $biplob018
 */

/**
 * Description of Create
 *
 * @author biplo
 */
use OXI_FLIP_BOX_PLUGINS\Page\Public_Render;

class Style1 extends Public_Render {

    public function default_render() {
        $styleid = esc_attr($this->oxiid);
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
                        <div class="<?php echo ($this->admin == 'admin') ? 'oxilab-ab-id' : ''; ?>  oxilab-flip-box-body-<?php echo esc_attr($styleid); ?> oxilab-flip-box-body-<?php echo esc_attr($styleid); ?>-<?php echo esc_attr($value['id']); ?>">
                            <?php
                            if ($filesdata[9] == '' && $filesdata[11] != '') {
                                echo '<a href="' . esc_url($filesdata[11]) . '" target="' . esc_attr($styledata[53]) . '">';
                            }
                            ?>
                            <div class="oxilab-flip-box-body-absulote">
                                <div class="<?php echo esc_attr($styledata[1]); ?>">
                                    <div class="oxilab-flip-box-style-data <?php echo esc_attr($styledata[3]); ?>">
                                        <div class="oxilab-flip-box-style">
                                            <div class="oxilab-flip-box-front">
                                                <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>">
                                                    <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>-data">
                                                        <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>-image">
                                                            <img src="<?php echo esc_url($filesdata[5]); ?>" <?php
                                                            if (isset($filesdata[15])): echo 'alt="' . $filesdata[15] . '"';
                                                            endif;
                                                            ?>>

                                                            <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>-image-icon">
                                                                <?php $this->font_awesome_render($filesdata[3]) ?>
                                                            </div>
                                                        </div>
                                                        <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>-heading">
                                                            <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>-heading-data">
                                                                <?php $this->text_render($filesdata[1]); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="oxilab-flip-box-back">
                                                <div class="oxilab-flip-box-back-<?php echo esc_attr($styleid); ?>">
                                                    <div class="oxilab-flip-box-back-<?php echo esc_attr($styleid); ?>-data">
                                                        <div class="oxilab-flip-box-back-<?php echo esc_attr($styleid); ?>-file">
                                                            <div class="oxilab-info">
                                                                <?php $this->text_render($filesdata[7]); ?>
                                                            </div>
                                                            <?php
                                                            if ($filesdata[9] != '' && $filesdata[11] != '') {
                                                                ?>
                                                                <div class="oxilab-button">
                                                                    <a href="<?php echo esc_url(esc_url($filesdata[11])); ?>" target="<?php echo esc_attr(esc_attr($styledata[53])); ?>">
                                                                        <span class="oxilab-button-data">
                                                                            <?php $this->text_render($filesdata[9]); ?>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                                <?php
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            if ($filesdata[9] == '' && $filesdata[11] != '') {
                                echo '</a>';
                            }
                            $this->admin_edit_panel($value['id']);
                            ?>
                        </div>

                        <?php
                        if ($filesdata[13] != '') {
                            $this->inline_css .= '.oxilab-flip-box-body-' . $styleid . '-' . $value['id'] . ' .oxilab-flip-box-back-' . $styleid . '-data{
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
            ?>

        </div>
            <?php
            $this->inline_css .= '.oxilab-flip-box-padding-' . $styleid . '{
                    padding: ' . $styledata[49] . 'px ' . $styledata[51] . 'px;
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
                .oxilab-flip-box-' . $styleid . '{
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    display: block;
                    border: 1px solid ' . $styledata[5] . ';
                    background-color: ' . $styledata[7] . ';
                    -webkit-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -moz-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -ms-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -o-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                }
                .oxilab-flip-box-' . $styleid . '-data{
                    position: absolute;
                    top: 0;
                    left: 0;
                    background: ' . $styledata[5] . ';
                    width: calc(100% - ' . ($styledata[75] * 2) . 'px);
                    height: calc(100% - ' . ($styledata[75] * 2) . 'px);
                    margin: ' . $styledata[75] . 'px;
                    padding: ' . $styledata[71] . 'px ' . $styledata[73] . 'px;
                    display: block;
                }
                .oxilab-flip-box-' . $styleid . '-image{
                    max-width: 100%;
                    width: 100%;
                    float: left;
                    position: relative;
                }
                .oxilab-flip-box-' . $styleid . '-image:after {
                    padding-bottom: ' . $styledata[69] . '%;
                    content: "";
                    display: block;
                }
                .oxilab-flip-box-' . $styleid . '-image img{
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    display: block;
                }
                .oxilab-flip-box-' . $styleid . '-image-icon{
                    position: absolute;
                    left: 50%;
                    background: ' . $styledata[11] . ';
                    border: 1px solid ' . $styledata[5] . ';
                    height: ' . $styledata[79] . 'px;
                    width: ' . $styledata[79] . 'px;
                    bottom: -' . ($styledata[79] / 2) . 'px;
                    -webkit-transform: translateX(-50%);
                    -ms-transform: translateX(-50%);
                    -moz-transform: translateX(-50%);
                    -ms-transform: translateX(-50%);
                    -o-transform: translateX(-50%);
                    transform: translateX(-50%);
                    text-align: center;
                    -webkit-border-radius:' . $styledata[81] . 'px;
                    -moz-border-radius:' . $styledata[81] . 'px;
                    -ms-border-radius:' . $styledata[81] . 'px;
                    -o-border-radius:' . $styledata[81] . 'px;
                    border-radius:' . $styledata[81] . 'px;
                    -webkit-backface-visibility: hidden;
                    -moz-backface-visibility: hidden;
                    -ms-backface-visibility: hidden;
                    -o-backface-visibility: hidden;
                    backface-visibility: hidden;
                }
                .oxilab-flip-box-' . $styleid . '-image-icon .oxi-icons{
                    line-height: ' . $styledata[79] . 'px;
                    color: ' . $styledata[9] . ';
                    font-size: ' . $styledata[77] . 'px;
                }
                .oxilab-flip-box-' . $styleid . '-heading{
                    width: 100%;
                    float: left;
                }
                .oxilab-flip-box-' . $styleid . '-heading-data{
                    margin-top: ' . ($styledata[79] / 2) . 'px;
                    color: ' . $styledata[13] . ';
                    width: 100%;
                    float: left;
                    text-align: ' . $styledata[91] . ';
                    font-size: ' . $styledata[83] . 'px;
                    font-family: ' . $this->font_familly($styledata[85]) . ';
                    font-weight: ' . $styledata[89] . ';
                    font-style:' . $styledata[87] . ';
                    padding: ' . $styledata[93] . 'px ' . $styledata[99] . 'px ' . $styledata[95] . 'px ' . $styledata[97] . 'px;
                }
                .oxilab-flip-box-back-' . $styleid . '{
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    display: block;
                    border: 1px solid;
                    border-color: ' . $styledata[15] . ';
                    background-color: ' . $styledata[17] . ';
                    -webkit-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -moz-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -ms-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -o-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';

                }
                .oxilab-flip-box-back-' . $styleid . '-data{
                    position: absolute;
                    top: 0;
                    left: 0;
                    background-color: ' . $styledata[15] . ';
                    width: calc(100% - ' . ($styledata[105] * 2) . 'px);
                    height: calc(100% - ' . ($styledata[105] * 2) . 'px);
                    margin: ' . $styledata[105] . 'px;
                    padding: ' . $styledata[101] . 'px ' . $styledata[103] . 'px;
                    display: block;
                }
                .oxilab-flip-box-back-' . $styleid . '-file{
                    position: absolute;
                    left: 0%;
                    top: 50%;
                    -webkit-transform: translateY(-50%);
                    -ms-transform: translateY(-50%);
                    -moz-transform: translateY(-50%);
                    -ms-transform: translateY(-50%);
                    -o-transform: translateY(-50%);
                    transform: translateY(-50%);
                    width: 100%;
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-info{
                    width: 100%;
                    float: left;
                    color: ' . $styledata[19] . ';
                    text-align: ' . $styledata[115] . ';
                    font-size: ' . $styledata[107] . 'px;
                    font-family: ' . $this->font_familly($styledata[109]) . ';
                    font-weight: ' . $styledata[113] . ';
                    font-style:' . $styledata[111] . ';
                    padding: ' . $styledata[117] . 'px ' . $styledata[123] . 'px ' . $styledata[119] . 'px ' . $styledata[121] . 'px;
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-button{
                    width: 100%;
                    float: left;
                    text-align: ' . $styledata[139] . ';
                    padding: ' . $styledata[141] . 'px ' . $styledata[147] . 'px ' . $styledata[143] . 'px ' . $styledata[145] . 'px;
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-button-data{
                    display: inline-block;
                    color: ' . $styledata[21] . ';
                    background-color:  ' . $styledata[23] . ';
                    font-size: ' . $styledata[125] . 'px;
                    font-family: ' . $this->font_familly($styledata[127]) . ';
                    font-weight: ' . $styledata[131] . ';
                    font-style:' . $styledata[129] . ';
                    padding: ' . $styledata[133] . 'px ' . $styledata[135] . 'px;
                    -webkit-border-radius:' . $styledata[137] . 'px;
                    -moz-border-radius:' . $styledata[137] . 'px;
                    -ms-border-radius:' . $styledata[137] . 'px;
                    -o-border-radius:' . $styledata[137] . 'px;
                    border-radius: ' . $styledata[137] . 'px;
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-button-data:hover{
                    background-color: ' . $styledata[27] . ';
                    color:  ' . $styledata[25] . ';
                }
                ' . $styledata[149] . '';
        }

    }
