<?php
namespace Bpost;

use Bpost\BpostApiClient\Bpost\Order\Box\CustomsInfo\CustomsInfo;
use Bpost\BpostApiClient\Exception\BpostLogicException\BpostInvalidLengthException;
use Bpost\BpostApiClient\Exception\BpostLogicException\BpostInvalidValueException;

class CustomsInfoTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Create a generic DOM Document
     *
     * @return \DOMDocument
     */
    private static function createDomDocument()
    {
        $document = new \DOMDocument('1.0', 'utf-8');
        $document->preserveWhiteSpace = false;
        $document->formatOutput = true;

        return $document;
    }

    /**
     * Tests Day->toXML
     */
    public function testToXML()
    {
        $data = array(
            'parcelValue' => '700',
            'contentDescription' => 'BOOK',
            'shipmentType' => 'DOCUMENTS',
            'parcelReturnInstructions' => 'RTS',
            'privateAddress' => false,
        );

        $expectedDocument = self::createDomDocument();
        $customsInfo = $expectedDocument->createElement('customsInfo');
        foreach ($data as $key => $value) {
            if ($key == 'privateAddress') {
                $value = ($value) ? 'true' : 'false';
            }
            $customsInfo->appendChild(
                $expectedDocument->createElement($key, $value)
            );
        }
        $expectedDocument->appendChild($customsInfo);

        $actualDocument = self::createDomDocument();
        $customsInfo = new CustomsInfo();
        $customsInfo->setParcelValue($data['parcelValue']);
        $customsInfo->setContentDescription($data['contentDescription']);
        $customsInfo->setShipmentType($data['shipmentType']);
        $customsInfo->setParcelReturnInstructions($data['parcelReturnInstructions']);
        $customsInfo->setPrivateAddress($data['privateAddress']);
        $actualDocument->appendChild(
            $customsInfo->toXML($actualDocument, null)
        );
        $this->assertEquals($expectedDocument, $actualDocument);

        $data = array(
            'parcelValue' => '700',
            'contentDescription' => 'BOOK',
            'shipmentType' => 'DOCUMENTS',
            'parcelReturnInstructions' => 'RTS',
            'privateAddress' => true,
        );

        $expectedDocument = self::createDomDocument();
        $customsInfo = $expectedDocument->createElement('customsInfo');
        foreach ($data as $key => $value) {
            if ($key == 'privateAddress') {
                $value = ($value) ? 'true' : 'false';
            }
            $customsInfo->appendChild(
                $expectedDocument->createElement($key, $value)
            );
        }
        $expectedDocument->appendChild($customsInfo);

        $actualDocument = self::createDomDocument();
        $customsInfo = new CustomsInfo();
        $customsInfo->setParcelValue($data['parcelValue']);
        $customsInfo->setContentDescription($data['contentDescription']);
        $customsInfo->setShipmentType($data['shipmentType']);
        $customsInfo->setParcelReturnInstructions($data['parcelReturnInstructions']);
        $customsInfo->setPrivateAddress($data['privateAddress']);
        $actualDocument->appendChild($customsInfo->toXML($actualDocument));
        $this->assertEquals($expectedDocument, $actualDocument);
    }

    /**
     * Tests CustomsInfo->createFromXML
     */
    public function testCreateFromXML()
    {
        $data = array(
            'parcelValue' => 700,
            'contentDescription' => 'BOOK',
            'shipmentType' => 'DOCUMENTS',
            'parcelReturnInstructions' => 'RTS',
            'privateAddress' => null,
        );

        $document = self::createDomDocument();
        $customsInfo = $document->createElement('CustomsInfo');
        foreach ($data as $key => $value) {
            $customsInfo->appendChild(
                $document->createElement($key, $value)
            );
        }
        $document->appendChild($customsInfo);

        $customsInfo = CustomsInfo::createFromXML(
            simplexml_load_string(
                $document->saveXML()
            )
        );

        $this->assertSame($data['parcelValue'], $customsInfo->getParcelValue());
        $this->assertSame($data['parcelReturnInstructions'], $customsInfo->getParcelReturnInstructions());
        $this->assertSame($data['contentDescription'], $customsInfo->getContentDescription());
        $this->assertSame($data['shipmentType'], $customsInfo->getShipmentType());
        $this->assertSame($data['privateAddress'], $customsInfo->getPrivateAddress());

        $data = array(
            'parcelValue' => 700,
            'contentDescription' => 'BOOK',
            'shipmentType' => 'DOCUMENTS',
            'parcelReturnInstructions' => 'RTS',
            'privateAddress' => 'true',
        );

        $document = self::createDomDocument();
        $customsInfo = $document->createElement('CustomsInfo');
        foreach ($data as $key => $value) {
            $customsInfo->appendChild(
                $document->createElement($key, $value)
            );
        }
        $document->appendChild($customsInfo);

        $customsInfo = CustomsInfo::createFromXML(
            simplexml_load_string(
                $document->saveXML()
            )
        );

        $this->assertSame($data['parcelValue'], $customsInfo->getParcelValue());
        $this->assertSame($data['parcelReturnInstructions'], $customsInfo->getParcelReturnInstructions());
        $this->assertSame($data['contentDescription'], $customsInfo->getContentDescription());
        $this->assertSame($data['shipmentType'], $customsInfo->getShipmentType());
        $this->assertSame(($data['privateAddress'] == 'true'), $customsInfo->getPrivateAddress());
    }

    /**
     * Test validation in the setters
     */
    public function testFaultyProperties()
    {
        $customsInfo = new CustomsInfo();

        try {
            $customsInfo->setContentDescription(str_repeat('a', 51));
            $this->fail('BpostInvalidLengthException not launched');
        } catch (BpostInvalidLengthException $e) {
            // Nothing, the exception is good
        } catch (\Exception $e) {
            $this->fail('BpostInvalidLengthException not caught');
        }

        try {
            $customsInfo->setContentDescription(str_repeat('a', 51));
            $this->fail('BpostInvalidLengthException not launched');
        } catch (BpostInvalidLengthException $e) {
            // Nothing, the exception is good
        } catch (\Exception $e) {
            $this->fail('BpostInvalidLengthException not caught');
        }

        try {
            $customsInfo->setShipmentType(str_repeat('a', 10));
            $this->fail('BpostInvalidValueException not launched');
        } catch (BpostInvalidValueException $e) {
            // Nothing, the exception is good
        } catch (\Exception $e) {
            $this->fail('BpostInvalidValueException not caught');
        }

        // Exceptions were caught,
        $this->assertTrue(true);
    }
}
