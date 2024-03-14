<?php
$parsedown = new Parsedown();

$msg = "";

add_action( 'admin_footer', function () {
	$xpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__), "", plugin_basename(__FILE__));
	?><script src="<?php echo $xpath; ?>citta.js" type="text/javascript"></script>
	<script type="text/javascript">
		function select_regione( divcity, divregio ) {
			temp = me.region1.options[me.region1.selectedIndex].value;
			varreg = ottienicitta( temp );
			lista = varreg.split( '---' );
			for( u=0; u < lista.length; u++ ) {
				duepezzi = lista[u].split( 'XxX' );
				document.getElementById( divcity ).options[u] = new Option(
					duepezzi[1]+' (loc='+duepezzi[0]+')',duepezzi[0]
				);
			}
		}
	</script><?php
}, PHP_INT_MAX );
?><style>
	.display-flex {
		display: column;
	}
	@media only screen and (min-width: 600px) {
		.display-flex {
			display: flex;
		}
	}
	a.sm_button {
		display: block;
		padding: 4px 4px 4px 25px;
		background-repeat: no-repeat;
		background-position: 5px 50%;
		text-decoration: none;
		border: none;
	}
	.sm-padded .inside ul {
		margin: 6px 0 12px 0;
	}
	.sm-padded .inside input {
		padding: 1px;
		margin: 0;
	}
	.inside ul li {
		display: block;
		padding: 4px 4px 4px 25px;
		background-repeat: no-repeat;
		background-position: 5px 50%;
		text-decoration: none;
		border: none;
	}
</style>
	<div class="wrap" id="sm_div">
<?php
$x = plugin_basename(__FILE__);
$x = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__), "", plugin_basename(__FILE__));?>
	<h2>3b Meteo</h2>
	by <strong>Meteo Solution</strong>
		<iframe src="https://www.facebook.com/plugins/like.php?href=<?php echo urlencode('https://www.3bmeteo.com'); ?>&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; margin-left:50px;width:450px; height:30px;"></iframe>
<?php if ($msg): ?>
	<div id="message" class="error"><p><strong><?php echo $msg; ?></strong></p></div>
<?php endif; ?>

	<div id="wpbody-content">

		<div class="wrap" id="sm_div">
			<p>Maggiori informazioni su come usare i nuovi widget le puoi trovare <a
						href="https://wordpress.org/plugins/3b-meteo/" target="_blank">qui.</a></p>
			<div id="poststuff" class="metabox-holder has-right-sidebar display-flex">
				<div>
					<?php
					$readme = file_get_contents( TREBIMETEO_PATH . 'README.md' );
					echo wp_kses_post( $parsedown->text( $readme ) );
					?>
				</div>
				<div class="inner-sidebar">
					<div id="side-sortables" class="meta-box-sortabless ui-sortable" style="position:relative;">
						<img src="<?php echo $x;?>3bmeteo.jpg" style="display: block;padding:1rem;margin: auto" alt="Logo 3bMeteo"/>
						<div id="sm_pnres2" class="postbox">
							<h3 class="hndle"><span>Codici Regioni e Localit&agrave;: </span></h3>
							<div class="inside">
								<form method="POST" name="me">
									<label for="region1">Seleziona una regione</label>
									<select
											name="region1"
											style="width:99%"
											class="insidemodule"
											id="region1"
											onchange="select_regione('localita1','region1');"
									>
										<option label="Abruzzo (idreg=1)" value="1">Abruzzo (idreg=1)</option>
										<option label="Basilicata (idreg=2)" value="2">Basilicata (idreg=2)</option>
										<option label="Calabria (idreg=3)" value="3">Calabria (idreg=3)</option>
										<option label="Campania (idreg=4)" value="4">Campania (idreg=4)</option>
										<option label="Emilia (idreg=5)" value="5">Emilia (idreg=5)</option>
										<option label="Friuli (idreg=6)" value="6">Friuli (idreg=6)</option>
										<option label="Lazio (idreg=7)" value="7">Lazio (idreg=7)</option>
										<option label="Liguria (idreg=8)" value="8">Liguria (idreg=8)</option>
										<option label="Lombardia (idreg=9)" value="9">Lombardia (idreg=9)</option>
										<option label="Marche (idreg=10)" value="10">Marche (idreg=10)</option>
										<option label="Molise (idreg=11)" value="11">Molise (idreg=11)</option>
										<option label="Piemonte (idreg=12)" value="12">Piemonte (idreg=12)</option>
										<option label="Puglia (idreg=13)" value="13">Puglia (idreg=13)</option>
										<option label="Sardegna (idreg=14)" value="14">Sardegna (idreg=14)</option>
										<option label="Sicilia (idreg=15)" value="15">Sicilia (idreg=15)</option>
										<option label="Toscana (idreg=16)" value="16">Toscana (idreg=16)</option>
										<option label="Trentino (idreg=17)" value="17">Trentino (idreg=17)</option>
										<option label="Umbria (idreg=18)" value="18">Umbria (idreg=18)</option>
										<option label="Valle aosta (idreg=19)" value="19">Valle d'aosta (idreg=19)</option>
										<option label="Veneto (idreg=20)" value="20">Veneto (idreg=20)</option>
									</select>
									<p>
										<select name="localita1" id="localita1" style="width:99%" class="insidemodule">
											<option label="Localit&agrave;" value="01">Localit&agrave;</option>
										</select>
									</p>
								</form>
							</div>
						</div>
						<div id="sm_pnres" class="postbox">
							<h3 class="hndle"><span>Info plugin:</span></h3>
							<div class="inside">
								<a class="sm_button sm_pluginHome"    href="https://www.3bmeteo.com/">Homepage Previsioni Meteo</a>
								<a class="sm_button sm_pluginList"    href="https://twitter.com/3bmeteo">Twitter 3B Meteo</a>
								<a class="sm_button sm_donatePayPal"  href="https://facebook.com">Facebook 3B Meteo</a>
							</div>
						</div>

						<div id="sm_pnres1" class="postbox">
							<h3 class="hndle"><span>Info sviluppatore:</span></h3>
							<div class="inside">
								<a class="sm_button sm_pluginHome"    href="https://www.andreapernici.com/wordpress/3bmeteo/">Plugin Page</a>
								<a class="sm_button sm_donatePayPal"  href="https://www.andreapernici.com/">Home Page Autore</a>
								<a class="sm_button sm_pluginList"    href="https://twitter.com/andreapernici">Twitter Account</a>
								<a class="sm_button sm_pluginSupport" href="https://wordpress.org/extend/plugins/3bmeteo/">Support Forum</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
