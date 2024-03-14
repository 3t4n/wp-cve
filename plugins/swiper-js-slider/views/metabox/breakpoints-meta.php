<?php 

$breakpoints   = get_post_meta($post->ID, $this->meta_prefix.'setting_breakpoints', true);

$breakpoints = array(
    "320"       => $this->s_j_s_checkit('320', $breakpoints, '1'),
    "480"       => $this->s_j_s_checkit('480', $breakpoints, '2'),
    "640"       => $this->s_j_s_checkit('640', $breakpoints, '3'),
    "768"       => $this->s_j_s_checkit('768', $breakpoints, '3'),
    "980"       => $this->s_j_s_checkit('980', $breakpoints, '3'),
    "1024"      => $this->s_j_s_checkit('1024', $breakpoints, '3')
);

?>

<div id="sjs-tab-breakpoints" class="tabSec breakpoints">
    <h3>Responsive Breakpoints</h3>
    <table class="breakpointsSettings">
        <tr data-filter-item data-filter-name="breakpoints Breakpoints responsive 320">
            <td>
                <label>320px</label>
            </td>
            <td>
                <span>When window width is >= 320px</span>
                <input 
                    id="breakpoints20"
                    name="<?php echo $this->meta_prefix.'setting_breakpoints[320]'; ?>"
                    type="number" 
                    min="1"
                    max="99"
                    value="<?php echo $breakpoints['320']; ?>">
            </td>
        </tr>
        <tr data-filter-item data-filter-name="breakpoints Breakpoints responsive 480">
            <td>
                <label>480px</label>
            </td>
            <td>
                <span>When window width is >= 480px</span>
                <input 
                    id="breakpoints480"
                    name="<?php echo $this->meta_prefix.'setting_breakpoints[480]'; ?>"
                    type="number" 
                    min="1"
                    max="99"
                    value="<?php echo $breakpoints['480']; ?>">
            </td>
        </tr>
        <tr data-filter-item data-filter-name="breakpoints Breakpoints responsive 640">
            <td>
                <label>640px</label>
            </td>
            <td>
                <span>When window width is >= 640px</span>
                <input 
                    id="breakpoints640"
                    name="<?php echo $this->meta_prefix.'setting_breakpoints[640]'; ?>"
                    type="number" 
                    min="1"
                    max="99"
                    value="<?php echo $breakpoints['640']; ?>">
            </td>
        </tr>
        <tr data-filter-item data-filter-name="breakpoints Breakpoints responsive 768">
            <td>
                <label>768px</label>
            </td>
            <td>
                <span>When window width is >= 768px</span>
                <input 
                    id="breakpoints768"
                    name="<?php echo $this->meta_prefix.'setting_breakpoints[768]'; ?>"
                    type="number" 
                    min="1"
                    max="99"
                    value="<?php echo $breakpoints['768']; ?>">
            </td>
        </tr>
        <tr data-filter-item data-filter-name="breakpoints Breakpoints responsive 980">
            <td>
                <label>980px</label>
            </td>
            <td>
                <span>When window width is >= 980px</span>
                <input 
                    id="breakpoints980"
                    name="<?php echo $this->meta_prefix.'setting_breakpoints[980]'; ?>"
                    type="number" 
                    min="1"
                    max="99"
                    value="<?php echo $breakpoints['980']; ?>">
            </td>
        </tr>
        <tr data-filter-item data-filter-name="breakpoints Breakpoints responsive 1024">
            <td>
                <label>1024px</label>
            </td>
            <td>
                <span>When window width is >= 1024px</span>
                <input 
                    id="breakpoints1024"
                    name="<?php echo $this->meta_prefix.'setting_breakpoints[1024]'; ?>"
                    type="number" 
                    min="1"
                    max="99"
                    value="<?php echo $breakpoints['1024']; ?>">
            </td>
        </tr>
    </table>
</div>