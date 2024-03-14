<?php

namespace WpLHLAdminUi\LicenseKeys;

class LicenseKeyLocalValidator {
    private $privateKeyPath;
    private $publicKeyPath;

    public function __construct($publicKeyPath = '', $privateKeyPath = '') {
        $this->privateKeyPath = $privateKeyPath;
        $this->publicKeyPath = $publicKeyPath;
    }

    public function encryptFromFile($inputFilePath, $outputFilePath) {
        $plaintext = file_get_contents($inputFilePath);
        $encryptedText = $this->_encryptRSA($plaintext, $this->privateKeyPath);
        file_put_contents($outputFilePath, $encryptedText);
    }

    public function decryptFromFile($inputFilePath, $outputFilePath = '') {
        $encryptedText = file_get_contents($inputFilePath);
        $decryptedText = $this->_decryptRSA($encryptedText, $this->publicKeyPath);
        // file_put_contents($outputFilePath, $decryptedText);
        return $decryptedText;
    }

    private function _encryptRSA($plaintext, $privateKeyPath) {
        $privateKey = openssl_pkey_get_private(file_get_contents($privateKeyPath));
        openssl_private_encrypt($plaintext, $encrypted, $privateKey);
        $base64Encrypted = base64_encode($encrypted);
        error_log($base64Encrypted);
        return $base64Encrypted;
    }

    private function _decryptRSA($base64Encrypted, $publicKeyPath) {
        $publicKey = openssl_pkey_get_public(file_get_contents($publicKeyPath));
        $encrypted = base64_decode($base64Encrypted);
        openssl_public_decrypt($encrypted, $decrypted, $publicKey);
        return $decrypted;
    }

    public function generateKeyPair() {
        $config = array(
            "private_key_bits" => 2048,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        );
        $privateKey = openssl_pkey_new($config);

        openssl_pkey_export($privateKey, $privateKeyString);
        file_put_contents($this->privateKeyPath, $privateKeyString);

        $publicKey = openssl_pkey_get_details($privateKey);
        $publicKeyString = $publicKey['key'];
        file_put_contents($this->publicKeyPath, $publicKeyString);

        echo "Private and public key files have been generated.";
    }
}

// Example usage
// $encryptionManager = new LicenseKeyLocalValidator('private_key.pem', 'public_key.pem');
// $encryptionManager->encryptFromFile('input.txt', 'encrypted.txt');
// $encryptionManager->decryptFromFile('encrypted.txt', 'decrypted.txt');

// // Example usage
// $encryptionManager = new LicenseKeyLocalValidator();
// $encryptionManager->generateKeyPair();
