<?php
/**
 * Provide Add-on tab view.
 *
 * @package    Media_Restriction
 * @subpackage Media_Restriction/admin/partials
 */

/**
 * Addon tab UI initiated.
 *
 * @return mixed
 */
function mo_media_restriction_addon() {
	$all_addons = array(
		array(
			'tag'             => 'Paid Memberships Pro',
			'title'           => 'Paid Memberships Pro Integration',
			'desc'            => 'Allows to restrict access of files and folder based on membership own by the user, Here Paid Membership Pro will be used for handling membership.',
			'img'             => 'images/page-restriction.png',
			'in_allinclusive' => true,
		),

		array(
			'tag'             => 'Memberpress',
			'title'           => 'Memberpress Integration',
			'desc'            => 'Allows to restrict access of files and folder based on membership own by the user, Here memberpress will be used for handling membership.',
			'img'             => 'images/discord.png',
			'in_allinclusive' => true,
		),

		array(
			'tag'             => 'Token gating',
			'title'           => 'Media Restriction through Token gating',
			'desc'            => 'Allow to restrict access of files and folder based on the NFT holding of a user in their cryptowallet',
			'img'             => 'images/learndash.png',
			'in_allinclusive' => true,
		),

	);
	?>
<style>
.outermost-div {
	color: #424242;
	font-family: Open Sans!important;
	font-size: 1px;
	line-height: 1.4;
	letter-spacing: 0.3px;
}

.column_container {
	position: relative;
	box-sizing: border-box;
	margin-top: 20px;
	margin-bottom: 15px;
	border-color: 1px solid red;
	z-index: 1000;
}  

.column_container > .column_inner {
	box-sizing: border-box;
	padding-left: 15px;
	padding-right: 10px;
	width: 100%;
	margin-right: 1px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	border-radius: 15px;
} 

.benefits-outer-block{
	padding-left: 1em;
	padding-top: 3px;
	margin: 0;
	padding-bottom: 150px;
	background:#fff;
	height:350px;
	overflow: hidden;
	box-shadow: 0 5px 10px rgba(0,0,0,.20);
	border-radius: 5px;
}
.benefits-outer-block:hover {
	box-shadow: 0 8px 16px 0 rgb(0 0 0 / 40%);
}

.benefits-icon {
	font-size: 25px;
	padding-top: 6px;
	padding-right: 8px;
	padding-left: 8px;
	border-radius: 3px;
	padding-bottom: 5px;
	background: #1779ab;
	color: #fff;
}

.mo_2fa_addon_button{
	margin-top: 3px !important;
}

.mo_float-container {
	border: 1px solid #fff;
	padding-top: 10px;
	padding-left: 1px;
	padding-right: 2px;
	text-align: center;
	width: 246px;
}

.mo_float-child {
	padding: 10px;
	padding-left: 0px;
	height: 55px;
}  

.mo_float-child2{
	padding: 6px;
	padding-left: 0px;
	padding-top:0px;
	height: 50px;
	font-weight: 700;
}

.mo_oauth_btn{
	margin: 0;
	position: absolute;
	bottom: 10px;
	left: 50%;
	-ms-transform: translateX(-50%);
	transform: translateX(-50%);
	display: inline-box;
	line-height: 1.42857143;
	text-align: center;
	white-space: nowrap;
	vertical-align: middle;
	touch-action: manipulation;
	cursor: pointer;
	user-select: none;
	background-image: none;
}


.mo_oauth_know_more_button {
	border-radius: 5px;
	margin: 0.5em 0.5em 10px 0;
	color:#EB6440;
	font-size: 13px;
	font-family: Roboto;
	line-height: normal;
	padding: 0.4rem 1rem;
	border: solid 2px #EB6440;
	background-origin: border-box;
	box-shadow: 2px 1000px 1px #fff inset;
	transition: all 0.5s ease-out;
}
.mo_oauth_know_more_button:hover {
	box-shadow: 2px 1000px 1px #EB6440 inset;
	color: #fff !important;
	text-decoration:none;
}

a {
	text-decoration: none;
	color: #585858;
	transition: all 0.5s ease-out;
}

.mo_oauth_addon_headline a {
	text-decoration: none;
	color: #585858;
}

@media (min-width: 768px) {
	.grid_view {
		width: 33%;
		float: left;
	}
	.row-view {
		width: 100%;
		position: relative;
		display: inline-block;
	}
}

/*Content Animation*/
@keyframes fadeInScale {
	0% {
		transform: scale(0.9);
		opacity: 0;
	}

	100% {
		transform: scale(1);
		opacity: 1;
	}
}
</style>

<row class=" row mo_media_restriction_row">
<div class="col-md-9"> 
<div id="ip-restrcition" class="tabcontent mo_media_restriction_tabcontent">
	<div class="dashboard-sec mo_media_restriction_container">
<div class="mo_media_table_layout">
	<b><p style="padding-left: 15px;font-size: 20px;margin-top: 10px; margin-bottom: 10px;">Check out our Add-ons :</p></b>
<div class="outermost-div" style="background-color:#f7f7f7;opacity:1;">
	<?php
	$available_addons = array();

	foreach ( $all_addons as $key => $value ) {
		array_push( $available_addons, $value['tag'] );
	}

	$total_addons = count( $available_addons );
	for ( $i = 0; $i < $total_addons; $i++ ) {
		?>
	<div class="row-view">
		<?php
		get_single_addon_cardt( $available_addons[ $i ] );
		if ( $i + 1 >= $total_addons ) {
			break;
		}
		get_single_addon_cardt( $available_addons[ $i + 1 ] );
		$i++;
		if ( $i + 1 >= $total_addons ) {
			break;
		}
		get_single_addon_cardt( $available_addons[ $i + 1 ] );
		$i++;
		?>
	</div> 
		<?php
	}
	?>
</div></div></div>
</div>
	<?php
}
/**
 * Display Add-on cart.
 *
 * @param mixed $tags tangs.
 */
function get_single_addon_cardt( $tags ) {
	$all_addons = array(
		array(
			'tag'             => 'Paid Memberships Pro',
			'title'           => 'Paid Memberships Pro Integration',
			'desc'            => 'Allows to restrict access of files and folder based on membership own by the end user, Here Paid Membership Pro will be used for handling membership.',
			'img'             => 'images/paidmember.png',
			'in_allinclusive' => true,
		),

		array(
			'tag'             => 'Memberpress',
			'title'           => 'Memberpress Integration',
			'desc'            => 'Allows to restrict access of files and folder based on membership own by the end user, Here&nbsp;&nbsp;&nbsp;&nbsp; memberpress will be &nbsp;&nbsp;used for handling membership.',
			'img'             => 'images/memberpress.png',
			'in_allinclusive' => true,
		),

		array(
			'tag'             => 'Token gating',
			'title'           => 'Token gated media restriction ',
			'desc'            => 'Allow to restrict access of files and folder based on the NFT holding of a end user in their cryptowallet',
			'img'             => 'images/nft.png',
			'in_allinclusive' => true,
		),

	);
	foreach ( $all_addons as $key => $value ) {
		$addon = array();
		if ( strpos( $value['tag'], $tags ) !== false ) {
			$addon = $value;
			?>
		<div class="grid_view column_container" style="border-radius: 5px;">
		<div class="column_inner" style="border-radius: 5px;">
			<div class="row benefits-outer-block">
			<div class="mo_float-container">
			<div class="mo_float-child"> 
					<img src="<?php echo esc_url( plugins_url( $addon['img'], __DIR__ ) ); ?>" style="height:40px;width:40px;" >
				</div>
			<div class="mo_float-child2">
			<div class="mo_oauth_addon_headline"><strong><p style="font-size: 15px;margin: 1px;padding-left: 16px;line-height: 120%;font-weight: 600;font-family: Verdana, Arial, Helvetica, sans-serif;" ><?php echo esc_html( $addon['title'] ); ?></p></strong></div>
			</div>
			</div>
			<p style="text-align: center;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 12.5px;padding:0px 8px 0px 8px;"><?php echo esc_html( $addon['desc'] ); ?></p>
			<a class="mo_oauth_btn mo_oauth_know_more_button"  >Contact Us</a> 
			</div>
		</div>
		</div>
			<?php
		}
	}
}
?>
