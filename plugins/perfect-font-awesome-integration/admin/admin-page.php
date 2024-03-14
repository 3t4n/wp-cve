<?php
if(!defined('PFAI_ENGINE')) {
   die('404');
}

function pfai_admin_page() {
	add_menu_page(
			__( 'PFAI', 'perfect-font-awesome-integration' ),
			__( 'PFAI', 'perfect-font-awesome-integration' ),
			'manage_options',
			'perfect-font-awesome-integration',
			'pfai_admin_page_contents',
			plugin_dir_url( __FILE__ ) . '../images/favicon.png',
			20
	);
}
add_action( 'admin_menu', 'pfai_admin_page' );


function pfai_admin_page_contents() {
?>
<h1 style="text-align:center;margin-top:2%;">
<a href="https://wporbit.net" target="_blank"><img src="<?php echo plugins_url( '../images/favicon.png' , __FILE__ ); ?>" style="width: 80px;margin: 0 auto 2%;"></a>
<br>Perfect Font Awesome Integration by WPOrbit</h1>

<div class="pfai_admin_main_container">
<div class="pfai_admin_col_1">
    <h2 style="color:#080;margin-top:0;">Thank you so much for using our plugin.</h2>
    <p>Hello, I am Kaushik Somaiya, I run a small WordPress Support & Maintenance Services company called <a href="https://wporbit.net" target="_blank"><b>WPOrbit</b></a>. 
    I have spent quite a few days creating this plugin. I also actively maintain it. Please take a few minutes to rate us on <a target="_blank" href="https://wordpress.org/plugins/perfect-font-awesome-integration/">wordpress.org</a>.</p>
<hr style="margin: 3% 0;">
        <h2 style="color:#333;margin-top:0;">Other Plugins:</h2>
<a href="https://wordpress.org/plugins/wp-masonry-infinite-scroll/" target="_blank"><img src="<?php echo plugins_url( '../images/wmis.png' , __FILE__ ); ?>" style="width: 100%;margin-bottom:30px;"></a>

<a href="https://wordpress.org/plugins/gmis/" target="_blank"><img src="<?php echo plugins_url( '../images/gmis.png' , __FILE__ ); ?>" style="width: 100%;"></a>
    
</div>
<div class="pfai_admin_col_2">
<a href="https://wporbit.net" target="_blank"><img src="<?php echo plugins_url( '../images/logo.png' , __FILE__ ); ?>" style="width: 100%;max-width: 200px;margin: 1% auto 6%;display: block;"></a>
<h3 style="color:#333;">Having trouble using this plugin? </h3>
<p>Drop your queries on our email address at <b><a href="mailto:support@wporbit.net">support@wporbit.net</a></b></p>
<p><a class="pfai_button" style="display:block;width:100%;box-sizing:border-box;border-radius:4px;text-align:center;color:#fff;" target="_blank" href="https://wporbit.net/contact-us/">Contact Us</a></p>
</div>

 

<div class="pfai_admin_col_2">
    <h3 style="color:#333;margin-top:0;text-align: center;">Loved this Plugin?</h3>
    <span class="dashicons dashicons-smiley" style="font-size: 100px;display: block;text-align: center;width: 100%;height: auto;color: #002e6a;"></span>
    <p style="text-align: center;">We need your support maintaining it.</p>
<p style="text-align: center;"><a href="https://www.paypal.me/kaushiksomaiya" style="background: gold;text-decoration: none;padding: 9px 30px;color: #003366;border-radius: 30px;border-bottom: solid 2px #dbb900;font-weight: 500;display: block;max-width: 150px;text-align: center;margin: auto;" target="_blank">Donate</a></p>
</div>


</div>
<style>
    .pfai_admin_main_container h2{
        font-size: 22px;
    }
    
    .pfai_admin_main_container p{
        font-size: 15px;
    }
    .pfai_admin_col_1,.pfai_admin_col_2{
    box-sizing: border-box;
    padding: 2%;
    }
    .pfai_admin_col_1{
    width: 70%;
    background: #fff;
    border-radius: 4px;
    min-height: 300px;
    margin: 1%;
    float: left;
    }
    .pfai_admin_col_2{
    width: 26%;
    background: #fff;
    border-radius: 4px;
    margin: 1%;
    float: left;
    }
   .pfai_cform input,.pfai_cform textarea{
    width: 100%;
    margin-bottom: 10px;
    border: solid 1px #ccc;
    border-radius: 4px;
    }
    .pfai_cform input[type="submit"],.shortcodebutton,.pfai_button{
    background: #cc0000;
    color: #fff;
    border: 0;
    padding: 10px 20px;
    cursor: pointer;
    display:inline-block;
    text-decoration:none;
    }
    .shortcode_container{
        border:solid 1px #ddd;
        padding: 2%;
        border-radius: 4px;
    }
    
    .pfai_section_group{
    border: solid 1px #ddd;
    padding: 3% 2.5%;
    background: #f5f5f5;
    margin-bottom: 30px;
    position: relative;
    border-radius: 6px;
    }
    
    .pfai_section_group h3{
         color:#fff;
         margin: 0;
         position: absolute;
         right: 0;
         top: 0;
         background: #333;
         padding: 4px 8px 6px;
         font-size: 14px;
         border-bottom-left-radius: 4px;
         border-top-right-radius: 4px;
    }

    .pfai_form_group{
        margin-bottom:15px;
        padding-bottom:15px;
        border-bottom:solid 1px #eee;
    }
    
    .pfai_section_group .pfai_form_group:last-of-type{
        margin-bottom:0;
        padding-bottom:0;
        border-bottom:0;
    }
    
    .pfai_form_group label{
        display:block;
        font-weight:bold;
        margin-bottom:5px;
    }
    .pfai_form_group select{
        width: 200px;
    }
    .pfai_shortcode input{
        width:100%;
    }
    .pfai_shortcode label{
        display:block;
        margin-bottom:5px;
    }
</style>
<?php
}
