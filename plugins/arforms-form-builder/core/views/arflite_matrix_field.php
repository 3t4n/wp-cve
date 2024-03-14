<?php
global $arflite_matrix_field_class;
$arflite_matrix_field_class = new arflite_matrix_field();

class arflite_matrix_field {

	function __construct() {

		add_filter( 'arfliteaavailablefields', array( $this, 'arflite_add_matrix_field_element_list' ), 12 );
	}

	function arflite_add_matrix_field_element_list( $fields ) {

		$fields['matrix'] = array(
			'icon'  => "<svg width='34' height='34' viewBox='0 0 40 40' fill='none' xmlns='http://www.w3.org/2000/svg'><rect x='0.994141' y='0.994141' width='38.0119' height='38.0119' rx='1.81288' stroke='#4E5462' stroke-width='1.9006'/><rect x='26.3555' y='1.95947' width='1.462' height='36.1216' fill='#4E5462'/><rect x='14.6602' y='1.93701' width='1.462' height='36.1445' fill='#4E5462'/><rect x='1.93896' y='15.4463' width='1.462' height='36.1216' transform='rotate(-90 1.93896 15.4463)' fill='#4E5462'/><rect x='1.93896' y='27.519' width='1.462' height='36.1216' transform='rotate(-90 1.93896 27.519)' fill='#4E5462'/><rect x='5.15674' y='7.229' width='6.28659' height='1.462' rx='0.467839' fill='#4E5462'/><rect x='5.15674' y='31.3496' width='6.28659' height='1.462' rx='0.467839' fill='#4E5462'/><rect x='5.15674' y='19.2759' width='6.87139' height='1.462' rx='0.467839' fill='#4E5462'/><circle cx='21.2316' cy='7.93665' r='2.193' stroke='#4E5462' stroke-width='0.584799'/><circle cx='21.2321' cy='20.7272' r='2.193' stroke='#4E5462' stroke-width='0.584799'/><circle cx='32.9425' cy='32.7999' r='2.193' stroke='#4E5462' stroke-width='0.584799'/><circle cx='21.2316' cy='32.7999' r='2.193' fill='#4E5462'/><circle cx='32.9425' cy='20.7272' r='2.193' fill='#4E5462'/><circle cx='32.9425' cy='7.93665' r='2.193' fill='#4E5462'/></svg>",
			'label' => __( 'Matrix', 'arforms-form-builder' ),
		);

		return $fields;
	}
}
