<?php
namespace Aws\Translate;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Translate** service.
 * @method \Aws\Result createParallelData(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createParallelDataAsync(array $args = [])
 * @method \Aws\Result deleteParallelData(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteParallelDataAsync(array $args = [])
 * @method \Aws\Result deleteTerminology(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteTerminologyAsync(array $args = [])
 * @method \Aws\Result describeTextTranslationJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeTextTranslationJobAsync(array $args = [])
 * @method \Aws\Result getParallelData(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getParallelDataAsync(array $args = [])
 * @method \Aws\Result getTerminology(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getTerminologyAsync(array $args = [])
 * @method \Aws\Result importTerminology(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise importTerminologyAsync(array $args = [])
 * @method \Aws\Result listLanguages(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listLanguagesAsync(array $args = [])
 * @method \Aws\Result listParallelData(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listParallelDataAsync(array $args = [])
 * @method \Aws\Result listTerminologies(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTerminologiesAsync(array $args = [])
 * @method \Aws\Result listTextTranslationJobs(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTextTranslationJobsAsync(array $args = [])
 * @method \Aws\Result startTextTranslationJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startTextTranslationJobAsync(array $args = [])
 * @method \Aws\Result stopTextTranslationJob(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise stopTextTranslationJobAsync(array $args = [])
 * @method \Aws\Result translateText(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise translateTextAsync(array $args = [])
 * @method \Aws\Result updateParallelData(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateParallelDataAsync(array $args = [])
 */
class TranslateClient extends AwsClient {}
