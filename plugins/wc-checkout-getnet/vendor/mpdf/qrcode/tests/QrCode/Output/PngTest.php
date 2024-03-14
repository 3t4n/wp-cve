<?php
/**
 * @license LGPL-2.1-or-later
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\Mpdf\QrCode\Output;

use CoffeeCode\Mpdf\QrCode\QrCode;

/**
 * @group unit
 */
class PngTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{

	public function testOutput()
	{
		$code = new QrCode('LOREM IPSUM 2019');

		$output = new Png();

		$data = $output->output($code);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L-100.png';
		file_put_contents($filename, $data);
		$this->assertSame($data, file_get_contents($filename));

		$data = $output->output($code, 250);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L-250.png';
		file_put_contents($filename, $data);
		$this->assertSame($data, file_get_contents($filename));

		$code->disableBorder();

		$data = $output->output($code, 250);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L-250-noborder.png';
		file_put_contents($filename, $data);
		$this->assertSame($data, file_get_contents($filename));

		$code = new QrCode('LOREM IPSUM 2019', QrCode::ERROR_CORRECTION_QUARTILE);

		$data = $output->output($code);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-Q-100.png';
		file_put_contents($filename, $data);
		$this->assertSame($data, file_get_contents($filename));

		$data = $output->output($code, 250);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-Q-250.png';
		file_put_contents($filename, $data);
		$this->assertSame($data, file_get_contents($filename));
	}
}
