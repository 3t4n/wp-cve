<div class="tab-pane fade" id="v-pills-glossary" role="tabpanel" aria-labelledby="glossary-tab">
   <div class="form-group paid-function">
       <div class="title">Glossary</div>
                <div>If you have a caching plugin installed, the data may be out of date. Please clean up pages that have been modified with your caching plugin.</div>
                <label>Glossary rules</label>
                <div id="glossary_wrapper">
                    <?php $languages = array_combine(array_column($this->variables->languages, 'code2'), array_column($this->variables->languages, 'title_en')); ?>
                    <?php if (isset($this->variables->glossary) && count($this->variables->glossary) > 0) : ?>
                        <?php foreach( $this->variables->glossary as $glossary ): ?>
                            <?php if (is_array($glossary)) : ?>
                                <div class="glossary position-relative w-100">
                                    <input type="hidden" class="glossary_id" value="<?php echo (isset($glossary['glossary_id']) ? $glossary['glossary_id'] : '') ?>"/>
                                    <button type="submit" name="submit" class="conveythis-delete-page"></button>
                                    <div class="row w-100 mb-2">
                                        <div class="col-md-3">
                                            <div class="ui input">

                                                <input
                                                        type="text"
                                                        class="source_text w-100 conveythis-input-text"
                                                        placeholder="Enter Word"
                                                        value="<?php echo (isset($glossary['source_text']) ? $glossary['source_text']: '') ?>"
                                                >

                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="dropdown fluid">
                                                <i class="dropdown icon"></i>
                                                <select class="dropdown fluid ui form-control rule w-100" required>
                                                    <option value="prevent" <?php echo ($glossary['rule'] == 'prevent') ? 'selected': '' ?> >Don't translate</option>
                                                    <option value="replace" <?php echo ($glossary['rule'] == 'replace') ? 'selected': '' ?> >Translate as</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="ui input">
                                                <input type="text" class="conveythis-input-text translate_text w-100" value="<?php echo (isset($glossary['translate_text']) ? $glossary['translate_text']: '') ?>" <?php echo (isset($glossary['rule']) &&  $glossary['rule'] == 'prevent' ? ' disabled="disabled"' : '');?>>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="dropdown fluid">
                                                <i class="dropdown icon"></i>
                                                <select class="dropdown fluid ui form-control target_language w-100">
                                                    <option value="">All languages</option>

                                                    <?php foreach ($this->variables->languages as $language) :?>
                                                        <?php if (in_array($language['code2'], $this->variables->target_languages)):?>
                                                            <option value="<?php echo  $language['code2']; ?>"<?php echo ($glossary['target_language'] == $language['code2']?' selected':'')?>>
                                                                <?php echo  $languages[$language['code2']]; ?>
                                                            </option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <input type="hidden" name="glossary" value='<?php echo json_encode( $this->variables->glossary ); ?>'>
                <button class="btn-default" type="button" id="add_glossary" style="color: #8A8A8A">Add more rules</button>
       <label class="hide-paid" for="">This feature is not available on Free plan. If you want to use this feature, please <a href="https://app.conveythis.com/dashboard/pricing/?utm_source=widget&utm_medium=wordpress" target="_blank" class="grey">upgrade your plan</a>.</label>
   </div>
</div>