<?php


namespace rnpdfimporter\PDFLib\core\document;


use rnpdfimporter\PDFLib\core\integration\ReferenceArray;
use rnpdfimporter\PDFLib\core\integration\StringIntegration;
use rnpdfimporter\PDFLib\core\objects\PDFRef;
use rnpdfimporter\PDFLib\core\syntax\CharCodes;
use rnpdfimporter\PDFLib\utils\strings;

class PDFCrossRefSection
{
    public $createEmpty;
    private $subsections;
    private $chunkIdx;
    private $chunkLength;

    public static function create()
    {
        return new PDFCrossRefSection(array(
            "ref" => PDFRef::of(0, 65535),
            "offset" => 0,
            "deleted" => true
        ));
    }

    public static function createEmpty(){
        return new PDFCrossRefSection();
    }
    public function __construct($firstEntry=null)
    {
        $this->subsections = $firstEntry ? ReferenceArray::createFromArray([$firstEntry]) : new ReferenceArray();
        $this->chunkIdx = 0;
        $this->chunkLength = $firstEntry ? 1 : 0;
    }

    public function addEntry($ref, $offset)
    {
        $this->append(array("ref" => $ref, "offset" => $offset, "deleted" => false));
    }

    public function addDeletedEntry($ref, $nextFreeObjectNumber)
    {
        $this->append(array('ref' => $ref, 'offset' => $nextFreeObjectNumber, 'deleted' => true));
    }

    public function toString()
    {
        $section = `xref\n`;

        for (
            $rangeIdx = 0, $rangeLen = $this->subsections->length();
            $rangeIdx < $rangeLen;
            $rangeIdx++
        )
        {
            $range = $this->subsections[$rangeIdx];
            $section .= $range[0]->ref->objectNumber . ' ' . $range->length . '\n';
            for (
                $entryIdx = 0, $entryLen = $range['length'];
                $entryIdx < $entryLen;
                $entryIdx++
            )
            {
                $entry = $range[$entryIdx];
                $section .= StringIntegration::padStart(\strval($entry['offset']), 10, '0');
                $section .= ' ';
                $section .= StringIntegration::padStart(\strval($entry['ref']['generationNumber']), 5, '0');
                $section .= ' ';
                $section .= $entry['deleted'] ? 'f' : 'n';
                $section .= ' \n';
            }
        }

        return $section;
    }

    public function sizeInBytes()
    {
        $size = 5;
        for ($idx = 0, $len = $this->subsections->length(); $idx < $len; $idx++)
        {
            $subsection = $this->subsections[$idx];
            $subsectionLength = $subsection->length();
            $firstEntry = $subsection['$firstEntry'];
            $size += 2;
            $size += \strlen($firstEntry['ref']->objectNumber);
            $size += \strlen(\strval($subsectionLength));
            $size += 20 * $subsectionLength;
        }
        return $size;
    }

    public function copyBytesInto($buffer, $offset)
    {
        $initialOffset = $offset;

        $buffer[$offset++] = CharCodes::x;
        $buffer[$offset++] = CharCodes::r;
        $buffer[$offset++] = CharCodes::e;
        $buffer[$offset++] = CharCodes::f;
        $buffer[$offset++] = CharCodes::Newline;

        $offset += $this->copySubsectionsIntoBuffer($this->subsections, $buffer, $offset);

        return $offset - $initialOffset;
    }

    private function copySubsectionsIntoBuffer($subsections, $buffer, $offset)
    {
        $initialOffset = $offset;
        $length = count($subsections);

        for ($idx = 0; $idx < $length; $idx++)
        {
            $subsection = $this->subsections[$idx];

            $firstObjectNumber = \strval($subsection[0]->ref->objectNumber);
            $offset += strings::copyStringIntoBuffer($firstObjectNumber, $buffer, $offset);
            $buffer[$offset++] = CharCodes::Space;

            $rangeLength = \strval(count($subsection));
            $offset += strings::copyStringIntoBuffer($rangeLength, $buffer, $offset);
            $buffer[$offset++] = CharCodes::Newline;

            $offset += $this->copyEntriesIntoBuffer($subsection, $buffer, $offset);
        }

        return $offset - $initialOffset;
    }


    private function copyEntriesIntoBuffer($entries, $buffer, $offset)
    {
        $length = count($entries);

        for ($idx = 0; $idx < $length; $idx++)
        {
            $entry = $entries[$idx];

            $entryOffset = strings::padStart(\strval(count($entry)), 10, '0');
            $offset += strings::copyStringIntoBuffer($entryOffset, $buffer, $offset);
            $buffer[$offset++] = CharCodes::Space;

            $entryGen = strings::padStart(\strval($entry->ref->generationNumber), 5, '0');
            $offset += strings::copyStringIntoBuffer($entryGen, $buffer, $offset);
            $buffer[$offset++] = CharCodes::Space;

            $buffer[$offset++] = $entry['deleted'] ? CharCodes::f : CharCodes::n;

            $buffer[$offset++] = CharCodes::Space;
            $buffer[$offset++] = CharCodes::Newline;
        }

        return 20 * $length;
    }

    private function append($currEntry)
    {
        if ($this->chunkLength === 0)
        {
            $this->subsections->push( ReferenceArray::createFromArray([$currEntry]));
            $this->chunkIdx = 0;
            $this->chunkLength = 1;
            return;
        }

        $chunk = $this->subsections[$this->chunkIdx];
        $prevEntry = $chunk[$this->chunkLength - 1];

        if ($currEntry['ref']->objectNumber - $prevEntry['ref']->objectNumber > 1)
        {
            $this->subsections->push( ReferenceArray::createFromArray([ $currEntry]));
            $this->chunkIdx += 1;
            $this->chunkLength = 1;
        } else
        {
            $chunk->push($currEntry);
            $this->chunkLength += 1;
        }
    }

}