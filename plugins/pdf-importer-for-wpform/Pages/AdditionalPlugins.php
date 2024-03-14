<?php

namespace rnpdfimporter\Pages;
global $rninstance;

use rnpdfimporter\core\PageBase;

class AdditionalPlugins extends PageBase{

    public function Render()
    {
        $rninstance=$this->Loader;
?>

<style>
    .AdditionalPluginItem{
        background-color: white;
        padding: 20px;
        margin:20px;
        display: inline-block;
        width: 300px;
        vertical-align: top;
    }

    .AdditionalPluginItem label{
        font-weight: bold;
    }

    .AdditionalPluginItem ul{
        list-style: disc;
        list-style-position: inside;
    }

    .AdditionalPluginItem .PluginTitle{
        min-height: 90px;
        border-bottom: 1px solid #ccc;
    }

    .AdditionalPluginItem ul{
        min-height:130px;
    }
</style>
        <div style="border:1px solid #ccc;border-radius:5px;padding:20px;display:flex;align-items:center;margin-top:50px;background-color: white;">
            <img src="<?php echo $rninstance->URL ?>images/adicons/formwiz.png"/>
            <div style="padding-left:20px;">
                <h2 style="margin:0;margin-bottom: 10px;">Would you like to get more than one add-on?</h2>
                <p style="margin:0;margin-bottom: 5px">Check out our bundles and get several add-ons at a HUGE discount</p>
                <a class="getItButton" href="https://formwiz.rednao.com/add-ons-bundle/">View Bundles</a>
            </div>
        </div>

<div style="padding: 20px">
    <div class="AdditionalPluginItem">
        <div class="productImage" style="text-align: center">
            <img src="<?php echo $this->Loader->URL ?>images/adicons/wpform.png"/>
        </div>

        <div class="PluginTitle">
            <label>PDF Importer for WPForms</label>
            <p>Fill an existing pdf using your entries information</p>
        </div>
        <p style="font-weight: bold;">Useful for:</p>
        <ul>
            <li>Fill an already existing pdf with WPForms information</li>
            <li>Creating licensing/gift certificates where you already have the design and you just want to add some information</li>
            <li>Filling government generated pdfs automatically</li>
        </ul>

        <div style="text-align: center">
            <a target="_blank" href="https://formwiz.rednao.com/pdf-importer/">View Details</a>
        </div>
    </div>

    <div class="AdditionalPluginItem">
        <div class="productImage" style="text-align: center">
            <img src="<?php echo $this->Loader->URL ?>images/adicons/pagebuilder.png"/>
        </div>
        <div class="PluginTitle">
            <label>Page Builder for WPForms</label>
            <p>Create pages using the WPForms entries.</p>
        </div>
        <p style="font-weight: bold;">Useful for:</p>
        <ul>
            <li>Creating listings, calendars, entry confirmation pages etc</li>
            <li>Edit entries in the front end</li>
            <li>Create pages that work with the entries like pages to approve/reject entries or "My Submitted Entries" account pages</li>
        </ul>

        <div style="text-align: center">
            <a target="_blank" href="https://formwiz.rednao.com/downloads/page-builder/">View Details</a>
        </div>
    </div>

    <div class="AdditionalPluginItem">
        <div class="productImage" style="text-align: center">
            <img src="<?php echo $this->Loader->URL ?>images/adicons/stp128.png"/>
        </div>
        <div class="PluginTitle">
            <label>Gutenberg submission to post</label>
            <p>Use gutenberg to design pages or post that are created when a form is submitted.</p>
        </div>
        <p style="font-weight: bold;">Useful for:</p>
        <ul>
            <li>Allow guest posting</li>
            <li>Create several pages with the same design (recipes, instructions, documentation etc)</li>
        </ul>

        <div style="text-align: center">
            <a target="_blank" href="https://formwiz.rednao.com/downloads/gutenberg-submission-to-post/">View Details</a>
        </div>
    </div>

    <div class="AdditionalPluginItem">
        <div class="productImage" style="text-align: center">
            <img src="<?php echo $this->Loader->URL ?>images/adicons/pdfbuilder.jpg"/>
        </div>
        <div class="PluginTitle">
            <label>PDF Builder</label>
            <p>Use gutenberg to design pages or post that are created when a form is submitted.</p>
        </div>
        <p style="font-weight: bold;">Useful for:</p>
        <ul>
            <li>Allow guest posting</li>
            <li>Create several pages with the same design (recipes, instructions, documentation etc)</li>
        </ul>

        <div style="text-align: center">
            <a target="_blank" href="https://formwiz.rednao.com/pdf-builder/">View Details</a>
        </div>
    </div>

    <div class="AdditionalPluginItem">
        <div class="productImage" style="text-align: center">
            <img src="<?php echo $this->Loader->URL ?>images/adicons/repeater.png"/>
        </div>
        <div class="PluginTitle">
            <label>Repeater for WPForms</label>
            <p>A field that let your customers repeat a group of fields multiple times.</p>
        </div>
        <p style="font-weight: bold;">Useful for:</p>
        <ul>
            <li>Define group of fields that can be repeated </li>
            <li>Useful when you don't know how many fields are needed, for example when your user needs to fill x number of person names or select x number of products </li>
        </ul>

        <div style="text-align: center">
            <a target="_blank" href="https://formwiz.rednao.com/downloads/repeater-for-wpforms/">View Details</a>
        </div>
    </div>
    <div class="AdditionalPluginItem">
        <div class="productImage" style="text-align: center">
            <img src="<?php echo $this->Loader->URL ?>images/adicons/calculation.png"/>
        </div>
        <div class="PluginTitle">
            <label>Calculation for WPForms</label>
            <p>A field that let you do calculations using other fields.</p>
        </div>
        <p style="font-weight: bold;">Useful for:</p>
        <ul>
            <li>Create your own calculations </li>
            <li>Custom conditions or even functions (to for example calculate the day between two date fields) are supported </li>
        </ul>

        <div style="text-align: center">
            <a target="_blank" href="https://formwiz.rednao.com/downloads/calculation-for-wpforms/">View Details</a>
        </div>
    </div>
    <div class="AdditionalPluginItem">
        <div class="productImage" style="text-align: center">
            <img src="<?php echo $this->Loader->URL ?>images/adicons/format_number.png"/>
        </div>
        <div class="PluginTitle">
            <label>Format Number for WPForms</label>
            <p>A field that can apply a custom format to a number inputed by the user.</p>
        </div>
        <p style="font-weight: bold;">Useful for:</p>
        <ul>
            <li>Creating fields that display an amount in a formatted currency</li>
        </ul>

        <div style="text-align: center">
            <a target="_blank" href="https://formwiz.rednao.com/downloads/format-number-for-wpforms/">View Details</a>
        </div>
    </div>

    <div class="AdditionalPluginItem">
        <div class="productImage" style="text-align: center">
            <img src="<?php echo $rninstance->URL ?>images/adicons/appointment.png"/>
        </div>
        <div class="PluginTitle">
            <label>Appointment for WPForms</label>
            <p>A field that display a list of available times that your user can pick from.</p>
        </div>
        <p style="font-weight: bold;">Useful for:</p>
        <ul>
            <li>Allow your users to schedule an appointment</li>
        </ul>

        <div style="text-align: center">
            <a target="_blank" href="https://formwiz.rednao.com/downloads/appointment-for-wpforms/">View Details</a>
        </div>
    </div>
</div>
<?php

    }
}

