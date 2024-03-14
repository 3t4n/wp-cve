<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
/**
 * add.php
 *
 * @package IntelliWidget
 * @subpackage includes
 * @author Jason C Fleming
 * @copyright 2014-2015 Lilaea Media LLC
 * @access public
 */
?>
<div class="iw-copy-container"> <span class="spinner" id="intelliwidget_spinner"></span> </div>
<a title="<?php echo $this->get_tip( 'iw_add' ); ?>" style="float:left;" href="<?php echo $obj->get_nonce_url( $id, 'add' ); ?>" id="iw_add_<?php echo $id; ?>" class="iw-add">
<?php echo $this->get_label( 'iw_add' ); ?>
</a>
<?php wp_nonce_field( 'iwpage_' . $id,'iwpage' ); ?>
<div style="clear:both"></div>



