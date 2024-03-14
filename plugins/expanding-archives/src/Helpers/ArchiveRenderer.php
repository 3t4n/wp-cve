<?php
/**
 * ArchiveRenderer.php
 *
 * @package   expanding-archives
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2+
 */

namespace Ashleyfae\ExpandingArchives\Helpers;

use Ashleyfae\ExpandingArchives\ValueObjects\Month;

class ArchiveRenderer
{

    protected DateQuery $dateQuery;
    protected int $currentYear;
    protected int $currentMonth;
    protected bool $expandCurrent = false;

    public function __construct()
    {
        $this->dateQuery    = new DateQuery();
        $this->currentYear  = date('Y');
        $this->currentMonth = date('m');
    }

    public function expandCurrent(): self
    {
        $this->expandCurrent = true;

        return $this;
    }

    public function render(): void
    {
        ?>
        <div class="ng-expanding-archives-wrap">
            <?php
            foreach ($this->dateQuery->getPeriods() as $year => $months) {
                $this->renderYear($year, $months);
            }
            ?>
        </div>
        <?php
    }

    /**
     * Renders a year.
     *
     * @param  int  $year  Year to render.
     * @param  array  $months  Months in that year.
     *
     * @return void
     */
    protected function renderYear(int $year, array $months): void
    {
        ?>
        <div class="expanding-archives-section">
            <h3 class="expanding-archives-title">
                <a
                    href="#"
                    data-wrapper="expanding-archives-year-<?php echo esc_attr($year); ?>">
                    <?php echo esc_html($year); ?>
                </a>
            </h3>

            <div
                id="expanding-archives-year-<?php echo esc_attr($year); ?>"
                class="expanding-archives-collapse-section<?php echo $this->shouldExpandYear($year) ? ' expanding-archives-expanded' : ''; ?>"
            >
                <ul>
                    <?php
                    foreach ($months as $month) {
                        $this->renderMonth($month);
                    }
                    ?>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Renders the months in a year.
     *
     * @param  Month  $month
     *
     * @return void
     */
    protected function renderMonth(Month $month): void
    {
        ?>
        <li>
            <a
                href="<?php echo esc_url($month->getLink()); ?>"
                class="expanding-archives-clickable-month<?php echo $this->shouldExpandMonth($month) ? ' expandable-archive-rendered-true' : ''; ?>"
                data-year="<?php echo esc_attr($month->year); ?>"
                data-month="<?php echo esc_attr($month->monthNumber); ?>"
                data-rendered="<?php echo esc_attr($this->shouldExpandMonth($month) ? '1' : '0'); ?>"
            >
                <span class="expanding-archive-month">
                    <span
                        class="expand-collapse<?php echo $this->shouldExpandMonth($month) ? ' archive-expanded' : ''; ?>"
                    >
                        <?php echo $this->shouldExpandMonth($month) ? '&ndash;' : '+'; ?>
                    </span>
                    <?php echo $month->getDisplayDate(); ?>
                    <span class="expanding-archives-spinner"></span>
                </span>

                <span class="expanding-archive-count">
                    (<?php echo esc_html($month->postCount); ?>)
                </span>
            </a>

            <div
                class="expanding-archive-month-results"
                <?php echo $this->shouldExpandMonth($month) ? '' : 'style="display:none;"'; ?>
            >
                <?php
                if ($this->shouldExpandMonth($month)) {
                    echo $this->getPostsInMonthHtml($month);
                }
                ?>
            </div>
        </li>
        <?php
    }

    public function getPostsInMonthHtml(Month $month): string
    {
        $transientKey = sprintf('expanding_archives_posts_%d_%d', $month->year, $month->monthNumber);
        $posts        = get_transient($transientKey);

        if ($posts !== false) {
            return $posts;
        }

        $output = '<ul>';
        $posts  = $month->getPosts();
        if ($posts) {
            foreach ($posts as $post) {
                $output .= '<li><a href="'.esc_url(get_permalink($post)).'">'.esc_html(get_the_title($post)).'</a></li>';
            }
        } else {
            $output .= '<li>'.esc_html__('None yet.', 'expanding-archives').'</li>';
        }
        $output .= '</ul>';

        set_transient($transientKey, $output, DAY_IN_SECONDS);

        return $output;
    }

    protected function shouldExpandYear(int $year): bool
    {
        return $year === $this->currentYear && $this->expandCurrent;
    }

    protected function shouldExpandMonth(Month $month): bool
    {
        return $this->shouldExpandYear($month->year) && $month->monthNumber === $this->currentMonth;
    }

}
