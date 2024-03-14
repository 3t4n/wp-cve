<?php

namespace Rockschtar\WordPress\ColoredAdminPostList\Utils;

use Rockschtar\WordPress\ColoredAdminPostList\Enums\DefaultColor;
use Rockschtar\WordPress\ColoredAdminPostList\Models\PostStatus;

class PostStati
{
    private static array $defaultPostStati = ["publish", "pending", "future", "private", "draft", "trash"];

    /**
     * @return PostStatus[]
     */
    public static function getDefault(): array
    {
        return array_filter(self::get(), static function (PostStatus $postStatus) {
            return in_array($postStatus->getName(), self::$defaultPostStati, true);
        });
    }

    /**
     * @return PostStatus[]
     */
    public static function getCustom(): array
    {
        return array_filter(self::get(), static function (PostStatus $postStatus) {
            return !in_array($postStatus->getName(), self::$defaultPostStati, true);
        });
    }

    /**
     * @return PostStatus[]
     */
    public static function get(): array
    {
        $postStati = get_post_stati([], "objects");
        $customPostStati = [];
        $defaultColors = DefaultColor::all();

        foreach ($postStati as $postStatus) {
            if ($postStatus->show_in_admin_status_list === false) {
                continue;
            }

            $defaultColor  = $defaultColors[strtoupper($postStatus->name)] ?? null;
            $customPostStati[] = new PostStatus($postStatus->label, $postStatus->name, $defaultColor);
        }

        return $customPostStati;
    }
}
