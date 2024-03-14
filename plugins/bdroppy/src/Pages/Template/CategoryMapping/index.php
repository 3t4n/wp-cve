<?php

$bdroppyCategories = $this->remote->main->categories($this->system->language->getActives(0));

$cat_args = array(
    'hide_empty' => false,
    'parent'     => 0,
);

$siteCategories = get_terms( 'product_cat', $cat_args );

?>

<div class="bdroppy_base" >
    <div class="container">
        <?php require __DIR__ . "/../adminBase.php" ?>
        <div class="bd-content">

            <div class='api-setting-info'>
                <div class="bdroppy_card">
                    <table class="wp-list-table widefat fixed striped posts">
                        <thead>
                        <tr>
                            <th>Category in Bdroppy</th>
                            <th>Category in Site</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody id="categoryMappingList">

                        </tbody>
                    </table>
                </div>
                <div class="bdroppy_card">
                    <div class="card-header">
                        <h3>Add New Category Mapping</h3>
                    </div>
                    <td>select category type </td>
                    <td>
                        <select id="category_type">
                            <option value="1" selected>Gender > Category > Subcategory</option>
                            <option value="2">Category > Subcategory</option>
                        </select>
                    </td><br><br>
                    <table class="wp-list-table widefat fixed striped">
                        <thead>
                        <tr>
                            <td><b>Category name</b></td>
                            <td><b>Bdroppy Category</b></td>
                            <td><b>Your Site Category</b></td>
                        </tr>
                        </thead>
                        <tr id="category_gender_row">
                            <td>
                                <lable>Gender</lable>
                            </td>
                            <td>
                                <select id="category_select_bdGender">
                                    <option selected disabled>- - - Select - - -</option>
                                    <option value="women">Woman</option>
                                    <option value="men">Men</option>
                                    <option value="kids">Kids</option>
                                    <option value="unisex">Unisex</option>
                                </select>
                            </td>
                            <td>
                                <select id="category_select_Gender" data-result-id="category_select_Category" >
                                    <option selected disabled>- - - Select - - -</option>
                                    <?php foreach ($siteCategories as $category):?>
                                        <option value="<?= $category->term_id ?>" ><?= $category->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><lable>Category</lable></td>
                            <td>
                                <select id="category_select_bdCategory" >
                                    <option selected disabled>- - - Select - - -</option>
                                    <?php foreach ($bdroppyCategories as $key =>$category):?>
                                        <option value="<?= $key ?>" ><?= $category ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select id="category_select_Category" data-result-id="category_select_SubCategory">
                                    <option selected disabled>- - - Select - - -</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><lable>Sub Category</lable></td>
                            <td>
                                <select id="category_select_bdSubCategory">
                                    <option selected disabled>- - - Select - - -</option>
                                </select>
                            </td>
                            <td>
                                <select id="category_select_SubCategory">
                                    <option selected disabled>- - - Select - - -</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <input type="button" class="button" id="add_category_mapping" value="add">
                </div>
            </div>

        </div>
    </div>
</div>