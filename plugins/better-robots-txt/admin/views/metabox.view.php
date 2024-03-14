<div class="misc-pub-section misc-pub-section-last"><span id="timestamp">

    <!-- <div class="rt-segment" style="margin-top: 10px"> -->

        <p class="post-attributes-label-wrapper"><label class="post-attributes-label" for="alt_text"><?php echo  esc_html__( 'Disallow this post in robots.txt*', 'better-robots-txt' ); ?></label></p>

        <div class="rt-switch-radio dual-btns">

            <input type="radio" id="rt_disallow_btn1" name="rt_disallow" value="rt_disallow_yes" <?php if ( isset( $rt_disallow ) ) echo 'checked="checked"'; ?> />
            <label for="rt_disallow_btn1"><?php echo esc_html__( 'Disallow', 'better-robots-txt' ); ?></label>

            <input type="radio" id="rt_disallow_btn2" name="rt_disallow" value="rt_disallow_no" <?php if ( empty( $rt_disallow ) ) echo 'checked="checked"'; ?> />
            <label for="rt_disallow_btn2"><?php echo esc_html__( 'Global Setting', 'better-robots-txt' ); ?></label>  

        </div>

        <p style="margin-top: 20px;"><?php echo  esc_html__( '*if activated, it will add rules in robots.txt to exclude this post from being visible on SERPs.', 'better-robots-txt' ); ?></p>

    <!-- </div> -->

</div>