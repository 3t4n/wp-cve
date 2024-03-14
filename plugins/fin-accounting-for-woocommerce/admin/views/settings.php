<!-- Page Content -->
<div id="finapp" class="fin-container">
	<div class="fin-tabs">	
    <nav class="nav-tab-wrapper w100">
			<a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_dashboard" class="nav-tab"><?php 
_e( 'Dashboard', 'finpose' );
?></a>
      <?php 
?>
      <a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_spendings" class="nav-tab"><?php 
_e( 'Spendings', 'finpose' );
?></a>
      <a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_orders" class="nav-tab"><?php 
_e( 'Orders', 'finpose' );
?></a>
      <a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_taxes" class="nav-tab"><?php 
_e( 'Taxes', 'finpose' );
?></a>
      <a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_accounts" class="nav-tab"><?php 
_e( 'Accounts', 'finpose' );
?></a>
      <a href="<?php 
echo  FINPOSE_WPADMIN_URL ;
?>admin.php?page=fin_settings" class="nav-tab nav-tab-active flr"><?php 
_e( 'Settings', 'finpose' );
?></a>
    </nav>
  </div>
	<div class="fin-content">
    <div class="w48">
    	<div class="fin-modal">
				<div class="fin-modal-content">
					<h2 style="margin:16px 0px;"><?php 
_e( 'Settings', 'finpose' );
?></h2>
					<form id="form-savesettings" @submit.prevent="saveSettings">
						<input type="hidden" name="process" value="saveSettings">
						<input type="hidden" name="handler" value="settings">
						<div class="flex">
							<div class="w48">
								<div class="pb1">
									<b><?php 
_e( 'Fiscal Year', 'finpose' );
?></b>
									<select name="fiscal" v-model="form.fiscal">
										<option value="standard"><?php 
_e( 'Standard (1 January)', 'finpose' );
?></option>
										<option value="indian"><?php 
_e( 'Indian (1 April)', 'finpose' );
?></option>
										<option value="australian"><?php 
_e( 'Australian (1 July)', 'finpose' );
?></option>
									</select>
								</div>
								<div class="pb1">
									<b><?php 
_e( 'Date Format', 'finpose' );
?></b>
									<select name="dateformat" v-model="form.dateformat">
										<option value="default"><?php 
_e( 'Default (Mon D,Y)', 'finpose' );
?></option>
										<option value="usa"><?php 
_e( 'USA (MM-DD-YY)', 'finpose' );
?></option>
										<option value="european"><?php 
_e( 'European (DD-MM-YY)', 'finpose' );
?></option>
									</select>
								</div>
								<div class="pb1">
									<b><?php 
_e( 'Timezone', 'finpose' );
?></b>
									<select name="timezone" v-model="form.timezone">
										<option v-for="(tzk, i) in tzkeys" :value="tzk" :key="i">{{timezones[tzk]}}</option>
									</select>
									<span v-if="wc_timezone">WooCommerce timezone setting: {{wc_timezone}}</span> 
								</div>
							</div>
							<div class="w48">
								<div>
									<input type="submit" class="fin-button flr" value="<?php 
esc_attr_e( 'Save', 'finpose' );
?>">
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
    </div>
		<div class="w48 p16">
			<h2>Logs</h2>
			<ul>
				<?php 
foreach ( $handler->view['logs'] as $msg ) {
    ?>
					<li><?php 
    echo  $msg ;
    ?></li>
				<?php 
}
?>
			</ul>
		</div>
	</div>
</div>
<!-- /#app -->