<?php if(isset($catalog->name)) :?>
    <h2 class="current_catalog_title">Your Selected Catalog</h2>
<div class="current_catalog_box">
    <div class="current_catalog_details">
        <span class="name"><?= $catalog->name ?> </span>
        <span class="count_product"><?= $catalog->count ?> products</span>
    </div>
    <div class="current_catalog_actions">
        <button type="button" class="btn btn-primary btn-change-catalog"  data-loading-text="sss" >Change Catalog</button>
    </div>
</div>

<?php else: ?>

    <div class="no_current_catalog_box">
        <div class="no_current_catalog_details">
            You Don't have Active Catalog :(
        </div>
        <div class="no_current_catalog_actions">
            <button type="button" class="btn btn-primary btn-change-catalog"  data-loading-text="sss" >Create Catalog</button>
        </div>
    </div>

<?php endif; ?>
