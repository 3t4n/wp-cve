<?php
/**
 *
 * CashBill Payment PHP SDK
 *
 * @author Lukasz Firek <lukasz.firek@cashbill.pl>
 * @version 1.0.0
 * @license MIT
 * @copyright CashBill S.A. 2015
 *
 * http://cashbill.pl
 *
 */
namespace CashBill\Payments\Services;

use Exception;

class CashBillException extends Exception {
}
class CashBillCurlException extends CashBillException {
}
class CashBillConfigurationException extends CashBillException {
}
class CashBillTransactionException extends CashBillException {
}
class CashBillPersonalDataException extends CashBillException {
}