<?php
class clicface_Collaborateur {
	function __construct( $id ) {
		try {
			// Nom
			$this->Nom = get_the_title($id);
			
			// Lien vers la fiche
			$this->Link = get_permalink($id);
			
			// Fonction
			$this->Fonction = get_post_meta($id , 'fonction', true);
			
			// Service
			$collaborateur_service = wp_get_post_terms( $id, 'collaborateur_service' );
			if ( isset($collaborateur_service[0]->name) ) {
				$this->ServiceID = '';
				$this->Service = '';
				for ($i = 0; $i < count($collaborateur_service); $i++) {
					$this->ServiceID .= $collaborateur_service[$i]->term_id . ' ';
					$this->Service .= $collaborateur_service[$i]->name . ', ';
				}
				$this->ServiceID = trim( $this->ServiceID, ', ' );
				$this->Service = trim( $this->Service, ', ' );
			} else {
				$this->ServiceID = NULL;
				$this->Service = NULL;
			}
			
			// Worksite
			$collaborateur_worksite = wp_get_post_terms( $id, 'collaborateur_worksite' );
			if ( isset($collaborateur_worksite[0]->name) ) {
				$this->WorksiteID = '';
				$this->Worksite = '';
				for ($i = 0; $i < count($collaborateur_worksite); $i++) {
					$this->WorksiteID .= $collaborateur_worksite[$i]->term_id . ' ';
					$this->Worksite .= $collaborateur_worksite[$i]->name . ', ';
				}
				$this->WorksiteID = trim( $this->WorksiteID, ', ' );
				$this->Worksite = trim( $this->Worksite, ', ' );
			} else {
				$this->WorksiteID = NULL;
				$this->Worksite = NULL;
			}
			
			// Mail
			if( !function_exists('convert_email_adr') ){
				function convert_email_adr($email) {
					$pieces = str_split(trim($email));
					$new_mail = '';
					foreach ($pieces as $val) {
						$new_mail .= '&#'.ord($val).';';
					}
					return $new_mail;
				}
			}
			$this->Mail = get_post_meta($id , 'mail', true);
			$this->Mailto = '<a href="mailto:' . convert_email_adr( get_post_meta($id , 'mail', true) ) . '">' . convert_email_adr( get_post_meta($id , 'mail', true) ) . '</a>';
			
			// Website
			$this->Website = get_post_meta($id , 'website', true);
			$this->Website = '<a href="' . get_post_meta($id , 'website', true) . '" target="_blank">' . get_post_meta($id , 'website', true) . '</a>';
			if ( get_post_meta($id , 'website', true) == NULL ) { $this->Website = NULL; }
			
			// Téléphone fixe
			$this->TelephoneFixe = get_post_meta($id , 'telephone_fixe', true);
			
			// Téléphone portable
			$this->TelephonePortable = get_post_meta($id , 'telephone_portable', true);
			
			// Commentaires
			$this->Commentaires = get_post_meta($id , 'commentaires', true);
			
			// Photo
			$photo_array = array_filter( get_post_meta( $id, 'clicface-trombi-images-type-collaborateur-photo', false ) );
			$photo_array_slice = array_slice($photo_array, 0, 1);
			$photo_id = array_shift($photo_array_slice);
			if ( $photo_id != NULL ) {
				$this->PhotoThumbnail = wp_get_attachment_image( $photo_id, 'thumbnail', false );
				$this->PhotoLarge = '<div id="pik_post_attachment_' . $id . '" class="clicface-field-container">';
				$this->PhotoLarge .= '<div class="clicface-label-container">' . wp_get_attachment_image( $id, 'large', false ) . '</div>';
				$this->PhotoLarge .= '</div>';
			} else {
				$this->PhotoThumbnail = NULL;
				$this->PhotoLarge = NULL;
			}
			if ( $this->PhotoThumbnail == NULL ) {
				$clicface_trombi_settings = get_option('clicface_trombi_settings');
				if ( !empty( $clicface_trombi_settings['trombi_default_picture'] ) ) {
					$this->PhotoThumbnail = '<img src="' . $clicface_trombi_settings['trombi_default_picture'] . '" alt="" />';
				} else {
					$this->PhotoThumbnail = '<img src="' . plugins_url( 'img/default_picture.png' , dirname(__FILE__) ) . '" alt="" />';
				} 
			}
			
			// Facebook
			$this->Facebook = get_post_meta($id , 'facebook', true);
			$this->Facebook = '<a href="' . get_post_meta($id , 'facebook', true) . '" target="_blank"><img src="' . plugins_url( 'img/facebook.png' , dirname(__FILE__) ) . '" alt="Facebook" title="Facebook" /></a>';
			if ( get_post_meta($id , 'facebook', true) == NULL ) { $this->Facebook = NULL; }
			
			// LinkedIn
			$this->LinkedIn = get_post_meta($id , 'linkedin', true);
			$this->LinkedIn = '<a href="' . get_post_meta($id , 'linkedin', true) . '" target="_blank"><img src="' . plugins_url( 'img/linkedin.png' , dirname(__FILE__) ) . '" alt="LinkedIn" title="LinkedIn" /></a>';
			if ( get_post_meta($id , 'linkedin', true) == NULL ) { $this->LinkedIn = NULL; }
			
			// Twitter
			$this->Twitter = get_post_meta($id , 'twitter', true);
			$this->Twitter = '<a href="' . get_post_meta($id , 'twitter', true) . '" target="_blank"><img src="' . plugins_url( 'img/twitter.png' , dirname(__FILE__) ) . '" alt="Twitter" title="Twitter" /></a>';
			if ( get_post_meta($id , 'twitter', true) == NULL ) { $this->Twitter = NULL; }
			
			// Youtube
			$this->Youtube = get_post_meta($id , 'youtube', true);
			$this->Youtube = '<a href="' . get_post_meta($id , 'youtube', true) . '" target="_blank"><img src="' . plugins_url( 'img/youtube.png' , dirname(__FILE__) ) . '" alt="Youtube" title="Youtube" /></a>';
			if ( get_post_meta($id , 'youtube', true) == NULL ) { $this->Youtube = NULL; }
			
			$this->Erreur = false;
			return true;
		}
		
		catch (Exception $e) {
			$this->Erreur = __('An error occurred:', 'clicface-trombi') . " $this->Nom : " . $e->getMessage() . "\r";
			return false;
		}
	}
}