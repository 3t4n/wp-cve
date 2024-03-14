<?php

function hmacsha1($data, $key) {
    $blocksize=64;
    $hashfunc='sha1';
    if (strlen($key)>$blocksize)
        $key=pack('H*', $hashfunc($key));
    $key=str_pad($key,$blocksize,chr(0x00));
    $ipad=str_repeat(chr(0x36),$blocksize);
    $opad=str_repeat(chr(0x5c),$blocksize);
    $hmac = pack(
        'H*',$hashfunc(
            ($key^$opad).pack(
                'H*',$hashfunc(
                    ($key^$ipad).$data
                )
            )
        )
    );
    return bin2hex($hmac);
}

function generateSsoData($data, $secret) {
  if (!$secret) throw new Exception('[gcSso] empty secret');

  $message = base64_encode(json_encode($data));
  $timestamp = time();

  $hexsig = hmacsha1($message . ' ' . $timestamp, $secret);

  return $message . ' ' . $hexsig . ' ' . $timestamp;
}

