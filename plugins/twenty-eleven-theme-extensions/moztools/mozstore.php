<?php
/*  Copyright 2011  Michael J. Walker (email: mike@moztools.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


interface MozStore_MozTheme2011 {
    public static function initStore($id);
    public function getItem($id = 1);
    public function addItem($item);
    public function update($item);
    public function updateItem($id, $item);
    public function updateItems($items);
    public function deleteItem($id = 1);
    public function deleteAllItems();
    public function getAllItems();
}

class MozOptionsStore_MozTheme2011 implements MozStore_MozTheme2011 {
    private $name;
    public static function initStore($name) {
        return new MozOptionsStore_MozTheme2011($name);
    }
    private function __construct($name) {
        $this->name = $name;
    }
    private function getStore() {
        $data = get_option($this->name);
        if ($data === false) {
            $data = array('idlast' => 0, 'items' => array());
        }
        return $data;
    }
    private function putStore($data) {
        update_option($this->name, $data);
    }
    private function clearStore() {
        delete_option($this->name);
    }
    public function getItem($id = 1) {
        $data = $this->getStore();
        return isset($data['items'][$id]) ? $data['items'][$id] : null;
    }
    public function getAllItems() {
        $data = $this->getStore();
        return $data['items'];
    }
    public function addItem($item) {
        $data = $this->getStore();
        $id = ++$data['idlast'];
        $data['items'][$id] = $item;
        $this->putStore($data);
        return $id;
    }
    public function update($item) {
        return $this->updateItem(1, $item);
    }
    public function updateItem($id, $item) {
        $data = $this->getStore();
        $data['items'][$id] = $item;
        if ($id > $data['idlast']) {
            $data['idlast'] = $id;
        }
        $this->putStore($data);
    }
    public function updateItems($items) {
        $data = $this->getStore();
        foreach ($items as $id -> $item) {
            $data['items'][$id] = $item;
            if ($id > $data['idlast']) {
                $data['idlast'] = $id;
            }
        }
        $this->putStore($data);
    }
    public function deleteItem($id = 1) {
        $data = $this->getStore();
        if (isset($data['items'][$id])) {
            unset($data['items'][$id]);
            $data = $this->putStore($data);
        }
    }
    public function deleteAllItems() {
        $data = $this->clearStore();
    }
}