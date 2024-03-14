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

class Style12 extends Public_Render {

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
                        <div class="<?php echo ($this->admin == 'admin') ? 'oxilab-ab-id' : ''; ?>  oxilab-flip-box-body-<?php echo esc_attr($styleid); ?> oxilab-flip-box-body-<?php echo esc_attr($styleid); ?>-<?php echo esc_attr($value['id']); ?>">
                            <?php
                            if ($filesdata[11] != '') {
                                echo '<a href="' . esc_url($filesdata[11]) . '" target="' . esc_html($styledata[53]) . '">';
                            }
                            ?>
                            <div class="oxilab-flip-box-body-absulote">
                                <div class="<?php echo esc_attr($styledata[1]); ?>">
                                    <div class="oxilab-flip-box-style-data <?php echo esc_attr($styledata[3]); ?>">
                                        <div class="oxilab-flip-box-style">
                                            <div class="oxilab-flip-box-front">
                                                <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>">
                                                    <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>-data2">
                                                        <div class="oxilab-icon">
                                                            <div class="oxilab-icon-data">
                                                                <?php  $this->font_awesome_render($filesdata[3]) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>-data">
                                                        <div class="oxilab-heading">
                                                            <?php  $this->text_render($filesdata[1]); ?>
                                                        </div>
                                                        <div class="oxilab-info">
                                                            <?php  $this->text_render($filesdata[15]); ?>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="oxilab-flip-box-back">
                                                <div class="oxilab-flip-box-back-<?php echo esc_attr($styleid); ?>">
                                                    <div class="oxilab-flip-box-back-<?php echo esc_attr($styleid); ?>-data">
                                                        <div class="oxilab-heading">
                                                            <?php  $this->text_render($filesdata[17]); ?>
                                                            <div class="oxilab-span"></div>
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
                        if ($filesdata[5] != '') {
                            $this->inline_css .= '.oxilab-flip-box-body-' . $styleid . '-' . $value['id'] . ' .oxilab-flip-box-' . $styleid . '{
background: linear-gradient(' . $styledata[5] . ', ' . $styledata[5] . '), url("' . $filesdata[5] . '");
-moz-background-size: 100% 100%;
-o-background-size: 100% 100%;
background-size: 100% 100%;
}';
                        }
                        if ($filesdata[13] != '') {
                            $this->inline_css .= '.oxilab-flip-box-body-' . $styleid . '-' . $value['id'] . ' .oxilab-flip-box-back-' . $styleid . '{
background: linear-gradient(' . $styledata[17] . ', ' . $styledata[17] . '), url("' . $filesdata[13] . '");
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

            $this->inline_css .= ' .oxilab-flip-box-padding-' . $styleid . '{
                    padding: ' . $styledata[49] . 'px ' . $styledata[51] . 'px;
                    transition:  opacity ' . $styledata[57] . 's linear;
                    -webkit-animation-duration: ' . $styledata[57] . 's;
                    -moz-animation-duration: ' . $styledata[57] . 's;
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
                .oxilab-flip-box-' . $styleid . '{
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    border-color: ' . $styledata[7] . ';
                    background-color: ' . $styledata[5] . ';
                    border-width: ' . $styledata[125] . 'px;
                    border-style:' . $styledata[127] . ';
                    display: block;
                    border-radius: ' . $styledata[129] . 'px;
                    overflow: hidden;
                    box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                }
                .oxilab-flip-box-' . $styleid . '-data{
                    position: absolute;
                    left: 0%;
                    top: ' . $styledata[75] . 'px;
                    padding: ' . $styledata[71] . 'px ' . $styledata[73] . 'px;
                    right: 0;
                }
                .oxilab-flip-box-' . $styleid . '-data2{
                    position: absolute;
                    top: 0%;
                    left: 50%;
                    background-color: ' . $styledata[11] . ';
                    -webkit-transform: translateX(-50%);
                    -ms-transform: translateX(-50%);
                    -moz-transform: translateX(-50%);
                    -o-transform: translateX(-50%);
                    transform: translateX(-50%);
                    height: ' . $styledata[75] . 'px;
                    width: ' . $styledata[79] . 'px;
                    border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                }
                .oxilab-flip-box-' . $styleid . '-data2 .oxilab-icon{
                    position: absolute;
                    bottom:  0;
                    width: 100%;
                    display: block;
                    text-align: center;
                }
                .oxilab-flip-box-' . $styleid . '-data2 .oxilab-icon-data{
                    display: inline-block;
                    width: ' . $styledata[79] . 'px;
                    height: ' . $styledata[79] . 'px;
                    border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                }
                .oxilab-flip-box-' . $styleid . '-data2 .oxilab-icon-data .oxi-icons{
                    line-height:' . $styledata[79] . 'px;
                    font-size: ' . $styledata[77] . 'px;
                    color: ' . $styledata[9] . ';
                }
                .oxilab-flip-box-' . $styleid . '-data .oxilab-heading{
                    display: block;
                    color: ' . $styledata[13] . ';
                    text-align: ' . $styledata[91] . ';
                    font-size: ' . $styledata[83] . 'px;
                    font-family: ' . $this->font_familly($styledata[85]) . ';
                    font-weight: ' . $styledata[89] . ';
                    font-style:' . $styledata[87] . ';
                    padding: ' . $styledata[93] . 'px ' . $styledata[99] . 'px ' . $styledata[95] . 'px ' . $styledata[97] . 'px;

                }

                .oxilab-flip-box-' . $styleid . '-data .oxilab-info{
                    display: block;
                    color:  ' . $styledata[15] . ';
                    text-align: ' . $styledata[147] . ';
                    font-size: ' . $styledata[139] . 'px;
                    font-family: ' . $this->font_familly($styledata[141]) . ';
                    font-weight: ' . $styledata[145] . ';
                    font-style:' . $styledata[143] . ';
                    padding: ' . $styledata[149] . 'px ' . $styledata[155] . 'px ' . $styledata[151] . 'px ' . $styledata[153] . 'px;

                }
                .oxilab-flip-box-back-' . $styleid . '{
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    border-color: ' . $styledata[19] . ';
                    background-color: ' . $styledata[17] . ';
                    border-width: ' . $styledata[131] . 'px;
                    border-style:' . $styledata[133] . ';
                    display: block;
                    border-radius: ' . $styledata[129] . 'px;
                    overflow: hidden;
                    box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
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
                    color:  ' . $styledata[21] . ';
                    text-align: ' . $styledata[165] . ';
                    font-size: ' . $styledata[157] . 'px;
                    font-family: ' . $this->font_familly($styledata[159]) . ';
                    font-weight: ' . $styledata[163] . ';
                    font-style:' . $styledata[161] . ';
                    padding:' . $styledata[167] . 'px ' . $styledata[173] . 'px ' . $styledata[169] . 'px ' . $styledata[171] . 'px;

                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-heading .oxilab-span{
                    position: absolute;
                    bottom: 0;
                    background-color: ' . $styledata[23] . ';
                    left: 50%;
                    -webkit-transform: translateX(-50%);
                    -ms-transform: translateX(-50%);
                    -moz-transform: translateX(-50%);
                    -o-transform: translateX(-50%);
                    transform: translateX(-50%);
                    height: ' . $styledata[177] . 'px;
                    width: ' . $styledata[175] . 'px;
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-info{
                    display: block;
                    color:  ' . $styledata[25] . ';
                    text-align: ' . $styledata[115] . ';
                    font-size: ' . $styledata[107] . 'px;
                    font-family: ' . $this->font_familly($styledata[109]) . ';
                    font-weight: ' . $styledata[113] . ';
                    font-style:' . $styledata[111] . ';
                    padding:' . $styledata[117] . 'px ' . $styledata[123] . 'px ' . $styledata[119] . 'px ' . $styledata[121] . 'px;
                }
                ' . $styledata[179] . '';
            ?>


        </div>
        <?php
    }

}
