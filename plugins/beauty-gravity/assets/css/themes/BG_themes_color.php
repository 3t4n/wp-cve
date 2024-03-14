<?php
$SIBG_free_css = "
/*region form-general*/


@media all and (-ms-high-contrast: none), (-ms-high-contrast: active) {
    form:not(.bg_default_theme) .BG_Button .gf_tooltip_body {
        transform: translateY(-50%);
        -webkit-transform: translateY(-50%);
        -moz-transform: translateY(-50%);
        -ms-transform: translateY(-50%);
        -o-transform: translateY(-50%);
    }


    form:not(.bg_default_theme) .gf_tooltip_body {
        z-index: 1;
    }
}



@font-face {
    font-family: 'bgicon';
    src: url('../../plugins/beauty-gravity/assets/font/bgicon/bgicon.eot?29850996');
    src: url('../../plugins/beauty-gravity/assets/font/bgicon/bgicon.eot?29850996#iefix') format('embedded-opentype'), url('../../plugins/beauty-gravity/assets/font/bgicon/bgicon.woff2?29850996') format('woff2'), url('../../plugins/beauty-gravity/assets/font/bgicon/bgicon.woff?29850996') format('woff'), url('../../plugins/beauty-gravity/assets/font/bgicon/bgicon.ttf?29850996') format('truetype'), url('../../plugins/beauty-gravity/assets/font/bgicon/bgicon.svg?29850996#bgicon') format('svg');
    font-weight: normal;
    font-style: normal;
}

.gf_progressbar_wrapper .gf_progressbar_title,
.gf_progressbar_wrapper .gf_progressbar_percentage > span {
    display: none !important;
}

.button:focus, button:focus, input[type='button']:focus, input[type='reset']:focus, input[type='submit']:focus {
    outline: none !important;
}

.icon-ok-3:before {
    content: '\\23';
}
/* '#' */
.icon-ok-4:before {
    content: '\\24';
}
/* '$' */
.icon-ok-1:before {
    content: '\\25';
}
/* '%' */
.icon-ok-5:before {
    content: '\\28';
}
/* '(' */
.icon-cancel-1:before {
    content: '\\29';
}
/* ')' */
.icon-right-open-1:before {
    content: '\\31';
}
/* '1' */
.icon-spin1:before {
    content: '\\32';
}
/* '2' */
.icon-spin4:before {
    content: '\\33';
}
/* '3' */
.icon-spin5:before {
    content: '\\34';
}
/* '4' */
.icon-spin6:before {
    content: '\\35';
}
/* '5' */
.icon-desktop:before {
    content: '\\36';
}
/* '6' */
.icon-laptop:before {
    content: '\\37';
}
/* '7' */
.icon-tablet:before {
    content: '\\38';
}
/* '8' */
.icon-mobile:before {
    content: '\\39';
}
/* '9' */
.icon-info-1:before {
    content: '\\41';
}
/* 'A' */
.icon-info-3:before {
    content: '\\43';
}
/* 'C' */
.icon-info:before {
    content: '\\44';
}
/* 'D' */
.icon-ok:before {
    content: '\\45';
}
/* 'E' */
.icon-ok-circled:before {
    content: '\\46';
}
/* 'G' */
.icon-asset-21:before {
    content: '\\48';
}
/* 'H' */
.icon-asset-2-1:before {
    content: '\\49';
}
/* 'I' */
.icon-cancel:before {
    content: '\\4a';
}
/* 'J' */
.icon-plus:before {
    content: '\\4b';
}
/* 'K' */
.icon-pencil:before {
    content: '\\4c';
}
/* 'L' */
.icon-upload:before {
    content: '\\4d';
}
/* 'M' */
.icon-download:before {
    content: '\\4e';
}
/* 'N' */
.icon-trash:before {
    content: '\\4f';
}
/* 'O' */
.icon-palette:before {
    content: '\\50';
}
/* 'P' */
.icon-cog:before {
    content: '\\51';
}
/* 'Q' */
.icon-down-open:before {
    content: '\\55';
}
/* 'U' */
.icon-left-open-1:before {
    content: '\\5a';
}
/* 'Z' */
.icon-help-circled:before {
    content: '\\61';
}
/* 'a' */
.icon-question-circle-o:before {
    content: '\\62';
}
/* 'b' */
.icon-help:before {
    content: '\\63';
}
/* 'c' */
.icon-help-circled-1:before {
    content: '\\64';
}
/* 'd' */
.icon-help-circled-2:before {
    content: '\\66';
}
/* 'f' */
.icon-help-circled-3:before {
    content: '\\67';
}
/* 'g' */
.icon-help-2:before {
    content: '\\68';
}
/* 'h' */
.icon-help-circled-alt:before {
    content: '\\69';
}
/* 'i' */
.icon-help-3:before {
    content: '\\6a';
}
/* 'j' */
.icon-question:before {
    content: '\\6b';
}
/* 'k' */
.icon-attention-alt:before {
    content: '\\6c';
}
/* 'l' */
.icon-attention:before {
    content: '\\6d';
}
/* 'm' */
.icon-attention-circled:before {
    content: '\\6e';
}
/* 'n' */
.icon-attention-1:before {
    content: '\\6f';
}
/* 'o' */
.icon-attention-filled:before {
    content: '\\70';
}
/* 'p' */
.icon-warning-empty:before {
    content: '\\71';
}
/* 'q' */
.icon-warning:before {
    content: '\\72';
}
/* 'r' */
.icon-attention-3:before {
    content: '\\73';
}
/* 's' */
.icon-attention-4:before {
    content: '\\74';
}
/* 't' */
.icon-attention-alt-1:before {
    content: '\\75';
}
/* 'u' */
.icon-attention-2:before {
    content: '\\76';
}
/* 'v' */
.icon-info-circled-2:before {
    content: '\\77';
}
/* 'w' */
.icon-info-2:before {
    content: '\\78';
}
/* 'x' */
.icon-info-circled-alt:before {
    content: '\\79';
}
/* 'y' */
.icon-info-circled-1:before {
    content: '\\7a';
}
/* 'z' */
.icon-back:before {
    content: '\\e802';
}
/* '' */
.icon-backmirrored:before {
    content: '\\e803';
}
/* '' */
.icon-backspaceqwertylg:before {
    content: '\\e804';
}
/* '' */
.icon-cancel-2:before {
    content: '\\e805';
}
/* '' */
.icon-checkmark:before {
    content: '\\e806';
}
/* '' */
.icon-chevrondown:before {
    content: '\\e807';
}
/* '' */
.icon-chevronup:before {
    content: '\\e808';
}
/* '' */
.icon-unknown:before {
    content: '\\e809';
}
/* '' */
.icon-unknownmirrored:before {
    content: '\\e80a';
}
/* '' */


form:not(.bg_default_theme) .gform_body ul > li.gfield {
    margin-top: 24px !important;
    position: relative
}

form:not(.bg_default_theme) .gform_body > ul > li > div {
    margin-top: 5px !important
}

form:not(.bg_default_theme) .gform_body ul > li .ginput_complex span {
    padding-top: 0 !important
}

.gform_wrapper label.gfield_label, .gform_wrapper legend.gfield_label{
    font-weight: 500 !important;
}

.gform_wrapper > form:not(.bg_default_theme) {
    color: inherit !important
}

.gform_wrapper > form:not(.bg_default_theme) .search-field{
    display:block;
    width:100% !important;
    margin:4px 0 !important;
    position:relative
}

.gform_wrapper > form:not(.bg_default_theme) .search-field .chosen-search-input{
    width:100% !important;
}


.gform_wrapper > form:not(.bg_default_theme):not(.BG_Material) input[type=text], .gform_wrapper > form:not(.bg_default_theme) .chosen-choices {
    background-color:transparent !important;
    background-image:none !important;
}

    .gform_wrapper > form:not(.bg_default_theme):not(.BG_Material) input[type=text], .gform_wrapper > form:not(.bg_default_theme) select,
    .gform_wrapper > form:not(.bg_default_theme):not(.BG_Material) input[type=text], .gform_wrapper > form:not(.bg_default_theme) .chosen-single,
    .gform_wrapper > form:not(.bg_default_theme):not(.BG_Material) input[type=text], .gform_wrapper > form:not(.bg_default_theme) .chosen-choices {
        // background: var(--input-background-color) !important;
        box-shadow:none !important;
        line-height: 1.25 !important
    }

    .gform_wrapper > form:not(.bg_default_theme).BG_Dark ul > li select > option {
        background: var(--option-background-color-dark) !important
    }

    .gform_wrapper > form:not(.bg_default_theme).BG_Light ul > li select > option {
        background: var(--option-background-color-light) !important
    }

.gform_wrapper form:not(.bg_default_theme) li input[type='checkbox']:checked ~ label, .gform_wrapper form:not(.bg_default_theme) li input[type='radio']:checked ~ label {
    font-weight: 400 !important
}


.gform_wrapper form:not(.bg_default_theme) li input[type='checkbox'] ~ label, .gform_wrapper form:not(.bg_default_theme) li input[type='radio'] ~ label {
    user-select: none !important;
}

.gform_wrapper form:not(.bg_default_theme) .BG_filecancel_icon{
    cursor: pointer;
}

.gform_wrapper form:not(.bg_default_theme):not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) .address_country {
    margin-top: 0 !important
}

form:not(.bg_default_theme) .BG_toggle li label, form:not(.bg_default_theme) .BG_toggle li label {
    display: flex !important;
    position: relative;
    justify-content: flex-end;
    flex-direction: row-reverse;
    align-items: center;
    max-width: 100% !important
}

form:not(.bg_default_theme) .BG_toggle {
    display: flex;
    flex-direction: column;
    align-items: flex-start
}

form:not(.bg_default_theme) .gform_fileupload_multifile{
    width:100%;
}

form:not(.bg_default_theme) .gform_fileupload_multifile .bg_button_container{
    display:inline-block;
    margin:5px auto !important
}

form:not(.bg_default_theme) .gform_page_footer {
    border-top: none !important
}

.SIBG-line-holder span{
    margin-bottom:0 !important;
}


form:not(.bg_default_theme) .ginput_container :not(.image-choices-choice), .gform_wrapper form:not(.bg_default_theme) .main_label {
    display: flex;
    align-items: center
}

form:not(.bg_default_theme) .image-choices-choice.BG_default input[type='radio'] ~ label{
    margin-left: 0 !important;
}

form:not(.bg_default_theme).BG_small_size,
form:not(.bg_default_theme).BG_small_size .chosen-results li,
form:not(.bg_default_theme).BG_small_size .chosen-single span,
form:not(.bg_default_theme).BG_small_size select option,
form:not(.bg_default_theme).BG_small_size .gfield_checkbox li label,
form:not(.bg_default_theme).BG_small_size .gfield_radio li label,
form:not(.bg_default_theme).BG_small_size input[type='text'],
form:not(.bg_default_theme).BG_small_size input[type='tel'],
form:not(.bg_default_theme).BG_small_size input[type='number'],
form:not(.bg_default_theme).BG_small_size input[type='email'] {
    font-size: 12px !important;
    font-weight: 400 !important
}


form:not(.bg_default_theme).BG_medium_size,
form:not(.bg_default_theme).BG_medium_size .chosen-results li,
form:not(.bg_default_theme).BG_medium_size .chosen-single span,
form:not(.bg_default_theme).BG_medium_size select option,
form:not(.bg_default_theme).BG_medium_size .gfield_checkbox li label,
form:not(.bg_default_theme).BG_medium_size .gfield_radio li label,
form:not(.bg_default_theme).BG_medium_size input[type='text'],
form:not(.bg_default_theme).BG_medium_size input[type='tel'],
form:not(.bg_default_theme).BG_medium_size input[type='number'],
form:not(.bg_default_theme).BG_medium_size input[type='email'] {
    font-size: 14px !important;
    font-weight: 400 !important;
    max-width: unset !important;
}

form:not(.bg_default_theme).BG_large_size,
form:not(.bg_default_theme).BG_large_size .chosen-results li,
form:not(.bg_default_theme).BG_large_size .chosen-single span,
form:not(.bg_default_theme).BG_large_size select option,
form:not(.bg_default_theme).BG_large_size .gfield_checkbox li label,
form:not(.bg_default_theme).BG_large_size .gfield_radio li label,
form:not(.bg_default_theme).BG_large_size input[type='text'],
form:not(.bg_default_theme).BG_large_size input[type='tel'],
form:not(.bg_default_theme).BG_large_size input[type='number'],
form:not(.bg_default_theme).BG_large_size input[type='email'] {
    font-size: 16px !important;
    max-width: unset !important;
    font-weight: 400 !important
}

form:not(.bg_default_theme).BG_xlarge_size,
form:not(.bg_default_theme).BG_xlarge_size .chosen-results li,
form:not(.bg_default_theme).BG_xlarge_size .chosen-single span,
form:not(.bg_default_theme).BG_xlarge_size .gfield_label,
form:not(.bg_default_theme).BG_xlarge_size select option,
form:not(.bg_default_theme).BG_xlarge_size .gfield_checkbox li label,
form:not(.bg_default_theme).BG_xlarge_size .gfield_radio li label,
form:not(.bg_default_theme).BG_xlarge_size input[type='text'],
form:not(.bg_default_theme).BG_xlarge_size input[type='tel'],
form:not(.bg_default_theme).BG_xlarge_size input[type='number'],
form:not(.bg_default_theme).BG_xlarge_size input[type='email'] {
    font-size: 18px !important;
    max-width: unset !important;
    font-weight: 400 !important
}


form:not(.bg_default_theme).BG_xlarge_size:not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) input,
form:not(.bg_default_theme).BG_xlarge_size:not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) .chosen-single,
form:not(.bg_default_theme).BG_xlarge_size:not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) select,
form:not(.bg_default_theme).BG_large_size:not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) input,form:not(.bg_default_theme).BG_large_size:not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) .chosen-single,
form:not(.bg_default_theme).BG_large_size:not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) select:not([multiple=multiple]) {
    height: 40px !important
}

form:not(.bg_default_theme).BG_medium_size:not(.BG_Material) input, form:not(.bg_default_theme).BG_medium_size:not(.BG_Material) select:not([multiple=multiple]),
form:not(.bg_default_theme).BG_medium_size:not(.BG_Material) input, form:not(.bg_default_theme).BG_medium_size:not(.BG_Material) .chosen-single{
    height: 35px !important
}

form:not(.bg_default_theme).BG_small_size:not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) input,
 form:not(.bg_default_theme).BG_small_size:not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) select:not([multiple=multiple]) ,
 form:not(.bg_default_theme).BG_small_size:not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) .chosen-single {
    height: 33px !important
}

.gform_wrapper form:not(.bg_default_theme).BG_large_size .BG_Button > li > label {
    margin: 0 !important;
    line-height: 1.25 !important
}

.gform_wrapper form:not(.bg_default_theme).BG_medium_size .BG_Button > li > label {
    margin: 0 !important;
    line-height: 1.2 !important
}

.gform_wrapper form:not(.bg_default_theme).BG_small_size .BG_Button > li > label {
    margin: 0 !important;
    line-height: 1.3 !important
}

form:not(.bg_default_theme).BG_medium_size .BG_Button .gf_tooltip_body {
    line-height: 1 !important;
}

.gform_wrapper form:not(.bg_default_theme) select[multiple=multiple] {
    overflow: hidden !important;
    padding-right: 3px !important;
    border: 1px solid #000
}

.gform_wrapper form:not(.bg_default_theme) .field_description_below .gfield_description {
    padding-top: 0 !important
}


form:not(.bg_default_theme) .gform_page_fields .ginput_container select:not([multiple=multiple]) {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    background: none !important;
    background-size: 40px !important;
    background-repeat: no-repeat !important
}

form:not(.bg_default_theme) li:not(.bg_multiple_upload) .ginput_container_fileupload {
    position: relative;
    display: flex
}

form:not(.bg_default_theme) .bg_multiple_upload .ginput_container_fileupload{
    display: block !important;
}

form:not(.bg_default_theme) .field_sublabel_hidden_label .main_label{
    display: none !important;
}

form:not(.bg_default_theme) .gfield.gf_right_half .ginput_complex>span{
    margin-top: 0 !important;
}

form:not(.bg_default_theme) div:not(.chosen-container)~.outline_container{
    display: none !important;
}

form:not(.bg_default_theme) .outline_container label{
    word-break: normal !important;
    word-wrap: unset !important;
}


.gform_wrapper .field_sublabel_hidden_label .ginput_complex.ginput_container input[type='text'], .gform_wrapper .field_sublabel_hidden_label .ginput_complex.ginput_container select{
    margin-bottom: 0 !important;
}

form:not(.bg_default_theme) .BG_fileupload {
    height: 36px;
    border: 1px solid #8a8886;
    display: flex !important;
    align-items: center;
    position: relative;
    cursor: pointer;
    padding: 0 8px;
    overflow: hidden;
    background: #ffffff36
}

form:not(.bg_default_theme) li:not(.bg_multiple_upload) .ginput_container_post_image,
form:not(.bg_default_theme) li:not(.bg_multiple_upload) .ginput_container_fileupload{
    display: flex;
    align-items: center;
}

form:not(.bg_default_theme) .BG_fileupload_icon {
    width: 18px;
    height: 18px;
    display: flex;
    margin-right: 8px;
    align-items: center;
    font-size: 20px;
    font-style: normal
}

form:not(.bg_default_theme) i.BG_fileupload_icon:before {
    content: '\\4d';
    font-family: bgicon;
    position: relative;
}

form:not(.bg_default_theme) .BG_fileupload_icon_selected {
    position: relative;
    margin-right: 20px
}

form:not(.bg_default_theme) .BG_fileupload_icon_selected:before {
    content: '\\24';
    font-family: bgicon;
    top: 50%;
    font-style: normal;
    position: absolute;
    transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -o-transform: translateY(-50%)
}

form:not(.bg_default_theme) .BG_filecancel_icon:before {
    content: '\\29';
    font-family: bgicon;
    font-style: normal;
    margin-left: 8px;
}

form:not(.bg_default_theme) .BG_fileupload_text {
    display: inline-block;
    text-overflow: ellipsis;
    white-space: nowrap;
    max-width: 90%;
    overflow: hidden
}

form:not(.bg_default_theme) .ginput_preview {
    display: none
}

form:not(.bg_default_theme) .ginput_container_fileupload .gform_drop_area .ginput_preview {
    display: block !important
}

// form:not(.bg_default_theme) input[type='file'] {
//     display: none
// }

form:not(.bg_default_theme) .gform_body button, form:not(.bg_default_theme) .gform_body textarea, form:not(.bg_default_theme) .gform_body input[type=submit], form:not(.bg_default_theme) .gform_body input[type=button] {
    outline: none !important;
    font-family: inherit !important
}

form:not(.bg_default_theme) h3 {
    font-family: inherit !important
}

form:not(.bg_default_theme) .gfield_visibility_hidden{
    display: none !important;
}

form:not(.bg_default_theme) .validation_message {
    color: red !important
}

.gform_wrapper form:not(.bg_default_theme) li.gfield.gfield_error.gfield_contains_required label.gfield_label {
    margin-top: 0 !important
}

.gform_wrapper.gform_validation_error form:not(.bg_default_theme) .gform_body ul li.gfield.gfield_error {
    border: none !important;
    background: none !important
}

.gform_wrapper form:not(.bg_default_theme) .gform_body ul li.gfield.gfield_error:not(.gf_left_half):not(.gf_right_half) input, .gform_wrapper .gform_body ul li.gfield.gfield_error:not(.gf_left_half):not(.gf_right_half) label {
    color: red !important;
    border-color: red !important;
}

form:not(.bg_default_theme).BG_xlarge_size .BG_fileupload_text {
    margin-top: 0 !important
}

/* form:not(.bg_default_theme).BG_large_size .BG_fileupload_text {
    margin-top: 2px !important
} */

#gform_wrapper_[form_id] form:not(.bg_default_theme):not(.BG_Material) .gform_body ul li.gfield.gfield_error:not(.gf_left_half):not(.gf_right_half) .ginput_container select,
#gform_wrapper_[form_id] form:not(.bg_default_theme):not(.BG_Material) .gform_body ul li.gfield.gfield_error .ginput_container .chosen-single {
    color: red !important;
    border: 1px solid red !important
}

form:not(.bg_default_theme) input:-webkit-autofill, form:not(.bg_default_theme) input:-webkit-autofill:hover, form:not(.bg_default_theme) input:-webkit-autofill:focus, form:not(.bg_default_theme) input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px #fff inset !important
}

form:not(.bg_default_theme) .ginput_container .chosen-single{
    box-shadow:unset !important;
    display: flex;
    justify-content: center;
    flex-direction: column;
    outline: unset !important;
}

form:not(.bg_default_theme) .ginput_container .chosen-single>div{
    display:none !important;
}

form:not(.bg_default_theme).BG_Dark input:-webkit-autofill, form:not(.bg_default_theme).BG_Dark input:-webkit-autofill:hover, form:not(.bg_default_theme).BG_Dark input:-webkit-autofill:focus, form:not(.bg_default_theme).BG_Dark input:-webkit-autofill:active {
    -webkit-box-shadow: 0 0 0 30px #363636 inset
}

.gform_wrapper form:not(.bg_default_theme) div.validation_error {
    display: none !important
}

form:not(.bg_default_theme) .gfield .bg_error_message {
    color: red !important;
    position: relative !important
}

form:not(.bg_default_theme) .ginput_container_fileupload .bg_error_message {
    color: red !important;
    position: absolute !important;
    top: 100%
}

form:not(.bg_default_theme) .gform_page_footer {
    display: inline-block;
    width:100% !important
}

    form:not(.bg_default_theme) .gform_page_footer .button:not(.gform_previous_button),
    form:not(.bg_default_theme) .gform_footer .button:not(.gform_previous_button) {
        float: right !important
    }

form:not(.bg_default_theme) .ginput_container .datepicker.medium {
    width: calc(50% - 8px) !important;
}

form:not(.bg_default_theme) .button {
   height: 36px !important;
}

@media only screen and (max-width: 641px) {
    form:not(.bg_default_theme) .gform_page_footer .bg_footer_container{
        display: block !important;
    }
}

form:not(.bg_default_theme) .bg_footer_container {
    display: flex !important;
    padding-right: 16px;
    justify-content: flex-end;
    align-items: center;
 }

@keyframes bg_spin {
    0% {
        transform: rotate(0deg);
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
    }

    100% {
        transform: rotate(359deg);
        -webkit-transform: rotate(359deg);
        -moz-transform: rotate(359deg);
        -ms-transform: rotate(359deg);
        -o-transform: rotate(359deg);
    }
}

@-webkit-keyframes bg_spin {
    0% {
        transform: rotate(0deg);
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
    }
}

form .gform_page_footer .bg-spin {
    font-family: bgicon;
    font-style: normal;
    display: inline-block;
    animation: bg_spin 3s linear infinite;
    -webkit-animation: bg_spin 3s linear infinite;
    margin-right: 16px;
    position: relative;
}

form:not(.bg_default_theme) .gf_tooltip_body {
    margin: 0 0 0 5px !important;
}

form:not(.bg_default_theme) .BG_Button .gf_tooltip_body {
 position: absolute !important;
}

form:not(.bg_default_theme) .BG_check {
    margin-bottom: 0 !important
}


@media only screen and (max-width: 641px) {


    form:not(.bg_default_theme) .ginput_container .BG_check {
        margin-bottom: 0 !important;
    }
}


.gform_wrapper form:not(.bg_default_theme) .BG_Button > li {
    margin-right: 5px;
}



form:not(.bg_default_theme) .bg_field_icon:not(.BG_Icon):not(.BG_Button) i:not(.dashicons) {
    width: 20px;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    left: 10px;
    transition: all .1s linear;
    -webkit-transition: all .1s linear;
    -moz-transition: all .1s linear;
    -ms-transition: all .1s linear;
    -o-transition: all .1s linear;
}


form:not(.bg_default_theme) .ui-datepicker-trigger {
    display: none !important;
}


form:not(.bg_default_theme) .bg_field_icon.BG_Icon i:not(.dashicons) {
    position: relative;
    left: 0;
    margin-right: 8px;
}

form:not(.bg_default_theme) .bg_field_icon {
    position: relative;
}

    form:not(.bg_default_theme) .bg_field_icon input,
    form:not(.bg_default_theme) .bg_field_icon select,
    form:not(.bg_default_theme) .bg_field_icon .chosen-single {
        padding-left: 32px !important;
        transition: all .1s linear;
        -webkit-transition: all .1s linear;
        -moz-transition: all .1s linear;
        -ms-transition: all .1s linear;
        -o-transition: all .1s linear;
    }

form:not(.bg_default_theme):not(.BG_Material):not(.BG_Material_out):not(.BG_Material_out_rnd) .bg_field_icon input:focus {
    padding-left: 8px !important;
    transition: all .1s linear;
    -webkit-transition: all .1s linear;
    -moz-transition: all .1s linear;
    -ms-transition: all .1s linear;
    -o-transition: all .1s linear;
}

form:not(.bg_default_theme) .bg_field_icon input ~ i:before {
    transition: all 0.1s linear;
    -webkit-transition: all 0.1s linear;
    -moz-transition: all 0.1s linear;
    -ms-transition: all 0.1s linear;
    -o-transition: all 0.1s linear;
}

form:not(.bg_default_theme):not(.BG_Material) .bg_field_icon input:focus ~ i:before {
    transform: translateX(-100%);
    transition: all 0.1s linear;
    -webkit-transition: all 0.1s linear;
    -moz-transition: all 0.1s linear;
    -ms-transition: all 0.1s linear;
    -o-transition: all 0.1s linear;
    -webkit-transform: translateX(-100%);
    -moz-transform: translateX(-100%);
    -ms-transform: translateX(-100%);
    -o-transform: translateX(-100%);
}

form:not(.bg_default_theme) .bg_field_icon input ~ i {
    overflow: hidden;
    display: flex;
}

form .gform_body .gform_fields .gfield:first-child{
    margin-top: 0 !important;
}

form:not(.bg_default_theme) .bg_prev_button_container {
    position: relative;
    display: inline-block;
    overflow: hidden;
    margin: 5px;
}

form:not(.bg_default_theme) .ginput_container_date.ginput_container select:not([multiple=multiple]){
    background-position:calc(100% - 6px),15px !important;
    padding-right:10px !important;
}

form:not(.bg_default_theme) .bg_button_container {
    position: relative;
    display: inline-block;
    display: flex;
    justify-content: flex-end;
    overflow: hidden;
    margin:5px 5px 5px 16px
}

form .hidden_label input{
    margin-top: 0 !important;
}

form:not(.bg_default_theme) .hidden_label .main_label {
    display:none !important;
}

form:not(.bg_default_theme) .gfield_radio li,
form:not(.bg_default_theme) .gfield_checkbox li{
    overflow: visible;
}

form:not(.bg_default_theme) .gfield_radio li label,
form:not(.bg_default_theme) .gfield_checkbox li label{
    width:100%;
}

form:not(.bg_default_theme) .bg_button_container img {
    display: none !important;
}

@media only screen and (min-width: 641px){
    .gform_wrapper .BG_sharp .ginput_complex .ginput_right{
        margin: 0 !important;
    }

    .gform_wrapper .ginput_complex .ginput_right{
        margin: 16px 3px 0 0 !important;
    }
}

form:not(.bg_default_theme) .button {
    margin: 0 !important;
}

form.BG_Material_out input,
form.BG_Material input,
form.BG_Material_out_rnd input,
form.BG_Material_out select,
form.BG_Material select,
form.BG_Material_out_rnd select{
    box-shadow: unset !important;
}

form:not(.bg_default_theme):not(.BG_Material_out):not(.BG_Material):not(.BG_Material_out_rnd) .button {
    margin: 0 0 0 16px !important;
    line-height:1.1
}

form:not(.bg_default_theme) .gfield_date_year{
    max-width: unset !important;
}

/*endregion*/
/*region BG_Microsoft*/
/*region general*/
.BG_Microsoft select {
    margin-top: 0 !important
}

.BG_Microsoft .name_prefix_select > label {
    margin-top: -2px !important
}

.BG_Microsoft input,
.BG_Microsoft select:not([multiple=multiple]),
.BG_Microsoft .chosen-single{
    padding: 6px 6px 6px 6px !important
}

.BG_Microsoft .gfield_date_year input{
    padding: 6px 4px 6px 4px !important
}

.BG_Microsoft .gfield_time_hour i{
    position: relative;
    top: -50%;
    padding-left: 5px;
}

    .BG_Microsoft  ul.gfield_checkbox li input[type='radio'], .BG_Microsoft  ul.gfield_checkbox li input[type='checkbox'] {
        display: inline-block !important;
        width: 0 !important;
        height: 0 !important;
    }

.BG_Microsoft .BG_fileupload {
    border-radius: 2px;
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    -ms-border-radius: 2px;
    -o-border-radius: 2px
}

.BG_Microsoft li:not(.image-choices-use-images) .BG_default input[type='radio'] ~ label, .BG_Microsoft li:not(.image-choices-use-images) .BG_default input[type='checkbox'] ~ label {
    position: relative;
    margin-left: 28px !important
}

.BG_Microsoft .BG_default input[type='checkbox']:hover ~ label > .BG_check:after {
    display: inline-block;
    color: #605e5c
}

.BG_Microsoft .BG_default input[type='checkbox']:checked ~ label > .BG_check:after {
    border-color: #fff;
    display: inline-block
}

.BG_Microsoft .BG_default input[type='checkbox']:checked:hover ~ label > .BG_check:after {
    color: white !important;
}

.BG_Microsoft .BG_default input[type='radio']:hover ~ label .BG_check:after {
    display: inline-block
}

.BG_Microsoft .BG_default input[type='checkbox'] ~ label > .BG_check:after {
    content: '\\e806';
    font-family: bgicon;
    position: absolute;
    right: calc(100% + 10px);
    color: white;
    display: none;
    top: 50%;
    transform: translateY(-50%);
    transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -webkit-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -moz-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -ms-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -o-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -webkit-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -o-transform: translateY(-50%);
}

.BG_Microsoft .BG_default input[type='checkbox'] ~ label > .BG_check:before,
.BG_Microsoft .BG_default input[type='checkbox'] ~ label > .BG_check:before {
    content: '';
    width: 20px;
    height: 20px;
    border: 1px solid #323130;
    display: inline-block;
    position: absolute;
    right: calc(100% + 7px);
    top: 50%;
    border-radius: 2px;
    transform: translateY(-50%);
    transition: all .2s linear;
    -webkit-transition: all .2s linear;
    -moz-transition: all .2s linear;
    -ms-transition: all .2s linear;
    -o-transition: all .2s linear;
    -webkit-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -o-transform: translateY(-50%);
    -webkit-border-radius: 2px;
    -moz-border-radius: 2px;
    -ms-border-radius: 2px;
    -o-border-radius: 2px;
}



.BG_Microsoft .BG_default input[type='radio'] ~ label .BG_check {
    width: 20px;
    height: 20px;
    border: 1px solid #323130;
    display: inline-block;
    position: absolute;
    right: calc(100% + 7px);
    top: 50%;
    border-radius: 50%;
    transform: translateY(-50%);
    transition: all .2s linear;
    -webkit-transition: all .2s linear;
    -moz-transition: all .2s linear;
    -ms-transition: all .2s linear;
    -o-transition: all .2s linear;
    -webkit-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -o-transform: translateY(-50%);
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
}

.BG_Microsoft.BG_Dark .BG_default input[type='radio'] ~ label .BG_check,
.BG_Microsoft.BG_Dark .BG_default input[type='checkbox'] ~ label > .BG_check:before {
    border-width: 1px !important;
    border-color: rgba(255, 255, 255, 0.45)
}

.BG_Microsoft .BG_default input[type='radio'] ~ label .BG_check:after {
    content: '';
    width: 10px;
    height: 10px;
    border-radius: 50%;
    position: absolute;
    background: #605e5c;
    left: 50%;
    display: none;
    top: 50%;
    transform: translate(-50%,-50%);
    transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -webkit-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -moz-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -ms-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -o-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -webkit-transform: translate(-50%,-50%);
    -moz-transform: translate(-50%,-50%);
    -ms-transform: translate(-50%,-50%);
    -o-transform: translate(-50%,-50%);
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
}

.BG_Microsoft .BG_toggle input[type='checkbox']:hover ~ label > .BG_check:after, .BG_Microsoft .BG_toggle input[type='radio']:hover ~ label > .BG_check:after {
    display: inline-block;
    border-color: #605e5c
}

.BG_Microsoft .BG_toggle input[type='radio'], .BG_Microsoft .BG_toggle input[type='radio'] ~ label {
    margin-left: 0 !important
}

    .BG_Microsoft .BG_toggle input[type='checkbox']:checked ~ label > .BG_check:after, .BG_Microsoft .BG_toggle input[type='radio']:checked ~ label > .BG_check:after {
        background: #fff;
        left: 22px;
        transition: all .2s cubic-bezier(0.4,0,0.23,1)
    }

    .BG_Microsoft .BG_toggle input[type='checkbox']:checked:hover ~ label > .BG_check:after, .BG_Microsoft .BG_toggle input[type='radio']:checked:hover ~ label > .BG_check:after {
        background: #fff
    }

    .BG_Microsoft .BG_toggle input[type='checkbox']:hover ~ label > .BG_check, .BG_Microsoft .BG_toggle input[type='radio']:hover ~ label > .BG_check {
        border-color: #201f1e
    }

        .BG_Microsoft .BG_toggle input[type='checkbox']:hover ~ label > .BG_check:after, .BG_Microsoft .BG_toggle input[type='radio']:hover ~ label > .BG_check:after {
            background: #201f1e
        }

.BG_Microsoft.BG_Dark .BG_toggle input[type='checkbox']:hover ~ label > .BG_check, .BG_Microsoft.BG_Dark .BG_toggle input[type='radio']:hover ~ label > .BG_check {
    border-color: #ffffffb0
}

    .BG_Microsoft.BG_Dark .BG_toggle input[type='checkbox']:hover ~ label > .BG_check:after, .BG_Microsoft.BG_Dark .BG_toggle input[type='radio']:hover ~ label > .BG_check:after {
        background: #ffffffb0
    }

.BG_Microsoft .BG_toggle input[type='checkbox'] ~ label > .BG_check:after, .BG_Microsoft .BG_toggle input[type='radio'] ~ label > .BG_check:after {
    content: '';
    width: 12px;
    height: 12px;
    position: absolute;
    border-radius: 50%;
    background-color: #605e5c;
    left: 4px;
    right: calc(100% + 10px);
    top: 50%;
    transform: translateY(-50%);
    transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -webkit-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -moz-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -ms-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -o-transition: all .1s cubic-bezier(0.4,0,0.23,1);
    -webkit-border-radius: 50%;
    -moz-border-radius: 50%;
    -ms-border-radius: 50%;
    -o-border-radius: 50%;
    -webkit-transform: translateY(-50%);
    -moz-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    -o-transform: translateY(-50%)
}

.BG_Microsoft .BG_toggle input[type='checkbox'] ~ label > .BG_check, .BG_Microsoft .BG_toggle input[type='radio'] ~ label > .BG_check {
    width: 40px;
    height: 20px;
    border-radius: 10px;
    border: 1px solid #605e5c;
    float: left;
    position: relative;
    border-radius: 10px;
    margin-right: 8px;
    transition: all .2s linear;
    -webkit-transition: all .2s linear;
    -moz-transition: all .2s linear;
    -ms-transition: all .2s linear;
    -o-transition: all .2s linear;
    -webkit-border-radius: 10px;
    -moz-border-radius: 10px;
    -ms-border-radius: 10px;
    -o-border-radius: 10px
}

.BG_Microsoft .BG_Button {
    display: flex;
    flex-direction: row !important;
    flex-wrap: wrap
}

.gform_wrapper .BG_Microsoft .BG_Button > li {
    margin-right: 5px !important;
}

    .gform_wrapper .BG_Microsoft .BG_Button > li > label {
        display: flex;
        align-items: center;
        text-align: center;
        padding: 8px;
        border: 1px solid #605e5c;
        max-width: 100% !important;
        border-radius: 2px;
        -webkit-border-radius: 2px;
        -moz-border-radius: 2px;
        -ms-border-radius: 2px;
        -o-border-radius: 2px
    }

.BG_Microsoft .BG_Button :not(.image-choices-choice) label:hover ~ .gf_tooltip_body > i {
    color: #fff !important;
    transition: none;
    -webkit-transition: none;
    -moz-transition: none;
    -ms-transition: none;
    -o-transition: none
}

.BG_Microsoft .BG_Button input[type='checkbox']:checked ~ .gf_tooltip_body > i, .BG_Microsoft .BG_Button input[type='radio']:checked ~ .gf_tooltip_body > i {
    color: #fff !important;
    transition: none;
    -webkit-transition: none;
    -moz-transition: none;
    -ms-transition: none;
    -o-transition: none
}

@media only screen and (max-width: 641px) {
    .BG_Microsoft .BG_Button .gf_tooltip_body, .BG_Microsoft .BG_Button .gf_tooltip_body > span, .BG_Microsoft .BG_Button li > label > .BG_check {
        display: inline-block !important
    }

    .BG_Microsoft li > label {
        width: unset !important
    }
}

.BG_Microsoft input[type=button].gform_previous_button {
    border: 1px solid #8a8886 !important;
    background: #fff !important;
    color: #323130 !important;
    font-weight: 600 !important
}

    .BG_Microsoft input[type=button].gform_previous_button:hover {
        background: #f3f2f1 !important
    }

.BG_Microsoft .gf_progressbar {
    padding: 0 !important
}

    .BG_Microsoft .gf_progressbar:after {
        height: 0 !important
    }

.BG_Microsoft .gf_progressbar_percentage span {
    color: #8a8886 !important;
    text-shadow: none !important
}



.BG_Microsoft .BG_filecancel_icon:before {
    content: '\\e805' !important;
}

.BG_Microsoft .BG_fileupload_icon_selected:before {
    content: '\\e806' !important;
}

.BG_Microsoft .BG_Button .gf_tooltip_body {
    position: absolute !important;
}




/*endregion*/
/*region color*/
#gform_[form_id].BG_Microsoft input:focus, #gform_[form_id].BG_Microsoft textarea:focus, #gform_[form_id].BG_Microsoft select:not([multiple=multiple]):focus ,
#gform_[form_id].BG_Microsoft .chosen-with-drop .chosen-single,
#gform_[form_id].BG_Microsoft .chosen-with-drop .chosen-choices {
    outline: none !important;
    box-shadow:none !important;
    border: 2px solid var(--microsoft-color-primary) !important
}

#gform_[form_id].BG_Microsoft select[multiple=multiple]:focus {
    outline: none !important;
    border: 1px solid var(--microsoft-color-primary) !important
}


#gform_[form_id].BG_Microsoft .search-choice{
    background-color:var(--microsoft-color-primary) !important;
    background-image:none !important;
    box-shadow:none !important;
    border:none !important;
    border-radius: 2px !important;
    color: inherit !important;
}

#gform_[form_id].BG_Microsoft .search-choice span{
    color: inherit !important;
}

#gform_[form_id].BG_Microsoft input, #gform_[form_id].BG_Microsoft textarea,
 #gform_[form_id].BG_Microsoft select ,
 #gform_[form_id].BG_Microsoft .chosen-single,
 #gform_[form_id].BG_Microsoft .chosen-choices {
    border: 1px solid #8a8886 !important;
    border-radius: 2px !important;
    box-shadow:none !important;
    color: inherit !important;
    -webkit-border-radius: 2px !important;
    -moz-border-radius: 2px !important;
    -ms-border-radius: 2px !important;
    -o-border-radius: 2px !important;
}

#gform_[form_id].BG_Microsoft .BG_default input[type='radio']:checked ~ label .BG_check:after {
    display: inline-block;
    box-sizing: border-box;
    background: #fff;
    border: 5px solid var(--microsoft-color-primary);
    transition: .2s all cubic-bezier(0.4,0,0.23,1);
    -webkit-transition: .2s all cubic-bezier(0.4,0,0.23,1);
    -moz-transition: .2s all cubic-bezier(0.4,0,0.23,1);
    -ms-transition: .2s all cubic-bezier(0.4,0,0.23,1);
    -o-transition: .2s all cubic-bezier(0.4,0,0.23,1)
}

#gform_[form_id].BG_Microsoft .BG_default input[type='radio']:checked ~ label .BG_check {
    border-color: var(--microsoft-color-primary) !important
}

#gform_[form_id].BG_Microsoft  input[type='checkbox']:checked ~ label > .BG_check:before {
    background: var(--microsoft-color-primary);
    border-color: var(--microsoft-color-primary);
    transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -webkit-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -moz-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -ms-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -o-transition: all .2s cubic-bezier(0.4,0,0.23,1)
}

#gform_[form_id].BG_Microsoft  input[type='checkbox']:checked:hover ~ label > .BG_check:before {
    background: var(--microsoft-color-primary-dark);
    border-color: var(--microsoft-color-primary-dark);
    transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -webkit-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -moz-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -ms-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -o-transition: all .2s cubic-bezier(0.4,0,0.23,1)
}

#gform_[form_id].BG_Microsoft .BG_default input[type='radio']:checked:hover ~ label .BG_check:after {
    border-color: var(--microsoft-color-primary-dark);
    transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -webkit-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -moz-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -ms-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -o-transition: all .2s cubic-bezier(0.4,0,0.23,1)
}

#gform_[form_id].BG_Microsoft .BG_default input[type='radio']:checked:hover ~ label .BG_check {
    border-color: var(--microsoft-color-primary-dark) !important;
    transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -webkit-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -moz-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -ms-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -o-transition: all .2s cubic-bezier(0.4,0,0.23,1)
}

#gform_[form_id].BG_Microsoft .BG_default input[type='radio']:checked ~ label > .BG_check:before {
    border-color: var(--microsoft-color-primary);
    transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -webkit-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -moz-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -ms-transition: all .2s cubic-bezier(0.4,0,0.23,1);
    -o-transition: all .2s cubic-bezier(0.4,0,0.23,1)
}

#gform_[form_id].BG_Microsoft .BG_toggle input[type='checkbox']:checked ~ label > .BG_check, #gform_[form_id].BG_Microsoft .BG_toggle input[type='radio']:checked ~ label > .BG_check {
    background: var(--microsoft-color-primary);
    border-color: var(--microsoft-color-primary)
}

#gform_[form_id]{
    color: var(--form-main-color) !important
}

#gform_[form_id].BG_Microsoft .BG_toggle input[type='checkbox']:checked:hover ~ label > .BG_check, #gform_[form_id].BG_Microsoft .BG_toggle input[type='radio']:checked:hover ~ label > .BG_check {
    background: var(--microsoft-color-primary-dark);
    border-color: var(--microsoft-color-primary-dark) !important
}

#gform_[form_id].BG_Microsoft .BG_Button :not(.image-choices-choice) label:hover {
    background: var(--microsoft-color-primary) !important;
    color: #fff !important;
    border-color: var(--microsoft-color-primary) !important;
}

#gform_[form_id].BG_Microsoft .BG_Button :not(.image-choices-choice) input[type='checkbox']:checked ~ label, #gform_[form_id].BG_Microsoft .BG_Button :not(.image-choices-choice) input[type='radio']:checked ~ label {
    background: var(--microsoft-color-primary) !important;
    color: #fff !important;
    border-color: var(--microsoft-color-primary) !important
}

#gform_[form_id].BG_Microsoft .ginput_container select:not([multiple=multiple]),
#gform_[form_id].BG_Microsoft .ginput_container .chosen-single,
#gform_[form_id].BG_Microsoft .chosen-choices {
    background-color: var(--input-background-color) !important;
}

#gform_[form_id].BG_Microsoft .ginput_container.ginput_container_date select:not([multiple=multiple]){
    background-position: calc(100% - 6px),15px !important;
    padding-right: 20px !important;
}

/* #gform_[form_id].BG_Microsoft .ginput_container.bg_field_icon  select:not([multiple=multiple]) {
    padding-left: 40px !important;
} */


#gform_[form_id].BG_Microsoft button, #gform_[form_id].BG_Microsoft input[type=submit], #gform_[form_id].BG_Microsoft input[type=button] {
    padding: 8px 20px !important;
    border: none !important;
    line-height: 1;
    color: #fff !important;
    background-color: var(--microsoft-color-primary) !important;
    transition: all 0.2s linear;
    border-radius: 2px !important;
    font-weight: 600 !important;
    -webkit-transition: all 0.2s linear;
    -moz-transition: all 0.2s linear;
    -ms-transition: all 0.2s linear;
    -o-transition: all 0.2s linear;
    -webkit-border-radius: 2px !important;
    -moz-border-radius: 2px !important;
    -ms-border-radius: 2px !important;
    -o-border-radius: 2px !important;
}

    #gform_[form_id].BG_Microsoft button:not(.bg_disabled):hover, #gform_[form_id].BG_Microsoft input[type=submit]:not(.bg_disabled):hover, #gform_[form_id].BG_Microsoft input[type=button]:not(.bg_disabled):hover {
        background-color: var(--microsoft-color-primary-dark) !important
    }

#gform_[form_id].BG_Microsoft .gf_progressbar_percentage {
    height: 2px !important;
    background: var(--microsoft-color-primary) !important
}

#gform_wrapper_[form_id] form .gform_body ul li.gfield.gfield_error:not(.gf_left_half):not(.gf_right_half) .ginput_container select {
    color: var(--error-color) !important;
    border: 1px solid var(--error-color) !important;
}

form#gform_[form_id] .gform_page_footer input[type='button'].bg_disabled,
form#gform_[form_id] .gform_page_footer input[type='submit'].bg_disabled {
    color: #a2a2a2 !important;
    background-color: #9494945e !important;
    border-color: #9494945e !important;
}


#gform_[form_id].BG_Microsoft input[type=button].BG_prev_ux:not(.bg_disabled) {
    color: var(--microsoft-color-primary) !important;
    background: #ffffff !important;
    border: 1px solid var(--microsoft-color-primary) !important;
}

#gform_[form_id].BG_Microsoft input[type=button].BG_prev_ux:hover {
    color: white !important;
    background: var(--microsoft-color-primary) !important;
}




#gform_[form_id].BG_Microsoft .ginput_container select:not([multiple=multiple]),
#gform_[form_id].BG_Microsoft .ginput_container .chosen-single {
    background: none !important;
    background-color: var(--input-background-color) !important;
    background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' height='6' width='10'%3E%3Cpolygon points='0,0 10,0 5,6' style='fill:%23888888' /%3Einline SVG.%0A%3C/svg%3E\") !important;
    background-position: 10px,15px !important;
    background-size: 10px !important;
    background-repeat: no-repeat !important;
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    background-position: calc(100% - 20px),15px !important;
}

#gform_[form_id].BG_Microsoft .gfield_error input {
    border-color: red !important;
}


#gform_[form_id].BG_Microsoft .ginput_container input[type=text],
#gform_[form_id].BG_Microsoft .ginput_container textarea {
    background-color: var(--input-background-color) !important;
}

#gform_[form_id].BG_Microsoft.BG_Dark .ginput_container select option,
#gform_[form_id].BG_Microsoft.BG_Dark .ginput_container .chosen-drop {
    background-color: #363636 !important;
    color:white;
}

#gform_[form_id].BG_Microsoft.BG_Dark .ginput_container .chosen-drop li {
    color:white;
}

#gform_[form_id].BG_Microsoft.BG_Dark .ginput_container select:not([multiple=multiple]) {
    background-image: url('data:image/svg+xml;charset=UTF-8,<svg xmlns='http://www.w3.org/2000/svg' width='500' height='100' viewBox='0 0 4442 720'><path fill='var(--microsoft-border-color)' d='M955 102l-455 454-455-454-43 43 498 499 499-499z' ></path></svg>') !important;
}


/*endregion*/
/*endregion*/ 
";