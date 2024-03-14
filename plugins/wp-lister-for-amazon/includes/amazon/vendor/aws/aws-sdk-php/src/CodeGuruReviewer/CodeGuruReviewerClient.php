<?php
namespace Aws\CodeGuruReviewer;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon CodeGuru Reviewer** service.
 * @method \Aws\Result associateRepository(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise associateRepositoryAsync(array $args = [])
 * @method \Aws\Result createCodeReview(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createCodeReviewAsync(array $args = [])
 * @method \Aws\Result describeCodeReview(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeCodeReviewAsync(array $args = [])
 * @method \Aws\Result describeRecommendationFeedback(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeRecommendationFeedbackAsync(array $args = [])
 * @method \Aws\Result describeRepositoryAssociation(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeRepositoryAssociationAsync(array $args = [])
 * @method \Aws\Result disassociateRepository(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disassociateRepositoryAsync(array $args = [])
 * @method \Aws\Result listCodeReviews(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listCodeReviewsAsync(array $args = [])
 * @method \Aws\Result listRecommendationFeedback(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRecommendationFeedbackAsync(array $args = [])
 * @method \Aws\Result listRecommendations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRecommendationsAsync(array $args = [])
 * @method \Aws\Result listRepositoryAssociations(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRepositoryAssociationsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result putRecommendationFeedback(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putRecommendationFeedbackAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 */
class CodeGuruReviewerClient extends AwsClient {}
