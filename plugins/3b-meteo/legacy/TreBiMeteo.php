<?php

final class TreBiMeteo
{
	/**
	 * Class Constructor
	 */
	public function __construct(){
	}

	public function boot() {
		// aggiungo il widget tra i disponibili
		add_action('widgets_init', array( $this,'SetWidget'));
		add_shortcode('trebi-a1', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-a2', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-a3', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-b1', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-b2', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-c1', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-c2', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-d1', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-d2', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-e1', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-e2', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-f1', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-f2', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-g1', array( $this,'trebi_shortcode_handler'));
		add_shortcode('trebi-g2', array( $this,'trebi_shortcode_handler'));
		add_filter( 'widget_text', 'shortcode_unautop');
		add_filter( 'widget_text', 'do_shortcode');
	}

	/**
	 * Enabled the RichCategoryEditor plugin with registering all required hooks
	 */
	function Enable() {

		//add_action('load-categories.php', array( $this,'SetAdminConfiguration'));
		//add_action('load-edit-tags.php', array( $this,'SetAdminConfiguration'));
		// Aggiungiamo la pagina delle opzioni
		add_action('admin_menu', array( $this,'SetAdminConfiguration'));
		//add_action('widgets_init', array( $this,'SetWidget'));

	}

	/**
	 * Add the 3B Meteo admin link to the Settings Bar.
	 */
	function SetAdminConfiguration() {
		add_options_page("3B Meteo", "3B Meteo", 'manage_options', basename(__FILE__), array( $this,'DesignAdminPage'));
	}

	/**
	 * Call the 3B Meteo Widget
	 */
	function SetWidget() {

		// This registers our widget so it appears with the other available
		// widgets and can be dragged and dropped into any active sidebars.
		$name = '3B Meteo';
		$id      = sanitize_title( $name );
		\wp_register_sidebar_widget( $id, $name, [ $this,'WidgetMeteo' ] );

		// This registers our optional widget control form.

		$options = [
			'width' => 450,
			'height' => 325,
		];

		\wp_register_widget_control( $id, $name, [ $this,'WidgetMeteoControl' ], $options );
	}

	/**
	 * Setup 3B Meteo Widget Frontend
	 */
	function WidgetMeteo($vars) {
		global $wpdb;
		global $wpdb_query;
		global $wp_rewrite;
		extract($vars);

		$options = get_option('widget_trebimeteo');

		$trebimeteotitle = $options['trebimeteotitle'];
		$trebiregione = urlencode($options['trebiregione']);
		$trebilocalita = urlencode($options['trebilocalita']);
		$trebicuno = urlencode($options['trebicuno']);
		$trebicdue = urlencode($options['trebicdue']);
		$trebictre = urlencode($options['trebictre']);
		$trebibuno = urlencode($options['trebibuno']);
		$trebibdue = urlencode($options['trebibdue']);
		$trebibtre = urlencode($options['trebibtre']);
		$trebilarghezza = urlencode($options['trebilarghezza']);
		$trebialtezza = urlencode($options['trebialtezza']);
		$trebitipo = urlencode($options['trebitipo']);

		$before_widget='<div class="textwidget">';
		$after_widget='</div>';

		$output = '';

		$output .= $before_widget;
		$output .= $before_title;
		$output .= $trebimeteotitle;
		$output .= $after_title;

		$query = [];

		$default_iframe_attr = [
			'src'			=> '',
			'width'			=> $trebilarghezza ?? 150,
			'height'		=> $trebialtezza ?? 190,
			'frameborder'	=> 0,
		];

		switch ( $trebitipo ) {

			case 'lsneve':
			case 'treale':
			case 'lsmari':
			case 'xssmall':

				$query = [
					'loc'	=> $trebilocalita,
					'tm'	=> $trebitipo,
					'c1'	=> $trebicuno,
					'b1'	=> $trebibuno,
					'b2'	=> $trebibdue,
				];
				break;
			case 'lbigor':
			case 'lsmall':
				$query = [
					'loc'	=> $trebilocalita,
					'tm'	=> $trebitipo,
					'new'	=> 1,
					'c1'	=> $trebicuno,
					'c2'	=> $trebicdue,
					'b1'	=> $trebibuno,
					'b2'	=> $trebibdue,
					'b3'	=> $trebibtre,
				];
				break;
			case 'ssmall':

				$query = [
					'loc'	=> $trebilocalita,
					'p'		=> 1,
					'tm'	=> $trebitipo,
					'new'	=> 1,
					'c1'	=> $trebicuno,
					'c2'	=> $trebicdue,
					'b1'	=> $trebibuno,
					'b2'	=> $trebibdue,
					'b3'	=> $trebibtre,
				];
				break;
			case 'lbig':

				$query = [
					'loc'	=> $trebilocalita,
					'tm'	=> $trebitipo,
					'new'	=> 1,
					'c1'	=> $trebicuno,
					'c2'	=> $trebicdue,
					'c3'	=> $trebictre,
					'b1'	=> $trebibuno,
					'b2'	=> $trebibdue,
				];
				break;
			case 'oraxora':

				$query = [
					'loc'	=> $trebilocalita,
					'tm'	=> $trebitipo,
					'c1'	=> $trebicuno,
					'c2'	=> $trebicdue,
					'c3'	=> $trebictre,
					'b1'	=> $trebibuno,
					'b2'	=> $trebibdue,
				];
				break;
			case 'lneve':
			case 'lmari':

				$query = [
					'loc'	=> $trebilocalita,
					'tm'	=> $trebitipo,
					'c1'	=> $trebicuno,
					'c3'	=> $trebictre,
					'b1'	=> $trebibuno,
					'b2'	=> $trebibdue,
				];
				break;
			case 'msmall':

				$query = [
					'idreg'	=> $trebiregione,
					'tm'	=> $trebitipo,
					'new'	=> 1,
					'c1'	=> $trebicuno,
					'b1'	=> $trebibuno,
					'b2'	=> $trebibdue,
				];
				break;
			case 'msmacro':

				$query = [
					'idreg'	=> $trebiregione,
					'tm'	=> $trebitipo,
					'c1'	=> $trebicuno,
					'c2'	=> $trebicdue,
					'c3'	=> $trebictre,
					'b1'	=> $trebibuno,
					'b2'	=> $trebibdue,
				];
				break;
			case 'mmari':

				$query = [
					'idreg'	=> $trebiregione,
					'tm'	=> $trebitipo,
					'c1'	=> $trebicuno,
					'c2'	=> $trebicdue,
					'b1'	=> $trebibuno,
					'b2'	=> $trebibdue,
					'c3'	=> $trebictre,
				];
				break;
		}

		$url = new \TreBiMeteo\UrlBuilder( 'https://portali.3bmeteo.com/3bm_meteo.php' );
		$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr,
				[
					'src'	=> (string) $url->query( $query ),
				]
			)
		);

		$output .= $iframe;

		if ( $trebitipo === 'mmari' || $trebitipo === 'msmacro' || $trebitipo === 'msmall' ) {
			$output .= trebi_getUrlRegione($trebiregione);
		} else {
			$output .= trebi_getUrlLoc($trebilocalita);
		}

		$output .= $after_widget;

		echo $output;
	}

	/**
	 * Setup 3B Meteo Widget Backend
	 */
	function WidgetMeteoControl() {

		$options = get_option('widget_trebimeteo');
		if ( ! is_array($options) )	{
			$options = [
				'trebimeteotitle' => 'Previsioni Meteo',
				'trebiregione'		=> '1',
				'trebilocalita'		=> '6',
				'trebicuno'			=> 'ffffff',
				'trebicdue'			=> 'ffffff',
				'trebictre'			=> 'ffffff',
				'trebibuno'			=> '93c1db',
				'trebibdue'			=> '3a8ebd',
				'trebibtre'			=> 'ffffff',
				'trebilarghezza'	=> '190',
				'trebialtezza'		=> '240',
				'trebitipo'			=> 'xssmall'
			];
		}

		if ( isset( $_POST['trebimeteo-submit'] ) && $_POST['trebimeteo-submit'] ) {
			// Remember to sanitize and format use input appropriately.
			$options['trebimeteotitle'] = strip_tags(stripslashes($_POST['trebimeteotitle']));
			$options['trebiregione'] = strip_tags(stripslashes($_POST['trebiregione']));
			$options['trebilocalita'] = strip_tags(stripslashes($_POST['trebilocalita']));
			$options['trebicuno'] = strip_tags(stripslashes($_POST['trebicuno']));
			$options['trebicdue'] = strip_tags(stripslashes($_POST['trebicdue']));
			$options['trebictre'] = strip_tags(stripslashes($_POST['trebictre']));
			$options['trebibuno'] = strip_tags(stripslashes($_POST['trebibuno']));
			$options['trebibdue'] = strip_tags(stripslashes($_POST['trebibdue']));
			$options['trebibtre'] = strip_tags(stripslashes($_POST['trebibtre']));
			$options['trebialtezza'] = strip_tags(stripslashes($_POST['trebialtezza']));
			$options['trebilarghezza'] = strip_tags(stripslashes($_POST['trebilarghezza']));
			$options['trebitipo'] = strip_tags(stripslashes($_POST['trebitipo']));

			update_option('widget_trebimeteo', $options);
		}

		$trebimeteotitle = htmlspecialchars($options['trebimeteotitle'], ENT_QUOTES);
		$trebiregione = htmlspecialchars($options['trebiregione'], ENT_QUOTES);
		$trebilocalita = htmlspecialchars($options['trebilocalita'], ENT_QUOTES);
		$trebicuno = htmlspecialchars($options['trebicuno'], ENT_QUOTES);
		$trebicdue = htmlspecialchars($options['trebicdue'], ENT_QUOTES);
		$trebictre = htmlspecialchars($options['trebictre'], ENT_QUOTES);
		$trebibuno = htmlspecialchars($options['trebibuno'], ENT_QUOTES);
		$trebibdue = htmlspecialchars($options['trebibdue'], ENT_QUOTES);
		$trebibtre = htmlspecialchars($options['trebibtre'], ENT_QUOTES);
		$trebialtezza = htmlspecialchars($options['trebialtezza'], ENT_QUOTES);
		$trebilarghezza = htmlspecialchars($options['trebilarghezza'], ENT_QUOTES);
		$trebitipo = htmlspecialchars($options['trebitipo'], ENT_QUOTES);

		$xpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

		?>

		<p style="text-align:right;"><label for="trebimeteo-title">Titolo <input id="trebimeteotitle" name="trebimeteotitle" type="text" value="<?php echo $trebimeteotitle;?>" /></label></p>
		<p style="text-align:right;"><label for="trebiregione">ID Regione (<a target="_blank" href="<?php echo home_url().'/wp-admin/options-general.php?page=3bmeteo.php'; ?>">?</a>) <input id="trebiregione" name="trebiregione" type="text" value="<?php echo $trebiregione;?>"/></label></p>
		<p style="text-align:right;"><label for="trebilocalita">ID Localit&agrave; (<a target="_blank" href="<?php echo home_url().'/wp-admin/options-general.php?page=3bmeteo.php';?>">?</a>) <input id="trebilocalita" name="trebilocalita" type="text" value="<?php echo $trebilocalita;?>"/></label></p>
		<p style="text-align:right;"><label for="trebialtezza">Altezza <input id="trebialtezza" name="trebialtezza" type="text" value="<?php echo $trebialtezza;?>"/></label></p>
		<p style="text-align:right;"><label for="trebilarghezza">Larghezza <input id="trebilarghezza" name="trebilarghezza" type="text" value="<?php echo $trebilarghezza;?>"/></label></p>
		<p style="text-align:right;"><label for="trebibuno">B1 <input id="trebibuno" name="trebibuno" type="text" value="<?php echo $trebibuno;?>"/></label></p>
		<p style="text-align:right;"><label for="trebibdue">B2 <input id="trebibdue" name="trebibdue" type="text" value="<?php echo $trebibdue;?>"/></label></p>
		<p style="text-align:right;"><label for="trebibtre">B3 <input id="trebibtre" name="trebibtre" type="text" value="<?php echo $trebibtre;?>"/></label></p>
		<p style="text-align:right;"><label for="trebicuno">C1 <input id="trebicuno" name="trebicuno" type="text" value="<?php echo $trebicuno;?>"/></label></p>
		<p style="text-align:right;"><label for="trebicdue">C2 <input id="trebicdue" name="trebicdue" type="text" value="<?php echo $trebicdue;?>"/></label></p>
		<p style="text-align:right;"><label for="trebictre">C3 <input id="trebictre" name="trebictre" type="text" value="<?php echo $trebictre;?>"/></label></p>
		<p style="text-align:right;"><label for="trebitipo">Tipo
				<select id="trebitipo" name="trebitipo">
					<option label="Localit&agrave; Compatti 1 giorno" value="xssmall"<?php if ($trebitipo=='xssmall') echo ' selected="selected"';?>>Localit&agrave; Compatti 1 giorno</option>
					<option label="Localit&agrave; Compatti 6 giorni" value="lsmall"<?php if ($trebitipo=='lsmall') echo ' selected="selected"';?>>Localit&agrave; Compatti 6 giorni</option>
					<option label="Localit&agrave; Compatti 7 giorni" value="lbigor"<?php if ($trebitipo=='lbigor') echo ' selected="selected"';?>>Localit&agrave; Compatti 7 giorni</option>
					<option label="Localit&agrave; Dati in Diretta" value="treale"<?php if ($trebitipo=='treale') echo ' selected="selected"';?>>Localit&agrave; Dati in Diretta</option>
					<option label="Tutte le Localit&agrave;" value="ssmall"<?php if ($trebitipo=='ssmall') echo ' selected="selected"';?>>Tutte le Localit&agrave;</option>
					<option label="Localit&agrave; estese 7 giorni" value="lbig"<?php if ($trebitipo=='lbig') echo ' selected="selected"';?>>Localit&agrave; estese 7 giorni</option>
					<option label="Localit&agrave; estese orario" value="oraxora"<?php if ($trebitipo=='oraxora') echo ' selected="selected"';?>>Localit&agrave; estese orario</option>
					<option label="Regionali compatto" value="msmall"<?php if ($trebitipo=='msmall') echo ' selected="selected"';?>>Regionali compatto</option>
					<option label="Regionali 7 giorni" value="msmacro"<?php if ($trebitipo=='msmacro') echo ' selected="selected"';?>>Regionali 7 giorni</option>
					<option label="Localit&agrave; Marine 1 giorno" value="lsmari"<?php if ($trebitipo=='lsmari') echo ' selected="selected"';?>>Localit&agrave; Marine 1 giorno</option>
					<option label="Localit&agrave; Marine 7 giorno" value="lmari"<?php if ($trebitipo=='lmari') echo ' selected="selected"';?>>Localit&agrave; Marine 7 giorno</option>
					<option label="Regionali Marine 7 giorni" value="mmari"<?php if ($trebitipo=='mmari') echo ' selected="selected"';?>>Regionali Marine 7 giorni</option>
					<option label="Neve 1 giorno" value="lsneve"<?php if ($trebitipo=='lsneve') echo ' selected="selected"';?>>Neve 1 giorno</option>
					<option label="Neve 7 giorni" value="lneve"<?php if ($trebitipo=='lneve') echo ' selected="selected"';?>>Neve 7 giorni</option>
				</select>
			</label>
		</p>
		<!--<p style="text-align:right;">
	<select id="trebimeteoregione" name="trebimeteoregione">
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
	</p>-->
		<!--  <p style="text-align:right;">
	<select name="trebimeteolocalita" id="trebimeteolocalita">
		<option label="Seleziona Localit&agrave;" value="01">Seleziona Localit&agrave;</option>
	</select>
    <p style="text-align:right;"><label for="trebiloc">Localit&agrave; <input style="width: 200px;" id="trebiloc" name="trebiloc" type="text" value="<?php //echo $loc;?>" /></label></p>-->
		<p><input type="hidden" id="trebimeteo-submit" name="trebimeteo-submit" value="1" /></p>
		<?php
	}

	private function config() {
		return [
			'trebi-a1' => 'xssmall',
			'trebi-a2' => 'lsmall',
			'trebi-a3' => 'lbigor',
			'trebi-b1' => 'treale',
			'trebi-b2' => 'ssmall',
			'trebi-c1' => 'lbig',
			'trebi-c2' => 'oraxora',
			'trebi-d1' => 'msmall',
			'trebi-d2' => 'msmacro',
			'trebi-e1' => 'lsmari',
			'trebi-e2' => 'lmari',
			'trebi-f1' => 'mmari',
			'trebi-g1' => 'lsneve',
			'trebi-g2' => 'lneve',
		];
	}

	private function get( $key, $default = '' ) {
		$config = $this->config();

		return $config[ $key ] ?? $default;
	}

	/**
	 * Handler for 3B Meteo Widget Shortcode
	 * $atts    ::= array of attributes
	 * $content ::= text within enclosing form of shortcode element
	 * $code    ::= the shortcode found, when == callback name
	 * examples: [my-shortcode]
	 *           [my-shortcode/]
	 *			 [my-shortcode foo='bar']
	 *           [my-shortcode foo='bar'/]
	 *           [my-shortcode]content[/my-shortcode]
	 *           [my-shortcode foo='bar']content[/my-shortcode]
	 *
	 * @return string
	 */
	function trebi_shortcode_handler( $atts, $content = null, $code = "" ) {
		$type = $this->get( $code );

		$url = new \TreBiMeteo\UrlBuilder( 'https://portali.3bmeteo.com/3bm_meteo.php' );

		$default_iframe_attr = [
			'src'			=> '',
			'width'			=> $atts['width'] ?? 150,
			'height'		=> $atts['height'] ?? 190,
			'frameborder'	=> 0,
		];

		switch ( $type ) {

			case 'xssmall':

				$default = [
					'loc' => '6',
					'tm' => 'xssmall',
					'c1' => 'ffffff',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
				];

				$new_atts = shortcode_atts( $default, $atts, $code );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
					'src'			=> (string) $url->query( $new_atts ),
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'lsmall':

				$default = [
					'loc'	=> '6',
					'tm'	=> 'lsmall',
					'new'	=> '1',
					'c1'	=> '999999',
					'c2'	=> 'ffffff',
					'b1'	=> '93c1db',
					'b2'	=> 'f0f0f0',
					'b3'	=> '2a7fae',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
					'src'			=> (string) $url->query( $new_atts ),
					'width'			=> 185,
					'height'		=> 330,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'lbigor':

				$default = [
					'loc' => '6',
					'tm' => 'lbigor',
					'new' => '1',
					'c1' => 'ffffff',
					'c2' => '888888',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
					'b3' => 'f0f0f0',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 455,
						'height'		=> 195,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'treale':

				$default = [
						'loc' => '6',
						'tm' => 'treale',
						'c1' => 'ffffff',
						'b1' => '93c1db',
						'b2' => '3a8ebd',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 195,
						'height'		=> 165,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'ssmall':

				$default = [
					'loc' => '6',
					'p'  => '1',
					'tm' => 'ssmall',
					'new' => '1',
					'c1' => 'ffffff',
					'c2' => 'aaaaaa',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
					'b3' => 'f0f0f0',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 245,
						'height'		=> 380,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'lbig':

				$default = [
					'loc' => '6',
					'tm' => 'lbig',
					'new' => '1',
					'c1' => '777777',
					'c2' => 'ffffff',
					'c3' => '93c1db',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 460,
						'height'		=> 380,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'oraxora':

				$default = [
					'loc' => '6',
					'tm' => 'oraxora',
					'c1' => 'ffffff',
					'c2' => '777777',
					'c3' => '93c1db',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 455,
						'height'		=> 500,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'msmall':

				$default = [
					'idreg' => '1',
					'tm' => 'msmall',
					'new' => '1',
					'c1' => 'ffffff',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlRegione( (int) $new_atts['idreg'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 190,
						'height'		=> 300,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'msmacro':

				$default = [
					'idreg' => '1',
					'tm' => 'msmacro',
					'c1' => 'ffffff',
					'c2' => '555555',
					'c3' => '93c1db',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlRegione( (int) $new_atts['idreg'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 660,
						'height'		=> 515,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'lsmari':

				$default = [
					'loc' => '6',
					'tm' => 'lsmari',
					'c1' => 'ffffff',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 190,
						'height'		=> 260,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'lmari':

				$default = [
					'loc' => '6',
					'tm' => 'lmari',
					'c1' => 'ffffff',
					'c3' => '93c1db',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 460,
						'height'		=> 380,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'mmari':

				$default = [
					'idreg' => '1',
					'tm' => 'mmari',
					'c1' => 'ffffff',
					'c2' => '555555',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
					'c3' => '93c1db',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlRegione( (int) $new_atts['idreg'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 650,
						'height'		=> 515,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'lsneve':

				$default = [
					'loc' => '6',
					'tm' => 'lsneve',
					'c1' => 'ffffff',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 210,
						'height'		=> 240,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;

			case 'lneve':

				$default = [
					'loc' => '6',
					'tm' => 'lneve',
					'c1' => 'ffffff',
					'c3' => '93c1db',
					'b1' => '93c1db',
					'b2' => '3a8ebd',
				];

				$new_atts = shortcode_atts( $default, $atts, $code  );
				$wloc = trebi_getUrlLoc( (int) $new_atts['loc'] );

				$iframe_attr = [
						'src'			=> (string) $url->query( $new_atts ),
						'width'			=> 490,
						'height'		=> 420,
				];

				$iframe = new \TreBiMeteo\IFrameBuilder( \array_replace( $default_iframe_attr, $iframe_attr ) );
				return $iframe . $wloc;
		}

		return $this->trebi_shortcode_handler( $atts, $content, 'trebi-a1' );
	}


	//Config page
	function DesignAdminPage() {
		require 'admin-view.php';
	}

	/**
	 * Returns the plugin version
	 *
	 * Uses the WP API to get the meta data from the top of this file (comment)
	 *
	 * @return string The version like 1.0.10
	 */
	function GetVersion() {
		if(!function_exists('get_plugin_data')) {
			if(file_exists(ABSPATH . 'wp-admin/includes/plugin.php')) require_once(ABSPATH . 'wp-admin/includes/plugin.php'); //2.3+
			else if(file_exists(ABSPATH . 'wp-admin/admin-functions.php')) require_once(ABSPATH . 'wp-admin/admin-functions.php'); //2.1
			else return "0.ERROR";
		}
		$data = get_plugin_data(__FILE__);
		return $data['Version'];
	}
}
