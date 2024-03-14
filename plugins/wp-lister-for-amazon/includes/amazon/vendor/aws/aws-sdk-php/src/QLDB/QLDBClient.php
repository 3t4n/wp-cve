<?php
namespace Aws\QLDB;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon QLDB** service.
 * @method \Aws\Result cancelJournalKinesisStream(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise cancelJournalKinesisStreamAsync(array $args = [])
 * @method \Aws\Result createLedger(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createLedgerAsync(array $args = [])
 * @method \Aws\Result deleteLedger(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteLedgerAsync(array $args = [])
 * @method \Aws\Result describeJournalKinesisStream(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeJournalKinesisStreamAsync(array $args = [])
 * @method \Aws\Result describeJournalS3Export(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeJournalS3ExportAsync(array $args = [])
 * @method \Aws\Result describeLedger(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeLedgerAsync(array $args = [])
 * @method \Aws\Result exportJournalToS3(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise exportJournalToS3Async(array $args = [])
 * @method \Aws\Result getBlock(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getBlockAsync(array $args = [])
 * @method \Aws\Result getDigest(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getDigestAsync(array $args = [])
 * @method \Aws\Result getRevision(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getRevisionAsync(array $args = [])
 * @method \Aws\Result listJournalKinesisStreamsForLedger(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listJournalKinesisStreamsForLedgerAsync(array $args = [])
 * @method \Aws\Result listJournalS3Exports(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listJournalS3ExportsAsync(array $args = [])
 * @method \Aws\Result listJournalS3ExportsForLedger(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listJournalS3ExportsForLedgerAsync(array $args = [])
 * @method \Aws\Result listLedgers(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listLedgersAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result streamJournalToKinesis(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise streamJournalToKinesisAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateLedger(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateLedgerAsync(array $args = [])
 * @method \Aws\Result updateLedgerPermissionsMode(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateLedgerPermissionsModeAsync(array $args = [])
 */
class QLDBClient extends AwsClient {}
