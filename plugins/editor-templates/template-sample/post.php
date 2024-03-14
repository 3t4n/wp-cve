<div id="shop-image">
<h4>Shop Image(Post thumbnail)</h4>
<?php tpl_post_thumbnail(); ?>
</div>

<div id="shop-basic">
<h4>Shop Name(Title)</h4>
<?php tpl_title(); ?>

<h4>Shop Category(Category)</h4>
<?php tpl_taxonomy_list( array( 'type' => 'select' ) ); ?>
</div>

<h4>Shop Outline(Excerpt)</h4>
<?php tpl_excerpt( 'wysiwyg=1' ); ?>

<h4>Shop Detail(Content)</h4>
<?php tpl_editor(); ?>

<h4>Attributes(Custom fields)</h4>
<table class="editor_template_table">
	<tr>
		<th>Hours</th>
		<td><?php tpl_custom( 'meta_key=open_hours' ); ?> ex) 10:00 - 20:00</td>
	</tr>
	<tr>
		<th>Tel</th>
		<td><?php tpl_custom( 'meta_key=tel' ); ?> ex) 0000-000-0000</td>
	</tr>
	<tr>
		<th>Address</th>
		<td><?php tpl_custom( 'meta_key=address' ); ?> ex) Minato-ku, Tokyo, Japan</td>
	</tr>
	<tr>
		<th>Access</th>
		<td><?php tpl_custom( 'meta_key=access&type=textarea&size=70&wysiwyg=1' ); ?></td>
	</tr>
	<tr>
		<th>Parking</th>
		<td><?php tpl_custom( array( 'meta_key' => 'parking', 'type' => 'radio', 'items' => array( 'yes' => 'Exists', 'no' => 'Not Exists' ) ) ); ?></td>
	</tr>
	<tr>
		<th>Cards</th>
		<td><?php tpl_custom( array( 'meta_key' => 'cards', 'type' => 'checkbox', 'items' => array( 'Visa', 'Master Card', 'American Express', 'JCB' ) ) ); ?></td>
	</tr>
	<tr>
		<th>Floor</th>
		<td><?php tpl_custom( array( 'meta_key' => 'floor', 'type' => 'select', 'items' => array( 'BF', '1F', '2F', '3F', '4F', '5F', '6F', '7F', '8F' ) ) ); ?></td>
	</tr>
	<tr>
		<th>Shop Owner</th>
		<td><?php tpl_custom( array( 'meta_key' => 'shop owner', 'type' => 'image' ) ); ?></td>
	</tr>
</table>

<h4>Slug(Slug)</h4>
<?php tpl_slug(); ?>

<h4>Priority(Menu Order)</h4>
<?php tpl_menu_order(); ?>