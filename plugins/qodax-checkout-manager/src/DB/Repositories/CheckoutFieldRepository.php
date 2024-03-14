<?php

namespace Qodax\CheckoutManager\DB\Repositories;

if ( ! defined('ABSPATH')) {
    exit;
}

class CheckoutFieldRepository
{
    /**
     * @var \wpdb
     */
    private $wpdb;

    public function __construct()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    public function findBySection(string $section): array
    {
        $query = $this->wpdb->prepare("
            SELECT *
            FROM " . $this->wpdb->prefix . "qodax_checkout_manager_fields
            WHERE `section` = %s
        ", [ $section ]);

        $results = $this->wpdb->get_results($query, ARRAY_A);
        if (!$results) {
            return [];
        }

        $fields = [];
        foreach ($results as $row) {
            $displayRules = $this->wpdb->get_results("
                SELECT * 
                FROM {$this->wpdb->prefix}qodax_checkout_manager_display_rules
                WHERE field_id = " . (int)$row['id'] . "
            ", ARRAY_A);

            $row['display_rules'] = [];
            foreach ($displayRules as $rule) {
                unset($rule['id'], $rule['field_id'], $rule['created_at']);
                $rule['conditions'] = json_decode($rule['conditions'], true);
                if (json_last_error()) {
                    $rule['conditions'] = [];
                }

                $row['display_rules'][] = $rule;
            }

            $fields[] = $row;
        }

        return $fields;
    }

    public function getCustomFields(): array
    {
        $results = $this->wpdb->get_results("
            SELECT *
            FROM " . $this->wpdb->prefix . "qodax_checkout_manager_fields
            WHERE native = 0
        ", ARRAY_A);

        $fields = [];
        foreach ($results as $row) {
            $row['display_rules'] = $this->getDisplayRules((int)$row['id']);
            $fields[] = $row;
        }

        return $fields;
    }

    public function findByNames(array $names): array
    {
        $patterns = array_map(function ($item) {
            return '%s';
        }, $names);

        $query = $this->wpdb->prepare("
            SELECT *
            FROM " . $this->wpdb->prefix . "qodax_checkout_manager_fields
            WHERE field_name in (" . implode(',', $patterns) . ")
        ", $names);

        return $this->wpdb->get_results($query, ARRAY_A);
    }

    public function all(): array
    {
        $results = $this->wpdb->get_results("
            SELECT *
            FROM " . $this->wpdb->prefix . "qodax_checkout_manager_fields
        ", ARRAY_A);

        $fields = [];
        foreach ($results as $row) {
            $row['display_rules'] = $this->getDisplayRules((int)$row['id']);
            $fields[] = $row;
        }

        return $fields;
    }

    public function deleteBySection(string $section)
    {
        $this->wpdb->query($this->wpdb->prepare(
            "DELETE FROM " . $this->wpdb->prefix . "qodax_checkout_manager_fields WHERE `section` = %s",
            [ $section ]
        ));
    }

    public function insert(array $field, string $section): int
    {
        $this->wpdb->insert($this->wpdb->prefix . 'qodax_checkout_manager_fields', [
            'data_version' => 1, // hardcoded yet
            'field_name' => $field['name'],
            'field_type' => $field['type'],
            'field_meta' => json_encode($field['meta']),
            'section' => $section,
            'native' => (int)$field['native'],
            'required' => (int)$field['required'],
            'active' => (int)$field['active'],
            'priority' => (int)$field['priority']
        ]);

        return $this->wpdb->insert_id;
    }

    public function deleteOldDisplayRules(): void
    {
        $result = $this->wpdb->get_results(
        "SELECT r.id FROM {$this->wpdb->prefix}qodax_checkout_manager_display_rules r
                LEFT JOIN {$this->wpdb->prefix}qodax_checkout_manager_fields f ON r.field_id = f.id
            WHERE f.id IS NULL",
            ARRAY_A
        );

        if (!$result) {
            return;
        }

        $this->wpdb->query(
        "DELETE FROM {$this->wpdb->prefix}qodax_checkout_manager_display_rules 
            WHERE id IN (" . implode(',', array_column($result, 'id')) . ")"
        );
    }

    public function insertDisplayRules(int $fieldId, array $rules): void
    {
        foreach ($rules as $rule) {
            $conditions = [];
            foreach ($rule['conditions'] ?? [] as $condition) {
                unset($condition['_id']);
                $conditions[] = $condition;
            }

            $this->wpdb->insert($this->wpdb->prefix . 'qodax_checkout_manager_display_rules', [
                'field_id' => $fieldId,
                'action' => $rule['action'],
                'logic' => $rule['logic'],
                'data_version' => 1, // hardcoded yet
                'conditions' => json_encode($conditions),
                'priority' => 0, // hardcoded yet
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    private function getDisplayRules(int $fieldId): array
    {
        $displayRules = $this->wpdb->get_results("
                SELECT * 
                FROM {$this->wpdb->prefix}qodax_checkout_manager_display_rules
                WHERE field_id = " . $fieldId . "
            ", ARRAY_A);

        $rules = [];
        foreach ($displayRules as $rule) {
            unset($rule['id'], $rule['field_id'], $rule['created_at']);
            $rule['conditions'] = json_decode($rule['conditions'], true);
            if (json_last_error()) {
                $rule['conditions'] = [];
            }

            $rules[] = $rule;
        }

        return $rules;
    }
}