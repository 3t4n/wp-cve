<?php

namespace Memsource\Service\TranslationPlugin;

use Exception;
use Inpsyde\MultilingualPress\Framework\Api\ContentRelations;
use Memsource\Exception\NotFoundException;
use WP_Error;

use function Inpsyde\MultilingualPress\assignedLanguages;
use function Inpsyde\MultilingualPress\currentSiteLocale;
use function Inpsyde\MultilingualPress\resolve;

class MultilingualpressPlugin implements ITranslationPlugin
{
    public function getName(): string
    {
        return 'MultilingualPress';
    }

    public function isPluginActive(): bool
    {
        return class_exists('Inpsyde\MultilingualPress\MultilingualPress');
    }

    public function supportsAdminMenu(): bool
    {
        return false;
    }

    public function supportsNetworkAdminMenu(): bool
    {
        return true;
    }

    public function getSourceLanguage(): string
    {
        return mb_strtolower(currentSiteLocale());
    }

    public function getTargetLanguages(): array
    {
        $sourceLanguage = $this->getSourceLanguage();
        $result = [];

        foreach ($this->getActiveLanguagesDetails() as $language) {
            if ($language['code'] !== $sourceLanguage) {
                $result[$language['id']] = $language['code'];
            }
        }

        return $result;
    }

    public function getActiveLanguages(): array
    {
        return [$this->getSourceLanguage()] + $this->getTargetLanguages();
    }

    public function getActiveLanguagesDetails(): array
    {
        $result = [];

        foreach (assignedLanguages() as $siteId => $assignedLanguage) {
            $result[] = [
                'id' => $siteId,
                'code' => mb_strtolower($assignedLanguage->locale()),
                'native_name' => $assignedLanguage->nativeName(),
            ];
        }

        return $result;
    }

    public function storePostTranslation(string $wpContentType, int $sourceContentId, string $targetLanguage, array $content): int
    {
        $api = $this->api();

        $targetSiteId = $this->getSiteIdByLang($targetLanguage);

        // try to find translation
        $targetPostId = $api->contentIdForSite(get_current_blog_id(), $sourceContentId, ContentRelations::CONTENT_TYPE_POST, $targetSiteId);

        if ($targetPostId === 0) {
            // insert
            $mainBlogId = $this->switchToSite($targetSiteId);
            $targetPostId = wp_insert_post($content);

            if ($targetPostId instanceof WP_Error) {
                throw new Exception('Call wp_insert_post() failed: ' . $targetPostId->get_error_message());
            } elseif ($targetPostId === 0) {
                throw new Exception('Call wp_insert_post() returned 0.');
            }

            $this->switchToSite($mainBlogId);
            // link src-target
            $currentRelations = $api->relations($mainBlogId, $sourceContentId, ContentRelations::CONTENT_TYPE_POST);
            if (empty($currentRelations)) {
                $currentRelations = [
                    $mainBlogId => $sourceContentId,
                ];
            }
            $newRelation =  [
                $this->getSiteIdByLang($targetLanguage) => $targetPostId,
            ];
            $api->createRelationship($currentRelations + $newRelation, ContentRelations::CONTENT_TYPE_POST);
        } else {
            // update
            $mainBlogId = $this->switchToSite($targetSiteId);
            $content['ID'] = $targetPostId;
            wp_update_post($content);

            if ($targetPostId instanceof WP_Error) {
                throw new Exception('Call wp_update_post() failed: ' . $targetPostId->get_error_message());
            } elseif ($targetPostId === 0) {
                throw new Exception('Call wp_update_post() returned 0.');
            }

            $this->switchToSite($mainBlogId);
        }

        return $targetPostId;
    }

    public function transferPostTerms(string $taxonomy, array $sourceTermsIds, int $targetPostId, string $targetLanguage)
    {
        $api = $this->api();

        $targetSiteId = $this->getSiteIdByLang($targetLanguage);

        $targetTerms = [];

        foreach ($sourceTermsIds as $sourceTermId) {
            $translationId = $api->contentIdForSite(get_current_blog_id(), $sourceTermId, ContentRelations::CONTENT_TYPE_TERM, $targetSiteId);

            if ($translationId !== null) {
                $targetTerms[] = $translationId;
            }
        }

        if (!empty($targetTerms)) {
            $mainBlogId = $this->switchToSite($targetSiteId);
            wp_set_post_terms($targetPostId, $targetTerms, $taxonomy);
            $this->switchToSite($mainBlogId);
        }
    }

    public function copyPostThumbnail(int $sourcePostId, int $targetPostId, string $targetLanguage)
    {
        $sourceThumbnailId = get_post_thumbnail_id($sourcePostId);

        if ($sourceThumbnailId === false) {
            return;
        }

        $imageUrl = wp_get_attachment_image_src($sourceThumbnailId, 'full')[0];
        $filename = uniqid() . '-' . $targetLanguage . '-' . basename($imageUrl);

        $mainBlogId = $this->switchToSiteByTargetLang($targetLanguage);

        if (get_post_thumbnail_id($targetPostId) === false) {
            $uploadDir = wp_upload_dir();

            if (wp_mkdir_p($uploadDir['path'])) {
                $file = $uploadDir['path'] . DIRECTORY_SEPARATOR . $filename;
            } else {
                $file = $uploadDir['basedir'] . DIRECTORY_SEPARATOR . $filename;
            }

            file_put_contents($file, file_get_contents($imageUrl));

            $attachment = [
                'post_mime_type' => wp_check_filetype($filename)['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit',
            ];

            $attachmentId = wp_insert_attachment($attachment, $file, $targetPostId);

            require_once(ABSPATH . 'wp-admin/includes/image.php');
            wp_update_attachment_metadata($attachmentId, wp_generate_attachment_metadata($attachmentId, $file));

            set_post_thumbnail($targetPostId, $attachmentId);
        }

        $this->switchToSite($mainBlogId);
    }

    public function convertInternalLinks(string $content, string $targetLanguage): string
    {
        return $content;
    }

    public function storeTermTranslation(string $wpContentType, int $sourceContentId, string $targetLanguage, string $title, string $description, int $parentTranslationId): int
    {
        $api = $this->api();

        $targetSiteId = $this->getSiteIdByLang($targetLanguage);

        // try to find translation
        $targetTermId = $api->contentIdForSite(get_current_blog_id(), $sourceContentId, ContentRelations::CONTENT_TYPE_TERM, $targetSiteId);

        if ($targetTermId === 0) {
            // insert
            $mainBlogId = $this->switchToSite($targetSiteId);
            $response = wp_insert_term($title, $wpContentType, [
                'description' => $description,
                'slug' => sanitize_title($title),
                'parent' => $parentTranslationId,
            ]);
            $targetTermId = $response['term_id'] ?? null;
            $this->switchToSite($mainBlogId);

            if ($response instanceof WP_Error) {
                throw new Exception('Call wp_insert_term() failed: ' . $response->get_error_message());
            }

            // link src-target
            $currentRelations = $api->relations($mainBlogId, $sourceContentId, ContentRelations::CONTENT_TYPE_TERM);
            if (empty($currentRelations)) {
                $currentRelations = [
                    $mainBlogId => $sourceContentId,
                ];
            }
            $newRelation =  [
                $targetSiteId => $targetTermId,
            ];
            $api->createRelationship($currentRelations + $newRelation, ContentRelations::CONTENT_TYPE_TERM);
        } else {
            // update
            $mainBlogId = $this->switchToSite($targetSiteId);
            $response = wp_update_term($targetTermId, $wpContentType, [
                'name' => $title,
                'description' => $description,
            ]);
            $this->switchToSite($mainBlogId);

            if ($response instanceof WP_Error) {
                throw new Exception('Call wp_update_term() failed: ' . $response->get_error_message());
            }

            $targetTermId = $response['term_id'];
        }

        return $targetTermId;
    }

    public function getTermTranslationId(string $wpContentType, int $sourceContentId, string $targetLanguage)
    {
        $targetSiteId = $this->getSiteIdByLang($targetLanguage);
        $targetTermId = $this->api()->contentIdForSite(get_current_blog_id(), $sourceContentId, ContentRelations::CONTENT_TYPE_TERM, $targetSiteId);

        return $targetTermId !== 0 ? $targetTermId : null;
    }

    public function switchToSiteByTargetLang(string $language): int
    {
        $previousSiteId = get_current_blog_id();
        $targetSiteId = $this->getSiteIdByLang($language);
        switch_to_blog($targetSiteId);
        return $previousSiteId;
    }

    public function switchToSite(int $siteId): int
    {
        $previousSiteId = get_current_blog_id();
        switch_to_blog($siteId);
        return $previousSiteId;
    }

    private function getSiteIdByLang(string $language): int
    {
        foreach ($this->getTargetLanguages() as $siteId => $activeLanguage) {
            if ($activeLanguage === $language) {
                return $siteId;
            }
        }

        throw new NotFoundException(sprintf('Site with target language \'%s\' not found (1).', $language));
    }

    private function api(): ContentRelations
    {
        return resolve(ContentRelations::class);
    }
}
