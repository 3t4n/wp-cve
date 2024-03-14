<?php
add_action('admin_head', 'mwfc_admin_fonts');
function mwfc_admin_fonts()
{
  $options = get_option('dash_font_settings');
  ?>
    <style type="text/css">
        <?php if ($options && $options['dashmwfcfont']) { ?>
        body.rtl,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        #wpadminbar a,
        .rtl #wpadminbar,
        #wpadminbar,
        body {
            font-family: <?php esc_attr_e($options['dashmwfcfont']); ?> !important;
        }

        .rtl #wpadminbar * {
            font-family: <?php esc_attr_e($options['dashmwfcfont']); ?>;
        }

        <?php } ?>
    </style>
  <?php
}

add_action('admin_head', 'mwfc_admin_head');
function mwfc_admin_head()
{
  echo '<style type="text/css">
.errorwppafe {
    width: 88%;
    border: 1px #d3400d solid;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    padding: 10px 10px 10px 10px;
    text-align: center !important;
    display: block !important;
    float: none !important;
    margin-right: auto !important;
    margin-left: auto !important;
    background: yellow !important;
    margin-top: 15px !important;
}

pre,
code {
    font-family: VRCD, monospaced;
}

.okwppafe {
    width: 94%;
    border: 1px #a1cb45 solid;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    margin: 5px 10px 10px 10px;
    padding: 10px 10px 10px 10px;
    background: #eaf8cc;
    display: block;
    text-align: center;
    margin-left: auto;
    margin-right: auto;
    float: none;
}

.clear {
    clear: both
}

form {
    margin: 0px;
    padding: 0px;
}

input,
select {
    padding: 5px;
    font-size: 10pt;
    border: 1px solid #cacaca;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
}

.mwtitle {
    margin-top: 40px;
    font-size: 50px;
    text-align: center;
}

.mwtitle h2 {
    line-height: 1.5em;
}

.mwfc-responsive {
    display: block;
    max-width: 100%;
    height: auto;
    margin: 10px;
    border: 1px solid black;
}

#template textarea {
    direction: ltr;
    text-align: left;
    font-family: VRCD;
}

.mwfcsteps li {
    line-height: 1.5em;
}
.mwfc-pro-version-notice {
    border: 3px solid #f1c40f !important;
    background-color: #2c3e50 !important;
    color: #f1c40f !important;
    padding: 20px !important;
    margin: 20px 0 !important;
    position: relative !important;
}

.mwfc-pro-version-notice p {
    font-size: 16px !important;
    margin-top: 0 !important;
    margin-bottom: 1em !important;
}

.buy-mwfc,
.mwfc-pro-version-notice a {
    font-size: 16px !important;
    background-color: #f1c40f !important;
    color: #2c3e50 !important;
    text-decoration: none !important;
    padding: 15px !important;
    display: inline-block !important;
    box-sizing: border-box !important;
    -webkit-appearance: none !important;
    border-radius: 3px !important;
}

.mwfc-pro-version-notice .notice-dismiss {
    top: 20px !important;
    right: 20px !important;
    padding: 0 !important;
    background-color: transparent !important;
}

.rtl #mwfc-pro-version-notice .notice-dismiss {
    left: 20px !important;
    right: auto !important;
}

.mwfc-pro-version-notice .notice-dismiss:before {
    color: #f1c40f !important;
    font-size: 30px !important;
}
</style>';
}