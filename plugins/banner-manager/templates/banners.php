<?php $this->extend('layout') ?>

<?php $this->start('title') ?>
<h2>
    <?php _e('Banners', 'banner-manager')?>
    <a class="button add-new-h2" href="?page=bm-index"><?php _e('Add New', 'banner-manager')?></a>
</h2>
<?php $this->stop() ?>

<div id="col-container">

<div id="col-right">
<div class="col-wrap">

<div class="tablenav">

<div class="alignleft actions">

</div>

</div>
<form action="" method="post">
<div class="clear"></div>
<div class="tablenav">

<div class="alignleft actions">

<?php if(isset($categories)): ?>
<select name="filter_category">
<option value=""><?php _e('View all categories', 'banner-manager')?></option>
<?php foreach($categories as $category): ?>
<option value="<?php echo $category->id;?>"<?php echo ($category->id==$filter_category)? ' selected=""':'';?>><?php echo $category->groups; ?></option>
<?php endforeach; ?>
</select>
<?php endif; ?>

<select class="postform" id="cat" name="filter_active">
    <option value=""><?php _e('View all actives', 'banner-manager')?></option>
    <option value="1"<?php echo ($filter_active=='1')? ' selected=""':'';?>><?php _e('Yes', 'banner-manager')?></option>
    <option value="0"<?php echo ($filter_active=='0')? ' selected=""':'';?>><?php _e('No', 'banner-manager')?></option>
</select>
<input type="submit" class="button-secondary" value="<?php _e('Filter', 'banner-manager');?>" id="post-query-submit">
</div>
</form>

<div class="clear"></div>
</div>

<table cellspacing="0" class="widefat tag fixed">
    <thead>
    <tr>
    <th style="" class="manage-column column-name" id="name" scope="col"><?php _e('Banner', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Category', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Group', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Views', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Clicks', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Active', 'banner-manager')?></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Banner', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Category', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Group', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Views', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Clicks', 'banner-manager')?></th>
    <th style="" class="manage-column column-name" scope="col"><?php _e('Active', 'banner-manager')?></th>
    </tr>
    </tfoot>

    <tbody class="list:tag" id="the-list">
    <?php if(isset($banners)): ?>
    <?php foreach($banners as $i_banner): ?>
    <tr class="alternate" id="tag-1">
        <td class="name column-name">
        <a href="?page=bm-index&amp;status=edit&amp;id=<?php echo $i_banner->id?>" class="row-title"><?php echo empty($i_banner->title)? '['.__('Empty', 'banner-manager').']' : $i_banner->title;?></a>
        <div class="row-actions">
            <span class="edit"><a title="<?php _e('Edit this item', 'banner-manager')?>" href="?page=bm-index&amp;status=edit&amp;id=<?php echo $i_banner->id?>&amp;<?php echo $url_query; ?>"><?php _e('Edit', 'banner-manager')?></a> | </span>
            <span class="trash"><a href="?page=bm-index&amp;status=delete&amp;id=<?php echo $i_banner->id?>&amp;<?php echo $url_query; ?>" class="delete" title="<?php _e('Move this item to the Trash', 'banner-manager')?>" class="submitdelete"><?php _e('Trash', 'banner-manager')?></a> | </span>
            <span class="view"><a rel="permalink" title="<?php _e('View', 'banner-manager')?>" target="_blank" href="<?php echo $i_banner->src;?>&amp;<?php echo $url_query; ?>"><?php _e('View', 'banner-manager')?></a></span>
        </div>
        </td>
        <td class="column-name"><?php echo $i_banner->groups;?></td>
        <td class="column-name"><?php echo $i_banner->groupkey;?></td>
        <td class="column-name"><?php echo $i_banner->views;?></td>
        <td class="column-name"><?php echo $i_banner->clicks;?></td>
        <td class="column-name"><?php echo ($i_banner->active)? __('Yes', 'banner-manager') : __('No', 'banner-manager') ;?></td>
    </tr>
    <?php endforeach;?>
    <?php else: ?>
    <tr class="alternate" id="tag-1">
        <td class="name column-name"><a href="#" class="row-title"><?php _e('Empty', 'banner-manager');?></a></td>
        <td class="name column-name"><a href="#" class="row-title"><?php _e('Empty', 'banner-manager');?></a></td>
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
<?php if(!isset($banner->id)): ?>
<h3><?php _e('Add New Banner', 'banner-manager')?></h3>
<?php else: ?>
<h3><?php _e('Edit Banner', 'banner-manager')?></h3>
<?php endif; ?>
<form class="validate" action="?page=bm-index&status=new" method="post" enctype="multipart/form-data">
<div class="form-field form-required">
    <label for="tag-name"><?php _e('Category', 'banner-manager')?></label>
    <?php if(isset($categories)): ?>
    <select name="category">
        <?php foreach($categories as $category): ?>
        <option value="<?php echo $category->id;?>"<?php $banner_cat = isset($banner->id_category)? $banner->id_category : 0; echo ($category->id==$banner_cat)? ' selected=""':'';?>><?php echo $category->groups; ?></option>
        <?php endforeach; ?>
    </select>
    <?php endif; ?>

    <label for="tag-name"><?php _e('Title', 'banner-manager')?></label>
    <input type="text" size="40" value="<?php echo isset($banner->title)? $banner->title : '';?>" name="title">

    <label for="tag-name"><?php _e('Src', 'banner-manager')?></label>
    <input type="file" size="40" value="" name="src">

    <?php if(isset($banner->src)): ?>
    <input type="hidden" size="40" value="<?php echo $banner->src;?>" name="src_old">
    <a href="<?php echo $banner->src;?>" target="_blank"><?php echo $banner->src;?></a>
    <?php endif; ?>

    <label for="tag-name"><?php _e('Link', 'banner-manager')?></label>
    <input type="text" size="40" value="<?php echo isset($banner->link)? $banner->link : '';?>" name="link">

    <label for="tag-name"><?php _e('Blank', 'banner-manager')?></label>
    <input type="checkbox" size="40" value="1" name="blank" <?php echo empty($banner->blank)? '' : 'checked="checked"';?>>

    <label for="tag-name"><?php _e('Active', 'banner-manager')?></label>
    <input type="checkbox" size="40" value="1" name="active" <?php echo empty($banner->active)? '' : 'checked="checked"';?>>

    <label for="tag-name"><?php _e('Group', 'banner-manager')?> (<?php _e('Only one banner of group in same page', 'banner-manager') ?>)</label>
    <input type="text" size="40" value="<?php echo isset($banner->groupkey)? $banner->groupkey : '';?>" name="groupkey">

    <input type="hidden" size="40" value="<?php echo isset($banner->id)? $banner->id : '';?>" name="id">
    <input type="hidden" size="40" value="<?php echo isset($filter_active)? $filter_active : '';?>" name="filter_active">
    <input type="hidden" size="40" value="<?php echo isset($filter_category)? $filter_category : '';?>" name="filter_category">
</div>

<p class="submit">
    <?php if(!isset($banner->id)): ?>
    <input type="submit" value="<?php _e('Add New Banner', 'banner-manager')?>" name="submit" class="button">
    <?php else: ?>
    <input type="submit" value="<?php _e('Edit Banner', 'banner-manager')?>" name="submit" class="button">
    <?php endif; ?>
</p>

</form>
</div>

</div>
</div>
</div><!-- /col-left -->
