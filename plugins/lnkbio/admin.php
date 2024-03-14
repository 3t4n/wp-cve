<div class="wrap" id="LB_cont">
	<h1>Lnk.Bio integration</h1>
	<h2>Automatically publish new Wordpress Posts as links on Lnk.Bio</h2>

	<form method="post" action="options.php"> 
		<p>You can retrieve these parameters from your Lnk.Bio account under the <a href="https://lnk.bio/manage/integrations/wordpress" target="_Blank" rel="noopener noreferer">Wordpress integration page.</a></p>
		<?php settings_fields('lnkbio_options'); ?>
		<?php do_settings_sections('lnkbio_options'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Lnk.Bio ID</th>
				<td><input type="text" name="lnkbio_id" value="<?php echo esc_attr(get_option('lnkbio_id')); ?>" /></td>
			</tr>
			
			<tr valign="top">
				<th scope="row">Lnk.Bio Secret</th>
				<td><input type="password" name="lnkbio_secret" value="<?php echo esc_attr(get_option('lnkbio_secret')); ?>" /></td>
			</tr>
			
			<tr valign="top" style="display:none" id="lnkbio_group_container">
				<th scope="row">Lnk.Bio Group</th>
				<td><select name="lnkbio_group" id="lnkbio_group"></select></td>
			</tr>
		</table>

		<table width="100%">
			<tr>
				<td width="50%"><?php submit_button(); ?></td>
				<td width="50%">
					<p class="submit">
					<?php if (get_option('lnkbio_id') && get_option('lnkbio_secret')) { ?>
						<button class="button button-secondary" id="LB_test" type="button">Test connection</button> 
						<span class="spinner" id="test_spinner"></span>
					<?php } ?>
					</p>
				</td>
			</tr>
			<tr>
				<td width="100%" colspan="2">
					<p class="submit" style="text-align:center">
					<?php if (get_option('lnkbio_id') && get_option('lnkbio_secret')) { ?>
						If you wish to sync all your existing Wordpress posts to your Lnk.Bio account, click the button below<br /><br />
						<button class="button button-secondary" id="LB_mass" type="button">Mass Sync</button> <br /><br />
						<span class="spinner" id="mass_spinner"></span>
					<?php } ?>
					</p>
				</td>
			</tr>
		</table>
	</form>

</div>