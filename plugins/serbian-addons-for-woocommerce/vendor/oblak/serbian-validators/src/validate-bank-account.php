<?php

namespace Oblak;

use function Oblak\getSerbianBanks;

/**
 * Validates bank account number
 *
 * @param  string $account Bank account number
 * @return bool            True if valid, false otherwise
 */
function validateBankAccount($account) {
    if (strpos($account, '-') === false) {
        $acct = [];
        $acct[] = substr($account, 0, 3);
        $acct[] = substr($account, 3, -2);
        $acct[] = substr($account, -2);
    } else {
        $acct = explode('-', $account);
    }

    if (!in_array($acct[0], getSerbianBanks())) {
        return false;
    }

    $acct[1] = str_pad($acct[1], 13, '0', STR_PAD_LEFT);

    return mod97((int)($acct[0] . $acct[1]), (int)ltrim($acct[2], '0'));
}
