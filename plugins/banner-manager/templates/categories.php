<?php $this->extend('layout') ?>

<?php $this->start('title') ?>
<h2><?php _e('Categories', 'banner-manager')?></h2>
<?php $this->stop();?>

<div id="col-container">

<div id="col-right">
<div class="col-wrap">

<div class="tablenav">

<div class="alignleft actions">

</div>

</div>

<div class="clear"></div>
<table cellspacing="0" class="widefat tag fixed">
    <thead>
    <tr>
    <th style="" class="manage-column column-name" id="name" scope="col"><?php _e('Category', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Template tag', 'banner-manager')?></th>

    </tr>
    </thead>

    <tfoot>
    <tr>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Category', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Template tag', 'banner-manager')?></th>
    </tr>
    </tfoot>

    <tbody class="list:tag" id="the-list">
    <?php if(isset($categories)): ?>
    <?php foreach($categories as $i_category): ?>
    <tr class="alternate" id="tag-1">
        <td class="name column-name">
        <strong><a href="?page=bm-categories&amp;status=edit&amp;id=<?php echo $i_category->id; ?>" class="row-title"><?php echo $i_category->groups?></a></strong>
        <div class="row-actions">
            <span class="edit"><a title="<?php _e('Edit this item', 'banner-manager')?>" href="?page=bm-categories&amp;status=edit&amp;id=<?php echo $i_category->id; ?>"><?php _e('Edit', 'banner-manager')?></a> | </span>
            <span class="trash"><a class="delete" href="?page=bm-categories&amp;status=delete&amp;id=<?php echo $i_category->id; ?>" title="<?php _e('Move this item to the Trash', 'banner-manager')?>" class="submitdelete"><?php _e('Trash', 'banner-manager')?></a></span>
        </div>
        </td>
        <td class="name column-name"><?php echo htmlentities(sprintf('<?php wp_banner_manager(%d);?>',$i_category->id)) ?></td>
    </tr>
    <?php endforeach;?>
    <?php else: ?>
    <tr class="alternate" id="tag-1">
        <td class="name column-name">
        <strong><a href="#" class="row-title"><?php _e('Without category', 'banner-manager');?></a></strong>
        </td>
    </tr>
    <?php endif; ?>
    </tbody>
</table>


<br class="clear">


</div>
</div><!-- /col-right -->

<div id="col-left">
<div class="col-wrap">


<div class="form-wrap">
<?php if(!isset($category->id)): ?>
<h3><?php _e('Add New Category', 'banner-manager')?></h3>
<?php else: ?>
<h3><?php _e('Edit Category', 'banner-manager')?></h3>
<?php endif; ?>
<form class="validate" action="?page=bm-categories&status=new" method="post">
<div class="form-field form-required">

    <label for="tag-name"><?php _e('Name', 'banner-manager')?></label>
    <input type="text" size="40" value="<?php echo isset($category->groups)? $category->groups : '';?>" name="category">

    <label for="tag-name"><?php _e('Width', 'banner-manager')?> (px)</label>
    <input type="text" size="5" value="<?php echo isset($category->width)? $category->width : '';?>" name="width">

    <label for="tag-name"><?php _e('Height', 'banner-manager')?> (px)</label>
    <input type="text" size="5" value="<?php echo isset($category->height)? $category->height : '';?>" name="height">

    <input type="hidden" size="40" value="<?php echo isset($category->id)? $category->id : '';?>" name="id">

</div>


<p class="submit">
    <?php if(!isset($category->id)): ?>
    <input type="submit" value="<?php _e('Add New Category', 'banner-manager')?>" name="submit" class="button">
    <?php else: ?>
    <input type="submit" value="<?php _e('Edit Category', 'banner-manager')?>" name="submit" class="button">
    <?php endif; ?>
</p>

</form>
</div>

</div>
</div>
</div><!-- /col-left -->
