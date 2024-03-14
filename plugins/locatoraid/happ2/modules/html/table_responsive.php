<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Html_Table_Responsive_HC_MVC extends _HC_MVC
{
	protected $striped = true;

	protected $header = array();
	protected $rows = array();
	protected $footer = array();

	protected $is_wp_admin = false;
	protected $no_footer = true;

	protected $cell_padding = 2;

	protected $width = array();
	protected $style = array();

	public function __toString()
	{
		return '' . $this->render();
	}

	public function _init()
	{
		$this->is_wp_admin = ( defined('WPINC') && is_admin() ) ? TRUE : FALSE;
		return $this;
	}

	public function set_padding( $padding )
	{
		$this->cell_padding = $padding;
		return $this;
	}

	public function set_no_footer( $no_footer = TRUE )
	{
		$this->no_footer = $no_footer;
		return $this;
	}

	public function set_striped( $striped = TRUE )
	{
		$this->striped = $striped;
		return $this;
	}

	public function set_header( $header )
	{
		$this->header = $header;
		return $this;
	}
	public function header()
	{
		return $this->header;
	}
	public function set_footer( $footer )
	{
		$this->footer = $footer;
		return $this;
	}
	public function footer()
	{
		return $this->footer;
	}
	public function set_rows( $rows )
	{
		$this->rows = $rows;
		return $this;
	}
	public function rows()
	{
		return $this->rows;
	}

	public function widths( $col_count )
	{
		$counts = array(
			1	=> array(12),
			2	=> array(6, 6),
			3	=> array(4, 4, 4),
			4	=> array(3, 3, 3, 3),
			5	=> array(3, 3, 2, 2, 2),
			6	=> array(2, 2, 2, 2, 2, 2),
			);
		$return = isset($counts[$col_count]) ? $counts[$col_count] : array();
		return $return;
	}

	public function set_width( $k, $width )
	{
		$this->width[ $k ] = $width;
		return $this;
	}

	public function set_style( $k, $style )
	{
		$this->style[ $k ] = $style;
		return $this;
	}

	protected function _render_cell( $k, $content, $header_label = '' )
	{
		$cell_content = $this->app->make('/html/element')->tag('div')
			->add( $content )
			;

		$padding_classes = array( 
			'hc-p' . $this->cell_padding,
			'hc-px' . $this->cell_padding . '-xs',
			'hc-py1-xs',
			);
		
		foreach( $padding_classes as $c ){
			$cell_content
				->add_attr('class', $c)
				;
		}

		if( isset($this->style[$k]) ){
			$cell_content
				->add_attr( 'style', $this->style[$k] )
				;
		}

		if( ! strlen($header_label) ){
			return $cell_content;
		}

		$out = $this->app->make('/html/element')->tag('div')
			->add_attr('class', 'hc-py1')
			;

		$cell_header = $this->app->make('/html/element')->tag('div')
			->add( $header_label )

			->add_attr('class', 'hc-fs1')
			->add_attr('class', 'hc-muted2')
			->add_attr('class', 'hc-lg-hide')

			->add_attr('class', 'hc-p1-xs')
			;

		$out
			->add( $cell_header )
			;
		if( strlen($content) ){
			$out
				->add( $cell_content )
				;
		}
		
		return $out;
	}

	protected function _render_row()
	{
		$out = $this->app->make('/html/grid')
			;
		return $out;
	}

	protected function _render_tbody()
	{
		$out = $this->app->make('/html/element')->tag('tbody');
		return $out;
	}

	public function generate_row( $row )
	{
		$tr = $this->_render_row();

		$header = $this->header();
		$widths = $this->widths( count($header) );
		$customWidth = $this->width;

		$keys = array_keys($header);
		for( $ki = 0; $ki < count($keys); $ki++ ){
			$k = $keys[$ki];

			if( isset($customWidth[$k]) ){
				$w = $customWidth[$k];
			}
			else {
				$w = isset($widths[$ki]) ? $widths[$ki] : 1;
			}

			$v = isset($row[$k]) ? $row[$k] : '';
			$tr->add( $v, $w );
			// $tr->add( $w, $w );
		}

		$tr = $this->app->make('/html/element')->tag('div')
			->add( $tr )
			;

		return $tr;
	}

	function render()
	{
		$header = $this->header();

		$col_count = count($header);
		$rows = $this->rows();

	// prerender
		foreach( $header as $k => $v ){
			if( $v !== NULL ){
				$header[$k] = '' . $v;
			}
			else {
				$header[$k] = $v;
			}
		}

		foreach( $rows as $rid => $row ){
			foreach( $row as $k => $v ){
				if( is_array($v) ){
					$v = join('', $v);
				}
				$rows[$rid][$k] = '' . $v;
			}
		}

		$full_out = $this->app->make('/html/element')->tag('div')
			// ->add_attr('class', 'hc-border')
			;

	// header
		$row_cells = array();

	// if all null then we don't need header
		$show_header = FALSE;
		reset( $header );
		foreach( $header as $k => $hv ){
			if( $hv !== NULL ){
				$show_header = TRUE;
				break;
			}
		}

		if( $show_header ){
			reset( $header );
			foreach( $header as $k => $hv ){
				$td = $this->_render_cell( $k, $hv );
				$row_cells[$k] = $td;
			}

			$tr = $this->generate_row($row_cells)
				->add_attr('class', 'hc-xs-hide')
				->add_attr('class', 'hc-fs4')
				->add_attr('style', 'line-height: 1.5em;')
				;

			if( $this->is_wp_admin ){
				$tr
					->add_attr('class', 'hc-bg-white')
					;
			}

			$header_row = clone $tr;

			$full_out->add(
				$tr
					->add_attr('class', 'hc-border-bottom')
				);
		}

	// rows
		$rri = 0;
		foreach( $rows as $rid => $row ){
			$rri++;

			$row_cells = array();
			reset( $header );
			$hii = 0;
			foreach( $header as $k => $hv ){
				$v = NULL;
				if( isset($row[$k . '_view']) ){
					$v = $row[$k . '_view'];
				}
				elseif( isset($row[$k]) ){
					$v = $row[$k];
				}

			// skip labels for certain cells
				// first one
				if( (! $hii) && in_array($k, array('title')) ){
					$hv = '';
				}

				$td = $this->_render_cell( $k, $v, $hv );
				$row_cells[$k] = $td;
				$hii++;
			}

			$tr = $this->generate_row($row_cells);

			if( $this->striped ){
				if( $this->is_wp_admin ){
					if( $rri % 2 ){
						$tr->add_attr('class', 'hc-bg-wpsilver');
					}
					else {
						$tr->add_attr('class', 'hc-bg-white');
					}
				}
				else {
					if( $rri % 2 ){
						$tr->add_attr('class', 'hc-bg-lightsilver');
					}
					else {
						// $tr->add_attr('class', 'hc-bg-white');
					}
				}
			}

			$full_out->add( $tr );
		}

	// copy from header
		if( $show_header ){
			if( ! $this->no_footer ){
				$footer_row = $header_row
					->add_attr('class', 'hc-border-top')
					;
				$full_out->add( $footer_row );
			}
		}

		return $full_out;
	}
}