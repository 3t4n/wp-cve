<?php class EModal_View {
	protected $file;
    protected $values = array();
	public function set($key, $value) {
	    $this->values[$key] = $value;
	}
	public function output(){}
    public function render()
    {
    	$this->pre_render();
    	$this->output();
    	$this->post_render();
    }
    public function pre_render(){}
    public function post_render(){}
}
