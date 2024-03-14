<?php


namespace rnpdfimporter\PDFLib\core\streams;


interface StreamType
{
    public function getByte();

    public function getUint16();

    public function getInt32();

    public function getBytes($length, $forceClamped);

    public function peekByte();

    public function peekBytes($length, $forceClamped);

    public function skip($n);

    public function reset();

    public function makeSubStream($start, $length);

    public function decode();
}