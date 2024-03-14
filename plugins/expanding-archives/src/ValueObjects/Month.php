<?php
/**
 * Month.php
 *
 * @package   expanding-archives
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2+
 */

namespace Ashleyfae\ExpandingArchives\ValueObjects;

class Month
{

    public int $year;
    public int $monthNumber;
    public int $postCount = 0;

    public function __construct(int $year, int $monthNumber, int $postCount = 0)
    {
        $this->year        = $year;
        $this->monthNumber = $monthNumber;
        $this->postCount   = $postCount;
    }

    /**
     * Returns the link to this month's archive page.
     *
     * @since 1.0
     *
     * @return string
     */
    public function getLink(): string
    {
        return get_month_link(
            $this->year,
            $this->monthNumber
        );
    }

    private function timestamp(): int
    {
        return mktime(0, 0, 0, $this->monthNumber, 1, $this->year) ? : time();
    }

    public function getDisplayDate(): string
    {
        return date_i18n('F', $this->timestamp());
    }

    public function getStart(): string
    {
        $newTimestamp = strtotime(date('Y-m-1 00:00:00', $this->timestamp()));

        return date(DATE_RFC3339, $newTimestamp);
    }

    public function getEnd(): string
    {
        $newTimestamp = strtotime(date('Y-m-t 23:59:59', $this->timestamp()));

        return date(DATE_RFC3339, $newTimestamp);
    }

    public function getPosts(): array
    {
        $args = [
            'posts_per_page' => -1,
            'nopaging'       => true,
            'year'           => $this->year,
            'monthnum'       => $this->monthNumber,
        ];

        /**
         * Filters the arguments used for retrieving posts.
         *
         * @since 1.1.1
         *
         * @param  array  $args
         */
        $args = apply_filters('expanding_archives_get_posts', $args);

        return get_posts($args);
    }

}
