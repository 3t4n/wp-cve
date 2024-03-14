<div class="wrap checklist">
<div id="save-notice" style="display:none;" class="updated settings-error notice is-dismissible"> 
<p><strong>Click Save button (at bottom) to save your Changes</strong></p>
</div>
    <form action="options.php" method="post">
       <?php
       settings_fields( 'dvin508-checklist' );
       do_settings_sections( 'dvin508-checklist' );
       ?>
       <h2>1. Perceivable</h2>
       <p>Information and user interface components have to be presentable to users in ways that they can perceive. (i.e. a blind person hears the content and a deaf person must see the content)</p>

       <h3>Guideline 1.1: Text Alternatives</h3>
      
       <?php foreach($settings1 as $key => $val){
        ?>           
        <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
       <?php
        }
       ?>

       <h3>Guideline 1.2 Alternatives For Multimedia</h3>
      
      <?php foreach($settings2 as $key => $val){
       ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php
       }
      ?>

      <h3>Guideline 1.3 Adaptable Content </h3>
      
      <?php foreach($settings3 as $key => $val){
       ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php
       }
      ?>

      <h3>Guideline 1.4 Distinguishable Content</h3>
      
      <?php foreach($settings4 as $key => $val){
       ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php
       }
      ?>

       <h2>2. Operable</h2>
       <p>User interface components and navigation must be operable by all users.</p>

      <h3>Guideline 2.1 Keyboard Functionality</h3>
      <?php foreach($settings5 as $key => $val){ ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php } ?>

      <h3>Guideline 2.2 Adjustable Time Limits</h3>
      <?php foreach($settings6 as $key => $val){ ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php } ?>

      <h3>Guideline 2.3 Seizures And Physical Reactions</h3>
      <?php foreach($settings7 as $key => $val){ ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php } ?>

      <h3>Guideline 2.4 Content Navigation</h3>
      <?php foreach($settings8 as $key => $val){ ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php } ?>

      <h3>Guideline 2.5 Inputs Beyond The Keyboard</h3>
      <?php foreach($settings9 as $key => $val){ ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php } ?>

       <h2>3. Understandable</h2>
       <p>Information and the operation of the user interface must be understandable by all users.</p>

       <h3>Guideline 3.1 Readable</h3>
      <?php foreach($settings10 as $key => $val){ ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php } ?>

       <h3>Guideline 3.2 Predictable</h3>
      <?php foreach($settings11 as $key => $val){ ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php } ?>

      <h3>Guideline 3.3 Input Assistance</h3>
      <?php foreach($settings12 as $key => $val){ ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php } ?>
        
      <h2>4. Robust</h2>
       <p>Content must be robust enough that it can be interpreted by a wide variety of user agents, including assistive technologies, browsers, and plugins.</p>
        
       <h3>Guideline 4.1 Compatible</h3>
      <?php foreach($settings13 as $key => $val){ ?>           
       <label <?php echo esc_attr( get_option($key) ) == 'on' ? 'class="active-point"':'' ?>><input type="checkbox" name="<?php echo $key; ?>" <?php echo esc_attr( get_option($key) ) == 'on' ? 'checked="checked"' : ''; ?>><strong><?php echo $val; ?></strong></label>
      <?php } ?>

       <?php submit_button(); ?> 
    </form>
    <p>
    This checklist is meant to help you better understand the Section 508 (<a href="https://www.w3.org/TR/WCAG21/#intro">WCAG 2.1</a>) guidelines. Follow this link for the official publication of the <a href="https://www.w3.org/TR/WCAG21/#intro">WCAG 2.1</a> recommendations.
    </p>
</div>