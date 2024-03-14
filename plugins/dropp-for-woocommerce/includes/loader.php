<?php

$classes = array(
	'TCPDF'                  => __DIR__ . '/tecnickcom/tcpdf/tcpdf.php',
	'TCPDF2DBarcode'         => __DIR__ . '/tecnickcom/tcpdf/tcpdf_barcodes_2d.php',
	'TCPDFBarcode'           => __DIR__ . '/tecnickcom/tcpdf/tcpdf_barcodes_1d.php',
	'TCPDF_COLORS'           => __DIR__ . '/tecnickcom/tcpdf/include/tcpdf_colors.php',
	'TCPDF_FILTERS'          => __DIR__ . '/tecnickcom/tcpdf/include/tcpdf_filters.php',
	'TCPDF_FONTS'            => __DIR__ . '/tecnickcom/tcpdf/include/tcpdf_fonts.php',
	'TCPDF_FONT_DATA'        => __DIR__ . '/tecnickcom/tcpdf/include/tcpdf_font_data.php',
	'TCPDF_IMAGES'           => __DIR__ . '/tecnickcom/tcpdf/include/tcpdf_images.php',
	'TCPDF_IMPORT'           => __DIR__ . '/tecnickcom/tcpdf/tcpdf_import.php',
	'TCPDF_PARSER'           => __DIR__ . '/tecnickcom/tcpdf/tcpdf_parser.php',
	'TCPDF_STATIC'           => __DIR__ . '/tecnickcom/tcpdf/include/tcpdf_static.php',
	'TCPDI'                  => __DIR__ . '/iio/libmergepdf/tcpdi/tcpdi.php',
	'Datamatrix'             => __DIR__ . '/tecnickcom/tcpdf/include/barcodes/datamatrix.php',
	'FPDF'                   => __DIR__ . '/iio/libmergepdf/tcpdi/tcpdi.php',
	'FPDF_TPL'               => __DIR__ . '/iio/libmergepdf/tcpdi/fpdf_tpl.php',
	'PDF417'                 => __DIR__ . '/tecnickcom/tcpdf/include/barcodes/pdf417.php',
	'QRcode'                 => __DIR__ . '/tecnickcom/tcpdf/include/barcodes/qrcode.php',
	'tcpdi_parser'           => __DIR__ . '/iio/libmergepdf/tcpdi/tcpdi_parser.php',
	'PagesInterface'         => __DIR__ . '/iio/libmergepdf/src/PagesInterface.php',
	'Pages'                  => __DIR__ . '/iio/libmergepdf/src/Pages.php',
	'Exception'              => __DIR__ . '/iio/libmergepdf/src/Exception.php',
	'Merger'                 => __DIR__ . '/iio/libmergepdf/src/Merger.php',
	'Driver/DriverInterface' => __DIR__ . '/iio/libmergepdf/src/Driver/DriverInterface.php',
	'Driver/DefaultDriver'   => __DIR__ . '/iio/libmergepdf/src/Driver/DefaultDriver.php',
	'Driver/TcpdiDriver'     => __DIR__ . '/iio/libmergepdf/src/Driver/TcpdiDriver.php',
	'Driver/Fpdi2Driver'     => __DIR__ . '/iio/libmergepdf/src/Driver/Fpdi2Driver.php',
	'Source/SourceInterface' => __DIR__ . '/iio/libmergepdf/src/Source/SourceInterface.php',
	'Source/FileSource'      => __DIR__ . '/iio/libmergepdf/src/Source/FileSource.php',
	'Source/RawSource'       => __DIR__ . '/iio/libmergepdf/src/Source/RawSource.php',
	'fpdi'                   => __DIR__ . '/setasign/fpdi/src/autoload.php',
);

foreach ( $classes as $file ) {
	require_once $file;
}
