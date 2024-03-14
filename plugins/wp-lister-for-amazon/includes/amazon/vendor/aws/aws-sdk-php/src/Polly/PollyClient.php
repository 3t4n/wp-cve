<?php
namespace Aws\Polly;

use Aws\Api\Serializer\JsonBody;
use Aws\AwsClient;
use Aws\Signature\SignatureV4;
use WPLab\GuzzeHttp\Psr7\Request;
use WPLab\GuzzeHttp\Psr7\Uri;
use WPLab\GuzzeHttp\Psr7;

/**
 * This client is used to interact with the **Amazon Polly** service.
 * @method \Aws\Result deleteLexicon(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteLexiconAsync(array $args = [])
 * @method \Aws\Result describeVoices(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise describeVoicesAsync(array $args = [])
 * @method \Aws\Result getLexicon(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getLexiconAsync(array $args = [])
 * @method \Aws\Result getSpeechSynthesisTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getSpeechSynthesisTaskAsync(array $args = [])
 * @method \Aws\Result listLexicons(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listLexiconsAsync(array $args = [])
 * @method \Aws\Result listSpeechSynthesisTasks(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listSpeechSynthesisTasksAsync(array $args = [])
 * @method \Aws\Result putLexicon(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putLexiconAsync(array $args = [])
 * @method \Aws\Result startSpeechSynthesisTask(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startSpeechSynthesisTaskAsync(array $args = [])
 * @method \Aws\Result synthesizeSpeech(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise synthesizeSpeechAsync(array $args = [])
 */
class PollyClient extends AwsClient
{
    /** @var JsonBody */
    private $formatter;

    /**
     * Create a pre-signed URL for Polly operation `SynthesizeSpeech`
     *
     * @param array $args parameters array for `SynthesizeSpeech`
     *                    More information @see Aws\Polly\PollyClient::SynthesizeSpeech
     *
     * @return string
     */
    public function createSynthesizeSpeechPreSignedUrl(array $args)
    {
        $uri = new Uri($this->getEndpoint());
        $uri = $uri->withPath('/v1/speech');

        // Formatting parameters follows rest-json protocol
        $this->formatter = $this->formatter ?: new JsonBody($this->getApi());
        $queryArray = json_decode(
            $this->formatter->build(
                $this->getApi()->getOperation('SynthesizeSpeech')->getInput(),
                $args
            ),
            true
        );

        // Mocking a 'GET' request in pre-signing the Url
        $query = Psr7\Query::build($queryArray);
        $uri = $uri->withQuery($query);

        $request = new Request('GET', $uri);
        $request = $request->withBody(Psr7\Utils::streamFor(''));
        $signer = new SignatureV4('polly', $this->getRegion());
        return (string) $signer->presign(
            $request,
            $this->getCredentials()->wait(),
            '+15 minutes'
        )->getUri();
    }
}
