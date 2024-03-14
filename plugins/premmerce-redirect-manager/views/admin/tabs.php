<?php
if(!defined('ABSPATH')){
	exit;
}
?>
<h2 class="nav-tab-wrapper">
	<?php foreach($tabs as $tab => $name): ?>
		<?php $class = ($tab == $current)? ' nav-tab-active' : ''; ?>
        <a class='nav-tab<?php echo $class ?>'
           href='?page=premmerce_redirect&tab=<?php echo $tab ?>'><?php echo $name ?></a>
	<?php endforeach; ?>
</h2>