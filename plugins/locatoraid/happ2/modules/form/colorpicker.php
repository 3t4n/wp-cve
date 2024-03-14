<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
class Form_Colorpicker_HC_MVC extends _HC_MVC implements Form_Input_Interface_HC_MVC
{
	protected $colors = array(
		'#ffb3a7',
		'#cbe86b',
		'#89c4f4',
		'#f5d76e',
		'#be90d4',
		'#fcf13a',
		'#ffffbb',
		'#ffbbff',
		'#87d37c',
		'#ff8000',
		'#73faa9',
		'#c8e9fc',
		'#cb9987',
		'#cfd8dc',
		'#99bb99',
		'#99bbbb',
		'#bbbbff',
		'#dcedc8',

		'#800000',
		'#8b0000',
		'#a52a2a',
		'#b22222',
		'#dc143c',
		'#ff0000',
		'#ff6347',
		'#ff7f50',
		'#cd5c5c',
		'#f08080',
		'#e9967a',
		'#fa8072',
		'#ffa07a',
		'#ff4500',
		'#ff8c00',
		'#ffa500',
		'#ffd700',
		'#b8860b',
		'#daa520',
		'#eee8aa',
		'#bdb76b',
		'#f0e68c',
		'#808000',
		'#ffff00',
		'#9acd32',
		'#556b2f',
		'#6b8e23',
		'#7cfc00',
		'#7fff00',
		'#adff2f',
		'#006400',
		'#008000',
		'#228b22',
		'#00ff00',
		'#32cd32',
		'#90ee90',
		'#98fb98',
		'#8fbc8f',
		'#00fa9a',
		'#00ff7f',
		'#2e8b57',
		'#66cdaa',
		'#3cb371',
		'#20b2aa',
		'#2f4f4f',
		'#008080',
		'#008b8b',
		'#00ffff',
		'#00ffff',
		'#e0ffff',
		'#00ced1',
		'#40e0d0',
		'#48d1cc',
		'#afeeee',
		'#7fffd4',
		'#b0e0e6',
		'#5f9ea0',
		'#4682b4',
		'#6495ed',
		'#00bfff',
		'#1e90ff',
		'#add8e6',
		'#87ceeb',
		'#87cefa',
		'#191970',
		'#000080',
		'#00008b',
		'#0000cd',
		'#0000ff',
		'#4169e1',
		'#8a2be2',
		'#4b0082',
		'#483d8b',
		'#6a5acd',
		'#7b68ee',
		'#9370db',
		'#8b008b',
		'#9400d3',
		'#9932cc',
		'#ba55d3',
		'#800080',
		'#d8bfd8',
		'#dda0dd',
		'#ee82ee',
		'#ff00ff',
		'#da70d6',
		'#c71585',
		'#db7093',
		'#ff1493',
		'#ff69b4',
		'#ffb6c1',
		'#ffc0cb',
		'#faebd7',
		'#f5f5dc',
		'#ffe4c4',
		'#ffebcd',
		'#f5deb3',
		'#fff8dc',
		'#fffacd',
		'#fafad2',
		'#ffffe0',
		'#8b4513',
		'#a0522d',
		'#d2691e',
		'#cd853f',
		'#f4a460',
		'#deb887',
		'#d2b48c',
		'#bc8f8f',
		'#ffe4b5',
		'#ffdead',
		'#ffdab9',
		'#ffe4e1',
		'#fff0f5',
		'#faf0e6',
		'#fdf5e6',
		'#ffefd5',
		'#fff5ee',
		'#f5fffa',
		'#708090',
		'#778899',
		'#b0c4de',
		'#e6e6fa',
		'#fffaf0',
		'#f0f8ff',
		'#f8f8ff',
		'#f0fff0',
		'#fffff0',
		'#f0ffff',
		'#fffafa',
		'#696969',
		'#808080',
		'#a9a9a9',
		'#c0c0c0',
		'#d3d3d3',
		'#dcdcdc',
		'#f5f5f5',
	);

	public function grab( $name, $post )
	{
		$return = $this->app->make('/form/hidden')
			->grab($name, $post)
			;
		return $return;
	}

	public function render( $name, $value = NULL )
	{
		$hidden = $this->app->make('/form/hidden')
			->render( $name, $value )
			->add_attr('class', 'hcj2-color-picker-value')
			;

		$title = $this->app->make('/html/element')->tag('a')
			->add('&nbsp;')
			->add_attr('class', 'hc-btn')
			->add_attr('class', 'hc-border')
			->add_attr('class', 'hc-p1')
			->add_attr('style', 'background-color: ' . $value . ';')
			->add_attr('style', 'width: 2em;')
			->add_attr('class', 'hcj2-color-picker-display')
			;

		$title = $this->app->make('/html/element')->tag('span')
			->add('&nbsp;&nbsp;')

			->add_attr('class', 'hc-btn')
			->add_attr('style', 'background-color: ' . $value . ';')

			->add_attr('class', 'hc-inline-block')
			->add_attr('class', 'hc-m1')
			->add_attr('class', 'hc-px2')
			->add_attr('class', 'hc-py1')

			->add_attr('class', 'hc-border')
			->add_attr('class', 'hc-rounded')
			
			->add_attr('class', 'hcj2-color-picker-display')
			;

		$options = $this->app->make('/html/list-inline')
			->set_gutter(0)
			;

		foreach( $this->colors as $color ){
			$option = $this->app->make('/html/element')->tag('a')
				->add('&nbsp;&nbsp;')

				->add_attr('class', 'hc-btn')
				->add_attr('style', 'background-color: ' . $color . ';')

				->add_attr('class', 'hc-inline-block')
				->add_attr('class', 'hc-m1')
				->add_attr('class', 'hc-px2')
				->add_attr('class', 'hc-py1')

				->add_attr('class', 'hc-border')
				->add_attr('class', 'hc-rounded')
				
				->add_attr('data-color', $color)
				->add_attr('class', 'hcj2-color-picker-selector')
				->add_attr('class', 'hcj2-collapse-closer')
				;

			$options->add( $option );
		}

		$display = $this->app->make('/html/collapse')
			->set_title( $title )
			->set_content( $options )
			;

		$out = $this->app->make('/html/element')->tag('div')
			->add_attr('class', 'hcj2-color-picker')
			->add( $hidden )
			->add( $display )
			;

		return $out;
	}
}