<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Models/PollInfos.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Models;


class PollInfos extends Model
{
    public function create()
    {

        $qry = "CREATE TABLE " . $this->db->mp_pollinfos . " (" .
            "id int(10) NOT NULL auto_increment," .
            "mp_poll_id int(10) NOT NULL default '0'," .
            "answer varchar(200) character set utf8 NOT NULL default ''," .
            "votes int(10) NOT NULL default '0'," .
            "PRIMARY KEY  (id)" .
            ") $this->charsetCollate;";
        dbDelta($qry);
    }

    public function sampleData($pollID)
    {

        $this->db->insert($this->db->mp_pollinfos, ['mp_poll_id' => $pollID, 'answer' => __('Good', FelixTzWPModernPollsTextdomain)], ['%d', '%s']);
        $this->db->insert($this->db->mp_pollinfos, ['mp_poll_id' => $pollID, 'answer' => __('Excellent', FelixTzWPModernPollsTextdomain)], ['%d', '%s']);
        $this->db->insert($this->db->mp_pollinfos, ['mp_poll_id' => $pollID, 'answer' => __('Bad', FelixTzWPModernPollsTextdomain)], ['%d', '%s']);
        $this->db->insert($this->db->mp_pollinfos, ['mp_poll_id' => $pollID, 'answer' => __('Can Be Improved', FelixTzWPModernPollsTextdomain)], ['%d', '%s']);
        $this->db->insert($this->db->mp_pollinfos, ['mp_poll_id' => $pollID, 'answer' => __('No Comments', FelixTzWPModernPollsTextdomain)], ['%d', '%s']);
    }

    public function insert($pollId, $answers, $votes = 0)
    {

        $qry = $this->db->insert($this->db->mp_pollinfos,
            [
                'mp_poll_id' => $pollId,
                'answer' => $answers,
                'votes' => $votes
            ],
            ['%d', '%s', '%d']
        );
        return $qry;
    }

    public function getAnswers($id)
    {
        $qry = $this->db->get_results("SELECT answer FROM " . $this->db->mp_pollinfos . " WHERE mp_poll_id = " . $id . " ");
        $answers = [];
        foreach ($qry as $row) {
            array_push($answers, $row->answer);
        }
        return $answers;
    }

    public function getAnswerInfos($id)
    {
        $qry = $this->db->get_results("SELECT * FROM " . $this->db->mp_pollinfos . " WHERE mp_poll_id = " . $id . " ");
        return $qry;
    }

    public function delete($id)
    {
        $qry = $this->db->delete($this->db->mp_pollinfos, ['mp_poll_id' => $id]);
        if ($qry) {
            return true;
        } else {
            return false;
        }
    }

    public function vote($id, $answer)
    {
        return $this->db->query("UPDATE " . $this->db->mp_pollinfos . " SET votes = (votes + 1) WHERE mp_poll_id = " . $id . " AND id = " . $answer);
    }
}