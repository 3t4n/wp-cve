<div id="sjs-tab-carousel" class="tabSec carousel">
    <table class="carouselSettings">
        <tr data-filter-item data-filter-name="slidesPerView SlidesPerView slidesperview Slides slides per Per view View">
            <td>
                <label>slidesPerView</label>
            </td>
            <td>
                <span>Number of slides per view.</span>
                <input 
                    id="slidesPerView"
                    name="<?php echo $this->meta_prefix.'setting_general[slidesPerView]'; ?>"
                    type="number" 
                    min="1"
                    max="99"
                    value="<?php echo $general['slidesPerView']; ?>">
            </td>
        </tr>
        <tr data-filter-item data-filter-name="slidesPerColumn SlidesPerColumn slidespercolumn slides Slides per Per column Column">
            <td>
                <label>slidesPerColumn</label>
            </td>
            <td>
                <span>Number of slides per column, for multirow layout.</span>
                <input 
                    id="slidesPerColumn"
                    name="<?php echo $this->meta_prefix.'setting_general[slidesPerColumn]'; ?>"
                    type="number" 
                    min="1"
                    value="<?php echo $general['slidesPerColumn']; ?>">
            </td>
        </tr>
    </table>
</div>