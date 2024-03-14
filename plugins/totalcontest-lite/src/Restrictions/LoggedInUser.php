<?php

namespace TotalContest\Restrictions;

/**
 * Class LoggedInUser
 * @package TotalContest\Restrictions
 */
class LoggedInUser extends Restriction
{
    /**
     * Check logic.
     *
     * @return \WP_Error|bool
     */
    public function check()
    {
        $result = true;

        if ($this->getContestId()):
            $cookieValue = $this->getCookie($this->getContestCookieName());
            $result = !($cookieValue >= $this->getCount());
        endif;

        if ($result && $this->getSubmissionId()):
            $cookieValue = $this->getCookie($this->getSubmissionCookieName());
            $result = !($cookieValue >= $this->getPerItem());
            if ($this->getAction() == 'vote' && $this->args['preventSelfVote']):
                $result = $result && !$this->getCookie($this->getOwnershipCookieName());
            endif;
        endif;

        if ($result && $this->getCategoryId() && $this->getPerCategory() > 0):
            $cookieValue = $this->getCookie($this->getCategoryAwareSubmissionCookieName());
            $result = !($cookieValue >= $this->getPerCategory());
        endif;

        if (($this->isFullCheck() || $result) && is_user_logged_in()):

            $timeout = (int)$this->getTimeout();
            $conditions = [
                'contest_id' => $this->getContestId(),
                'action' => $this->getAction(),
                'status' => 'accepted',
                'user_id' => get_current_user_id(),
                'date' => [],
            ];

            if ($timeout !== 0):
                $date = TotalContest('datetime', ["-{$timeout} minutes", new \DateTimeZone('UTC')]);
                $conditions['date'][] = ['operator' => '>', 'value' => $date->format('Y/m/d H:i:s')];
            endif;

            $count = TotalContest('log.repository')->count(['conditions' => $conditions,]);

            if ($count >= $this->getCount()):
                $this->setCookie($this->getContestCookieName(), (int)$this->getCount(), $timeout);
                $result = false;
            elseif ($this->getSubmissionId()):

                $conditions['submission_id'] = $this->getSubmissionId();

                if ($this->getAction() == 'vote' && $this->args['preventSelfVote'] && $this->args['submission']->getAuthor()->ID == $conditions['user_id']):
                    $result = false;
                else :
                    $count = TotalContest('log.repository')->count(['conditions' => $conditions]);
                    $result = !($count >= $this->getPerItem());

                    if (!$result):
                        $this->setCookie($this->getSubmissionCookieName(), (int)$this->getPerItem(), $timeout);
                    elseif ($this->getCategoryId() && $this->getPerCategory() > 0):
                        unset($conditions['submission_id']);
                        $conditions['category_id'] = $this->getCategoryId();

                        $count = TotalContest('log.repository')->count(['conditions' => $conditions]);
                        $result = !($count >= $this->getPerCategory());

                        if (!$result):
                            $this->setCookie($this->getCategoryAwareSubmissionCookieName(), (int)$this->getPerCategory(), $timeout);
                        endif;
                    endif;
                endif;

            endif;
        endif;

        return $result ?: new \WP_Error('user', $this->getMessage());
    }

    public function getPrefix()
    {
        return 'user';
    }
}
