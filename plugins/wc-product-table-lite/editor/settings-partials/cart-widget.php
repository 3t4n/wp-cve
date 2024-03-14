<div class="wcpt-toggle-options" wcpt-model-key="cart_widget">
  <div class="wcpt-editor-light-heading wcpt-toggle-label">Cart widget <?php echo wcpt_icon('chevron-down'); ?></div>

  <div class="wcpt-editor-row-option">
    <label>Toggle</label>
    <label><input type="radio" wcpt-model-key="toggle" value="enabled"> Enabled</label>
    <label><input type="radio" wcpt-model-key="toggle" value="disabled"> Disabled</label>
  </div>

  <div class="wcpt-editor-row-option">
    <label>Responsive toggle</label>
    <label><input type="radio" wcpt-model-key="r_toggle" value="enabled"> Enabled</label>
    <label><input type="radio" wcpt-model-key="r_toggle" value="disabled"> Disabled</label>
  </div>

  <div class="wcpt-editor-row-option">
    <label>Link to</label>
    <label><input type="radio" wcpt-model-key="link" value="cart"> Cart</label>
    <label><input type="radio" wcpt-model-key="link" value="checkout"> Checkout</label>
    <?php wcpt_pro_radio('custom_url', ' Custom url', 'link'); ?>    
  </div>  

  <div 
    class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="link"
    wcpt-condition-val="custom_url"
  >
    <label>Custom redirect URL </label>
    <input type="text" wcpt-model-key="custom_url">
  </div>      

  <div class="wcpt-editor-row-option">
    <label>Cost source</label>
    <label><input type="radio" wcpt-model-key="cost_source" value="subtotal"> Subtotal</label>
    <label><input type="radio" wcpt-model-key="cost_source" value="total"> Total</label>
  </div>    

  <div class="wcpt-editor-row-option">
    <?php wcpt_pro_checkbox( true, 'Enable on all pages (provides include / exclude options)', 'enabled_site_wide' ); ?>
  </div>

  <div
    class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="enabled_site_wide"
    wcpt-condition-val="true"
  >

    <div class="wcpt-editor-row-option">
      <label>
        <small>
          1. Use these options only if you you need finer control </br>
          2. The 'Exclude URLs' option takes priority over the 'Include URLs' option </br>
          3. Enter only one relative URL per line </br>
          4. URLs should be relative to "<?php echo site_url() . '/'; ?>", eg: about-us/team </br>
          5. Add "*" to end of path to mass exclude, eg: shop/* </br>
          5. Enter just "/" to refer to home page </br>
        </small>
      </label>
    </div>    

    <div class="wcpt-editor-row-option">
      <label>
        Exclude relative URLs
        <small>Relative URLs entered here will be excluded even if they are entered in the include option below</small>        
      </label>
      <textarea wcpt-model-key="exclude_urls" placeholder="eg: cart"></textarea>
    </div>  

    <div class="wcpt-editor-row-option">
      <label>
        Include relative URLs
        <small>If you use this option, the cart widget will appear only on these pages</small>
      </label>
      <textarea wcpt-model-key="include_urls" placeholder="eg: shop"></textarea>
    </div>  

  </div>

  <div class="wcpt-editor-row-option" wcpt-model-key="style">
    <label style="font-weight: bold;">Style</label>

    <div class="wcpt-editor-row-option">
      <label>
        Bottom offset (px)
      </label>
      <input type="number" wcpt-model-key="bottom" />
    </div>

    <div class="wcpt-editor-row-option">
      <label>
        Background color
      </label>
      <input type="text" wcpt-model-key="background-color" />
    </div>

    <div class="wcpt-editor-row-option">
      <label>
        Border color
      </label>
      <input type="text" wcpt-model-key="border-color" />
    </div>

    <div class="wcpt-editor-row-option">
      <label>
        Font color
      </label>
      <input type="text" wcpt-model-key="color" />
    </div>

    <div class="wcpt-editor-row-option">
      <label>
        Font size
      </label>
      <input type="text" wcpt-model-key="font-size" />
    </div>

    <div class="wcpt-editor-row-option">
      <label>Font weight</label>
      <select wcpt-model-key="font-weight">
        <option value=""></option>    
        <option value="normal">Normal</option>
        <option value="bold">Bold</option>
        <option value="light">Light</option>
        <option value="100">100</option>
        <option value="200">200</option>
        <option value="300">300</option>
        <option value="400">400</option>
        <option value="500">500</option>
        <option value="600">600</option>
        <option value="700">700</option>
        <option value="800">800</option>
        <option value="900">900</option>
      </select>
    </div>    

    <div class="wcpt-editor-row-option">
      <label>
        Width
      </label>
      <input type="text" wcpt-model-key="width" />
    </div>

  </div>

  <div class="wcpt-editor-row-option" wcpt-model-key="labels">
    <label style="font-weight: bold;">
      Labels
      <small>For multiple translations enter one per line like this:</small>
      <small>
        Item <br>
        en_US: Item <br>
        fr_FR: Article <br>
      </small> 
      <small>
        Check WordPress locale codes <a href="https://wcproducttable.com/wordpress-locale-codes" target="_blank">here</a>
      </small>
    </label>

    <div class="wcpt-editor-row-option">
      <label>
        'Item' (singular)
      </label>
      <textarea wcpt-model-key="item"></textarea>
    </div>

    <div class="wcpt-editor-row-option">
      <label>
        'Items' (plural)
      </label>
      <textarea wcpt-model-key="items"></textarea>
    </div>

    <div class="wcpt-editor-row-option">
      <label>
        'View Cart'
      </label>
      <textarea wcpt-model-key="view_cart"></textarea>
    </div>

    <div class="wcpt-editor-row-option">
      <label>
        'Extra charges may apply'
      </label>
      <textarea wcpt-model-key="extra_charges"></textarea>
    </div>
  </div>

</div>
