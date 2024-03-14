<div class="tab-pane fade" id="v-pills-links" role="tabpanel" aria-labelledby="links-tab">
    <div class="form-group paid-function">

        <div class="title">System links</div>

        <div>
            <div class="alert alert-primary" role="alert">
                <strong>Note:</strong> Please provide the link in the format (/404.html or /system/link) without using (https:// or http://), and also without the domain name (temp_domain.com).
            </div>
        </div>

        <label>System links</label>

        <div id="system_link_wrapper" class="col-md-12">
            <?php if (
                    isset($this->variables->system_links) &&
                    is_array($this->variables->system_links) &&
                    count($this->variables->system_links) > 0
            ) : ?>
                <?php foreach( $this->variables->system_links as $link ): ?>
                    <?php if (is_array($link)) : ?>
                        <div class="system_link position-relative w-100">
                            <input type="hidden" class="system_link_id" value="<?php echo (isset($link['link_id']) ? $link['link_id'] : '') ?>"/>
                            <button type="submit" name="submit" class="conveythis-delete-page"></button>
                            <div class="row w-100 mb-2">

                                <div class="ui input w-100">

                                    <input
                                            type="text"
                                            id="link_enter"
                                            class="link_text w-100 conveythis-input-text"
                                            placeholder="Enter link (/404.html or /path/path...)"
                                            value="<?php echo (isset($link['link']) ? $link['link']: '') ?>"
                                    >

                                </div>

                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <input type="hidden" name="conveythis_system_links" value='<?php echo json_encode( $this->variables->system_links ); ?>'>
        <button class="btn-default" type="button" id="add_system_link" style="color: #8A8A8A">Add link</button>

    </div>
</div>