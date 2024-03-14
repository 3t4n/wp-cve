<?php
if (!defined('ABSPATH')) {
    exit;
}
if (!current_user_can('manage_options')) {
    die('Access Denied');
}
global $wpdb;
$table_name1 = $wpdb->prefix . "totalsoft_icons";
$table_name2 = $wpdb->prefix . "totalsoft_ptable_id";
$table_name3 = $wpdb->prefix . "totalsoft_ptable_manager";
$table_name4 = $wpdb->prefix . "totalsoft_ptable_cols";
$table_name5 = $wpdb->prefix . "totalsoft_ptable_sets";
$table_name05 = $wpdb->prefix . "totalsoft_ptable_sets_def";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (check_admin_referer('edit-menu_', 'TS_PTable_Nonce')) {
       
    } else {
        wp_die('Security check fail');
    }
}
$TS_PTable_Columns = $wpdb->get_results($wpdb->prepare("SELECT PTable FROM $table_name4  WHERE id>%d order by id", 0));
$valueFromFirst = [];
$valueArray = json_decode($TS_PTable_Columns[0]->PTable, true);
foreach ($valueArray as $key => $v) {
    if ($v['TS_PTable_TType'] == "type1") {
        array_push($valueFromFirst, $v);
    }
}
function SortCol($a, $b)
{
    if ($a['index'] == $b['index']) {
        return 0;
    }
    return ($a['index'] < $b['index']) ? -1 : 1;
}

usort($valueFromFirst, "SortCol");

$TS_PTable_Settings = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5 WHERE id>%d order by id", 0));
$setValues = [];
$valueArraySet = json_decode($TS_PTable_Settings[0]->Price, true);
foreach ($valueArraySet as $key => $v) {
    if ($v['TS_PTable_TType'] == "type1") {
        array_push($setValues, $v);
    }
}
usort($setValues, "SortCol");

$keys = array_keys($valueArraySet);
$lastIdCol = $valueArraySet[$keys[count($keys) - 1]]['id'];

$TS_PTable_Manager = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3 WHERE id>%d order by id", 0));
$value = (json_decode($val = json_encode($TS_PTable_Manager), true));
$TableArray = [];
foreach ($value[0] as $res) {
    $values = (json_decode($res, true));
    for ($i = 0; $i < count($values); $i++) {
        $TableArray = $values[$i];
    }
}
$TotalSoftIconCount = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name1 WHERE id>%d order by id", 0));
$TS_PTable_Theme_Set = $wpdb->get_results($wpdb->prepare("SELECT Price FROM $table_name5 WHERE id>%d order by id", 0));

$valueArraySetting = json_decode($TS_PTable_Theme_Set[0]->Price, true);

$TS_PTable_Short_ID = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name2 WHERE id>%d order by id desc limit 1", 0));
$TS_PTable = $wpdb->get_results($wpdb->prepare("SELECT Defoult FROM $table_name3  WHERE id>%d order by id", 0));

$defoult = (json_decode(json_encode($TS_PTable), TRUE));
$valueArrayMeneger = json_decode($defoult[0]['Defoult'], TRUE);
$TS_PTableDef = $defoult[0];

$TS_PTable_Short_ID ? $TS_PTable_Short_IDn = $TS_PTable_Short_ID[0]->PTable_ID : $TS_PTable_Short_IDn = 0;
$TotalSoftFontCount = array('Abadi MT Condensed Light','Aharoni','Aldhabi','Andalus','Angsana New','AngsanaUPC','Aparajita','Arabic Typesetting','Arial','Arial Black', 'Batang','BatangChe','Browallia New','BrowalliaUPC','Calibri','Calibri Light','Calisto MT','Cambria','Candara','Century Gothic','Comic Sans MS','Consolas', 'Constantia','Copperplate Gothic','Copperplate Gothic Light','Corbel','Cordia New','CordiaUPC','Courier New','DaunPenh','David','DFKai-SB','DilleniaUPC', 'DokChampa','Dotum','DotumChe','Ebrima','Estrangelo Edessa','EucrosiaUPC','Euphemia','FangSong','Franklin Gothic Medium','FrankRuehl','FreesiaUPC','Gabriola', 'Gadugi','Gautami','Georgia','Gisha','Gulim','GulimChe','Gungsuh','GungsuhChe','Impact','IrisUPC','Iskoola Pota','JasmineUPC','KaiTi','Kalinga','Kartika', 'Khmer UI','KodchiangUPC','Kokila','Lao UI','Latha','Leelawadee','Levenim MT','LilyUPC','Lucida Console','Lucida Handwriting Italic','Lucida Sans Unicode', 'Malgun Gothic','Mangal','Manny ITC','Marlett','Meiryo','Meiryo UI','Microsoft Himalaya','Microsoft JhengHei','Microsoft JhengHei UI','Microsoft New Tai Lue', 'Microsoft PhagsPa','Microsoft Sans Serif','Microsoft Tai Le','Microsoft Uighur','Microsoft YaHei','Microsoft YaHei UI','Microsoft Yi Baiti','MingLiU_HKSCS', 'MingLiU_HKSCS-ExtB','Miriam','Mongolian Baiti','MoolBoran','MS UI Gothic','MV Boli','Myanmar Text','Narkisim','Nirmala UI','News Gothic MT','NSimSun','Nyala', 'Palatino Linotype','Plantagenet Cherokee','Raavi','Rod','Sakkal Majalla','Segoe Print','Segoe Script','Segoe UI Symbol','Shonar Bangla','Shruti','SimHei','SimKai', 'Simplified Arabic','SimSun','SimSun-ExtB','Sylfaen','Tahoma','Times New Roman','Traditional Arabic','Trebuchet MS','Tunga','Utsaah','Vani','Vijaya');
$TotalSoftFontGCount = array('Abadi MT Condensed Light','Aharoni','Aldhabi','Andalus','Angsana New','AngsanaUPC','Aparajita','Arabic Typesetting','Arial','Arial Black', 'Batang','BatangChe','Browallia New','BrowalliaUPC','Calibri','Calibri Light','Calisto MT','Cambria','Candara','Century Gothic','Comic Sans MS','Consolas', 'Constantia','Copperplate Gothic','Copperplate Gothic Light','Corbel','Cordia New','CordiaUPC','Courier New','DaunPenh','David','DFKai-SB','DilleniaUPC', 'DokChampa','Dotum','DotumChe','Ebrima','Estrangelo Edessa','EucrosiaUPC','Euphemia','FangSong','Franklin Gothic Medium','FrankRuehl','FreesiaUPC','Gabriola', 'Gadugi','Gautami','Georgia','Gisha','Gulim','GulimChe','Gungsuh','GungsuhChe','Impact','IrisUPC','Iskoola Pota','JasmineUPC','KaiTi','Kalinga','Kartika', 'Khmer UI','KodchiangUPC','Kokila','Lao UI','Latha','Leelawadee','Levenim MT','LilyUPC','Lucida Console','Lucida Handwriting Italic','Lucida Sans Unicode', 'Malgun Gothic','Mangal','Manny ITC','Marlett','Meiryo','Meiryo UI','Microsoft Himalaya','Microsoft JhengHei','Microsoft JhengHei UI','Microsoft New Tai Lue', 'Microsoft PhagsPa','Microsoft Sans Serif','Microsoft Tai Le','Microsoft Uighur','Microsoft YaHei','Microsoft YaHei UI','Microsoft Yi Baiti','MingLiU_HKSCS', 'MingLiU_HKSCS-ExtB','Miriam','Mongolian Baiti','MoolBoran','MS UI Gothic','MV Boli','Myanmar Text','Narkisim','Nirmala UI','News Gothic MT','NSimSun','Nyala', 'Palatino Linotype','Plantagenet Cherokee','Raavi','Rod','Sakkal Majalla','Segoe Print','Segoe Script','Segoe UI Symbol','Shonar Bangla','Shruti','SimHei','SimKai', 'Simplified Arabic','SimSun','SimSun-ExtB','Sylfaen','Tahoma','Times New Roman','Traditional Arabic','Trebuchet MS','Tunga','Utsaah','Vani','Vijaya');
?>
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url('../CSS/totalsoft.css', __FILE__); ?>">
<link rel="stylesheet" type="text/css"
      href="<?php echo plugins_url('../CSS/Total-Soft-pricing-Table-Admin.css', __FILE__); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo plugins_url('../CSS/alpha-color-picker.css', __FILE__); ?>">
<link href="https://fonts.googleapis.com/css?family=AbadiMTCondensedLight|Aharoni|Aldhabi|Amaranth|Andalus|AngsanaNew|AngsanaUPC|Anton|Aparajita|ArabicTypesetting|Arial|ArialBlack|Batang|BatangChe|BrowalliaNew|BrowalliaUPC|Calibri|CalibriLight|CalistoMT|Cambria|Candara|CenturyGothic|ComicSansMS|Consolas|Constantia|CopperplateGothic|CopperplateGothicLight|Battambang|Baumans|BungeeShade|Butcherman|Cabin|CabinSketch|Cairo|Damion|DilleniaUPC|DaunPenh|EagleLake|EastSeaDokdo|FiraSansCondensed|FiraSansExtraCondensed|FreesiaUPC|Gafata|Gabriola|JacquesFrancois|HeadlandOne|Katibeh|KaiTi|MicrosoftYiBaiti|MonsieurLaDoulaise|MrDeHaviland|NovaScript|NovaSquare|Nyala|OdorMeanChey|Offside|OldStandardTT|Oldenburg|Oxygen|OxygenMono|PrincessSofia|Prociono|Prompt|ProstoOne|ProzaLibre|Quicksand|Quintessential|Qwigley|Raavi|RacingSansOne|Radley|Rajdhani|Rakkas|Raleway|RalewayDots|Ramabhadra|Ramaraja|Rosarivo|Revalia|Shruti|Siemreap|SigmarOne|Signika|SignikaNegative|SimHei|SimKai|Simonetta|Tahoma|Tajawal|Tangerine|Taprom|Tauri|Taviraj|Teko|Telex|TenaliRamakrishna|TenorSans|TextMeOne|TheGirlNextDoor|Tienne|Tillana|TimesNewRoman|Timmana|Tinos|TitanOne|Vijaya"
      rel="stylesheet">
<form method="POST" enctype="multipart/form-data" oninput="TS_PTable_Out()">
    <?php wp_nonce_field('edit-menu_', 'TS_PTable_Nonce'); ?>
    <div class="Image_Container">
        <img src="<?php echo plugins_url('../Images/Themes/type1.png', __FILE__); ?>"
             onclick="Total_Soft_PTable_TImage_Type('type1')" class="Total_Soft_PTable_TImage">
        <img src="<?php echo plugins_url('../Images/Themes/type2.png', __FILE__); ?>"
             onclick="Total_Soft_PTable_TImage_Type('type2')" class="Total_Soft_PTable_TImage">
        <img src="<?php echo plugins_url('../Images/Themes/type3.png', __FILE__); ?>"
             onclick="Total_Soft_PTable_TImage_Type('type3')" class="Total_Soft_PTable_TImage">
        <img src="<?php echo plugins_url('../Images/Themes/type4.png', __FILE__); ?>"
             onclick="Total_Soft_PTable_TImage_Type('type4')" class="Total_Soft_PTable_TImage">
        <img src="<?php echo plugins_url('../Images/Themes/type5.png', __FILE__); ?>"
             onclick="Total_Soft_PTable_TImage_Type('type5')" class="Total_Soft_PTable_TImage">
    </div>
    <div class="Total_Soft_PTable_MainDiv">
        <div class="TS_left_Side_Div">
            <div class="Total_Soft_PTable_AMD">
                <div class="Support_Span">
                    <a href="https://wordpress.org/support/plugin/woo-pricing-table" target="_blank"
                       title="Click Here to Ask">
                        <i class="totalsoft totalsoft-comments-o"></i><span style="margin-left:5px;">If you have any questions click here to ask it to our support.</span>
                    </a>
                </div>
                <div class="Total_Soft_PTable_AMD1"></div>
                <div class="Total_Soft_PTable_AMD2">
                    <i class="Total_Soft_PTable_Help totalsoft totalsoft-question-circle-o" title=""></i> <span
                            class="Total_Soft_PTable_AMD2_But"
                            onclick="Total_Soft_PTable_AMD2_But1(<?php echo $TS_PTable_Short_IDn; ?>)">
				Create New
			</span>
                    <i class="Total_Soft_PTable_Help totalsoft totalsoft-question-circle-o" title=""></i> <span
                            class="Total_Soft_PTable_AMD2_But" onclick="TotalSoftPTable_Exit_From_Full_Page()">
				Cancel
			</span>
                </div>
                <div class="Total_Soft_PTable_AMD3">
                    <i class="Total_Soft_PTable_Help totalsoft totalsoft-question-circle-o" title=""></i> <span
                            class="Total_Soft_PTable_AMD2_But cancel" onclick="TotalSoftPTable_Reload()">
				Cancel
			</span> <i class="Total_Soft_PTable_Save Total_Soft_PTable_Help totalsoft totalsoft-question-circle-o"
                       title=""></i>
                
                      <span  class="Total_Soft_PTable_Save Total_Soft_PTable_AMD2_But"
                          onclick="TS_PTable_Ajax('save')" >
                        Save
                    </span>
                    <i class="Total_Soft_PTable_Update Total_Soft_PTable_Help totalsoft totalsoft-question-circle-o"
                       title=""></i>
                  
                    <span  class="Total_Soft_PTable_Update Total_Soft_PTable_AMD2_But"
                            onclick="TS_PTable_Ajax('update')">
                            <i class="totalsoft totalsoft-cog tsoft_pricing_setting" title=""></i>
                        Update

                    </span>
                    <input type="text" style="display:none" name="Total_SoftPTable_Update" id="Total_SoftPTable_Update">
                </div>
            </div>
            <table class="Total_Soft_PTable_AMMTable">
                <tr class="Total_Soft_PTable_AMMTableFR">
                    <td>No</td>
                    <td>Title</td>
                    <td>Theme</td>
                    <td>Column Count</td>
                    <td>Copy</td>
                    <td>Edit</td>
                    <td>Delete</td>
                </tr>
            </table>
            <table class="Total_Soft_PTable_AMOTable">

            </table>
            <div class="Total_Soft_PTable_Loading">
                <img src="<?php echo plugins_url('../Images/loading.gif', __FILE__); ?>">
            </div>
            <div class="Total_Soft_PTable_AMMain_Div">
                <table class="Total_Soft_PTable_AMShortTable">
                    <tr style="text-align:center">
                        <td>Shortcode</td>
                        <td>Templete Include</td>
                    </tr>
                    <tr>
                        <td>Copy &amp; paste the shortcode directly into any WordPress post or page.</td>
                        <td>Copy &amp; paste this code into a template file to include the pricing table within your
                            theme.
                        </td>
                    </tr>
                    <tr style="text-align:center">
                        <td>
                            <span id="Total_Soft_PTable_ID"></span> <i
                                    class="Total_Soft_PTable_Help1 totalsoft totalsoft-files-o" title="Click to Copy."
                                    onclick="Copy_Shortcode_PT('Total_Soft_PTable_ID')"></i>
                        </td>
                        <td>
                            <span id="Total_Soft_PTable_TID"></span> <i
                                    class="Total_Soft_PTable_Help1 totalsoft totalsoft-files-o" title="Click to Copy."
                                    onclick="Copy_Shortcode_PT('Total_Soft_PTable_TID')"></i>
                        </td>
                    </tr>
                </table>
                <div class="TS_PTable_Remove_Cols_Fixed"></div>
                <div class="TS_PTable_Remove_Cols_Abs">
                    <div class="TS_PTable_Remove_Cols_Rel">
                        <p> Are you sure you want to remove ? </p>
                        <span class="TS_PTable_Remove_Cols_Rel_No">No</span> <span
                                class="TS_PTable_Remove_Cols_Rel_Yes">Yes</span>
                    </div>
                </div>
                <div class="Total_Soft_PTable_AMMain_Div2">
                    <div class="Total_Soft_PTable_AMMain_Div2_But">
				<span class="Total_Soft_PTable_AddColBut" onclick="Total_Soft_PTable_New_Col_Set()">
					<span class="Total_Soft_PTable_AddColBut2">
						<i class="Total_Soft_PTable_AddColBut_Icon totalsoft totalsoft-plus-circle"
                           style="margin-right: 5px;"></i>
						Add Column
					</span>
				</span>
                        <input type="text" style="display: none;" id="Total_Soft_PTable_Cols_Count"
                               name="Total_Soft_PTable_Cols_Count" value="0">
                        <select id="Total_Soft_PTable_Select_Icon" style="display: none;">
                            <option value="none" selected> None</option>
                            <?php for ($i = 0; $i < count($TotalSoftIconCount); $i++) { ?>
                                <option value="<?php echo strtolower(str_replace(" ", "-", $TotalSoftIconCount[$i]->Icon_Name)); ?>"><?php echo '&#x' . $TotalSoftIconCount[$i]->Icon_Type . '&nbsp; &nbsp; &nbsp;' . $TotalSoftIconCount[$i]->Icon_Name; ?></option>
                            <?php } ?>
                        </select> 
                    </div>

                    <div class="Total_Soft_PTable_AMMain_Div2_Cols">
                        <!--Teman 1 Start-->
                        <div class="TS_Desctop_View">

                        </div>
                        <!--Teman 1 End-->
                    </div>
                </div>
            </div>
        </div>
        <div id="TS_Hidden_Opt">
            <div class="Ts_Opt_Close_Button_Cont">
                <div class="Ts_Opt_Container_Options">
                   <!--  <i class="totalsoft totalsoft-chevron-circle-right" title="Close Option"
                       onclick="TotalSoftPTable_Close_Option()"></i> -->
                        <p>Container Options</p>
                </div>
            </div>
            <div class="TS_Hidden_OPT_Body">
                <ul class="TS_Hidden_OPT_Body_Ul">
                    <li class="TS_Hidden_OPT_Body_Li ">
                      <!--   <div class="TS_Toggle_Option">
                            <i class="totalsoft totalsoft-chevron-circle-down" onclick="TS_Toggle_Li_Opt(this)"
                               title="Close Option"
                               onclick="TotalSoftPTable_Close_Option()"></i>
                        </div> -->
                        <div class="TS_Hidden_OPT_Body_Div Hidden_Top_Set_General"></div>
                    </li>
                     <li class="TS_Hidden_OPT_Body_Li">
                        <div class="block_option" >
                            <p>Block Options</p>
                            
                        </div>
                    </li>
                    <li class="TS_Hidden_OPT_Body_Li">
                        <div class="TS_Toggle_Option" onclick="TS_Toggle_Li_Opt(this)" >
                            <p class="option_Name">Header Options</p>
                            <i class="totalsoft totalsoft-chevron-circle-down"
                               title="Close Option"
                              ></i>
                        </div>
                        <div class="TS_Hidden_OPT_Body_Div Hidden_Top_Set"></div>
                    </li>
                    <li class="TS_Hidden_OPT_Body_Li">
                        <div class="TS_Toggle_Option"onclick="TS_Toggle_Li_Opt(this)">
                            <p class="option_Name">Feautures Options</p>
                            <i class="totalsoft totalsoft-chevron-circle-down" 
                               title="Close Option"
                               ></i>
                        </div>
                        <div class="TS_Hidden_OPT_Body_Div hidden_Top_Set_Desc"></div>
                    </li>
                    <li class="TS_Hidden_OPT_Body_Li">
                        <div class="TS_Toggle_Option" onclick="TS_Toggle_Li_Opt(this)">
                            <p class="option_Name">Button Options</p>
                            <i class="totalsoft totalsoft-chevron-circle-down"
                               title="Close Option"
                               ></i>
                        </div>
                        <div class="TS_Hidden_OPT_Body_Div hidden_Set_But"></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <input type="text" style="display:none" name="Total_Soft_PTable_Theme_Type" id="Total_Soft_PTable_Theme_Type">
    <input type="text" style="display:none" name="Total_Soft_PTable_New_Col" id="Total_Soft_PTable_New_Col" value="0">
    <input type="text" style="display:none" name="Total_Soft_PTable_New_Col_Last_Id"
           id="Total_Soft_PTable_New_Col_Last_Id" value="<?php echo $lastIdCol; ?>">
    <input type="text" style="display:none;" name="Total_Soft_PTable_Setting_Type" id="Total_Soft_PTable_Setting_Type"
           value="0">
    <input type="text" style="display:none" name="Total_Soft_PTable_Col_Id" id="Total_Soft_PTable_Col_Id" value="0">
    <input type="text" style="display:none" name="Total_Soft_PTable_Col_Val_Id" id="Total_Soft_PTable_Col_Val_Id"
           value="0">
    <input type="text" style="display:none" name="Total_Soft_PTable_Col_Count" id="Total_Soft_PTable_Col_Count"
           value="0">
    <input type="text" style="display:none" name="Total_Soft_PTable_Col_Del" id="Total_Soft_PTable_Col_Del" value="0">
    <input type="text" style="display:none" name="Total_Soft_PTable_TImage" id="Total_Soft_PTable_TImage">
    <input type="text" style="display:none" name="Total_Soft_PTable_Add_Set" id="Total_Soft_PTable_Add_Set" value="0">
    <input type="text" style="display:none" name="Total_Soft_PTable_Col_Sel_Count" id="Total_Soft_PTable_Col_Sel_Count"
           value="0">
    <input type="text" style="display:none;" class="Total_Soft_PTable_Col_Type" name="Total_Soft_PTable_Col_Type"
           id="Total_Soft_PTable_Col_Type">
    <input type="text" style="display:none;" class="Total_Soft_PTable_Dup" name="Total_Soft_PTable_Dup"
           id="Total_Soft_PTable_Dup" value="0">
    <div id="TS_PTable_Fonts" style="display: none;">
        <?php for ($i = 0; $i < count($TotalSoftFontGCount); $i++) { ?>
            <option value='<?php echo $TotalSoftFontGCount[$i]; ?>'
                    style="font-family: <?php echo $TotalSoftFontGCount[$i]; ?>;"><?php echo $TotalSoftFontCount[$i]; ?></option>
        <?php } ?>
    </div>
</form>