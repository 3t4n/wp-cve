<?php
defined('ABSPATH') or die('Nope nope nope...');

include_once 'BlockedEmail.php';
$obj = new ADEV_BlockedEmails_List();

$list = array(
    'disposable email' => array('count'=>0, 'is_blocked' => !get_option('adev_allow_disposable_email'), 'is_available' => 1),
    'generic email' => array('count'=>0, 'is_blocked' => !get_option('adev_allow_role_business_email'), 'is_available' => 1),
    'free email' => array('count'=>0, 'is_blocked' => !get_option('adev_allow_free_email'), 'is_available' => 1),
    'invalid syntax' => array('count'=>0, 'is_blocked' => 1, 'is_available' => 1),
    'invalid host' => array('count'=>0, 'is_blocked' => 1, 'is_available' => 1),
    'black listed email' => array('count'=>0, 'is_blocked' => 1, 'is_available' => 1),
    'black listed domain' => array('count'=>0, 'is_blocked' => 1, 'is_available' => 1),
    'invalid mx record' => array('count'=>0, 'is_blocked' => 1, 'is_available' => get_option('adev_token')),
);

foreach($obj->grouped_list() as $key => $value){
    $list[$value['type']]['count'] = $value['c'];
}

?>
<style type="text/css">

#wpcontent {
    padding-left: 0px !important; 
}

.anticontainer{
    width: auto;
    clear: both;
    padding: 1%;
    background-color: #f3f6ff;
}

.maincounts{
    width: 100%;
    padding: 2% 0%;
    text-align: left;
    background-color: #fff;
}

.antititle{
    display: inline-flex;
}

.antititle h3{
    transform: translateY(30%);
    transform: translateX(3%);
}

.maincounts h4{
    padding-left: 20px;
    font-size: 16px;
}

.maincount{
    width: 100%;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    background-color: #fff;
}

.counts{
    width: 24.5%;
    border-width: 1px 1px 1px 1px;
	border-style: solid;
	border-color: #b6c9da;
    position: relative;
    text-align: center;
}

.tinfo{
	position: absolute;
	top: 5px;
	right: 5px;
}

.tinfo .tooltip {
	visibility: hidden;
	width: 200px;
	height:auto;
	background-color: #052c88;
	color: #fff;
	text-align: center;
	border-radius: 6px;
	padding: 5px 5px;
	bottom:30px;
	right: -10px;
	position: absolute;
	z-index: 1;
}

.tooltip a{
    color: #ffc81f;
}

.tinfo .tooltip:before {
	content: '';
	display: block;
	width: 0;
	height: 0;
	position: absolute;
	border-left: 10px solid transparent;
	border-right: 10px solid transparent;
	border-top: 10px solid #052c88;
	bottom: -9px;
	right: 10px;
}

.tinfo:hover .tooltip {
  visibility: visible;
}

h3{
    font-weight: 700;
}

.counts .title{
	color: #686868;;
    font-size: 18px;
    font-weight: 700;
    padding: 20px 4px;
}

.counts.not-available .title{
	color: #6868689e;;
}

.counts .qty{
    color: #4f9fe0;
    font-size: 40px;
    font-weight: 700;
    padding: 15px;
    margin-bottom: 10px;
}

.counts.not-available .qty{
	color: #4f9fe08c;
}

.antiintro{
    text-align: left;
    width: auto;
    padding:20px;
    margin:20px auto;
    height: auto;
    clear: both;
    background-image: url("<?php echo plugin_dir_url( __FILE__ ) . 'assets/hbgimage1.jpg'; ?>");
    background-repeat: no-repeat;
    background-size: cover;
    background-color: #fff;    
}

.antiintro h2{
    font-size: 18px;
    font-weight: 700;
}

.antiintro p{
	font-size: 16px;
	color: #393f4c;
	line-height: 1.8;
	font-weight: 700;
}

.antipara{
    padding: 20px 0;
    font-size: 16px;
}

.antibtn{
    background: #5cc0a5;
    border-color: #40a88d;
    color: #fff;
    font-size: 17px;
    font-weight: 700;
    padding: 15px 25px;
    line-height: 1;
    border: solid;
    border-width: 1px 1px 2px;
    border-radius: 3px;
    cursor: pointer;
    display: inline-block;
    text-decoration: none;
}

.antibtn:hover{
    color: #fff;
    background: #5cc0b8;
    text-decoration: none;
}

.antilist{
    width: auto;
    padding:30px;
}

.antifooter{
	width:auto;
	height: auto;
	font-size: 16px;
	line-height: 1.5;
	text-align: center;
	margin: 20px;
}

.antipremium{
    float: right;
    right: 20px;
    position: absolute;
}

.prespace{
    width: 100%;
    text-align: left;
}

.prespace h2{
    font-size: 22px;
    color: #5cc0a5;
}

.prespace a{
    text-decoration: none;
}

@media(max-width: 420px){

    .maincount{
        flex-direction: column;
    }
    .counts{
    width: 100%;
    }

    .prespace .antibtn{
        margin-top: 5%;
    }

}

</style>
<div class="anticontainer"> 

    <div class="antititle">
        <img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/antideomin.png'; ?>" width="50px">
        <h3><?php echo esc_html__('Antideo Email Validator','antideo-email-validator'); ?></h3>
    </div>

    <?php if(get_option('adev_token')) { ?>
    <div class="antiintro">
        <div class="prespace">
            <h2> <?php echo esc_html__('You are using Antideo premium','antideo-email-validator'); ?> 
                <a class="antibtn" target="_blank" href="https://account.antideo.com/account">
                <?php echo esc_html__('Click here to access your account','antideo-email-validator'); ?>
                </a>
            </h2>
        </div>
    </div>
    <?php } ?>
    
    <h3><?php echo esc_html__('Overview','antideo-email-validator'); ?></h3>
    <div class="maincounts">
        <div class="maincount">
            <?php foreach($list as $key => $value){ ?>
				<div class="counts <?php echo $value['is_available'] ? '' : 'not-available'; ?> ">
					<div class="tinfo">
						<img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/infoicon2.png'; ?>" width="15px">
						<span class="tooltip"><?php echo esc_html__('Number of '. $key . ' blocked.','antideo-email-validator'); ?><a href="https://www.antideo.com/wordpress-email-validation-plugin/" target="blank"><?php echo esc_html__('What is a ' . $key . '?' ,'antideo-email-validator'); ?> </a></span>
					</div>
					<div class="title"><?php echo ucwords($key); ?></div>
					<div class="qty"><?php echo $value['is_available'] && $value['is_blocked']  ? $value["count"] : '-'; ?></div>
				</div>
			<?php }?>
        </div>
    </div>

    <?php if(!get_option('adev_token')) { ?>
    <div class="antiintro">
        <h2><?php echo esc_html__('Upgrade to Premium to be equipped with more powerful features' ,'antideo-email-validator'); ?> </h2>
        <p class="antipara"><?php echo esc_html__('New disposable vendors pop up frequently and you can stay on the top ' ,'antideo-email-validator'); ?>
        <br> <?php echo esc_html__('with the premium version that keeps the list updated dynamically.' ,'antideo-email-validator'); ?> <br><?php echo esc_html__('Ensure your team doesn\'t waste time on fake leads.' ,'antideo-email-validator'); ?></p>
        <a target="_blank" class="antibtn"  href="https://www.antideo.com/wordpress-email-validation-plugin/"><?php echo esc_html__('Upgrade to Antideo Premium' ,'antideo-email-validator'); ?></a>
    </div>
    <?php } ?>

    <div class="antilist">
        <?php
            $obj->prepare_items();
            $obj->display(); 
        ?>
    </div>
    <div class="antifooter">
        <?php echo esc_html__('Please rate Antideo Email Validator' ,'antideo-email-validator'); ?> 
        <img src="<?php echo plugin_dir_url( __FILE__ ) . 'assets/stars.png'; ?>">
        <?php echo esc_html__(' on' ,'antideo-email-validator'); ?>
        <a target="_blank" href="https://wordpress.org/plugins/antideo-email-validator/" >
            <?php echo esc_html__('WordPress.org' ,'antideo-email-validator'); ?>
        </a>
        <?php echo esc_html__(' to help us spread the word. Thank you from the Antideo team!' ,'antideo-email-validator'); ?>
    </div>
</div>