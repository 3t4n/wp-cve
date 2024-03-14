<?php if ( ! defined( 'ABSPATH' ) ) exit;

$plugin_dir_url =  plugin_dir_url( __FILE__ );
?>

<style>



.premium-box {width: 100%;}

.premium-box-head {background: #eae8e7; width: 100%; height:500px; text-align: center;}

.pho-upgrade-btn {display: block; text-align: center;}

.pho-upgrade-btn a{display: inline-block;  margin-top: 75px;}

.pho-upgrade-btn a:focus {outline: none; box-shadow: none; }

.main-heading  {text-align: center; background: #fff; margin-bottom: -70px;}

.main-heading img {margin-top: -200px;}



.premium-box-container {margin: 0 auto;}

.premium-box-container .description {text-align: center; display: block; padding: 35px 0;}

.premium-box-container .description:nth-child(odd) {background: #fff;}

.premium-box-container .description:nth-child(even) {background: #eae8e7;}



.premium-box-container .pho-desc-head {width: 768px; margin: 0 auto; position: relative;}

.premium-box-container .pho-desc-head:after {background:url(<?php echo $plugin_dir_url; ?>../assets/images/head-arrow.png) no-repeat;

 position: absolute; right: -30px; top: -6px; width: 69px; height: 98px; content: "";} 



.premium-box-container .pho-desc-head h2 {color: #02c277; font-weight: bolder; font-size: 28px; text-transform: capitalize;margin: 0 0 30px 0; line-height:35px;}

.pho-plugin-content {margin: 0 auto; width: 768px; overflow: hidden;}

.pho-plugin-content p {line-height: 32px; font-size: 18px; color: #212121; }

.pho-plugin-content img {width: auto; max-width: 100%;}

.description .pho-plugin-content ol { margin: 0; padding-left: 25px; text-align: left;}

.description .pho-plugin-content ol li {font-size: 16px; line-height: 28px; color: #212121; padding-left: 5px;}

.description .pho-plugin-content .pho-img-bg { width: 750px; margin: 0 auto; border-radius: 5px 5px 0 0; 

padding: 70px 0 40px; height: auto;}

.premium-box-container .description:nth-child(odd) .pho-img-bg {background: #f1f1f1 url(<?php echo $plugin_dir_url; ?>../assets/images/image-frame-odd.png) no-repeat 100% top;}

.premium-box-container .description:nth-child(even) .pho-img-bg {background: #f1f1f1 url(<?php echo $plugin_dir_url; ?>../assets/images/image-frame-even.png) no-repeat 100% top;}



</style>



<div class="premium-box">



    <div class="premium-box-head">

        <div class="pho-upgrade-btn">

        <a href="https://www.phoeniixx.com/product/catalog-for-woocommerce/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>../assets/images/premium-btn.png" /></a>
		<a href="http://catalog.phoeniixxdemo.com/shop/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>../assets/images/demo-btn.png"></a>
        </div>

    </div>

    <div class="main-heading"><h1><img src="<?php echo $plugin_dir_url; ?>../assets/images/premium-head.png" /></h1></div>



        <div class="premium-box-container">

				<div class="description">

                <div class="pho-desc-head"><h2>Category based catalog mode</h2></div>

                

                    <div class="pho-plugin-content">

                       

                        <div class="pho-img-bg">

                        <img src="<?php echo $plugin_dir_url; ?>../assets/images/Category_based.jpg" />

                        </div>

                    </div>

				</div> <!-- description end -->



            

            <div class="description">

                <div class="pho-desc-head"><h2>Product based catalog mode</h2></div>

                

                    <div class="pho-plugin-content">



                        <div class="pho-img-bg">

                        <img src="<?php echo $plugin_dir_url; ?>../assets/images/product_base.jpg" />

                        </div>

                    </div>

            </div> <!-- description end -->
			
			<div class="description">

                <div class="pho-desc-head"><h2>User Role based catalog mode</h2></div>

                

                    <div class="pho-plugin-content">

                    

                        <div class="pho-img-bg">

                        <img src="<?php echo $plugin_dir_url; ?>../assets/images/user_role_based.jpg" />

                        </div>

                    </div>

            </div> <!-- description end -->
			
			
			<div class="description">

                <div class="pho-desc-head"><h2>Open Pop up when click on custom buttom</h2></div>

                

                    <div class="pho-plugin-content">

                      
                        <div class="pho-img-bg">

                        <img src="<?php echo $plugin_dir_url; ?>../assets/images/contact_form.jpg" />

                        </div>

                    </div>

            </div> <!-- description end -->
			
			<div class="description">

                <div class="pho-desc-head"><h2>Option to add any shortcode in pop up</h2></div>

                

                    <div class="pho-plugin-content">


                        <div class="pho-img-bg">

                        <img src="<?php echo $plugin_dir_url; ?>../assets/images/shortcode_in_popup.jpg" />

                        </div>

                    </div>

            </div> <!-- description end -->
			
			
			<div class="description">

                <div class="pho-desc-head"><h2>Supports contact form 7 shortcode</h2></div>

                

                    <div class="pho-plugin-content">

                     
                        <div class="pho-img-bg">

                        <img src="<?php echo $plugin_dir_url; ?>../assets/images/support_shortcode.jpg" />

                        </div>

                    </div>

            </div> <!-- description end -->

        </div> <!-- premium-box-container end -->

        

        <div class="pho-upgrade-btn">

			<a href="https://www.phoeniixx.com/product/catalog-for-woocommerce/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>../assets/images/premium-btn.png" /></a>
			<a href="http://catalog.phoeniixxdemo.com/shop/" target="_blank"><img src="<?php echo $plugin_dir_url; ?>../assets/images/demo-btn.png"></a>
        </div>



</div> <!-- premium-box end -->