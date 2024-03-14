<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace OXI_FLIP_BOX_PLUGINS\Inc_Helper;

/**
 *
 * @author biplo
 */
trait Sanitization
{



    

    public function oxilab_flip_box_admin_font_weight($id, $value, $name, $title)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="<?php echo esc_attr($id); ?>" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($title); ?>"><?php echo esc_html($name); ?> </label>
            <div class="col-sm-6">
                <select class="form-control" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>">
                    <option value="100" <?php
                                        if ($value == '100') {
                                            echo 'selected';
                                        };
                                        ?>>100</option>
                    <option value="200" <?php
                                        if ($value == '200') {
                                            echo 'selected';
                                        };
                                        ?>>200</option>
                    <option value="300" <?php
                                        if ($value == '300') {
                                            echo 'selected';
                                        };
                                        ?>>300</option>
                    <option value="400" <?php
                                        if ($value == '400') {
                                            echo 'selected';
                                        };
                                        ?>>400</option>
                    <option value="500" <?php
                                        if ($value == '500') {
                                            echo 'selected';
                                        };
                                        ?>>500</option>
                    <option value="600" <?php
                                        if ($value == '600') {
                                            echo 'selected';
                                        };
                                        ?>>600</option>
                    <option value="700" <?php
                                        if ($value == '700') {
                                            echo 'selected';
                                        };
                                        ?>>700</option>
                    <option value="800" <?php
                                        if ($value == '800') {
                                            echo 'selected';
                                        };
                                        ?>>800</option>
                    <option value="900" <?php
                                        if ($value == '900') {
                                            echo 'selected';
                                        };
                                        ?>>900</option>
                    <option value="normal" <?php
                                            if ($value == 'normal') {
                                                echo 'selected';
                                            };
                                            ?>>Normal</option>
                    <option value="bold" <?php
                                            if ($value == 'bold') {
                                                echo 'selected';
                                            };
                                            ?>>Bold</option>
                    <option value="lighter" <?php
                                            if ($value == 'lighter') {
                                                echo 'selected';
                                            };
                                            ?>>Lighter</option>
                    <option value="initial" <?php
                                            if ($value == 'initial') {
                                                echo 'selected';
                                            };
                                            ?>>Initial</option>
                </select>
            </div>
        </div>
    <?php
    }

    public function oxilab_flip_box_admin_font_style($id, $value, $name, $title)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="<?php echo esc_attr($id); ?>" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($title); ?>"><?php echo esc_html($name); ?> </label>
            <div class="col-sm-6">
                <select class="form-control" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>">
                    <option <?php
                            if ($value == 'normal') {
                                echo 'selected';
                            }
                            ?> value="normal">Normal</option>
                    <option <?php
                            if ($value == 'italic') {
                                echo 'selected';
                            }
                            ?> value="italic">Italic</option>
                    <option <?php
                            if ($value == 'oblique') {
                                echo 'selected';
                            }
                            ?> value="oblique">Oblique</option>
                    <option <?php
                            if ($value == 'initial') {
                                echo 'selected';
                            }
                            ?> value="initial">Initial</option>
                    <option <?php
                            if ($value == 'inherit') {
                                echo 'selected';
                            }
                            ?> value="inherit">Inherit</option>
                </select>
            </div>
        </div>
    <?php
    }
    public function oxilab_flip_box_admin_true_false($id, $value, $fristname, $fristvalue, $Secondname, $Secondvalue, $name, $title)
    {
?>
        <div class="form-group row">
            <label class="col-sm-6 control-label" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($title); ?>"><?php echo esc_html($name); ?></label>
            <div class="col-sm-6">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-primary <?php
                                                    if ($fristvalue == $value) {
                                                        echo 'active';
                                                    }
                                                    ?>"> <input type="radio" <?php
                                                                                if ($fristvalue == $value) {
                                                                                    echo 'checked';
                                                                                }
                                                                                ?> name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>-yes" autocomplete="off" value="<?php echo esc_attr($fristvalue); ?>"><?php echo esc_html($fristname); ?></label>
                    <label class="btn btn-primary <?php
                                                    if ($Secondvalue == $value) {
                                                        echo 'active';
                                                    }
                                                    ?>"> <input type="radio" <?php
                                                                                if ($Secondvalue == $value) {
                                                                                    echo 'checked';
                                                                                }
                                                                                ?> name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>-no" autocomplete="off" value="<?php echo esc_attr($Secondvalue); ?>"><?php echo esc_html($Secondname); ?> </label>
                </div>
            </div>
        </div>
    <?php
    }
    public function oxilab_flip_box_admin_text_align($id, $value, $name, $title)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="<?php echo esc_attr($id); ?>" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($title); ?>"><?php echo esc_html($name); ?> </label>
            <div class="col-sm-6">
                <select class="form-control" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>">
                    <option value="left" <?php
                                            if ($value == 'left') {
                                                echo 'selected';
                                            }
                                            ?>>Left</option>
                    <option value="Center" <?php
                                            if ($value == 'Center') {
                                                echo 'selected';
                                            }
                                            ?>>Center</option>
                    <option value="Right" <?php
                                            if ($value == 'Right') {
                                                echo 'selected';
                                            }
                                            ?>>Right</option>
                </select>
            </div>
        </div>
    <?php
    }

 

    public function oxilab_flip_box_admin_col_data($id, $value, $name, $title)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="<?php echo esc_attr($id); ?>" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($title); ?>"><?php echo esc_html($name); ?> </label>
            <div class="col-sm-6">
                <select class="form-control" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>">
                    <option value="oxilab-flip-box-col-1" <?php
                                                            if ($value == 'oxilab-flip-box-col-1') {
                                                                echo 'selected';
                                                            }
                                                            ?>>Single Item</option>
                    <option value="oxilab-flip-box-col-2" <?php
                                                            if ($value == 'oxilab-flip-box-col-2') {
                                                                echo 'selected';
                                                            }
                                                            ?>>2 Items</option>
                    <option value="oxilab-flip-box-col-3" <?php
                                                            if ($value == 'oxilab-flip-box-col-3') {
                                                                echo 'selected';
                                                            }
                                                            ?>>3 Items</option>
                    <option value="oxilab-flip-box-col-4" <?php
                                                            if ($value == 'oxilab-flip-box-col-4') {
                                                                echo 'selected';
                                                            }
                                                            ?>>4 Items</option>
                    <option value="oxilab-flip-box-col-5" <?php
                                                            if ($value == 'oxilab-flip-box-col-5') {
                                                                echo 'selected';
                                                            }
                                                            ?>>5 Items</option>
                    <option value="oxilab-flip-box-col-6" <?php
                                                            if ($value == 'oxilab-flip-box-col-6') {
                                                                echo 'selected';
                                                            }
                                                            ?>>6 Items</option>
                </select>
            </div>
        </div>
    <?php
    }

    public function oxilab_flip_box_admin_animation_select($value)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="oxilab-animation" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Select your Viewing Animation">Animation </label>
            <div class="col-sm-6">
                <select class="form-control" id="oxilab-animation" name="oxilab-animation">
                    <optgroup label="No Animation">
                        <option value="" <?php
                                            if ($value == '') {
                                                echo 'selected';
                                            }
                                            ?>>No Animation</option>
                    </optgroup>
                    <optgroup label="Attention Seekers">
                        <option value="bounce" <?php
                                                if ($value == 'bounce') {
                                                    echo 'selected';
                                                }
                                                ?>>bounce</option>
                        <option value="flash" <?php
                                                if ($value == 'flash') {
                                                    echo 'selected';
                                                }
                                                ?>>flash</option>
                        <option value="pulse" <?php
                                                if ($value == 'pulse') {
                                                    echo 'selected';
                                                }
                                                ?>>pulse</option>
                        <option value="rubberBand" <?php
                                                    if ($value == 'rubberBand') {
                                                        echo 'selected';
                                                    }
                                                    ?>>rubberBand</option>
                        <option value="shake" <?php
                                                if ($value == 'shake') {
                                                    echo 'selected';
                                                }
                                                ?>>shake</option>
                        <option value="swing" <?php
                                                if ($value == 'swing') {
                                                    echo 'selected';
                                                }
                                                ?>>swing</option>
                        <option value="tada" <?php
                                                if ($value == 'tada') {
                                                    echo 'selected';
                                                }
                                                ?>>tada</option>
                        <option value="wobble" <?php
                                                if ($value == 'wobble') {
                                                    echo 'selected';
                                                }
                                                ?>>wobble</option>
                        <option value="jello" <?php
                                                if ($value == 'jello') {
                                                    echo 'selected';
                                                }
                                                ?>>jello</option>
                    </optgroup>

                    <optgroup label="Bouncing Entrances">
                        <option value="bounceIn" <?php
                                                    if ($value == 'bounceIn') {
                                                        echo 'selected';
                                                    }
                                                    ?>>bounceIn</option>
                        <option value="bounceInDown" <?php
                                                        if ($value == 'bounceInDown') {
                                                            echo 'selected';
                                                        }
                                                        ?>>bounceInDown</option>
                        <option value="bounceInLeft" <?php
                                                        if ($value == 'bounceInLeft') {
                                                            echo 'selected';
                                                        }
                                                        ?>>bounceInLeft</option>
                        <option value="bounceInRight" <?php
                                                        if ($value == 'bounceInRight') {
                                                            echo 'selected';
                                                        }
                                                        ?>>bounceInRight</option>
                        <option value="bounceInUp" <?php
                                                    if ($value == 'bounceInUp') {
                                                        echo 'selected';
                                                    }
                                                    ?>>bounceInUp</option>
                    </optgroup>
                    <optgroup label="Fading Entrances">
                        <option value="fadeIn" <?php
                                                if ($value == 'fadeIn') {
                                                    echo 'selected';
                                                }
                                                ?>>fadeIn</option>
                        <option value="fadeInDown" <?php
                                                    if ($value == 'fadeInDown') {
                                                        echo 'selected';
                                                    }
                                                    ?>>fadeInDown</option>
                        <option value="fadeInDownBig" <?php
                                                        if ($value == 'fadeInDownBig') {
                                                            echo 'selected';
                                                        }
                                                        ?>>fadeInDownBig</option>
                        <option value="fadeInLeft" <?php
                                                    if ($value == 'fadeInLeft') {
                                                        echo 'selected';
                                                    }
                                                    ?>>fadeInLeft</option>
                        <option value="fadeInLeftBig" <?php
                                                        if ($value == 'fadeInLeftBig') {
                                                            echo 'selected';
                                                        }
                                                        ?>>fadeInLeftBig</option>
                        <option value="fadeInRight" <?php
                                                    if ($value == 'fadeInRight') {
                                                        echo 'selected';
                                                    }
                                                    ?>>fadeInRight</option>
                        <option value="fadeInRightBig" <?php
                                                        if ($value == 'fadeInRightBig') {
                                                            echo 'selected';
                                                        }
                                                        ?>>fadeInRightBig</option>
                        <option value="fadeInUp" <?php
                                                    if ($value == 'fadeInUp') {
                                                        echo 'selected';
                                                    }
                                                    ?>>fadeInUp</option>
                        <option value="fadeInUpBig" <?php
                                                    if ($value == 'fadeInUpBig') {
                                                        echo 'selected';
                                                    }
                                                    ?>>fadeInUpBig</option>
                    </optgroup>

                    <optgroup label="Fading Exits">
                        <option value="fadeOut" <?php
                                                if ($value == 'fadeOut') {
                                                    echo 'selected';
                                                }
                                                ?>>fadeOut</option>
                        <option value="fadeOutDown" <?php
                                                    if ($value == 'fadeOutDown') {
                                                        echo 'selected';
                                                    }
                                                    ?>>fadeOutDown</option>
                        <option value="fadeOutDownBig" <?php
                                                        if ($value == 'fadeOutDownBig') {
                                                            echo 'selected';
                                                        }
                                                        ?>>fadeOutDownBig</option>
                        <option value="fadeOutLeft" <?php
                                                    if ($value == 'fadeOutLeft') {
                                                        echo 'selected';
                                                    }
                                                    ?>>fadeOutLeft</option>
                        <option value="fadeOutLeftBig" <?php
                                                        if ($value == 'fadeOutLeftBig') {
                                                            echo 'selected';
                                                        }
                                                        ?>>fadeOutLeftBig</option>
                        <option value="fadeOutRight" <?php
                                                        if ($value == 'fadeOutRight') {
                                                            echo 'selected';
                                                        }
                                                        ?>>fadeOutRight</option>
                        <option value="fadeOutRightBig" <?php
                                                        if ($value == 'fadeOutRightBig') {
                                                            echo 'selected';
                                                        }
                                                        ?>>fadeOutRightBig</option>
                        <option value="fadeOutUp" <?php
                                                    if ($value == 'fadeOutUp') {
                                                        echo 'selected';
                                                    }
                                                    ?>>fadeOutUp</option>
                        <option value="fadeOutUpBig" <?php
                                                        if ($value == 'fadeOutUpBig') {
                                                            echo 'selected';
                                                        }
                                                        ?>>fadeOutUpBig</option>
                    </optgroup>

                    <optgroup label="Flippers">
                        <option value="flip" <?php
                                                if ($value == 'flip') {
                                                    echo 'selected';
                                                }
                                                ?>>flip</option>
                        <option value="flipInX" <?php
                                                if ($value == 'flipInX') {
                                                    echo 'selected';
                                                }
                                                ?>>flipInX</option>
                        <option value="flipInY" <?php
                                                if ($value == 'flipInY') {
                                                    echo 'selected';
                                                }
                                                ?>>flipInY</option>
                        <option value="flipOutX" <?php
                                                    if ($value == 'flipOutX') {
                                                        echo 'selected';
                                                    }
                                                    ?>>flipOutX</option>
                        <option value="flipOutY" <?php
                                                    if ($value == 'flipOutY') {
                                                        echo 'selected';
                                                    }
                                                    ?>>flipOutY</option>
                    </optgroup>

                    <optgroup label="Lightspeed">
                        <option value="lightSpeedIn" <?php
                                                        if ($value == 'lightSpeedIn') {
                                                            echo 'selected';
                                                        }
                                                        ?>>lightSpeedIn</option>
                        <option value="lightSpeedOut" <?php
                                                        if ($value == 'lightSpeedOut') {
                                                            echo 'selected';
                                                        }
                                                        ?>>lightSpeedOut</option>
                    </optgroup>

                    <optgroup label="Rotating Entrances">
                        <option value="rotateIn" <?php
                                                    if ($value == 'rotateIn') {
                                                        echo 'selected';
                                                    }
                                                    ?>>rotateIn</option>
                        <option value="rotateInDownLeft" <?php
                                                            if ($value == 'rotateInDownLeft') {
                                                                echo 'selected';
                                                            }
                                                            ?>>rotateInDownLeft</option>
                        <option value="rotateInDownRight" <?php
                                                            if ($value == 'rotateInDownRight') {
                                                                echo 'selected';
                                                            }
                                                            ?>>rotateInDownRight</option>
                        <option value="rotateInUpLeft" <?php
                                                        if ($value == 'rotateInUpLeft') {
                                                            echo 'selected';
                                                        }
                                                        ?>>rotateInUpLeft</option>
                        <option value="rotateInUpRight" <?php
                                                        if ($value == 'rotateInUpRight') {
                                                            echo 'selected';
                                                        }
                                                        ?>>rotateInUpRight</option>
                    </optgroup>
                    <optgroup label="Sliding Entrances">
                        <option value="slideInUp" <?php
                                                    if ($value == 'slideInUp') {
                                                        echo 'selected';
                                                    }
                                                    ?>>slideInUp</option>
                        <option value="slideInDown" <?php
                                                    if ($value == 'slideInDown') {
                                                        echo 'selected';
                                                    }
                                                    ?>>slideInDown</option>
                        <option value="slideInLeft" <?php
                                                    if ($value == 'slideInLeft') {
                                                        echo 'selected';
                                                    }
                                                    ?>>slideInLeft</option>
                        <option value="slideInRight" <?php
                                                        if ($value == 'slideInRight') {
                                                            echo 'selected';
                                                        }
                                                        ?>>slideInRight</option>
                    </optgroup>
                    <optgroup label="Zoom Entrances">
                        <option value="zoomIn" <?php
                                                if ($value == 'zoomIn') {
                                                    echo 'selected';
                                                }
                                                ?>>zoomIn</option>
                        <option value="zoomInDown" <?php
                                                    if ($value == 'zoomInDown') {
                                                        echo 'selected';
                                                    }
                                                    ?>>zoomInDown</option>
                        <option value="zoomInLeft" <?php
                                                    if ($value == 'zoomInLeft') {
                                                        echo 'selected';
                                                    }
                                                    ?>>zoomInLeft</option>
                        <option value="zoomInRight" <?php
                                                    if ($value == 'zoomInRight') {
                                                        echo 'selected';
                                                    }
                                                    ?>>zoomInRight</option>
                        <option value="zoomInUp" <?php
                                                    if ($value == 'zoomInUp') {
                                                        echo 'selected';
                                                    }
                                                    ?>>zoomInUp</option>
                    </optgroup>
                    <optgroup label="Specials">
                        <option value="hinge" <?php
                                                if ($value == 'hinge') {
                                                    echo 'selected';
                                                }
                                                ?>>hinge</option>
                        <option value="jackInTheBox" <?php
                                                        if ($value == 'jackInTheBox') {
                                                            echo 'selected';
                                                        }
                                                        ?>>jackInTheBox</option>
                        <option value="rollIn" <?php
                                                if ($value == 'rollIn') {
                                                    echo 'selected';
                                                }
                                                ?>>rollIn</option>
                    </optgroup>
                </select>
            </div>
        </div>
    <?php
    }
    public function oxilab_flip_box_admin_border($border_size, $border_size_value, $border_type, $border_type_value, $name, $title)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="<?php echo esc_attr($border_size); ?>" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($title); ?>"><?php echo esc_html($name); ?> </label>
            <div class="col-sm-3">
                <input class="form-control" type="number" min="0" value="<?php echo esc_attr($border_size_value); ?>" id="<?php echo esc_attr($border_size); ?>" name="<?php echo esc_attr($border_size); ?>">
            </div>

            <div class="col-sm-3">
                <select class="form-control" id="<?php echo esc_attr($border_type); ?>" name="<?php echo esc_attr($border_type); ?>">
                    <option value="dotted" <?php
                                            if ($border_type_value == 'dotted') {
                                                echo 'selected';
                                            }
                                            ?>>Dotted</option>
                    <option value="dashed" <?php
                                            if ($border_type_value == 'dashed') {
                                                echo 'selected';
                                            }
                                            ?>>Dashed</option>
                    <option value="solid" <?php
                                            if ($border_type_value == 'solid') {
                                                echo 'selected';
                                            }
                                            ?>>Solid</option>
                    <option value="double" <?php
                                            if ($border_type_value == 'double') {
                                                echo 'selected';
                                            }
                                            ?>>Double</option>
                    <option value="groove" <?php
                                            if ($border_type_value == 'groove') {
                                                echo 'selected';
                                            }
                                            ?>>Groove</option>
                    <option value="ridge" <?php
                                            if ($border_type_value == 'ridge') {
                                                echo 'selected';
                                            }
                                            ?>>Ridge</option>
                    <option value="inset" <?php
                                            if ($border_type_value == 'inset') {
                                                echo 'selected';
                                            }
                                            ?>>Inset</option>
                    <option value="outset" <?php
                                            if ($border_type_value == 'outset') {
                                                echo 'selected';
                                            }
                                            ?>>Outset</option>
                    <option value="dotted solid" <?php
                                                    if ($border_type_value == 'dotted solid') {
                                                        echo 'selected';
                                                    }
                                                    ?>>Custom 1</option>
                    <option value="dotted solid dashed" <?php
                                                        if ($border_type_value == 'dotted solid dashed') {
                                                            echo 'selected';
                                                        }
                                                        ?>>Custom 2</option>
                    <option value="dotted solid dashed double" <?php
                                                                if ($border_type_value == 'dotted solid dashed double') {
                                                                    echo 'selected';
                                                                }
                                                                ?>>Custom 3</option>
                </select>
            </div>

        </div>
    <?php
    }
    public function oxilab_flip_box_admin_support()
    {
    ?>
        <div class="ihewc-admin-style-settings-div-css">
            <div class="col-xs-12">
                <a href="https://oxilabdemos.com/flipbox/docs/installations/how-to-install-the-plugin/" target="_blank">
                    <div class="col-xs-support-ihewc">
                        <div class="ihewc-admin-support-icon">
                            <i class="fas fa-file oxi-icons"></i>
                        </div>
                        <div class="ihewc-admin-support-heading">
                            Read Our Docs
                        </div>
                        <div class="ihewc-admin-support-info">
                            Learn how to set up and using Flipbox - Awesomes Flip Boxes Image Overlay
                        </div>
                    </div>
                </a>
                <a href="https://wordpress.org/support/plugin/image-hover-effects-ultimate-visual-composer" target="_blank">
                    <div class="col-xs-support-ihewc">
                        <div class="ihewc-admin-support-icon">
                            <i class="fas fa-users oxi-icons"></i>
                        </div>
                        <div class="ihewc-admin-support-heading">
                            Support
                        </div>
                        <div class="ihewc-admin-support-info">
                            Powered by WordPress.org, Issues resolved by Plugins Author.
                        </div>
                    </div>
                </a>
                <a href="https://www.youtube.com/watch?v=OaLL0DNUHWA" target="_blank">
                    <div class="col-xs-support-ihewc">
                        <div class="ihewc-admin-support-icon">
                            <i class="fas fa-ticket-alt oxi-icons"></i>
                        </div>
                        <div class="ihewc-admin-support-heading">
                            Video Tutorial
                        </div>
                        <div class="ihewc-admin-support-info">
                            Watch our Using Video Tutorial in Youtube.
                        </div>
                    </div>
                </a>
            </div>
        </div>
    <?php
    }

    public function image_upload($id, $value, $name, $title, $alt = '')
    {
    ?>
        <div class="form-group col-sm-12">
            <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></label>
            <div class="form-group row col-sm-12  mb-0">
                <div class="col-md-8 col-xs-6" style="padding-left: 0px;">
                    <input type="text" class="form-control" name="<?php echo esc_attr($id); ?>" id="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>">
                    <input type="hidden" class="form-control" name="<?php echo esc_attr($id); ?>-alt" id="<?php echo esc_attr($id); ?>-alt" value="<?php echo esc_attr($alt); ?>">

                </div>
                <div class="col-md-4 col-xs-6" style="padding-left: 0px;">
                    <button type="button" oxi-upload="#<?php echo esc_attr($id); ?>" class="flip-box-image-upload btn btn-outline-secondary" style="font-size: 12px;">Upload Image</button>
                </div>
            </div>
            <small class="form-text text-muted"><?php echo esc_html($title); ?></small>
        </div>
    <?php
    }

    public function allowed_html($rawdata)
    {
        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
            ),
            'abbr' => array(
                'title' => array(),
            ),
            'b' => array(),
            'blockquote' => array(
                'cite' => array(),
            ),
            'cite' => array(
                'title' => array(),
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array(),
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
                'id' => array(),
            ),
            'table' => array(
                'class' => array(),
                'id' => array(),
                'style' => array(),
            ),
            'button' => array(
                'class' => array(),
                'type' => array(),
                'value' => array(),
            ),
            'thead' => array(),
            'tbody' => array(),
            'tr' => array(),
            'td' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(),
            'h2' => array(),
            'h3' => array(),
            'h4' => array(),
            'h5' => array(),
            'h6' => array(),
            'i' => array(
                'class' => array(),
            ),
            'img' => array(
                'alt' => array(),
                'class' => array(),
                'height' => array(),
                'src' => array(),
                'width' => array(),
            ),
            'li' => array(
                'class' => array(),
            ),
            'ol' => array(
                'class' => array(),
            ),
            'p' => array(
                'class' => array(),
            ),
            'q' => array(
                'cite' => array(),
                'title' => array(),
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array(),
            ),
            'strike' => array(),
            'strong' => array(),
            'ul' => array(
                'class' => array(),
            ),
        );
        if (is_array($rawdata)) :
            return $rawdata = array_map(array($this, 'allowed_html'), $rawdata);
        else :
            return wp_kses($rawdata, $allowed_tags);
        endif;
    }
    public function oxilab_flip_box_admin_number($id, $value, $step, $name, $title)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="<?php echo esc_attr($id); ?>" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($title); ?>"><?php echo esc_html($name); ?> </label>
            <div class="col-sm-6">
                <input class="form-control" type="number" step="<?php echo esc_attr($step); ?>" value="<?php echo esc_attr($value); ?>" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>">
            </div>
        </div>
    <?php
    }

    public function oxilab_flip_box_admin_number_double($frist_id, $first_value, $second_id, $second_value, $name, $title)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="<?php echo esc_attr($frist_id); ?>" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($title); ?>"><?php echo esc_html($name); ?> </label>
            <div class="col-sm-3">
                <input class="form-control" type="number" min="0" value="<?php echo esc_attr($first_value); ?>" id="<?php echo esc_attr($frist_id); ?>" name="<?php echo esc_attr($frist_id); ?>">
            </div>
            <div class="col-sm-3">
                <input class="form-control" type="number" min="0" value="<?php echo esc_attr($second_value); ?>" id="<?php echo esc_attr($second_id); ?>" name="<?php echo esc_attr($second_id); ?>">
            </div>
        </div>
    <?php
    }

    public function oxilab_flip_box_admin_color($id, $value, $type, $name, $title, $exporttype, $exportid)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="<?php echo esc_attr($id); ?>" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($title); ?>"><?php echo esc_html($name); ?> </label>
            <div class="col-sm-6">
                <input type="text" <?php
                                    if ($type == 'rgba') {
                                        echo ' data-format="rgb" data-opacity="true" ';
                                    }
                                    ?> class="form-control oxilab-vendor-color" oxiexporttype="<?php echo esc_attr($exporttype); ?>" oxiexportid="<?php echo esc_attr($exportid); ?>" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>">
            </div>
        </div>

    <?php
    }

    public function oxilab_flip_box_admin_font_family($id, $value, $name, $title)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="<?php echo esc_attr($id); ?>" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="<?php echo esc_html($title); ?>"><?php echo esc_html($name); ?> </label>
            <div class="col-sm-6">
                <input class="form-control oxilab-admin-font" type="text" value="<?php echo esc_attr($value); ?>" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>">
            </div>
        </div>
    <?php
    }

    public function oxilab_flip_box_admin_input_text($id, $value, $name, $title)
    {
    ?>
        <div class="form-group col-sm-12">
            <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></label>
            <input type="text " class="form-control" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>">
            <small class="form-text text-muted"><?php echo esc_html($title); ?></small>
        </div>
    <?php
    }

    public function oxilab_flip_box_admin_input_icon($id, $value, $name, $title)
    {
    ?>
        <div class="form-group col-sm-12">
            <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></label>
            <input type="text " class="form-control flip_box_admin_input_icon" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>" value="<?php echo esc_attr($value); ?>">
            <small class="form-text text-muted"><?php echo esc_html($title); ?></small>
        </div>
    <?php
    }

    public function oxilab_flip_box_admin_input_text_area($id, $value, $name, $title)
    {
    ?>
        <div class="form-group col-sm-12">
            <label for="<?php echo esc_attr($id); ?>"><?php echo esc_html($name); ?></label>
            <textarea class="form-control" rows="4" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>"><?php echo esc_attr($value); ?></textarea>
            <small class="form-text text-muted"><?php echo esc_html($title); ?></small>
        </div>
    <?php
    }
    public function admin_special_charecter($data)
    {
        $data = str_replace('\\\\"', '&quot;', $data);
        $data = str_replace('\\\"', '&quot;', $data);
        $data = str_replace('\\"', '&quot;', $data);
        $data = str_replace('\"', '&quot;', $data);
        $data = str_replace('"', '&quot;', $data);
        $data = str_replace('\\\\&quot;', '&quot;', $data);
        $data = str_replace('\\\&quot;', '&quot;', $data);
        $data = str_replace('\\&quot;', '&quot;', $data);
        $data = str_replace('\&quot;', '&quot;', $data);
        $data = str_replace("\\\\'", '&apos;', $data);
        $data = str_replace("\\\'", '&apos;', $data);
        $data = str_replace("\\'", '&apos;', $data);
        $data = str_replace("\'", '&apos;', $data);
        $data = str_replace("\\\\&apos;", '&apos;', $data);
        $data = str_replace("\\\&apos;", '&apos;', $data);
        $data = str_replace("\\&apos;", '&apos;', $data);
        $data = str_replace("\&apos;", '&apos;', $data);
        $data = str_replace("'", '&apos;', $data);
        $data = str_replace('<', '&lt;', $data);
        $data = str_replace('>', '&gt;', $data);
        $data = sanitize_text_field($data);
        return $data;
    }

    public function oxilab_flip_box_flip_type_effects_type($flip_type, $effects_Type)
    {
    ?>
        <div class="form-group row form-group-sm">
            <label for="oxilab-flip-type" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Select Flip Type to use into this Flip">Flip Type </label>
            <div class="col-sm-6">
                <select class="form-control" id="oxilab-flip-type" name="oxilab-flip-type">
                    <option value="oxilab-flip-box-flip oxilab-flip-box-flip-top-to-bottom" <?php
                                                                                            if ($flip_type == 'oxilab-flip-box-flip oxilab-flip-box-flip-top-to-bottom') {
                                                                                                echo 'selected';
                                                                                            }
                                                                                            ?>>Flip top to Bottom</option>
                    <option value="oxilab-flip-box-flip oxilab-flip-box-flip-left-to-right" <?php
                                                                                            if ($flip_type == 'oxilab-flip-box-flip oxilab-flip-box-flip-left-to-right') {
                                                                                                echo 'selected';
                                                                                            }
                                                                                            ?>>Flip Left to Right</option>
                    <option value="oxilab-flip-box-flip oxilab-flip-box-flip-bottom-to-top" <?php
                                                                                            if ($flip_type == 'oxilab-flip-box-flip oxilab-flip-box-flip-bottom-to-top') {
                                                                                                echo 'selected';
                                                                                            }
                                                                                            ?>>Flip Bottom to Top</option>
                    <option value="oxilab-flip-box-flip oxilab-flip-box-flip-right-to-left" <?php
                                                                                            if ($flip_type == 'oxilab-flip-box-flip oxilab-flip-box-flip-right-to-left') {
                                                                                                echo 'selected';
                                                                                            }
                                                                                            ?>>Flip Right to Left</option>
                </select>
            </div>
        </div>
        <div class="form-group row form-group-sm">
            <label for="oxilab-flip-effects" class="col-sm-6 col-form-label" data-toggle="tooltip" data-placement="top" title="Customize Your Fliping Effects">Flip Effects </label>
            <div class="col-sm-6">
                <select class="form-control" id="oxilab-flip-effects" name="oxilab-flip-effects">
                    <option value="easing_easeInOutExpo" <?php
                                                            if ($effects_Type == 'easing_easeInOutExpo') {
                                                                echo 'selected';
                                                            }
                                                            ?>>easing_easeInOutExpo</option>
                    <option value="easing_easeInOutCirc" <?php
                                                            if ($effects_Type == 'easing_easeInOutCirc') {
                                                                echo 'selected';
                                                            }
                                                            ?>>easing_easeInOutCirc</option>
                    <option value="easing_easeOutBack" <?php
                                                        if ($effects_Type == 'easing_easeOutBack') {
                                                            echo 'selected';
                                                        }
                                                        ?>>easing_easeOutBack</option>
                </select>
            </div>
        </div>
<?php
    }
}
