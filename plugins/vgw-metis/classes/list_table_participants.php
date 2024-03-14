<?php

namespace WP_VGWORT;

/**
 * Manages the Metis List Table to build the participant list
 *
 * @package     vgw-metis
 * @copyright   Verwertungsgesellschaft Wort
 * @license     https://www.gnu.org/licenses/gpl-3.0.html
 * @author      Torben Gallob
 * @author      Michael Hillebrand
 *
 */
class List_Table_Participants extends List_Table {
	const participant_select_options = [
		"AUTHOR"     => "Autor",
		"PUBLISHER"  => "Verlag",
		"TRANSLATOR" => "Übersetzer",
	];

	/**
	 * prepare data before display
	 *
	 * @return void
	 */
	public function prepare_items(): void {
		$this->set_columns( $this->define_columns() );
	}

	/**
	 * reads data for table
	 *
	 * @return void
	 */
	public function read_data(): void {
		$table_data = Db_Participants::get_all_participants();
		$this->set_items( $table_data );
	}

	/**
	 * load script for metis list table
	 *
	 * @return array of Column_Definition columns
	 */
	public function define_columns(): array {
		$col                   = new Column_Definition();
		$col->name             = 'first_name';
		$col->label            = 'Vorname';
		$col->required         = false;
		$col->field_type       = Column_Definition::METIS_FIELD_TYPE_TEXT;
		$col->edit_type        = Column_Definition::METIS_EDIT_TYPE_INPUT;
		$col->max              = 40;
		$col->min              = 0;
		$columns['first_name'] = $col;

		$col                  = new Column_Definition();
		$col->name            = 'last_name';
		$col->label           = 'Nachname';
		$col->required        = true;
		$col->field_type      = Column_Definition::METIS_FIELD_TYPE_TEXT;
		$col->edit_type       = Column_Definition::METIS_EDIT_TYPE_INPUT;
		$col->max             = 255;
		$col->min             = 1;
		$columns['last_name'] = $col;

		$col                    = new Column_Definition();
		$col->name              = 'file_number';
		$col->label             = 'Karteinummer';
		$col->required          = false;
		$col->field_type        = Column_Definition::METIS_FIELD_TYPE_NUMBER;
		$col->edit_type         = Column_Definition::METIS_EDIT_TYPE_INPUT;
		$col->max               = 9999999;
		$col->min               = 1;
		$columns['file_number'] = $col;

		$col                         = new Column_Definition();
		$col->name                   = 'involvement';
		$col->label                  = 'Funktion';
		$col->required               = true;
		$col->field_type             = Column_Definition::METIS_FIELD_TYPE_TEXT;
		$col->edit_type              = Column_Definition::METIS_EDIT_TYPE_SELECT;
		$col->select_options         = self::participant_select_options;
		$columns['involvement'] = $col;

		$col                = new Column_Definition();
		$col->name          = 'wp_user';
		$col->linkid        = 'ID';
		$col->linkattr      = 'profile.php?user_id';
		$col->label         = 'Benutzername';
		$col->required      = false;
		$col->edit          = 'disabled';
		$col->field_type    = Column_Definition::METIS_FIELD_TYPE_TEXT;
		$col->edit_type     = Column_Definition::METIS_EDIT_TYPE_INPUT;
		$col->max           = 60;
		$col->min           = 0;
		$columns['wp_user'] = $col;

		return $columns;
	}

}
