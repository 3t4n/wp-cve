<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <!--[if gte mso 15]>
    <xml>
    <o:OfficeDocumentSettings>
        <o:AllowPNG/>
        <o:PixelsPerInch>96</o:PixelsPerInch>
    </o:OfficeDocumentSettings>
    </xml>
    <![endif]-->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" >
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body>
    <div class="fs_comment <?php echo is_rtl() ? 'rtl-align' : ''; ?>">
        <?php echo $email_body;  // Make sure to properly sanitize $email_body. ?>
    </div>

    <div style="color: #9e9e9e; margin: 10px 0 14px 0; padding-top: 10px;border-top: 1px solid #eeeeee;">
        <?php echo $email_footer;  // WPCS: XSS ok. ?>
    </div>
</body>
</html>
