<div class="wcpt-editor-row-option"
  wcpt-panel-condition="prop"
  wcpt-condition-prop="position"
  wcpt-condition-val="header"
>
  <div class="wcpt-editor-row-option"
    wcpt-panel-condition="prop"
    wcpt-condition-prop="display_type"
    wcpt-condition-val="dropdown"
  >
    <div class="wcpt-editor-row-option"
      wcpt-panel-condition="prop"
      wcpt-condition-prop="single"
      wcpt-condition-val="true"
    >
      <div class="wcpt-editor-row-option">    
        <label>
          Heading format when option is selected
        </label>
        <label>
          <input type="radio" wcpt-model-key="heading_format__op_selected" value="only_heading" >
          Only show filter heading
        </label>      
        <label>
          <input type="radio" wcpt-model-key="heading_format__op_selected" value="heading_and_selected" >
          Show filter heading and selected option
        </label>
        <label>
          <input type="radio" wcpt-model-key="heading_format__op_selected" value="only_selected" >
          Replace heading with selected option
        </label>
      </div>
    </div>
  </div>
</div>