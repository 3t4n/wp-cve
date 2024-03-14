<div class="wrap">
    <h1>
        <span class="dashicons dashicons-controls-repeat" style="vertical-align: middle;" ></span> 
        <?php esc_attr_e( 'New Integration.', 'wpgsi' ); ?>
        <a class="button-secondary" style="color: red" href="<?php echo admin_url('admin.php?page=wpgsi')?>" class="button-secondary"> <?php esc_attr_e( 'Cancel', 'wpgsi' ); ?></a>        
    </h1>

    <div id="new_connection">
        <div id="post-body" class="metabox-holder ">
            <div id="vuejs-app-div" >
                <!-- <pre> {{ $data }}  </pre>  -->
                <br>
                <form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" >
                    <input type="hidden" name="action" value="wpgsi_Integration">
                    <input type="hidden" name="status" value="new_Integration" />
                    <div id='FirstPart'>
                        <!-- <h2><?php esc_attr_e( 'Select Event & Spreadsheet :', 'wpgsi' ); ?></h2> -->
                        <table class="widefat">
                            <tbody>
                                <tr class="alternate">
                                    <td class="row-title">  <label for="tablecell"> <?php esc_attr_e( 'Integration Title', 'wpgsi' ); ?> </label>  </td>
                                    <td> <input type="text" class="regular-text"  name="IntegrationTitle" v-model="IntegrationTitle" placeholder="<?php _e( 'Enter title here', 'wpgsi'); ?>" required="required"> </td>
                                    <td> </td>
                                </tr>
                                <tr >
                                    <td class="row-title"> <label for="tablecell"> <?php esc_attr_e( 'Data Source ', 'wpgsi' ); ?> </label> </td>
                                    <td>
                                        <select name="DataSourceID" id="DataSourceID" v-model="DataSourceID" @change="DataSourceFunc($event)" class="regular-text"  >
                                            <option value="" > <?php _e( 'select ...', 'wpgsi' ); ?> </option>
                                            <option :value="value" v-for="(name,value, index)  in DataSourceTitles" > {{name}} </option>
                                        </select>
                                    </td>
                                    <td></td>
                                </tr>
                                <tr class="alternate" >
                                    <td class="row-title">
                                        <label for="tablecell">
                                            <?php esc_attr_e( 'Spreadsheet & Worksheet ', 'wpgsi' ); ?>
                                        </label>
                                    </td>
                                    <td id="gData">
                                        <select name='SpreadsheetAndWorksheet' id='SpreadsheetAndWorksheetID' v-model="SpreadsheetWorksheet" @change="SpreadsheetAndWorksheetChange($event)" class="regular-text" >
                                            <option value=""  > <?php _e( 'select ...', 'wpgsi' ); ?> </option>
                                            <option :value="value" v-for="(name,value, index)  in DisplaySheets" > {{name}} </option>
                                        </select>
                                        <!-- Loading Starts -->
                                        <img  v-if="DisplayIcon"  style="vertical-align: middle;" src="<?php echo plugins_url( 'loading.gif', __FILE__ ); ?>" alt="loading...">
                                        <!-- Loading Ends -->
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                            <!-- Hidden Fields Starts -->
                            <input type="hidden" name="IntegrationTitle"    v-model="IntegrationTitle" >
                            <input type="hidden" name="DataSourceID"        v-model="DataSourceID" >
                            <input type="hidden" name="Worksheet"           v-model="Worksheet" >
                            <input type="hidden" name="WorksheetID"         v-model="WorksheetID" >
                            <input type="hidden" name="Spreadsheet"         v-model="Spreadsheet" >
                            <input type="hidden" name="SpreadsheetID"       v-model="SpreadsheetID" >
                            <!-- Hidden Fields Ends -->
                            <!-- Dynamic Table Starts  -->
                            <tbody  v-if="ok">
                                <!-- Spacer Starts -->
                                <tr style="background-color: #f1f1f1;">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <!-- Spacer Ends -->
                                <tr>
                                    <td class="row-title">  <label> Worksheet Column Title  </label> </td>
                                    <td class="row-title"> <label>  Column & Event relation  </label> </td>
                                    <td class="row-title">   <label>  Event Data  </label> </td>
                                </tr>
                                <tr v-for="(value, name, index)  in WorksheetColumnsTitle" :class=" index % 2 ? '' : 'alternate' " >
                                    <td> <label for="tablecell">  <b> {{ name }} : {{value}} </b>   </label> </td>
                                    <td class="eventOutputsHolderTD"> 
                                        <input type="text" v-bind:id="'Relation_' + name" :name="'Relation[' + name + ']'"  v-model="Relations[name]" class="regular-text" >
                                        <input type="hidden" v-bind:id="'ColumnTitle_' + name" :name="'ColumnTitle[' + name + ']'" :value="value" class="regular-text" >
                                    </td>
                                    <td>
                                        <span class="dashicons dashicons-arrow-left-alt" style="vertical-align: middle;"  ></span>
                                        <span  v-if="DataSourceID !== 'undefined' "  >
                                            <select  name="eventOutputs" class="eventOutputs" @change="eventOutputs($event , name )" class="regular-text" >
                                                <option value=""  >  <?php _e('select ...', 'wpgsi' ); ?>   </option>
                                                <option v-for="(dataName , dataIndex) in DataSourceFields[DataSourceID]" :value="dataIndex">{{ dataName }}<option>
                                            </select>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="row-title">  <label> Worksheet Column Title </label>  </td>
                                    <td class="row-title">  <label> Column & Event relation   </label> </td>
                                    <td class="row-title">  <label> Event Data </label> </td>
                                </tr>
                            </tbody>
                            <!-- Dynamic Table Ends  -->
                        </table>
                    </div>
                    <br class="clear">
                    <!-- Save integration Starts -->
                    <p v-if="ok">
                        <input class="button-primary" type="submit" name="saveRelation" value="<?php esc_attr_e( 'Save Relation', 'wpgsi' ); ?>" />
                        <a class="button-secondary" style="color: red" href="<?php echo admin_url('admin.php?page=wpgsi')?>" class="button-secondary"> <?php esc_attr_e( 'Cancel', 'wpgsi' ); ?></a>
                    </p>
                    <!-- Save integration Ends -->
                </form>
            </div>                         
        </div>
        <!-- #post-body .metabox-holder .columns-2 -->
        <br class="clear">
    </div>
    <!-- #poststuff -->

</div> <!-- .wrap -->
