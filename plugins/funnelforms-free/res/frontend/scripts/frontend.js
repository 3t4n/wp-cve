let forms = [];                                                                                                         // All Forms
let datas = [];                                                                                                         // All Data contents
let afincluded = false;
let extraParms = undefined;
let execForm = undefined;
let phoneInputElement;
let af2Styles = // The basic styling
        {
            "af2_answer":
                    [
                        {
                            "attribute": "width",
                            "value": "200px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "width",
                            "value": "100%",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "margin",
                            "value": "20px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "margin",
                            "value": "7px 15px",
                            "special_class": "af2_mobile"
                        },
                        /**{
                            "attribute": "max-width",
                            "value": "90%",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "min-width",
                            "value": "90%",
                            "special_class": "af2_mobile"
                        }**/
                    ],
            "af2_form_carousel":
                    [
                        {
                            "attribute": "margin",
                            "value": "0 auto 30px auto",
                        }
                    ],
                    "af2_desktop_list": 
                    [
                        {
                            "attribute": "font-size",
                            "value": "70px",
                            "special_class": "af2_answer_container .af2_answer.desktop .af2_answer_image_wrapper i",
                        }
                    ],
                    "af2_desktop_list2": 
                    [
                        {
                            "attribute": "font-size",
                            "value": "60px",
                            "special_class": "af2_answer_container .af2_answer.desktop .af2_answer_image_wrapper i",
                        }
                    ],
                    "af2_mobile_grid": 
                    [
                        {
                            "attribute": "font-size",
                            "value": "25px",
                            "special_class": "af2_answer_container .af2_answer.af2_mobile .af2_answer_image_wrapper.af2_mobile i",
                        }
                    ],
                    "af2_answer_card":
                    [
                        {
                            "attribute": "font-size",
                            "value": "90px",
                            "special_class": "desktop i"
                        },
                        {
                            "attribute": "font-size",
                            "value": "25px",
                            "special_class": "af2_mobile i"
                        },
                        {
                            "attribute": "height",
                            "value": "150px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "min-height",
                            "value": "52px",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "border-radius",
                            "value": "15px"
                        },
                        {
                            "attribute": "color",
                            "value": ""
                        },
                        {
                            "attribute": "background-color",
                            "value": "rgba(0, 0, 0, 0)"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1"
                        }
                    ],
            "af2_form_heading_wrapper":
                    [
                        {
                            "attribute": "margin",
                            "value": "30px auto 30px auto"
                        },
                        {
                            "attribute": "width",
                            "value": "100%"
                        }
                    ],
            "af2_form_heading":
                    [
                        {
                            "attribute": "color",
                            "value": ""
                        },
                        {
                            "attribute": "font-size",
                            "value": "",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-weight",
                            "value": ""
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "margin",
                            "value": "0 auto"
                        }
                    ],
            "af2_question_heading_wrapper":
                    [
                        {
                            "attribute": "margin",
                            "value": "0 auto 55px auto"
                        }
                    ],
            "af2_content_frage":
            [
                {
                    "attribute": "margin",
                    "value": "0 auto 0 auto"
                }
            ],
            "af2_dateiupload": [
                {
                    "attribute": "margin",
                    "value": "0 auto 0 auto"
                }
            ],
            "af2_dateiupload_inner": [
                {
                    "attribute": "border-radius",
                    "value": "10px"
                }
            ],
            "af2_ahref": [
                {
                    "attribute": "color",
                    "value": "rgba(157, 65, 221, 1) !important"
                }
            ],
            "af2_question_description":
                    [
                        {
                            "attribute": "color",
                            "value": ""
                        },
                        {
                            "attribute": "font-size",
                            "value": "20px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "18px",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-weight",
                            "value": "",
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "margin-top",
                            "value": "15px !important",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "margin-top",
                            "value": "15px !important",
                            "special_class": "af2_mobile"
                        }
                    ],
            "af2_question_heading":
                    [
                        {
                            "attribute": "color",
                            "value": ""
                        },
                        {
                            "attribute": "font-size",
                            "value": "32px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "24px",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-weight",
                            "value": "",
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "af2_mobile"
                        }
                    ],
            "af2_answer_image_wrapper":
                    [
                        {
                            "attribute": "padding",
                            "value": "0 7px"
                        },
                        {
                            "attribute": "width",
                            "value": "70px",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "margin-right",
                            "value": "10px",
                            "special_class": "af2_mobile"
                        }
                    ],
            "af2_answer_text":
                    [
                        {
                            "attribute": "color",
                            "value": ""
                        },
                        {
                            "attribute": "margin-left",
                            "value": "15px",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-size",
                            "value": "16px",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "line-height",
                            "value": "27px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "line-height",
                            "value": "20px",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-size",
                            "value": "17px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-weight",
                            "value": "",
                        }
                    ],
            "af2_form":
                    [
                        {
                            "attribute": "background-color",
                            "value": "rgba(255, 255, 255, 1)"
                        },
                        {
                            "attribute": "padding",
                            "value": "7px"
                        },
                        {
                            "attribute": "font-family",
                            "value": "inherit"
                        },
                        {
                            "attribute": "border-radius",
                            "value": "10px"
                        }
                    ],
            "af2_form_button":
                    [
                        {
                            "attribute": "background-color",
                            "value": ""
                        },
                        {
                            "attribute": "border-radius",
                            "value": "7px"
                        },
                        {
                            "attribute": "border",
                            "value": "none"
                        },
                        {
                            "attribute": "padding",
                            "value": "0 10px"
                        },
                        {
                            "attribute": "min-height",
                            "value": "50px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "color",
                            "value": "rgba(255, 255, 255, 1)"
                        },
                        {
                            "attribute": "fill",
                            "value": "rgba(255, 255, 255, 1)"
                        },
                        {
                            "attribute": "background-color",
                            "value": "#d7d7d7",
                            "special_class": "af2_disabled"
                        },
                        {
                            "attribute": "min-height",
                            "value": "40px",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-size",
                            "value": "18px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "16px",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-family",
                            "value": "Montserrat"
                        }
                    ],
            "af2_form_bottombar":
                    [
                        {
                            "attribute": "padding",
                            "value": "0 15px"
                        }
                    ],
            "af2_form_wrapper":
                    [
                        {
                            "attribute": "width",
                            "value": "100%"
                        },
                        {
                            "attribute": "max-width",
                            "value": "1250px"
                        }
                    ],
            "af2_textfeld_frage":
                    [
                        {
                            "attribute": "margin",
                            "value": "0 auto 50px auto !important"
                        },
                        {
                            "attribute": "border-radius",
                            "value": "7px"
                        },
                        {
                            "attribute": "height",
                            "value": "50px"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                        },
                        {
                            "attribute": "font-family",
                            "value": "inherit",
                        },
                        {
                            "attribute": "font-size",
                            "value": "",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-weight",
                            "value": ""
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "border",
                            "value": "",
                            "special_state": "focus"
                        }
                    ],
            "af2_datum_frage":
                    [
                        {
                            "attribute": "margin",
                            "value": "0 auto 20px auto !important"
                        },
                        {
                            "attribute": "border-radius",
                            "value": "7px"
                        },
                        {
                            "attribute": "height",
                            "value": "50px"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                        },
                        {
                            "attribute": "font-family",
                            "value": "inherit"
                        },
                        {
                            "attribute": "font-size",
                            "value": "",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-weight",
                            "value": ""
                        },
                        {
                            "attribute": "text-align",
                            "value": "center"
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "border",
                            "value": "",
                            "special_state": "focus"
                        }
                    ],
            "af2_slider_frage_wrapper":
                    [

                    ],
            "af2_slider_frage_bullet":
                    [
                        {
                            "attribute": "margin",
                            "value": "0 auto 20px auto"
                        },
                        {
                            "attribute": "color",
                            "value": "#333"
                        },
                        {
                            "attribute": "font-weight",
                            "value": "600"
                        },
                        {
                            "attribute": "font-size",
                            "value": "32px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "20px",
                            "special_class": "af2_mobile"
                        }
                    ],
            "af2_slider_frage":
                    [
                        {
                            "attribute": "margin",
                            "value": "0 auto 15px auto !important"
                        },
                        {
                            "attribute": "border-radius",
                            "value": "10px"
                        },
                        {
                            "attribute": "height",
                            "value": "15px"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                        },
                        {
                            "attribute": "background-color",
                            "value": "",
                        },
                        {
                            "attribute": "background-color",
                            "value": "#333",
                            "special_extra": "-webkit-slider-thumb"
                        },
                        {
                            "attribute": "background-color",
                            "value": "#333",
                            "special_extra": "-moz-range-thumb"
                        }
                    ],
                    "af2_slider_frage_val":
                    [
                        {
                            "attribute": "border",
                            "value": "",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                        },
                        {
                            "attribute": "font-size",
                            "value": "17px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "16px",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-weight",
                            "value": "500px"
                        }
                    ],
                    "af2_slider_frage_val_after":
                    [
                        {
                            "attribute": "border-radius",
                            "value": "10px 0 0 10px"
                        }
                    ],
                    "af2_slider_frage_val_before":
                    [
                        {
                            "attribute": "border-radius",
                            "value": "0 10px 10px 0"
                        }
                    ],
                    "select2-search__field":
                    [
                        {
                            "attribute": "border-radius",
                            "value": "10px"
                        }
                    ],
            "af2_textbereich_frage":
                    [
                        {
                            "attribute": "margin",
                            "value": "0 auto 50px auto !important"
                        },
                        {
                            "attribute": "border-radius",
                            "value": "7px"
                        },
                        {
                            "attribute": "height",
                            "value": "150px"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                        },
                        {
                            "attribute": "font-family",
                            "value": "inherit"
                        },
                        {
                            "attribute": "font-size",
                            "value": "",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-weight",
                            "value": ""
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "line-height",
                            "value": "",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "border",
                            "value": "",
                            "special_state": "focus"
                        }
                    ],
            "af2_question_wrapper":
                    [
                        {
                            "attribute": "width",
                            "value": "90%"
                        },
                        {
                            "attribute": "margin",
                            "value": "0 auto 25px !important"
                        },
                        {
                            "attribute": "color",
                            "value": ""
                        }
                    ],
            "af2_text_type":
                    [
                        {
                            "attribute": "border-radius",
                            "value": "0 7px 7px 0"
                        },
                        {
                            "attribute": "height",
                            "value": "47px"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                        },
                        {
                            "attribute": "border",
                            "value": "1px solid",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "font-family",
                            "value": "inherit"
                        },
                        {
                            "attribute": "font-size",
                            "value": ""
                        },
                        {
                            "attribute": "font-weight",
                            "value": ""
                        },
                        {
                            "attribute": "padding",
                            "value": ""
                        },
                        {
                            "attribute": "max-width",
                            "value": "100%"
                        },
                        {
                            "attribute": "width",
                            "value": "100%"
                        },
                        {
                            "attribute": "color",
                            "value": ""
                        }
                    ],
                    "af2_text_type.af2_rtl_layout":
                    [
                        {
                            "attribute": "border-radius",
                            "value": "7px 0 0 7px"
                        }
                    ],
                    "af2_text_type_":
                    [
                        {
                            "attribute": "border-radius",
                            "value": "7px 7px 7px 7px"
                        },
                        {
                            "attribute": "height",
                            "value": "47px"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "box-shadow",
                            "value": "5px 5px 15px 0 #e1e1e1",
                        },
                        {
                            "attribute": "border",
                            "value": "1px solid",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "font-family",
                            "value": "inherit"
                        },
                        {
                            "attribute": "font-size",
                            "value": ""
                        },
                        {
                            "attribute": "font-weight",
                            "value": ""
                        },
                        {
                            "attribute": "padding",
                            "value": ""
                        },
                        {
                            "attribute": "max-width",
                            "value": "100%"
                        },
                        {
                            "attribute": "width",
                            "value": "100%"
                        },
                        {
                            "attribute": "color",
                            "value": ""
                        }
                    ],
            "af2_checkbox_type":
                    [
                        {
                            "attribute": "margin-right",
                            "value": "15px"
                        }
                    ],
            "af2_answer.selected_item .af2_answer_card":
                    [
                        {
                            "attribute": "border",
                            "value": "5px 5px 15px 0 #e1e1e1"
                        }
                    ],
            "af2_form_progress_bar":
                    [
                        {
                            "attribute": "width",
                            "value": "100%"
                        },
                        {
                            "attribute": "height",
                            "value": "8px"
                        },
                        {
                            "attribute": "border-radius",
                            "value": "15px"
                        },
                        {
                            "attribute": "border",
                            "value": ""
                        },
                        {
                            "attribute": "background-color",
                            "value": "white"
                        },
                        {
                            "attribute": "margin",
                            "value": "21px"
                        }
                    ],
            "af2_form_progress_bar_wrapper":
                    [
                        {
                            "attribute": "margin",
                            "value": "0 15px"
                        },
                        {
                            "attribute": "width",
                            "value": "100%"
                        }
                    ],
            "af2_form_progress":
                    [
                        {
                            "attribute": "border-radius",
                            "value": "15px"
                        },
                        {
                            "attribute": "background-color",
                            "value": ""
                        }
                    ],
            "af2_form_percentage":
                    [
                        {
                            "attribute": "width",
                            "value": "50px"
                        },
                        {
                            "attribute": "height",
                            "value": "25px"
                        },
                        {
                            "attribute": "background-color",
                            "value": ""
                        },
                        {
                            "attribute": "color",
                            "value": "#ffffff"
                        }
                    ],
            "af2_form_percentage_triangle":
                    [
                        {
                            "attribute": "border-color",
                            "value": ""
                        }
                    ],
            "af2_multiselect_style":
                    [
                        {
                            "attribute": "font-size",
                            "value": "24px",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "20px",
                            "special_class": "af2_mobile"
                        }
                    ],
            "af2_question_label":
                    [
                        {
                            "attribute": "font-size",
                            "value": "17",
                            "special_class": "desktop"
                        },
                        {
                            "attribute": "font-size",
                            "value": "15",
                            "special_class": "af2_mobile"
                        },
                        {
                            "attribute": "font-weight",
                            "value": ""
                        },
                        {
                            "attribute": "margin-bottom",
                            "value": "8px !important"
                        }
                    ],
                    "af2_question":
                    [
                        {
                            "attribute": "margin",
                            "value": "0 auto 0 auto"
                        }
                    ],
            "af2_submit_button":
                    [
                        {
                            "attribute": "font-size",
                            "value": ""
                        },
                        {
                            "attribute": "font-weight",
                            "value": ""
                        },
                        {
                            "attribute": "padding",
                            "value": ""
                        },
                        {
                            "attribute": "padding-left",
                            "value": ""
                        },
                        {
                            "attribute": "padding-right",
                            "value": ""
                        },
                        {
                            "attribute": "border-radius",
                            "value": ""
                        },
                        {
                            "attribute": "background-color",
                            "value": ""
                        },
                        {
                            "attribute": "outline",
                            "value": "none",
                            "special_state": "focus"
                        },
                        {
                            "attribute": "border",
                            "value": "none"
                        },
                        {
                            "attribute": "transition",
                            "value": "all 400ms ease-out"
                        },
                        {
                            "attribute": "color",
                            "value": ""
                        },
                        {
                            "attribute": "margin",
                            "value": "20px auto 0 auto !important"
                        },
                        {
                            "attribute": "white-space",
                            "value": 'normal'
                        },
                        {
                            "attribute": "width",
                            "value": "50%"
                        },
                        {
                            "attribute": "font-family",
                            "value": ""
                        },
                        {
                            "attribute": "--rgb",
                            "value": ""
                        },
                        {
                            "attribute": "--rgbcol",
                            "value": ""
                        },
                    ],
            "af2_question_cb_label":
                    [
                        {
                            "attribute": "font-size",
                            "value": ""
                        },
                        {
                            "attribute": "font-weight",
                            "value": ""
                        }
                    ],
            "af2_datepicker_header":
                    [
                        {
                            "attribute": "background-color",
                            "value": "",
                            "special_class": "af2_datepicker",
                            "sub_class": "ui-datepicker-title"
                        },
                        {
                            "attribute": "color",
                            "value": "",
                            "special_class": "af2_datepicker",
                            "sub_class": "ui-datepicker-title"
                        }
                    ],
            "ui-datepicker-title":
            [
                {
                    "attribute": "font-family",
                    "value": "Montserrat"
                },
            ],
            "desktop .ui-datepicker-title":
            [
                {
                    "attribute": "font-size",
                    "value": "17px"
                }
            ],
            "af2_mobile .ui-datepicker-title":
            [
                {
                    "attribute": "font-size",
                    "value": "15px"
                }
            ],
            "af2_datepicker_active":
                    [
                        {
                            "attribute": "background-color",
                            "value": "",
                            "special_class": "af2_datepicker",
                            "sub_class": "ui-datepicker-current-day"
                        },
                        {
                            "attribute": "color",
                            "value": "",
                            "special_class": "af2_datepicker",
                            "sub_class": "ui-state-active"
                        }
                    ],
            "af2_datepicker_buttons":
                    [
                        {
                            "attribute": "background-color",
                            "value": "#fff",
                            "special_class": "af2_datepicker",
                            "sub_class": "ui-datepicker-prev"
                        },
                        {
                            "attribute": "color",
                            "value": "#3a3a3a",
                            "special_class": "af2_datepicker",
                            "sub_class": "ui-datepicker-prev"
                        },
                        {
                            "attribute": "background-color",
                            "value": "#fff",
                            "special_class": "af2_datepicker",
                            "sub_class": "ui-datepicker-next"
                        },
                        {
                            "attribute": "color",
                            "value": "#3a3a3a",
                            "special_class": "af2_datepicker",
                            "sub_class": "ui-datepicker-next"
                        }
                ],
                "af2_form_html_content":
                [
                    {
                        "attribute": "font-family",
                        "value": "inherit"
                    },
                    {
                        "attribute": "font-size",
                        "value": ""
                    },
                    {
                        "attribute": "font-weight",
                        "value": ""
                    }
                ],
                "af2_radio_label": [
                    {
                        "attribute": "font-size",
                        "value": ""
                    },
                    {
                        "attribute": "font-weight",
                        "value": ""
                    },
                ],
                "af2_question_cf_text_type_icon": [
                    {
                        "attribute": "width",
                        "value": "70px"
                    },
                    {
                        "attribute": "height",
                        "value": "50px"
                    },
                    {
                        "attribute": "background-color",
                        "value": "rgba(157, 65, 221, 1)"
                    },
                    {
                        "attribute": "border-radius",
                        "value": "10px 0 0 10px"
                    }
                ],
                "af2_question_cf_text_type_icon.af2_rtl_layout": [
                    {
                        "attribute": "border-radius",
                        "value": "0 10px 10px 0"
                    }
                ],
                "af2_ad_trans":
                [
                    {
                        "attribute": "font-family",
                        "value": "inherit"
                    },
                    {
                        "attribute": "height",
                        "value": "50px"
                    },
                    {
                        "attribute": "border-radius",
                        "value": "10px"
                    },
                    {
                        "attribute": "border",
                        "value": "",
                        "special_state": "focus"
                    },
                    {
                        "attribute": "box-shadow",
                        "value": "",
                        "special_state": "focus"
                    },
                    {
                        "attribute": "box-shadow",
                        "value": ""
                    },
                    {
                        "attribute": "box-shadow",
                        "value": "5px 5px 15px 0 #e1e1e1"
                    },
                    {
                        "attribute": "font-size",
                        "value": "17px",
                        "special_class": "desktop"
                    },
                    {
                        "attribute": "font-size",
                        "value": "15px",
                        "special_class": "af2_mobile"
                    },
                    {
                        "attribute": "font-weight",
                        "value": "500",
                    }
                ],
                "af2_slider_input_wrap":
                [
                    {
                        "attribute": "height",
                        "value": "50px"
                    },
                ],
                "af2_address_field_":
                [
                    {
                        "attribute": "border-radius",
                        "value": "10px"
                    }
                ],
                "af2_adress_map_input_wrapper":
                [
                    {
                        "attribute": "border-radius",
                        "value": "10px"
                    }
                ],
                "af2_html_content_summary":
                [
                    {
                        "attribute": "border-radius",
                        "value": "10px"
                    }
                ],
                "af2_ad_trans_tabel":
                [
                    {
                        "attribute": "font-size",
                        "value": "17px",
                        "special_class": "desktop"
                    },
                    {
                        "attribute": "font-size",
                        "value": "16px",
                        "special_class": "af2_mobile"
                    },
                    {
                        "attribute": "font-weight",
                        "value": "500",
                    },
                ],
                "af2_html_content_summary_object_title_":
                [
                    {
                        "attribute": "font-size",
                        "value": "17px",
                        "special_class": "desktop"
                    },
                    {
                        "attribute": "font-size",
                        "value": "16px",
                        "special_class": "af2_mobile"
                    },
                    {
                        "attribute": "font-weight",
                        "value": "700",
                    },
                ],
                "af2_html_content_summary_object_answer_":
                [
                    {
                        "attribute": "font-size",
                        "value": "17px",
                        "special_class": "desktop"
                    },
                    {
                        "attribute": "font-size",
                        "value": "16px",
                        "special_class": "af2_mobile"
                    },
                    {
                        "attribute": "font-weight",
                        "value": "500",
                    },
                ],
                "alternate_text_wrap_span":
                [
                    {
                        "attribute": "background-color",
                        "value": "rgba(157, 65, 221, 1)"
                    },
                    {
                        "attribute": "font-size",
                        "value": "17px",
                        "special_class": "desktop"
                    },
                    {
                        "attribute": "font-size",
                        "value": "16px",
                        "special_class": "af2_mobile"
                    },
                    {
                        "attribute": "font-weight",
                        "value": "500"
                    }
                ],
                "alternate_text_wrap_span_after":
                [
                    {
                        "attribute": "border-radius",
                        "value": "0 10px 10px 0"
                    }
                ],
                "alternate_text_wrap_span_before":
                [
                    {
                        "attribute": "border-radius",
                        "value": "10px 0 0 10px"
                    }
                ],
                "range_text_box_label":
                [
                    {
                        "attribute": "font-size",
                        "value": "17px",
                        "special_class": "desktop"
                    },
                    {
                        "attribute": "font-size",
                        "value": "16px",
                        "special_class": "af2_mobile"
                    },
                    {
                        "attribute": "font-weight",
                        "value": "500"
                    }
                ],
                "af2_response_error":
                [
                    {
                        "attribute": "font-size",
                        "value": "17px"
                    },
                    {
                        "attribute": "font-weight",
                        "value": "500"
                    }
                ],
                "af2-select2-container input.select2-search__field":
                [
                    {
                        "attribute": "border",
                        "value": "",
                        "special_state": "focus",
                        "special_class": "form_class"
                    },
                    {
                        "attribute": "border-radius",
                        "value": "",
                        "special_class": "form_class"
                    },
                    {
                        "attribute": "box-shadow",
                        "value": "",
                        "special_state": "focus",
                        "special_class": "form_class"
                    },
                    {
                        "attribute": "box-shadow",
                        "value": "5px 5px 15px 0 #e1e1e1",
                        "special_class": "form_class"
                    },
                    {
                        "attribute": "font-weight",
                        "value": "500px",
                        "special_class": "form_class"
                    },
                    {
                        "attribute": "font-family",
                        "value": "Montserrat",
                        "special_class": "form_class"
                    }
                ],
                "af2-select2-container.desktop input.select2-search__field":
                [
                    {
                        "attribute": "font-size",
                        "value": "17px",
                        "special_class": "form_class"
                    }
                ],
                "af2-select2-container.af2_mobile input.select2-search__field":
                [
                    {
                        "attribute": "font-size",
                        "value": "15px",
                        "special_class": "form_class"
                    }
                ],
                "select2-results__option.select2-results__option--selectable":
                [
                    {
                        "attribute": "font-weight",
                        "value": "500px",
                        "special_class": "form_class"
                    },
                    {
                        "attribute": "font-family",
                        "value": "Montserrat",
                        "special_class": "form_class"
                    }
                ],
                "af2-select2-container.desktop .select2-results__option.select2-results__option--selectable":
                [
                    {
                        "attribute": "font-size",
                        "value": "17px",
                        "special_class": "form_class"
                    }
                ],
                "af2-select2-container.af2_mobile .select2-results__option.select2-results__option--selectable":
                [
                    {
                        "attribute": "font-size",
                        "value": "15px",
                        "special_class": "form_class"
                    }
                ],
                "af2-select2-container.select2-selection.select2-selection--single":
                [
                    {
                        "attribute": "border",
                        "value": "",
                        "special_state": "focus",
                        "special_class": "form_class"
                    },
                    {
                        "attribute": "font-weight",
                        "value": "500px",
                        "special_class": "form_class"
                    },
                    {
                        "attribute": "font-family",
                        "value": "Montserrat",
                        "special_class": "form_class"
                    }
                ],
                "af2-select2-container.desktop.select2-selection.select2-selection--single":
                [
                    {
                        "attribute": "font-size",
                        "value": "17px",
                        "special_class": "form_class"
                    }
                ],
                "af2-select2-container.af2_mobile.select2-selection.select2-selection--single":
                [
                    {
                        "attribute": "font-size",
                        "value": "15px",
                        "special_class": "form_class"
                    }
                ],
                "af2-select2-container .select2-results__option.select2-results__option--selectable.select2-results__option--highlighted":
                [
                    {
                        "attribute": "background-color",
                        "value": "",
                        "special_class": "form_class"
                    }
                ],
                "af2_notification": [
                    {
                        "attribute": "--rgb",
                        "value": ""
                    },
                    {
                        "attribute": "--rgbcol",
                        "value": ""
                    }
                ],
                "af2_slider_image_icon_wrapper": [
                    {
                        "attribute": "color",
                        "value": ""
                    }
                ]
        };


/**
 * The Initialize Method
 */
const af2_init = ($) => {

    const errorAnswer = af2_frontend_ajax.datas.error;

    if(errorAnswer != null) {
    }
    else if(af2_frontend_ajax.datas.data != null)
    {
        let preloadJson = JSON.parse(af2_frontend_ajax.datas.data);

        const keys = Object.keys(preloadJson);

        $.each(keys, (i, el) => {
            let newJson = preloadJson[el];

            if(newJson.type_specifics != null) {
                const keysofjson = Object.keys(newJson.type_specifics);

                $.each(keysofjson, (j, ele) => {
                    if(newJson.type_specifics[ele] == 'true') newJson.type_specifics[ele] = true;
                    if(newJson.type_specifics[ele] == 'false') newJson.type_specifics[ele] = false;

                    if(ele == 'center' && newJson.type_specifics[ele].lat != null && newJson.type_specifics[ele].lng != null) {
                        newJson.type_specifics[ele].lat = Number(newJson.type_specifics[ele].lat);
                        newJson.type_specifics[ele].lng = Number(newJson.type_specifics[ele].lng);
                    }
                });
            }

            datas[el] = preloadJson[el];
        });
    }

    /** Filling the Forms with all Forms on the Screen **/
    $('.af2_form_wrapper').each((i, el) => {
        const id = jQuery(el).data('did');
        const num = jQuery(el).data('num');
        const preload = jQuery(el).data('preload');
        const size = jQuery(el).data('size');
        const errormail = jQuery(el).data('errormail');
        const activateScrollToAnchor = jQuery(el).data('activatescrolltoanchor');
        const showSuccessScreen = jQuery(el).data('showsuccessscreen');

        forms[num] = new Form($, id, num, preload, size, errormail, activateScrollToAnchor, false, showSuccessScreen);
    });

};


/**
 * For multiple forms on the screen
 *
 * @param $
 * @param id
 * @param num
 * @param preload
 * @param size
 * @constructor
 */
function Form($, id, num, preload, size, errormail, activateScrollToAnchor, popup, showSuccessScreen) {
    this.errormail = errormail;
    this.activateScrollToAnchor = activateScrollToAnchor;
    this.$ = $;                                                                                                         // The jQuery operator
    this.id = id;                                                                                                       // Dataid
    this.num = num;                                                                                                     // Key of the element To call
    this.preload = preload;                                                                                             // Amount of preloads
    this.size = size;                                                                                                   // Max size (amount of sections)
    this.formSelector = '#af2_form_' + this.num;                                                                        // Selector for everything within this form
    this.actualSection = 0;                                                                                             // Section the Form is actually in
    this.actualContent = 0;                                                                                             // Content the Form is actually showing
    this.neededContent = undefined;                                                                                     // The neededContent to draw
    this.actualData = undefined;                                                                                        // The actual Dataid
    this.actualCarouselItem = 0;
    this.beforeSection = [];
    this.beforeContent = [];
    this.needsDraw = true;
    this.answers = [];
    this.set = false;
    this.af2_is_send_allowed = false;
    this.popup = popup;
    this.resizeListener_init = false;
    this.stor = {};
    this.attachment_ids = [];
    this.rtl_layout = $(this.formSelector).data('rtl');

    this.contactFormAnswers = [];

    this.showSuccessScreen = showSuccessScreen;

    this.verifictationId = undefined;
    this.verifictationDataId = undefined;

    this.resendTimer = undefined;
    this.resendValue = 10;

    this.answerObject = [];
    this.firstQuestion = true;
    //this.autocomplete = undefined;
    //this.autocomplete2 = undefined;
    //this.map = undefined;
    
    //this.markers = [];


    if (this.preload > this.size - 1)
        this.preload = this.size - 1;

    
    this.setLastAddrObject = (value) => {
        this.lastAdressObj = value;
    }

    this.$(this.formSelector).on('af2_loaded_font', (ev) => {
        this.setHeight();
    });


    /**
     * When Loaded the Content for the Form
     */
    this.$(this.formSelector).on('loadedData', (ev) => {
        af2CompareAttributeInArray(this.$, this.id, ev.dataids).done(() => {
            af2LoadStyling(this.$, this.id, this.formSelector, this.setHeight);
            this.loadContent();
        });
    });

    this.$(this.formSelector + ' .af2_form_carousel').on('loadedData', (ev) => {
        if (this.neededContent !== undefined)
        {
            af2CompareAttributeInArray(this.$, this.neededContent, ev.dataids).done(() => {
                this.initDraw();
            });
        }
    });

    

    /**
     * Verify and load the new Content
     */
    this.loadContent = (goBefore) => {
        const prom = $.Deferred();

        if(datas[this.id].sections == undefined) {
            prom.resolve();
        } else {

            const redirectHelper = datas[this.id].sections[this.actualSection].contents[this.actualContent];
            this.neededContent = datas[this.id].sections[this.actualSection].contents[this.actualContent].data;
            /** Check the redirect **/
            if (this.neededContent.includes('redirect'))
            {
                if(typeof af2_doOwnExternalRedirectFunction === 'function')
                {
                    af2_doOwnExternalRedirectFunction(this.$, this.answerObject).done((newval) => {
                            if(newval) {
                                this.af2GoNext(param).done((cont) => {
                                    this.beforeSection.push(cont[0]);
                                    this.beforeContent.push(cont[1]);

                                    if(this.beforeSection.length > 0) {
                                        jQuery(this.formSelector + ' .af2_form_back_button').removeClass('af2_disabled');
                                    } else {
                                        jQuery(this.formSelector + ' .af2_form_back_button').addClass('af2_disabled');
                                    }
                                });
                            }
                            else {
                                //
                            }
                    });
                }
                else {
                    if(redirectHelper.newtab == 'true' || redirectHelper.newtab == true) {
                        jQuery(this.formSelector + ' .af2_form .af2_form_heading_wrapper').remove();
                        jQuery(this.formSelector + ' .af2_form .af2_form_carousel').remove();
                        jQuery(this.formSelector + ' .af2_form .af2_form_bottombar').remove();
                        
                        let link = this.neededContent.substr(9);

                        try {
                            let objs = window.btoa(JSON.stringify(this.answerObject));
                            link = link.replace(af2_frontend_ajax.strings.antworten_tag, objs);
                        }
                        catch(e) {

                        }

                        window.open(link, '_blank');
                    } else if(redirectHelper.newtab == 'false' || redirectHelper.newtab == false) {
                        let link = this.neededContent.substr(9);

                        try {
                            let objs = window.btoa(JSON.stringify(this.answerObject))
                            link = link.replace(af2_frontend_ajax.strings.antworten_tag, objs);
                        }
                        catch(e) {

                        }
                        
                        window.location.href = link;
                    }else if(redirectHelper.newtab == null || redirectHelper.newtab == '') {
                        let link = this.neededContent.substr(9);

                        try {
                            let objs = window.btoa(JSON.stringify(this.answerObject));
                            link = link.replace(af2_frontend_ajax.strings.antworten_tag, objs);
                        }
                        catch(e) {

                        }

                        window.location.href = link;
                    }
                }
                

                prom.reject();
            } else
            {
                if (datas[this.neededContent] !== undefined && datas[this.neededContent] !== true)
                {
                    this.initDraw(goBefore);
                }
                this.iteratePreloads().done((dataids) => {
                    if (dataids.length > 0)
                    {
                        af2HandleRequest($, this.formSelector, '.af2_form_carousel', dataids, this);
                    }
                });
                if (datas[this.neededContent] !== undefined && datas[this.neededContent] !== true)
                {
                    this.initDraw(goBefore);
                }
                prom.resolve();
            }
        }
        
        return prom.promise();
    };

    this.resizeListener = () => {
        if(!this.resizeListener_init) {
            this.$(window).resize(() => {
                const width = this.$(this.formSelector+' .af2_form_heading.desktop').css('display') == 'none' ? this.$(this.formSelector+' .af2_form_heading.af2_mobile').width() : this.$(this.formSelector+' .af2_form_heading.desktop').width();
                // RESIZE DO
                //this.$(this.formSelector+ ' .af2_form_carousel').css('max-width', width + 'px');
                //this.$(this.formSelector+ ' .af2_form_carousel').css('min-width', width + 'px');
                this.$(this.formSelector+ ' .af2_form_carousel').css('max-width', 'unset');
                this.$(this.formSelector+ ' .af2_form_carousel').css('min-width', 'unset');

                this.setHeight();
            });
            this.resizeListener_init = true;
        }
    }

    this.initDraw = (goBefore) => {
        if (this.needsDraw === true)
            af2DrawCarouselContent(this.$, this, this.neededContent, this.formSelector, this.actualCarouselItem, this.resizeListener);
        this.actualData = this.neededContent;
        this.neededContent = undefined;
        this.setHeight();
        this.setTriggers();

        if (datas[this.actualData].typ !== undefined &&
                (datas[this.actualData].typ === 'af2_slider' || datas[this.actualData].typ === 'af2_content' || datas[this.actualData].typ === 'af2_dropdown'))
        {
            //
            if(goBefore != true) jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
        } else if(datas[this.actualData].typ === 'af2_textfeld' || datas[this.actualData].typ === 'af2_textbereich_frage') {
            if (datas[this.actualData].type_specifics.mandatory == true) {
                //
                if(goBefore != true) jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
            } else {
                //
                if(goBefore != true) jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
            }
        }
        else if(datas[this.actualData].typ === 'af2_dateiupload') {

        }
        else
        {
            //
            /*if(goBefore != true)*/ jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
        }

        if(goBefore === true) {
            if(datas[this.actualData].af2_type == 'frage') {
                if(datas[this.actualData].typ === 'af2_select')         jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
                if(datas[this.actualData].typ === 'af2_multiselect')    jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
                if(datas[this.actualData].typ === 'af2_textfeld')       jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
                if(datas[this.actualData].typ === 'af2_textbereich')    jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
                if(datas[this.actualData].typ === 'af2_content')        jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
                if(datas[this.actualData].typ === 'af2_datum')          jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
                if(datas[this.actualData].typ === 'af2_dateiupload')    jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
                if(datas[this.actualData].typ === 'af2_dropdown')       jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
                if(datas[this.actualData].typ === 'af2_slider')         jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
                if(datas[this.actualData].typ === 'af2_adressfeld')     jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
            }
        }

        jQuery(this.formSelector + ' .af2_form_bottombar').css('opacity', 1);

        if (datas[this.actualData].typ === 'af2_content')
        {
            if (datas[this.actualData].type_specifics.content_button == true)
            {

                jQuery(this.formSelector + ' .af2_form_bottombar').css('opacity', 0);

                getAllImagesDonePromise($, this.formSelector).done(() => {
                    this.setHeight();

                });
            }

            if (datas[this.actualData].type_specifics.content_wait_time !== undefined && $.isNumeric(datas[this.actualData].type_specifics.content_wait_time))
            {
                //blend out everything
                if (datas[this.actualData].type_specifics.content_button == true)
                {
                    jQuery(this.formSelector + ' .af2_submit_wrapper input.af2_submit_button.no_send').css('opacity', 0);
                } else
                {
                    jQuery(this.formSelector + ' .af2_form_bottombar').css('opacity', 0);
                }


                //timer
                setTimeout(() => {
                    if (datas[this.actualData].type_specifics.content_button == true)
                    {
                        jQuery(this.formSelector + ' .af2_submit_wrapper input.af2_submit_button.no_send').css('opacity', 1);
                    } else
                    {
                        jQuery(this.formSelector + ' .af2_form_bottombar').css('opacity', 1);
                    }
                    this.af2Move("", 'next');
                }, datas[this.actualData].type_specifics.content_wait_time);
            }
        }

        if (datas[this.actualData].show_bottombar !== undefined && (datas[this.actualData].show_bottombar == 'false' || datas[this.actualData].show_bottombar == false))
        {
            jQuery(this.formSelector + ' .af2_form_bottombar').css('opacity', 0);
        }
        
        // set height for contact form
        if(datas[this.actualData].af2_type == 'kontaktformular'){
            setTimeout(this.setkontaktformularHeight,500);
        }
    };
    
    this.setkontaktformularHeight = () => {
        const height = jQuery(this.formSelector + ' .af2_form_carousel #' + this.actualCarouselItem + ' .af2_carousel_content').height();
        jQuery(this.formSelector+' .af2_form_carousel').css('height',height);
    }

    /**
     * Get all Needed preloads
     *
     * @returns {*}
     */
    this.iteratePreloads = () => {
        const prom = this.$.Deferred();
        let dataIds = [];

        /** Check neededContent first **/
        if (this.neededContent != undefined && datas[this.neededContent] === undefined)
        {
            dataIds.push(this.neededContent);
            datas[this.neededContent] = true;
        }

        if (datas[this.id].sections[this.actualSection].contents[this.actualContent].connections !== undefined)
        {
            /** Iterate the first part **/
            jQuery(datas[this.id].sections[this.actualSection].contents[this.actualContent].connections).each((i, el) => {
                const toSection = el.to_section;
                const toContent = el.to_content;

                /** Check the Data **/
                if (datas[el.to_dataid] === undefined && !el.to_dataid.includes('redirect'))
                {
                    dataIds.push(el.to_dataid);
                    datas[el.to_dataid] = true;

                    if (datas[this.id].sections[toSection].contents[toContent].connections !== undefined)
                    {
                        $.each(datas[this.id].sections[toSection].contents[toContent].connections, (j, e) => {

                            /** Check the Data **/
                            if (datas[e.to_dataid] === undefined && !e.to_dataid.includes('redirect')) {
                                dataIds.push(e.to_dataid);
                                datas[e.to_dataid] = true;
                            }
                        });
                    }
                }


                if (i === datas[this.id].sections[this.actualSection].contents[this.actualContent].connections.length - 1)
                {
                    prom.resolve(dataIds);
                }
            });

            if(datas[this.id].sections[this.actualSection].contents[this.actualContent].connections.length == 0) {
                prom.resolve(dataIds);
            }
        }

        return prom.promise();
    };

    /**
     * Setting all Triggers you need
     */
    this.setTriggers = () => {
        jQuery(document).on('keypress', (ev) => {
            const keycode = (ev.keyCode ? ev.keyCode : ev.which);
            if (keycode == '13') {

                if (!jQuery(this.formSelector + ' .af2_form_foward_button').hasClass('af2_disabled'))
                {
                    if (datas[this.actualData].typ === 'af2_multiselect')
                    {
                        let arr = [];
                        jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer.selected_item').each((i, el) => {
                            arr.push(jQuery(el).attr('id'));
                        }).promise().done(() => {
                            this.af2Move(arr, 'next');
                        });
                    } else if (datas[this.actualData].typ === 'af2_textfeld') 
                    {
                        if (af2_isMobileView($, this.formSelector)) {
                            this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textfeld_frage.af2_mobile').val(), 'next');
                        } else {
                            this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textfeld_frage').val(), 'next');
                        }
                    } else if (datas[this.actualData].typ === 'af2_datum')
                    {
                        if (af2_isMobileView($, this.formSelector)) {
                            this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_datum_frage.af2_mobile').val(), 'next');
                        } else {
                            this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_datum_frage').val(), 'next');
                        }
                    } else if (datas[this.actualData].typ === 'af2_dateiupload') {
                        let fileupload_class = '.af2_dateiupload';
                        if (af2_isMobileView($, this.formSelector)) {
                            fileupload_class = '.af2_dateiupload.af2_mobile';
                        } else {
                            fileupload_class = '.af2_dateiupload.desktop';
                        }
                        
                        let arr = [];
                        if(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item '+fileupload_class+' div[data-attachment_id]').length > 0){
                            jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item '+fileupload_class+' div[data-attachment_id]').each((i, el) => {
                                arr.push(jQuery(el).data('attachment_id'));
                            }).promise().done(() => {
                                this.attachment_ids = this.attachment_ids.concat(arr);
                                this.af2Move(arr, 'next');
                            });
                        }else{
                            this.af2Move(0, 'next');
                        }
                    } else if (datas[this.actualData].typ === 'af2_adressfeld') {
                        this.af2Move(this.lastAdressObj, 'next');
                    }
                } 
                else if (!jQuery(this.formSelector + ' .af2_form_foward_button.desktop').hasClass('af2_disabled'))
                {
                    if (datas[this.actualData].typ === 'af2_adressfeld') {
                        this.af2Move(this.lastAdressObj, 'next');
                    }
                }
            }
        });

        this.$(document).on('mouseenter', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer', (ev) => {
            jQuery(ev.currentTarget).addClass('hover');
        });
        this.$(document).on('mouseleave', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer', (ev) => {
            jQuery(ev.currentTarget).removeClass('hover');
        });


        this.$(document).on('click', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer', (ev) => {
            if (datas[this.actualData].typ === 'af2_multiselect')
            {
                jQuery(ev.currentTarget).toggleClass('selected_item');
                jQuery(ev.currentTarget).removeClass('hover');

                const len = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer.selected_item').length;
                if (len > 0)
                {
                    jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');

                    const cond = datas[this.actualData].type_specifics.condition;
                    if (cond !== undefined && cond !== '' && $.isNumeric(cond) && cond > 1)
                    {
                        if (len >= cond)
                        {
                            let arr = [];
                            jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer.selected_item').each((i, el) => {
                                arr.push(jQuery(el).attr('id'));
                            }).promise().done(() => {
                                this.af2Move(arr, 'next');
                            });
                        }
                    }
                } else
                {
                    jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
                }
            } else
            {
                const id = parseInt(jQuery(ev.currentTarget).attr('id'));
                this.af2Move(id, 'next');
            }
        });

        this.$(document).on('input', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textfeld_frage', (ev) => {  // MINLENGTH 1

            const text = jQuery(ev.currentTarget).val();


            let text_only_text = jQuery(ev.currentTarget).data("text_only_text");
            let text_only_numbers = jQuery(ev.currentTarget).data("text_only_numbers");
            let text_birthday = jQuery(ev.currentTarget).data("text_birthday");
            //let text_plz = jQuery(ev.currentTarget).data("text_plz");
            let last_input_length = jQuery(ev.currentTarget).data("lastInputLength") != undefined ? jQuery(ev.currentTarget).data("lastInputLength") : 0;
            let actual_input_length = text.length;

            if(text_only_text) {
				const newText = text.replace(/[0-9]/g,'');
                 jQuery(ev.currentTarget).val(newText);
            }
            if(text_only_numbers && !text_only_text) {
                const newText = text.replace(/[^0-9]/g,'');
                 jQuery(ev.currentTarget).val(newText);
            }
            if(text_birthday && !text_only_text && !text_only_numbers) {
                const lastLetter = text.substr(text.length - 1, 1);

                let key = ev.which || ev.keyCode || ev.charCode;

                if(actual_input_length > last_input_length) {
                    if(isNaN(lastLetter)) {
                        jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                    }
                    else {
                        if(text.length == 2) jQuery(ev.currentTarget).val(text + '.');
                        if(text.length == 5) jQuery(ev.currentTarget).val(text + '.');
                    }
                }
                else {
                    if(text.length == 2) jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                    if(text.length == 5) jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                }
                
                /**if(isNaN(lastLetter)) {
                    if(key != 8) jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                    else if(text >= '') jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                }
                else {
                    if(text.length == 2) jQuery(ev.currentTarget).val(text + '.');
                    if(text.length == 5) jQuery(ev.currentTarget).val(text + '.');
                }*/
            }
            
            

            let minlength = jQuery(ev.currentTarget).data("minlength");
            let maxlength = jQuery(ev.currentTarget).data("maxlength");

            /*if(text_plz && !text_birthday && !text_only_text && !text_only_numbers) {
                
            }*/

            if(minlength == null || minlength == undefined) minlength = 0;
            if(maxlength == null || maxlength == undefined) maxlength = 999999;
            
            if(text.length < minlength || text.length > maxlength) {
                if(text.length > maxlength) {
                    const lastLetter = text.substr(text.length - 1, 1);
                    //if(isNaN(lastLetter)) {
                        jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                    //}
                }
                else jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
            }
            if(text.length >= minlength && text.length <= maxlength) {
                jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
            }

            /*if (jQuery(ev.currentTarget).val().trim() !== '' &&) {
                jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
            } */
            if(jQuery(ev.currentTarget).val().trim() == '') {
                if (jQuery(ev.currentTarget).data("mandatory") === true) {
                    jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
                } else {
                    jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
                }

            }

            if(text_birthday && !text_only_text && !text_only_numbers) {
                if(text.length == 10) {
                    let dateText = '';

                    dateTextSplit = text.split('.');

                    //dateText = dateTextSplit[1] + '.' + dateTextSplit[0] + '.' + dateTextSplit[2];

                    const year = dateTextSplit[2];
                    const month = dateTextSplit[1];
                    const day = dateTextSplit[0];

                    if(!(new Date(year, month, day) !== "Invalid Date" && !isNaN(new Date(year, month, day)))) jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
                }
            }

            
            jQuery(ev.currentTarget).data("lastInputLength", jQuery(ev.currentTarget).val().length);
        });
        this.$(document).on('input', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textbereich_frage', (ev) => { // MINLENGTH 1

            const text = jQuery(ev.currentTarget).val();

            let text_only_text = jQuery(ev.currentTarget).data("text_only_text");
            let text_only_numbers = jQuery(ev.currentTarget).data("text_only_numbers");
            let text_birthday = jQuery(ev.currentTarget).data("text_birthday");
            let last_input_length = jQuery(ev.currentTarget).data("lastInputLength") != undefined ? jQuery(ev.currentTarget).data("lastInputLength") : 0;
            let actual_input_length = text.length;

            if(text_only_text) {
                const lastLetter = text.substr(text.length - 1, 1);
                if(!isNaN(lastLetter)) {
                    jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                }
            }
            if(text_only_numbers && !text_only_text) {
                const lastLetter = text.substr(text.length - 1, 1);
                if(isNaN(lastLetter)) {
                    jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                }
            }
            if(text_birthday && !text_only_text && !text_only_numbers) {
                const lastLetter = text.substr(text.length - 1, 1);

                let key = ev.which || ev.keyCode || ev.charCode;

                if(actual_input_length > last_input_length) {
                    if(isNaN(lastLetter)) {
                        jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                    }
                    else {
                        if(text.length == 2) jQuery(ev.currentTarget).val(text + '.');
                        if(text.length == 5) jQuery(ev.currentTarget).val(text + '.');
                    }
                }
                else {
                    if(text.length == 2) jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                    if(text.length == 5) jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                }
                
                /**if(isNaN(lastLetter)) {
                    if(key != 8) jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                    else if(text >= '') jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                }
                else {
                    if(text.length == 2) jQuery(ev.currentTarget).val(text + '.');
                    if(text.length == 5) jQuery(ev.currentTarget).val(text + '.');
                }*/
            }

            let minlength = jQuery(ev.currentTarget).data("minlength");
            let maxlength = jQuery(ev.currentTarget).data("maxlength");

            if(minlength == null || minlength == undefined) minlength = 0;
            if(maxlength == null || maxlength == undefined) maxlength = 999999;
            
            if(text.length < minlength || text.length > maxlength) {
                if(text.length > maxlength) {
                    const lastLetter = text.substr(text.length - 1, 1);
                    //if(isNaN(lastLetter)) {
                        jQuery(ev.currentTarget).val(text.substr(0, text.length - 1));
                    //}
                }
                else jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
            }
            if(text.length >= minlength && text.length <= maxlength) {
                jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
            }

            /*if (jQuery(ev.currentTarget).val().trim() !== '' &&) {
                jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
            } */
            if(jQuery(ev.currentTarget).val().trim() == '') {
                if (jQuery(ev.currentTarget).data("mandatory") === true) {
                    jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
                } else {
                    jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
                }
            }

            if(text_birthday && !text_only_text && !text_only_numbers) {
                if(text.length == 10) {
                    let dateText = '';

                    dateTextSplit = text.split('.');

                    //dateText = dateTextSplit[1] + '.' + dateTextSplit[0] + '.' + dateTextSplit[2];

                    const year = dateTextSplit[2];
                    const month = dateTextSplit[1];
                    const day = dateTextSplit[0];

                    if(!(new Date(year, month, day) !== "Invalid Date" && !isNaN(new Date(year, month, day)))) jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
                }
            }

            
            jQuery(ev.currentTarget).data("lastInputLength", jQuery(ev.currentTarget).val().length);
        });

        this.$(document).on('input', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_datum_frage', (ev) => {

            if (jQuery(ev.currentTarget).val().trim() !== '') {
                jQuery(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
            } else {
                jQuery(this.formSelector + ' .af2_form_foward_button').addClass('af2_disabled');
            }

        });

        this.$(document).on('click', this.formSelector + ' .af2_form_back_button', (ev) => {
            if (!jQuery(ev.currentTarget).hasClass('af2_disabled'))
            {
                this.set = false;

                const bSection = this.beforeSection[this.beforeSection.length-1];
                const bContent = this.beforeContent[this.beforeContent.length-1];
                const bId = datas[this.id].sections[bSection].contents[bContent].data;

                //const bRemove = function() {
                    jQuery(this.formSelector + ' .af2_form_button').each((i, el) => {
                        jQuery(el).addClass('af2_disabled');
                    });
                //}

                let removeNoHtml = true;

                if(datas[bId].af2_type == 'frage') {
                    /*if(datas[bId].typ === 'af2_select') bRemove();
                    if(datas[bId].typ === 'af2_multiselect') bRemove();
                    if(datas[bId].typ === 'af2_textfeld');//bRemove();
                    if(datas[bId].typ === 'af2_textbereich');//bRemove();
                    if(datas[bId].typ === 'af2_content');//bRemove();
                    if(datas[bId].typ === 'af2_datum'); //bRemove();
                    if(datas[bId].typ === 'af2_dateiupload'); //bRemove();
                    if(datas[bId].typ === 'af2_dropdown'); //bRemove();
                    if(datas[bId].typ === 'af2_slider'); //bRemove();
                    if(datas[bId].typ === 'af2_adressfeld'); //bRemove();*/
                    if(datas[bId].typ === 'af2_adressfeld') {
                        this.setLastAddrObject(this.answers[this.answers.length - 1]);
                    }
                    if(datas[bId].typ === 'af2_content') {
                        removeNoHtml = false;
                    }
                }

                
                this.af2Move(-1, 'before', removeNoHtml);
            }
        });

        this.$(document).on('click', this.formSelector + ' .af2_form_foward_button', (ev) => {
            if (!jQuery(ev.currentTarget).hasClass('af2_disabled'))
            {
                if (datas[this.actualData].typ === 'af2_multiselect')
                {
                    let arr = [];
                    jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer.selected_item').each((i, el) => {
                        arr.push(jQuery(el).attr('id'));
                    }).promise().done(() => {
                        this.af2Move(arr, 'next');
                    });
                } else if (datas[this.actualData].typ === 'af2_textfeld') { 
                    if (af2_isMobileView($, this.formSelector)) {
                        this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textfeld_frage.af2_mobile').val(), 'next');
                    } else {
                        this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textfeld_frage').val(), 'next');
                    }

                } else if (datas[this.actualData].typ === 'af2_textbereich') {
                    if (af2_isMobileView($, this.formSelector)) {
                        this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textbereich_frage.af2_mobile').val(), 'next');
                    } else {
                        this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textbereich_frage').val(), 'next');
                    }
                } 
            }
        });
        this.$(document).on('click', this.formSelector + ' .af2_submit_button.no_send', (ev) => {
            if (!jQuery(ev.currentTarget).hasClass('af2_disabled'))
            {
                if (datas[this.actualData].typ === 'af2_multiselect')
                {
                    let arr = [];
                    jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer.selected_item').each((i, el) => {
                        arr.push(jQuery(el).attr('id'));
                    }).promise().done(() => {
                        this.af2Move(arr, 'next');
                    });
                } else if (datas[this.actualData].typ === 'af2_textfeld') { 
                    if (af2_isMobileView($, this.formSelector)) {
                        this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textfeld_frage.af2_mobile').val(), 'next');
                    } else {
                        this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textfeld_frage').val(), 'next');
                    }

                } else if (datas[this.actualData].typ === 'af2_textbereich') {
                    if (af2_isMobileView($, this.formSelector)) {
                        this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textbereich_frage.af2_mobile').val(), 'next');
                    } else {
                        this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textbereich_frage').val(), 'next');
                    }

                } else if (datas[this.actualData].typ === 'af2_datum') {
                    if (af2_isMobileView($, this.formSelector)) {
                        this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_datum_frage.af2_mobile').val(), 'next');
                    } else {
                        this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_datum_frage').val(), 'next');
                    }

                } else if (datas[this.actualData].typ === 'af2_slider') {
                    this.af2Move(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_slider_frage').val(), 'next');
                } else if (datas[this.actualData].typ === 'af2_content') {
                    jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item video').each((i, el) => {
                        jQuery(el).stop();
                    });
                    this.af2Move("", 'next');
                } else if (datas[this.actualData].typ === 'af2_dateiupload') {
                    let fileupload_class = '.af2_dateiupload';
                    if (af2_isMobileView($, this.formSelector)) {
                        fileupload_class = '.af2_dateiupload.af2_mobile';
                    } else {
                        fileupload_class = '.af2_dateiupload.desktop';
                    }

                    let arr = [];
                    if(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item '+fileupload_class+' div[data-attachment_id]').length > 0){
                        jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item '+fileupload_class+' div[data-attachment_id]').each((i, el) => {
                            arr.push(jQuery(el).data('attachment_id'));
                        }).promise().done(() => {
                            this.attachment_ids = this.attachment_ids.concat(arr);
                            this.af2Move(arr, 'next');
                        });
                    }else{
                        this.af2Move(0, 'next');
                    }

                } else if (datas[this.actualData].typ === 'af2_dropdown') {
                    //TODO
                    const label = jQuery(this.formSelector + ' #'+this.actualCarouselItem+'.af2_carousel_item .af2_dropdown_fragen').next().find('.select2-selection__rendered').html();
                    const value = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_dropdown_fragen').val();
                    this.af2Move(value, 'next', label);
                }
            }
        });


        this.$(document).on('click', this.formSelector + ' .af2_submit_button', (ev) => {
            if (this.af2_is_send_allowed)
            {

                if (jQuery(ev.currentTarget).hasClass('no_send'))
                {

                }
                else
                {
                    this.af2_is_send_allowed = false;
                    jQuery(this.formSelector + ' .af2_response_success').each((i, el) => {
                        jQuery(el).remove();
                    });
                    jQuery(this.formSelector + ' .af2_response_error').each((i, el) => {
                        jQuery(el).remove();
                    });
                    jQuery(this.formSelector + ' .af2_loading_error').each((i, el) => {
                        jQuery(el).remove();
                    });

                    let arr = [];
                    let arr2 = [];
                    let doneRadios = {};

                    if(jQuery(ev.currentTarget).hasClass('real_send')) {
                        this.send_mail(jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_text_type_validate').val(),
                        jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_text_type_validate').data('number'));
                    }
                    else {
                        jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_question input').each((i, el) => {
                            if(jQuery(el).hasClass('filter_country')) return;
                            
                            if (jQuery(el).attr('type') === 'checkbox')
                            {
                                arr.push(jQuery(el).prop('checked'));
                                arr2.push({ value: jQuery(el).prop('checked'), id: jQuery(el).attr('data-uid') });
                            } else if(jQuery(el).attr('type') === 'radio') 
                            {
                                /**console.log(jQuery(el).attr('name'));
                                console.log(jQuery(el).val());
                                console.log(jQuery(el).is(':checked'));
                                **/
                                const val = jQuery("input[name="+jQuery(el).attr('name')+"]:checked").val();
                                const name = jQuery(el).attr('name');
    
                                let value = val === undefined ? 'keine Angabe' : val;
    
                                if(doneRadios[name] === undefined) {
                                    doneRadios[name] = true;
                                    arr.push(value);
                                    arr2.push({ value: value, id: jQuery(el).attr('data-uid') });
                                }
                            } else
                            {
                                arr.push(jQuery(el).val());
                                arr2.push({ value: jQuery(el).val(), id: jQuery(el).attr('id') });
                            }
                        }).promise().done(() => {
                            if (this.set === true)
                            {
                                this.answers.pop();
                            }
                            this.answers.push(arr);
                            this.contactFormAnswers = arr2;
    
                            this.set = true;

                            this.send_mail();
                        });
                    }
                }
            }


        });



        this.send_mail = (value, number) => {
            let af2_queryString = window.location.search.substr(1);
            let af2_url = window.location.href;

            let verVal = '';
            let verNum = '';

            if(value) verVal = value;
            if(number) verNum = number;

            let submits = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_submit_button');
            jQuery(submits).each((i, el) => {
                if(jQuery(el).hasClass('newVerification')){}
                else jQuery(el).addClass('af2_submit_button--loading');
            });
            jQuery.ajax({
                url: af2_frontend_ajax.ajax_url,
                type: "POST",
                data: {
                    _ajax_nonce: af2_frontend_ajax.nonce,
                    action: 'fnsf_af2_send_mail',
                    sec: this.actualSection,
                    cont: this.actualContent,
                    dataid: this.id,
                    answers: this.answers,
                    attachment_ids: this.attachment_ids,
                    af2_queryString: af2_queryString,
                    af2_url: af2_url,
                    verificationSMS: [verVal, verNum],
                    contactFormAnswers: this.contactFormAnswers,
                },
                success: (answer) => {
                    //console.log(answer);
                    // Version - just to test extern
                    if (answer === 'ERROR' || answer === undefined)
                    {
                        this.af2_is_send_allowed = true;
                        af2ThrowError(this.$, jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_submit_button'), af2_frontend_ajax.strings.erroroccured);
                        this.setHeight();

                        
                    jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_submit_button.af2_submit_button--loading').removeClass('af2_submit_button--loading');
                    }
                    else if(answer === 'NOT VALIDATED') {
                        this.af2_is_send_allowed = true;
                        const sel = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_question .af2_question_wrapper')[0];
                        jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_verify').append('<div style="text-align: center;" class="af2_response_error">Der Code ist entweder falsch, oder abgelaufen.</div>');

                        this.setHeight();
                        
                    jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_submit_button.af2_submit_button--loading').removeClass('af2_submit_button--loading');
                    } 
                    else
                    {
                        const code = jQuery($.parseHTML(answer));
                        let error = code.filter('.af2_response_error')[0];

                        if (error !== undefined)
                        {
                            this.af2_is_send_allowed = true;
                            error = jQuery(error);
                            const sel = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_question .af2_question_wrapper')[error.data('id')];

                            af2ThrowLoadingError(this.$, sel, error[0]);
                            this.setHeight();

                            if (af2_isMobile())
                            {
                                jQuery('html,body').animate({scrollTop: jQuery(sel).offset().top-150});
                            }

                            
                    jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_submit_button.af2_submit_button--loading').removeClass('af2_submit_button--loading');
                        } else
                        {
                            const success = jQuery(code.filter('.af2_response_success')[0]);
                            /**af2ThrowLoadingSuccess(this.$, jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_submit_wrapper'), success[0]);
                            const height = jQuery(this.formSelector + ' .af2_form_carousel #' + this.actualCarouselItem + ' .af2_carousel_content').height();
                            jQuery(this.formSelector + ' .af2_form_carousel').css('height');
                            jQuery(this.formSelector + ' .af2_form_carousel').css('height', height); **/


                            //TODO
                            if(this.showSuccessScreen != false && this.showSuccessScreen != null && this.showSuccessScreen != undefined) {
                                jQuery(this.formSelector + ' .af2_form .af2_form_heading_wrapper').remove();
                                jQuery(this.formSelector + ' .af2_form .af2_form_carousel').remove();
                                jQuery(this.formSelector + ' .af2_form .af2_form_bottombar').remove();
                                jQuery(this.formSelector + ' .af2_form .af2_success_message_screen').addClass('show');
                            }
                            

                            const theFormElement = this;

                            setTimeout(function() {
                                
                            jQuery(theFormElement.formSelector + ' #' + theFormElement.actualCarouselItem + '.af2_carousel_item .af2_submit_button.af2_submit_button--loading').removeClass('af2_submit_button--loading');
                            jQuery(theFormElement.formSelector + ' #' + theFormElement.actualCarouselItem + '.af2_carousel_item .af2_submit_button span').html(af2_frontend_ajax.strings.form_sent);
                            if (success.data('redirect') !== "" && success.data('redirect') !== false)
                                {
                                    if(typeof af2_doOwnExternalRedirectFunction === 'function')
                                    {
                                        af2_doOwnExternalRedirectFunction(theFormElement.answerObject, success.data('bl'));
                                    }
                                    else {
                                        let blank = success.data('bl');
                                        if(blank == true)
                                            window.open(success.data('redirect').substr(9), '_blank');
                                        else
                                            window.location.href = success.data('redirect').substr(9);
                                    }
                                }
                            }, 2000);
                        }
                    }
                    
                },
                error: () => {
                    this.af2_is_send_allowed = true;
                    af2ThrowError(this.$, jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_submit_button'), af2_frontend_ajax.strings.erroroccured);
                    this.setHeight();
                    jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_submit_button.af2_submit_button--loading').removeClass('af2_submit_button--loading');
                }
            });
        }

        this.$(document).on('input', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_slider_frage', (ev) => {
            const sliderSelector = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_slider_frage');
            const sliderBulletSelector = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_slider_frage_bullet');
            af2AdjustSliderBullet(sliderSelector, sliderBulletSelector, datas[this.actualData], this.$);
            
            if(datas[this.actualData].type_specifics.manual == true){
                jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_slider_frage_val').val(sliderSelector.val());
                jQuery(this.formSelector + ' .af2_form_foward_button').removeClass("af2_disabled");
            }
        });
        
        this.$(document).on('input', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_slider_frage_val', (ev) => {
            
            const sliderSelector = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_slider_frage');
            const sliderBulletSelector = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_slider_frage_bullet');
            const sliderMin = parseInt(datas[this.actualData].type_specifics.min);
            const sliderMax = parseInt(datas[this.actualData].type_specifics.max);
            const sliderVal = parseInt(jQuery(ev.currentTarget).val());
            if(sliderVal >= sliderMin && sliderVal <= sliderMax){
                jQuery(this.formSelector + ' .af2_form_foward_button').removeClass("af2_disabled");
                sliderSelector.val(sliderVal);
                af2AdjustSliderBullet(sliderSelector, sliderBulletSelector, datas[this.actualData], this.$);
            }else{
                if(!isNaN(sliderVal)){
                    jQuery(this.formSelector + ' .af2_form_foward_button').addClass("af2_disabled");
                }
                    
            }
            
        });
    };

    /**
     * Removing all Triggers
     */
    this.removeTriggers = () => {
        jQuery(document).off('keypress');

        this.$(document).off('mouseenter', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer');
        this.$(document).off('mouseleave', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer');

        this.$(document).off('click', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer');
        this.$(document).off('input', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textfeld_frage');
        this.$(document).off('input', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textbereich_frage');
        this.$(document).off('input', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_datum_frage');
        this.$(document).off('input', this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_slider_frage');


        this.$(document).off('click', this.formSelector + ' .af2_form_back_button');
        this.$(document).off('click', this.formSelector + ' .af2_form_foward_button');
        this.$(document).off('click', this.formSelector + ' .af2_submit_button.no_send');

        this.$(document).off('click', this.formSelector + ' .af2_submit_button');
    };

    this.af2_addAhrefs = () => {
        this.$(this.formSelector+' a').each((i, el) => {
            if(jQuery(el).hasClass('af2_ahref')) {}
            else if(jQuery(el).hasClass('ui-state-default')) {}
            else jQuery(el).addClass('af2_ahref');
        });
    };

    /**
     * Moves the Carousel
     *
     * @param connectionFrom
     * @param type
     */
    this.af2Move = (connectionFrom, type, dropdownInput) => {
        /** Remove all Hooks**/
        this.removeTriggers();
        
        this.scroll_to_anchor(this.formSelector, this.$);


        

        if (type === 'next')
        {
            jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_answer').each((i, el) => {
                jQuery(el).removeClass('selected_item');
                jQuery(el).removeClass('hover');
            });

            this.answers.push(connectionFrom);

            // CATH BY TYPE

            let answerObjectAnswer = connectionFrom;

            let doAnswerObject = true;

            switch(datas[this.actualData].typ) {
                case 'af2_select': {
                    answerObjectAnswer = datas[this.actualData].type_specifics.answers[connectionFrom].text;
                    break;
                }
                case 'af2_multiselect': {
                    answerObjectAnswer = '';
                    connectionFrom.forEach((el, i) => {
                        answerObjectAnswer += datas[this.actualData].type_specifics.answers[el].text;
                        if(i < connectionFrom.length - 1) answerObjectAnswer += ', ';
                    });
                    break;
                }
                case 'af2_textfeld': {
                    break;
                }
                case 'af2_textbereich': {
                    break;
                }
                case 'af2_datum': {
                    break;
                }
                case 'af2_dropdown': {
                    answerObjectAnswer = dropdownInput;
                    break;
                }
                case 'af2_slider': {
                    break;
                }
                case 'af2_adressfeld': {
                    break;
                }
                case 'af2_content': {
                    doAnswerObject = false;
                    break;
                }
                case 'af2_dateiupload': {
                    answerObjectAnswer = '';
                    let dateienNamen = 'Dateien';
                    let wurdenNamen = 'wurden';
                    let fromlength = connectionFrom.length;
                    if(connectionFrom.length == 1 ) dateienNamen = 'Datei';
                    if(connectionFrom.length == 1 ) wurdenNamen = 'wurde';
                    if(connectionFrom.length == undefined) fromlength = connectionFrom
                    answerObjectAnswer = 'Es '+wurdenNamen+' '+fromlength+' '+dateienNamen+' hochgeladen.';
                    break;
                }
            }

            if(doAnswerObject)
                this.answerObject.push({ id: this.actualData, title: datas[this.actualData].frontend_name, answer: answerObjectAnswer });


            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'event': 'af2NextSlide',
                'id': this.actualData,
                'title': datas[this.actualData].frontend_name,
                'answer': answerObjectAnswer
            });

            this.needsDraw = true;

            let param = connectionFrom;

            if(datas[this.actualData].typ === 'af2_dropdown') {
                let options = datas[this.actualData].type_specifics.dropdown_options;
                $.each(options,function(i,t){
                    if(t.label == dropdownInput){
                        param = i;
                    }
                });
                this.answers.pop();
                this.answers.push(param);
            }

            if(typeof af2_doOwnExternalRedirectFunctionBetween === 'function')
            {
                af2_doOwnExternalRedirectFunctionBetween().done((newval) => {
                    if(newval) {
                        this.af2GoNext(param).done((cont) => {
                            this.beforeSection.push(cont[0]);
                            this.beforeContent.push(cont[1]);
            
                            if (this.beforeSection.length > 0)
                            {
                                jQuery(this.formSelector + ' .af2_form_back_button').removeClass('af2_disabled');
                            } else
                            {
                                jQuery(this.formSelector + ' .af2_form_back_button').addClass('af2_disabled');
                            }
                        });
                    } else {
                        //
                    }
                });
            }
            else {
                this.af2GoNext(param).done((cont) => {
                    this.beforeSection.push(cont[0]);
                    this.beforeContent.push(cont[1]);
    
                    if (this.beforeSection.length > 0)
                    {
                        jQuery(this.formSelector + ' .af2_form_back_button').removeClass('af2_disabled');
                    } else
                    {
                        jQuery(this.formSelector + ' .af2_form_back_button').addClass('af2_disabled');
                    }
                });
            }
        } else if (type === 'before')
        {
            window.dataLayer = window.dataLayer || [];
            window.dataLayer.push({
                'event': 'af2PrevSlide',
            });

            // IF
            if(dropdownInput == true) {
                this.answers.pop();
                this.answerObject.pop();
            }

            this.needsDraw = false;
            this.af2GoBefore().done(() => {
                this.beforeSection.pop();
                this.beforeContent.pop();

                if (this.beforeSection.length > 0)
                {
                    jQuery(this.formSelector + ' .af2_form_back_button').removeClass('af2_disabled');
                } else
                {
                    jQuery(this.formSelector + ' .af2_form_back_button').addClass('af2_disabled');
                }
            });
        }
    };

    this.scroll_to_anchor = (anchor_id, $) => {
        if(this.activateScrollToAnchor) {
            if (af2_isMobile())
            {
                var tag = jQuery(anchor_id + " .af2_form_heading.af2_mobile");
                jQuery('html,body').animate({scrollTop: tag.offset().top-100});
            }
        }
    }




    /**
     * Going to the one before
     */
    this.af2GoBefore = () => {
        
        // if its a contact form then store value in local store
        if(datas[this.actualData].af2_type == 'kontaktformular'){
            var data = [];
            jQuery(".af2_carousel_item#"+this.actualCarouselItem+" .af2_question").each(function(i,t){
                if(jQuery(t).find("input[type='text']").length > 0){
                    var id = jQuery(t).attr("id");
                    data[id] = jQuery(t).find("input[type='text']").val();
                }
            })
            this.stor.af2_form_values = JSON.stringify(data);
        }
            
        const prom = this.$.Deferred();

        this.actualSection = this.beforeSection[this.beforeSection.length - 1];
        this.actualContent = this.beforeContent[this.beforeContent.length - 1];

        this.actualCarouselItem--;

        /** Loading Content **/
        this.loadContent(true).done(() => {
            /** Move to the next **/

            jQuery(document).on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd',this.formSelector + ' #' + (this.actualCarouselItem) + '.af2_carousel_item', () => {
                jQuery(document).off('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd',this.formSelector + ' #' + (this.actualCarouselItem) + '.af2_carousel_item');
                jQuery(this.formSelector + ' #' + (this.actualCarouselItem + 1) + '.af2_carousel_item').remove();
                if (datas[this.actualData].typ === 'af2_textfeld' || datas[this.actualData].typ === 'af2_textbereich') {
                    if (datas[this.actualData].type_specifics.mandatory !== true) {
                        jQuery(this.formSelector + ' .af2_form_foward_button').removeClass("af2_disabled");
                    }
                }else if (datas[this.actualData].typ === 'af2_dateiupload') {
                    
                    if (datas[this.actualData].type_specifics.mandatory === true) {
                        let uploaded_files = 0;
                        if (af2_isMobileView($, this.formSelector)) {
                            uploaded_files = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_dateiupload.af2_mobile div[data-attachment_id]').length;
                        } else {
                             uploaded_files = jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_dateiupload.desktop div[data-attachment_id]').length;
                        }
                        if(uploaded_files > 0){
                            jQuery(this.formSelector + ' .af2_form_foward_button').removeClass("af2_disabled");
                        }
                    }else{
                        jQuery(this.formSelector + ' .af2_form_foward_button').removeClass("af2_disabled");
                    }
                }
                prom.resolve();
            });
            jQuery(this.formSelector + ' #' + (this.actualCarouselItem)).css('transform');
            // $(this.formSelector + ' #' + (this.actualCarouselItem) + '.af2_carousel_item').removeClass('left_marg');

            direction = '-';
            if(this.rtl_layout == true) {
                direction = '';
            }
            let offset = 'translateX('+direction+''+((this.actualCarouselItem)*100)+'%)';
            jQuery(this.formSelector + ' .af2_carousel_item').css('transform', offset);

            const newPercent = (this.actualSection / (this.size - 1)) * 100;
            jQuery(this.formSelector + ' .af2_form_progress').css('width');
            jQuery(this.formSelector + ' .af2_form_progress').css('width', newPercent + '%');

            //this.af2SetPercentage(parseInt(jQuery(this.formSelector + ' .af2_form_percentage').html()), newPercent, 500, 'down');
            
        });

        return prom.promise();
    };

    /**
     * Going to the next one
     */
    this.af2GoNext = (connectionFrom) => {
        const prom = this.$.Deferred();

        /** Set new Content **/
        /**if(datas[this.actualData].typ === 'af2_dropdown'){
            let options = datas[this.actualData].type_specifics.dropdown_options;
            $.each(options,function(i,t){
                if(t.value == connectionFrom || t.label == connectionFrom){
                    connectionFrom = i;
                }
            });
        }**/
        
        const buffer = af2FindNew(this.$, datas[this.id].sections[this.actualSection].contents[this.actualContent].connections, connectionFrom);
        
        const sec = this.actualSection;
        const cont = this.actualContent;

        this.actualSection = buffer[0];
        this.actualContent = buffer[1];

        this.actualCarouselItem++;

        /** Loading Content **/
        this.loadContent().done(() => { // MINLENGTH 1
            jQuery(document).on('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd',
                    this.formSelector + ' #' + (this.actualCarouselItem - 1) + '.af2_carousel_item', (ev) => {

                if(jQuery(ev.target).hasClass('af2_carousel_item'))
                {
                    jQuery(document).off('transitionend webkitTransitionEnd oTransitionEnd otransitionend MSTransitionEnd',
                        this.formSelector + ' #' + (this.actualCarouselItem - 1) + '.af2_carousel_item');

                    if (datas[this.actualData].typ === 'af2_textfeld')
                    {
                        let is_mandatory = datas[this.actualData].type_specifics.mandatory;
                        let min_length = datas[this.actualData].type_specifics.min_length;
                        if (is_mandatory !== true) {
                            jQuery(this.formSelector + ' .af2_form_foward_button').removeClass("af2_disabled");
                        }

                        jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textfeld_frage').each((i, el) => {
                            if (jQuery(el).css('display') === 'none')
                            {

                            } else
                            {
                                jQuery(el).focus();
                            }
                        });
                    }

                    if (datas[this.actualData].typ === 'af2_textbereich')
                    {
                        let is_mandatory = datas[this.actualData].type_specifics.mandatory;
                        if (is_mandatory !== true) {
                            jQuery(this.formSelector + ' .af2_form_foward_button').removeClass("af2_disabled");
                        }

                        jQuery(this.formSelector + ' #' + this.actualCarouselItem + '.af2_carousel_item .af2_textbereich_frage').each((i, el) => {
                            if (jQuery(el).css('display') === 'none')
                            {

                            } else
                            {
                                jQuery(el).focus();
                            }
                        });
                    }
                    
                }
            });

            /** Move to the next **/
            // $(this.formSelector + ' #' + (this.actualCarouselItem - 1)).css('transform');
            // $(this.formSelector + ' #' + (this.actualCarouselItem - 1) + '.af2_carousel_item').addClass('left_marg');

            direction = '-';
            if(this.rtl_layout == true) {
                direction = '';
            }
            let offset = 'translateX('+direction+''+((this.actualCarouselItem)*100)+'%)';
            let offset2 = 'translateX('+direction+''+((this.actualCarouselItem-1)*100)+'%)';

            let saveTrans = jQuery(this.formSelector + ' #' + (this.actualCarouselItem) + '.af2_carousel_item').css('transition');
            jQuery(this.formSelector + ' #' + (this.actualCarouselItem) + '.af2_carousel_item').css('transition', '');
            jQuery(this.formSelector + ' #' + (this.actualCarouselItem) + '.af2_carousel_item').css('transform', offset2);
            setTimeout(()=> {
                jQuery(this.formSelector + ' .af2_carousel_item').css('transform', offset);
            }, 300);
            jQuery(this.formSelector + ' #' + (this.actualCarouselItem) + '.af2_carousel_item').css('transition', saveTrans);

            const newPercent = (this.actualSection / (this.size - 1)) * 100;
            jQuery(this.formSelector + ' .af2_form_progress').css('width');
            jQuery(this.formSelector + ' .af2_form_progress').css('width', newPercent + '%');

            prom.resolve([sec, cont]);

//            if(datas[this.actualData].typ === 'af2_textbereich')

            //this.af2SetPercentage(parseInt(jQuery(this.formSelector + ' .af2_form_percentage').html()), newPercent, 500, 'up');
            
            // if its a contact form then store value in local store
            if(datas[this.actualData].af2_type == 'kontaktformular'){
                if (this.stor.af2_form_values !== null && this.stor.af2_form_values !== undefined) {
                    
                    let optionValues = JSON.parse(this.stor.af2_form_values);        
                    jQuery(".af2_carousel_item#"+this.actualCarouselItem+" .af2_question").each(function(i,t){
                        var id = jQuery(t).attr("id");
                        if(typeof optionValues[id] !== undefined){
                            jQuery(t).find("input[type='text']").val(optionValues[i]);
                        }
                    })
                }
            }

        });

       

        return prom.promise();
    };

    this.af2SetPercentage = (oldPercentage, newPercentage, interv, way) => {

        let difference = newPercentage - oldPercentage;
        let actPercentage = oldPercentage + 1;

        if (way === 'down')
        {
            difference = oldPercentage - newPercentage;
            actPercentage = oldPercentage - 1;
        }


        if (difference === 0)
        {
            return null;
        }

        let interval = interv / difference;

        setTimeout(() => {
            jQuery(this.formSelector + ' .af2_form_percentage').html(actPercentage + '%');
            this.af2SetPercentage(actPercentage, newPercentage, interv - interval, way);
        }, interval);

    };

    

    this.af2_initAdress = () => {
        let map = GMap($, this.neededContent, this.actualData, this.formSelector, this.setHeight, this);
        /** 
        //jQuery(this.formSelector + ' .af2_form_carousel #' + this.actualCarouselItem + ' .af2_carousel_content').css('height', '500px');

        const fact = this.neededContent == undefined ? this.actualData : this.neededContent;

        const zoomlevel = datas[fact].type_specifics.zoomlevel == undefined ? 8 : parseInt(datas[fact].type_specifics.zoomlevel);
        const center = datas[fact].type_specifics.center == undefined ? { lat: -34.397, lng: 150.644 } : datas[fact].type_specifics.center;

        this.map = new extraParms.maps.Map(this.$(this.formSelector + ' #af2_adress_field')[0], {
            center: center,
            zoom: zoomlevel,
            mapTypeId: "roadmap",
            mapTypeControlOptions: { mapTypeIds: [] },
            disableDefaultUI: true,
        });
        //console.log(jQuery(this.formSelector + ' input#af2_adress_street')[0]);

        this.autocomplete = new extraParms.maps.places.Autocomplete(
            jQuery(this.formSelector + ' input#af2_adress_street')[0], {types: ["geocode"]}
        );
        this.autocomplete2 = new extraParms.maps.places.Autocomplete(
            jQuery(this.formSelector + ' input#af2_adress_street_')[0], {types: ["geocode"]}
        );

        setTimeout(() => {
            this.autocomplete.setFields(["address_component", "geometry"]);
            this.autocomplete.addListener("place_changed", this.fillInAdress);
            this.autocomplete2.setFields(["address_component", "geometry"]);
            this.autocomplete2.addListener("place_changed", this.fillInAdress2);
        }, 1000);

        this.setHeight();

        this.triggersDesktop();
        this.triggersMobile();**/
    };/** *
    this.triggersDesktop = () => {
        jQuery(this.formSelector + ' #af2_adress_streetnum').on('input', (ev) => {
            const streetnum = jQuery(this.formSelector + ' #af2_adress_streetnum').val();
            const street = jQuery(this.formSelector + ' #af2_adress_street').val();
            const plz = jQuery(this.formSelector + ' #af2_adress_plz').val();
            const city = jQuery(this.formSelector + ' #af2_adress_city').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_streetnum').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_streetnum').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_street').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_street').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_plz').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_plz').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_city').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_city').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.desktop').removeClass('af2_disabled');
            this.lastAdressObj = street + ' ' + streetnum + ', ' + plz + ' ' + city;
        });
        jQuery(this.formSelector + ' #af2_adress_street').on('input', (ev) => {
            const streetnum = jQuery(this.formSelector + ' #af2_adress_streetnum').val();
            const street = jQuery(this.formSelector + ' #af2_adress_street').val();
            const plz = jQuery(this.formSelector + ' #af2_adress_plz').val();
            const city = jQuery(this.formSelector + ' #af2_adress_city').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_streetnum').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_streetnum').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_street').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_street').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_plz').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_plz').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_city').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_city').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.desktop').removeClass('af2_disabled');
            this.lastAdressObj = street + ' ' + streetnum + ', ' + plz + ' ' + city;
        });
        jQuery(this.formSelector + ' #af2_adress_plz').on('input', (ev) => {
            const streetnum = jQuery(this.formSelector + ' #af2_adress_streetnum').val();
            const street = jQuery(this.formSelector + ' #af2_adress_street').val();
            const plz = jQuery(this.formSelector + ' #af2_adress_plz').val();
            const city = jQuery(this.formSelector + ' #af2_adress_city').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_streetnum').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_streetnum').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_street').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_street').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_plz').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_plz').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_city').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_city').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.desktop').removeClass('af2_disabled');
            this.lastAdressObj = street + ' ' + streetnum + ', ' + plz + ' ' + city;
        });
        jQuery(this.formSelector + ' #af2_adress_city').on('input', (ev) => {
            const streetnum = jQuery(this.formSelector + ' #af2_adress_streetnum').val();
            const street = jQuery(this.formSelector + ' #af2_adress_street').val();
            const plz = jQuery(this.formSelector + ' #af2_adress_plz').val();
            const city = jQuery(this.formSelector + ' #af2_adress_city').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_streetnum').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_streetnum').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_street').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_street').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_plz').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_plz').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_city').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_city').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.desktop').removeClass('af2_disabled');
            this.lastAdressObj = street + ' ' + streetnum + ', ' + plz + ' ' + city;
        });
    }

    this.triggersMobile = () => {
        jQuery(this.formSelector + ' #af2_adress_streetnum_').on('input', (ev) => {
            const streetnum = jQuery(this.formSelector + ' #af2_adress_streetnum_').val();
            const street = jQuery(this.formSelector + ' #af2_adress_street_').val();
            const plz = jQuery(this.formSelector + ' #af2_adress_plz_').val();
            const city = jQuery(this.formSelector + ' #af2_adress_city_').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_streetnum_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_streetnum_').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_street_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_street_').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_plz_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_plz_').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_city_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_city_').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');
            this.lastAdressObj = street + ' ' + streetnum + ', ' + plz + ' ' + city;
        });
        jQuery(this.formSelector + ' #af2_adress_street_').on('input', (ev) => {
            const streetnum = jQuery(this.formSelector + ' #af2_adress_streetnum_').val();
            const street = jQuery(this.formSelector + ' #af2_adress_street_').val();
            const plz = jQuery(this.formSelector + ' #af2_adress_plz_').val();
            const city = jQuery(this.formSelector + ' #af2_adress_city_').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_streetnum_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_streetnum_').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_street_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_street_').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_plz_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_plz_').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_city_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_city_').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');
            this.lastAdressObj = street + ' ' + streetnum + ', ' + plz + ' ' + city;
        });
        jQuery(this.formSelector + ' #af2_adress_plz_').on('input', (ev) => {
            const streetnum = jQuery(this.formSelector + ' #af2_adress_streetnum_').val();
            const street = jQuery(this.formSelector + ' #af2_adress_street_').val();
            const plz = jQuery(this.formSelector + ' #af2_adress_plz_').val();
            const city = jQuery(this.formSelector + ' #af2_adress_city_').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_streetnum_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_streetnum_').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_street_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_street_').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_plz_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_plz_').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_city_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_city_').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');
            this.lastAdressObj = street + ' ' + streetnum + ', ' + plz + ' ' + city;
        });
        jQuery(this.formSelector + ' #af2_adress_city_').on('input', (ev) => {
            const streetnum = jQuery(this.formSelector + ' #af2_adress_streetnum_').val();
            const street = jQuery(this.formSelector + ' #af2_adress_street_').val();
            const plz = jQuery(this.formSelector + ' #af2_adress_plz_').val();
            const city = jQuery(this.formSelector + ' #af2_adress_city_').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_streetnum_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_streetnum_').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_street_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_street_').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_plz_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_plz_').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.formSelector + ' #af2_adress_city_').css('border-color', 'red');
                this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.formSelector + ' #af2_adress_city_').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');
            this.lastAdressObj = street + ' ' + streetnum + ', ' + plz + ' ' + city;
        });
    }
    
    /** 
    this.fillInAdress = () => {
        const place = this.autocomplete.getPlace();
        let street_number = '';
        let route = '';
        let postal_code = '';
        let locality = '';
        jQuery(this.formSelector + ' #af2_adress_streetnum').val('');
        jQuery(this.formSelector + ' #af2_adress_street').val('');
        jQuery(this.formSelector + ' #af2_adress_plz').val('');
        jQuery(this.formSelector + ' #af2_adress_city').val('');
        place.address_components.forEach((el) => {
            const adressType = el.types[0];
            const val = el.long_name;
            switch(adressType) {
                case 'street_number': {
                    jQuery(this.formSelector + ' #af2_adress_streetnum').val(val);
                    street_number = val;
                    break;
                }
                case 'route': {
                    jQuery(this.formSelector + ' #af2_adress_street').val(val);
                    route = val;
                    break;
                }
                case 'postal_code': {
                    jQuery(this.formSelector + ' #af2_adress_plz').val(val);
                    postal_code = val;
                    break;
                }
                case 'locality': {
                    jQuery(this.formSelector + ' #af2_adress_city').val(val);
                    locality = val;
                    break;
                }
                default: break;
            }
            //console.log(adressType + ' - ' + val);
        });
        let clean = true;
        if(street_number == '') {
            clean = false;
            this.$(this.formSelector + ' #af2_adress_streetnum').css('border-color', 'red');
            this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
        } else this.$(this.formSelector + ' #af2_adress_streetnum').css('border-color', 'rgba(51,51,51,0.12)');
        if(route == '') {
            clean = false;
            this.$(this.formSelector + ' #af2_adress_street').css('border-color', 'red');
             this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
        } else this.$(this.formSelector + ' #af2_adress_street').css('border-color', 'rgba(51,51,51,0.12)');
        if(postal_code == '') {
            clean = false;
            this.$(this.formSelector + ' #af2_adress_plz').css('border-color', 'red');
             this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
        } else this.$(this.formSelector + ' #af2_adress_plz').css('border-color', 'rgba(51,51,51,0.12)');
        if(locality == '') {
            clean = false;
            this.$(this.formSelector + ' #af2_adress_city').css('border-color', 'red');
             this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
        } else this.$(this.formSelector + ' #af2_adress_city').css('border-color', 'rgba(51,51,51,0.12)');
        if(clean) this.$(this.formSelector + ' .af2_form_foward_button.desktop').removeClass('af2_disabled');

        this.markers.forEach((marker) => {
            marker.setMap(null);
        });
        this.markers = [];

        const icon = {
            url: place.icon,
            size: new extraParms.maps.Size(71, 71),
            origin: new extraParms.maps.Point(0, 0),
            anchor: new extraParms.maps.Point(17, 34),
            scaledSize: new extraParms.maps.Size(25, 25),
        };


        let mark = new extraParms.maps.Marker({
            title: place.name, position: place.geometry.location,
        });
        mark.setMap(this.map);
        this.markers.push(mark);

        this.map.setCenter(place.geometry.location);
        this.map.setZoom(16);

        this.lastAdressObj = route + ' ' + street_number + ', ' + postal_code + ' ' + locality;
    }**

    this.fillInAdress2 = () => {
        const place = this.autocomplete2.getPlace();
        let street_number = '';
        let route = '';
        let postal_code = '';
        let locality = '';
        jQuery(this.formSelector + ' #af2_adress_streetnum_').val('');
        jQuery(this.formSelector + ' #af2_adress_street_').val('');
        jQuery(this.formSelector + ' #af2_adress_plz_').val('');
        jQuery(this.formSelector + ' #af2_adress_city_').val('');
        place.address_components.forEach((el) => {
            const adressType = el.types[0];
            const val = el.long_name;
            switch(adressType) {
                case 'street_number': {
                    jQuery(this.formSelector + ' #af2_adress_streetnum_').val(val);
                    street_number = val;
                    break;
                }
                case 'route': {
                    jQuery(this.formSelector + ' #af2_adress_street_').val(val);
                    route = val;
                    break;
                }
                case 'postal_code': {
                    jQuery(this.formSelector + ' #af2_adress_plz_').val(val);
                    postal_code = val;
                    break;
                }
                case 'locality': {
                    jQuery(this.formSelector + ' #af2_adress_city_').val(val);
                    locality = val;
                    break;
                }
                default: break;
            }
            //console.log(adressType + ' - ' + val);
        });
        let clean = true;
        if(street_number == '') {
            clean = false;
            this.$(this.formSelector + ' #af2_adress_streetnum_').css('border-color', 'red');
            this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
        } else this.$(this.formSelector + ' #af2_adress_streetnum_').css('border-color', 'rgba(51,51,51,0.12)');
        if(route == '') {
            clean = false;
            this.$(this.formSelector + ' #af2_adress_street_').css('border-color', 'red');
             this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
        } else this.$(this.formSelector + ' #af2_adress_street_').css('border-color', 'rgba(51,51,51,0.12)');
        if(postal_code == '') {
            clean = false;
            this.$(this.formSelector + ' #af2_adress_plz_').css('border-color', 'red');
             this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
        } else this.$(this.formSelector + ' #af2_adress_plz_').css('border-color', 'rgba(51,51,51,0.12)');
        if(locality == '') {
            clean = false;
            this.$(this.formSelector + ' #af2_adress_city_').css('border-color', 'red');
             this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
        } else this.$(this.formSelector + ' #af2_adress_city_').css('border-color', 'rgba(51,51,51,0.12)');
        if(clean) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');

        this.markers.forEach((marker) => {
            marker.setMap(null);
        });
        this.markers = [];

        const icon = {
            url: place.icon,
            size: new extraParms.maps.Size(71, 71),
            origin: new extraParms.maps.Point(0, 0),
            anchor: new extraParms.maps.Point(17, 34),
            scaledSize: new extraParms.maps.Size(25, 25),
        };


        let mark = new extraParms.maps.Marker({
            title: place.name, position: place.geometry.location,
        });
        mark.setMap(this.map);
        this.markers.push(mark);

        this.map.setCenter(place.geometry.location);
        this.map.setZoom(16);

        this.lastAdressObj = route + ' ' + street_number + ', ' + postal_code + ' ' + locality;
    }*/

    this.setHeight = () => {
        const height = jQuery(this.formSelector + ' .af2_form_carousel #' + this.actualCarouselItem + ' .af2_carousel_content').height();
        jQuery(this.formSelector + ' .af2_form_carousel').css('height', height);

        
        const width = jQuery(this.formSelector).width();
        if(width > 780) {
            jQuery(this.formSelector).removeClass('af2_nm_mobile_view');
            jQuery(this.formSelector).removeClass('af2_nm_ipad_view');
            jQuery(this.formSelector).addClass('af2_nm_desktop_view');
        }
        else if (width <= 360) {
            jQuery(this.formSelector).addClass('af2_nm_mobile_view');
            jQuery(this.formSelector).removeClass('af2_nm_ipad_view');
            jQuery(this.formSelector).removeClass('af2_nm_desktop_view');
        }
        else {
            jQuery(this.formSelector).removeClass('af2_nm_mobile_view');
            jQuery(this.formSelector).addClass('af2_nm_ipad_view');
            jQuery(this.formSelector).removeClass('af2_nm_desktop_view');
        }
    }

    /** Load the Form's Content **/
    if (datas[id] === undefined) // When Dataid is not already set
    {
        datas[id] = true;

        af2HandleRequest($, this.formSelector, '.af2_form_wrapper', [this.id], this);
    }
    else {
        af2LoadStyling(this.$, this.id, this.formSelector, this.setHeight);
        this.loadContent();
    }
}

const getAllImagesDonePromise = ($, formSelector) => {
    // A jQuery-style promise we'll resolve
    var d = $.Deferred();

    // Get all images to start with (even complete ones)
    var imgs = jQuery(formSelector + " img");

    // Add a one-time event handler for the load and error events on them
    imgs.one("load.allimages error.allimages", function () {
        // This one completed, remove it
        imgs = imgs.not(this);
        if (imgs.length == 0) {
            // It was the last, resolve
            d.resolve();
        }
    });

    // Find the completed ones
    var complete = imgs.filter(function () {
        return this.complete;
    });

    // Remove our handler from completed ones, remove them from our list
    complete.off(".allimages");
    imgs = imgs.not(complete);
    complete = undefined; // Don't need it anymore

    // If none left, resolve; otherwise wait for events
    if (imgs.length == 0) {
        d.resolve();
    }

    // Return the promise for our deferred
    return d.promise();
}

const af2AdjustSliderBullet = (sliderSelector, sliderBulletSelector, json, $) => {
    let val = sliderSelector.val();
    const min = sliderSelector.attr('min');
    const max = sliderSelector.attr('max');
    const width = sliderSelector.width();
    const thumbWidth = 25;
    const offset = 19;

    let cont = val;

    let bulletPercentage = ((val - min) / (max - min));
    let bulletPosition = bulletPercentage * (width - thumbWidth) - offset;

    putInThousands(json, cont, $).done((ret) => {
        cont = ret;

        let labelBefore = json.type_specifics.labelBefore;

        const label = json.type_specifics.label;
        if (label !== undefined && label !== null && label.trim() !== '')
        {
            if (labelBefore == false || labelBefore === undefined)
            {
                cont += ' ' + label;
            } else if (labelBefore == true)
            {
                cont = label +' ' + cont;
            }
        }

        //sliderBulletSelector.css('left', bulletPosition + 'px');
        sliderBulletSelector.each((i, el) => {
            jQuery(el).html(cont);
        });
    });
};

/**
 * Finding new contents
 *
 * @param $
 * @param iterator
 * @param from
 */
const af2FindNew = ($, iterator, from) => {   
    let newSection = undefined;
    let newContent = undefined;
    let found = false;
    $.each(iterator, (i, el) => {
        if (el.operator == null || el.operator == '')
        {
            if (newSection != null || found === false)
            {
                if (el.from === -1) {
                    if (found === false)
                    {
                        newSection = el.to_section;
                        newContent = el.to_content;
                    }
                } else if (el.from === from) {                    
                    newSection = el.to_section;
                    newContent = el.to_content;
                    found = true;
                }
            }
        } else
        {
            switch (el.operator)
            {
                case '<':
                {
                    if (parseInt(from) < parseInt(el.number))
                    {
                        newSection = el.to_section;
                        newContent = el.to_content;
                    }
                    break;
                }
                case '<=':
                {
                    if (parseInt(from) <= parseInt(el.number))
                    {
                        newSection = el.to_section;
                        newContent = el.to_content;
                    }
                    break;
                }
                case '>':
                {
                    if (parseInt(from) > parseInt(el.number))
                    {
                        newSection = el.to_section;
                        newContent = el.to_content;
                    }
                    break;
                }
                case '>=':
                {
                    if (parseInt(from) >= parseInt(el.number))
                    {
                        newSection = el.to_section;
                        newContent = el.to_content;
                    }
                    break;
                }
                case '=':
                {
                    if (parseInt(from) == parseInt(el.number))
                    {
                        newSection = el.to_section;
                        newContent = el.to_content;
                    }
                    break;
                }
                case '!=':
                {
                    if (parseInt(from) != parseInt(el.number))
                    {
                        newSection = el.to_section;
                        newContent = el.to_content;
                    }
                    break;
                }
            }
        }
    });

    return [newSection, newContent];
};

/**
 * Finding new contents
 *
 * @param $
 * @param iterator
 * @param from
 *
 const af2FindNew = ($, iterator, from) => {
 let newSection = undefined;
 let newContent = undefined;
 
 let found = false;
 $.each(iterator, (i, el) => {
 if(el.from === -1)
 {
 if(found === false)
 {
 newSection = el.to_section;
 newContent = el.to_content;
 }
 }
 else if(el.from === from)
 {
 newSection = el.to_section;
 newContent = el.to_content;
 found = true;
 }
 });
 
 return [newSection, newContent];
 };*/

/**
 * Draws out the content into the carousel
 *
 * @param $
 * @param dataid
 * @param formSelector
 * @param carouselNum
 */
const af2DrawCarouselContent = ($, form, dataid, formSelector, carouselNum, resize_listener_add) => {
    form.needsDraw = false
    if (jQuery(formSelector + ' .af2_form_carousel').width() !== 0)
    {
        // RESIZE DO
        jQuery(formSelector + ' .af2_form_carousel').css('max-width', jQuery(formSelector + ' .af2_form_carousel').width());
        jQuery(formSelector + ' .af2_form_carousel').css('min-width', jQuery(formSelector + ' .af2_form_carousel').width());
    }

    /** Check out which type it is **/
    const type = datas[dataid].af2_type;                                                                                // The type of the Content to draw
    let content = '';

    /** Build wrapper **/
    content += '<div id="' + carouselNum + '" class="af2_carousel_item">';

    let json = undefined;
    let inp = '';
    /** Validate the type **/
    if (type === 'frage')
    {
        inp = af2DrawFrage($, datas[dataid], formSelector, form);
        json = datas[dataid];
    } else if (type === 'kontaktformular')
    {
        inp = af2DrawKontaktformular($, datas[dataid], form);
        form.af2_is_send_allowed = true;
        setTimeout(function() { initiateIntlTelInput($, form.rtl_layout); }, 200);
    }

    let c = 'af2_carousel_content';
   
    content += '<div class="'+c+'">';
    content += inp;

    /** Close wrapper **/
    content += '</div>';
    content += '</div>';

    /** Print out **/
    jQuery(formSelector + ' .af2_loading_overlay').css('opacity', 0);
    jQuery(formSelector + ' .af2_loading_overlay').css('width', '0px');
    jQuery(formSelector + ' .af2_loading_overlay').css('height', '0px');
    jQuery(formSelector + ' .af2_form').css('display', 'block');


    jQuery(formSelector + ' .af2_form_carousel').append(content);

    // THROW EVENT

    jQuery(formSelector + ' .af2_form_carousel').trigger('af2_drawed_content');

    form.af2_addAhrefs();

    resize_listener_add();

    adjustTargetBlanks($, formSelector);

    
};


function af2addDays (datex, days) {
    var date = new Date(datex.valueOf());
    date.setDate(date.getDate() + days);
    return date;
}

function af2getDatesInBetween(startDate, stopDate) {
    var dateArray = new Array();
    var currentDate = startDate;
    while (currentDate < stopDate) {
        dateArray.push(new Date (currentDate));
        currentDate = af2addDays(currentDate, 1);
    }
    return dateArray;
}

const doBasicTimeSlotsAf2 = (form, val, duration, dauer, time1, time2) => {
    form[val][duration].forEach((ele, i) => {
        const t1 = ele.time.split(':')[0]+''+ele.time.split(':')[1];
        let newDateOb = af2_getTodayStandard();
        newDateOb.setHours(ele.time.split(':')[0]);
        newDateOb.setMinutes(ele.time.split(':')[1]);
        let day = newDateOb.getDay();
        newDateOb.setMinutes(newDateOb.getMinutes()+parseInt(dauer[duration].min));
        newDateOb.setHours(newDateOb.getHours()+parseInt(dauer[duration].std));
        let minutes = (newDateOb.getMinutes() < 10 ? '0' : '') + newDateOb.getMinutes()
        let hours = (newDateOb.getHours() < 10 ? '0' : '') + newDateOb.getHours();
        const t2 = hours+''+minutes;
        if(t1 >= time1 && t1 < time2 && t2 > time1 && t2 <= time2 && day == newDateOb.getDay() ) form[val][duration][i].allowed = true;
    });
}

const adjustTargetBlanks = ($, formSelector) => {
    jQuery(formSelector + ' .af2_question a').each((i, el) => {
        jQuery(el).attr('target', '_blank');
    });
};

/**
 * Draws the content of a question
 *
 * @param $
 * @param json
 * @returns {string}
 */
const af2DrawFrage = ($, json, formSelector, form) => {

    let content = '';
    let bonusClass = '';
    if(form.firstQuestion === true) bonusClass = 'af2_start';

    /** Validate Questiontype **/
    if (json.typ === 'af2_select')
    {
        content += '<div class="af2_question_heading_wrapper desktop"><div class="af2_question_heading desktop">' + json.frontend_name + '</div><div class="af2_question_description desktop">' + json.frontend_description + '</div></div>';
        content += '<div class="af2_question_heading_wrapper af2_mobile"><div class="af2_question_heading af2_mobile">' + json.frontend_name + '</div><div class="af2_question_description af2_mobile">' + json.frontend_description + '</div></div>';
        content += '<div class="af2_answer_container af2_mobile_'+json.type_specifics.mobile_layout+' af2_desktop_'+json.type_specifics.desktop_layout+'">';
        content += af2ProcessAnswersWithHide($, bonusClass, json.type_specifics.answers,json.type_specifics.desktop_layout,json.type_specifics.mobile_layout, json.type_specifics.hide_icons);
        content += '<div>';
    } else if (json.typ === 'af2_multiselect')
    {
        /**let cond = '(Mehrfachauswahl möglich)';
         if(json.type_specifics.condition !== '' && $.isNumeric(json.type_specifics.condition) && json.type_specifics.condition > 1)
         {
         cond = '(Bis zu '+json.type_specifics.condition+' Antworten möglich)';
         }**/
        content += '<div class="af2_question_heading_wrapper desktop"><div class="af2_question_heading desktop">' + json.frontend_name + '</div><div class="af2_question_description desktop">' + json.frontend_description + '</div></div>';
        content += '<div class="af2_question_heading_wrapper af2_mobile"><div class="af2_question_heading af2_mobile">' + json.frontend_name + '</div><div class="af2_question_description af2_mobile">' + json.frontend_description + '</div></div>';
        content += '<div class="af2_answer_container af2_mobile_'+json.type_specifics.mobile_layout+' af2_desktop_'+json.type_specifics.desktop_layout+'">';
        content += af2ProcessAnswersWithHide($, bonusClass, json.type_specifics.answers,json.type_specifics.desktop_layout,json.type_specifics.mobile_layout, json.type_specifics.hide_icons);
        content += '<div>';
    } else if (json.typ === 'af2_textfeld')
    {
        let minlength = json.type_specifics.min_length != null ? 'data-minlength="'+json.type_specifics.min_length+'"' : '';
        let maxlength = json.type_specifics.max_length != null ? 'data-maxlength="'+json.type_specifics.max_length+'"' : '';
        
        let text_only_text = json.type_specifics.text_only_text != null ? 'data-text_only_text="'+json.type_specifics.text_only_text+'"' : '';
        let text_only_numbers = json.type_specifics.text_only_numbers != null ? 'data-text_only_numbers="'+json.type_specifics.text_only_numbers+'"' : '';
        let text_birthday = json.type_specifics.text_birthday != null ? 'data-text_birthday="'+json.type_specifics.text_birthday+'"' : '';

        content += '<div class="af2_question_heading_wrapper desktop"><div class="af2_question_heading desktop">' + json.frontend_name + '</div><div class="af2_question_description desktop">' + json.frontend_description + '</div></div>';
        content += '<div class="af2_question_heading_wrapper af2_mobile"><div class="af2_question_heading af2_mobile">' + json.frontend_name + '</div><div class="af2_question_description af2_mobile">' + json.frontend_description + '</div></div>';
        content += '<input type="text" '+minlength+' '+maxlength+' '+text_only_text+' '+text_only_numbers+' '+text_birthday+' class="af2_textfeld_frage '+bonusClass+' desktop" data-mandatory="' + json.type_specifics.mandatory + '" placeholder="' + json.type_specifics.placeholder + '">';
        content += '<input type="text" '+minlength+' '+maxlength+' '+text_only_text+' '+text_only_numbers+' '+text_birthday+' class="af2_textfeld_frage '+bonusClass+' af2_mobile" data-mandatory="' + json.type_specifics.mandatory + '" placeholder="' + json.type_specifics.placeholder + '">';
    } else if (json.typ === 'af2_textbereich')
    {
        let minlength = json.type_specifics.min_length != null ? 'data-minlength="'+json.type_specifics.min_length+'"' : '';
        let maxlength = json.type_specifics.max_length != null ? 'data-maxlength="'+json.type_specifics.max_length+'"' : '';

        
        let text_only_text = json.type_specifics.text_only_text != null ? 'data-text_only_text="'+json.type_specifics.text_only_text+'"' : '';
        let text_only_numbers = json.type_specifics.text_only_numbers != null ? 'data-text_only_numbers="'+json.type_specifics.text_only_numbers+'"' : '';
        let text_birthday = json.type_specifics.text_birthday != null ? 'data-text_birthday="'+json.type_specifics.text_birthday+'"' : '';

        content += '<div class="af2_question_heading_wrapper desktop"><div class="af2_question_heading desktop">' + json.frontend_name + '</div><div class="af2_question_description desktop">' + json.frontend_description + '</div></div>';
        content += '<div class="af2_question_heading_wrapper af2_mobile"><div class="af2_question_heading af2_mobile">' + json.frontend_name + '</div><div class="af2_question_description af2_mobile">' + json.frontend_description + '</div></div>';
        content += '<textarea  '+minlength+' '+maxlength+' '+text_only_text+' '+text_only_numbers+' '+text_birthday+' class="af2_textbereich_frage '+bonusClass+' desktop" data-mandatory="' + json.type_specifics.mandatory + '"  placeholder="' + json.type_specifics.placeholder + '"></textarea>';
        content += '<textarea  '+minlength+' '+maxlength+' '+text_only_text+' '+text_only_numbers+' '+text_birthday+' class="af2_textbereich_frage '+bonusClass+' af2_mobile" data-mandatory="' + json.type_specifics.mandatory + '"  placeholder="' + json.type_specifics.placeholder + '"></textarea>';
    } 
    form.firstQuestion = false;
    return content;
};

const putInThousands = (json, cont, $) => {
    let len = cont.length;
    let prom = $.Deferred();

    let ret = '';

    let times = parseInt(len / 3);

    let thousands = json.type_specifics.thousand;

    if (thousands == true)
    {
        if (len > 3)
        {
            let mod = len % 3;
            if (len % 3 === 0)
            {
                times--;
            }

            if (mod === 0)
            {
                mod = 3;
            }

            let schritt = 0;
            for (schritt = 1; schritt <= times; schritt++)
            {
                ret = af2_frontend_ajax.strings.dot + cont.substr(cont.length - schritt * 3, 3) + ret;

                if (schritt === times)
                {
                    ret = cont.substr(0, mod) + ret
                    return prom.resolve(ret);
                }
            }
        } else
        {
            return prom.resolve(cont);
        }
    } else
    {
        return prom.resolve(cont);
    }



    return prom.promise();
};

/**
 * Draws the content of a Kontaktformular
 *
 * @param $
 * @param json
 */
const af2DrawKontaktformular = ($, json, form) => {
    const queryStr = window.location.search.substr(1);
    const queryArr = queryStr.split('&');

    let content = '';

    
    content += '<div class="af2_question_heading_wrapper desktop"><div class="af2_question_heading desktop">' + json.frontend_name + '</div><div class="af2_question_description desktop">' + json.frontend_description + '</div></div>';
    content += '<div class="af2_question_heading_wrapper af2_mobile"><div class="af2_question_heading af2_mobile">' + json.frontend_name + '</div><div class="af2_question_description af2_mobile">' + json.frontend_description + '</div></div>';

    $.each(json.questions, (i, el) => {
        let required = '';
        let label = el.label;
        let placeholder = el.placeholder;
        let el_id = el.id;
        let iconval = el.icon;

        let rtl_type_class = form.rtl_layout == true ? 'af2_rtl_layout' : '';
        
        let icon = iconval != null && iconval.trim() != '' ? '<div class="af2_question_cf_text_type_icon '+rtl_type_class+'"><i class="'+iconval+'"></i></div>' : '';
        let text_type_class = icon == '' ? 'af2_text_type_' : 'af2_text_type '+rtl_type_class;

        if (el.required == 'true' || el.required == true)
        {
            required = ' *';
        }
        if(label == undefined || label == 'undefined' || label == null) label = '';
        if(label == undefined || label.trim() == '') placeholder += required;
        else label += required;

        if(placeholder == 'undefined') {
            placeholder = "";
        }

        content += '<div id="' + i + '" class="af2_question">';

        let el_value = '';

        queryArr.forEach(el => {
            const queArr = el.split('=');
            if(queArr[0] == el_id) {
                el_value = decodeURIComponent(queArr[1]);
            }
        });

        el_value = el_value.replaceAll('+', ' ');
        

        if (el.typ.includes('text_type_'))
        {
            if (el.typ.includes('_name'))
            {
                content += '<div class="af2_question_text_type_wrapper af2_question_wrapper af2_question_wrapper_'+i+'">';
                if(label.trim() != '' )
                    content += '<div class="af2_question_text '+rtl_type_class+'"><p class="af2_question_label desktop">' + label + '</p><p class="af2_question_label af2_mobile">' + label + '</p></div>';
                content += '<div class="af2_question_cf_text_type_wrapper">';
                content += icon;
                content += '<input type="text" name="'+el_id+'" id="'+el.id+'" class="'+text_type_class+' " placeholder="' + placeholder + '" autofill="name" value="'+el_value+'">';
                content += '</div>';
                content += '</div>';
            } else if (el.typ.includes('_mail'))
            {
                content += '<div class="af2_question_text_type_wrapper af2_question_wrapper  af2_question_wrapper_'+i+'">';
                if(label.trim() != '' )
                    content += '<div class="af2_question_text '+rtl_type_class+'"><p class="af2_question_label desktop">' + label + '</p><p class="af2_question_label af2_mobile">' + label + '</p></div>';
                content += '<div class="af2_question_cf_text_type_wrapper">';
                content += icon;
                content += '<input type="text" name="'+el_id+'" id="'+el.id+'" autofill="email" class="'+text_type_class+'" placeholder="' + placeholder + '" value="'+el_value+'">';
                content += '</div>';
                content += '</div>';
            } else if (el.typ.includes('_phone'))
            {
                    content += '<div class="af2_question_text_type_wrapper af2_question_wrapper af2_question_wrapper_'+i+'">';
                    if(label.trim() != '' )
                        content += '<div class="af2_question_text '+rtl_type_class+'"><p class="af2_question_label desktop">' + label + '</p><p class="af2_question_label af2_mobile">' + label + '</p></div>';
                    content += '<div class="af2_question_cf_text_type_wrapper">';
                    content += icon;
                    content += '<input type="tel" name="'+el_id+'" id="'+el.id+'" autofill="tel" class="'+text_type_class+' phone_type" placeholder="' + placeholder + '" value="'+el_value+'">';
                    content += '</div>';
                    content += '</div>';
            } else
            {
                content += '<div class="af2_question_text_type_wrapper af2_question_wrapper af2_question_wrapper_'+i+'">';
                if(label.trim() != '' )
                    content += '<div class="af2_question_text '+rtl_type_class+'"><p class="af2_question_label desktop">' + label + '</p><p class="af2_question_label af2_mobile">' + label + '</p></div>';
                content += '<div class="af2_question_cf_text_type_wrapper">';
                content += icon;
                content += '<input type="text" name="'+el_id+'" id="'+el.id+'" class="'+text_type_class+'" placeholder="' + placeholder + '" value="'+el_value+'">';
                content += '</div>';
                content += '</div>';
            }
        } else if (el.typ.includes('checkbox'))
        {
            content += '<div class="af2_question_wrapper">';
            content += '<div class="af2_question_checkbox_type_wrapper">';
            content += '<input type="checkbox" data-uid="'+el_id+'" id="af2_checkbox_' + i + '" class="af2_checkbox_type"><div class="af2_question_text"><label for="af2_checkbox_' + i + '" class="af2_question_cb_label">' + el.text + required + '</label></div>';
            content += '</div>';
            content += '</div>';
        } else if (el.typ.includes('salutation'))
        {
            content += '<div class="af2_question_text_type_wrapper af2_question_wrapper">';
            if(label.trim() != '' )
                content += '<div class="af2_question_text '+rtl_type_class+'"><p class="af2_question_label desktop">' + label + '</p><p class="af2_question_label af2_mobile">' + label + '</p></div>';
            if(form.rtl_layout == true) content += '<div style="display: flex; align-items: center;">';
            else content += '<div style="display: flex; justify-content: left; align-items: center;">';
            let allowSalutationCompany = true;
            let allowSalutationMale = true;
            let allowSalutationFemale = true;
            let allowSalutationDivers = true;
            
            if(el.allowSalutationCompany == 'true') el.allowSalutationCompany = true;
            else if(el.allowSalutationCompany == 'false') el.allowSalutationCompany = false;
            if(el.allowSalutationMale == 'true') el.allowSalutationMale = true;
            else if(el.allowSalutationMale == 'false') el.allowSalutationMale = false;
            if(el.allowSalutationFemale == 'true') el.allowSalutationFemale = true;
            else if(el.allowSalutationFemale == 'false') el.allowSalutationFemale = false;
            if(el.allowSalutationDivers == 'true') el.allowSalutationDivers = true;
            else if(el.allowSalutationDivers == 'false') el.allowSalutationDivers = false;

            if(el.allowSalutationCompany != undefined && el.allowSalutationCompany != null && el.allowSalutationCompany == false) allowSalutationCompany = false;
            if(el.allowSalutationMale != undefined && el.allowSalutationMale != null && el.allowSalutationMale == false) allowSalutationMale = false;
            if(el.allowSalutationFemale != undefined && el.allowSalutationFemale != null && el.allowSalutationFemale == false) allowSalutationFemale = false;
            if(el.allowSalutationDivers != undefined && el.allowSalutationDivers != null && el.allowSalutationDivers == false) allowSalutationDivers = false;


            maleChecked = el_value.toLocaleLowerCase() == 'herr' ? 'checked' : '';
            femaleChecked = el_value.toLocaleLowerCase() == 'frau' ? 'checked' : '';
            diversChecked = el_value.toLocaleLowerCase() == 'divers' ? 'checked' : '';
            companyChecked = el_value.toLocaleLowerCase() == 'firma' ? 'checked' : '';

            if(form.rtl_layout == true) {
                if(allowSalutationMale) content += '<input style="margin-right: 5px;" type="radio" name="af_salutation_'+i+'" data-uid="'+el_id+'" id="male_'+i+'" value="Herr" '+maleChecked+'/><label for="male_'+i+'" class="af2_radio_label" style="margin-left: 25px;">'+af2_frontend_ajax.strings.mr+'</label>';
                if(allowSalutationFemale) content += '<input style="margin-right: 5px;" type="radio" name="af_salutation_'+i+'" data-uid="'+el_id+'" id="female_'+i+'" value="Frau" '+femaleChecked+'/><label for="female_'+i+'" class="af2_radio_label" style="margin-left: 25px;">'+af2_frontend_ajax.strings.mrs+'</label>';
                if(allowSalutationDivers) content += '<input style="margin-right: 5px;" type="radio" name="af_salutation_'+i+'" data-uid="'+el_id+'" id="divers_'+i+'" value="Divers" '+diversChecked+'/><label for="divers_'+i+'" class="af2_radio_label" style="margin-left: 25px;">'+af2_frontend_ajax.strings.diverse+'</label>';
                if(allowSalutationCompany) content += '<input style="margin-right: 5px;" type="radio" name="af_salutation_'+i+'" data-uid="'+el_id+'" id="company_'+i+'" value="Firma" '+companyChecked+'/><label class="af2_radio_label" for="company_'+i+'">'+af2_frontend_ajax.strings.company+'</label>';
            }
            else {
                if(allowSalutationMale) content += '<input style="margin-right: 5px;" type="radio" name="af_salutation_'+i+'" data-uid="'+el_id+'" id="male_'+i+'" value="Herr" '+maleChecked+'/><label for="male_'+i+'" class="af2_radio_label" style="margin-right: 25px;">'+af2_frontend_ajax.strings.mr+'</label>';
                if(allowSalutationFemale) content += '<input style="margin-right: 5px;" type="radio" name="af_salutation_'+i+'" data-uid="'+el_id+'" id="female_'+i+'" value="Frau" '+femaleChecked+'/><label for="female_'+i+'" class="af2_radio_label" style="margin-right: 25px;">'+af2_frontend_ajax.strings.mrs+'</label>';
                if(allowSalutationDivers) content += '<input style="margin-right: 5px;" type="radio" name="af_salutation_'+i+'" data-uid="'+el_id+'" id="divers_'+i+'" value="Divers" '+diversChecked+'/><label for="divers_'+i+'" class="af2_radio_label" style="margin-right: 25px;">'+af2_frontend_ajax.strings.diverse+'</label>';
                if(allowSalutationCompany) content += '<input style="margin-right: 5px;" type="radio" name="af_salutation_'+i+'" data-uid="'+el_id+'" id="company_'+i+'" value="Firma" '+companyChecked+'/><label class="af2_radio_label" for="company_'+i+'">'+af2_frontend_ajax.strings.company+'</label>';
            }
            
            content += '</div>';
            content += '</div>';
        } 

        content += '</div>';
    });

    content += '<div class="af2_question_wrapper">';
    
    content += '<div class="af2_submit_wrapper">';
        content += '<button type="button" class="af2_submit_button">';
        if(form.rtl_layout == true) {
            content += '<span class="af2_submit_button__text">'+json.sendButtonLabel+'<i class="fa fa-paper-plane" style="margin-right: 10px;"></i></span>';
        }
        else {
            content += '<span class="af2_submit_button__text"><i class="fa fa-paper-plane" style="margin-right: 10px;"></i>'+json.sendButtonLabel+'</span>';
        }
        content += '</button>'
    content += '</div>';
    //content += '<div class="af2_submit_wrapper"><input class="af2_submit_button" value="' + json.sendButtonLabel + '" type="submit"></div>';
    content += '</div>';

    return content;
};

const af2_isMobile = () => {
    const winWidth = jQuery(window).width();
    if(winWidth < 700){
        return true;
    }else{
        return false;
    }
}

const af2_isMobileView = ($, formSelector) => {
    if(!$(formSelector).hasClass('af2_form-type-2')) {
        const winWidth = jQuery(window).width();

        if(winWidth < 700){
            return true;
        }else{
            return false;
        }
    }
    
    if($(formSelector).hasClass('af2_nm_mobile_view')) {
        return true;
    }
    if($(formSelector).hasClass('af2_nm_ipad_view')) {
        return false;
    }
    if($(formSelector).hasClass('af2_nm_desktop_view')) {
        return false;
    }
}

/**
 * Process Answers for a question
 *
 * @param $
 * @param answers
 * @returns {string}
 */
const af2ProcessAnswers = ($, bonusClass, answers,desktop_layout,mobile_layout) => {
    af2ProcessAnswersWithHide($, bonusClass, answers,desktop_layout,mobile_layout, false);
}

const af2ProcessAnswersWithHide = ($, bonusClass, answers,desktop_layout,mobile_layout, hide) => {
    let content = '';
    $.each(answers, (i, el) => {
        let answer_img = '';
        let answer_mob_img = '';
        if (el.icon_type === 'url')
        {
            // display without images
            if(hide) {
                if (desktop_layout == 'list' || desktop_layout == 'list2') {
                    answer_img = '<div class="af2_answer_card desktop"><div class="af2_answer_text desktop text-center">' + el.text + '</div></div>';
                } else {
                    answer_img = '<div class="af2_answer_card desktop"><div class="af2_answer_text desktop text-center">' + el.text + '</div></div>';
                }

                if(mobile_layout == 'grid'){
                    answer_mob_img = '<div class="af2_answer_card af2_mobile"><div class="af2_answer_text af2_mobile text-center">' + el.text + '</div></div>';
                }else{
                    answer_mob_img = '<div class="af2_answer_card af2_mobile"><div class="af2_answer_text af2_mobile text-center">' + el.text + '</div></div>';
                }
            } else {
                if(desktop_layout == 'list' || desktop_layout == 'list2'){
                    answer_img = '<div class="af2_answer_card desktop"><div class="af2_answer_image_wrapper"><img class="af2_answer_image pic" src="' + el.icon + '" alt="answer_image"></div><div class="af2_answer_text desktop">' + el.text + '</div></div>';
                }else{
                    answer_img = '<div class="af2_answer_card desktop"><div class="af2_answer_image_wrapper"><img class="af2_answer_image pic" src="' + el.icon + '" alt="answer_image"></div></div><div class="af2_answer_text desktop">' + el.text + '</div>';
                }

                if(mobile_layout == 'grid'){
                    answer_mob_img = '<div class="af2_answer_card af2_mobile"><div class="af2_answer_image_wrapper af2_mobile"><img class="af2_answer_image pic" src="' + el.icon + '" alt="answer_image"></div></div><div class="af2_answer_text af2_mobile">' + el.text + '</div>';
                }else{
                    answer_mob_img = '<div class="af2_answer_card af2_mobile"><div class="af2_answer_image_wrapper af2_mobile"><img class="af2_answer_image pic" src="' + el.icon + '" alt="answer_image"></div><div class="af2_answer_text af2_mobile">' + el.text + '</div></div>';
                }
            }

        } else if (el.icon_type === 'font-awesome')
        {
            // display without icons
            if(hide) {
                if (desktop_layout == 'list' || desktop_layout == 'list2') {
                    answer_img = '<div class="af2_answer_card desktop"><div class="af2_answer_text desktop text-center">' + el.text + '</div></div>';
                } else {
                    answer_img = '<div class="af2_answer_card desktop"><div class="af2_answer_text desktop text-center">' + el.text + '</div></div>';
                }

                if(mobile_layout == 'grid'){
                    answer_mob_img = '<div class="af2_answer_card af2_mobile"><div class="af2_answer_text af2_mobile text-center">' + el.text + '</div></div>';
                }else{
                    answer_mob_img = '<div class="af2_answer_card af2_mobile"><div class="af2_answer_text af2_mobile text-center">' + el.text + '</div></div>';
                }
            } else {

                if (desktop_layout == 'list' || desktop_layout == 'list2') {
                    answer_img = '<div class="af2_answer_card desktop"><div class="af2_answer_image_wrapper"><i class="' + el.icon + ' fa-3x"></i></div><div class="af2_answer_text desktop">' + el.text + '</div></div>';
                } else {
                    answer_img = '<div class="af2_answer_card desktop"><div class="af2_answer_image_wrapper"><i class="' + el.icon + ' fa-5x"></i></div></div><div class="af2_answer_text desktop">' + el.text + '</div>';
                }

                if (mobile_layout == 'grid') {
                    answer_mob_img = '<div class="af2_answer_card af2_mobile"><div class="af2_answer_image_wrapper af2_mobile"><i class="' + el.icon + ' fa-4x"></i></div></div><div class="af2_answer_text af2_mobile">' + el.text + '</div>';
                } else {
                    answer_mob_img = '<div class="af2_answer_card af2_mobile"><div class="af2_answer_image_wrapper af2_mobile"><i class="' + el.icon + ' fa-2x"></i></div><div class="af2_answer_text af2_mobile">' + el.text + '</div></div>';
                }
            }
        }

        content += '<div id="' + i + '" class="af2_answer '+bonusClass+' desktop">';
        content += answer_img;
        content += '</div>';

        content += '<div id="' + i + '" class="af2_answer '+bonusClass+' af2_mobile">';
        content += answer_mob_img;
        content += '</div>';
    });

    return content;
};


/**
 * Performing and handling a Data-request
 *
 * @param $
 * @param formSelector
 * @param selector
 * @param dataids
 */
const af2HandleRequest = ($, formSelector, selector, dataids, form) => {
    const result = requestData($, dataids, form);

    /**
     * When it had no error -> Throw the Event and set the data into the Array
     */
    result.done((json) => {

        /** Setting up Data **/
        const keys = Object.keys(json);

        $.each(keys, (i, el) => {
            let newJson = json[el];

            if(newJson.type_specifics != null) {
                const keysofjson = Object.keys(newJson.type_specifics);

                $.each(keysofjson, (j, ele) => {
                    if(newJson.type_specifics[ele] == 'true') newJson.type_specifics[ele] = true;
                    if(newJson.type_specifics[ele] == 'false') newJson.type_specifics[ele] = false;

                    if(ele == 'center' && newJson.type_specifics[ele].lat != null && newJson.type_specifics[ele].lng != null) {
                        newJson.type_specifics[ele].lat = Number(newJson.type_specifics[ele].lat);
                        newJson.type_specifics[ele].lng = Number(newJson.type_specifics[ele].lng);
                    }
                });
            }

            datas[el] = json[el];
        });

        /** Throwing out event **/
        let finishedEvent = jQuery.Event('loadedData');
        finishedEvent.dataids = dataids;
        jQuery(selector).trigger(finishedEvent);
    });
    /**
     * When it has an Error -> just send the Error out and fill the data with 'ERROR'
     */
    result.fail((error) => {
        af2ThrowError($, jQuery(formSelector), error);
    });
};

/**
 * Request all data needet to process the Formular in future
 */
const requestData = ($, dataids, form) => {
    
    const prom = $.Deferred();
    jQuery.ajax({
        url: af2_frontend_ajax.ajax_url,
        type: "GET",
        data: {
            _ajax_nonce: af2_frontend_ajax.nonce,
            action: 'af2_request_data',
            ids: dataids
        },
        success: (answer) => {
            if (answer.indexOf('ERROR') != -1)
            {
                if(answer != 'ERRORX')
                {
                    // REQUEST ERROR EMAIL
                    /*
                    if(form.errormail == true) {
                        //send_errormail($, "Intern - " + answer);

                        if(answer == af2_frontend_ajax.strings.error_01)
                        {prom.reject(af2_frontend_ajax.strings.fehler_admin+'<a href="'+af2_frontend_ajax.strings.help_url+'" target="_blank">'+af2_frontend_ajax.strings.help+'</a>');
                        }
                        else prom.reject(af2_frontend_ajax.strings.fehler_find);
                    }
                    else*/
                    if(answer == af2_frontend_ajax.strings.error_01) {
                        prom.reject(af2_frontend_ajax.strings.fehler_admin+'<a href="'+af2_frontend_ajax.strings.help_url+'" target="_blank">'+af2_frontend_ajax.strings.help+'</a>');
                    }
                    else prom.reject(af2_frontend_ajax.strings.fehler_find);
                }
                else prom.reject(af2_frontend_ajax.strings.fehler_find);
            } 
            else
            {
                let answerJson = JSON.parse(answer);
                prom.resolve(answerJson);
            }
        },
        error: (xhr, status, error) => {
            // REQUEST ERROR EMAIL
            if(form.errormail == true) {
                //send_errormail($, "REQUEST - " +  xhr.status + ' - ' + xhr.statusText + ' - ' + status + ' - ' + error+ ' :--');     
            }
            prom.reject(af2_frontend_ajax.strings.fehler_admin+'<a href="'+af2_frontend_ajax.strings.help_url+'" target="_blank">'+af2_frontend_ajax.strings.help+'</a>');
        }
    });

    return prom.promise();
};

const send_errormail = ($, errorcode) => {
    /**const currentURL = window.location.href;
    jQuery.ajax({
        url: af2_frontend_ajax.ajax_url,
        type: "GET",
        data: {
            _ajax_nonce: af2_frontend_ajax.nonce,
            action: 'af2_send_error_mail',
			errorcode: errorcode,
			currentURL: currentURL,
            errortype: 'intern'
        },
        success: (answer) => {

        },
        error: () => {

        }
    });**/
};

/**
 * Find out if this attribute is in the array
 *
 * @param $
 * @param attribute
 * @param arr
 */
const af2CompareAttributeInArray = ($, attribute, arr) => {
    const prom = $.Deferred();

    jQuery(arr).each((i, el) => {
        if (el === attribute)
        {
            prom.resolve();
        }
    });

    return prom.promise();
};

/**
 * Function to append stylings
 */
const af2LoadStyling = ($, id, formSelector, cb) => {
    /** Overwrite styling **/
    af2OverwriteStylings($, datas[id].styling).done((styling) => {
        /** Generate the styling **/
        if(styling == null) { 
            cb(); 
            return;
        }
        else {
            af2GenerateStylingContent($, formSelector, styling).done((style) => {
                jQuery('head').append(style);
                cb();
            });
        }
    });
};

/**
 * Merges the new Styling with the basic one
 *
 * @param $
 * @param styling
 * @returns json
 */
const af2OverwriteStylings = ($, styling) => {
    const prom = $.Deferred();
    let newStyling = af2Styles;

    if(styling == null || styling == undefined) prom.resolve(null);
    else {
    
        // Copy of the basic styling
        const keys = Object.keys(styling);
        jQuery(keys).each((i, e) => {
            $.each(styling[e], (j, el) => {
                $.each(newStyling[e], (k, ele) => {
                    if (ele.attribute === el.attribute)
                    {
                        if (ele.special_class === el.special_class && ele.special_state === el.special_state && ele.special_extra === el.special_extra)
                        {
                            newStyling[e][k].value = styling[e][j].value;
                        }
                    }
                });
            });

            if (i === keys.length - 1)
            {
                prom.resolve(newStyling);
            }
        });
    }

    return prom.promise();
};

/**
 * Generate the content for the styling
 *
 * @param $
 * @param formSelector
 * @param styling
 * @returns {*}
 */
const af2GenerateStylingContent = ($, formSelector, styling) => {
    const prom = $.Deferred();
    let content = '';

    /** Create wrapper **/
    content += '<style>';

    const keys = Object.keys(styling);

    jQuery(keys).each((i) => {
        let desktopList = [];
        let mobileList = [];
        let othersList = [];
        let dateList = [];
        let af2DisabledList = [];
        let focusList = [];
        let wtList = [];
        let mtList = [];
        let formClassList = [];
        content += formSelector + ' .' + keys[i] + '{';

        $.each(styling[keys[i]], (j, e) => {
            if (e.special_class !== undefined)
            {
                if (e.special_class === "desktop")
                    desktopList.push(e);
                else if (e.special_class === "af2_mobile")
                    mobileList.push(e);
                else if (e.special_class === "af2_disabled")
                    af2DisabledList.push(e);
                else if (e.special_class === "af2_datepicker")
                    dateList.push(e);
                else if (e.special_class === "form_class")
                    formClassList.push(e);
                else othersList.push(e);

            } else if (e.special_state !== undefined)
            {
                if (e.special_state === "focus")
                    focusList.push(e);
            } else if (e.special_extra !== undefined)
            {
                if (e.special_extra === "-webkit-slider-thumb")
                    wtList.push(e);
                else if (e.special_extra === "-moz-range-thumb")
                    mtList.push(e);
            } else
            {
                content += e.attribute + ':' + e.value + ';';
            }
        });

        content += '}';

        /** Desktop **/
        if (desktopList.length > 0)
        {
            content += formSelector + ' .' + keys[i] + '.desktop {';
        }
        $.each(desktopList, (j, e) => {
            content += e.attribute + ':' + e.value + ';';
        });
        if (desktopList.length > 0)
        {
            content += '}';
        }

        // Others
        $.each(othersList, (j, e) => {
            content += formSelector + ' .' + keys[i] + '.' + e.special_class + ' {';
            content += e.attribute + ':' + e.value + ';';
            content += '}';
        });

        formClassFocusList = [];

        /** formClassList **/
        if (formClassList.length > 0)
        {
            content += formSelector.replace('#', '.') + '.' + keys[i] + ' {';
        }
        $.each(formClassList, (j, e) => {
            if(e.special_state !== undefined)
                formClassFocusList.push(e);
            else
                content += e.attribute + ':' + e.value + ';';
        });
        if (formClassList.length > 0)
        {
            content += '}';
        }

         /** formClassList focus **/
         if (formClassFocusList.length > 0)
         {
             content += formSelector.replace('#', '.') + '.' + keys[i] + ':focus {';
         }
         $.each(formClassFocusList, (j, e) => {
            content += e.attribute + ':' + e.value + ';';
         });
         if (formClassFocusList.length > 0)
         {
             content += '}';
         }

        /** Mobile **/
        if (mobileList.length > 0)
        {
            content += formSelector + ' .' + keys[i] + '.af2_mobile {';
        }
        $.each(mobileList, (j, e) => {
            content += e.attribute + ':' + e.value + ';';
        });
        if (mobileList.length > 0)
        {
            content += '}';
        }

        /** af2Disabled **/
        if (af2DisabledList.length > 0)
        {
            content += formSelector + ' .' + keys[i] + '.af2_disabled {';
        }
        $.each(af2DisabledList, (j, e) => {
            content += e.attribute + ':' + e.value + ';';
        });
        if (af2DisabledList.length > 0)
        {
            content += '}';
        }

        /** Datepciker styling **/
        if (dateList.length > 0)
        {
            $.each(dateList, (j, e) => {
                content += formSelector + ' .af2-datepicker .' + e.sub_class + ' {';
                content += e.attribute + ':' + e.value + ';';
                content += '}';
                content += formSelector + ' .af2_terminbuchung_datewrapper .' + e.sub_class + ' {';
                content += e.attribute + ':' + e.value + ';';
                content += '}';
            });
        }

        /** :focus **/
        if (focusList.length > 0)
        {
            content += formSelector + ' .' + keys[i] + ':focus {';
        }
        $.each(focusList, (j, e) => {
            content += e.attribute + ':' + e.value + ';';
        });
        if (focusList.length > 0)
        {
            content += '}';
        }

        /** moz **/
        if (mtList.length > 0)
        {
            content += formSelector + ' .' + keys[i] + '::-moz-range-thumb {';
        }
        $.each(mtList, (j, e) => {
            content += e.attribute + ':' + e.value + ';';
        });
        if (mtList.length > 0)
        {
            content += '}';
        }

        /** web **/
        if (wtList.length > 0)
        {
            content += formSelector + ' .' + keys[i] + '::-webkit-slider-thumb {';
        }
        $.each(wtList, (j, e) => {
            content += e.attribute + ':' + e.value + ';';
        });
        if (wtList.length > 0)
        {
            content += '}';
        }

        if (i === keys.length - 1)
        {
            prom.resolve(content);
        }
    });

    /** Close wrapper **/
    content += '</style>';

    return prom.promise();
};

/**
 * Throw an Error to the given Selector
 *
 * @param $
 * @param selector
 * @param errortext
 */
const af2ThrowError = ($, selector, errortext) => {
    selector.after('<p class="af2_loading_error">' + errortext + '</p>');
};


const af2ThrowLoadingSuccess = ($, selector, html) => {
    selector.append(html);
};
const af2ThrowLoadingError = ($, selector, html) => {
    selector.append(html);
};

function GMap($, neededContent, actualData, formSelector, setHeight, form) {
    this.$ = $;
    this.formSelector = formSelector;

    this.mapCount = form.mapCount;
    this.markers = [];
    this.form = form;

    this.selector = this.formSelector + ' .af2_adress_mapp_wrapper.af2_mw_'+(this.mapCount-1);
    this.selectorMap = this.$(this.selector + ' #af2_adress_field')[0];

    this.mandatory = jQuery(this.selector).data('mandatory');

    const fact = neededContent == undefined ? actualData : neededContent;

    const zoomlevel = datas[fact].type_specifics.zoomlevel == undefined ? 8 : parseInt(datas[fact].type_specifics.zoomlevel);

    const center = datas[fact].type_specifics.center == undefined ? { lat: -34.397, lng: 150.644 } : datas[fact].type_specifics.center;

    this.map = new extraParms.maps.Map(this.selectorMap, {
        center: center,
        zoom: zoomlevel,
        mapTypeId: "roadmap",
        mapTypeControlOptions: { mapTypeIds: [] },
        disableDefaultUI: true,
    });

    this.autocomplete = new extraParms.maps.places.Autocomplete(
        jQuery(selector + ' input#af2_adress_street')[0], {types: ["geocode"]}
    );
    this.autocomplete2 = new extraParms.maps.places.Autocomplete(
        jQuery(selector + ' input#af2_adress_street_')[0], {types: ["geocode"]}
    );

    this.form.setLastAddrObject("");

    if(this.mandatory == false){
        this.$(this.formSelector + ' .af2_form_foward_button').removeClass('af2_disabled');
        this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');
    }

    

    this.fillInAdress = () => {
        const place = this.autocomplete.getPlace();
        let street_number = '';
        let route = '';
        let postal_code = '';
        let locality = '';
        jQuery(this.selector + ' #af2_adress_streetnum').val('');
        jQuery(this.selector + ' #af2_adress_street').val('');
        jQuery(this.selector + ' #af2_adress_plz').val('');
        jQuery(this.selector + ' #af2_adress_city').val('');
        place.address_components.forEach((el) => {
            const adressType = el.types[0];
            const val = el.long_name;
            switch(adressType) {
                case 'street_number': {
                    jQuery(this.selector + ' #af2_adress_streetnum').val(val);
                    street_number = val;
                    break;
                }
                case 'route': {
                    jQuery(this.selector + ' #af2_adress_street').val(val);
                    route = val;
                    break;
                }
                case 'postal_code': {
                    jQuery(this.selector + ' #af2_adress_plz').val(val);
                    postal_code = val;
                    break;
                }
                case 'locality': {
                    jQuery(this.selector + ' #af2_adress_city').val(val);
                    locality = val;
                    break;
                }
                default: break;
            }
            //console.log(adressType + ' - ' + val);
        });
        let clean = true;
        if(street_number == '') {
            clean = false;
            this.$(this.selector + ' #af2_adress_streetnum').css('border-color', 'red');
            if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
        } else this.$(this.selector + ' #af2_adress_streetnum').css('border-color', 'rgba(51,51,51,0.12)');
        if(route == '') {
            clean = false;
            this.$(this.selector + ' #af2_adress_street').css('border-color', 'red');
            if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
        } else this.$(this.selector + ' #af2_adress_street').css('border-color', 'rgba(51,51,51,0.12)');
        if(postal_code == '') {
            clean = false;
            this.$(this.selector + ' #af2_adress_plz').css('border-color', 'red');
            if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
        } else this.$(this.selector + ' #af2_adress_plz').css('border-color', 'rgba(51,51,51,0.12)');
        if(locality == '') {
            clean = false;
            this.$(this.selector + ' #af2_adress_city').css('border-color', 'red');
            if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
        } else this.$(this.selector + ' #af2_adress_city').css('border-color', 'rgba(51,51,51,0.12)');
        if(clean) this.$(this.formSelector + ' .af2_form_foward_button.desktop').removeClass('af2_disabled');
    
        this.markers.forEach((marker) => {
            marker.setMap(null);
        });
        this.markers = [];
    
        const icon = {
            url: place.icon,
            size: new extraParms.maps.Size(71, 71),
            origin: new extraParms.maps.Point(0, 0),
            anchor: new extraParms.maps.Point(17, 34),
            scaledSize: new extraParms.maps.Size(25, 25),
        };
    
    
        let mark = new extraParms.maps.Marker({
            title: place.name, position: place.geometry.location,
        });
        mark.setMap(this.map);
        this.markers.push(mark);
    
        this.map.setCenter(place.geometry.location);
        this.map.setZoom(16);
    
        this.form.setLastAddrObject(route + ' ' + street_number + ', ' + postal_code + ' ' + locality);
    }

    this.fillInAdress2 = () => {
        const place = this.autocomplete2.getPlace();
        let street_number = '';
        let route = '';
        let postal_code = '';
        let locality = '';
        jQuery(this.selector + ' #af2_adress_streetnum_').val('');
        jQuery(this.selector + ' #af2_adress_street_').val('');
        jQuery(this.selector + ' #af2_adress_plz_').val('');
        jQuery(this.selector + ' #af2_adress_city_').val('');
        place.address_components.forEach((el) => {
            const adressType = el.types[0];
            const val = el.long_name;
            switch(adressType) {
                case 'street_number': {
                    jQuery(this.selector + ' #af2_adress_streetnum_').val(val);
                    street_number = val;
                    break;
                }
                case 'route': {
                    jQuery(this.selector + ' #af2_adress_street_').val(val);
                    route = val;
                    break;
                }
                case 'postal_code': {
                    jQuery(this.selector + ' #af2_adress_plz_').val(val);
                    postal_code = val;
                    break;
                }
                case 'locality': {
                    jQuery(this.selector + ' #af2_adress_city_').val(val);
                    locality = val;
                    break;
                }
                default: break;
            }
            //console.log(adressType + ' - ' + val);
        });
        let clean = true;
        if(street_number == '') {
            clean = false;
            this.$(this.selector + ' #af2_adress_streetnum_').css('border-color', 'red');
            if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
        } else this.$(this.selector + ' #af2_adress_streetnum_').css('border-color', 'rgba(51,51,51,0.12)');
        if(route == '') {
            clean = false;
            this.$(this.selector + ' #af2_adress_street_').css('border-color', 'red');
            if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
        } else this.$(this.selector + ' #af2_adress_street_').css('border-color', 'rgba(51,51,51,0.12)');
        if(postal_code == '') {
            clean = false;
            this.$(this.selector + ' #af2_adress_plz_').css('border-color', 'red');
            if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
        } else this.$(this.selector + ' #af2_adress_plz_').css('border-color', 'rgba(51,51,51,0.12)');
        if(locality == '') {
            clean = false;
            this.$(this.selector + ' #af2_adress_city_').css('border-color', 'red');
            if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
        } else this.$(this.selector + ' #af2_adress_city_').css('border-color', 'rgba(51,51,51,0.12)');
        if(clean) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');

        this.markers.forEach((marker) => {
            marker.setMap(null);
        });
        this.markers = [];

        const icon = {
            url: place.icon,
            size: new extraParms.maps.Size(71, 71),
            origin: new extraParms.maps.Point(0, 0),
            anchor: new extraParms.maps.Point(17, 34),
            scaledSize: new extraParms.maps.Size(25, 25),
        };


        let mark = new extraParms.maps.Marker({
            title: place.name, position: place.geometry.location,
        });
        mark.setMap(this.map);
        this.markers.push(mark);

        this.map.setCenter(place.geometry.location);
        this.map.setZoom(16);

        this.form.setLastAddrObject(route + ' ' + street_number + ', ' + postal_code + ' ' + locality);
    }

    this.triggersDesktop = () => {
        jQuery(this.selector + ' #af2_adress_streetnum').on('input', (ev) => {
            const streetnum = jQuery(this.selector + ' #af2_adress_streetnum').val();
            const street = jQuery(this.selector + ' #af2_adress_street').val();
            const plz = jQuery(this.selector + ' #af2_adress_plz').val();
            const city = jQuery(this.selector + ' #af2_adress_city').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_streetnum').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_streetnum').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_street').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_street').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_plz').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_plz').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_city').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_city').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.desktop').removeClass('af2_disabled');
            this.form.setLastAddrObject( street + ' ' + streetnum + ', ' + plz + ' ' + city);
        });
        jQuery(this.selector + ' #af2_adress_street').on('input', (ev) => {
            const streetnum = jQuery(this.selector + ' #af2_adress_streetnum').val();
            const street = jQuery(this.selector + ' #af2_adress_street').val();
            const plz = jQuery(this.selector + ' #af2_adress_plz').val();
            const city = jQuery(this.selector + ' #af2_adress_city').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_streetnum').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_streetnum').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_street').css('border-color', 'red');
                if(this.mandatory == true)  this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_street').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_plz').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_plz').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_city').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_city').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.desktop').removeClass('af2_disabled');
            this.form.setLastAddrObject( street + ' ' + streetnum + ', ' + plz + ' ' + city);
        });
        jQuery(this.selector + ' #af2_adress_plz').on('input', (ev) => {
            const streetnum = jQuery(this.selector + ' #af2_adress_streetnum').val();
            const street = jQuery(this.selector + ' #af2_adress_street').val();
            const plz = jQuery(this.selector + ' #af2_adress_plz').val();
            const city = jQuery(this.selector + ' #af2_adress_city').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_streetnum').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_streetnum').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_street').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_street').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_plz').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_plz').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_city').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_city').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.desktop').removeClass('af2_disabled');
            this.form.setLastAddrObject( street + ' ' + streetnum + ', ' + plz + ' ' + city);
        });
        jQuery(this.selector + ' #af2_adress_city').on('input', (ev) => {
            const streetnum = jQuery(this.selector + ' #af2_adress_streetnum').val();
            const street = jQuery(this.selector + ' #af2_adress_street').val();
            const plz = jQuery(this.selector + ' #af2_adress_plz').val();
            const city = jQuery(this.selector + ' #af2_adress_city').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_streetnum').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_streetnum').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_street').css('border-color', 'red');
                if(this.mandatory == true)  this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_street').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_plz').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_plz').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_city').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.desktop').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_city').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.desktop').removeClass('af2_disabled');
            this.form.setLastAddrObject( street + ' ' + streetnum + ', ' + plz + ' ' + city);
        });
    }

    this.triggersMobile = () => {
        jQuery(this.selector + ' #af2_adress_streetnum_').on('input', (ev) => {
            const streetnum = jQuery(this.selector + ' #af2_adress_streetnum_').val();
            const street = jQuery(this.selector + ' #af2_adress_street_').val();
            const plz = jQuery(this.selector + ' #af2_adress_plz_').val();
            const city = jQuery(this.selector + ' #af2_adress_city_').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_streetnum_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_streetnum_').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_street_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_street_').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_plz_').css('border-color', 'red');
                if(this.mandatory == true)this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_plz_').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_city_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_city_').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');
            this.form.setLastAddrObject( street + ' ' + streetnum + ', ' + plz + ' ' + city);
        });
        jQuery(this.selector + ' #af2_adress_street_').on('input', (ev) => {
            const streetnum = jQuery(this.selector + ' #af2_adress_streetnum_').val();
            const street = jQuery(this.selector + ' #af2_adress_street_').val();
            const plz = jQuery(this.selector + ' #af2_adress_plz_').val();
            const city = jQuery(this.selector + ' #af2_adress_city_').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_streetnum_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_streetnum_').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_street_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_street_').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_plz_').css('border-color', 'red');
                if(this.mandatory == true)  this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_plz_').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_city_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_city_').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');
            this.form.setLastAddrObject( street + ' ' + streetnum + ', ' + plz + ' ' + city);
        });
        jQuery(this.selector + ' #af2_adress_plz_').on('input', (ev) => {
            const streetnum = jQuery(this.selector + ' #af2_adress_streetnum_').val();
            const street = jQuery(this.selector + ' #af2_adress_street_').val();
            const plz = jQuery(this.selector + ' #af2_adress_plz_').val();
            const city = jQuery(this.selector + ' #af2_adress_city_').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_streetnum_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_streetnum_').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_street_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_street_').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_plz_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_plz_').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_city_').css('border-color', 'red');
                if(this.mandatory == true)  this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_city_').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');
            this.form.setLastAddrObject( street + ' ' + streetnum + ', ' + plz + ' ' + city);
        });
        jQuery(this.selector + ' #af2_adress_city_').on('input', (ev) => {
            const streetnum = jQuery(this.selector + ' #af2_adress_streetnum_').val();
            const street = jQuery(this.selector + ' #af2_adress_street_').val();
            const plz = jQuery(this.selector + ' #af2_adress_plz_').val();
            const city = jQuery(this.selector + ' #af2_adress_city_').val();

            let clean = true;
            if(streetnum == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_streetnum_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_streetnum_').css('border-color', 'rgba(51,51,51,0.12)');
            if(street == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_street_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_street_').css('border-color', 'rgba(51,51,51,0.12)');
            if(plz == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_plz_').css('border-color', 'red');
                if(this.mandatory == true) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_plz_').css('border-color', 'rgba(51,51,51,0.12)');
            if(city == '') {
                clean = false;
                this.$(this.selector + ' #af2_adress_city_').css('border-color', 'red');
                if(this.mandatory == true)  this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').addClass('af2_disabled');
            } else this.$(this.selector + ' #af2_adress_city_').css('border-color', 'rgba(51,51,51,0.12)');
            if(clean) this.$(this.formSelector + ' .af2_form_foward_button.af2_mobile').removeClass('af2_disabled');
            this.form.setLastAddrObject( street + ' ' + streetnum + ', ' + plz + ' ' + city);
        });
    }

    setTimeout(() => {
        autocomplete.setFields(["address_component", "geometry"]);
        autocomplete.addListener("place_changed", this.fillInAdress);
        autocomplete2.setFields(["address_component", "geometry"]);
        autocomplete2.addListener("place_changed", this.fillInAdress2);
    }, 1000);

    setHeight();

    this.triggersDesktop();
    this.triggersMobile();
}



jQuery(document).ready(($) => {
    if(jQuery('.af2_form_wrapper').length > 0){
            af2_init($);  
    }

    document.fonts.onloadingdone = (fontFaceSetEvent) => {
        jQuery('.af2_form_wrapper').trigger('af2_loaded_font', []);
    };
    
    jQuery(document).on('click', '.iti__flag-container', function() { 
        var countryCode = jQuery('.iti__selected-flag').attr('title');
        countryCode = countryCode.replace(/[^0-9]/g,'')
        jQuery('#telefon').val("");
        jQuery('#telefon').val("+"+countryCode+" "+ jQuery('#telefon').val());
    });

    jQuery(document).on("input", '.iti--allow-dropdown #telefon', function(e){
        let value = e.currentTarget.value;
        let countryCode = jQuery('.iti__selected-flag').attr('title');
        countryCode = countryCode.replace(/[^0-9]/g,'');
        if( value == '+'+countryCode) return false;
        if( value.indexOf('+'+countryCode) == -1 ){
            if( '+'+countryCode.slice(0,countryCode.length-1) == value ){
                e.currentTarget.value = "+"+countryCode+" ";
            }else{
                e.currentTarget.value = "+"+countryCode+" "+ value;
            }
        }
    });
});

const af2_deepCopyArray = (arr) => {
    let newArr = [];
  
    arr.forEach((el, i) => {
      if (Array.isArray(el)) newArr.push(af2_deepCopyArray(el));
      else if (typeof el === 'object') newArr.push(af2_deepCopyObject(el));
      else newArr.push(el);
    });
  
    return newArr;
  }
  
  const af2_deepCopyObject = (obj) => {
    let newObj = {};
      
    for(let [key, val] of Object.entries(obj)){
        if(Array.isArray(val)) newObj[key] = af2_deepCopyArray(val);
      else if (typeof val === 'object') newObj[key] = af2_deepCopyObject(val);
      else newObj[key] = val;
    }
    
    return newObj;
  }


function af2_convertTZ(date, tzString) {
    return new Date((typeof date === "string" ? new Date(date) : date).toLocaleString("en-US", {timeZone: tzString}));   
}

function af2_returnTimeZoneName() {
	return Intl.DateTimeFormat().resolvedOptions().timeZone;
}

function af2_getTimeZoneNameRegion() {
	let str = Intl.DateTimeFormat().resolvedOptions().timeZone;
  
    return str.split('/')[0];
}

function af2_getTodayStandard() {
    return af2_convertTZ(new Date(), 'Europe/Amsterdam');
}

function af2_convertMinutesToHoursAndMinutes(num) {
    let number = Math.abs(num);
    let hours = number/60;
    let rHours = Math.floor(hours);
    let minutes = (hours - rHours) * 60;
    let rMinutes = Math.round(minutes);

    rHours = (rHours < 10 ? '0' : '') + rHours;
    rMinutes = (rMinutes < 10 ? '0' : '') + rMinutes;

    let vorzeichen = Math.sign(num);

    if(vorzeichen == 0) vorzeichen = '+';
    if(vorzeichen == 1) vorzeichen = '-';
    if(vorzeichen == -1) vorzeichen = '+';

    return vorzeichen + rHours + ':' + rMinutes;
}


const initiateIntlTelInput = ($, rtl_layout) => {
    if( jQuery('.phone_type').length ){
        jQuery('.phone_type').each( function(){
            if( jQuery(this).attr('af2-initiated') == 'true' ) return;
            jQuery(this).attr('af2-initiated', 'true');
            jQuery(this).intlTelInputSelect2({
                preferredCountries:[],
                geoIpLookup:true
              }, rtl_layout);      
        } );
    }
}
