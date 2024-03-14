<?php

namespace Flamix\Plugin\WP;

class Markup
{

    public static function markup_input(string $id, array $params)
    {
        ?>
        <tr class="form-field">
            <th>
                <label for="<?php echo $id; ?>"><?php echo esc_html($params['label'] ?? $id); ?></label>
            </th>
            <td>
                <input type="text"
                       name="<?php echo $id; ?>"
                       id="<?php echo $id; ?>"
                       value="<?php echo esc_html($params['value'] ?? ''); ?>"
                       placeholder="<?php echo esc_html($params['placeholder'] ?? ''); ?>"
                />
                <?php if (isset($params['description'])): ?>
                    <p class="description"><?php echo $params['description']; // Here we can put HTML tags ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }

    public static function markup_select(string $id, array $params)
    {
        ?>
        <tr class="form-field">
            <th>
                <label for="<?php echo $id; ?>"><?php echo esc_html($params['label'] ?? $id); ?></label>
            </th>
            <td>
                <select id="<?php echo $id; ?>" name="<?php echo $id; ?>">
                    <?php foreach ($params['options'] ?? [] as $option_key => $option_value): ?>
                        <option value="<?php echo esc_html($option_key); ?>" <?php echo ($option_key === ($params['value'] ?? '')) ? 'selected=selected' : '' ?>><?php echo esc_html($option_value); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($params['description'])): ?>
                    <p class="description"><?php echo $params['description']; // Here we can put HTML tags ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <?php
    }

    /**
     * Show admin msg (only when plugin active!)
     *
     * @param string $msg
     * @param string $type
     * @return string
     */
    public static function adminMessage(string $msg, string $type = 'updated'): string
    {
        return '<div class="' . esc_html($type) . ' notice"><p>' . $msg . ' </p></div>';
    }
}