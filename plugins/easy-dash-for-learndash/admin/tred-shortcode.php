<div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 pb-24 md:pb-5 tred-main-content tred-easydash-tab"
    id="tred-easydash-tab-shortcode" style="display:none">

    <div class="wrap">
        <button class="button button-primary tred-shortcode-section-buttons" id="tred-global-shortcode-button"
            data-section="tred-global-shortcode-section">
            Global
        </button>
        <button class="button tred-shortcode-section-buttons" id="tred-filtered-shortcode-button"
            data-section="tred-filtered-shortcode-section">
            Filtered
        </button>
        <select class="tred-settings-select" id="tred-settings-shortcode-filtered-select" style="display:none;">
            <option value="all" selected>All Filters</option>
            <option value="course" selected>Course</option>
            <option value="group">Group</option>
            <option value="user">User</option>
        </select>
    </div>

    <!-- GLOBAL SECTION -->
    <div class="flex flex-wrap tred-shortcode-section" id="tred-global-shortcode-section">

        <?php include_once('shortcode-sections/global.php'); ?>

    </div>
    <!-- GLOBAL SECTION END -->

    <!-- FILTERED SECTION -->
    <div class="tred-shortcode-section" id="tred-filtered-shortcode-section" style="display:none">

        <?php include_once('shortcode-sections/all.php'); ?>
        <?php include_once('shortcode-sections/course.php'); ?>
        <?php include_once('shortcode-sections/user.php'); ?>
        <?php include_once('shortcode-sections/group.php'); ?>

    </div>
    <!-- FILTERED SECTION END -->

</div> <!-- END MAIN CONTENT -->