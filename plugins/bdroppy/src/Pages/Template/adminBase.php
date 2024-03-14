<ul class="menu">
    <?php

    $active_tab = isset( $_GET[ 'tab' ] ) ? esc_attr($_GET[ 'tab' ]) : 'dashboard';

    use BDroppy\Services\System\SystemLanguage; ?>
    <li class="menu_item menu_logo">
        <div class="logo"></div>
    </li>
    <li class="menu_item" >
        <a href="?page=bdroppy-setting&tab=dashboard" class="menu_item_link <?php echo $active_tab == 'dashboard' ? 'menu_item_link_active' : ''; ?>">Dashboard</a>
    </li>
    <li class="menu_item"  >
        <a href="?page=bdroppy-setting&tab=catalog" class="menu_item_link <?php echo $active_tab == 'catalog' ? 'menu_item_link_active' : ''; ?>" >Catalogs</a>
    </li>
    <li class="menu_item <?= SystemLanguage::hasWpmlSupport() ? 'd-none':'' ?>"  >
        <a href="?page=bdroppy-setting&tab=category-mapping" class="menu_item_link <?php echo $active_tab == 'category-mapping' ? 'menu_item_link_active' : ''; ?>" >Category Mapping</a>
    </li>
    <li class="menu_item"  >
        <a href="?page=bdroppy-setting&tab=order" class="menu_item_link <?php echo $active_tab == 'order' ? 'menu_item_link_active' : ''; ?>"  >Orders</a>
    </li>

    <li class="menu_item"  >
        <a href="?page=bdroppy-setting&tab=setting" class="menu_item_link <?php echo $active_tab == 'setting' ? 'menu_item_link_active' : ''; ?>"  >Dev Setting</a>
    </li>

    <li class="menu_item menu_item_last"  >
        <span class="version"><a class="logout-btn" href="javascript:;">Log Out</a> </span>
    </li>
</ul>
