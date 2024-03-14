<?php

namespace TotalContest\Restrictions;

/**
 * Class Cookies
 * @package TotalContest\Restrictions
 */
class Cookies extends Restriction
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
            // we are checking if we are inside 'vote" and of the prevent self vote arg is true
            if ($this->getAction() == 'vote' && $this->args['preventSelfVote']):
                $result = $result && !$this->getCookie($this->getOwnershipCookieName());
            endif;
        endif;

        if ($result && $this->getCategoryId() && $this->getPerCategory() > 0):
            $cookieValue = $this->getCookie($this->getCategoryAwareSubmissionCookieName());
            $result = !($cookieValue >= $this->getPerCategory());
        endif;


        return $result ?: new \WP_Error('cookies', $this->getMessage());
    }

    public function getPrefix()
    {
        return 'cookies';
    }


}
