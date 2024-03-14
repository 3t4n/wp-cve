/*

  jQuery plugin for creating a dropdown that is dependent on the value of 
  another dropdown. 

  <!-- master dropdown -->
  <select id="master">
    <option value="A">Words that start with A</option>
    <option value="B">Words that start with B</option>
  </select>

  <!-- dependent dropdown -->
  <select id="dependent">
    <optgroup label="A">
      <option value="" selected="selected">Prompt Text</option>
      <option value="apples">Apples</option>
      <option value="avocados">Avocados</option>
    </optgroup>  
    <optgroup label="B">
      <option value="" selected="selected">Prompt Text</option>
      <option value="bananas">Bananas</option>
    </optgroup>
  </select>

  Usage:
  new DependentDropdowns('#master', '#dependent');

*/

function DependentDropdowns(master, dependent) {

  // The master dropdown.
  this.masterSelect = jQuery(master);

  // The dependent dropdown.
  this.dependentSelect = jQuery(dependent);

  // A copy of all the dependent options, grouped by master values type. Not 
  // to be overwritten. This is referenced every time the dependent dropdown
  // is updated.
  this.dependentHtml = this.dependentSelect.html();

  this.updateDependentSelect();

  this.masterSelect.change(jQuery.proxy(this.updateDependentSelect, this));

}

DependentDropdowns.prototype = {
  constructor: DependentDropdowns,

  updateDependentSelect: function() {

    if( this.masterSelect.val() === "") {
      // if the master select doesn't have a value, disable the dependent select
      this.dependentSelect.prop('disabled', 'disabled');

    } else {

      var selectedMasterValue = this.masterSelect.val();

      var validDependentOptions = jQuery(this.dependentHtml)
        .filter("optgroup[label='" + selectedMasterValue + "']").find("option");

      // Only enable the dependent dropdown if there are options. The prompt 
      // option will always be there, so there has to be more than one.
      if( validDependentOptions.length > 1) {
        this.dependentSelect.html( validDependentOptions );
        this.dependentSelect.prop('disabled', null);
      } else {
        this.dependentSelect.html( '<option value="">none available</option>' );
        this.dependentSelect.prop('disabled', true);
      }
    }

  }

}
