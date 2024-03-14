<?php
if ( ! defined( 'ABSPATH' ) ) { exit; } 
global $foxtool_options;
# shortcode an noi dung theo nhom
if (isset($foxtool_options['shortcode-s1'])){
function foxtool_content_pro($atts, $content = null) {
    global $foxtool_options;
    $roleuser = !empty($foxtool_options['shortcode-s11']) ? $foxtool_options['shortcode-s11'] : 'subscriber';
    $locked_content = !empty($foxtool_options['shortcode-s12']) ? $foxtool_options['shortcode-s12'] : __('This content is locked! You need to log in to view', 'foxtool');
    if (current_user_can($roleuser) || current_user_can('administrator')) {
        return '<div>'. do_shortcode($content) .'</div>';
    } else {
        return '<div class="ft-vip">' . $locked_content . '</div><style>.ft-vip{box-sizing:border-box;background:#ff7b3c;padding:20px;border-radius:5px;color: #fff;}</style>';
    }
}
add_shortcode('vip', 'foxtool_content_pro');
}
# shortcode chữ ký
if (isset($foxtool_options['shortcode-s2'])){
function foxtool_sign_shortcode(){
	global $foxtool_options;
	$shortcode_s21 = !empty($foxtool_options['shortcode-s21']) ? $foxtool_options['shortcode-s21'] : '';
    return '<div>'. do_shortcode($shortcode_s21) .'</div>'; 
}
add_shortcode('sign', 'foxtool_sign_shortcode');
}
# shortcode titday
if (isset($foxtool_options['shortcode-s3'])){
// titday
function foxtool_dateday_shortcode(){
	global $foxtool_options;
	if(isset($foxtool_options['shortcode-s31']) && $foxtool_options['shortcode-s31'] == 'EN'){
	$date = date_i18n('Y/m/d');	
	} else {
    $date = date_i18n('d/m/Y');
	}
    return $date;
}
add_shortcode('titday', 'foxtool_dateday_shortcode');
// titmoth
function foxtool_datemonth_shortcode(){
	global $foxtool_options;
	if(isset($foxtool_options['shortcode-s31']) && $foxtool_options['shortcode-s31'] == 'EN'){
	$date = date_i18n('Y/m');	
	} else {
    $date = date_i18n('m/Y');
	}
    return $date;
}
add_shortcode('titmonth', 'foxtool_datemonth_shortcode');
// tityear
function foxtool_dateyear_shortcode(){
    $date = date_i18n('Y');
    return $date;
}
add_shortcode('tityear', 'foxtool_dateyear_shortcode');
}
# shortcode gget
if (isset($foxtool_options['shortcode-s4'])){
static $tai_id = 1;
function foxtool_tai_shortcode($args, $content){
global $foxtool_options, $tai_id;
$tai_id++;
extract($args);
ob_start(); ?>
<div class="ft-tai">
<a class="ft-tai-a" title="<?php if(!empty($content)){echo $content;} else {_e('Download', 'foxtool');} ?>" id="dow-<?php echo $tai_id; ?>"><svg width="100%" height="100%" viewBox="0 0 70 70" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
    <g transform="matrix(0.0977275,0,0,0.0977275,9.98177,9.98177)">
        <g>
            <g>
                <path d="M151.467,448C81.067,448 23.467,390.4 23.467,320C23.467,260.267 64,209.067 121.6,196.267C132.267,192 145.067,200.533 147.2,211.2C149.333,221.867 142.933,234.667 130.133,236.8C91.733,245.333 64,279.467 64,320C64,366.933 102.4,405.333 149.333,405.333C157.867,405.333 164.267,403.2 172.8,401.067C183.467,396.8 196.267,405.333 198.4,416C202.667,426.667 194.133,439.467 183.467,441.6C174.933,445.867 162.133,448 151.467,448Z" style="fill:white;fill-rule:nonzero;"/>
                <path d="M360.533,448C347.733,448 337.067,445.867 326.4,443.733C315.733,439.467 309.333,428.8 311.467,418.133C313.6,407.467 326.4,401.067 337.067,403.2C345.6,405.333 352,407.467 360.533,407.467C407.467,407.467 445.867,369.067 445.867,322.133C445.867,281.6 418.133,247.467 379.733,238.933C369.067,236.8 360.533,224 364.8,213.333C369.067,202.667 379.733,194.133 390.4,198.4C448,211.2 488.533,262.4 488.533,322.133C488.533,390.4 430.933,448 360.533,448Z" style="fill:white;fill-rule:nonzero;"/>
                <path d="M386.133,234.667C373.333,234.667 364.8,226.133 364.8,213.333C364.8,153.6 317.867,106.667 258.133,106.667C198.4,106.667 151.467,153.6 151.467,213.333C151.467,226.133 142.933,234.667 130.133,234.667C117.333,234.667 108.8,226.133 108.8,213.333C108.8,130.133 174.933,64 258.133,64C341.333,64 407.467,130.133 407.467,213.333C407.467,226.133 396.8,234.667 386.133,234.667Z" style="fill:white;fill-rule:nonzero;"/>
                <path d="M258.133,416C251.733,416 247.467,413.867 243.2,409.6L168.533,334.933C160,326.4 160,313.6 168.533,305.067C177.067,296.533 189.867,296.533 198.4,305.067L258.133,364.8L320,305.067C328.533,296.533 341.333,296.533 349.867,305.067C358.4,313.6 358.4,326.4 349.867,334.933L273.067,409.6C268.8,413.867 262.4,416 258.133,416Z" style="fill:white;fill-rule:nonzero;"/>
                <path d="M258.133,379.733C245.333,379.733 236.8,371.2 236.8,358.4L236.8,213.333C236.8,200.533 245.333,192 258.133,192C270.933,192 279.467,200.533 279.467,213.333L279.467,358.4C279.467,369.067 268.8,379.733 258.133,379.733Z" style="fill:white;fill-rule:nonzero;"/>
            </g>
        </g>
    </g>
</svg> <span><?php if(!empty($content)){echo $content;} else {_e('Download', 'foxtool');} ?></span> <b style="margin-left:5px;" id="box-<?php echo $tai_id; ?>"><span id="giay-<?php echo $tai_id; ?>"></span></b></a>
<?php if(isset($foxtool_options['shortcode-s4a'])){ ?>
<div style="display:none" class="ft-tai-link" id="link-<?php echo $tai_id; ?>"></div>
<?php } ?>
</div>
<script>
    jQuery(document).ready(function($) {
        var countdownRunning = false;
        function startCountdown(giayId, boxId, get) {
            var n = <?= !empty($foxtool_options['shortcode-s41']) ? $foxtool_options['shortcode-s41'] : '10' ?>;
            $(giayId).text(n);
            $(boxId).show();
            var countdown = setInterval(function() {
                if (!document.hidden) {
                    n -= 1;
                } else {
                    n -= 0;
                }
                $(giayId).text(n);
                if (n === 0) {
                    clearInterval(countdown);
                    if (<?= isset($foxtool_options['shortcode-s4a']) ? 'true' : 'false' ?>) {
                        $("#dow-<?= $tai_id ?>").hide();
                        $("#link-<?= $tai_id ?>").show();
                        $("#link-<?= $tai_id ?>").html('<a target="_blank" href="' + get + '">' + get + '</a>');
                    } else {
                        window.location = get;
                    }
                    $(boxId).hide();
                    countdownRunning = false;
                }
            }, 1000);
            $(boxId).click(function() {
                clearInterval(countdown);
                if (<?= isset($foxtool_options['shortcode-s4a']) ? 'true' : 'false' ?>) {
                    $("#dow-<?= $tai_id ?>").hide();
                    $("#link-<?= $tai_id ?>").show();
                    $("#link-<?= $tai_id ?>").html('<a target="_blank" href="' + get + '">' + get + '</a>');
                } else {
                    window.location = get;
                }
                $(boxId).hide();
                countdownRunning = false;
            });
        }
        $("#dow-<?= $tai_id ?>").click(function() {
            if (!countdownRunning) {
                startCountdown("#giay-<?= $tai_id ?>", "#box-<?= $tai_id ?>", "<?= $url ?>");
                countdownRunning = true;
            }
        });
    });
</script>
<?php 
$tais = ob_get_clean(); 
return $tais;
}
add_shortcode( 'gget', 'foxtool_tai_shortcode' );
function foxtool_tai_shortcode_css(){
	global $foxtool_options;
	$colorbg = !empty($foxtool_options['shortcode-s42']) ? $foxtool_options['shortcode-s42'] : '#00875c';
	$colorbo = !empty($foxtool_options['shortcode-s43']) && !empty($foxtool_options['shortcode-s44']) ? 'border-bottom:'. $foxtool_options['shortcode-s44'] .'px solid '. $foxtool_options['shortcode-s43'] .';' : 'border:none;';
	$borderru = !empty($foxtool_options['shortcode-s45']) ? $foxtool_options['shortcode-s45'] : '10';
	$center = isset($foxtool_options['shortcode-s4b']) ? '.ft-tai {text-align:center !important;}' : NULL;
	?>
	<style>
	.ft-tai{
		margin-top:10px;
		margin-bottom:10px;
	}
	.ft-tai .ft-tai-a{
		background: <?php echo $colorbg; ?>;
		color: #fff;
		padding: 7px 20px 7px 60px;
		<?php echo $colorbo; ?>
		text-decoration: none;
		box-sizing: border-box;
		border-radius: <?php echo $borderru; ?>px;
		font-weight: bold;
		cursor: pointer;
		display: inline-flex;
		align-items: center;
		text-transform: uppercase;
		position: relative;
		font-size:20px;
	}
	.ft-tai .ft-tai-a:hover{
		color:#eee;
		opacity:0.8;
	}
	.ft-tai-a svg {
		position: absolute;
		border-right: 3px solid #ffffff42;
		left: 0;
		top: 0;
		bottom: 0;
		padding: 4px;
		height: 100%;
		box-sizing: border-box;
		width: 50px;
	}
	.ft-tai-link{
		margin-top:10px;
		margin-bottom:10px;
	}
	.ft-tai-link a{
		text-decoration: none;
		font-weight:bold;
	}
	<?php echo $center; ?>
	</style>
	<?php
}
add_action('wp_head', 'foxtool_tai_shortcode_css');
}

