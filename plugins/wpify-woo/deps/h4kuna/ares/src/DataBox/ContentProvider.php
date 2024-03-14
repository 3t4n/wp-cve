<?php

declare (strict_types=1);
namespace WpifyWooDeps\h4kuna\Ares\DataBox;

use WpifyWooDeps\Psr\Http\Message\StreamFactoryInterface;
use stdClass;
class ContentProvider
{
    public function __construct(private Client $client, private StreamFactoryInterface $streamFactory)
    {
    }
    /**
     * @return array<stdClass>
     */
    public function load(string $in) : array
    {
        $content = $this->xml('Ico', $in)->Osoba;
        return \is_array($content) ? $content : [$content];
    }
    protected function xml(string $parameter, string $value) : stdClass
    {
        $xml = <<<XML
<GetInfoRequest xmlns="http://seznam.gov.cz/ovm/ws/v1">
\t<{$parameter}>{$value}</{$parameter}>
</GetInfoRequest>
XML;
        return $this->client->request($this->streamFactory->createStream($xml));
    }
}
