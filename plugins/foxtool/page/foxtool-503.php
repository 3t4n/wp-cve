<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="<?php _e('We apologize, the website is currently undergoing maintenance', 'foxtool'); ?>">
    <title>503 - <?php _e('Maintenance', 'foxtool'); ?></title>
    <style>
		@import url('https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;1,100;1,200;1,300;1,400&display=swap');
        body {
            font-family: 'Kanit', sans-serif;
            color: #333;
			text-align: center;
        }
        .page_503 {
			background: #fff;
			height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
			flex-direction: column;
			padding: 20px;
		}
		.container {
			max-width: 700px;
			width: 100%;
		}
        .four_zero_four_bg {
            background-image: url('<?php echo esc_url(FOXTOOL_URL . 'img/503.gif'); ?>');
            height: 350px;
            background-position: center;
        }
        .four_zero_four_bg h1 {
            font-size: 80px;
			margin-top:0px;
			color:#95684a;
        }
        .contant_box_503 p{
            font-size: 25px;
        }
        .link_503 {
            color: #fff;
            background: #39ac31;
            padding: 10px 20px;
            margin: 20px 0;
            display: inline-block;
            text-decoration: none;
        }
        .link_503:hover {
            color: #fff;
            background: #333;
        }
    </style>
</head>
<body>
    <section class="page_503">
        <div class="container">
            <div class="row">    
                <div class="col-sm-12">
                    <div class="col-sm-10 col-sm-offset-1 text-center">
                        <div class="four_zero_four_bg">
                            <h1 class="text-center">503</h1>
                        </div>
                        <div class="contant_box_503">
                            <h2 class="h2"><?php _e('MAINTENANCE MODE', 'foxtool'); ?></h2>
                            <p><?php _e('We apologize, the website is currently undergoing maintenance. Please wait for a moment', 'foxtool'); ?>!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
