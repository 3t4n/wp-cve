<div class="hpf-admin-settings-primary-nav">

	<nav>
		<ul>
			<?php
				foreach ( $tabs as $key => $value )
				{
					echo '<li' . ( $key == $active_tab ? ' class="active"' : '' ) . '><a href="' . admin_url('admin.php?page=houzez-property-feed-import&tab=' . esc_attr($key)) . '">' . esc_html($value) . '</a></li>';
				}
			?>
		</ul>
	</nav>

</div>