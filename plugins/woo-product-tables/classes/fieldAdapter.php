<?php
/**
 * Class to adapt field before display
 * return ONLY htmlParams property
 *
 * @see field
 */
class FieldAdapterWtbp {
	const DB = 'DbWtbp';
	const HTML = 'HtmlWtbp';
	const STR = 'str';
	public static $userfieldDest = array('registration', 'shipping', 'billing');
	public static $countries = array();
	public static $states = array();
	/**
	 * Executes field Adaption process
	 *
	 * @param object type field or value $fieldOrValue if DB adaption - this must be a value of field, elase if html - field object
	 */
	public static function _( $fieldOrValue, $method, $type ) {
		if (method_exists('FieldAdapterWtbp', $method)) {
			switch ($type) {
				case self::DB:
					return self::$method($fieldOrValue);
					break;
				case self::HTML:
					self::$method($fieldOrValue);
					break;
				case self::STR:
					return self::$method($fieldOrValue);
					break;
			}
		}
		return $fieldOrValue;
	}
	public static function userFieldDestHtml( $field ) {
		$field->htmlParams['OptionsWtbp'] = array();
		if (!is_array($field->value)) {
			if (empty($field->value)) {
				$field->value = array();
			} else {
				$field->value = json_decode($field->value);
			}
		}
		foreach (self::$userfieldDest as $d) {
			$field->htmlParams['OptionsWtbp'][] = array(
				'id' => $d,
				'text' => $d,
				'checked' => in_array($d, $field->value)
			);
		}
	}
	public static function userFieldDestToDB( $value ) {
		return UtilsWtbp::jsonEncode($value);
	}
	public static function userFieldDestFromDB( $value ) {
		return UtilsWtbp::jsonDecode($value);
	}
	public static function taxDataHtml( $field ) {
		$listOfDest = array();
		if (!is_array($field->value)) {
			if (empty($field->value)) {
				$field->value = array();
			} else {
				$field->value = (array) json_decode($field->value, true);
			}
		}
		foreach (self::$userfieldDest as $d) {
			$listOfDest[] = array(
				'id' => $d,
				'text' => $d,
				'checked' => ( is_array($field->value['dest']) && in_array($d, $field->value['dest']) )
			);
		}
		$categories = FrameWtbp::_()->getModule('products')->getCategories();
		$brands = FrameWtbp::_()->getModule('products')->getBrands();
		$cOptions = array();
		$bOptions = array();
		if (!empty($categories)) {
			if (!is_array($field->value['categories'])) {
				$field->value['categories'] = array();
			}
			foreach ($categories as $c) {
				$cOptions[] = array('id' => $c->term_taxonomy_id, 
					'text' => $c->cat_name,
					'checked' => in_array($c->term_taxonomy_id, $field->value['categories']));
			}
		}
		if (!empty($brands)) {
			if (!is_array($field->value['brands'])) {
				$field->value['brands'] = array();
			}
			foreach ($brands as $b) {
				$bOptions[] = array('id' => $b->term_taxonomy_id, 
					'text' => $b->cat_name,
					'checked' => in_array($b->term_taxonomy_id, $field->value['brands']));
			}
		}
		return '<div>' . esc_html__('Apply To', 'woo-product-tables') . '
			<div id="tax_address">
				<b>' . esc_html__('Address', 'woo-product-tables') . '</b><br />
				' . esc_html__('Destination', 'woo-product-tables') . ':' . HtmlWtbp::checkboxlist('params[dest]', array('OptionsWtbp' => $listOfDest)) . '<br />
				' . esc_html__('Country', 'woo-product-tables') . ':' . HtmlWtbp::countryList('params[country]', array('notSelected' => true, 'value' => $field->value['country'])) . '<br />
			</div>
			<div id="tax_category">
				<b>' . esc_html__('Categories', 'woo-product-tables') . '</b><br />
				' . ( empty($cOptions) ? esc_html__('You have no categories', 'woo-product-tables') : HtmlWtbp::checkboxlist( 'params[categories][]', array('OptionsWtbp' => $cOptions) ) ) . '<br />
					<b>' . esc_html__('Brands', 'woo-product-tables') . '</b><br />
				' . ( empty($bOptions) ? esc_html__('You have no brands', 'woo-product-tables') : HtmlWtbp::checkboxlist( 'params[brands][]', array('OptionsWtbp' => $bOptions) ) ) . '<br />
			</div>
			<div>' . esc_html__('Tax Rate', 'woo-product-tables') . ': ' . HtmlWtbp::text('params[rate]', array('value' => $field->value['rate'])) . '</div>
			<div>' . esc_html__('Absolute', 'woo-product-tables') . ': ' . HtmlWtbp::checkbox('params[absolute]', array('checked' => $field->value['absolute'])) . '</div>
		</div>';
	}
	public static function displayCountry( $cid, $key = 'name' ) {
		if ('name' == $key) {
			$countries = self::getCountries();
			return $countries[$cid];
		} else {
			if (empty(self::$countries)) {
				self::$countries = self::getCachedCountries();
			}
			foreach (self::$countries as $c) {
				if ($c['id'] == $cid) {
					return $c[ $key ];
				}
			}
		}
		return false;
	}
	public static function displayState( $sid, $key = 'name' ) {
		$states = self::getStates();
		return empty($states[$sid]) ? $sid : $states[$sid][$key];
	}
	public static function getCountries( $notSelected = false ) {
		static $options = array();
		if (empty($options[ $notSelected ])) {
			$options[ $notSelected ] = array();
			if (empty(self::$countries)) {
				self::$countries = self::getCachedCountries();
			}
			if ($notSelected) {
				$options[ $notSelected ][0] = is_bool($notSelected) ? esc_html__('Not selected', 'woo-product-tables') : esc_html($notSelected);
			}
			foreach (self::$countries as $c) {
				$options[ $notSelected ][$c['id']] = $c['name'];
			}
		}
		return $options[ $notSelected ];
	}
	public static function getStates( $notSelected = false ) {
		static $options = array();
		if (empty($options[ $notSelected ])) {
			$options[ $notSelected ] = array();
			if (empty(self::$states)) {
				self::$states = self::getCachedStates();
			}
			if ($notSelected) {
				$notSelectedLabel = is_bool($notSelected) ? esc_html__('Not selected', 'woo-product-tables') : $notSelected;
				$options[ $notSelected ][0] = array('name' => $notSelectedLabel, 'country_id' => null);
			}
			foreach (self::$states as $s) {
				$options[ $notSelected ][$s['id']] = $s;
			}
		}
		return $options[ $notSelected ];
	}
	/**
	 * Function to get extra field options 
	 * 
	 * @param object $field
	 * @return string 
	 */
	public static function getExtraFieldOptions( $field_id ) {
		$output = '';
		if (0 == $field_id) {
			return '';
		}
		$options = FrameWtbp::_()->getModule('OptionsWtbp')->getHelper()->getOptions($field_id);
		if (!empty($options)) {
			foreach ($options as $key=>$value) {
				$output .= '<p>' . $value . '<span class="delete_option" rel="' . esc_attr($key) . '"></span></p>';
			}
		}
		return $output;
	}
	/**
	 * Function to get field params
	 * 
	 * @param object $params 
	 */
	public static function getFieldAttributes( $params ) {
		$output = '';
		if (!empty($params->attr)) {
			foreach ($params->attr as $key => $value) {
				$output .= esc_html__($key) . ':<br />';
				$output .= HtmlWtbp::text('params[attr][' . $key . ']', array('value' => $value)) . '<br />';
			}
		} else {
			$output .= esc_html__('class', 'woo-product-tables') . ':<br />';
			$output .= HtmlWtbp::text('params[attr][class]', array('value' => '')) . '<br />';
			$output .= esc_html__('id', 'woo-product-tables') . ':<br />';
			$output .= HtmlWtbp::text('params[attr][id]', array('value' => '')) . '<br />';
		}
		return $output;
	}
	/**
	 * Generating the list of categories for product extra fields
	 * 
	 * @param object $field 
	 */
	public static function productFieldCategories( $field ) {
		if (!empty($field->htmlParams['OptionsWtbp'])) {
			return;
		}
	}
	public static function intToDB( $val ) {
		return intval($val);
	}
	public static function floatToDB( $val ) {
		return floatval($val);
	}
	/**
	 * Save this in static var - to futher usage
	 *
	 * @return array with countries
	 */
	public static function getCachedCountries( $clearCache = false ) {
		if (empty(self::$countries) || $clearCache) {
			self::$countries = FrameWtbp::_()->getTable('countries')->getAll('id, name, iso_code_2, iso_code_3');
		}
		return self::$countries;
	}
	/**
	 * Save this in static var - to futher usage
	 *
	 * @return array with states
	 */
	public static function getCachedStates( $clearCache = false ) {
		if (empty(self::$states) || $clearCache) {
			self::$states = FrameWtbp::_()->getTable('states')
				->leftJoin( FrameWtbp::_()->getTable('countries'), 'country_id' )
				->getAll('toe_states.id,
					toe_states.name, 
					toe_states.code, 
					toe_states.country_id, 
					toe_cry.name AS c_name,
					toe_cry.iso_code_2 AS c_iso_code_2, 
					toe_cry.iso_code_3 AS c_iso_code_3');
		}
		return self::$states;
	}
	public static function getFontsList() {
		return array('Abel', 'Abril Fatface', 'Aclonica', 'Acme', 'Actor', 'Adamina', 'Advent Pro',
			'Aguafina Script', 'Aladin', 'Aldrich', 'Alegreya', 'Alegreya SC', 'Alex Brush', 'Alfa Slab One', 'Alice',
			'Alike', 'Alike Angular', 'Allan', 'Allerta', 'Allerta Stencil', 'Allura', 'Almendra', 'Almendra SC', 'Amaranth',
			'Amatic SC', 'Amethysta', 'Andada', 'Andika', 'Angkor', 'Annie Use Your Telescope', 'Anonymous Pro', 'Antic',
			'Antic Didone', 'Antic Slab', 'Anton', 'Arapey', 'Arbutus', 'Architects Daughter', 'Arimo', 'Arizonia', 'Armata',
			'Artifika', 'Arvo', 'Asap', 'Asset', 'Astloch', 'Asul', 'Atomic Age', 'Aubrey', 'Audiowide', 'Average',
			'Averia Gruesa Libre', 'Averia Libre', 'Averia Sans Libre', 'Averia Serif Libre', 'Bad Script', 'Balthazar',
			'Bangers', 'Basic', 'Battambang', 'Baumans', 'Bayon', 'Belgrano', 'Belleza', 'Bentham', 'Berkshire Swash',
			'Bevan', 'Bigshot One', 'Bilbo', 'Bilbo Swash Caps', 'Bitter', 'Black Ops One', 'Bokor', 'Bonbon', 'Boogaloo',
			'Bowlby One', 'Bowlby One SC', 'Brawler', 'Bree Serif', 'Bubblegum Sans', 'Buda', 'Buenard', 'Butcherman',
			'Butterfly Kids', 'Cabin', 'Cabin Condensed', 'Cabin Sketch', 'Caesar Dressing', 'Cagliostro', 'Calligraffitti',
			'Cambo', 'Candal', 'Cantarell', 'Cantata One', 'Cardo', 'Carme', 'Carter One', 'Caudex', 'Cedarville Cursive',
			'Ceviche One', 'Changa One', 'Chango', 'Chau Philomene One', 'Chelsea Market', 'Chenla', 'Cherry Cream Soda',
			'Chewy', 'Chicle', 'Chivo', 'Coda', 'Coda Caption', 'Codystar', 'Comfortaa', 'Coming Soon', 'Concert One',
			'Condiment', 'Content', 'Contrail One', 'Convergence', 'Cookie', 'Copse', 'Corben', 'Cousine', 'Coustard',
			'Covered By Your Grace', 'Crafty Girls', 'Creepster', 'Crete Round', 'Crimson Text', 'Crushed', 'Cuprum', 'Cutive',
			'Damion', 'Dancing Script', 'Dangrek', 'Dawning of a New Day', 'Days One', 'Delius', 'Delius Swash Caps', 
			'Delius Unicase', 'Della Respira', 'Devonshire', 'Didact Gothic', 'Diplomata', 'Diplomata SC', 'Doppio One', 
			'Dorsa', 'Dosis', 'Dr Sugiyama', 'Droid Sans', 'Droid Sans Mono', 'Droid Serif', 'Duru Sans', 'Dynalight',
			'EB Garamond', 'Eater', 'Economica', 'Electrolize', 'Emblema One', 'Emilys Candy', 'Engagement', 'Enriqueta',
			'Erica One', 'Esteban', 'Euphoria Script', 'Ewert', 'Exo', 'Expletus Sans', 'Fanwood Text', 'Fascinate', 'Fascinate Inline',
			'Federant', 'Federo', 'Felipa', 'Fjord One', 'Flamenco', 'Flavors', 'Fondamento', 'Fontdiner Swanky', 'Forum',
			'Francois One', 'Fredericka the Great', 'Fredoka One', 'Freehand', 'Fresca', 'Frijole', 'Fugaz One', 'GFS Didot',
			'GFS Neohellenic', 'Galdeano', 'Gentium Basic', 'Gentium Book Basic', 'Geo', 'Geostar', 'Geostar Fill', 'Germania One',
			'Give You Glory', 'Glass Antiqua', 'Glegoo', 'Gloria Hallelujah', 'Goblin One', 'Gochi Hand', 'Gorditas',
			'Goudy Bookletter 1911', 'Graduate', 'Gravitas One', 'Great Vibes', 'Gruppo', 'Gudea', 'Habibi', 'Hammersmith One',
			'Handlee', 'Hanuman', 'Happy Monkey', 'Henny Penny', 'Herr Von Muellerhoff', 'Holtwood One SC', 'Homemade Apple',
			'Homenaje', 'IM Fell DW Pica', 'IM Fell DW Pica SC', 'IM Fell Double Pica', 'IM Fell Double Pica SC',
			'IM Fell English', 'IM Fell English SC', 'IM Fell French Canon', 'IM Fell French Canon SC', 'IM Fell Great Primer',
			'IM Fell Great Primer SC', 'Iceberg', 'Iceland', 'Imprima', 'Inconsolata', 'Inder', 'Indie Flower', 'Inika',
			'Irish Grover', 'Istok Web', 'Italiana', 'Italianno', 'Jim Nightshade', 'Jockey One', 'Jolly Lodger', 'Josefin Sans',
			'Josefin Slab', 'Judson', 'Julee', 'Junge', 'Jura', 'Just Another Hand', 'Just Me Again Down Here', 'Kameron',
			'Karla', 'Kaushan Script', 'Kelly Slab', 'Kenia', 'Khmer', 'Knewave', 'Kotta One', 'Koulen', 'Kranky', 'Kreon',
			'Kristi', 'Krona One', 'La Belle Aurore', 'Lancelot', 'Lato', 'League Script', 'Leckerli One', 'Ledger', 'Lekton',
			'Lemon', 'Lilita One', 'Limelight', 'Linden Hill', 'Lobster', 'Lobster Two', 'Londrina Outline', 'Londrina Shadow',
			'Londrina Sketch', 'Londrina Solid', 'Lora', 'Love Ya Like A Sister', 'Loved by the King', 'Lovers Quarrel',
			'Luckiest Guy', 'Lusitana', 'Lustria', 'Macondo', 'Macondo Swash Caps', 'Magra', 'Maiden Orange', 'Mako', 'Marck Script',
			'Marko One', 'Marmelad', 'Marvel', 'Mate', 'Mate SC', 'Maven Pro', 'Meddon', 'MedievalSharp', 'Medula One', 'Merriweather',
			'Metal', 'Metamorphous', 'Michroma', 'Miltonian', 'Miltonian Tattoo', 'Miniver', 'Miss Fajardose', 'Modern Antiqua',
			'Molengo', 'Monofett', 'Monoton', 'Monsieur La Doulaise', 'Montaga', 'Montez', 'Montserrat', 'Moul', 'Moulpali',
			'Mountains of Christmas', 'Mr Bedfort', 'Mr Dafoe', 'Mr De Haviland', 'Mrs Saint Delafield', 'Mrs Sheppards',
			'Muli', 'Mystery Quest', 'Neucha', 'Neuton', 'News Cycle', 'Niconne', 'Nixie One', 'Nobile', 'Nokora', 'Norican',
			'Nosifer', 'Nothing You Could Do', 'Noticia Text', 'Nova Cut', 'Nova Flat', 'Nova Mono', 'Nova Oval', 'Nova Round',
			'Nova Script', 'Nova Slim', 'Nova Square', 'Numans', 'Nunito', 'Odor Mean Chey', 'Old Standard TT', 'Oldenburg',
			'Oleo Script', 'Open Sans', 'Open Sans Condensed', 'Orbitron', 'Original Surfer', 'Oswald', 'Over the Rainbow',
			'Overlock', 'Overlock SC', 'Ovo', 'Oxygen', 'PT Mono', 'PT Sans', 'PT Sans Caption', 'PT Sans Narrow', 'PT Serif',
			'PT Serif Caption', 'Pacifico', 'Parisienne', 'Passero One', 'Passion One', 'Patrick Hand', 'Patua One', 'Paytone One',
			'Permanent Marker', 'Petrona', 'Philosopher', 'Piedra', 'Pinyon Script', 'Plaster', 'Play', 'Playball', 'Playfair Display',
			'Podkova', 'Poiret One', 'Poller One', 'Poly', 'Pompiere', 'Pontano Sans', 'Port Lligat Sans', 'Port Lligat Slab',
			'Prata', 'Preahvihear', 'Press Start 2P', 'Princess Sofia', 'Prociono', 'Prosto One', 'Puritan', 'Quantico',
			'Quattrocento', 'Quattrocento Sans', 'Questrial', 'Quicksand', 'Qwigley', 'Radley', 'Raleway', 'Rammetto One',
			'Rancho', 'Rationale', 'Redressed', 'Reenie Beanie', 'Revalia', 'Ribeye', 'Ribeye Marrow', 'Righteous', 'Rochester',
			'Rock Salt', 'Rokkitt', 'Ropa Sans', 'Rosario', 'Rosarivo', 'Rouge Script', 'Ruda', 'Ruge Boogie', 'Ruluko',
			'Ruslan Display', 'Russo One', 'Ruthie', 'Sail', 'Salsa', 'Sancreek', 'Sansita One', 'Sarina', 'Satisfy', 'Schoolbell',
			'Seaweed Script', 'Sevillana', 'Shadows Into Light', 'Shadows Into Light Two', 'Shanti', 'Share', 'Shojumaru',
			'Short Stack', 'Siemreap', 'Sigmar One', 'Signika', 'Signika Negative', 'Simonetta', 'Sirin Stencil', 'Six Caps',
			'Slackey', 'Smokum', 'Smythe', 'Sniglet', 'Snippet', 'Sofia', 'Sonsie One', 'Sorts Mill Goudy', 'Special Elite',
			'Spicy Rice', 'Spinnaker', 'Spirax', 'Squada One', 'Stardos Stencil', 'Stint Ultra Condensed', 'Stint Ultra Expanded',
			'Stoke', 'Sue Ellen Francisco', 'Sunshiney', 'Supermercado One', 'Suwannaphum', 'Swanky and Moo Moo', 'Syncopate',
			'Tangerine', 'Taprom', 'Telex', 'Tenor Sans', 'The Girl Next Door', 'Tienne', 'Tinos', 'Titan One', 'Trade Winds',
			'Trocchi', 'Trochut', 'Trykker', 'Tulpen One', 'Ubuntu', 'Ubuntu Condensed', 'Ubuntu Mono', 'Ultra', 'Uncial Antiqua',
			'UnifrakturCook', 'UnifrakturMaguntia', 'Unkempt', 'Unlock', 'Unna', 'VT323', 'Varela', 'Varela Round', 'Vast Shadow',
			'Vibur', 'Vidaloka', 'Viga', 'Voces', 'Volkhov', 'Vollkorn', 'Voltaire', 'Waiting for the Sunrise', 'Wallpoet',
			'Walter Turncoat', 'Wellfleet', 'Wire One', 'Yanone Kaffeesatz', 'Yellowtail', 'Yeseva One', 'Yesteryear', 'Zeyada'
		);
	}
}
