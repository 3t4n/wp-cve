<?php

namespace Watchful\Helpers;


/**
 * AES implementation in PHP (c) Chris Veness 2005-2011.
 * Right to use and adapt is granted for under a simple creative commons attribution
 * licence. No warranty of any form is offered.
 *
 * Modified for Akeeba Backup by Nicholas K. Dionysopoulos
 * Modified for Watchful by Jeff Channell
 */
class WatchfulEncrypt
{
    /**
     * Sbox is pre-computed multiplicative inverse in GF(2^8) used in SubBytes and KeyExpansion [�5.1.1]
     *
     * @var array
     */
    protected static $s_box = array(
        0x63,
        0x7c,
        0x77,
        0x7b,
        0xf2,
        0x6b,
        0x6f,
        0xc5,
        0x30,
        0x01,
        0x67,
        0x2b,
        0xfe,
        0xd7,
        0xab,
        0x76,
        0xca,
        0x82,
        0xc9,
        0x7d,
        0xfa,
        0x59,
        0x47,
        0xf0,
        0xad,
        0xd4,
        0xa2,
        0xaf,
        0x9c,
        0xa4,
        0x72,
        0xc0,
        0xb7,
        0xfd,
        0x93,
        0x26,
        0x36,
        0x3f,
        0xf7,
        0xcc,
        0x34,
        0xa5,
        0xe5,
        0xf1,
        0x71,
        0xd8,
        0x31,
        0x15,
        0x04,
        0xc7,
        0x23,
        0xc3,
        0x18,
        0x96,
        0x05,
        0x9a,
        0x07,
        0x12,
        0x80,
        0xe2,
        0xeb,
        0x27,
        0xb2,
        0x75,
        0x09,
        0x83,
        0x2c,
        0x1a,
        0x1b,
        0x6e,
        0x5a,
        0xa0,
        0x52,
        0x3b,
        0xd6,
        0xb3,
        0x29,
        0xe3,
        0x2f,
        0x84,
        0x53,
        0xd1,
        0x00,
        0xed,
        0x20,
        0xfc,
        0xb1,
        0x5b,
        0x6a,
        0xcb,
        0xbe,
        0x39,
        0x4a,
        0x4c,
        0x58,
        0xcf,
        0xd0,
        0xef,
        0xaa,
        0xfb,
        0x43,
        0x4d,
        0x33,
        0x85,
        0x45,
        0xf9,
        0x02,
        0x7f,
        0x50,
        0x3c,
        0x9f,
        0xa8,
        0x51,
        0xa3,
        0x40,
        0x8f,
        0x92,
        0x9d,
        0x38,
        0xf5,
        0xbc,
        0xb6,
        0xda,
        0x21,
        0x10,
        0xff,
        0xf3,
        0xd2,
        0xcd,
        0x0c,
        0x13,
        0xec,
        0x5f,
        0x97,
        0x44,
        0x17,
        0xc4,
        0xa7,
        0x7e,
        0x3d,
        0x64,
        0x5d,
        0x19,
        0x73,
        0x60,
        0x81,
        0x4f,
        0xdc,
        0x22,
        0x2a,
        0x90,
        0x88,
        0x46,
        0xee,
        0xb8,
        0x14,
        0xde,
        0x5e,
        0x0b,
        0xdb,
        0xe0,
        0x32,
        0x3a,
        0x0a,
        0x49,
        0x06,
        0x24,
        0x5c,
        0xc2,
        0xd3,
        0xac,
        0x62,
        0x91,
        0x95,
        0xe4,
        0x79,
        0xe7,
        0xc8,
        0x37,
        0x6d,
        0x8d,
        0xd5,
        0x4e,
        0xa9,
        0x6c,
        0x56,
        0xf4,
        0xea,
        0x65,
        0x7a,
        0xae,
        0x08,
        0xba,
        0x78,
        0x25,
        0x2e,
        0x1c,
        0xa6,
        0xb4,
        0xc6,
        0xe8,
        0xdd,
        0x74,
        0x1f,
        0x4b,
        0xbd,
        0x8b,
        0x8a,
        0x70,
        0x3e,
        0xb5,
        0x66,
        0x48,
        0x03,
        0xf6,
        0x0e,
        0x61,
        0x35,
        0x57,
        0xb9,
        0x86,
        0xc1,
        0x1d,
        0x9e,
        0xe1,
        0xf8,
        0x98,
        0x11,
        0x69,
        0xd9,
        0x8e,
        0x94,
        0x9b,
        0x1e,
        0x87,
        0xe9,
        0xce,
        0x55,
        0x28,
        0xdf,
        0x8c,
        0xa1,
        0x89,
        0x0d,
        0xbf,
        0xe6,
        0x42,
        0x68,
        0x41,
        0x99,
        0x2d,
        0x0f,
        0xb0,
        0x54,
        0xbb,
        0x16,
    );
    /**
     * Rcon is Round Constant used for the Key Expansion [1st col is 2^(r-1) in GF(2^8)] [�5.2]
     *
     * @var array
     */
    protected static $r_con = array(
        array(0x00, 0x00, 0x00, 0x00),
        array(0x01, 0x00, 0x00, 0x00),
        array(0x02, 0x00, 0x00, 0x00),
        array(0x04, 0x00, 0x00, 0x00),
        array(0x08, 0x00, 0x00, 0x00),
        array(0x10, 0x00, 0x00, 0x00),
        array(0x20, 0x00, 0x00, 0x00),
        array(0x40, 0x00, 0x00, 0x00),
        array(0x80, 0x00, 0x00, 0x00),
        array(0x1b, 0x00, 0x00, 0x00),
        array(0x36, 0x00, 0x00, 0x00),
    );

    /**
     * @var array
     */
    protected static $passwords = array();

    /**
     * Encrypt a text using AES encryption in Counter mode of operation
     *  - see http://csrc.nist.gov/publications/nistpubs/800-38a/sp800-38a.pdf
     *
     * Unicode multi-byte character safe
     *
     * @param string $plaintext source text to be encrypted
     * @param string $password the password to use to generate a key
     * @param int $n_bits number of bits to be used in the key (128, 192, or 256)
     * @return string encrypted text
     */
    public static function aes_encrypt($plaintext, $password, $n_bits)
    {
        $block_size = 16;  // block size fixed at 16 bytes / 128 bits (Nb=4) for AES
        if (!($n_bits == 128 || $n_bits == 192 || $n_bits == 256)) {
            return '';
        }  // standard allows 128/192/256 bit keys


        // note PHP (5) gives us plaintext and password in UTF8 encoding!
        // use AES itself to encrypt password to get cipher key (using plain password as source for
        // key expansion) - gives us well encrypted key
        $nBytes = $n_bits / 8;  // no bytes in key
        $pwBytes = array();
        for ($i = 0; $i < $nBytes; $i++) {
            $pwBytes[$i] = ord(substr($password, $i, 1)) & 0xff;
        }
        $key = self::cipher($pwBytes, self::key_expansion($pwBytes));
        $key = array_merge($key, array_slice($key, 0, $nBytes - 16));  // expand key to 16/24/32 bytes long
        // initialise counter block (NIST SP800-38A �B.2): millisecond time-stamp for nonce in
        // 1st 8 bytes, block counter in 2nd 8 bytes
        $counter_block = array();
        $nonce = floor(microtime(true) * 1000);   // timestamp: milliseconds since 1-Jan-1970
        $nonce_sec = floor($nonce / 1000);
        $nonce_ms = $nonce % 1000;
        // encode nonce with seconds in 1st 4 bytes, and (repeated) ms part filling 2nd 4 bytes
        for ($i = 0; $i < 4; $i++) {
            $counter_block[$i] = self::urs($nonce_sec, $i * 8) & 0xff;
        }
        for ($i = 0; $i < 4; $i++) {
            $counter_block[$i + 4] = $nonce_ms & 0xff;
        }
        // and convert it to a string to go on the front of the ciphertext
        $ctr_txt = '';
        for ($i = 0; $i < 8; $i++) {
            $ctr_txt .= chr($counter_block[$i]);
        }

        // generate key schedule - an expansion of the key into distinct Key Rounds for each round
        $keySchedule = self::key_expansion($key);

        $block_count = ceil(strlen($plaintext) / $block_size);
        $cipher_txt = array();  // ciphertext as array of strings

        for ($b = 0; $b < $block_count; $b++) {
            // set counter (block #) in last 8 bytes of counter block (leaving nonce in 1st 8 bytes)
            // done in two stages for 32-bit ops: using two words allows us to go past 2^32 blocks (68GB)
            for ($c = 0; $c < 4; $c++) {
                $counter_block[15 - $c] = self::urs($b, $c * 8) & 0xff;
            }
            for ($c = 0; $c < 4; $c++) {
                $counter_block[15 - $c - 4] = self::urs($b / 0x100000000, $c * 8);
            }

            $cipher_cntr = self::cipher($counter_block, $keySchedule);  // -- encrypt counter block --
            // block size is reduced on final block
            $blockLength = $b < $block_count - 1 ? $block_size : (strlen($plaintext) - 1) % $block_size + 1;
            $cipher_byte = array();

            for ($i = 0; $i < $blockLength; $i++) {  // -- xor plaintext with ciphered counter byte-by-byte --
                $cipher_byte[$i] = $cipher_cntr[$i] ^ ord(substr($plaintext, $b * $block_size + $i, 1));
                $cipher_byte[$i] = chr($cipher_byte[$i]);
            }
            $cipher_txt[$b] = implode('', $cipher_byte);  // escape troublesome characters in ciphertext
        }

        // implode is more efficient than repeated string concatenation
        $cipher_text = $ctr_txt.implode('', $cipher_txt);

        return base64_encode($cipher_text);
    }

    /**
     * AES Cipher function: encrypt 'input' with Rijndael algorithm
     *
     * @param string message as byte-array (16 bytes)
     * @param string     key schedule as 2D byte-array (Nr+1 x Nb bytes) -
     *              generated from the cipher key by KeyExpansion()
     * @return      float[]|int[] as byte-array (16 bytes)
     */
    private static function cipher($input, $w)
    {    // main Cipher function [�5.1]
        $Nb = 4;                 // block size (in words): no of columns in state (fixed at 4 for AES)
        $Nr = count($w) / $Nb - 1; // no of rounds: 10/12/14 for 128/192/256-bit keys

        $state = array();  // initialise 4xNb byte-array 'state' with input [�3.4]
        for ($i = 0; $i < 4 * $Nb; $i++) {
            $state[$i % 4][floor($i / 4)] = $input[$i];
        }

        $state = self::add_round_key($state, $w, 0, $Nb);

        for ($round = 1; $round < $Nr; $round++) {  // apply Nr rounds
            $state = self::sub_bytes($state, $Nb);
            $state = self::shift_rows($state, $Nb);
            $state = self::mix_columns($state, $Nb);
            $state = self::add_round_key($state, $w, $round, $Nb);
        }

        $state = self::sub_bytes($state, $Nb);
        $state = self::shift_rows($state, $Nb);
        $state = self::add_round_key($state, $w, $Nr, $Nb);

        $output = array(4 * $Nb);  // convert state to 1-d array before returning [�3.4]
        for ($i = 0; $i < 4 * $Nb; $i++) {
            $output[$i] = $state[$i % 4][floor($i / 4)];
        }

        return $output;
    }

    protected static function add_round_key($state, $w, $rnd, $Nb)
    {  // xor Round Key into state S [�5.1.4]
        for ($r = 0; $r < 4; $r++) {
            for ($c = 0; $c < $Nb; $c++) {
                $state[$r][$c] ^= $w[$rnd * 4 + $c][$r];
            }
        }

        return $state;
    }

    protected static function sub_bytes($s, $Nb)
    {    // apply SBox to state S [�5.1.1]
        for ($r = 0; $r < 4; $r++) {
            for ($c = 0; $c < $Nb; $c++) {
                $s[$r][$c] = self::$s_box[$s[$r][$c]];
            }
        }

        return $s;
    }

    protected static function shift_rows($s, $Nb)
    {    // shift row r of state S left by r bytes [�5.1.2]
        $t = array(4);
        for ($r = 1; $r < 4; $r++) {
            for ($c = 0; $c < 4; $c++) {
                $t[$c] = $s[$r][($c + $r) % $Nb];
            }  // shift into temp copy
            for ($c = 0; $c < 4; $c++) {
                $s[$r][$c] = $t[$c];
            }         // and copy back
        }          // note that this will work for Nb=4,5,6, but not 7,8 (always 4 for AES):

        return $s;  // see fp.gladman.plus.com/cryptography_technology/rijndael/aes.spec.311.pdf
    }

    protected static function mix_columns($s)
    {   // combine bytes of each col of state S [�5.1.3]
        for ($c = 0; $c < 4; $c++) {
            $a = array(4);  // 'a' is a copy of the current column from 's'
            $b = array(4);  // 'b' is a�{02} in GF(2^8)
            for ($i = 0; $i < 4; $i++) {
                $a[$i] = $s[$i][$c];
                $b[$i] = $s[$i][$c] & 0x80 ? $s[$i][$c] << 1 ^ 0x011b : $s[$i][$c] << 1;
            }
            // a[n] ^ b[n] is a�{03} in GF(2^8)
            $s[0][$c] = $b[0] ^ $a[1] ^ $b[1] ^ $a[2] ^ $a[3]; // 2*a0 + 3*a1 + a2 + a3
            $s[1][$c] = $a[0] ^ $b[1] ^ $a[2] ^ $b[2] ^ $a[3]; // a0 * 2*a1 + 3*a2 + a3
            $s[2][$c] = $a[0] ^ $a[1] ^ $b[2] ^ $a[3] ^ $b[3]; // a0 + a1 + 2*a2 + 3*a3
            $s[3][$c] = $a[0] ^ $b[0] ^ $a[1] ^ $a[2] ^ $b[3]; // 3*a0 + a1 + a2 + 2*a3
        }

        return $s;
    }

    /**
     * Key expansion for Rijndael Cipher(): performs key expansion on cipher key
     * to generate a key schedule
     *
     * @param string key cipher key byte-array (16 bytes)
     * @return array    key schedule as 2D byte-array (Nr+1 x Nb bytes)
     */
    public static function key_expansion($key)
    {  // generate Key Schedule from Cipher Key [�5.2]
        $nb = 4;              // block size (in words): no of columns in state (fixed at 4 for AES)
        $nk = count($key) / 4;  // key length (in words): 4/6/8 for 128/192/256-bit keys
        $nr = $nk + 6;        // no of rounds: 10/12/14 for 128/192/256-bit keys

        $w = array();
        $temp = array();

        for ($i = 0; $i < $nk; $i++) {
            $r = array($key[4 * $i], $key[4 * $i + 1], $key[4 * $i + 2], $key[4 * $i + 3]);
            $w[$i] = $r;
        }

        for ($i = $nk; $i < ($nb * ($nr + 1)); $i++) {
            $w[$i] = array();
            for ($t = 0; $t < 4; $t++) {
                $temp[$t] = $w[$i - 1][$t];
            }
            if ($i % $nk == 0) {
                $temp = self::sub_word(self::rot_word($temp));
                for ($t = 0; $t < 4; $t++) {
                    $temp[$t] ^= self::$r_con[$i / $nk][$t];
                }
            } else {
                if ($nk > 6 && $i % $nk == 4) {
                    $temp = self::sub_word($temp);
                }
            }
            for ($t = 0; $t < 4; $t++) {
                $w[$i][$t] = $w[$i - $nk][$t] ^ $temp[$t];
            }
        }

        return $w;
    }

    protected static function sub_word($w)
    {    // apply SBox to 4-byte word w
        for ($i = 0; $i < 4; $i++) {
            $w[$i] = self::$s_box[$w[$i]];
        }

        return $w;
    }

    protected static function rot_word($w)
    {    // rotate 4-byte word w left by one byte
        $tmp = $w[0];
        for ($i = 0; $i < 3; $i++) {
            $w[$i] = $w[$i + 1];
        }
        $w[3] = $tmp;

        return $w;
    }

    /**
     * Unsigned right shift function, since PHP has neither >>> operator nor unsigned ints
     *
     * @param int $a number to be shifted (32-bit integer)
     * @param int $b number of bits to shift a to the right (0..31)
     * @return int right-shifted and zero-filled by b bits
     */
    protected static function urs($a, $b)
    {
        $a &= 0xffffffff;
        $b &= 0x1f;  // (bounds check)
        if ($a & 0x80000000 && $b > 0) {   // if left-most bit set
            $a = ($a >> 1) & 0x7fffffff;   //   right-shift one bit & clear left-most bit
            $a = $a >> ($b - 1);           //   remaining right-shifts
        } else {                       // otherwise
            $a = ($a >> $b);               //   use normal right-shift
        }

        return $a;
    }
}
