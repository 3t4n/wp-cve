<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Models/Polls.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Models;


class Polls extends Model
{
    public function create()
    {

        $qry = "CREATE TABLE " . $this->db->mp_polls . " (" .
            "id int(10) NOT NULL auto_increment," .
            "question varchar(200) character set utf8 NOT NULL default ''," .
            "content text character set utf8 NOT NULL default ''," .
            "category int(10) NOT NULL default '0'," .
            "multiple tinyint(3) NOT NULL default '0'," .
            "showresult tinyint(3) NOT NULL default '1'," .
            "template_id int(10) NOT NULL default '1'," .
            "active tinyint(1) NOT NULL default '1'," .
            "start varchar(20) NOT NULL default ''," .
            "expiry int(10) NOT NULL default '0'," .
            "totalvotes int(10) NOT NULL default '0'," .
            "totalvoters int(10) NOT NULL default '0'," .
            "PRIMARY KEY  (id)" .
            ") $this->charsetCollate;";
        dbDelta($qry);
    }

    public function sampleData()
    {
        $exc = $this->db->insert($this->db->mp_polls,
            [
                'question' => __('How do you like this plugin?', FelixTzWPModernPollsTextdomain),
                'content' => __('This is a sample Text in an Poll', FelixTzWPModernPollsTextdomain),
                'start' => current_time('timestamp')
            ],
            ['%s', '%s', '%s']);

        return $exc;
    }

    public function insert($question, $content, $start, $expiry, $multiple, $active, $showresult, $template)
    {
        $qry = $this->db->insert($this->db->mp_polls,
            [
                'question' => $question,
                'content' => $content,
                'category' => 0,
                'multiple' => $multiple,
                'showresult' => $showresult,
                'template_id' => $template,
                'active' => $active,
                'start' => $start,
                'expiry' => $expiry,
                'totalvotes' => 0,
                'totalvoters' => 0

            ],
            ['%s', '%s', '%d', '%d', '%d', '%d', '%d', '%s', '%s', '%d', '%d']
        );

        if (!$qry) return false;

        $insertId = (int)$this->db->insert_id;
        return $insertId;
    }

    public function update($id, $question, $content, $start, $expiry, $multiple, $active, $showresult, $template)
    {
        $qry = $this->db->update($this->db->mp_polls,
            [
                'question' => $question,
                'content' => $content,
                'category' => 0,
                'multiple' => $multiple,
                'showresult' => $showresult,
                'template_id' => $template,
                'active' => $active,
                'start' => $start,
                'expiry' => $expiry,
                'totalvotes' => 0,
                'totalvoters' => 0

            ],
            ['id' => $id],
            ['%s', '%s', '%d', '%d', '%d', '%d', '%d', '%s', '%s', '%d', '%d'],
            ['%d']
        );

        if (!$qry) return false;

        return true;
    }

    public function getAll()
    {
        $qry = $this->db->get_results("SELECT * FROM " . $this->db->mp_polls . "  ORDER BY 'timestamp' DESC");
        return $qry;
    }

    public function get($id)
    {
        $qry = $this->db->get_row("SELECT * FROM " . $this->db->mp_polls . " WHERE id = " . $id . " ");
        return $qry;
    }

    public function open($id)
    {
        $qry = $this->db->update($this->db->mp_polls,
            ['active' => 1],
            ['id' => $id],
            ['%d'],
            ['%d']
        );

        if (!$qry) return false;
        return true;
    }

    public function close($id)
    {
        $qry = $this->db->update($this->db->mp_polls,
            ['active' => 0],
            ['id' => $id],
            ['%d'],
            ['%d']
        );

        if (!$qry) return false;
        return true;
    }

    public function delete($id)
    {
        $qry = $this->db->delete($this->db->mp_polls, ['id' => $id]);
        if ($qry) {
            return true;
        } else {
            return false;
        }
    }

    public function isOpen($id)
    {
        return (int)$this->db->get_var($this->db->prepare("SELECT COUNT(*) FROM " . $this->db->mp_polls . " WHERE id = %d AND active = 1", $id));
    }

    public function vote($id, $answerCount)
    {
        return $this->db->query("UPDATE " . $this->db->mp_polls . " SET totalvotes = (totalvotes+" . $answerCount . "), totalvoters = (totalvoters + 1) WHERE id = " . $id . " AND active = 1");
    }
}