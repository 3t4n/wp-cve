<?php
	if(!current_user_can('manage_options'))
	{
		die('Access Denied');
	}
	require_once(dirname(__FILE__) . '/Total-Soft-Portfolio-Install.php');
	require_once(dirname(__FILE__) . '/Total-Soft-Pricing.php');
	global $wpdb;
	wp_enqueue_media();
	wp_enqueue_script( 'custom-header' );
	$table_name2 = $wpdb->prefix . "totalsoft_portfolio_dbt";
	$table_name3 = $wpdb->prefix . "totalsoft_portfolio_id";
	$table_name4 = $wpdb->prefix . "totalsoft_portfolio_manager";
	$table_name5 = $wpdb->prefix . "totalsoft_portfolio_albums";
	$table_name6 = $wpdb->prefix . "totalsoft_portfolio_images";
	if($_SERVER["REQUEST_METHOD"]=="POST") {
		if(check_admin_referer( 'ts_pg_nonce', 'ts_pg_nonce_field' )) {
			$TotalSoftPortfolio_Title = sanitize_text_field($_POST['TotalSoftPortfolio_Title']);
			$TotalSoftPortfolio_Option = sanitize_text_field($_POST['TotalSoftPortfolio_Option']);
			$TotalSoftPortfolio_AlbumCount = sanitize_text_field($_POST['TotalSoftPortfolio_AlbumCount']);
			$TotalSoftPortfolio_ATitle = array();
			$TotalSoftPortfolio_IT = array();
			$TotalSoftPortfolio_IA = array();
			$TotalSoftPortfolio_IURL = array();
			$TotalSoftPortfolio_IDesc = array();
			$TotalSoftPortfolio_ILink = array();
			$TotalSoftPortfolio_IONT = array();
			for($i=1;$i<=$TotalSoftPortfolio_AlbumCount;$i++)
			{
				$TotalSoftPortfolio_ATitle[$i] = str_replace("\&","&", sanitize_text_field(esc_html($_POST['TotalSoftPortfolio_ATitle' . $i])));
			}
			$TotalSoftHidNum = sanitize_text_field($_POST['TotalSoftHidNum']);
			for($j=1;$j<=$TotalSoftHidNum;$j++)
			{
				$TotalSoftPortfolio_IT[$j] = str_replace("\&","&", sanitize_text_field(esc_html($_POST['TotalSoftPortfolio_IT_' . $j])));
				$TotalSoftPortfolio_IA[$j] = sanitize_text_field($_POST['TotalSoftPortfolio_IA_' . $j]);
				$TotalSoftPortfolio_IURL[$j] = sanitize_text_field($_POST['TotalSoftPortfolio_IURL_' . $j]);
				$TotalSoftPortfolio_IDesc[$j] = str_replace("\&","&", sanitize_text_field(esc_html($_POST['TotalSoftPortfolio_IDesc_' . $j])));
				$TotalSoftPortfolio_ILink[$j] = sanitize_text_field($_POST['TotalSoftPortfolio_ILink_' . $j]);
				$TotalSoftPortfolio_IONT[$j] = sanitize_text_field($_POST['TotalSoftPortfolio_IONT_' . $j]);
			}
			if(isset($_POST['Total_Soft_Portfolio_Save']))
			{
				$wpdb->query($wpdb->prepare("INSERT INTO $table_name4 (id, TotalSoftPortfolio_Title, TotalSoftPortfolio_Option, TotalSoftPortfolio_AlbumCount) VALUES (%d, %s, %s, %s)", '', $TotalSoftPortfolio_Title, $TotalSoftPortfolio_Option, $TotalSoftPortfolio_AlbumCount));
				$New_Portfolio_ID = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name3 WHERE id>%d order by id desc limit 1",0));
				$New_Total_SoftPortID = $New_Portfolio_ID[0]->Portfolio_ID + 1;
				$wpdb->query($wpdb->prepare("INSERT INTO $table_name3 (id, Portfolio_ID) VALUES (%d, %s)", '', $New_Total_SoftPortID));
				for($i=1;$i<=$TotalSoftPortfolio_AlbumCount;$i++)
				{
					$wpdb->query($wpdb->prepare("INSERT INTO $table_name5 (id, TotalSoftPortfolio_ATitle, Portfolio_ID) VALUES (%d, %s, %s)", '', html_entity_decode($TotalSoftPortfolio_ATitle[$i]), $New_Total_SoftPortID));
				}
				for($j=1;$j<=$TotalSoftHidNum;$j++)
				{
					$wpdb->query($wpdb->prepare("INSERT INTO $table_name6 (id, TotalSoftPortfolio_IT, TotalSoftPortfolio_IA, TotalSoftPortfolio_IURL, TotalSoftPortfolio_IDesc, TotalSoftPortfolio_ILink, TotalSoftPortfolio_IONT, Portfolio_ID) VALUES (%d, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_IT[$j], $TotalSoftPortfolio_IA[$j], $TotalSoftPortfolio_IURL[$j], $TotalSoftPortfolio_IDesc[$j], $TotalSoftPortfolio_ILink[$j], $TotalSoftPortfolio_IONT[$j], $New_Total_SoftPortID));
				}
			}
			else if(isset($_POST['Total_Soft_Portfolio_Update']))
			{
				$Total_SoftPortfolio_Update = sanitize_text_field($_POST['Total_SoftPortfolio_Update']);
				$wpdb->query($wpdb->prepare("UPDATE $table_name4 set TotalSoftPortfolio_Title = %s, TotalSoftPortfolio_Option = %s, TotalSoftPortfolio_AlbumCount = %s WHERE id = %d", $TotalSoftPortfolio_Title, $TotalSoftPortfolio_Option, $TotalSoftPortfolio_AlbumCount, $Total_SoftPortfolio_Update));
				$wpdb->query($wpdb->prepare("DELETE FROM $table_name5 WHERE Portfolio_ID = %s", $Total_SoftPortfolio_Update));
				$wpdb->query($wpdb->prepare("DELETE FROM $table_name6 WHERE Portfolio_ID = %s", $Total_SoftPortfolio_Update));
				for($i=1;$i<=$TotalSoftPortfolio_AlbumCount;$i++)
				{
					$wpdb->query($wpdb->prepare("INSERT INTO $table_name5 (id, TotalSoftPortfolio_ATitle, Portfolio_ID) VALUES (%d, %s, %s)", '', html_entity_decode($TotalSoftPortfolio_ATitle[$i]), $Total_SoftPortfolio_Update));
				}
				for($j=1;$j<=$TotalSoftHidNum;$j++)
				{
					$wpdb->query($wpdb->prepare("INSERT INTO $table_name6 (id, TotalSoftPortfolio_IT, TotalSoftPortfolio_IA, TotalSoftPortfolio_IURL, TotalSoftPortfolio_IDesc, TotalSoftPortfolio_ILink, TotalSoftPortfolio_IONT, Portfolio_ID) VALUES (%d, %s, %s, %s, %s, %s, %s, %s)", '', $TotalSoftPortfolio_IT[$j], $TotalSoftPortfolio_IA[$j], $TotalSoftPortfolio_IURL[$j], $TotalSoftPortfolio_IDesc[$j], $TotalSoftPortfolio_ILink[$j], $TotalSoftPortfolio_IONT[$j], $Total_SoftPortfolio_Update));
				}
			}
		} else {
			wp_die('Security check fail'); 
		}
	}
	$TotalSoftPortfolioOptions = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2 WHERE id>%d order by id", 0));
	$TotalSoftPortfolio = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name4 WHERE id>%d order by id", 0));
	$TotalSoftPortfolioShortID = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name3 WHERE id>%d order by id desc limit 1",0));
	wp_register_style('ts-pg-fontawesome-css', plugin_dir_url( __DIR__ ) . 'CSS/totalsoft.css');
	wp_enqueue_style('ts-pg-fontawesome-css');
	wp_register_style('ts-pg-fonts', esc_url('https://fonts.googleapis.com/css?family=ABeeZee|Abel|Abhaya+Libre|Abril+Fatface|Aclonica|Acme|Actor|Adamina|Advent+Pro|Aguafina+Script|Akronim|Aladin|Aldrich|Alef|Alegreya|Alegreya+SC|Alegreya+Sans|Alegreya+Sans+SC|Alex+Brush|Alfa+Slab+One|Alice|Alike|Alike+Angular|Allan|Allerta|Allerta+Stencil|Allura|Almendra|Almendra+Display|Almendra+SC|Amarante|Amaranth|Amatic+SC|Amethysta|Amiko|Amiri|Amita|Anaheim|Andada|Andika|Angkor|Annie+Use+Your+Telescope|Anonymous+Pro|Antic|Antic+Didone|Antic+Slab|Anton|Arapey|Arbutus|Arbutus+Slab|Architects+Daughter|Archivo|Archivo+Black|Archivo+Narrow|Aref+Ruqaa|Arima+Madurai|Arimo|Arizonia|Armata|Arsenal|Artifika|Arvo|Arya|Asap|Asap+Condensed|Asar|Asset|Assistant|Astloch|Asul|Athiti|Atma|Atomic+Age|Aubrey|Audiowide|Autour+One|Average|Average+Sans|Averia+Gruesa+Libre|Averia+Libre|Averia+Sans+Libre|Averia+Serif+Libre|Bad+Script|Bahiana|Baloo|Baloo+Bhai|Baloo+Bhaijaan|Baloo+Bhaina|Baloo+Chettan|Baloo+Da|Baloo+Paaji|Baloo+Tamma|Baloo+Tammudu|Baloo+Thambi|Balthazar|Bangers|Barlow|Barlow+Condensed|Barlow+Semi+Condensed|Barrio|Basic|Battambang|Baumans|Bayon|Belgrano|Bellefair|Belleza|BenchNine|Bentham|Berkshire+Swash|Bevan|Bigelow+Rules|Bigshot+One|Bilbo|Bilbo+Swash+Caps|BioRhyme|BioRhyme+Expanded|Biryani|Bitter|Black+And+White+Picture|Black+Han+Sans|Black+Ops+One|Bokor|Bonbon|Boogaloo|Bowlby+One|Bowlby+One+SC|Brawler|Bree+Serif|Bubblegum+Sans|Bubbler+One|Buda:300|Buenard|Bungee|Bungee+Hairline|Bungee+Inline|Bungee+Outline|Bungee+Shade|Butcherman|Butterfly+Kids|Cabin|Cabin+Condensed|Cabin+Sketch|Caesar+Dressing|Cagliostro|Cairo|Calligraffitti|Cambay|Cambo|Candal|Cantarell|Cantata+One|Cantora+One|Capriola|Cardo|Carme|Carrois+Gothic|Carrois+Gothic+SC|Carter+One|Catamaran|Caudex|Caveat|Caveat+Brush|Cedarville+Cursive|Ceviche+One|Changa|Changa+One|Chango|Chathura|Chau+Philomene+One|Chela+One|Chelsea+Market|Chenla|Cherry+Cream+Soda|Cherry+Swash|Chewy|Chicle|Chivo|Chonburi|Cinzel|Cinzel+Decorative|Clicker+Script|Coda|Coda+Caption:800|Codystar|Coiny|Combo|Comfortaa|Coming+Soon|Concert+One|Condiment|Content|Contrail+One|Convergence|Cookie|Copse|Corben|Cormorant|Cormorant+Garamond|Cormorant+Infant|Cormorant+SC|Cormorant+Unicase|Cormorant+Upright|Courgette|Cousine|Coustard|Covered+By+Your+Grace|Crafty+Girls|Creepster|Crete+Round|Crimson+Text|Croissant+One|Crushed|Cuprum|Cute+Font|Cutive|Cutive+Mono|Damion|Dancing+Script|Dangrek|David+Libre|Dawning+of+a+New+Day|Days+One|Dekko|Delius|Delius+Swash+Caps|Delius+Unicase|Della+Respira|Denk+One|Devonshire|Dhurjati|Didact+Gothic|Diplomata|Diplomata+SC|Do+Hyeon|Dokdo|Domine|Donegal+One|Doppio+One|Dorsa|Dosis|Dr+Sugiyama|Duru+Sans|Dynalight|EB+Garamond|Eagle+Lake|East+Sea+Dokdo|Eater|Economica|Eczar|El+Messiri|Electrolize|Elsie|Elsie+Swash+Caps|Emblema+One|Emilys+Candy|Encode+Sans|Encode+Sans+Condensed|Encode+Sans+Expanded|Encode+Sans+Semi+Condensed|Encode+Sans+Semi+Expanded|Engagement|Englebert|Enriqueta|Erica+One|Esteban|Euphoria+Script|Ewert|Exo|Exo+2|Expletus+Sans|Fanwood+Text|Farsan|Fascinate|Fascinate+Inline|Faster+One|Fasthand|Fauna+One|Faustina|Federant|Federo|Felipa|Fenix|Finger+Paint|Fira+Mono|Fira+Sans|Fira+Sans+Condensed|Fira+Sans+Extra+Condensed|Fjalla+One|Fjord+One|Flamenco|Flavors|Fondamento|Fontdiner+Swanky|Forum|Francois+One|Frank+Ruhl+Libre|Freckle+Face|Fredericka+the+Great|Fredoka+One|Freehand|Fresca|Frijole|Fruktur|Fugaz+One|GFS+Didot|GFS+Neohellenic|Gabriela|Gaegu|Gafata|Galada|Galdeano|Galindo|Gamja+Flower|Gentium+Basic|Gentium+Book+Basic|Geo|Geostar|Geostar+Fill|Germania+One|Gidugu|Gilda+Display|Give+You+Glory|Glass+Antiqua|Glegoo|Gloria+Hallelujah|Goblin+One|Gochi+Hand|Gorditas|Gothic+A1|Goudy+Bookletter+1911|Graduate|Grand+Hotel|Gravitas+One|Great+Vibes|Griffy|Gruppo|Gudea|Gugi|Gurajada|Habibi|Halant|Hammersmith+One|Hanalei|Hanalei+Fill|Handlee|Hanuman|Happy+Monkey|Harmattan|Headland+One|Heebo|Henny+Penny|Herr+Von+Muellerhoff|Hi+Melody|Hind|Hind+Guntur|Hind+Madurai|Hind+Siliguri|Hind+Vadodara|Holtwood+One+SC|Homemade+Apple|Homenaje|IBM+Plex+Mono|IBM+Plex+Sans|IBM+Plex+Sans+Condensed|IBM+Plex+Serif|IM+Fell+DW+Pica|IM+Fell+DW+Pica+SC|IM+Fell+Double+Pica|IM+Fell+Double+Pica+SC|IM+Fell+English|IM+Fell+English+SC|IM+Fell+French+Canon|IM+Fell+French+Canon+SC|IM+Fell+Great+Primer|IM+Fell+Great+Primer+SC|Iceberg|Iceland|Imprima|Inconsolata|Inder|Indie+Flower|Inika|Inknut+Antiqua|Irish+Grover|Istok+Web|Italiana|Italianno|Itim|Jacques+Francois|Jacques+Francois+Shadow|Jaldi|Jim+Nightshade|Jockey+One|Jolly+Lodger|Jomhuria|Josefin+Sans|Josefin+Slab|Joti+One|Jua|Judson|Julee|Julius+Sans+One|Junge|Jura|Just+Another+Hand|Just+Me+Again+Down+Here|Kadwa|Kalam|Kameron|Kanit|Kantumruy|Karla|Karma|Katibeh|Kaushan+Script|Kavivanar|Kavoon|Kdam+Thmor|Keania+One|Kelly+Slab|Kenia|Khand|Khmer|Khula|Kirang+Haerang|Kite+One|Knewave|Kotta+One|Koulen|Kranky|Kreon|Kristi|Krona+One|Kurale|La+Belle+Aurore|Laila|Lakki+Reddy|Lalezar|Lancelot|Lateef|Lato|League+Script|Leckerli+One|Ledger|Lekton|Lemon|Lemonada|Libre+Barcode+128|Libre+Barcode+128+Text|Libre+Barcode+39|Libre+Barcode+39+Extended|Libre+Barcode+39+Extended+Text|Libre+Barcode+39+Text|Libre+Baskerville|Libre+Franklin|Life+Savers|Lilita+One|Lily+Script+One|Limelight|Linden+Hill|Lobster|Lobster+Two|Londrina+Outline|Londrina+Shadow|Londrina+Sketch|Londrina+Solid|Lora|Love+Ya+Like+A+Sister|Loved+by+the+King|Lovers+Quarrel|Luckiest+Guy|Lusitana|Lustria|Macondo|Macondo+Swash+Caps|Mada|Magra|Maiden+Orange|Maitree|Mako|Mallanna|Mandali|Manuale|Marcellus|Marcellus+SC|Marck+Script|Margarine|Marko+One|Marmelad|Martel|Martel+Sans|Marvel|Mate|Mate+SC|Maven+Pro|McLaren|Meddon|MedievalSharp|Medula+One|Meera+Inimai|Megrim|Meie+Script|Merienda|Merienda+One|Merriweather|Merriweather+Sans|Metal|Metal+Mania|Metamorphous|Metrophobic|Michroma|Milonga|Miltonian|Miltonian+Tattoo|Mina|Miniver|Miriam+Libre|Mirza|Miss+Fajardose|Mitr|Modak|Modern+Antiqua|Mogra|Molengo|Molle:400i|Monda|Monofett|Monoton|Monsieur+La+Doulaise|Montaga|Montez|Montserrat|Montserrat+Alternates|Montserrat+Subrayada|Moul|Moulpali|Mountains+of+Christmas|Mouse+Memoirs|Mr+Bedfort|Mr+Dafoe|Mr+De+Haviland|Mrs+Saint+Delafield|Mrs+Sheppards|Mukta|Mukta+Mahee|Mukta+Malar|Mukta+Vaani|Muli|Mystery+Quest|NTR|Nanum+Brush+Script|Nanum+Gothic|Nanum+Gothic+Coding|Nanum+Myeongjo|Nanum+Pen+Script|Neucha|Neuton|New+Rocker|News+Cycle|Niconne|Nixie+One|Nobile|Nokora|Norican|Nosifer|Nothing+You+Could+Do|Noticia+Text|Noto+Sans|Noto+Serif|Nova+Cut|Nova+Flat|Nova+Mono|Nova+Oval|Nova+Round|Nova+Script|Nova+Slim|Nova+Square|Numans|Nunito|Nunito+Sans|Odor+Mean+Chey|Offside|Old+Standard+TT|Oldenburg|Oleo+Script|Oleo+Script+Swash+Caps|Open+Sans|Open+Sans+Condensed:300|Oranienbaum|Orbitron|Oregano|Orienta|Original+Surfer|Oswald|Over+the+Rainbow|Overlock|Overlock+SC|Overpass|Overpass+Mono|Ovo|Oxygen|Oxygen+Mono|PT+Mono|PT+Sans|PT+Sans+Caption|PT+Sans+Narrow|PT+Serif|PT+Serif+Caption|Pacifico|Padauk|Palanquin|Palanquin+Dark|Pangolin|Paprika|Parisienne|Passero+One|Passion+One|Pathway+Gothic+One|Patrick+Hand|Patrick+Hand+SC|Pattaya|Patua+One|Pavanam|Paytone+One|Peddana|Peralta|Permanent+Marker|Petit+Formal+Script|Petrona|Philosopher|Piedra|Pinyon+Script|Pirata+One|Plaster|Play|Playball|Playfair+Display|Playfair+Display+SC|Podkova|Poiret+One|Poller+One|Poly|Pompiere|Pontano+Sans|Poor+Story|Poppins|Port+Lligat+Sans|Port+Lligat+Slab|Pragati+Narrow|Prata|Preahvihear|Press+Start+2P|Pridi|Princess+Sofia|Prociono|Prompt|Prosto+One|Proza+Libre|Puritan|Purple+Purse|Quando|Quantico|Quattrocento|Quattrocento+Sans|Questrial|Quicksand|Quintessential|Qwigley|Racing+Sans+One|Radley|Rajdhani|Rakkas|Raleway|Raleway+Dots|Ramabhadra|Ramaraja|Rambla|Rammetto+One|Ranchers|Rancho|Ranga|Rasa|Rationale|Ravi+Prakash|Redressed|Reem+Kufi|Reenie+Beanie|Revalia|Rhodium+Libre|Ribeye|Ribeye+Marrow|Righteous|Risque|Roboto|Roboto+Condensed|Roboto+Mono|Roboto+Slab|Rochester|Rock+Salt|Rokkitt|Romanesco|Ropa+Sans|Rosario|Rosarivo|Rouge+Script|Rozha+One|Rubik|Rubik+Mono+One|Ruda|Rufina|Ruge+Boogie|Ruluko|Rum+Raisin|Ruslan+Display|Russo+One|Ruthie|Rye|Sacramento|Sahitya|Sail|Saira|Saira+Condensed|Saira+Extra+Condensed|Saira+Semi+Condensed|Salsa|Sanchez|Sancreek|Sansita|Sarala|Sarina|Sarpanch|Satisfy|Scada|Scheherazade|Schoolbell|Scope+One|Seaweed+Script|Secular+One|Sedgwick+Ave|Sedgwick+Ave+Display|Sevillana|Seymour+One|Shadows+Into+Light|Shadows+Into+Light+Two|Shanti|Share|Share+Tech|Share+Tech+Mono|Shojumaru|Short+Stack|Shrikhand|Siemreap|Sigmar+One|Signika|Signika+Negative|Simonetta|Sintony|Sirin+Stencil|Six+Caps|Skranji|Slabo+13px|Slabo+27px|Slackey|Smokum|Smythe|Sniglet|Snippet|Snowburst+One|Sofadi+One|Sofia|Song+Myung|Sonsie+One|Sorts+Mill+Goudy|Source+Code+Pro|Source+Sans+Pro|Source+Serif+Pro|Space+Mono|Special+Elite|Spectral|Spectral+SC|Spicy+Rice|Spinnaker|Spirax|Squada+One|Sree+Krushnadevaraya|Sriracha|Stalemate|Stalinist+One|Stardos+Stencil|Stint+Ultra+Condensed|Stint+Ultra+Expanded|Stoke|Strait|Stylish|Sue+Ellen+Francisco|Suez+One|Sumana|Sunflower:300|Sunshiney|Supermercado+One|Sura|Suranna|Suravaram|Suwannaphum|Swanky+and+Moo+Moo|Syncopate|Tajawal|Tangerine|Taprom|Tauri|Taviraj|Teko|Telex|Tenali+Ramakrishna|Tenor+Sans|Text+Me+One|The+Girl+Next+Door|Tienne|Tillana|Timmana|Tinos|Titan+One|Titillium+Web|Trade+Winds|Trirong|Trocchi|Trochut|Trykker|Tulpen+One|Ubuntu|Ubuntu+Condensed|Ubuntu+Mono|Ultra|Uncial+Antiqua|Underdog|Unica+One|UnifrakturCook:700|UnifrakturMaguntia|Unkempt|Unlock|Unna|VT323|Vampiro+One|Varela|Varela+Round|Vast+Shadow|Vesper+Libre|Vibur|Vidaloka|Viga|Voces|Volkhov|Vollkorn|Vollkorn+SC|Voltaire|Waiting+for+the+Sunrise|Wallpoet|Walter+Turncoat|Warnes|Wellfleet|Wendy+One|Wire+One|Work+Sans|Yanone+Kaffeesatz|Yantramanav|Yatra+One|Yellowtail|Yeon+Sung|Yeseva+One|Yesteryear|Yrsa|Zeyada|Zilla+Slab|Zilla+Slab+Highlight'));
	wp_enqueue_style('ts-pg-fonts');
	wp_register_script('ts-pg-tinymce',esc_url(plugin_dir_url( __DIR__ ) . 'JS/tinymce.min.js'),array());
	wp_register_script('ts-pg-jquery-tinymce',esc_url(plugin_dir_url( __DIR__ ) . 'JS/jquery.tinymce.min.js'),array('jquery'));
	wp_enqueue_script('ts-pg-tinymce');
	wp_enqueue_script('ts-pg-jquery-tinymce');
?>
<form method="POST" enctype="multipart/form-data" style="overflow: hidden;">
	<?php wp_nonce_field( 'ts_pg_nonce', 'ts_pg_nonce_field' );?>
	<div class="Total_Soft_Portfolio_AMD">
		<div class="Support_Span">
			<a href="https://wordpress.org/support/plugin/gallery-portfolio/" target="_blank" title="Click Here to Ask">
				<i class="totalsoft totalsoft-comments-o"></i><span style="margin-left:5px;">If you have any questions click here to ask it to our support.</span>
			</a>
		</div>
		<div class="Total_Soft_Portfolio_AMD1"></div>
		<div class="Total_Soft_Portfolio_AMD2">
			<i class="Total_Soft_Help totalsoft totalsoft-question-circle-o" title="Click for Creating New Portfolio"></i>
			<span class="Total_Soft_Portfolio_AMD2_But" onclick="Total_Soft_Portfolio_AMD2_But1(<?php echo esc_js($TotalSoftPortfolioShortID[0]->Portfolio_ID+1);?>)">
				New Portfolio
			</span>
		</div>
		<div class="Total_Soft_Portfolio_AMD3">
			<i class="Total_Soft_Help totalsoft totalsoft-question-circle-o" title="Click for Canceling"></i>
			<span class="Total_Soft_Portfolio_AMD2_But" onclick="TotalSoft_Reload()">
				Cancel
			</span>
			<i class="Total_Soft_Portfolio_Save Total_Soft_Help totalsoft totalsoft-question-circle-o" title="Click for Saving Settings"></i>
			<button type="submit" class="Total_Soft_Portfolio_Save Total_Soft_Portfolio_AMD2_But" name="Total_Soft_Portfolio_Save">
				Save
			</button>
			<i class="Total_Soft_Portfolio_Update Total_Soft_Help totalsoft totalsoft-question-circle-o" title="Click for Updating Settings"></i>
			<button type="submit" class="Total_Soft_Portfolio_Update Total_Soft_Portfolio_AMD2_But" name="Total_Soft_Portfolio_Update">
				Update
			</button>
			<input type="text" style="display:none" name="Total_SoftPortfolio_Update" id="Total_SoftPortfolio_Update">
		</div>
	</div>
	<table class="Total_Soft_PortfolioAMMTable">
		<tr class="Total_Soft_PortfolioTMMTableFR">
			<td>No</td>
			<td>Portfolio Name</td>
			<td>Portfolio Option</td>
			<td>Albums/Images</td>
			<td>Copy</td>
			<td>Edit</td>
			<td>Delete</td>
		</tr>
	</table>
	<table class="Total_Soft_PortfolioAMOTable">
		<?php for($i=0;$i<count($TotalSoftPortfolio);$i++){
			$TotalSoftPortfolioImages = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name6 WHERE Portfolio_ID = %s", $TotalSoftPortfolio[$i]->id));
			?>
			<tr id="Total_Soft_PortfolioAMOTable_tr_<?php echo esc_html($TotalSoftPortfolio[$i]->id);?>">
				<td><?php echo esc_html($i+1);?></td>
				<td><?php echo esc_html($TotalSoftPortfolio[$i]->TotalSoftPortfolio_Title);?></td>
				<td><?php echo esc_html($TotalSoftPortfolio[$i]->TotalSoftPortfolio_Option);?></td>
				<td><?php echo esc_html($TotalSoftPortfolio[$i]->TotalSoftPortfolio_AlbumCount);?>/<?php echo esc_html(count($TotalSoftPortfolioImages));?></td>
				<td><i class="totalsoft totalsoft-file-text" onclick="TotalSoftPortfolio_Clone(<?php echo esc_js($TotalSoftPortfolio[$i]->id);?>)"></i></td>
				<td><i class="totalsoft totalsoft-pencil" onclick="TotalSoftPortfolio_Edit(<?php echo esc_js($TotalSoftPortfolio[$i]->id);?>)"></i></td>
				<td>
					<i class="totalsoft totalsoft-trash" onclick="TotalSoftPortfolio_Del(<?php echo esc_js($TotalSoftPortfolio[$i]->id);?>)"></i>
					<span class="Total_Soft_Portfolio_Del_Span">
						<i class="Total_Soft_Portfolio_Del_Span_Yes totalsoft totalsoft-check" onclick="TotalSoftPortfolio_Del_Yes(<?php echo esc_js($TotalSoftPortfolio[$i]->id);?>)"></i>
						<i class="Total_Soft_Portfolio_Del_Span_No totalsoft totalsoft-times" onclick="TotalSoftPortfolio_Del_No(<?php echo esc_js($TotalSoftPortfolio[$i]->id);?>)"></i>
					</span>
				</td>
			</tr>
		<?php }?>
	</table>
	<div class="Total_Soft_Port_Loading">
		<img src="<?php echo esc_url(plugins_url('../Images/loading.gif',__FILE__));?>">
	</div>
	<table class="Total_Soft_AMShortTable">
		<tr style="text-align:center">
			<td>Shortcode</td>
			<td>Templete Include</td>
		</tr>
		<tr style="text-align:center">
			<td>Copy & paste the shortcode directly into any WordPress post or page.</td>
			<td>Copy & paste this code into a template file to include the portfolio within your theme.</td>
		</tr>
		<tr style="text-align:center">
			<td>
				<span id="Total_Soft_Portfolio_ID"></span>
				<i class="Total_Soft_Help3 totalsoft totalsoft-files-o" title="Click to Copy." onclick="Copy_Shortcode_Port('Total_Soft_Portfolio_ID')"></i>
			</td>
			<td>
				<span id="Total_Soft_Portfolio_TID"></span>
				<i class="Total_Soft_Help3 totalsoft totalsoft-files-o" title="Click to Copy." onclick="Copy_Shortcode_Port('Total_Soft_Portfolio_TID')"></i>
			</td>
		</tr>
	</table>
	<div class="TS_Port_Add_Image_Fixed_div"></div>
	<div class="TS_Port_Add_Image_Absolute_div">
		<div class="TS_Port_Add_Image_Relative_div">
			<table class="TS_Port_Add_Image_Table">
				<tr>
					<td colspan="2">Add Image</td>
				</tr>
				<tr>
					<td>Image Title</td>
					<td>
						<i class="Total_Soft_Help2 totalsoft totalsoft-question-circle-o" title="Name your image , which also will be heading."></i>
						<input type="text" name="TotalSoftPortfolio_ImTitle" id="TotalSoftPortfolio_ImTitle" class="Total_Soft_Select" placeholder=" * Required">
					</td>
				</tr>
				<tr>
					<td>Select Album</td>
					<td>
						<i class="Total_Soft_Help2 totalsoft totalsoft-question-circle-o" title="Select that album in which you want to be your entitled own picture and description."></i>
						<select class="Total_Soft_Select" name="TotalSoftPortfolio_ImAlbum" id="TotalSoftPortfolio_ImAlbum">
							<?php for($i=1;$i<20;$i++){?>
								<option value="<?php echo esc_html($i); ?>" class="TotalSoftPortfolio_ImAlbum" id="TotalSoftPortfolio_ImAlbum_<?php echo esc_html($i); ?>">Album Title <?php echo esc_html($i); ?></option>
							<?php }?>
						</select>
					</td>
				</tr>
				<tr>
					<td>
						<div id="wp-content-media-buttons" class="wp-media-buttons" >
							<a href="#" class="button insert-media add_media" style="border:1px solid #009491; color:#009491; background-color:#f4f4f4" data-editor="TotalSoftPortfolio_ImURL_1" title="Add Image" id="TotalSoftPortfolio_ImURL" onclick="TotalSoftPortfolio_ImURL_Clicked()">
								<span class="wp-media-buttons-icon"></span>Add Image
							</a>
						</div>
						<input type="text" style="display:none;" id="TotalSoftPortfolio_ImURL_1">
					</td>
					<td>
						<i class="Total_Soft_Help2 totalsoft totalsoft-question-circle-o" title="Click to 'Add Image' button to upload your own images."></i>
						<input type="text" id="TotalSoftPortfolio_ImURL_2" class="Total_Soft_Select" readonly>
						<i class="TS_Port_IT_FD totalsoft totalsoft-times" onclick="TS_Port_IT_FD_Clicked('1')" title="Click to reset this field."></i>
					</td>
				</tr>
				<tr>
					<td colspan="2">Image Description <i class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o" title="Write some information about Gallery content."></i></td>
				</tr>
				<tr>
					<td colspan="2">
						<textarea class="Total_Soft_Select Total_Soft_Desc" name="TotalSoftPortfolio_ImDesc" id="TotalSoftPortfolio_ImDesc"></textarea>
					</td>
				</tr>
				<tr>
					<td>Link <i class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o" title="It is instended for referance. Tick for opening the link in the new page or in the same page."></i></td>
					<td>
						<input type="text" name="TotalSoftPortfolio_ImLink" id="TotalSoftPortfolio_ImLink" class="Total_Soft_Select">
						New Tab <input type="checkbox" class="Total_Soft_Check" checked="check" id="TotalSoftPortfolio_ImONT" name="TotalSoftPortfolio_ImONT">
					</td>
				</tr>
				<tr>
					<td colspan="2" style="line-height: 1.6 !important;">
						<span class="Total_Soft_Portfolio_AMD2_But" onclick="TS_Port_Add_Image_Button_Close()">Close</span>
						<span class="Total_Soft_Portfolio_AMD2_But" id="Total_Soft_Portfolio_UpdIm" onclick="Total_Soft_Portfolio_Img_Update()">Update Image</span>
						<span class="Total_Soft_Portfolio_AMD2_But" id="Total_Soft_Portfolio_SavIm" onclick="Total_Soft_Portfolio_Img_Sav()">Save Image</span>
						<span class="Total_Soft_Portfolio_AMD2_But" onclick="Total_Soft_Portfolio_Img_Res()">Reset</span>
						<input type="text" style="display:none;" id="TotalSoftHidNum" name="TotalSoftHidNum" value="0">
						<input type="text" style="display:none;" id="TotalSoftHidUpdate" value="0">
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="TS_Port_AM_Table_Div">
		<table class="Total_Soft_PortfolioAMTable">
			<tr>
				<td>Portfolio Title <i class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o" title="Write your Gallery Portfolio’s name. Every time for create new one , must complete this line."></i></td>
				<td>Portfolio Option <i class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o" title="Select the option that you previously created in General Option section."></i></td>
				<td>Albums Count <i class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o" title="Select for Gallery several Portfolio options will be."></i></td>
			</tr>
			<tr>
				<td><input type="text" name="TotalSoftPortfolio_Title" id="TotalSoftPortfolio_Title" class="Total_Soft_Select" required placeholder=" * Required"></td>
				<td>
					<select class="Total_Soft_Select" name="TotalSoftPortfolio_Option" id="TotalSoftPortfolio_Option">
						<?php for($i=0;$i<count($TotalSoftPortfolioOptions);$i++){?>
							<option value="<?php echo esc_html($TotalSoftPortfolioOptions[$i]->TotalSoftPortfolio_SetName);?>"><?php echo esc_html($TotalSoftPortfolioOptions[$i]->TotalSoftPortfolio_SetName);?></option>
						<?php }?>
					</select>
				</td>
				<td>
					<select name="TotalSoftPortfolio_AlbumCount" id="TotalSoftPortfolio_AlbumCount" onchange="TotalSoftPortfolio_ACount()">
						<?php for($i=1;$i<31;$i++){?>
							<option value="<?php echo esc_html($i);?>"><?php echo esc_html($i);?></option>
						<?php }?>
					</select>
					<input type="text" style="display:none;" id="TotalSoftPortfolio_AlbumCountHid" value="1">
				</td>
			</tr>
			<tr>
				<td colspan="3"></td>
			</tr>
			<tr class="TotalSoftHiddenRows TotalSoftHiddenRowsFirst">
				<td> Album Title 1 <i class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o" title="Write a title of the album. It is also designed for Menu."></i></td>
				<td colspan="2"><input type="text" name="TotalSoftPortfolio_ATitle1" id="TotalSoftPortfolio_ATitle1" class="Total_Soft_Select" placeholder=" * Required"></td>
			</tr>
			<?php for($i=2;$i<31;$i++){?>
				<tr class="TotalSoftHiddenRows" id="TotalSoftHiddenRows_<?php echo esc_html($i);?>">
					<td> Album Title <?php echo esc_html($i);?> <i class="Total_Soft_Help1 totalsoft totalsoft-question-circle-o" title="Write a title of the album. It is also designed for Menu."></i></td>
					<td colspan="2"><input type="text" name="TotalSoftPortfolio_ATitle<?php echo esc_html($i);?>" id="TotalSoftPortfolio_ATitle<?php echo esc_html($i);?>" class="Total_Soft_Select" placeholder=" * Required"></td>
				</tr>
			<?php }?>
			<tr>
				<td colspan="3">
					<div class="TS_Port_AM_ANI">
						<span class="TS_Port_AM_ANI_Span1" onclick="TS_Port_Add_Image_Button()">
							<span class="TS_Port_AM_ANI_Span2">
								<i class="TS_Port_AM_ANI_Icon totalsoft totalsoft-plus-circle" style="margin-right: 5px;"></i>
								Add Image
							</span>
						</span>
					</div>
				</td>
			</tr>
		</table>
	</div>
	<table class="Total_Soft_AMImageTable">
		<tr>
			<td>No</td>
			<td>Image Title</td>
			<td>Image Album</td>
			<td>Image</td>
			<td>Copy</td>
			<td>Edit</td>
			<td class="delete_td" style="display: table-cell;">Delete</td>
		</tr>
	</table>
	<div style="display: none" class="totalsoft_delete_block"><input id="totalsoft_delete_all" type="checkbox" name="totalsoft_delete_all"><input type="button" name="totalsoft_delete_button" value="Delete" id="totalsoft_delete_choose"></div>
	<ul id="TotalSoftPortfolioUl" onclick="TotalSoftPortfolioUlSort()"></ul>
</form>