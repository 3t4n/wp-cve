<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Models/Templates.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Models;


class Templates extends Model
{
    public function create()
    {
        $qry = "CREATE TABLE " . $this->db->mp_templates . " (" .
            "id int(10) NOT NULL auto_increment," .
            "name varchar(200) character set utf8 NOT NULL default ''," .
            "dir varchar(200) NOT NULL default ''," .
            "PRIMARY KEY  (id)" .
            ") $this->charsetCollate;";
        dbDelta($qry);
    }

    public function sampleData()
    {
        $this->db->insert($this->db->mp_templates, [
            'name' => __('Default', FelixTzWPModernPollsTextdomain),
            'dir' => 'default'], ['%s', '%s']);

        $this->db->insert($this->db->mp_templates, [
            'name' => __('Basic', FelixTzWPModernPollsTextdomain),
            'dir' => 'basic'], ['%s', '%s']);
    }

    public function insert($name, $dir)
    {
        $exc = $this->db->insert($this->db->mp_templates, [
            'name' => $name,
            'dir' => $dir], ['%s', '%s']);

        return $exc;
    }

    public function getAll()
    {
        $qry = $this->db->get_results("SELECT * FROM " . $this->db->mp_templates . " ");
        return $qry;
    }

    public function get($id)
    {
        $qry = $this->db->get_row("SELECT * FROM " . $this->db->mp_templates . " WHERE id = " . $id . " ");
        return $qry;
    }

    public function delete($id)
    {
        $qry = $this->db->delete($this->db->mp_templates, ['id' => $id]);
        if ($qry) {
            return true;
        } else {
            return false;
        }
    }
}