<?php
namespace Bpost;

use Bpost\BpostApiClient\Bpost\Order\Box\AtBpost;
use Bpost\BpostApiClient\Bpost\Order\Box\National\ShopHandlingInstruction;
use Bpost\BpostApiClient\Bpost\Order\PugoAddress;
use Bpost\BpostApiClient\Exception\BpostLogicException\BpostInvalidValueException;

class AtBpostTest extends \PHPUnit_Framework_TestCase
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
     * Tests Address->toXML
     */
    public function testToXML()
    {
        $data = array(
            'atBpost' => array(
                'product' => 'bpack@bpost',
                'weight' => 2000,
                'pugoId' => '207500',
                'pugoName' => 'WIJNEGEM',
                'pugoAddress' => array(
                    'streetName' => 'Turnhoutsebaan',
                    'number' => '468',
                    'postalCode' => '2110',
                    'locality' => 'WIJNEGEM',
                    'countryCode' => 'BE',
                ),
                'receiverName' => 'Tijs Verkoyen',
                'receiverCompany' => 'Sumo Coders',
                'requestedDeliveryDate' => '2016-03-16',
                'shopHandlingInstruction' => 'Do not break it please',
            ),
        );

        $expectedDocument = self::createDomDocument();
        $nationalBox = $expectedDocument->createElement('nationalBox');
        $atBpost = $expectedDocument->createElement('atBpost');
        $nationalBox->appendChild($atBpost);
        $expectedDocument->appendChild($nationalBox);
        foreach ($data['atBpost'] as $key => $value) {
            if ($key == 'pugoAddress') {
                $address = $expectedDocument->createElement($key);
                foreach ($value as $key2 => $value2) {
                    $key2 = 'common:' . $key2;
                    $address->appendChild(
                        $expectedDocument->createElement($key2, $value2)
                    );
                }
                $atBpost->appendChild($address);
            } else {
                $atBpost->appendChild(
                    $expectedDocument->createElement($key, $value)
                );
            }
        }

        $actualDocument = self::createDomDocument();
        $pugoAddress = new PugoAddress(
            $data['atBpost']['pugoAddress']['streetName'],
            $data['atBpost']['pugoAddress']['number'],
            null,
            $data['atBpost']['pugoAddress']['postalCode'],
            $data['atBpost']['pugoAddress']['locality'],
            $data['atBpost']['pugoAddress']['countryCode']
        );

        $atBpost = new AtBpost();
        $atBpost->setProduct($data['atBpost']['product']);
        $atBpost->setWeight($data['atBpost']['weight']);

        $atBpost->setPugoId($data['atBpost']['pugoId']);
        $atBpost->setPugoName($data['atBpost']['pugoName']);
        $atBpost->setPugoAddress($pugoAddress);
        $atBpost->setReceiverName($data['atBpost']['receiverName']);
        $atBpost->setReceiverCompany($data['atBpost']['receiverCompany']);
        $atBpost->setRequestedDeliveryDate($data['atBpost']['requestedDeliveryDate']);
        $atBpost->setShopHandlingInstruction($data['atBpost']['shopHandlingInstruction']);

        $actualDocument->appendChild($atBpost->toXML($actualDocument));

        $this->assertSame($expectedDocument->saveXML(), $actualDocument->saveXML());
    }

    /**
     * Test validation in the setters
     */
    public function testFaultyProperties()
    {
        $atBpost = new AtBpost();

        try {
            $atBpost->setProduct(str_repeat('a', 10));
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
