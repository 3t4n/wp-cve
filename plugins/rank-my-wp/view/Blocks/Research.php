<div class="card col-sm-12 mb-4 py-4 border" style="background: #f8fbff">
    <div class="card-body">
        <div class="col-sm-12 p-0 m-0"><?php echo apply_filters('rkmw_form_notices', false); ?></div>

        <div class="col-sm-12 p-0 text-center">
            <form class="justify-content-center" method="get">
                <input type="hidden" name="page" value="rkmw_research">
                <input type="hidden" name="tab" value="research">
                <div class="rkmw_step rkmw_step1 my-2">
                    <h4 class="text-success text-center my-4"><?php echo sprintf(esc_html__("%s Start a Keyword Research %s and rank your website on Google:", RKMW_PLUGIN_NAME), '<strong>', '</strong>') ?></h4>

                    <div class="col-sm-8 offset-sm-2">

                        <input type="text" name="keyword" class="form-control mb-2">
                        <h6 class="my-2 text-black-50">
                            <?php echo esc_html__("Enter a starting 2-3 words keyword that matches your business.", RKMW_PLUGIN_NAME) ?>
                        </h6>
                        <h4 class="rkmw_research_error text-warning text-center" style="display: none"><?php echo esc_html__("You need to enter a keyword first", RKMW_PLUGIN_NAME) ?></h4>
                    </div>
                    <div class="col-sm-12 mt-3 text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5"><?php echo esc_html__("Do Research", RKMW_PLUGIN_NAME) ?> >></button>
                    </div>
                </div>
            </form>
        </div>

    </div>

</div>