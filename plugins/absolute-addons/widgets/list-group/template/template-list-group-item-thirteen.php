<?php
/**
 * Template Style Thirteen for List Group
 *
 * @package AbsoluteAddons
 */

/**
 * @var array $list
 * @var int $index
 */
?>
<span class="list-number"><?php echo esc_html( $index + 1 ); ?>.</span>
<?php
$this->render_title( $list );
$this->render_list_group_icon( $list );
