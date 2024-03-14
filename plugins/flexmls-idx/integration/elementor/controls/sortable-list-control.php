<?php
class Sortable_List extends \Elementor\Base_Data_Control {
    public function get_type() {
        return 'sortable_list_control';
    }

    public function enqueue() {
		// Styles
		wp_register_style( 'sortable-list', plugins_url('../styles/sortable-list-control.css', __FILE__), [], '1.0.0' );
        wp_enqueue_style( 'sortable-list' );
        
        // Scripts
		wp_register_script( 'sortable-list-control', plugins_url('../scripts/sortable-list-control.js', __FILE__), [ 'jquery' ], '1.0.0', true );
		wp_enqueue_script( 'sortable-list-control' );

    }
    
    public function content_template() {
    
        $control_uid = $this->get_control_uid('sortable_list');
        ?>

        <label for="<?php echo $control_uid; ?>">{{data.label}}</label>

        <div>
            <input fmc-field="{{data.name}}" fmc-type="text" type="text" 
            class="flexmls_connect__list_values" value="" data-setting="{{ data.name }}">

            <ul class="flexmls_connect__sortable loaded ui-sortable">

            <# 
            var collection = {},
                ids, cValue;

            if(data.controlValue != '') {
                cValue = data.controlValue;
                ids = cValue.split(',');
                _.each(ids, function(id){
                    _.each(data.fields_types, function(val, key){
                        if(typeof(key)==='number'){
                            if(val.value == id){
                                collection[id] = val.value;
                            }
                        } else {
                            if(key == id){
                                collection[id] = val;
                            }
                        }
                    })
                });

                _.each(collection, function(display_text, id){ #>
                    <li data-connect-name="{{id}}">
                        <span class='remove' title='Remove this from the search'>&times;</span>
                        <span class='ui-icon ui-icon-arrowthick-2-n-s'></span>
                        {{display_text}}
                    </li>
            <# }) } #>
            </ul>

            <select name="available_types" class="flexmls_connect__available" id="<?php echo $control_uid; ?>">
                <# if(data.fields_types) {
                _.each(data.fields_types, function(val, id){
                    if(typeof(id)==='number'){
                        #>
                        <option value="{{val.value}}">{{val.display_text}}</option>
                        <# } else { #>
                        <option value="{{id}}">{{val}}</option>
                <#  }
                })
            } #>                 
            </select>

            <input type="button" title="Add this to the search" class="button add-proprtytype-button button-large fmc-margin-left-small flexmls_connect__add_property_type" value="{{data.button_name}}"></input>
            <img src="x" class="flexmls_connect__bootloader" onerror="flexmls_connect.sortable_setup(this);">
        </div>
        
        <?php
    }

    protected function get_default_settings() {
		return [
            'collection' => [],
		];
    }
}