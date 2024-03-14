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

class Style14 extends Public_Render {

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
                         sa-data-animation-duration=" <?php echo esc_attr(($styledata[57] * 1000)); ?>ms">
                        <div class="<?php echo ($this->admin == 'admin') ? 'oxilab-ab-id' : ''; ?> oxilab-flip-box-body-<?php echo esc_attr($styleid); ?> oxilab-flip-box-body-<?php echo esc_attr($styleid); ?>-<?php echo esc_attr($value['id']); ?>">
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
                                                    <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>-data2">
                                                        <div class="oxilab-icon">
                                                            <div class="oxilab-icon-data">
                                                                <div class="oxilab-span">
                                                                    <?php $this->text_render($filesdata[3]); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="oxilab-flip-box-<?php echo esc_attr($styleid); ?>-data">
                                                        <div class="oxilab-heading">
                                                            <?php $this->text_render($filesdata[1]); ?>
                                                        </div>
                                                        <div class="oxilab-info">
                                                            <?php $this->text_render($filesdata[15]); ?>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="oxilab-flip-box-back">
                                                <div class="oxilab-flip-box-back-<?php echo esc_attr($styleid); ?>">
                                                    <div class="oxilab-flip-box-back-<?php echo esc_attr($styleid); ?>-data2">
                                                        <div class="oxilab-icon">
                                                            <div class="oxilab-icon-data">
                                                                <?php $this->font_awesome_render($filesdata[19]) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="oxilab-flip-box-back-<?php echo esc_attr($styleid); ?>-data">
                                                        <div class="oxilab-heading">
                                                            <?php $this->text_render($filesdata[17]); ?>
                                                        </div>
                                                        <div class="oxilab-info">
                                                            <?php $this->text_render($filesdata[7]); ?>
                                                        </div>
                                                        <?php
                                                        if ($filesdata[9] != '' && $filesdata[11] != '') {
                                                            ?>
                                                            <a href="<?php echo esc_url($filesdata[11]) ?>" target="<?php echo esc_attr($styledata[53]) ?>">
                                                                <span class="oxilab-button">
                                                                    <span class="oxilab-button-data">
                                                                        <?php $this->text_render($filesdata[9]) ?>
                                                                    </span>
                                                                </span>
                                                            </a>
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
                            <?php
                            if ($filesdata[9] == '' && $filesdata[11] != '') {
                                echo '</a>';
                            }
                            $this->admin_edit_panel($value['id']);
                            ?>
                        </div>
                        <style>
                <?php
                if ($filesdata[5] != '') {
                    $this->inline_css .= '.oxilab-flip-box-body-' . $styleid . '-' . $value['id'] . ' .oxilab-flip-box-front{
background: linear-gradient(' . $styledata[5] . ', ' . $styledata[5] . '), url("' . $filesdata[5] . '");
-moz-background-size: 100% 100%;
-o-background-size: 100% 100%;
background-size: 100% 100%;
}';
                }
                if ($filesdata[13] != '') {
                    $this->inline_css .= '.oxilab-flip-box-body-' . $styleid . '-' . $value['id'] . ' .oxilab-flip-box-back{
background: linear-gradient(' . $styledata[17] . ', ' . $styledata[17] . '), url("' . $filesdata[13] . '");
-moz-background-size: 100% 100%;
-o-background-size: 100% 100%;
background-size: 100% 100%;
}';
                }
                ?>
                        </style>
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
                    border-width: ' . $styledata[125] . 'px;
                    border-style:' . $styledata[127] . ';
                    -webkit-border-radius: ' . $styledata[129] . 'px;
                    -moz-border-radius: ' . $styledata[129] . 'px;
                    -ms-border-radius: ' . $styledata[129] . 'px;
                    -o-border-radius: ' . $styledata[129] . 'px;
                    border-radius: ' . $styledata[129] . 'px;
                    border-color: ' . $styledata[7] . ';
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
                    left: ' . $styledata[73] . 'px;
                    right: ' . $styledata[73] . 'px;
                    bottom: ' . $styledata[71] . 'px;
                    border-width: ' . $styledata[179] . 'px;
                    border-style:' . $styledata[181] . ';
                    border-color: ' . $styledata[27] . ';
                    display: block;
                    -webkit-border-radius: ' . $styledata[183] . 'px;
                    -moz-border-radius: ' . $styledata[183] . 'px;
                    -ms-border-radius: ' . $styledata[183] . 'px;
                    -o-border-radius: ' . $styledata[183] . 'px;
                    border-radius: ' . $styledata[183] . 'px;
                    overflow: hidden;
                }
                .oxilab-flip-box-' . $styleid . '-data2{
                    position: absolute;
                    top: 0%;
                    left: 50%;
                    -webkit-transform: translateX(-50%);
                    -ms-transform: translateX(-50%);
                    -moz-transform: translateX(-50%);
                    -o-transform: translateX(-50%);
                    transform: translateX(-50%);
                    background-color: ' . $styledata[11] . ';
                    height: ' . $styledata[75] . 'px;
                    width: ' . $styledata[79] . 'px;
                    -webkit-border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                    -moz-border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                    -ms-border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                    -o-border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                    border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                    -webkit-box-shadow: 0 0 3px 0 #666666;
                    -moz-box-shadow: 0 0 3px 0 #666666;
                    -ms-box-shadow: 0 0 3px 0 #666666;
                    -o-box-shadow: 0 0 3px 0 #666666;
                    box-shadow: 0 0 3px 0 #666666;
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
                    -webkit-border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                    -moz-border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                    -ms-border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                    -o-border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                    border-radius: 0 0 ' . $styledata[81] . 'px ' . $styledata[81] . 'px;
                }
                .oxilab-flip-box-' . $styleid . '-data2 .oxilab-icon-data .oxilab-span{
                    line-height:' . $styledata[79] . 'px;
                    font-size: ' . $styledata[77] . 'px;
                    color: ' . $styledata[9] . ';
                }
                .oxilab-flip-box-' . $styleid . '-data{
                    position: absolute;
                    left: 0%;
                    top: ' . $styledata[75] . 'px;
                    padding: ' . $styledata[185] . 'px ' . $styledata[187] . 'px;
                    right: 0;
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
                    color: ' . $styledata[15] . ';
                    text-align: ' . $styledata[147] . ';
                    font-size: ' . $styledata[139] . 'px;
                    font-family: ' . $this->font_familly($styledata[141]) . ';
                    font-weight: ' . $styledata[145] . ';
                    font-style:' . $styledata[143] . ';
                    padding: ' . $styledata[149] . 'px ' . $styledata[155] . 'px ' . $styledata[151] . 'px ' . $styledata[153] . 'px;

                }
                .oxilab-flip-box-body-' . $styleid . ' .oxilab-flip-box-back{
                    border-width: ' . $styledata[131] . 'px;
                    border-style:' . $styledata[133] . ';
                    border-color: ' . $styledata[19] . ';
                    background-color: ' . $styledata[17] . ';
                    -webkit-border-radius: ' . $styledata[129] . 'px;
                    -moz-border-radius: ' . $styledata[129] . 'px;
                    -ms-border-radius: ' . $styledata[129] . 'px;
                    -o-border-radius: ' . $styledata[129] . 'px;
                    border-radius: ' . $styledata[129] . 'px;
                    overflow: hidden;
                    -webkit-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -moz-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -ms-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    -o-box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                    box-shadow: ' . $styledata[61] . 'px ' . $styledata[63] . 'px ' . $styledata[65] . 'px ' . $styledata[67] . 'px ' . $styledata[59] . ';
                }
                .oxilab-flip-box-back-' . $styleid . '{
                    position: absolute;
                    top: ' . $styledata[101] . 'px;
                    left: ' . $styledata[103] . 'px;
                    right: ' . $styledata[103] . 'px;
                    bottom: ' . $styledata[101] . 'px;
                    border-width: ' . $styledata[189] . 'px;
                    border-style:' . $styledata[191] . ';
                    border-color: ' . $styledata[29] . ';
                    display: block;
                    -webkit-border-radius: ' . $styledata[193] . 'px;
                    -moz-border-radius: ' . $styledata[193] . 'px;
                    -ms-border-radius: ' . $styledata[193] . 'px;
                    -o-border-radius: ' . $styledata[193] . 'px;
                    border-radius: ' . $styledata[193] . 'px;
                    overflow: hidden;
                }
                .oxilab-flip-box-back-' . $styleid . '-data2{
                    position: absolute;
                    top: 0%;
                    left: 50%;
                    -webkit-transform: translateX(-50%);
                    -ms-transform: translateX(-50%);
                    -moz-transform: translateX(-50%);
                    -o-transform: translateX(-50%);
                    transform: translateX(-50%);
                    height: ' . $styledata[203] . 'px;
                    width: ' . $styledata[201] . 'px;
                    background-color: ' . $styledata[41] . ';
                    -webkit-border-radius: 0 0 ' . $styledata[209] . 'px ' . $styledata[209] . 'px;
                    -moz-border-radius: 0 0 ' . $styledata[209] . 'px ' . $styledata[209] . 'px;
                    -ms-border-radius: 0 0 ' . $styledata[209] . 'px ' . $styledata[209] . 'px;
                    -o-border-radius: 0 0 ' . $styledata[209] . 'px ' . $styledata[209] . 'px;
                    border-radius: 0 0 ' . $styledata[209] . 'px ' . $styledata[209] . 'px;
                    -webkit-box-shadow: 0 0 3px 0 #666666;
                    -moz-box-shadow: 0 0 3px 0 #666666;
                    -ms-box-shadow: 0 0 3px 0 #666666;
                    -o-box-shadow: 0 0 3px 0 #666666;
                    box-shadow: 0 0 3px 0 #666666;
                }
                .oxilab-flip-box-back-' . $styleid . '-data2 .oxilab-icon{
                    position: absolute;
                    bottom:  0;
                    width: 100%;
                    display: block;
                    text-align: center;
                }
                .oxilab-flip-box-back-' . $styleid . '-data2 .oxilab-icon-data{
                    display: inline-block;
                    width: ' . $styledata[201] . 'px;
                    height: ' . $styledata[201] . 'px;
                }
                .oxilab-flip-box-back-' . $styleid . '-data2 .oxilab-icon-data .oxi-icons{
                    line-height: ' . $styledata[201] . 'px;
                    font-size: ' . $styledata[199] . 'px;
                    color: ' . $styledata[39] . ';
                }
                .oxilab-flip-box-back-' . $styleid . '-data{
                    position: absolute;
                    left: 0%;
                    right: 0;
                    top: ' . $styledata[203] . 'px;
                    padding: ' . $styledata[195] . 'px ' . $styledata[197] . 'px;
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-heading{
                    display: block;
                    color: ' . $styledata[21] . ';
                    text-align: ' . $styledata[165] . ';
                    font-size: ' . $styledata[157] . 'px;
                    font-family: ' . $this->font_familly($styledata[159]) . ';
                    font-weight: ' . $styledata[163] . ';
                    font-style:' . $styledata[161] . ';
                    padding:' . $styledata[167] . 'px ' . $styledata[173] . 'px ' . $styledata[169] . 'px ' . $styledata[171] . 'px;
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-info{
                    display: block;
                    color: ' . $styledata[25] . ';
                    text-align: ' . $styledata[115] . ';
                    font-size: ' . $styledata[107] . 'px;
                    font-family: ' . $this->font_familly($styledata[109]) . ';
                    font-weight: ' . $styledata[113] . ';
                    font-style:' . $styledata[111] . ';
                    padding:' . $styledata[117] . 'px ' . $styledata[123] . 'px ' . $styledata[119] . 'px ' . $styledata[121] . 'px;

                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-button{
                    display: block;
                    text-align: ' . $styledata[225] . ';
                    padding: ' . $styledata[227] . 'px ' . $styledata[233] . 'px ' . $styledata[229] . 'px ' . $styledata[231] . 'px;

                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-button-data{
                    display: inline-block;
                    color: ' . $styledata[31] . ';
                    background-color:  ' . $styledata[33] . ';
                    font-size: ' . $styledata[211] . 'px;
                    font-family: ' . $this->font_familly($styledata[213]) . ';
                    font-weight: ' . $styledata[217] . ';
                    font-style:' . $styledata[215] . ';
                    padding: ' . $styledata[219] . 'px ' . $styledata[221] . 'px;
                    -webkit-border-radius: ' . $styledata[223] . 'px;
                    -moz-border-radius: ' . $styledata[223] . 'px;
                    -ms-border-radius: ' . $styledata[223] . 'px;
                    -o-border-radius: ' . $styledata[223] . 'px;
                    border-radius: ' . $styledata[223] . 'px;
                }
                .oxilab-flip-box-back-' . $styleid . '-data .oxilab-button-data:hover{
                    background-color: ' . $styledata[37] . ';
                    color:  ' . $styledata[35] . ';
                }
                ' . $styledata[235] . '';
            ?>


        </div>
        <?php
    }

}
