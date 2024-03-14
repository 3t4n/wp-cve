<?php
// Lancement automatique de la fonction de mise à jour en cas de publication si l'option est validée
if(get_option("wp_excerpt_generator_maj") == true) {
	// Mise à jour des données par défaut
	function WP_Excerpt_Generator_update_auto() {
		global $wpdb, $table_WP_Excerpt_Generator; // insérer les variables globales
    	$editID = get_the_ID();

		// Variables utiles
		$wp_excerpt_generator_method		= get_option("wp_excerpt_generator_method");
		$wp_excerpt_generator_owntag		= get_option("wp_excerpt_generator_owntag");
		$wp_excerpt_generator_nbletters		= get_option("wp_excerpt_generator_nbletters");
		$wp_excerpt_generator_nbwords		= get_option("wp_excerpt_generator_nbwords");
		$wp_excerpt_generator_nbparagraphs	= get_option("wp_excerpt_generator_nbparagraphs");
	
		// Si la chaîne doit se terminer par une ponctuation logique
		if(get_option("wp_excerpt_generator_cleaner") == true) {
			$cleaner = true;
		} else {
			$cleaner = false;
		}
	
		// Si le code HTML est conservé
		if(get_option("wp_excerpt_generator_htmlOK") == 'none') {
			$htmlOK = 'none';
		} else if(get_option("wp_excerpt_generator_htmlOK") == 'partial') {
			$htmlOK = "partial";
		} else if(get_option("wp_excerpt_generator_htmlOK") == 'total') {
			$htmlOK = 'total';
		}
	
		// Si le code HTML est conservé
		if(get_option("wp_excerpt_generator_htmlBR") == true) {
			$htmlBR = true;
		} else {
			$htmlBR = false;
		}
	
		// Si la chaîne doit être terminée par quelques caractères
		if(get_option("wp_excerpt_generator_breakOK") == true) {
			$break = array(true, get_option("wp_excerpt_generator_break"));
		} else {
			$break = array(false, '');
		}

		// Vérifie que l'option "Fin de chaîne"
		if($wp_excerpt_generator_method == "owntag" && is_string($wp_excerpt_generator_owntag) && !empty($wp_excerpt_generator_owntag)) {
			$owntag = $wp_excerpt_generator_owntag;
		} else {
			$owntag = '';
		}

		// Vérifie que l'option "lettres" est activée et qu'un nombre de lettres a été donné...
		if($wp_excerpt_generator_method == "letters" && is_numeric($wp_excerpt_generator_nbletters) && !empty($wp_excerpt_generator_nbletters)) {
			$nbletters = $wp_excerpt_generator_nbletters;
		} else {
			$nbletters = 600;
		}
		
		// Vérifie que l'option "mots" est activée et qu'un nombre de mots a été donné...
		if($wp_excerpt_generator_method == "words" && is_numeric($wp_excerpt_generator_nbwords) && !empty($wp_excerpt_generator_nbwords)) {
			$nbwords = $wp_excerpt_generator_nbwords;
		} else {
			$nbwords = 100;
		}
		
		// Vérifie que l'option "paragraphes" est activée et qu'un nombre de paragraphes a été donné...
		if($wp_excerpt_generator_method == "paragraph" && is_numeric($wp_excerpt_generator_nbparagraphs) && !empty($wp_excerpt_generator_nbparagraphs)) {
			$nbparagraphs = $wp_excerpt_generator_nbparagraphs;
		} else {
			$nbparagraphs = 1;
		}
		
		// Récupère le statut des données dans la base de données
		if(get_option("wp_excerpt_generator_status") == 'publish') {
			$selectContent = "post_status = 'publish'";
		} else if(get_option("wp_excerpt_generator_status") == 'future') {		
			$selectContent = "post_status = 'future'";
		} else if(get_option("wp_excerpt_generator_status") == 'publishfuture') {	
			$selectContent = "(post_status = 'publish' OR post_status = 'future')";
		} else {
			$selectContent = "post_status = 'publish'";
		}
	
		// Récupère le type de contenus pour créer l'extrait et sélectionne des données dans la base de données
		if(get_option("wp_excerpt_generator_type") == 'page') {
			$selectContent = $wpdb->get_results("SELECT ID, post_content FROM $table_WP_Excerpt_Generator WHERE ".$selectContent." AND post_type = 'page' AND ID = '".esc_sql($editID)."'");
		} else if(get_option("wp_excerpt_generator_type") == 'post') {		
			$selectContent = $wpdb->get_results("SELECT ID, post_content FROM $table_WP_Excerpt_Generator WHERE ".$selectContent." AND post_type = 'post' AND ID = '".esc_sql($editID)."'");
		} else if(get_option("wp_excerpt_generator_type") == 'pagepost') {	
			$selectContent = $wpdb->get_results("SELECT ID, post_content FROM $table_WP_Excerpt_Generator WHERE ".$selectContent." AND (post_type = 'page' OR post_type = 'post') AND ID = '".esc_sql($editID)."'");
		}
				
		// Boucle de mise à jour des contenus
		if(!empty($selectContent)) {
			foreach($selectContent as $key => $content) {		
				// On récupère les ID dans un tableau pour la mise à jour et les contenus à traiter
				$ID[] = $content->ID;
				$content = $content->post_content;

				// On supprime les shortcodes si l'option est cochée
				if(get_option("wp_excerpt_generator_delete_shortcode") == true) {
					$regex = "#(\[[^\[\]]+\][ ]?)#i";
					$content = preg_replace($regex, "", $content);
				}

				// On adapte la fonction de formatage en fonction de la méthode utilisée
				if(get_option("wp_excerpt_generator_method") == 'paragraph') {
					$formatText[] = Limit_Paragraph($content, $nbparagraphs, $htmlOK, $htmlBR, $break);
				} else if(get_option("wp_excerpt_generator_method") == 'words') {
					$formatText[] = Limit_Words($content, $nbwords, $htmlOK, $htmlBR, $cleaner, $break);
				} else if(get_option("wp_excerpt_generator_method") == 'letters') {
					$formatText[] = Limit_Letters($content, $nbletters, $htmlOK, $htmlBR, $cleaner, $break);
				} else if(get_option("wp_excerpt_generator_method") == 'moretag') {
					$formatText[] = Limit_More($content, $htmlOK, $htmlBR, $break);
				} else if(get_option("wp_excerpt_generator_method") == 'owntag') {
					$formatText[] = Limit_OwnTag($content, $owntag, $htmlOK, $htmlBR, $break);
				}
			}
			
			// On combine les ID avec leur valeur et on boucle pour faire l'update
			$arrayContent = array_combine($ID, $formatText);
			if(get_option("wp_excerpt_generator_save") == true) {
				foreach($arrayContent as $key => $value) {
					$wp_excerpt_generator_update = $wpdb->query("UPDATE $table_WP_Excerpt_Generator SET post_excerpt = '".esc_sql($value)."' WHERE ID = '".esc_sql($editID)."' AND (post_excerpt IS NULL OR post_excerpt = '')");
				}
			} else {
				foreach($arrayContent as $key => $value) {
					$wp_excerpt_generator_update = $wpdb->update($table_WP_Excerpt_Generator, array('post_excerpt' => $value), array('ID' => esc_sql($editID)));
				}
			}
		}
	}
	add_action('edit_post','WP_Excerpt_Generator_update_auto'); // save_post ou edit_post dans l'idéal comme action
	add_action('publish_post','WP_Excerpt_Generator_update_auto'); // save_post ou edit_post dans l'idéal comme action
	add_action('save_post','WP_Excerpt_Generator_update_auto'); // save_post ou edit_post dans l'idéal comme action
}
?>