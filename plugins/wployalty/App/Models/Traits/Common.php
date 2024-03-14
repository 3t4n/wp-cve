<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Models\Traits;
defined('ABSPATH') or die();

trait Common
{
    function bulkAction($ids, $action)
    {
        if (empty($ids) || empty($action) || !in_array($action, array('delete', 'activate', 'deactivate'))) {
            return false;
        }
        $status = false;
        switch ($action) {
            case 'delete':
                $status = $this->bulkDelete($ids);
                break;
            case 'activate':
                $status = $this->bulkActiveOrDeActive($ids, 1);
                break;
            case 'deactivate':
                $status = $this->bulkActiveOrDeActive($ids, 0);
                break;
        }
        return $status;
    }

    protected function bulkDelete($ids)
    {
        if (empty($ids) || !is_array($ids)) {
            return false;
        }
        try {
            $status = false;
            foreach ($ids as $id) {
                $status = $this->deleteById($id);
            }
        } catch (\Exception $e) {
            $status = false;
        }
        return $status;
    }

    function deleteById($id)
    {
        if (empty($id)) {
            return false;
        }
        try {
            if (!$this->deleteRow(array(
                'id' => (int)$id
            ))) {
                return false;
            };
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    protected function bulkActiveOrDeActive($ids, $active = 0)
    {
        if (empty($ids) || !is_array($ids)) {
            return false;
        }
        try {
            $status = false;
            foreach ($ids as $id) {
                $status = $this->activateOrDeactivate($id, $active);
            }
        } catch (\Exception $e) {
            $status = false;
        }
        return $status;
    }

    function activateOrDeactivate($id, $active = 0)
    {
        if (empty($id)) {
            return false;
        }
        $status = false;
        $updateData = array(
            'active' => (int)$active,
            'modified_at' => strtotime(date("Y-m-d H:i:s")),
        );
        $where = array('id' => $id);
        if ($this->updateRow($updateData, $where)) {
            $status = true;
        }
        return $status;
    }
}