<?php 

$pagination = get_post_meta($post->ID, $this->meta_prefix.'setting_pagination', true);

$pagination = array(
    "pagination"            => $this->s_j_s_checkit('pagination', $pagination, 'true'),
    "el"                    => $this->s_j_s_checkit('el', $pagination, 'swiper-pagination'),
    "type"                  => $this->s_j_s_checkit('type', $pagination, 'bullets'),
    "bulletElement"         => $this->s_j_s_checkit('bulletElement', $pagination, 'span'),
    "clickable"             => $this->s_j_s_checkit('clickable', $pagination, 'false'),
    "bulletClass"           => $this->s_j_s_checkit('bulletClass', $pagination, 'swiper-pagination-bullet'),
    "currentClass"          => $this->s_j_s_checkit('currentClass', $pagination, 'swiper-pagination-current'),
    "bulletActiveClass"     => $this->s_j_s_checkit('bulletActiveClass', $pagination, 'swiper-pagination-bullet-active'),
    "clickableClass"        => $this->s_j_s_checkit('clickableClass', $pagination, 'swiper-pagination-clickable'),
    "lockClass"             => $this->s_j_s_checkit('lockClass', $pagination, 'swiper-pagination-lock'),
);

?>

<div id="sjs-tab-pagination" class="tabSec pagiNation">
    <table>
        <tbody>
            <tr data-filter-item data-filter-name="pagination Pagination">
                <td>
                    <label>Pagination</label>
                </td>
                <td>
                    <ul>
                        <li>
                            <label>
                                <input 
                                    type="radio" 
                                    name="<?php echo $this->meta_prefix.'setting_pagination[pagination]'; ?>" 
                                    value="true" 
                                    <?php checked( $pagination['pagination'], 'true' ); ?>> True
                            </label>
                        </li>
                        <li>
                            <label>
                                <input 
                                    type="radio" 
                                    name="<?php echo $this->meta_prefix.'setting_pagination[pagination]'; ?>" 
                                    value="false" 
                                    <?php checked( $pagination['pagination'], 'false' ); ?>> False
                            </label>
                        </li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="<?php echo ($pagination['pagination'] == 'false' ? 'hiddenData' : '' ) ?>">
        <table class="autoPlaySettings">
            <tr data-filter-item data-filter-name="Element Class element class">
                <td>
                    <label for="el">Element Class</label> 
                </td>
                <td>
                    <span>Class Name for Pagination.</span>
                    <input 
                            id="el"
                            name="<?php echo $this->meta_prefix.'setting_pagination[el]'; ?>"
                            type="text" 
                            value="<?php echo $pagination['el']; ?>">
                </td>
            </tr>
            <tr data-filter-item data-filter-name="type Type">
                <td>
                    <label for="">type</label> 
                </td>
                <td>
                    <span>String with type of pagination.</span>
                    <select name="<?php echo $this->meta_prefix.'setting_pagination[type]'; ?>">
                        <option value="bullets" <?php selected($pagination['type'], 'bullets') ?>>bullets</option>
                        <option value="fraction" <?php selected($pagination['type'], 'fraction') ?>>fraction</option>
                        <option value="progressbar" <?php selected($pagination['type'], 'progressbar') ?>>progressbar</option>
                    </select>
                </td>
            </tr>
            <tr data-filter-item data-filter-name="bulletElement bulletelement bulletelement bullet element Element Bullet">
                <td>
                    <label for="bulletEle">bulletElement</label> 
                </td>
                <td>
                    <span>Defines which HTML tag will be use to represent single pagination bullet. Only for bullets pagination type.</span>
                    <input 
                            id="bulletEle"
                            name="<?php echo $this->meta_prefix.'setting_pagination[bulletElement]'; ?>"
                            type="text" 
                            value="<?php echo $pagination['bulletElement']; ?>">
                </td>
            </tr>
            <tr data-filter-item data-filter-name="clickable ClickAble click able Click Able">
                <td>
                    <label>clickable</label>
                </td>
                <td>
                    <span>If true then clicking on pagination button will cause transition to appropriate slide. Only for bullets pagination type</span>
                    <ul>
                        <li>
                            <label>
                                <input 
                                    name="<?php echo $this->meta_prefix.'setting_pagination[clickable]'; ?>"
                                    type="radio" 
                                    <?php checked( $pagination['clickable'], 'true' ); ?>
                                    value="true">True
                            </label>
                        </li>
                        <li>
                            <label>
                                <input 
                                    name="<?php echo $this->meta_prefix.'setting_pagination[clickable]'; ?>"
                                    type="radio" 
                                    <?php checked( $pagination['clickable'], 'false' ); ?>
                                    value="false">False
                            </label>
                        </li>
                    </ul>
                </td>
            </tr>
            <tr data-filter-item data-filter-name="bulletClass bullet class bulletclass BulletClass Bullet Class">
                <td>
                    <label for="bulletClass">bulletClass</label> 
                </td>
                <td>
                    <span>CSS class name of single pagination bullet</span>
                    <input 
                            id="bulletClass"
                            name="<?php echo $this->meta_prefix.'setting_pagination[bulletClass]'; ?>"
                            type="text" 
                            value="<?php echo $pagination['bulletClass']; ?>">
                </td>
            </tr>
            <tr data-filter-item data-filter-name="currentClass current class currentclass CurrentClass Current Class">
                <td>
                    <label for="currentClass">currentClass</label> 
                </td>
                <td>
                    <span>CSS class name of the element with currently active index in "fraction" pagination</span>
                    <input 
                            id="currentClass"
                            name="<?php echo $this->meta_prefix.'setting_pagination[currentClass]'; ?>"
                            type="text" 
                            value="<?php echo $pagination['currentClass']; ?>">
                </td>
            </tr>
            <tr data-filter-item data-filter-name="bulletActiveClass bullet active class bulletactiveclass Class Active Bullet">
                <td>
                    <label for="bulletActiveClass">bulletActiveClass</label> 
                </td>
                <td>
                    <span>CSS class name of currently active pagination bullet</span>
                    <input 
                            id="bulletActiveClass"
                            name="<?php echo $this->meta_prefix.'setting_pagination[bulletActiveClass]'; ?>"
                            type="text" 
                            value="<?php echo $pagination['bulletActiveClass']; ?>">
                </td>
            </tr>
            <tr data-filter-item data-filter-name="clickableClass clickableclass click class Click Class able Able">
                <td>
                    <label for="clickableClass">clickableClass</label> 
                </td>
                <td>
                    <span>CSS class name set to pagination when it is clickable</span>
                    <input 
                            id="clickableClass"
                            name="<?php echo $this->meta_prefix.'setting_pagination[clickableClass]'; ?>"
                            type="text" 
                            value="<?php echo $pagination['clickableClass']; ?>">
                </td>
            </tr>
            <tr data-filter-item data-filter-name="lockClass lockclass LockClass lock class Lock Class">
                <td>
                    <label for="lockClass">lockClass</label> 
                </td>
                <td>
                    <span>CSS class name set to pagination when it is disabled</span>
                    <input 
                            id="lockClass"
                            name="<?php echo $this->meta_prefix.'setting_pagination[lockClass]'; ?>"
                            type="text" 
                            value="<?php echo $pagination['lockClass']; ?>">
                </td>
            </tr>
        </table>
    </div>
</div>