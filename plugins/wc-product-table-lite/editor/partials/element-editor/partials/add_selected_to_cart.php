<div class="wcpt-editor-row-option">
  <label>
    <small>
    Note: To use this element you need to have the Checkbox element in any column of your table. This element will help visitors add their checked items to cart or check all items on the table page. 
    </small>
  </label>
</div>

<div class="wcpt-editor-row-option">
  <label>
    Label for 'Add selected to cart' button
    <small>Available placeholders: {total_qty} {total_cost}</small>
  </label>
  <input type="text" wcpt-model-key="add_selected_label" />
</div>

<div class="wcpt-editor-row-option">
  <label>
    Optional label when only 1 item is selected
    <small>Leave empty to use the regular label instead</small>
  </label>
  <input type="text" wcpt-model-key="add_selected_label__single_item" />
</div>

<div class="wcpt-editor-row-option">
  <label>
    Label for 'Add selected to cart' button when none selected
  </label>
  <input type="text" wcpt-model-key="add_selected__unselected_label" />
</div>

<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="select_all_enabled" />
    Enable the 'Select all' button
  </label>
</div>

<div 
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="select_all_enabled"
  wcpt-condition-val="true"  
>
  <label>
    Label for 'Select all' button
  </label>
  <input type="text" wcpt-model-key="select_all_label" />
</div>

<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="clear_all_enabled" />
    Enable the 'Clear all' button
  </label>
</div>

<div 
  class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="clear_all_enabled"
  wcpt-condition-val="true"  
>
  <label>
    Label for 'Clear all' button
  </label>
  <input type="text" wcpt-model-key="clear_all_label" />
</div>

<div class="wcpt-editor-row-option">
  <label>
    <input type="checkbox" wcpt-model-key="duplicate_enabled" />
    Duplicate in table footer
  </label>
</div>


<div class="wcpt-editor-row-option" wcpt-model-key="style">

  <!-- style button (general) -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] > *">

    <span class="wcpt-toggle-label">
      Style for all Buttons - general
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <?php require( 'style/common-props.php' ); ?>

  </div>

  <!-- style for each -->
  <?php 
    $items = array(
      array(
        'label' => "Style for 'Add selected' button",
        'selector' => '[id] > .wcpt-add-selected__add',
      ),

      array(
        'label' => "Style for 'Add selected' button - no products selected",
        'selector' => '[id].wcpt-add-selected--unselected > .wcpt-add-selected__add',
      ),

      array(
        'label' => "Style for 'Select all' button",
        'selector' => '[id] > .wcpt-add-selected__select-all',
      ),

      array(
        'label' => "Style for 'Clear all' button",
        'selector' => '[id] > .wcpt-add-selected__clear-all',
      ),
    );

    foreach( $items as $item ){
      ?>
      <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="<?php echo $item['selector']; ?>">
        <span class="wcpt-toggle-label">
          <?php echo $item['label']; ?>
          <?php echo wcpt_icon('chevron-down'); ?>
        </span>

        <!-- opacity -->
        <div class="wcpt-editor-row-option">
          <label>Opacity</label>
          <select wcpt-model-key="opacity">
            <?php 
              $i = 10;
              while( $i > 0 ){
                ?>
                <option><?php echo $i / 10; ?></option>
                <?php
                --$i;
              }
            ?>
          </select>
        </div>


        <!-- font color -->
        <div class="wcpt-editor-row-option">
          <label>Font color</label>
          <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker">
        </div>

        <!-- background color -->
        <div class="wcpt-editor-row-option">
          <label>Background color</label>
          <input type="text" wcpt-model-key="background-color" class="wcpt-color-picker">
        </div>

        <!-- background color on hover -->
        <div class="wcpt-editor-row-option">
          <label>↳ on hover</label>
          <input type="text" wcpt-model-key="background-color:hover" class="wcpt-color-picker">
        </div>

        <!-- border-color -->
        <div class="wcpt-editor-row-option">
          <label>Border color</label>
          <input type="text" wcpt-model-key="border-color" class="wcpt-color-picker" placeholder="color">
        </div>
        
        <!-- border-color on hover -->
        <div class="wcpt-editor-row-option">
          <label>↳ color on hover</label>
          <input type="text" wcpt-model-key="border-color:hover" class="wcpt-color-picker" placeholder="color">
        </div>

        <!-- display -->
        <div class="wcpt-editor-row-option">
          <label>Display</label>
          <select wcpt-model-key="display">
            <option value=""></option>
            <option value="block">Block</option>
            <option value="inline">Inline</option>
            <option value="inline-block">Inline-block</option>
            <option value="none">None</option>
          </select>
        </div>

      </div>
      <?php
    }
  ?>

  <!-- style for cart icon -->
  <div class="wcpt-editor-row-option wcpt-toggle-options wcpt-row-accordion" wcpt-model-key="[id] .wcpt-add-selected__cart-icon">

    <span class="wcpt-toggle-label">
      Style for Cart Icon
      <?php echo wcpt_icon('chevron-down'); ?>
    </span>

    <!-- font-size -->
    <div class="wcpt-editor-row-option">
      <label>Size</label>
      <input type="text" wcpt-model-key="font-size">
    </div>

    <!-- font-color -->
    <div class="wcpt-editor-row-option">
      <label>Stroke color</label>
      <input type="text" wcpt-model-key="color" placeholder="#000" class="wcpt-color-picker" >
    </div>

    <!-- fill -->
    <div class="wcpt-editor-row-option">
      <label>Fill color</label>
      <input type="text" wcpt-model-key="fill" class="wcpt-color-picker" >
    </div>

    <!-- stroke-width -->
    <div class="wcpt-editor-row-option">
      <label>Thickness</label>
      <input type="text" wcpt-model-key="stroke-width">
    </div>

    <!-- margin -->
    <div class="wcpt-editor-row-option">
      <label>Margin</label>
      <input type="text" wcpt-model-key="margin-top" placeholder="top">
      <input type="text" wcpt-model-key="margin-right" placeholder="right">
      <input type="text" wcpt-model-key="margin-bottom" placeholder="bottom">
      <input type="text" wcpt-model-key="margin-left" placeholder="left">
    </div>

  </div>

</div>

<div class="wcpt-editor-row-option">
  <label>HTML Class</label>
  <input type="text" wcpt-model-key="html_class" />
</div>
