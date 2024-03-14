<section id="wphb-box-manager-fonts" class="firan-section box-manager-fonts" style="background: none;box-shadow: none;">
  <div class="box-content" style="padding:0">
    <div class="row">
      <form name="fi_manager" method="post" class="form-wrap" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
        <div class="cols custom-rows">
          <?php $this->view('wpfi-fimf-side-page'); ?>
        </div>
        <div class="cols rows-manager">
        <div class="row-manager-wrapper">
          <div class="box-title">
            <h3><i class="fcp fcp-magic-wand"></i>سفارشی سازی</h3>
            <input type="submit" name="fi_ul_font" value="به روز رسانی" class="button-primary btns" style="margin: 0 15px 0 10px;" />
          </div>
          <div class="box-content">
            <?php 
	  $row = 0;
	  foreach($this->options as $key=>$elm) { 
	  
		  $tab = (isset($elm['tab'])) ? $elm['tab'] : $row;
		  $label = (isset($elm['label'])) ? $elm['label'] : 'کلاس سفارشی';
		  $subject = (isset($elm['subject'])) ? $elm['subject'] : null;
		  $name = (isset($elm['font']) && $elm['font'] !=='0') ? $elm['font'] : '0';
		  $size = (isset($elm['size']) && $elm['size'] !=='0') ? $elm['size'] : '';
		  $size_type = (isset($elm['size_type'])&&$elm['size_type'] !=='0') ? $elm['size_type'] : 'px';	
		  $weight = (isset($elm['weight']) && $elm['weight'] !=='0') ? $elm['weight'] : '';
		  $style = (isset($elm['style']) && $elm['style'] !=='0') ? $elm['style'] : '';
		  $color = (isset($elm['color'])&&$elm['color']) ? $elm['color'] : '';
		  $active = ($row <1) ? 'opened' : null;  ?>
    <div class="fi-row-box clearfix <?php echo $active; ?>" data-tab-url="<?php echo $tab; ?>" >
      <input name="fi_ops[<?php echo $tab; ?>][subject]" class="choose_element" value="<?php echo $elm['class']; ?>" type="hidden">
      <div class="row">
        <div class="fi-grid fi-label-row firan-fix">
          <div class="col-3">
            <h4>عنوان این کلاس</h4>
          </div>
          <div class="col-9">
            <input name="fi_ops[<?php echo $tab; ?>][label]" class="fi-label" value="<?php echo $label; ?>" type="text">
            <span class="sub-des">این بخش تنها برای دسته بندی دستورات است و در خروجی نمایش داده نمی شود.</span> </div>
        </div>
      </div>
      <div class="row">
        <div class="fi-grid fi-subject-row firan-fix">
          <div class="col-3">
            <h4>انتخاب کلاس ها</h4>
          </div>
          <div class="col-9">
            <textarea name="fi_ops[<?php echo $tab; ?>][subject]" class="show-code" type="text"><?php echo $subject; ?></textarea>
            <span class="sub-des">تگ ها، کلاس ها و یا آیدی هایی که می خواهید سفارشی سازی کنید را در این بخش وارد کنید. (این بخش بایسته است)</span> </div>
        </div>
      </div>
      <div class="row">
        <div class="fi-grid fi-font-row firan-fix">
          <div class="col-3">
            <h4>تنظیمات فونت</h4>
          </div>
          <div class="col-9">
            <div class="row">
              <div class="cols col-4">
                <label>نام فونت</label>
                <select name="fi_ops[<?php echo $tab; ?>][font]" class="choose_font_type">
                  <option value="0">گزینش فونت</option>
                  <?php fi_fonts_name('html', $name); ?>
                </select>
              </div>
              <div class="cols col-4">
                <label>وزن فونت</label>
                <select name="fi_ops[<?php echo $tab; ?>][weight]">
                  <option value="0">گزینش کنید</option>
                  <?php $this->get_html_select(array('normal','100','200','300','400','500','600','700','800','900','bold'), $weight); ?>
                </select>
              </div>
              <div class="cols col-4">
                <label>استایل فونت</label>
                <select name="fi_ops[<?php echo $tab; ?>][style]">
                  <option value="0">گزینش کنید</option>
                  <?php $this->get_html_select(array('normal','italic','oblique'), $style); ?>
                </select>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="fi-grid fi-color-row firan-fix">
          <div class="col-3">
            <h4>انتخاب کلاس ها</h4>
          </div>
          <div class="col-9">
            <label>رنگ بندی</label>
            <div class="lcwp_colpick"> <span class="lcwp_colblock"></span>
              <input name="fi_ops[<?php echo $tab; ?>][color]" value="<?php echo $color; ?>" type="text">
            </div>
          </div>
        </div>
      </div>
    </div>
            <?php $row++; } ?>
          </div>
        </div>
      </form>
    </div>
  </div>
  </div>
</section>
