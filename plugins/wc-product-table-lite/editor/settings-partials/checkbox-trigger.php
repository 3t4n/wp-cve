<div class="wcpt-toggle-options" wcpt-model-key="checkbox_trigger">
  <div class="wcpt-editor-light-heading wcpt-toggle-label">Checkbox trigger <?php echo wcpt_icon('chevron-down'); ?></div>

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
      <label>Redirect to</label>
      <label><input type="radio" wcpt-model-key="link" value=""> None</label>
      <label><input type="radio" wcpt-model-key="link" value="cart"> Cart</label>
      <label><input type="radio" wcpt-model-key="link" value="checkout"> Checkout</label>
      <label><input type="radio" wcpt-model-key="link" value="refresh"> Refresh page</label>
    </div>  

    <div class="wcpt-editor-row-option" wcpt-model-key="style">
      <label style="font-weight: bold;">Style</label>

      <!-- <div class="wcpt-editor-row-option">
        <label>
          Bottom offset (px)
        </label>
        <input type="number" wcpt-model-key="bottom" />
      </div> -->

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

      <!-- <div class="wcpt-editor-row-option">
        <label>
          Width
        </label>
        <input type="text" wcpt-model-key="width" />
      </div> -->

    </div>

    <div class="wcpt-editor-row-option" wcpt-model-key="labels">
      <label style="font-weight: bold;">
        Label
        <small>For multiple translations enter one per line like this:</small>
        <small>
          Add selected ([n]) to cart <br>
          en_US: Add selected ([n]) to cart <br>
          fr_FR: Ajouter des produits ([n]) au panier <br>
        </small>
        <small>Use placeholder [n] for number of checked items and [c] for total cost</small>
        <small>
          Check WordPress locale codes <a href="https://wcproducttable.com/wordpress-locale-codes" target="_blank">here</a>
        </small>        
      </label>
      <textarea wcpt-model-key="label"></textarea>
    </div>

  <!-- </div> -->

</div>
