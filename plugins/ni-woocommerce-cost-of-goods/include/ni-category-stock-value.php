<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
include_once('ni-cog-function.php');  
	if( !class_exists( 'Ni_Category_Stock_Value' ) ) {
		class Ni_Category_Stock_Value extends Ni_COG_Function{
			 private $ni_cost_goods;
  			function __construct(){
				 $ni_cog_meta_key = $this->get_cog_setting_by_key("ni_cog_meta_key",'_ni_cost_goods');
				 if(empty( $ni_cog_meta_key)){
					$ni_cog_meta_key = '_ni_cost_goods';	 
				 }
				 $this->ni_cost_goods = $ni_cog_meta_key;
				 
				 
				 
			}
			function page_init(){
			$product_category = 	$this->get_product_category();
				
			 $input_type="text";
		 	$input_type="hidden";	
			$today = date_i18n("Y-m-d");
			$sales_year =  $this->get_sales_year();
			//$this->print_data($sales_year);
			?>
            <div class="container-fluid">
            	<div id= 'niwoocog'>
                	<div class="row">
                    <div class="col">
                        <div class="card " style="max-width:50%">
                            <div class="card-header bd-indigo-400">
                                <?php esc_html_e("Product category stock value", 'wooreportcog'); ?>
                            </div>
                            <div class="card-body">
                                 <form name="frm_cog_report" id="frm_cog_report">
                                    <div class="row">
                                        <div class="col-3">
                                           <label for="selected_year"><?php esc_html_e('Category', 'wooreportcog'); ?></label>
                                        </div>
                                        <div class="col-3">
                                           <select name="product_category[]"  id="product_category[]" class="form-control" multiple="multiple" size="10">
                                        		<?php foreach($product_category  as $key=>$value): ?>
											  <option value="<?php esc_attr_e($value->category_id) ?>"><?php esc_html_e($value->category_name, 'wooreportcog'); ?></option>
                                              <?php endforeach; ?>
											 
										</select>
                                        <span style="font-size:12px">Hold down the control (ctrl) button to select multiple options</span>
                                        </div>
                                        
                                        
                                    </div>
                                    
                                   
                                    <div class="row">
                                    	<div  class="col" style="text-align:right">
                                        <input type="submit" value="<?php  esc_html_e("Search", 'wooreportcog'); ?>" class="btn bd-blue-500  mb-2" />
                                        </div>
                                    </div>
                                    
                                   <input type="<?php echo  $input_type; ?>" name="sub_action" value="ni_category_stock_value">
             						<input type="<?php echo  $input_type; ?>" name="action" value="ni_cog_action">
                                </form>
                                    
                            </div>
                        </div>
                    </div>
                	</div>
                	
                    
                    <div class="row" style="padding-top:20px;">
                    	<div class="col">
                        	<div class="ajax_cog_content"></div>
                        </div>
                    </div>
                    
                    
               		
           		</div>
            </div>
			 
			<?php	
			}
			function page_ajax(){
				$this->get_tables();
			}
			function get_query(){
				
				//$this->prettyPrint($_REQUEST);
				
				$product_category = isset($_REQUEST["product_category"])?$_REQUEST["product_category"]:array();
				
				global $wpdb;	
			$query ="";
			$query .= " SELECT
				t.name AS category_name,
				COUNT(p.ID) AS product_count,
				SUM(pm.meta_value) AS stock_count,
				SUM(pm.meta_value * pm2.meta_value) AS stock_value
			FROM
				{$wpdb->prefix}terms AS t
				JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id = tt.term_id
				JOIN {$wpdb->prefix}term_relationships AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
				JOIN {$wpdb->prefix}posts AS p ON tr.object_id = p.ID
				JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id AND pm.meta_key = '_stock'
				JOIN {$wpdb->prefix}postmeta AS pm2 ON p.ID = pm2.post_id AND pm2.meta_key = '_price'
			WHERE
				tt.taxonomy = 'product_cat'
				AND p.post_type = 'product'
				AND p.post_status = 'publish'";
				
				if (count($product_category)>0){
					$query .= " AND	t.term_id IN (" . "'" . implode ( "', '", $product_category ) . "'" .")";
				}
				
			$query .= " GROUP BY
				t.term_id, t.name
			ORDER BY
				category_name ASC;
			 ";
			 
			// echo $query;
			 
			$rows = $wpdb->get_results( $query);
			
			
			
			return $rows;	
			}
			
			function get_columns(){
				
				$column["category_name"]  = esc_html__("Category Name","wooreportcog");
				$column["product_count"] = esc_html__("Product Count","wooreportcog");
				$column["stock_count"] = esc_html__("Stock Count","wooreportcog");
				$column["stock_value"] = esc_html__("Stock Value","wooreportcog");
				
				
				return $column;
			}
			function get_product_category(){
				global $wpdb;	
				$query ="";
				$query .="SELECT DISTINCT
    t.term_id AS category_id,
    t.name AS category_name
FROM
    {$wpdb->prefix}terms AS t
    JOIN {$wpdb->prefix}term_taxonomy AS tt ON t.term_id = tt.term_id
    JOIN {$wpdb->prefix}term_relationships AS tr ON tt.term_taxonomy_id = tr.term_taxonomy_id
    JOIN {$wpdb->prefix}posts AS p ON tr.object_id = p.ID
WHERE
    tt.taxonomy = 'product_cat'
    AND p.post_type = 'product'
    AND p.post_status = 'publish'
	
	ORDER BY
				category_name ASC;


				";
				$rows = $wpdb->get_results( $query);
				
			//	$this->prettyPrint($rows );
				
				return $rows;
	
			}
			function get_tables(){
				$columns 		= $this->get_columns();
				$rows 			= $this->get_query();
				
				?>
                 <table class="table table-bordered table-striped table-hover">
               		 <thead class="bd-indigo-400">
                    	<tr>
                        	<?php foreach($columns  as $col_key=>$col_value):?>
                            	<th><?php echo $col_value; ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
               		<tbody>
					<?php foreach($rows  as $row_key=>$row_value):?>
                    	<tr>
                        		<?php foreach($columns  as $col_key=>$col_value):?>
                                	<?php switch($col_key): case 1: break; ?>
                                
                                		 <?php case "product_count": ?>
                                         <?php case "stock_count": ?>
                                         	   <td class="text-end"> <?php echo isset($row_value->$col_key)?$row_value->$col_key:'0'; ?>  </td>
                                         <?php break; ?>
                                       
                                         <?php case "stock_value": ?>
                                         	   <td class="text-end"> <?php echo wc_price( isset($row_value->$col_key)?$row_value->$col_key:'0'); ?>  </td>
                                         <?php break; ?>
                                         <?php default; ?>
                                         		<?php $year_month_key  = $col_key.'-'.$row_key; ?>		
                                            <td> <?php echo isset($row_value->$col_key)?$row_value->$col_key:''; ?>  </td>
                                	<?php endswitch; ?>	
                                
                                
                           	    <?php endforeach; ?>
                        </tr>    
                    <?php endforeach; ?>
              
               		</tbody>
                </table>
                <?php
			}
		}
	}
?>