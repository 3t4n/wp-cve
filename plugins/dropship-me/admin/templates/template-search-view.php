<?php
/**
 * Author: Vitaly Kukin
 * Date: 13.09.2018
 * Time: 15:09
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tmpl    = new \dm\dmTemplate();
$mystore = isset( $_GET[ 'mystore' ] );

?>
<div class="settings-block-wrap">
<div class="settings-block">
   <div class="d-none d-sm-block dm_FiltersDetach">
   <?php if( ! $mystore ) : ?>
       <?php require( DM_PATH . 'admin/templates/template-form-filters.php' ); ?>
	<?php endif;?>
	</div>
   <div id="dm_collapseSettings">
    <?php require( DM_PATH . 'admin/templates/template-form-import.php' ); ?>
	</div>
</div> 
</div>
<div class="row mb-3 pr-3 pl-3">
    <div class="d-sm-inline-block col-12 col-sm-6 pl-0">
        <h1 class="wp-heading-inline">
		   <?php if( $mystore ) : ?>
				<?php _e( 'Imports History', 'dm' ) ?>
				<a href="https://help.dropship.me/products/imports-history" class="help_import" target="_blank"></a>
			<?php else: ?>
				<?php _e( 'Import Products', 'dm' ) ?>
				<a href="http://help.dropship.me/products/importing-products" class="help_import" target="_blank"></a>
			<?php endif;?>
        </h1>
    </div>
	<div class="col-12 col-sm-6 text-left text-sm-right ml-sm-0 mb-2 ml-0" style="padding: 0px;">
		<span class="dm-text-info">
            <?php _e( '', 'dm' ) ?> <span id="has_deposit">{{deposit}}</span>
            <?php _e( 'Imports left', 'dm' )?>
        </span>
         <a href="https://dropship.me/packages/" target="_blank" class="btn btn-blue get-btn">Get more</a>
	</div>
</div>
<div class="settings_toggle_block">
	<button id="settings_toggle" class="mb-3 btn btn-blue d-none d-sm-block"><i class="gear"></i></button>
</div>

<!--<div class="row">
    <div class="col-12 col-lg-6 d-none d-sm-inline-block">
            <a href="javascript:;" id="settings-btn"
               data-toggle="collapse"
               data-target="#dm_collapseSettings"
               aria-controls="dm_collapseSettings"
               aria-expanded="false" class="btn btn-blue">
                <?php _e( 'Import settings', 'dm' ) ?>
            </a>
    </div>
</div>
 import settings begin-->
<div class="collapse" id="dm_collapseSettings">
    <?php // require( DM_PATH . 'admin/templates/template-form-import.php' ); ?>
</div>
<!-- import settings end-->

	
<?php

    echo $tmpl->hidden( [
        'name' => 'sort',
        'id'   => 'sort'
    ] ) . $tmpl->hidden( [
        'name' => 'page',
        'id'   => 'page'
    ] ) . $tmpl->hidden( [
        'name'  => 'errorCatAdd',
        'id'    => 'errorCatAdd',
        'value' => __( 'Enter Category Name', 'dm' )
    ] ) . $tmpl->hidden( [
        'name'  => 'errorKeyword',
        'id'    => 'errorKeyword',
        'value' => __( 'Fill at least 3 symbols to search', 'dm' )
    ] )
?>

<?php if( ! $mystore ) : ?>

<!-- top nav elements begin-->
<div class="row no-gutters top-elements mobile-select-cat">
	<div class="col-md-5 col-lg-4 col-xl-3 pb-3 pb-md-0">
		<?php

            echo $tmpl->select( [
                'name'  => 'categoryId',
                'label' => false,
                'class' => 'category-id w-100 pr-md-2'
            ] )

		?>
	</div>
	<div class="col-md-4 col-lg-3 pb-3 pb-md-0 sub-cat-col">
		<?php

            echo $tmpl->select( [
                'name'  => 'subCategoryId',
                'label' => false,
                'class' => 'subCategoryId w-100 pr-md-2'
            ] )


		?>
	</div>
	<div class="col">
        <div class="search-input position-relative">
            <?php

                echo $tmpl->text( [
                    'name'        => 'keywords',
                    'label'       => false,
                    'placeholder' => __( 'Search', 'dm' ),
                ] );
                echo $tmpl->hidden( [
                    'name'  => 'supplier',
                    'id'    => 'supplier',
                    'value' => ''
                ] );

            ?>
            <a href="javascript:;" id="search-btn" class="position-absolute d-inline-block">
                <i class="d-block icon-search"></i>
            </a>
        </div>
	</div>
</div>
<!-- top nav elements end-->

<!-- breadcrumbs begin-->
<div class="row py-2 py-sm-3 px-0 no-gutters breadcrumbs-content">
	<div class="col">
            <a href="javascript:;" data-cat="0" data-selector="categoryId" class="color-blue">
                <?php _e( 'All Categories', 'dm' ) ?>
            </a>
			<span class="breadcrumb-list"></span>
			<span class="font-weight-bold" id="items-founded"></span> <?php _e( 'Results', 'dm' ) ?>
	</div>
</div>
<!-- breadcrumbs end-->
<?php else : ?>
    <div class="row pb-3 pt-3 no-gutters breadcrumbs-content breadcrumbs-content-mystore mb-2">
        <div class="col">
	        <span id="items-founded"></span> <?php _e( 'Imports used', 'dm' ) ?>.
            <span id="items-notfounded"></span>
        </div>
    </div>
	
    <?php
    
    echo $tmpl->hidden( [
        'name'  => 'to',
        'id'    => 'to',
        'value' => 'US'
    ] );
    ?>


<?php endif; ?>


<?php if(  $mystore ) : ?>

<div class="row py-3 d-sm-none"> 
	<div class="col-6 text-sm-left pr-0 pl-3">
		<a href="javascript:;"
           data-toggle="collapse"
           data-target="#dm_collapseSettingsM"
           aria-controls="dm_collapseSettingsM"
           aria-expanded="false" class="btn btn-green import-settings-btn">
            <?php _e( 'Import settings', 'dm' ) ?>
        </a>
	</div>
</div>
<!-- import settings begin-->
<div class="collapse d-sm-none" id="dm_collapseSettingsM">
    <?php require( DM_PATH . 'admin/templates/template-form-import-emulating.php' ); ?>
</div>
<!-- import settings end-->
<?php endif; ?>
<?php if( ! $mystore ) : ?>
<!-- import settings link begin-->
<div class="row py-3 d-sm-none"> 
	<div class="col-6 text-sm-left pr-0 pl-3">
		<a href="javascript:;"
           data-toggle="collapse"
           data-target="#dm_collapseSettingsM"
           aria-controls="dm_collapseSettingsM"
           aria-expanded="false" class="btn btn-green import-settings-btn">
            <?php _e( 'Import settings', 'dm' ) ?>
        </a>
	</div>
	<div class="col-6 text-sm-left pr-3 pl-0 d-sm-none">
		<a href="javascript:;"
           data-toggle="collapse"
           data-target="#dm_collapseFilters"
           aria-controls="dm_collapseFilters"
           aria-expanded="false" class="btn import-filters-btn">
            <?php _e( 'FILTERS', 'dm' ) ?>
        </a>
	</div>
</div>
<!-- import settings link end-->

<!-- import settings begin-->
<div class="collapse d-sm-none" id="dm_collapseSettingsM">
    <?php require( DM_PATH . 'admin/templates/template-form-import-emulating.php' ); ?>
</div>
<!-- import settings end-->

    <!-- filters settings begin-->
<div class="collapse d-sm-none" id="dm_collapseFilters">
    <?php require( DM_PATH . 'admin/templates/template-form-filters-emulating.php' ); ?>
</div>
<div class="d-none d-sm-block dm_FiltersDetach">
    <?php // require( DM_PATH . 'admin/templates/template-form-filters.php' ); ?>
</div>
    <!-- filters settings end-->

<?php endif; ?>


<!-- table nav elements begin-->
<div class="row d-none tab-nav-filters pb-0 import-to">
            <div class="ml-0 ml-sm-3 order-first-element  d-none d-sm-block pb-0">
			
                <?php

                echo $tmpl->select( [
                    'name'     => 'categoryImport',
                    'multiple' => true,
                    'label'    => __( 'Import to:', 'dm' ),
                    'label_class'    => 'custom-label'
                ] )

                ?>
                <div class="form-group" style="display:none" id="categoryImportDM">
                    <label><?php _e( 'Categories will be created automatically', 'dm' ) ?></label>
                </div>
            </div>
</div>
<div class="row d-none tab-nav-filters import-sort-block mt-0">
    <div class="col-12 col-md mobile-action-order order-2 order-md-0">
        <div class="tab-nav-elements btn-friendly d-flex d-md-inline-block order-column-element">
            <div class="order-second-element">
			
            <?php
                echo $tmpl->checkbox( [
                    'value' => '1',
                    'id'    => 'checkAll',
                    'class' => 'checkAll'
                //] ) . $tmpl->select( [
                   // 'value'  => 'none',
                   // 'values' => [ 'none' => __( 'Not selected', 'dm' ), 'bulkImport' => __( 'Import selected', 'dm' ) ],
                   // 'id'     => 'actions',
                   // 'label'  => __( 'Bulk action:', 'dm' )
                ] ) . $tmpl->button( [
                    'form_group' => 'form_group',
                    'class' => 'btn btn-green ads-no js-import-selected',
                    'value' => __( 'Import selected<span id="count_ckeck"></span>', 'dm' )
                ] );
				
            ?>
            </div>

        </div>
    </div>
    <div class="col-12 col-md mobile-action-order order-0 order-md-0 d-block d-sm-none">
        <div class="tab-nav-elements btn-friendly d-flex order-column-element">
			<div class="ml-0 ml-sm-2 order-first-element">
                <?php

                echo $tmpl->select( [
                    'name'     => 'categoryImport',
					'id'		=>'categoryImportMobile',
                    'multiple' => true,
                    'label'    => __( 'Import to:', 'dm' )
                ] )

                ?>
                <div class="form-group" style="display:none" id="categoryImportDMMobile">
                    <label><?php _e( 'Categories will be created automatically', 'dm' ) ?></label>
                </div>
			</div>
		</div>
	</div>
    <div class="col-12 col-md text-md-right order-1 order-md-1">
        <div class="tab-nav-elements tab-nav-last tab-nav-additional d-flex justify-content-end order-column-element">
            <div class="order-first-element sortby">
                <?php

                echo $tmpl->select( [
                    'value'  => 'volumeDown',
                    'values' => [
                        'volumeDown'       => __( 'Order count', 'dm' ),
                        'new'              => __( 'Newest', 'dm' ),
                        'orignalPriceUp'   => __( 'Price: High to Low', 'dm' ),
                        'orignalPriceDown' => __( 'Price: Low to High', 'dm' )
                    ],
                    'id'    => 'sortby',
                    'wrap'  => 'align-self-center',
                    'label' => __( 'Sort by:', 'dm' )
                ] )
				
				?>
            </div>
            <div class="order-second-element d-none d-md-block">
                <div class="pagination-menu jqpagination"></div>
            </div>
        </div>
    </div>
</div>
<!-- table nav elements end-->
