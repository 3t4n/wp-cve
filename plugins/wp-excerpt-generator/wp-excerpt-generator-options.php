<?php
// Mise à jour des données par défaut
function WP_Excerpt_Generator_update() {
	global $wpdb, $table_WP_Excerpt_Generator; // insérer les variables globales

	// Réglages de base
	$wp_excerpt_generator_save				= $_POST['wp_excerpt_generator_save'];
	$wp_excerpt_generator_type				= $_POST['wp_excerpt_generator_type'];
	$wp_excerpt_generator_status			= $_POST['wp_excerpt_generator_status'];
	$wp_excerpt_generator_method			= $_POST['wp_excerpt_generator_method'];
	$wp_excerpt_generator_owntag			= $_POST['wp_excerpt_generator_owntag'];
	$wp_excerpt_generator_nbletters			= $_POST['wp_excerpt_generator_nbletters'];
	$wp_excerpt_generator_nbwords			= $_POST['wp_excerpt_generator_nbwords'];
	$wp_excerpt_generator_nbparagraphs		= $_POST['wp_excerpt_generator_nbparagraphs'];
	$wp_excerpt_generator_cleaner			= $_POST['wp_excerpt_generator_cleaner'];
	$wp_excerpt_generator_breakOK			= $_POST['wp_excerpt_generator_breakOK'];
	$wp_excerpt_generator_break				= $_POST['wp_excerpt_generator_break'];
	$wp_excerpt_generator_htmlOK			= $_POST['wp_excerpt_generator_htmlOK'];
	$wp_excerpt_generator_htmlBR			= $_POST['wp_excerpt_generator_htmlBR'];
	$wp_excerpt_generator_delete_shortcode	= $_POST['wp_excerpt_generator_delete_shortcode'];

	update_option("wp_excerpt_generator_save", $wp_excerpt_generator_save);
	update_option("wp_excerpt_generator_type", $wp_excerpt_generator_type);
	update_option("wp_excerpt_generator_status", $wp_excerpt_generator_status);
	update_option("wp_excerpt_generator_method", $wp_excerpt_generator_method);
	update_option("wp_excerpt_generator_owntag", $wp_excerpt_generator_owntag);
	update_option("wp_excerpt_generator_nbletters", $wp_excerpt_generator_nbletters);
	update_option("wp_excerpt_generator_nbwords", $wp_excerpt_generator_nbwords);
	update_option("wp_excerpt_generator_nbparagraphs", $wp_excerpt_generator_nbparagraphs);
	update_option("wp_excerpt_generator_cleaner", $wp_excerpt_generator_cleaner);
	update_option("wp_excerpt_generator_breakOK", $wp_excerpt_generator_breakOK);
	update_option("wp_excerpt_generator_break", $wp_excerpt_generator_break);
	update_option("wp_excerpt_generator_htmlOK", $wp_excerpt_generator_htmlOK);
	update_option("wp_excerpt_generator_htmlBR", $wp_excerpt_generator_htmlBR);
	update_option("wp_excerpt_generator_delete_shortcode", $wp_excerpt_generator_delete_shortcode);
}

// Fonction de génération manuelle des extraits
function WP_Excerpt_Generator_generate() {
	global $wpdb, $table_WP_Excerpt_Generator; // insérer les variables globales

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
		$selectContent = $wpdb->get_results("SELECT ID, post_content FROM $table_WP_Excerpt_Generator WHERE ".$selectContent." AND post_type = 'page'");
	} else if(get_option("wp_excerpt_generator_type") == 'post') {		
		$selectContent = $wpdb->get_results("SELECT ID, post_content FROM $table_WP_Excerpt_Generator WHERE ".$selectContent." AND post_type = 'post'");
	} else if(get_option("wp_excerpt_generator_type") == 'pagepost') {	
		$selectContent = $wpdb->get_results("SELECT ID, post_content FROM $table_WP_Excerpt_Generator WHERE ".$selectContent." AND (post_type = 'page' OR post_type = 'post')");
	}

	// Boucle de mise à jour des contenus
	if(!empty($selectContent)) {
		foreach($selectContent as $key => $content) {		
			// On récupère les ID dans un tableau pour la mise à jour et les contenus à traiter
			$ID[] = $content->ID;
			$content = trim($content->post_content);
			
			// On supprime les shortcodes si l'option est cochée
			if(get_option("wp_excerpt_generator_delete_shortcode") == true) {
				// $regex = "#(\[[^\[\]]+\][ ]?)#i";
				$regex = "#(\[[^\[\]]+\]([^\[\]]+\[/[^\[\]]+\])?)#i";
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
				if(!empty($value)) {
					$value = trim($value);
				}
				$wp_excerpt_generator_update = $wpdb->query("UPDATE $table_WP_Excerpt_Generator SET post_excerpt = '".esc_sql($value)."' WHERE ID = '".esc_sql(htmlspecialchars($key))."' AND (post_excerpt IS NULL OR post_excerpt = '')");
			}
		} else {
			foreach($arrayContent as $key => $value) {
				$wp_excerpt_generator_update = $wpdb->update($table_WP_Excerpt_Generator, array('post_excerpt' => $value), array('ID' => $key));
			}
		}
	}
}

// Fonction de lancement du générateur automatique d'extraits...
function WP_Excerpt_Generator_update_maj_auto() {
	global $wpdb, $table_WP_Excerpt_Generator; // insérer les variables globales
	$wp_excerpt_generator_maj = $_POST['wp_excerpt_generator_maj'];
	update_option("wp_excerpt_generator_maj", $wp_excerpt_generator_maj);
}

// Suppression complète des données
function WP_Excerpt_Generator_delete() {
	global $wpdb, $table_WP_Excerpt_Generator; // insérer les variables globales

	// Réglages de base
	$wp_excerpt_generator_deleteExcerpt = $_POST['wp_excerpt_generator_deleteExcerpt'];
	update_option("wp_excerpt_generator_deleteExcerpt", $wp_excerpt_generator_deleteExcerpt);
	
	if($wp_excerpt_generator_deleteExcerpt == true) {
		$deleteContent = $wpdb->get_results("UPDATE $table_WP_Excerpt_Generator SET post_excerpt = ''");
	}
}

// Suppression des extraits sélectionnés
function WP_Excerpt_Generator_deleteSelectedExcerpts() {
	global $wpdb, $table_WP_Excerpt_Generator; // insérer les variables globales

	// Réglages de base
	$wp_excerpt_generator_deleteSelectedExcerpt = $_POST['wp_excerpt_generator_deleteSelectedExcerpts'];
	
	if(!in_array('aucun',$wp_excerpt_generator_deleteSelectedExcerpt)) {
		$deleteContent = "UPDATE $table_WP_Excerpt_Generator SET post_excerpt = '' WHERE ";
		$countExcerpt = count($wp_excerpt_generator_deleteSelectedExcerpt);
		$nb = 0;
		foreach($wp_excerpt_generator_deleteSelectedExcerpt as $IDExcerpt) {
			$deleteContent .= "ID = ".$IDExcerpt."";
			if($nb < $countExcerpt-1) {
				$deleteContent .= " OR ";
			}
			$nb++;
		}
		$deleteSelectedContent = $wpdb->get_results($deleteContent);
	}
}

// Suppression de l'extrait sélectionné (en cas d'édition)
function WP_Excerpt_Generator_deleteSelectedExcerpt() {
	global $wpdb, $table_WP_Excerpt_Generator; // insérer les variables globales

	// Récupération de l'ID de l'extrait
	$wp_excerpt_generator_editExcerpt_id = $_POST['wp_excerpt_generator_editExcerpt_id'];
	
	if($wp_excerpt_generator_editExcerpt_id != "aucun") {
		$deleteExcerpt = "UPDATE ".$table_WP_Excerpt_Generator." SET post_excerpt = '' WHERE ID = ".$wp_excerpt_generator_editExcerpt_id;
		$deleteSelectedContent = $wpdb->get_results($deleteExcerpt);
	}
}

// Modification de l'extrait sélectionné (en cas d'édition)
function WP_Excerpt_Generator_editSelectedExcerpt() {
	global $wpdb, $table_WP_Excerpt_Generator; // insérer les variables globales

	// Récupération de l'ID de l'extrait
	$wp_excerpt_generator_editExcerpt_id = $_POST['wp_excerpt_generator_editExcerpt_id'];

	// Récupération du texte de l'extrait modifié
	$wp_excerpt_generator_editExcerpt = $_POST['wp_excerpt_generator_editExcerpt'];
	
	if($wp_excerpt_generator_editExcerpt_id != "aucun") {
		$editExcerpt = "UPDATE ".$table_WP_Excerpt_Generator." SET post_excerpt = '".$wp_excerpt_generator_editExcerpt."' WHERE ID = ".$wp_excerpt_generator_editExcerpt_id;
		$editSelectedContent = $wpdb->get_results($editExcerpt);
	}
}

// Fonctions Ajax pour l'admin
add_action('wp_ajax_wp_excerpt_generator_edit', 'wp_excerpt_generator_edit');
add_action('wp_ajax_nopriv_wp_excerpt_generator_edit', 'wp_excerpt_generator_edit');
function wp_excerpt_generator_edit() {
	// Récupération des paramètres
	$id = $_GET['idExcerpt'];
	$excerpt = get_the_excerpt($id);

	// Retourne le résultat de l'extrait choisi via Ajax
	echo trim($excerpt);

	die(); // Fin de l'Ajax pour WordPress
}

// Fonction d'affichage de la page de réglages de l'extension
function WP_Excerpt_Generator_Callback() {
	global $wpdb, $table_WP_Excerpt_Generator; // insérer les variables globales

	// Déclencher la fonction de mise à jour (upload)
	if(isset($_POST['wp_excerpt_generator_action']) && $_POST['wp_excerpt_generator_action'] == __('Enregistrer' , 'wp-excerpt-generator')) {
		WP_Excerpt_Generator_update();
	}
	
	// Déclencher la fonction de mise à jour (upload)
	if(isset($_POST['wp_excerpt_generator_generate']) && $_POST['wp_excerpt_generator_generate'] == __('Generate the excerpts' , 'wp-excerpt-generator')) {
		WP_Excerpt_Generator_update();
		WP_Excerpt_Generator_generate();
	}
	
	// Déclencher la fonction de mise à jour automatique des extraits (upload)
	if(isset($_POST['wp_excerpt_generator_action_maj_auto']) && $_POST['wp_excerpt_generator_action_maj_auto'] == __('Save' , 'wp-excerpt-generator')) {
		WP_Excerpt_Generator_update_maj_auto();
	}
	
	// Déclencher la fonction de suppression des extraits
	if(isset($_POST['wp_excerpt_generator_delete']) && $_POST['wp_excerpt_generator_delete'] == __('Delete' , 'wp-excerpt-generator')) {
		WP_Excerpt_Generator_delete();
	}
	
	// Déclencher la fonction de suppression des extraits sélectionnés uniquement
	if(isset($_POST['wp_excerpt_generator_deleteSelectedExcerpts_choice']) && $_POST['wp_excerpt_generator_deleteSelectedExcerpts_choice'] == __('Delete these excerpts' , 'wp-excerpt-generator')) {
		WP_Excerpt_Generator_deleteSelectedExcerpts();
	}

	// Déclencher la fonction de suppression de l'extrait sélectionné (en cas d'édition)
	if(isset($_POST['wp_excerpt_generator_deleteSelectedExcerpt']) && $_POST['wp_excerpt_generator_deleteSelectedExcerpt'] == __('Delete' , 'wp-excerpt-generator')) {
		WP_Excerpt_Generator_deleteSelectedExcerpt();
	}

	// Déclencher la fonction d'édition de l'extrait séléctionné
	if(isset($_POST['wp_excerpt_generator_editSelectedExcerpt']) && $_POST['wp_excerpt_generator_editSelectedExcerpt'] == __('Edit and save' , 'wp-excerpt-generator')) {
		WP_Excerpt_Generator_editSelectedExcerpt();
	}

	/* --------------------------------------------------------------------- */
	/* ------------------------ Affichage de la page ----------------------- */
	/* --------------------------------------------------------------------- */
	echo '<div class="wrap excerpt-generator-admin">';
	echo '<div class="block-info">';
	echo '<div class="icon">';
	echo '<h2>'; _e('WP Excerpt Generator Settings','wp-excerpt-generator'); echo '</h2><br/>';
	echo '</div>';
	echo '<div class="text">';
	_e('<strong>WP Excerpt Generator</strong> is an automated extracts generator for WordPress.', 'wp-excerpt-generator');
	_e('Several methods can be exploited to generate excerpts as we want:', 'wp-excerpt-generator');	echo '<br/>';
	echo '<ol>';
	echo '<li>'; _e('Keep or not the existing excerpts','wp-excerpt-generator'); echo '</li>';
	echo '<li>'; _e('Choose the type of targeted content (pages, items or both)','wp-excerpt-generator'); echo '</li>';
	echo '<li>'; _e('Choose the method of creation (first paragraph, number of words, number of letters ...)','wp-excerpt-generator'); echo '</li>';
	echo '<li>'; _e('Refine the final display','wp-excerpt-generator'); echo '</li>';
	echo '<li>'; _e('Keep or not the HTML code in the excerpt','wp-excerpt-generator'); echo '</li>';
	echo '<li>'; _e('Automatically generate excerpts according to the current settings','wp-excerpt-generator'); echo '</li>';
	echo '<li>'; _e('Clean and remove existing extracts (generated or not)','wp-excerpt-generator'); echo '</li>';
	echo '</ol><em>';
	_e('Note: there may be some problems with clipping when you keep the HTML code, especially with cutting letters.' , 'wp-excerpt-generator');
	echo "<br/>";
	_e('N.B. : This extension is not perfect but it helps to fill in the missing excerpt without difficulty. Contact <a href="https://blog.internet-formation.fr" target="_blank">Mathieu Chartier</a>, the creator of the plugin, for more information.' , 'wp-excerpt-generator'); 
	echo '</em><br/>';
	echo '</div>';
	echo '</div>';
?>       
<script type="text/javascript">
function montrer(object) {
   if (document.getElementById) document.getElementById(object).style.display = 'block';
}

function cacher(object) {
   if (document.getElementById) document.getElementById(object).style.display = 'none';
}
</script>

<div class="block">
    <div class="col first-col">
    <!-- Formulaire de mise à jour des données -->
    <form method="post" action="">
        <h4><?php _e('General Settings','wp-excerpt-generator'); ?></h4>
        <p class="tr">
            <select name="wp_excerpt_generator_save" id="wp_excerpt_generator_save">
                <option value="1" <?php if(get_option("wp_excerpt_generator_save") == true) { echo 'selected="selected"'; } ?>><?php _e('Keep existing excerpts','wp-excerpt-generator'); ?></option>
                <option value="0" <?php if(get_option("wp_excerpt_generator_save") == false) { echo 'selected="selected"'; } ?>><?php _e('Replace','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_save"><strong><?php _e('Keep the existing excerpts or replace them?','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('The option to create missing excerpts without erasing the existing excerpts. Otherwise, everything is replaced by the new excerpts ...','wp-excerpt-generator'); ?></em>
        </p>
        <p class="tr">
            <select name="wp_excerpt_generator_type" id="wp_excerpt_generator_type">
                <option value="page" <?php if(get_option("wp_excerpt_generator_type") == 'page') { echo 'selected="selected"'; } ?>><?php _e('Pages','wp-excerpt-generator'); ?></option>
                <option value="post" <?php if(get_option("wp_excerpt_generator_type") == 'post') { echo 'selected="selected"'; } ?>><?php _e('Posts','wp-excerpt-generator'); ?></option>
                <option value="pagepost" <?php if(get_option("wp_excerpt_generator_type") == 'pagepost') { echo 'selected="selected"'; } ?>><?php _e('Posts + Pages','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_type"><strong><?php _e('Generate excerpts for what content?','wp-excerpt-generator'); ?></strong></label>
        </p>
        <p class="tr">
            <select name="wp_excerpt_generator_status" id="wp_excerpt_generator_status">
                <option value="publish" <?php if(get_option("wp_excerpt_generator_status") == 'publish') { echo 'selected="selected"'; } ?>><?php _e('Published contents','wp-excerpt-generator'); ?></option>
                <option value="future" <?php if(get_option("wp_excerpt_generator_status") == 'future') { echo 'selected="selected"'; } ?>><?php _e('Scheduled contents','wp-excerpt-generator'); ?></option>
                <option value="publishfuture" <?php if(get_option("wp_excerpt_generator_status") == 'publishfuture') { echo 'selected="selected"'; } ?>><?php _e('Both','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_status"><strong><?php _e('Generate excerpts for content published or planned?','wp-excerpt-generator'); ?></strong></label>
        </p>
        <p class="tr">
            <select name="wp_excerpt_generator_method" id="wp_excerpt_generator_method">
                <option value="paragraph" onclick="montrer('blockParagraphs'); cacher('blockWords'); cacher('blockLetters'); cacher('blockClean'); cacher('blockOwn');" <?php if(get_option("wp_excerpt_generator_method") == 'paragraph') { echo 'selected="selected"'; } ?>><?php _e('Number of paragraphs (to be defined)','wp-excerpt-generator'); ?></option>
                <option value="words" onclick="montrer('blockWords'); montrer('blockClean'); cacher('blockLetters'); cacher('blockParagraphs'); cacher('blockOwn');" <?php if(get_option("wp_excerpt_generator_method") == 'words') { echo 'selected="selected"'; } ?>><?php _e('Number of words (to be defined)','wp-excerpt-generator'); ?></option>
                <option value="letters" onclick="montrer('blockLetters'); montrer('blockClean'); cacher('blockParagraphs'); cacher('blockWords'); cacher('blockOwn');" <?php if(get_option("wp_excerpt_generator_method") == 'letters') { echo 'selected="selected"'; } ?>><?php _e('Number of letters (to be defined)','wp-excerpt-generator'); ?></option>
                <option value="moretag" onclick="cacher('blockWords'); cacher('blockLetters'); cacher('blockParagraphs'); cacher('blockClean'); cacher('blockOwn');" <?php if(get_option("wp_excerpt_generator_method") == 'moretag') { echo 'selected="selected"'; } ?>><?php _e('Before the MORE tag WordPress','wp-excerpt-generator'); ?></option>
                <option value="owntag" onclick="montrer('blockOwn'); cacher('blockWords'); cacher('blockLetters'); cacher('blockParagraphs'); montrer('blockClean');" <?php if(get_option("wp_excerpt_generator_method") == 'owntag') { echo 'selected="selected"'; } ?>><?php _e('Before a custom delimiter?','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_method"><strong><?php _e('Generator method for the excerpts','wp-excerpt-generator'); ?></strong></label>
        </p>
        <p class="tr" id="blockOwn" <?php if(get_option("wp_excerpt_generator_method") == 'owntag') { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
            <input value="<?php echo get_option("wp_excerpt_generator_owntag"); ?>" name="wp_excerpt_generator_owntag" id="wp_excerpt_generator_owntag" type="text" />
            <label for="wp_excerpt_generator_owntag"><strong><?php _e('Edit the delimiter (string)','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('The option to turn off the text before the selected string.<br/>Examples: a word invented a tag...','wp-excerpt-generator'); ?></em>
        </p>
        <p class="tr" id="blockWords" <?php if(get_option("wp_excerpt_generator_method") == 'words') { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
            <input value="<?php echo get_option("wp_excerpt_generator_nbwords"); ?>" name="wp_excerpt_generator_nbwords" id="wp_excerpt_generator_nbwords" type="text" />
            <label for="wp_excerpt_generator_nbwords"><strong><?php _e('Exact number of words to keep','wp-excerpt-generator'); ?></strong></label>
        </p>
        <p class="tr" id="blockLetters" <?php if(get_option("wp_excerpt_generator_method") == 'letters') { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
            <input value="<?php echo get_option("wp_excerpt_generator_nbletters"); ?>" name="wp_excerpt_generator_nbletters" id="wp_excerpt_generator_nbletters" type="text" />
            <label for="wp_excerpt_generator_nbletters"><strong><?php _e('Number of letters to keep (maximum)','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('Cutting sometimes inaccurate because of existing HTML tags!','wp-excerpt-generator'); ?></em>
        </p>
        <p class="tr" id="blockParagraphs" <?php if(get_option("wp_excerpt_generator_method") == 'paragraph') { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
            <input value="<?php echo get_option("wp_excerpt_generator_nbparagraphs"); ?>" name="wp_excerpt_generator_nbparagraphs" id="wp_excerpt_generator_nbparagraphs" type="text" />
            <label for="wp_excerpt_generator_nbparagraphs"><strong><?php _e('Number of paragraphs to keep (maximum)','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('Cutting sometimes inaccurate because of existing HTML tags!','wp-excerpt-generator'); ?></em>
        </p>

        <p class="tr" id="blockClean" <?php if(get_option("wp_excerpt_generator_method") == 'letters' || get_option("wp_excerpt_generator_method") == 'words') { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
            <select name="wp_excerpt_generator_cleaner" id="wp_excerpt_generator_cleaner">
                <option value="1" <?php if(get_option("wp_excerpt_generator_cleaner") == true) { echo 'selected="selected"'; } ?>><?php _e('Yes','wp-excerpt-generator'); ?></option>
                <option value="0" <?php if(get_option("wp_excerpt_generator_cleaner") == false) { echo 'selected="selected"'; } ?>><?php _e('No','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_cleaner"><strong><?php _e('Finish excerpt with a clean punctuation? (recommended)','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('The option to end sentences withclean punctuation (".", "?", "!"...).','wp-excerpt-generator'); ?></em>
        </p>
        <p class="tr">
            <select name="wp_excerpt_generator_htmlOK" id="wp_excerpt_generator_htmlOK">
                <option value="total" onclick="cacher('blockHtmlBR');" <?php if(get_option("wp_excerpt_generator_htmlOK") == 'total') { echo 'selected="selected"'; } ?>><?php _e('Keep all HTML code','wp-excerpt-generator'); ?></option>
                <option value="partial" onclick="montrer('blockHtmlBR');" <?php if(get_option("wp_excerpt_generator_htmlOK") == 'partial') { echo 'selected="selected"'; } ?>><?php _e('Partially (bold, italic ...)','wp-excerpt-generator'); ?></option>
                <option value="none" onclick="montrer('blockHtmlBR');" <?php if(get_option("wp_excerpt_generator_htmlOK") == 'none') { echo 'selected="selected"'; } ?>><?php _e('No HTML','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_htmlOK"><strong><?php _e('Keep the HTML code ? (not recommended)','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('Caution! If you cut through groups of words or letters, you may break the logic of HTML code ...','wp-excerpt-generator'); ?></em>
        </p>
        <p class="tr" id="blockHtmlBR" <?php if(get_option("wp_excerpt_generator_htmlOK") == 'partial' || get_option("wp_excerpt_generator_htmlOK") == 'none') { echo 'style="display:block;"'; } else { echo 'style="display:none;"'; } ?>>
            <select name="wp_excerpt_generator_htmlBR" id="wp_excerpt_generator_htmlBR">
                <option value="1" onclick="montrer('blockBreak');" <?php if(get_option("wp_excerpt_generator_htmlBR") == true) { echo 'selected="selected"'; } ?>><?php _e('Yes','wp-excerpt-generator'); ?></option>
                <option value="0" onclick="cacher('blockBreak');" <?php if(get_option("wp_excerpt_generator_htmlBR") == false) { echo 'selected="selected"'; } ?>><?php _e('No','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_htmlBR"><strong><?php _e('Keep line breaks?','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('The option to add characters to understand that the continuous text.','wp-excerpt-generator'); ?></em>
        </p>
        <p class="tr">
            <select name="wp_excerpt_generator_breakOK" id="wp_excerpt_generator_breakOK">
                <option value="1" onclick="montrer('blockBreak');" <?php if(get_option("wp_excerpt_generator_breakOK") == true) { echo 'selected="selected"'; } ?>><?php _e('Yes','wp-excerpt-generator'); ?></option>
                <option value="0" onclick="cacher('blockBreak');" <?php if(get_option("wp_excerpt_generator_breakOK") == false) { echo 'selected="selected"'; } ?>><?php _e('No','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_breakOK"><strong><?php _e('Add a string to the end of the excerpt?','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('The option to add characters to understand that the continuous text.','wp-excerpt-generator'); ?></em>
        </p>
        <p class="tr" id="blockBreak" <?php if(get_option("wp_excerpt_generator_breakOK") == false) { echo 'style="display:none;"'; } ?>>
            <input value="<?php echo get_option("wp_excerpt_generator_break"); ?>" name="wp_excerpt_generator_break" id="wp_excerpt_generator_break" type="text" />
            <label for="wp_excerpt_generator_break"><strong><?php _e('String displayed after the excerpt','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('For example : " (...)", " [...]", " ..."','wp-excerpt-generator'); ?></em>
        </p>
        <p class="tr">
            <select name="wp_excerpt_generator_delete_shortcode" id="wp_excerpt_generator_delete_shortcode">
                <option value="1" <?php if(get_option("wp_excerpt_generator_delete_shortcode") == true) { echo 'selected="selected"'; } ?>><?php _e('Yes','wp-excerpt-generator'); ?></option>
                <option value="0" <?php if(get_option("wp_excerpt_generator_delete_shortcode") == false) { echo 'selected="selected"'; } ?>><?php _e('No','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_delete_shortcode"><strong><?php _e('Remove shortcodes in excerpts?','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('This option removes all that is of the form [shortcode] in excerpts (to prevent them are active).','wp-excerpt-generator'); ?></em>
        </p>
        
    	<p class="submit">
        	<input type="submit" name="wp_excerpt_generator_action" class="button-primary" value="<?php _e('Save' , 'wp-excerpt-generator'); ?>" />
            <input type="submit" name="wp_excerpt_generator_generate" class="button-primary" value="<?php _e('Generate the excerpts' , 'wp-excerpt-generator'); ?>" />
        </p>
    </form>
	</div>

	<div class="col">
	<form method="post" action="">
    	<h4><?php _e('Automatic update excerpts?','wp-excerpt-generator'); ?></h4>
        <p class="tr">
            <select name="wp_excerpt_generator_maj" id="wp_excerpt_generator_maj">
                <option value="1" <?php if(get_option("wp_excerpt_generator_maj") == true) { echo 'selected="selected"'; } ?>><?php _e('Yes','wp-excerpt-generator'); ?></option>
                <option value="0" <?php if(get_option("wp_excerpt_generator_maj") == false) { echo 'selected="selected"'; } ?>><?php _e('No','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_maj"><strong><?php _e('Automatically generate new excerpts?','wp-excerpt-generator'); ?></strong></label>
            <br/><em><?php _e('The option is used to automatically generate excerpts for posts or pages after their publication or modification.','wp-excerpt-generator'); ?></em>
        </p>
        <p class="submit"><input type="submit" name="wp_excerpt_generator_action_maj_auto" class="button-primary" value="<?php _e('Save' , 'wp-excerpt-generator'); ?>" /></p>
    </form>
    <form method="post" action="">
        <h4><?php _e('Cleaning excerpts ...','wp-excerpt-generator'); ?></h4>
        <p class="tr">
            <select name="wp_excerpt_generator_deleteExcerpt" id="wp_excerpt_generator_deleteExcerpt">
                <option value="1" onclick="return(confirm('<?php _e('Are you sure you want to delete the existing excerpts?\nNB: no extract will be retained!','wp-excerpt-generator'); ?>'));"><?php _e('Yes','wp-excerpt-generator'); ?></option>
                <option value="0" <?php echo 'selected="selected"'; ?>><?php _e('No','wp-excerpt-generator'); ?></option>
            </select>
            <label for="wp_excerpt_generator_deleteExcerpt"><strong><?php _e('Delete all excerpt from the database?','wp-excerpt-generator'); ?></strong></label>
        </p>   
    	<p class="submit"><input type="submit" name="wp_excerpt_generator_delete" onclick="javascript:return(confirm('<?php _e('Last chance before deleting the selected excerpts ...\nAre you always safe with you?','wp-excerpt-generator'); ?>'));" class="button-primary" value="<?php _e('Delete' , 'wp-excerpt-generator'); ?>" /></p>
    </form>
    <form method="post" action="">
		<p class="trNew">
			<?php
                $existingTitleExcerpt = $wpdb->get_results("SELECT post_title FROM $wpdb->posts WHERE post_status = 'publish' AND post_title !='' AND post_excerpt != '' ORDER BY post_date DESC"); // Lister les extraits existants
				$existingIdExcerpt = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_title !='' AND post_excerpt != '' ORDER BY post_date DESC"); // Lister les extraits existants
                foreach($existingTitleExcerpt as $excerpt) {
					foreach($excerpt as $TitleExcerpt) {
                        $tabTitleExcerpt[] = $TitleExcerpt;	
                    }
                }
				foreach($existingIdExcerpt as $excerptId) {
					foreach($excerptId as $IdExcerpt) {
                        $tabIdExcerpt[] = $IdExcerpt;	
                    }
                }
				if(!empty($tabTitleExcerpt) && !empty($tabIdExcerpt)) {
	                $tabExcerpt = array_combine($tabIdExcerpt, $tabTitleExcerpt);
				}
            ?>
            <label for="wp_excerpt_generator_deleteSelectedExcerpts"><strong><?php _e('Delete or edit selected excerpts','wp-excerpt-generator'); ?></strong></label>
            <select name="wp_excerpt_generator_deleteSelectedExcerpts[]" id="wp_excerpt_generator_deleteSelectedExcerpts" multiple="multiple" size="12" class="selectedExcerpt">
                <option value="aucun"><?php _e('None','wp-excerpt-generator'); ?></option>
                <?php foreach($tabExcerpt as $ExcerptKey => $ExcerptTitle) { ?>
                	<option value="<?php echo $ExcerptKey; ?>"><?php _e($ExcerptTitle,'wp-excerpt-generator'); ?></option>
                <?php } ?>
            </select>
            <br/><em><?php _e('Double-click to edit a selected excerpt!','wp-excerpt-generator'); ?>
            <br/><?php _e('Only the contents of which are filled excerpts are displayed in the list!','wp-excerpt-generator'); ?></em>
        </p>
    	<p class="submit"><input type="submit" name="wp_excerpt_generator_deleteSelectedExcerpts_choice" onclick="javascript:return(confirm('<?php _e('Last chance before deleting the selected excerpts ...\nAre you always safe with you?','wp-excerpt-generator'); ?>'));" class="button-primary" value="<?php _e('Delete these selected excerpts' , 'wp-excerpt-generator'); ?>" /></p>

    	<div id="hidden_wp_excerpt">
	        <p class="trNew">
	        	<label for="wp_excerpt_generator_deleteExcerpt"><strong><?php _e('Edit this excerpt','wp-excerpt-generator'); ?></strong></label>
	            <textarea name="wp_excerpt_generator_editExcerpt" id="wp_excerpt_generator_editExcerpt"></textarea>
	            <input type="hidden" name="wp_excerpt_generator_editExcerpt_id" id="wp_excerpt_generator_editExcerpt_id" value=""/>
	            <br/><em><?php _e('Edit the excerpt to optimize it, or delete it.','wp-excerpt-generator'); ?></em>
	        </p>
	        <p class="submit">
	        	<input type="submit" name="wp_excerpt_generator_editSelectedExcerpt" class="button-primary" value="<?php _e('Edit and save' , 'wp-excerpt-generator'); ?>"/>
	        	<input type="submit" name="wp_excerpt_generator_deleteSelectedExcerpt" class="button-primary" value="<?php _e('Delete' , 'wp-excerpt-generator'); ?>"/>
	        	<input type="button" name="wp_excerpt_generator_cancelSelectedExcerpt" id="wp_excerpt_generator_cancelSelectedExcerpt" class="button-primary" value="<?php _e('Cancel' , 'wp-excerpt-generator'); ?>"/>
	        </p>
        </div>
    </form>
    </div>
    <div class="clear"></div>
</div>
<?php
echo '</div>'; // Fin de la page d'admin
} // Fin de la fonction Callback
?>