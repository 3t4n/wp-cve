<?php
namespace Aws\Textract;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Textract** service.
 * @method \Aws\Result analyzeDocument(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise analyzeDocumentAsync(array $args = [])
 * @method \Aws\Result analyzeExpense(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise analyzeExpenseAsync(array $args = [])
 * @method \Aws\Result analyzeID(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise analyzeIDAsync(array $args = [])
 * @method \Aws\Result detectDocumentText(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise detectDocumentTextAsync(array $args = [])
 * @method \Aws\Result getDocumentAnalysis(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getDocumentAnalysisAsync(array $args = [])
 * @method \Aws\Result getDocumentTextDetection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getDocumentTextDetectionAsync(array $args = [])
 * @method \Aws\Result getExpenseAnalysis(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getExpenseAnalysisAsync(array $args = [])
 * @method \Aws\Result startDocumentAnalysis(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startDocumentAnalysisAsync(array $args = [])
 * @method \Aws\Result startDocumentTextDetection(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startDocumentTextDetectionAsync(array $args = [])
 * @method \Aws\Result startExpenseAnalysis(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startExpenseAnalysisAsync(array $args = [])
 */
class TextractClient extends AwsClient {}
