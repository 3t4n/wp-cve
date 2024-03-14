<?php

namespace Memsource\Parser;

use Memsource\Service\BlockService;
use Memsource\Utils\ArrayUtils;
use Memsource\Utils\AuthUtils;
use Memsource\Utils\LogUtils;
use Memsource\Utils\StringUtils;

class BlockParser
{
    private const MEMSOURCE_BLOCK_COMMENT = 'MEMSOURCE_BLOCK_COMMENT';

    /** @var ArrayUtils */
    private $arrayUtils;

    /** @var AuthUtils */
    private $authUtils;

    /** @var BlockService */
    private $blockService;

    /** @var string */
    private $content;

    public function __construct(ArrayUtils $arrayUtils, AuthUtils $authUtils, BlockService $blockService)
    {
        $this->arrayUtils = $arrayUtils;
        $this->authUtils = $authUtils;
        $this->blockService = $blockService;
    }

    public function encode(string $content): string
    {
        $this->content = $content;
        $parsed = parse_blocks($content);

        foreach ($parsed as $block) {
            $this->processBlock($block);
        }

        return $this->content;
    }

    private function processBlock(array $block)
    {
        if (!is_array($block['attrs'])) {
            return;
        }

        array_walk($block['attrs'], [$this, 'processBlockAttributes'], $block['blockName']);

        if (!empty($block['innerBlocks'])) {
            foreach ($block['innerBlocks'] as $innerBlock) {
                $this->processBlock($innerBlock);
            }
        }
    }

    private function processBlockAttributes($value, string $key, string $blockName)
    {
        if (is_array($value)) {
            if ($this->arrayUtils->isScalarArrayOfStrings($value)) {
                $this->processBlockAttribute($blockName, $key, $value);
            } else {
                array_walk($value, [$this, 'processBlockAttributes'], $blockName);
            }
        } else {
            $this->processBlockAttribute($blockName, $key, [$value]);
        }
    }

    private function processBlockAttribute(string $blockName, string $attribute, array $values)
    {
        foreach ($values as $value) {
            $this->extractBlockComments($value);

            if ($this->blockService->isAttributeTranslatable($blockName, $attribute)) {
                $this->extractTextFromBlock($blockName, $attribute, $value, count($values) === 1);
            }
        }
    }

    private function extractBlockComments($value)
    {
        preg_match_all('/<!--(.*)-->/Uis', $value, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $pattern = '/<!--(' . $this->encodeValueForRegex($match[1]) . ')-->/smi';
            $replacement = '<!' . self::MEMSOURCE_BLOCK_COMMENT . '--${1}--' . self::MEMSOURCE_BLOCK_COMMENT . '>';
            $this->content = preg_replace($pattern, $replacement, $this->content, 1);
        }
    }

    private function extractTextFromBlock(string $block, string $attribute, string $value, bool $isValueScalar)
    {
        // strip 'wp:' from the beginning of block
        $block = strip_core_block_namespace($block);

        $value = $this->encodeValueForRegex($value);

        $pattern = '/(<!-- wp:' .
                   preg_quote($block, '/') .
                   '.*?"' .
                   preg_quote($attribute, '/') .
                   '":.*?")(' .
                   $value .
                   ')(".*?-->)/smi';

        $token = $this->authUtils->generateRandomToken();
        $replaced = false;

        try {
            preg_match($pattern, $this->content, $matches);
        } catch (\Throwable $exception) {
            LogUtils::error('Failed processing regex', $exception);
        }

        if (isset($matches[2])) {
            $lastKey = key(array_slice($matches, -1, 1, true));
            $replacement = '${1}' . $this->quoteReplacement(
                '--><div class="memsource-block-start memsource-id-' . $token . '"></div>' .
                json_decode('"' . $matches[2] . '"') .
                '<div class="memsource-block-end memsource-id-' . $token . '"></div><!--' . $matches[$lastKey]
            );

            $this->content = preg_replace($pattern, $replacement, $this->content, 1, $replaced);
        }

        if ($isValueScalar && !$replaced) {
            $token = $this->authUtils->generateRandomToken();
            $pattern = '/(<!-- wp:' . preg_quote($block, '/') . '.*?"' . preg_quote($attribute, '/') . '"\s*:\s*")([^"\\\\]*(?:\\\\.[^"\\\\]*)*)(".*?-->)/s';
            preg_match_all($pattern, $this->content, $matches, PREG_SET_ORDER);

            foreach ($matches ?? [] as $match) {
                if (isset($match[3]) && $match[2] !== '--><div class=' && !StringUtils::startsWith($match[3], '"memsource-block-start')) {
                    $replacement =
                        $match[1] .
                        '--><div class="memsource-block-start memsource-id-' . $token . '"></div>' .
                        json_decode('"' . $match[2] . '"') .
                        '<div class="memsource-block-end memsource-id-' . $token . '"></div><!--' .
                        $match[3];
                    $this->content = str_replace($match[0], $replacement, $this->content);
                    $replaced = true;
                    break;
                }
            }
        }

        if (!$replaced) {
            LogUtils::error("Gutenberg block '${block}' with attribute '${attribute}' was not exported for translation.");
        }
    }

    private function encodeValueForRegex(string $value): string
    {
        // convert value to JSON
        $value = $this->jsonEncodeBlock($value, true);

        // value will be used as a part of regex - needs to be escaped
        $value = preg_quote($value, '/');

        // slash can be (optionally) escaped
        $value = str_replace('\\/', '(\\\\)?\\/', $value);

        // match both escaped and unescaped UTF8 characters
        $value = preg_replace(
            '/\\\\\\\u[0-9a-zA-Z]{4}/sm',
            '(\\\\\\u[0-9a-zA-Z]{4}|[^\x00-\x7F]+|.{1,2})',
            $value
        );

        return $value;
    }

    public function decode(string $content, string $storedContent): string
    {
        preg_match_all(
            '|--><div class="memsource-block-start memsource-id-([^\s]+)?"></div>(.*?)<div class="memsource-block-end|sm',
            $content,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $replacement = str_replace('\\', '\\\\', $match[2]);
            $replacement = $this->jsonEncodeBlock($replacement);
            $replacement = $this->quoteReplacement($replacement);

            $storedContent = preg_replace(
                '|(--><div class="memsource-block-start memsource-id-' . $match[1] . '"></div>)(.*?)(<div class="memsource-block-end memsource-id-' . $match[1] . '"></div><!--)|sm',
                $replacement,
                $storedContent
            );
        }

        return $storedContent;
    }

    private function jsonEncodeBlock($content, bool $convertHex = false): string
    {
        if ($convertHex) {
            $content = wp_json_encode($content, JSON_HEX_TAG | JSON_UNESCAPED_SLASHES | JSON_HEX_AMP | JSON_HEX_QUOT);
        } else {
            $content = wp_json_encode($content, JSON_UNESCAPED_UNICODE);
        }

        $content = ltrim($content, '"');
        $content = rtrim($content, '"');

        return $content;
    }

    private function quoteReplacement($text)
    {
        return preg_replace('/\${?\d+}?/s', '\\\$0', $text);
    }

    public function stripBlockComments(string $transformedContent)
    {
        return str_replace(self::MEMSOURCE_BLOCK_COMMENT, '', $transformedContent);
    }
}
