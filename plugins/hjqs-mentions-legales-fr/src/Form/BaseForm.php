<?php

namespace Form;

use Entity\Field;
use Entity\Form;

class BaseForm {

	/**
	 * Crée un objet Form avec les champs spécifiés
	 *
	 * @param string $slug Slug du formulaire
	 * @param string $title Titre du formulaire
	 * @param array $fields Tableau de champs du formulaire
	 *
	 * @return Form Objet Form créé
	 */
	public function create_form( string $slug, string $title, array $fields, ?string $description = '' ): Form {
		$form = new Form();
		$form->set_slug( $slug );
		$form->set_title( $title );
		$form->set_description($description);

		foreach ( $fields as $field_args ) {
			$field_args['form_slug'] = "hjqs_$slug";
			$field                   = new Field( $field_args );
			$form->add_field( $field );

			if($field->is_content_field()){
				$form->set_content_field($field);
			}
		}

		return $form;
	}




	/**
	 * @desc Fonction callback du shortcode
	 *
	 * @param $option_form
	 * @param $option_key
	 *
	 * @return string
	 */
	public function render_content($option_form, $option_key): string {
		$option_form = "hjqs_$option_form";
		$options = get_option( $option_form );
		$content = $options[ $option_key ] ?? $this->content();
		return sprintf("<div class='hjqs-ln-render'>%s</div>",$this->prepare_content($options, $content));
	}

	public function prepare_render(): string {
		$option_form= $this->form->get_slug();
		if(!$this->form->get_content_field()){
			return "";
		}
		$option_key= $this->form->get_content_field()->get_option_key_copy();
		return $this->render_content($option_form, $option_key);
	}

	/**
	 * @desc Permet de remplacer les valeurs %%__%% par l'option du forumulaire
	 * @param $options
	 * @param $content
	 *
	 * @return string
	 */
	public function prepare_content($options, $content): string{
		preg_match_all( '/%%(.*?)%%/', $content, $matches );

		foreach ( $matches[0] as $match ) {
			$option_key = str_replace( "%", "", $match );
			$value      = $options[ $option_key ] ?? '';
			if($value === 'custom'){
				$value = $options[$option_key . '_bis'] ?? '';
			}

			if ( is_array( $value ) ) {
				$join = '<ul>';
				foreach ( $value as $item ) {
					if($item === 'custom'){
						$item = $options[$option_key . '_bis'] ?? '';
					}
					$join .= "<li>$item</li>";
				}
				$join  .= '</ul>';
				$value = $join;
			}
			$content = preg_replace( "/$match/", $value, $content );
		}

		return $content;
	}


}