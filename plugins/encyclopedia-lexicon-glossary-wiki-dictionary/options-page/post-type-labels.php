<?php

use WordPress\Plugin\Encyclopedia\{
    I18n,
    MockingBird,
    Options,
    PostType,
    PostTypeLabels,
    WPML
};

?>
<table class="form-table">

    <tr>
        <th><label for="encyclopedia_type"><?php I18n::_e('Encyclopedia type') ?></label></th>
        <td>
            <input type="text" name="encyclopedia_type" id="encyclopedia_type" value="<?php echo esc_Attr(Options::get('encyclopedia_type')) ?>">
            <p class="help">
                <?php I18n::_e('This is how your encyclopedia is called in the dashboard. For example: Encyclopedia, Lexicon, Glossary, Knowledge Base, etc.') ?>
                <?php #I18n::_e('You can change this at any time later without worries.') 
                ?>
            </p>
        </td>
    </tr>

    <tr>
        <th><label for="item_singular_name"><?php I18n::_e('Item singular name') ?></label></th>
        <td>
            <input type="text" name="item_singular_name" id="item_singular_name" value="<?php echo esc_Attr(Options::get('item_singular_name')) ?>">
            <p class="help"><?php I18n::_e('The singular name for an encyclopedia item. For example: Entry, Term, Article, etc.') ?></p>
        </td>
    </tr>

    <tr>
        <th><label for="item_plural_name"><?php I18n::_e('Item plural name') ?></label></th>
        <td>
            <input type="text" name="item_plural_name" id="item_plural_name" value="<?php echo esc_Attr(Options::get('item_plural_name')) ?>">
            <p class="help"><?php I18n::_e('The plural name for multiple encyclopedia items. For example: Entries, Terms, Articles, etc.') ?></p>
        </td>
    </tr>


    <?php if (get_Option('permalink_structure')) : ?>
        <tr>
            <th><label><?php I18n::_e('Archive URL slug') ?></label></th>
            <td>
                <div class="input-row">
                    <div><?php echo trailingslashit(Home_Url('/')) ?></div>
                    <div class="input-element"><input type="text" value="<?php echo esc_Attr(PostTypeLabels::getArchiveSlug()) ?>" <?php disabled(true) ?>></div>
                    <?php MockingBird::printProNotice('unlock') ?>
                </div>
                <p class="help"><?php I18n::_e('The url slug of your encyclopedia archive. This slug must not used by another post type or page.') ?></p>
            </td>
        </tr>

        <tr>
            <th><label><?php I18n::_e('Item URL slug') ?></label></th>
            <td>
                <div class="input-row">
                    <div><?php echo trailingslashit(Home_Url('/')) ?></div>
                    <div class="input-element"><input type="text" value="<?php echo esc_Attr(PostTypeLabels::getItemSlug()) ?>" <?php disabled(true) ?>></div>
                    <div><?php echo User_TrailingSlashIt(sprintf(I18n::__('/%%%s-name%%'), sanitize_Title(PostTypeLabels::getItemSingularName())), 'single') ?></div>
                    <?php MockingBird::printProNotice('unlock') ?>
                </div>

                <?php if (WPML::isPostTypeSlugTranslationEnabled()) : ?>
                    <p class="help warning"><?php I18n::_e('This option is not available if you translate the post type url slug with WPML.') ?></p>
                <?php else : ?>
                    <p class="help">
                        <?php I18n::_e('The url slug of your encyclopedia items.') ?>
                        <?php if ($taxonomies = PostType::getAssociatedTaxonomies()) : $taxonomies = Array_Map(function ($taxonomy) {
                                return "%{$taxonomy->name}%";
                            }, $taxonomies) ?>
                            <?php printf(I18n::__('You can use these placeholders: %s'), join(', ', $taxonomies)) ?>
                        <?php endif ?>
                    </p>
                <?php endif ?>
            </td>
        </tr>
    <?php endif ?>

</table>