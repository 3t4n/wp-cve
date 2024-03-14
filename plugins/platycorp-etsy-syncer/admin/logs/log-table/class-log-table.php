<?php

namespace platy\etsy\admin;

class LogTable{
    private $rows;

    public function __construct($rows) {
        $this->rows = $rows;
    }

    private function get_status_color($status) {
        switch($status) {
            case 0:
                return "red";
            case 1:
                return "green";
            default:
                return "black";
        }
    }

    public function render() {
        if(\count($this->rows) == 0) {
            ?>
            <h2>No logs found</h1>
            <?php
        }
        if (count($this->rows) > 0): ?>
            <table>
              <thead>
                <tr>
                    <th width="%15"><?php echo "Date" ?></th>
                    <th width="%85"><?php echo "Message" ?></th>
                </tr>
              </thead>
              <tbody>
            <?php foreach ($this->rows as $row):  ?>
                <tr style="font-size: larger;">
                    <td style="padding: 7px"><b><?php echo $row['date'] ?></b></td>
                    <td style="padding: 7px; color: <?php echo $this->get_status_color($row['status']) ?>;"><b><?php echo $row['message'] ?></b></td>
                </tr>
            <?php endforeach; ?>
              </tbody>
            </table>
            <?php endif; ?>
        <?php
    }
}