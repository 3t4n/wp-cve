<?php

/**
 * This file is part of FPDI
 *
 * @package   setasign\Fpdi
 * @copyright Copyright (c) 2023 Setasign GmbH & Co. KG (https://www.setasign.com)
 * @license   http://opensource.org/licenses/mit-license The MIT License
 */
namespace WPDeskFIVendor\setasign\Fpdi;

use WPDeskFIVendor\setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Filter\FilterException;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\PdfParser;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\PdfParserException;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\StreamReader;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfArray;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfBoolean;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfDictionary;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfHexString;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfIndirectObject;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfIndirectObjectReference;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfName;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNull;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfStream;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfString;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfToken;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfType;
use WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfTypeException;
use WPDeskFIVendor\setasign\Fpdi\PdfReader\DataStructure\Rectangle;
use WPDeskFIVendor\setasign\Fpdi\PdfReader\PageBoundaries;
use WPDeskFIVendor\setasign\Fpdi\PdfReader\PdfReader;
use WPDeskFIVendor\setasign\Fpdi\PdfReader\PdfReaderException;
use WPDeskFIVendor\setasign\FpdiPdfParser\PdfParser\PdfParser as FpdiPdfParser;
/**
 * The FpdiTrait
 *
 * This trait offers the core functionalities of FPDI. By passing them to a trait we can reuse it with e.g. TCPDF in a
 * very easy way.
 */
trait FpdiTrait
{
    /**
     * The pdf reader instances.
     *
     * @var PdfReader[]
     */
    protected $readers = [];
    /**
     * Instances created internally.
     *
     * @var array
     */
    protected $createdReaders = [];
    /**
     * The current reader id.
     *
     * @var string|null
     */
    protected $currentReaderId;
    /**
     * Data of all imported pages.
     *
     * @var array
     */
    protected $importedPages = [];
    /**
     * A map from object numbers of imported objects to new assigned object numbers by FPDF.
     *
     * @var array
     */
    protected $objectMap = [];
    /**
     * An array with information about objects, which needs to be copied to the resulting document.
     *
     * @var array
     */
    protected $objectsToCopy = [];
    /**
     * Release resources and file handles.
     *
     * This method is called internally when the document is created successfully. By default it only cleans up
     * stream reader instances which were created internally.
     *
     * @param bool $allReaders
     */
    public function cleanUp($allReaders = \false)
    {
        $readers = $allReaders ? \array_keys($this->readers) : $this->createdReaders;
        foreach ($readers as $id) {
            $this->readers[$id]->getParser()->getStreamReader()->cleanUp();
            unset($this->readers[$id]);
        }
        $this->createdReaders = [];
    }
    /**
     * Set the minimal PDF version.
     *
     * @param string $pdfVersion
     */
    protected function setMinPdfVersion($pdfVersion)
    {
        if (\version_compare($pdfVersion, $this->PDFVersion, '>')) {
            $this->PDFVersion = $pdfVersion;
        }
    }
    /** @noinspection PhpUndefinedClassInspection */
    /**
     * Get a new pdf parser instance.
     *
     * @param StreamReader $streamReader
     * @param array $parserParams Individual parameters passed to the parser instance.
     * @return PdfParser|FpdiPdfParser
     */
    protected function getPdfParserInstance(\WPDeskFIVendor\setasign\Fpdi\PdfParser\StreamReader $streamReader, array $parserParams = [])
    {
        // note: if you get an exception here - turn off errors/warnings on not found classes for your autoloader.
        // psr-4 (https://www.php-fig.org/psr/psr-4/) says: Autoloader implementations MUST NOT throw
        // exceptions, MUST NOT raise errors of any level, and SHOULD NOT return a value.
        /** @noinspection PhpUndefinedClassInspection */
        if (\class_exists(\WPDeskFIVendor\setasign\FpdiPdfParser\PdfParser\PdfParser::class)) {
            /** @noinspection PhpUndefinedClassInspection */
            return new \WPDeskFIVendor\setasign\FpdiPdfParser\PdfParser\PdfParser($streamReader, $parserParams);
        }
        return new \WPDeskFIVendor\setasign\Fpdi\PdfParser\PdfParser($streamReader);
    }
    /**
     * Get an unique reader id by the $file parameter.
     *
     * @param string|resource|PdfReader|StreamReader $file An open file descriptor, a path to a file, a PdfReader
     *                                                     instance or a StreamReader instance.
     * @param array $parserParams Individual parameters passed to the parser instance.
     * @return string
     */
    protected function getPdfReaderId($file, array $parserParams = [])
    {
        if (\is_resource($file)) {
            $id = (string) $file;
        } elseif (\is_string($file)) {
            $id = \realpath($file);
            if ($id === \false) {
                $id = $file;
            }
        } elseif (\is_object($file)) {
            $id = \spl_object_hash($file);
        } else {
            throw new \InvalidArgumentException(\sprintf('Invalid type in $file parameter (%s)', \gettype($file)));
        }
        /** @noinspection OffsetOperationsInspection */
        if (isset($this->readers[$id])) {
            return $id;
        }
        if (\is_resource($file)) {
            $streamReader = new \WPDeskFIVendor\setasign\Fpdi\PdfParser\StreamReader($file);
        } elseif (\is_string($file)) {
            $streamReader = \WPDeskFIVendor\setasign\Fpdi\PdfParser\StreamReader::createByFile($file);
            $this->createdReaders[] = $id;
        } else {
            $streamReader = $file;
        }
        $reader = new \WPDeskFIVendor\setasign\Fpdi\PdfReader\PdfReader($this->getPdfParserInstance($streamReader, $parserParams));
        /** @noinspection OffsetOperationsInspection */
        $this->readers[$id] = $reader;
        return $id;
    }
    /**
     * Get a pdf reader instance by its id.
     *
     * @param string $id
     * @return PdfReader
     */
    protected function getPdfReader($id)
    {
        if (isset($this->readers[$id])) {
            return $this->readers[$id];
        }
        throw new \InvalidArgumentException(\sprintf('No pdf reader with the given id (%s) exists.', $id));
    }
    /**
     * Set the source PDF file.
     *
     * @param string|resource|StreamReader $file Path to the file or a stream resource or a StreamReader instance.
     * @return int The page count of the PDF document.
     * @throws PdfParserException
     */
    public function setSourceFile($file)
    {
        return $this->setSourceFileWithParserParams($file);
    }
    /**
     * Set the source PDF file with parameters which are passed to the parser instance.
     *
     * This method allows us to pass e.g. authentication information to the parser instance.
     *
     * @param string|resource|StreamReader $file Path to the file or a stream resource or a StreamReader instance.
     * @param array $parserParams Individual parameters passed to the parser instance.
     * @return int The page count of the PDF document.
     * @throws CrossReferenceException
     * @throws PdfParserException
     * @throws PdfTypeException
     */
    public function setSourceFileWithParserParams($file, array $parserParams = [])
    {
        $this->currentReaderId = $this->getPdfReaderId($file, $parserParams);
        $this->objectsToCopy[$this->currentReaderId] = [];
        $reader = $this->getPdfReader($this->currentReaderId);
        $this->setMinPdfVersion($reader->getPdfVersion());
        return $reader->getPageCount();
    }
    /**
     * Imports a page.
     *
     * @param int $pageNumber The page number.
     * @param string $box The page boundary to import. Default set to PageBoundaries::CROP_BOX.
     * @param bool $groupXObject Define the form XObject as a group XObject to support transparency (if used).
     * @param bool $importExternalLinks Define whether external links are imported or not.
     * @return string A unique string identifying the imported page.
     * @throws CrossReferenceException
     * @throws FilterException
     * @throws PdfParserException
     * @throws PdfTypeException
     * @throws PdfReaderException
     * @see PageBoundaries
     */
    public function importPage($pageNumber, $box = \WPDeskFIVendor\setasign\Fpdi\PdfReader\PageBoundaries::CROP_BOX, $groupXObject = \true, $importExternalLinks = \false)
    {
        if ($this->currentReaderId === null) {
            throw new \BadMethodCallException('No reader initiated. Call setSourceFile() first.');
        }
        $pageId = $this->currentReaderId;
        $pageNumber = (int) $pageNumber;
        $pageId .= '|' . $pageNumber . '|' . ($groupXObject ? '1' : '0') . '|' . ($importExternalLinks ? '1' : '0');
        // for backwards compatibility with FPDI 1
        $box = \ltrim($box, '/');
        if (!\WPDeskFIVendor\setasign\Fpdi\PdfReader\PageBoundaries::isValidName($box)) {
            throw new \InvalidArgumentException(\sprintf('Box name is invalid: "%s"', $box));
        }
        $pageId .= '|' . $box;
        if (isset($this->importedPages[$pageId])) {
            return $pageId;
        }
        $reader = $this->getPdfReader($this->currentReaderId);
        $page = $reader->getPage($pageNumber);
        $bbox = $page->getBoundary($box);
        if ($bbox === \false) {
            throw new \WPDeskFIVendor\setasign\Fpdi\PdfReader\PdfReaderException(\sprintf("Page doesn't have a boundary box (%s).", $box), \WPDeskFIVendor\setasign\Fpdi\PdfReader\PdfReaderException::MISSING_DATA);
        }
        $dict = new \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfDictionary();
        $dict->value['Type'] = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfName::create('XObject');
        $dict->value['Subtype'] = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfName::create('Form');
        $dict->value['FormType'] = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric::create(1);
        $dict->value['BBox'] = $bbox->toPdfArray();
        if ($groupXObject) {
            $this->setMinPdfVersion('1.4');
            $dict->value['Group'] = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfDictionary::create(['Type' => \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfName::create('Group'), 'S' => \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfName::create('Transparency')]);
        }
        $resources = $page->getAttribute('Resources');
        if ($resources !== null) {
            $dict->value['Resources'] = $resources;
        }
        list($width, $height) = $page->getWidthAndHeight($box);
        $a = 1;
        $b = 0;
        $c = 0;
        $d = 1;
        $e = -$bbox->getLlx();
        $f = -$bbox->getLly();
        $rotation = $page->getRotation();
        if ($rotation !== 0) {
            $rotation *= -1;
            $angle = $rotation * \M_PI / 180;
            $a = \cos($angle);
            $b = \sin($angle);
            $c = -$b;
            $d = $a;
            switch ($rotation) {
                case -90:
                    $e = -$bbox->getLly();
                    $f = $bbox->getUrx();
                    break;
                case -180:
                    $e = $bbox->getUrx();
                    $f = $bbox->getUry();
                    break;
                case -270:
                    $e = $bbox->getUry();
                    $f = -$bbox->getLlx();
                    break;
            }
        }
        // we need to rotate/translate
        if ($a != 1 || $b != 0 || $c != 0 || $d != 1 || $e != 0 || $f != 0) {
            $dict->value['Matrix'] = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfArray::create([\WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric::create($a), \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric::create($b), \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric::create($c), \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric::create($d), \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric::create($e), \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric::create($f)]);
        }
        // try to use the existing content stream
        $pageDict = $page->getPageDictionary();
        try {
            $contentsObject = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfType::resolve(\WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfDictionary::get($pageDict, 'Contents'), $reader->getParser(), \true);
            $contents = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfType::resolve($contentsObject, $reader->getParser());
            // just copy the stream reference if it is only a single stream
            if (($contentsIsStream = $contents instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfStream) || $contents instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfArray && \count($contents->value) === 1) {
                if ($contentsIsStream) {
                    /**
                     * @var PdfIndirectObject $contentsObject
                     */
                    $stream = $contents;
                } else {
                    $stream = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfType::resolve($contents->value[0], $reader->getParser());
                }
                $filter = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfDictionary::get($stream->value, 'Filter');
                if (!$filter instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNull) {
                    $dict->value['Filter'] = $filter;
                }
                $length = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfType::resolve(\WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfDictionary::get($stream->value, 'Length'), $reader->getParser());
                $dict->value['Length'] = $length;
                $stream->value = $dict;
                // otherwise extract it from the array and re-compress the whole stream
            } else {
                $streamContent = $this->compress ? \gzcompress($page->getContentStream()) : $page->getContentStream();
                $dict->value['Length'] = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric::create(\strlen($streamContent));
                if ($this->compress) {
                    $dict->value['Filter'] = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfName::create('FlateDecode');
                }
                $stream = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfStream::create($dict, $streamContent);
            }
            // Catch faulty pages and use an empty content stream
        } catch (\WPDeskFIVendor\setasign\Fpdi\FpdiException $e) {
            $dict->value['Length'] = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric::create(0);
            $stream = \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfStream::create($dict, '');
        }
        $externalLinks = [];
        if ($importExternalLinks) {
            $externalLinks = $page->getExternalLinks($box);
        }
        $this->importedPages[$pageId] = ['objectNumber' => null, 'readerId' => $this->currentReaderId, 'id' => 'TPL' . $this->getNextTemplateId(), 'width' => $width / $this->k, 'height' => $height / $this->k, 'stream' => $stream, 'externalLinks' => $externalLinks];
        return $pageId;
    }
    /**
     * Draws an imported page onto the page.
     *
     * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
     * aspect ratio.
     *
     * @param mixed $pageId The page id
     * @param float|int|array $x The abscissa of upper-left corner. Alternatively you could use an assoc array
     *                           with the keys "x", "y", "width", "height", "adjustPageSize".
     * @param float|int $y The ordinate of upper-left corner.
     * @param float|int|null $width The width.
     * @param float|int|null $height The height.
     * @param bool $adjustPageSize
     * @return array The size.
     * @see Fpdi::getTemplateSize()
     */
    public function useImportedPage($pageId, $x = 0, $y = 0, $width = null, $height = null, $adjustPageSize = \false)
    {
        if (\is_array($x)) {
            /** @noinspection OffsetOperationsInspection */
            unset($x['pageId']);
            \extract($x, \EXTR_IF_EXISTS);
            /** @noinspection NotOptimalIfConditionsInspection */
            if (\is_array($x)) {
                $x = 0;
            }
        }
        if (!isset($this->importedPages[$pageId])) {
            throw new \InvalidArgumentException('Imported page does not exist!');
        }
        $importedPage = $this->importedPages[$pageId];
        $originalSize = $this->getTemplateSize($pageId);
        $newSize = $this->getTemplateSize($pageId, $width, $height);
        if ($adjustPageSize) {
            $this->setPageFormat($newSize, $newSize['orientation']);
        }
        $scaleX = $newSize['width'] / $originalSize['width'];
        $scaleY = $newSize['height'] / $originalSize['height'];
        $xPt = $x * $this->k;
        $yPt = $y * $this->k;
        $newHeightPt = $newSize['height'] * $this->k;
        $this->_out(
            // reset standard values, translate and scale
            \sprintf('q 0 J 1 w 0 j 0 G 0 g %.4F 0 0 %.4F %.4F %.4F cm /%s Do Q', $scaleX, $scaleY, $xPt, $this->hPt - $yPt - $newHeightPt, $importedPage['id'])
        );
        if (\count($importedPage['externalLinks']) > 0) {
            foreach ($importedPage['externalLinks'] as $externalLink) {
                // mPDF uses also 'externalLinks' but doesn't come with a rect-value
                if (!isset($externalLink['rect'])) {
                    continue;
                }
                /** @var Rectangle $rect */
                $rect = $externalLink['rect'];
                $this->Link($x + $rect->getLlx() / $this->k * $scaleX, $y + $newSize['height'] - ($rect->getLly() + $rect->getHeight()) / $this->k * $scaleY, $rect->getWidth() / $this->k * $scaleX, $rect->getHeight() / $this->k * $scaleY, $externalLink['uri']);
                $this->adjustLastLink($externalLink, $xPt, $scaleX, $yPt, $newHeightPt, $scaleY, $importedPage);
            }
        }
        return $newSize;
    }
    /**
     * This method will add additional data to the last created link/annotation.
     *
     * It is separated because TCPDF uses its own logic to handle link annotations.
     * This method is overwritten in the TCPDF implementation.
     *
     * @param array $externalLink
     * @param float|int $xPt
     * @param float|int $scaleX
     * @param float|int $yPt
     * @param float|int $newHeightPt
     * @param float|int $scaleY
     * @param array $importedPage
     * @return void
     */
    protected function adjustLastLink($externalLink, $xPt, $scaleX, $yPt, $newHeightPt, $scaleY, $importedPage)
    {
        // let's create a relation of the newly created link to the data of the external link
        $lastLink = \count($this->PageLinks[$this->page]);
        $this->PageLinks[$this->page][$lastLink - 1]['importedLink'] = $externalLink;
        if (\count($externalLink['quadPoints']) > 0) {
            $quadPoints = [];
            for ($i = 0, $n = \count($externalLink['quadPoints']); $i < $n; $i += 2) {
                $quadPoints[] = $xPt + $externalLink['quadPoints'][$i] * $scaleX;
                $quadPoints[] = $this->hPt - $yPt - $newHeightPt + $externalLink['quadPoints'][$i + 1] * $scaleY;
            }
            $this->PageLinks[$this->page][$lastLink - 1]['quadPoints'] = $quadPoints;
        }
    }
    /**
     * Get the size of an imported page.
     *
     * Give only one of the size parameters (width, height) to calculate the other one automatically in view to the
     * aspect ratio.
     *
     * @param mixed $tpl The template id
     * @param float|int|null $width The width.
     * @param float|int|null $height The height.
     * @return array|bool An array with following keys: width, height, 0 (=width), 1 (=height), orientation (L or P)
     */
    public function getImportedPageSize($tpl, $width = null, $height = null)
    {
        if (isset($this->importedPages[$tpl])) {
            $importedPage = $this->importedPages[$tpl];
            if ($width === null && $height === null) {
                $width = $importedPage['width'];
                $height = $importedPage['height'];
            } elseif ($width === null) {
                $width = $height * $importedPage['width'] / $importedPage['height'];
            }
            if ($height === null) {
                $height = $width * $importedPage['height'] / $importedPage['width'];
            }
            if ($height <= 0.0 || $width <= 0.0) {
                throw new \InvalidArgumentException('Width or height parameter needs to be larger than zero.');
            }
            return ['width' => $width, 'height' => $height, 0 => $width, 1 => $height, 'orientation' => $width > $height ? 'L' : 'P'];
        }
        return \false;
    }
    /**
     * Writes a PdfType object to the resulting buffer.
     *
     * @param PdfType $value
     * @throws PdfTypeException
     */
    protected function writePdfType(\WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfType $value)
    {
        if ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNumeric) {
            if (\is_int($value->value)) {
                $this->_put($value->value . ' ', \false);
            } else {
                $this->_put(\rtrim(\rtrim(\sprintf('%.5F', $value->value), '0'), '.') . ' ', \false);
            }
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfName) {
            $this->_put('/' . $value->value . ' ', \false);
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfString) {
            $this->_put('(' . $value->value . ')', \false);
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfHexString) {
            $this->_put('<' . $value->value . '>', \false);
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfBoolean) {
            $this->_put($value->value ? 'true ' : 'false ', \false);
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfArray) {
            $this->_put('[', \false);
            foreach ($value->value as $entry) {
                $this->writePdfType($entry);
            }
            $this->_put(']');
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfDictionary) {
            $this->_put('<<', \false);
            foreach ($value->value as $name => $entry) {
                $this->_put('/' . $name . ' ', \false);
                $this->writePdfType($entry);
            }
            $this->_put('>>');
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfToken) {
            $this->_put($value->value);
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfNull) {
            $this->_put('null ', \false);
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfStream) {
            $this->writePdfType($value->value);
            $this->_put('stream');
            $this->_put($value->getStream());
            $this->_put('endstream');
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfIndirectObjectReference) {
            if (!isset($this->objectMap[$this->currentReaderId])) {
                $this->objectMap[$this->currentReaderId] = [];
            }
            if (!isset($this->objectMap[$this->currentReaderId][$value->value])) {
                $this->objectMap[$this->currentReaderId][$value->value] = ++$this->n;
                $this->objectsToCopy[$this->currentReaderId][] = $value->value;
            }
            $this->_put($this->objectMap[$this->currentReaderId][$value->value] . ' 0 R ', \false);
        } elseif ($value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfIndirectObject) {
            $n = $this->objectMap[$this->currentReaderId][$value->objectNumber];
            $this->_newobj($n);
            $this->writePdfType($value->value);
            // add newline before "endobj" for all objects in view to PDF/A conformance
            if (!($value->value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfArray || $value->value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfDictionary || $value->value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfToken || $value->value instanceof \WPDeskFIVendor\setasign\Fpdi\PdfParser\Type\PdfStream)) {
                $this->_put("\n", \false);
            }
            $this->_put('endobj');
        }
    }
}
