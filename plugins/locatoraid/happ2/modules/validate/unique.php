<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Validate_Unique_HC_MVC extends _HC_MVC
{
	protected $table = NULL;
	protected $field = NULL;
	protected $skip_id = NULL;

	public function params( $table, $field, $skip_id = NULL )
	{
		$this->table = $table;
		$this->field = $field;
		$this->skip_id = $skip_id;
		return $this;
	}

	public function validate( $value )
	{
		$return = TRUE;
		$msg = __('This value is already used', 'locatoraid');
		// $msg .= ': ' . strip_tags($value);
		$id_field = 'id';

		$command_slug = '/' . $this->table . '/commands/read';
		$command = $this->app->make( $command_slug );

		$command_args = array();
		$command_args[] = array( 'limit', 1 );
		$command_args[] = array( $this->field, '=', $value );

		if( $this->skip_id ){
			$command_args[] = array( $id_field, 'NOTIN', $this->skip_id );
		}

		$already = $command->execute( $command_args );
		
		if( $already ){
			$return = $msg;
		}

		return $return;
	}
}