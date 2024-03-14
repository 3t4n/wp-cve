<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\DataCollector;

use WPPayVendor\Symfony\Component\HttpFoundation\Request;
use WPPayVendor\Symfony\Component\HttpFoundation\Response;
use WPPayVendor\Symfony\Component\HttpKernel\DataCollector\DataCollector;
use WPPayVendor\Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;
use WPPayVendor\Symfony\Component\Translation\DataCollectorTranslator;
use WPPayVendor\Symfony\Component\VarDumper\Cloner\Data;
/**
 * @author Abdellatif Ait boudad <a.aitboudad@gmail.com>
 *
 * @final
 */
class TranslationDataCollector extends \WPPayVendor\Symfony\Component\HttpKernel\DataCollector\DataCollector implements \WPPayVendor\Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface
{
    private $translator;
    public function __construct(\WPPayVendor\Symfony\Component\Translation\DataCollectorTranslator $translator)
    {
        $this->translator = $translator;
    }
    /**
     * {@inheritdoc}
     */
    public function lateCollect()
    {
        $messages = $this->sanitizeCollectedMessages($this->translator->getCollectedMessages());
        $this->data += $this->computeCount($messages);
        $this->data['messages'] = $messages;
        $this->data = $this->cloneVar($this->data);
    }
    /**
     * {@inheritdoc}
     */
    public function collect(\WPPayVendor\Symfony\Component\HttpFoundation\Request $request, \WPPayVendor\Symfony\Component\HttpFoundation\Response $response, ?\Throwable $exception = null)
    {
        $this->data['locale'] = $this->translator->getLocale();
        $this->data['fallback_locales'] = $this->translator->getFallbackLocales();
    }
    /**
     * {@inheritdoc}
     */
    public function reset()
    {
        $this->data = [];
    }
    /**
     * @return array|Data
     */
    public function getMessages()
    {
        return $this->data['messages'] ?? [];
    }
    public function getCountMissings() : int
    {
        return $this->data[\WPPayVendor\Symfony\Component\Translation\DataCollectorTranslator::MESSAGE_MISSING] ?? 0;
    }
    public function getCountFallbacks() : int
    {
        return $this->data[\WPPayVendor\Symfony\Component\Translation\DataCollectorTranslator::MESSAGE_EQUALS_FALLBACK] ?? 0;
    }
    public function getCountDefines() : int
    {
        return $this->data[\WPPayVendor\Symfony\Component\Translation\DataCollectorTranslator::MESSAGE_DEFINED] ?? 0;
    }
    public function getLocale()
    {
        return !empty($this->data['locale']) ? $this->data['locale'] : null;
    }
    /**
     * @internal
     */
    public function getFallbackLocales()
    {
        return isset($this->data['fallback_locales']) && \count($this->data['fallback_locales']) > 0 ? $this->data['fallback_locales'] : [];
    }
    /**
     * {@inheritdoc}
     */
    public function getName() : string
    {
        return 'translation';
    }
    private function sanitizeCollectedMessages(array $messages)
    {
        $result = [];
        foreach ($messages as $key => $message) {
            $messageId = $message['locale'] . $message['domain'] . $message['id'];
            if (!isset($result[$messageId])) {
                $message['count'] = 1;
                $message['parameters'] = !empty($message['parameters']) ? [$message['parameters']] : [];
                $messages[$key]['translation'] = $this->sanitizeString($message['translation']);
                $result[$messageId] = $message;
            } else {
                if (!empty($message['parameters'])) {
                    $result[$messageId]['parameters'][] = $message['parameters'];
                }
                ++$result[$messageId]['count'];
            }
            unset($messages[$key]);
        }
        return $result;
    }
    private function computeCount(array $messages)
    {
        $count = [\WPPayVendor\Symfony\Component\Translation\DataCollectorTranslator::MESSAGE_DEFINED => 0, \WPPayVendor\Symfony\Component\Translation\DataCollectorTranslator::MESSAGE_MISSING => 0, \WPPayVendor\Symfony\Component\Translation\DataCollectorTranslator::MESSAGE_EQUALS_FALLBACK => 0];
        foreach ($messages as $message) {
            ++$count[$message['state']];
        }
        return $count;
    }
    private function sanitizeString(string $string, int $length = 80)
    {
        $string = \trim(\preg_replace('/\\s+/', ' ', $string));
        if (\false !== ($encoding = \mb_detect_encoding($string, null, \true))) {
            if (\mb_strlen($string, $encoding) > $length) {
                return \mb_substr($string, 0, $length - 3, $encoding) . '...';
            }
        } elseif (\strlen($string) > $length) {
            return \substr($string, 0, $length - 3) . '...';
        }
        return $string;
    }
}
