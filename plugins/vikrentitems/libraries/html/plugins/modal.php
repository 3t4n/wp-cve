<?php
/** 
 * @package   	VikRentItems - Libraries
 * @subpackage 	html.plugins
 * @author    	E4J s.r.l.
 * @copyright 	Copyright (C) 2018 E4J s.r.l. All Rights Reserved.
 * @license  	http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link 		https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$id 	= !empty($displayData['id']) 		? $displayData['id'] 		: uniqid();
$title 	= !empty($displayData['title']) 	? $displayData['title'] 	: 'Title';
$body 	= !empty($displayData['body']) 		? $displayData['body'] 		: '';
$style 	= !empty($displayData['style']) 	? $displayData['style'] 	: '';

?>

<div class="modal hide fade" id="jmodal-<?php echo $id; ?>" style="<?php echo $style; ?>">
	<div class="modal-header">
		<span class="box-close">
			<button type="button" class="close" data-dismiss="modal">×</button>
		</span>
		<h3><?php echo $title; ?></h3>
	</div>
	<div class="modal-body-wrapper" id="jmodal-box-<?php echo $id; ?>">
		<?php if (!empty($body)) { ?>
			<div class="modal-body"><?php echo $body; ?></div>
		<?php } ?>
	</div>
</div>
