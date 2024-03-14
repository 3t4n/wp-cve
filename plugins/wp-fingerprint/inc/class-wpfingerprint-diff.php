<?php
class WPFingerprint_Diff{

	public function check_file_diff( $file_body, $remote_body ) {
		if ( ! class_exists( 'WP_Text_Diff_Renderer_Table', false ) )
		{
			require( ABSPATH . WPINC . '/wp-diff.php' );
		}
		$left_string  = normalize_whitespace($file_body);
		$right_string = normalize_whitespace($remote_body);
		$left_lines  = explode("\n", $left_string);
		$right_lines = explode("\n", $right_string);
	return new Text_Diff($left_lines, $right_lines);
	}

	public function show_diffs( $diff_object ) {
		if(!is_object($diff_object)) return false;
		$line_count = 0;
		$diffs = array();
		foreach($diff_object->_edits as $edits)
		{
			//var_dump($edits);
			if(empty($edits->orig)) $edits->orig = array();
			if(empty($edits->final)) $edits->final = array();

			$diff_array = array_diff( $edits->orig, $edits->final);
			$array_count = count($edits->orig) + $line_count;

			if(!empty($diff_array))
			{
				$diffs[] = array(
					'line' => $array_count,
					'local' => implode("\n",$edits->orig),
					'remote' => implode("\n",$edits->final)
				);
			}

			$line_count = $array_count;

		}
		return $diffs;
	}

}
