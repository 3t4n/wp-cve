<div class="steps">
    <div class="step">
        <div class="number">1</div>
        <div class="title">Base</div>
    </div>
    <div class="step">
        <div class="number">2</div>
        <div class="title">Attributes</div>
    </div>
    <div class="step">
        <div class="number">3</div>
        <div class="title">Product Name</div>
    </div>
    <div class="step">
        <div class="number">4</div>
        <div class="title">Select Catalog</div>
    </div>
</div>
<form id="importCatalogForm" class="tabs">
    <div class="tab" id="base">
        <h2 class="title">Base Information</h2>
        <?php if (!$this->system->language->hasWpmlSupport()): ?>
            <div class="form-group row">
                <label for="form-size-select" class="col-sm-4 col-form-label col-form-label-lg ">Language</label>
                <div class="col-sm-8">
                    <select name="import-language" class="form-control form-control-lg" id="form-size-select">
                        <?php foreach ($this->system->language->getLanguages() as $key => $value) { ?>
                            <option value="<?= $key ?>" <?= $this->config->catalog->get('import-language',substr(get_locale(),0,2)) == $key ? 'selected' : ''?> ><?= $value['name'] ?> </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        <?php endif; ?>
        <div class="form-group row">
            <label for="form-category-structure-select" class="col-sm-4 col-form-label col-form-label-lg ">Category Structure</label>
            <div class="col-sm-8">
                <select name="category-structure" class="form-control form-control-lg" id="form-category-structure-select">
                    <?php foreach ($this->wc->resource->getCategoryStructure() as $category) { ?>
                        <option value="<?= $category['id'] ?>" <?= $this->config->catalog->get('category-structure') == $category['id'] ? 'selected' : ''?> ><?= $category['name'] ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-import-images-select" class="col-sm-4 col-form-label col-form-label-lg ">Import Image</label>
            <div class="col-sm-8">
                <select name="import-images" class="form-control form-control-lg" id="form-import-images-select">
                    <?php foreach ($this->wc->resource->getImages() as $image) { ?>
                        <option value="<?= $image['id'] ?>" <?= $this->config->catalog->get('import-images') == $image['id'] ? 'selected' : ''?>><?= $image['name'] ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-import-product-sku-select" class="col-sm-4 col-form-label col-form-label-lg ">Product SKU</label>
            <div class="col-sm-8">
                <select name="import-product-sku" class="form-control form-control-lg" id="form-import-product-sku-select">
                    <?php foreach ($this->wc->resource->GetProductSkuTypes() as $skuType) { ?>
                        <option value="<?= $skuType['id'] ?>" <?= $this->config->catalog->get('import-product-sku') == $skuType['id'] ? 'selected' : ''?>><?= $skuType['name'] ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-brand-select" class="col-sm-4 col-form-label col-form-label-lg ">Local Image</label>
            <div class="col-sm-8">
                <label class="checkbox-name <?= $this->config->catalog->get('add-image-url-tools') ? "checked":'' ?>" for="check-image-url-name" >
                    <input type="checkbox"  <?= $this->config->catalog->get('add-image-url-tools') ? "checked":'' ?> name="add-image-url-tools" id="check-image-url-name">
                    download pictures (not recommended)
                </label>
            </div>
        </div>

        <div class="form-group row">
            <label class="col-sm-4 col-form-label col-form-label-lg ">Server Performance</label>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col">
                        <label class="checkbox-name" for="check-import-per-minute-low" >
                            <input type="radio" <?= $this->config->catalog->get('import-per-minute',0) <= 4 ? "checked" :'' ?> name="import-per-minute" value="4" id="check-import-per-minute-low">
                            Low
                        </label>
                    </div>
                    <div class="col">
                        <label class="checkbox-name" for="check-import-per-minute-medium" >
                            <input type="radio" <?=( $this->config->catalog->get('import-per-minute',0) > 4 &&  $this->config->catalog->get('import-per-minute',0) <= 12) ? "checked" :'' ?> name="import-per-minute" value="12" id="check-import-per-minute-medium">
                            Medium
                        </label>
                    </div>
                    <div class="col">
                        <label class="checkbox-name" for="check-import-per-minute-high" >
                            <input type="radio" <?= $this->config->catalog->get('import-per-minute',0) > 12 ? "checked" :'' ?> name="import-per-minute" value="20" id="check-import-per-minute-high">
                            High
                        </label>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="tab" id="attributes">
        <h2 class="title">Attribute</h2>
        <div class="form-group row">
            <label for="form-size-select" class="col-sm-2 col-form-label col-form-label-lg ">Size</label>
            <div class="col-sm-10">
                <select name="size-attr" class="form-control form-control-lg" id="form-size-select">
                    <?php foreach ($this->wc->resource->getAttributes() as $attribute) { ?>
                        <option value="<?= $attribute['id'] ?>" <?= $this->config->catalog->get('size-attr') == $attribute['id'] ? 'selected' : ''?> ><?= $attribute['name'] ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-category-structure-select" class="col-sm-2 col-form-label col-form-label-lg ">Gender</label>
            <div class="col-sm-10">
                <select name="gender-attr" class="form-control form-control-lg" id="form-gender-select">
                    <?php foreach ($this->wc->resource->getAttributes() as $attribute) { ?>
                        <option value="<?= $attribute['id'] ?>" <?= $this->config->catalog->get('gender-attr') == $attribute['id'] ? 'selected' : ''?> ><?= $attribute['name'] ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-color-select" class="col-sm-2 col-form-label col-form-label-lg ">Color</label>
            <div class="col-sm-10">
                <select name="color-attr" class="form-control form-control-lg" id="form-color-select">
                    <?php foreach ($this->wc->resource->getAttributes() as $attribute) { ?>
                        <option value="<?= $attribute['id'] ?>" <?= $this->config->catalog->get('color-attr') == $attribute['id'] ? 'selected' : ''?>><?= $attribute['name'] ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-season-select" class="col-sm-2 col-form-label col-form-label-lg ">Season</label>
            <div class="col-sm-10">
                <select name="season-attr" class="form-control form-control-lg" id="form-season-select">
                    <?php foreach ($this->wc->resource->getAttributes() as $attribute) { ?>
                        <option value="<?= $attribute['id'] ?>" <?= $this->config->catalog->get('season-attr') == $attribute['id'] ? 'selected' : ''?>><?= $attribute['name'] ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="form-brand-select" class="col-sm-2 col-form-label col-form-label-lg ">Brand</label>
            <div class="col-sm-10">
                <select name="brand-attr" class="form-control form-control-lg" id="form-brand-select">
                    <?php foreach ($this->wc->resource->getAttributes() as $attribute) { ?>
                        <option value="<?= $attribute['id'] ?>" <?= $this->config->catalog->get('brand-attr') == $attribute['id'] ? 'selected' : ''?>><?= $attribute['name'] ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>

    </div>

    <div class="tab" id="product-name" >
        <h2 class="title">Product Name</h2>

        <div class="product-name-preview">
            <div class="title-preview">Preview</div>
            <div class="preview-box">
                <span class="brand-view" <?= $this->config->catalog->get('import-brand-to-title') == false ? "style='display:none'":'' ?> >Brand - </span>
                <span class="product-name-view">Product Name</span>
                <span class="color-view" <?= $this->config->catalog->get('import-color-to-title') == false ? "style='display:none'":'' ?>> - Color</span>
            </div>
        </div>

        <div class="form-group row">
            <div class="col">
                <label class="checkbox-name <?= $this->config->catalog->get('import-brand-to-title') == true ? "checked":'' ?>" for="check-import-brand-to-title" data-target="brand" >
                    <input type="checkbox" name="import-brand-to-title"  <?= $this->config->catalog->get('import-brand-to-title') == true ? "checked":'' ?> id="check-import-brand-to-title">
                    Add Brand To Product Name
                </label>
            </div>
            <div class="col">
                <label class="checkbox-name <?= $this->config->catalog->get('import-color-to-title') == true ? "checked":'' ?>" for="check-import-color-to-title" data-target="color">
                    <input type="checkbox" name="import-color-to-title" <?= $this->config->catalog->get('import-color-to-title') == true ? "checked":'' ?> id="check-import-color-to-title">
                    Add Color To Product Name
                </label>
            </div>
        </div>
    </div>

    <div class="tab" id="catalog">
        <h2 class="title">Catalog</h2>

        <div class="form-group row">
            <label for="form-size-select" class="col-sm-4 col-form-label col-form-label-lg ">Catalog</label>
            <div class="col-sm-8">
                <select name="catalog" class="form-control form-control-lg" id="form-size-select">
                    <?php foreach ($this->remote->catalog->getCatalogs() as $catalog) { ?>
                        <option value="<?= $catalog['id'] ?>" <?= $this->config->catalog->get('catalog') == $catalog['id'] ? "selected":'' ?>  ><?= $catalog['name'] ?> </option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group row">
            <label for="check-update-prices" class="col-sm-4 col-form-label col-form-label-lg ">Auto Update Price</label>
            <div class="col-sm-8">
                <label class="checkbox-name <?= $this->config->catalog->get('update-prices')? "checked":'' ?>" for="check-update-prices">
                    <input type="checkbox" <?= $this->config->catalog->get('update-prices')? "checked":'' ?> name="update-prices" id="check-update-prices">
                    Auto Update Price
                </label>
            </div>
        </div>

        <div class="form-group row">
            <label for="check-publish-product" class="col-sm-4 col-form-label col-form-label-lg ">Publish Products</label>
            <div class="col-sm-8">
                <label class="checkbox-name <?= $this->config->catalog->get('publish-product')? "checked":'' ?>" for="check-publish-product">
                    <input type="checkbox" <?= $this->config->catalog->get('publish-product')? "checked":'' ?> name="publish-product" id="check-publish-product">
                    Publish products on import
                </label>
            </div>
        </div>

        <div class="form-group row">
            <label for="check-import-retail" class="col-sm-4 col-form-label col-form-label-lg ">Retail Price</label>
            <div class="col-sm-8">
                <label class="checkbox-name <?= $this->config->catalog->get('import-retail')? "checked":'' ?>" for="check-import-retail">
                    <input type="checkbox" <?= $this->config->catalog->get('import-retail')? "checked":'' ?> name="import-retail" id="check-import-retail">
                    Retail Price And Sell Price
                </label>
            </div>
        </div>

        <div class="form-group row">
            <label for="check-delete-products" class="col-sm-4 col-form-label col-form-label-lg "></label>
            <div class="col-sm-8" style="font-size: 12px">
                <label class="<?= $this->config->catalog->get('delete-products')? "checked":'' ?>" for="check-delete-products">
                    <input type="checkbox" <?= $this->config->catalog->get('delete-products')? "checked":'' ?> name="delete-products" id="check-delete-products">
                    Don't Delete Products ( <b> Not Recommended! </b> )
                </label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <div class="form-item">
                <button type="button" class="btn btn-primary btn-lg btn-block prevTab">« Back</button>
            </div>
        </div>
        <div class="col">
            <div class="form-item">
                <button type="button" class="btn btn-primary btn-lg btn-block nextTab">Next »</button>
            </div>
        </div>
    </div>
</form>
