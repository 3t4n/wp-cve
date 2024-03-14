<div class="tab-pane fade" id="v-pills-cache" role="tabpanel" aria-labelledby="cache-tab">

    <div class="form-group">
        <div class="title">Cache</div>

        <div class="block-setting form-group paid-function">

            <label class="pb-2">Settings cache</label>

            <div class="form-group form-input-value paid-function">
                <label for="clear_cache" class="subtitle">Cache clear time (hours)</label>
                <input type=text class="me-2" id="conveythis_clear_cache" name="conveythis_clear_cache" value="<?php echo $this->variables->clear_cache < 1 ? '0' : $this->variables->clear_cache ?>" >
                <label class="hide-paid" for="">This feature is only available for Grand and Enterprise plans. If you want to use this feature, please <a href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress" target="_blank" class="grey">upgrade your plan</a>.</label>
            </div>

        </div>

        <div class="block-setting form-group paid-function">
            <label class="pb-2">Translation cache</label>
            <button class="btn-default" type="button" id="clear_translate_cache" style="color: #8A8A8A">Clear translation cache</button>
            <label class="hide-paid" for="">
                This feature is not available on Free plan. If you want to use this feature, please <a href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress" target="_blank" class="grey">upgrade your plan</a>.
            </label>
        </div>

    </div>

</div>

