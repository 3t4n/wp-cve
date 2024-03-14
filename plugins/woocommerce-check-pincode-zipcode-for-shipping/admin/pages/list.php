<div class="wrap">
    <div>
        <h1 class="wp-heading-inline"><?php esc_html_e('Pincode List Table','pho-pincode-zipcode-cod'); ?></h1>
        <a href="?page=phoeniixx-add-pincode" class="page-title-action">ADD</a>
        <hr class="wp-header-end">
    </div>
    <div>
    <?php 
        $pincodeTable = new Phoeniixx_Pincode_Zipcode_List_Table();
        $pincodeTable->prepare_items(); ?>
        <form method="post">
            <input type="hidden" name="page" value="<?= $_REQUEST['page'] ?>" />
            <?php 
                $pincodeTable->search_box( 'search', 'pincode' );
                $pincodeTable->display(); 
            ?>
        </form>
    </div>
</div>