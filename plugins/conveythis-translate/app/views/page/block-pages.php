<div class="tab-pane fade" id="v-pills-block" role="tabpanel" aria-labelledby="block-pages-tab">

    <div class="title">Block pages</div>

    <div class="form-group">
        <div class="subtitle">Add alternative links for excluded pages</div>
        <div class="radio-block">
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" id="conveythis_lang_code_url_yes" name="conveythis_lang_code_url" value="1" <?php echo $this->variables->lang_code_url == 1 ? 'checked' : '' ?> <?php echo count($this->variables->blockpages) <= 0 ? 'disabled' : '' ?>>
                <label for="conveythis_lang_code_url_yes">Yes</label></div>
            <div class="form-check">
                <input type="radio" class="form-check-input me-2" id="conveythis_lang_code_url_no" name="conveythis_lang_code_url" value="0" <?php echo $this->variables->lang_code_url == 0 ? 'checked' : '' ?> <?php echo count($this->variables->blockpages) <= 0 ? 'disabled' : '' ?>>
                <label for="conveythis_lang_code_url_no">No</label></div>
        </div>
    </div>

    <div class="form-group paid-function">
        <div class="subtitle">Block pages</div>
        <label>Add URL that you want to exclude from translations.</label>
        <div id="blockpages_wrapper" class="w-100">
            <?php if (count($this->variables->blockpages) > 0) : ?>
                <?php foreach( $this->variables->blockpages as $blockpage ): ?>
                    <div class="blockpage position-relative w-100 pe-4">
                        <button class="conveythis-delete-page"></button>
                        <div class="ui input w-100">
                            <input type="url" name="blockpages[]" class="w-100" placeholder="https://example.com" value="<?php echo  esc_url($blockpage);?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button class="btn-default" type="button" id="add_blockpage" style="color: #8A8A8A">Add more URLs</button>
        <label class="hide-paid" for="">This feature is not available on Free plan. If you want to use this feature, please <a href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress" target="_blank" class="grey">upgrade your plan</a>.</label>
    </div>

    <!--Separator-->
    <div class="line-grey mb-2"></div>

    <div class="form-group paid-function">
        <label>Add rule that you want to exclude from translations.</label>
        <div id="exclusion_wrapper" class="w-100">
            <?php if(isset($this->variables->exclusions) && count($this->variables->exclusions) > 0) : ?>
                <?php foreach($this->variables->exclusions as $exclusion ): ?>
                    <?php if (is_array($exclusion)) : ?>
                        <div class="exclusion d-flex position-relative w-100 pe-4">
                            <button class="conveythis-delete-page"></button>
                            <div class="dropdown me-3">
                                <i class="dropdown icon"></i>
                                <select class="dropdown fluid ui form-control rule" >
                                    <?php foreach (['start', 'end', 'contain', 'equal'] as $rule) :?>
                                        <?php if (isset($exclusion['rule']) && !empty($exclusion['rule'])) : ?>
                                            <option value="<?php echo $rule ?>"<?php echo ($exclusion['rule'] == $rule ? 'selected': '')?>><?php echo ucfirst($rule); ?></option>
                                        <?php endif ; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <input type="hidden" class="exclusion_id" value="<?php echo (isset($exclusion['id']) ? $exclusion['id'] : '') ?>"/>
                            <div class="ui input w-100">
                                <input type="text" value="<?php echo (isset($exclusion['page_url']) ? $exclusion['page_url'] : '') ?>" class="page_url w-100" placeholder="https://example.com" value="">
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <input type="hidden" name="exclusions" value='<?php echo json_encode( $this->variables->exclusions ); ?>'>
        <button class="btn-default" type="button" id="add_exlusion" style="color: #8A8A8A">Add more rules</button>
        <label class="hide-paid" for="">This feature is not available on Free plan. If you want to use this feature, please <a href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress" target="_blank" class="grey">upgrade your plan</a>.</label>
    </div>

    <!--Separator-->
    <div class="line-grey mb-2"></div>


    <div class="form-group paid-function">
        <label>Exclusion div Ids</label>
        <div id="exclusion_block_wrapper">
                <?php foreach( $this->variables->exclusion_blocks as $exclusion_block ) : ?>
                    <?php if (is_array($exclusion_block)) : ?>
                        <div class="exclusion_block position-relative w-100 pe-4">
                            <button class="conveythis-delete-page"></button>
                            <div class="ui input">
                                <input disabled="disabled" type="text" class="form-control id_value w-100" value="<?php echo isset($exclusion_block['id_value']) ? $exclusion_block['id_value'] : '' ?>" placeholder="Enter id">
                            </div>
                            <input type="hidden" class="exclusion_block_id" value="<?php echo $exclusion_block['id']; ?>"/>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
        </div>
        <input type="hidden" name="exclusion_blocks" value='<?php echo  json_encode( $this->variables->exclusion_blocks ); ?>'>
        <button class="btn-default" type="button" id="add_exlusion_block" style="color: #8A8A8A">Add more ids</button>
        <label class="hide-paid" for="">This feature is not available on Free plan. If you want to use this feature, please <a href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress" target="_blank" class="grey">upgrade your plan</a>.</label>
    </div>

</div>