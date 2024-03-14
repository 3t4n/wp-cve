<?php

namespace Memsource\Service\TranslationPlugin;

class NonExistingPlugin implements ITranslationPlugin
{
    public function getName(): string
    {
        return 'NonExistingPlugin';
    }

    public function isPluginActive(): bool
    {
        return true;
    }

    public function supportsAdminMenu(): bool
    {
        return false;
    }

    public function supportsNetworkAdminMenu(): bool
    {
        return false;
    }

    public function getSourceLanguage(): string
    {
        return '';
    }

    public function getTargetLanguages(): array
    {
        return [];
    }

    public function getActiveLanguages(): array
    {
        return [];
    }

    public function getActiveLanguagesDetails(): array
    {
        return [];
    }

    public function storePostTranslation(string $wpContentType, int $sourceContentId, string $targetLanguage, array $content): int
    {
        return 0;
    }

    public function storeTermTranslation(string $wpContentType, int $sourceContentId, string $targetLanguage, string $title, string $description, int $parentTranslationId): int
    {
        return 0;
    }

    public function getTermTranslationId(string $wpContentType, int $sourceContentId, string $targetLanguage)
    {
        return null;
    }

    public function switchToSiteByTargetLang(string $language): int
    {
        return get_current_blog_id();
    }

    public function switchToSite(int $siteId): int
    {
        return get_current_blog_id();
    }

    public function transferPostTerms(string $taxonomy, array $sourceTermsIds, int $targetPostId, string $targetLanguage)
    {
    }

    public function copyPostThumbnail(int $sourcePostId, int $targetPostId, string $targetLanguage)
    {
    }

    public function convertInternalLinks(string $content, string $targetLanguage): string
    {
        return $content;
    }
}
