<?php if (!isset($_GET['tlms-course'])) : ?>
    <fieldset>
        <legend><?php esc_html_e('Options', 'talentlms'); ?></legend>
        <label><?php esc_html_e('All categories', 'talentlms');?></label>
        <input type="checkbox" class="ef-category" value="all" checked="true">

        <?php foreach ($categories as $key => $category) : ?>
            <label><?php echo esc_html($category->name);?></label>
            <input type="checkbox" class="ef-category" value="<?php echo esc_attr($category->id); ?>">
        <?php endforeach ?>
    </fieldset>

    <table id="tlms_courses_table">
        <thead>
        <tr>
            <th><?php esc_html_e('Image', 'talentlms'); ?></th>
            <th><?php esc_html_e('Course', 'talentlms'); ?></th>
            <th><?php esc_html_e('Description', 'talentlms'); ?></th>
            <th><?php esc_html_e('Price', 'talentlms'); ?></th>
            <th><?php esc_html_e('Created On', 'talentlms'); ?></th>
            <th><?php esc_html_e('Last Updated On', 'talentlms'); ?></th>
            <th style="display:none;"><?php esc_html_e('categories_ID', 'talentlms'); ?></th>
        </tr>
        </thead>

        <tbody>
        <?php foreach ($courses as $course) : ?>
            <tr>
                <td><img src="<?php echo esc_url($course->big_avatar); ?>"/></td>
                <td><a href="?tlms-course=<?php echo esc_attr((int)$course->id); ?>"><?php echo esc_html($course->name);
                echo (isset($course->course_code)) ? "(".esc_html($course->course_code).")":''; ?></a></td>
                <td><?php echo esc_html($course->description); ?></td>
                <td><?php echo esc_html($course->price); ?></td>
                <td><?php echo date($dateFormat, $course->creation_date); ?></td>
                <td><?php echo date($dateFormat, $course->last_update_on); ?></td>
                <td style="display:none;"><?php echo esc_html((int)$course->category_id); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        jQuery(function(){
            jQuery("#tlms_courses_table").dataTable({
                "order": [[ 0, "asc" ]] ,
                "columns": [
                    { "orderable": false },
                    null,
                    { "orderable": false },
                    null,
                    { "orderable": true },
                    { "orderable": true },
                    { "bSearchable": true }
                ]
            });
        });

        jQuery('.ef-category').click(function () {
            var id = jQuery(this).val();
            var courseTable = jQuery('#tlms_courses_table').DataTable();
            if(id=='all'){
                courseTable.search('').columns().search('').draw();
            }else{
                courseTable.column(6).search(id, true, true).draw();
            }
            jQuery(this).siblings('input:checkbox').not(this).removeAttr('checked');
        });
    </script>

<?php else : ?>
    <?php $course = \TalentlmsIntegration\Utils::tlms_getCourse((int)$_GET['tlms-course']); ?>

    <div class="tlms-course-header">
        <img src="<?php echo esc_url($course['big_avatar']); ?>" alt="<?php echo esc_attr($course['name']); ?>" />
        <h2><?php echo esc_html($course['name']); ?></h2>
    </div>

    <h3><?php esc_html_e('Price', 'talentlms');?>:</h3>
    <p><?php echo (esc_html($course['price'])) ? esc_html($course['price']) : '-'; ?></p>

    <h3><?php esc_html_e('Description', 'talentlms');?>:</h3>
    <p><?php echo esc_html($course['description']); ?></p>
<?php endif; ?>
