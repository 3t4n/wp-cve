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
class SvgTest extends \Yoast\PHPUnitPolyfills\TestCases\TestCase
{
	public function testOutput()
	{
		$code = new QrCode('LOREM IPSUM 2019');

		$output = new Svg();

		$data = $output->output($code);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L.svg';
		$this->assertStringStartsWith('<?xml', $data); // @todo solve line endings in GitHub Windows CI and test against reference

		$code->disableBorder();

		$data = $output->output($code);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-L-noborder.svg';
		$this->assertStringStartsWith('<?xml', $data);

		$code = new QrCode('LOREM IPSUM 2019', QrCode::ERROR_CORRECTION_QUARTILE);

		$data = $output->output($code);

		$filename = __DIR__ . '/../../reference/LOREM-IPSUM-2019-Q.svg';
		$this->assertStringStartsWith('<?xml', $data);
	}
}
