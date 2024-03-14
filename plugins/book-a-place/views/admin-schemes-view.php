<div id="scheme-place-dialog-form" title="<?php _e("Set a place", $this->plugin_slug); ?>">
    <p class="validateTips"><?php _e("All form fields are required.", $this->plugin_slug); ?></p>
    <form>
        <fieldset>
            <input type="hidden" name="action" value=""/>
            <label for="scheme-place-name"><?php _e("Name", $this->plugin_slug); ?></label>
            <input type="text" name="scheme-place-name" id="scheme-place-name" class="text" />
            <label for="scheme-place-description"><?php _e("Description", $this->plugin_slug); ?></label>
            <textarea name="scheme-place-description" id="scheme-place-description" class="text"></textarea>
            <label for="scheme-place-price"><?php _e("Price", $this->plugin_slug); ?></label>
            <input type="text" name="scheme-place-price" id="scheme-place-price" value="" class="text" />
            <div id="scheme-cell-color">
                <input type="text" value="#bada55" class="scheme-cell-color-field" data-default-color="#effeff" />
            </div>
        </fieldset>
    </form>
</div>

<div id="scheme-place-status-form" title="<?php _e("Change place status", $this->plugin_slug); ?>">
    <form>
        <fieldset>
            <label for="scheme-place-status"><?php _e("Place status:", $this->plugin_slug); ?> </label>
            <select name="scheme-place-status" id="scheme-place-status">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </fieldset>
    </form>
</div>

<div id="scheme-controls" class="ui-widget-header">
    <a id="scheme-set-place" href="#" title="<?php _e("Set", $this->plugin_slug); ?>"></a>
    <a id="scheme-edit-place" href="#" title="<?php _e("Edit", $this->plugin_slug); ?>"></a>
    <a id="scheme-unset-place" href="#" title="<?php _e("Unset", $this->plugin_slug); ?>"></a>
    <a id="scheme-change-place-status" href="#" title="<?php _e("Change Status", $this->plugin_slug); ?>"></a>
</div>

<div id="scheme-container">

    <?php echo $this->display_scheme($_GET['scheme']); ?>

</div>