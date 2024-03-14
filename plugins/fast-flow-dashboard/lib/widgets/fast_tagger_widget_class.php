<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//session_start();



		add_filter('ff_set_widgets','fast_tagger_register_widget',12,1);

		function fast_tagger_register_widget($widget){

			$widget[] = new Fast_Tagger_Widget();

			return $widget;

		}






class Fast_Tagger_Widget extends WP_Widget {



    /**
     *
     * Sets up the widgets name etc
     */


    public function __construct() {

        $widget_ops = array(

            'classname' => 'fast_tagger_widget',

            'description' => 'Tag Stats'

        );



        parent::__construct( 'fast_tagger_widget', 'Fast Tags', $widget_ops );


    }





    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */

    public function widget( $args, $instance ) {


        $widget_id = $args['widget_id'];

        if( $instance[ "format" ] == 'stats' ) {
            echo '<div class="ct-chart" id="'.$args["widget_id"].'"></div>';
        }
        else
        {
            echo '<div class="ct-chart ct-perfect-fourth" id="'.$args["widget_id"].'"></div>';
        }
		if ( $instance[ 'tags' ] != NULL ) {

            $tags = get_terms( array( 'taxonomy' => 'fast_tag', 'hide_empty' => false )  );

            $tags_id = explode( ',', $instance[ 'tags' ] );

               $index = 0;
               $x = [];
               $y = [];
               $color_arr = [] ;


            foreach ( $tags as $obj ) :

                $terms[] =  array( 'ID'=> $obj->term_id,'tag'=> $obj->name,'type'=> get_tag_parent_name($obj->term_id),'users'  => $obj->count );

                $tagName = $obj->name;
                $userCount = $obj->count;
                $termId = $obj->term_id;

                $color = get_term_meta($termId,'tag_color',true);

                if(!$color)
                {
                    $color = '';
                }

                if (in_array($termId, $tags_id)) {

                         $x[] = "'".$tagName."'";
                         $y[] = $userCount;
                         //$color_arr[] = "'".$tagName."':".$color."'";
                         $color_arr[] = "'".$index."':'".$color."'";
                         $color_list[] = "'".$color."'";;
                         $index++;
                }

            endforeach;


            $tagsName = join( ',', $x );
            $usersCount = join( ',', $y );
            $colorsArrStr =  join(',', $color_arr);
            $colorsListStr =  join(',', $color_list);


			echo "<script type='text/javascript'>

                    //console.log('{'+{$colorsArrStr}+'}'');

					if( '{$instance[ "format" ]}' == 'stats' ) {

					(function(){
                        var fts = new FastTaggerStats([{$tagsName}],[{$usersCount}],[{$colorsListStr}]);
                        fts.generateOutput('#{$args[ "widget_id" ]}');
                    })();

					} else if( '{$instance[ "format" ]}' == 'barchart' ) {

						var data = {

							  labels: [ {$tagsName} ],
							  series: [ {$usersCount} ]
						};

                        var options = {
                            axisX: {
                                showLabel: true,
                                showGrid: false,
                                  },
                              // Options for Y-Axis
                              axisY: {
                                showLabel: true,
                                // If the axis grid should be drawn or not
                                showGrid: true,
                                onlyInteger: true
                              },
                              distributeSeries: true
                            };


                        var chart_colors = {{$colorsArrStr}};


                        var charts = [];

						charts['{$args[ "widget_id" ]}'] = new Chartist.Bar( '#{$args[ "widget_id" ]}', data, options);


                        charts['{$args[ "widget_id" ]}'].on('draw', function(context) {

                                if(context.type === 'bar') {
                                    series_no = ''+context.seriesIndex;
                                    context.element.attr({
                                            style: 'stroke:'+chart_colors[series_no]
                                                        });
                                                          }
                        });
					} else if( '{$instance[ "format" ]}' == 'piechart' ) {

						                      var data = {

                              labels: [ {$tagsName} ],
                              series: [ {$usersCount} ]
                        };

                        var options = {
                              distributeSeries: true
                            };


                        var chart_colors = {{$colorsArrStr}};


                        var charts = [];

                        charts['{$args[ "widget_id" ]}'] = new Chartist.Pie( '#{$args[ "widget_id" ]}', data );

						charts['{$args[ "widget_id" ]}'].on('draw', function(context) {
                                if(context.type === 'slice') {
                                    series_no = ''+context.index;
                                    context.element.attr({
                                            style: 'fill:'+chart_colors[series_no]
                                                        });
                                                          }
                        });
					}else if( '{$instance[ "format" ]}' == 'gauge' ) {
						var chart = new Chartist.Pie('#{$args[ "widget_id" ]}', {
						  series: [{$usersCount}]
						}, {
							donut: true,
						  donutWidth: 60,
						  donutSolid: true,
						  startAngle: 270,
						  total: 500,
						  showLabel: true
						});
						var chartColors = {{$colorsArrStr}};
						chart.on('draw', function(data) {
						  if(data.type === 'slice') {
						    if (chartColors[data.index]) {
						      data.element._node.setAttribute('style','stroke: ' + chartColors[data.index]);
						    }
						 }
					 });

					}else if( '{$instance[ "format" ]}' == 'donut' ) {
						var chart = new Chartist.Pie('#{$args[ "widget_id" ]}', {
						  series: [{$usersCount}]
						}, {
							donut: true,
						  donutWidth: 60,
						  donutSolid: true,
						  startAngle: 270,
						  showLabel: true
						});
						var chartColors = {{$colorsArrStr}};
						chart.on('draw', function(data) {
						  if(data.type === 'slice') {
						    if (chartColors[data.index]) {
						      data.element._node.setAttribute('style','stroke: ' + chartColors[data.index]);
						    }
						 }
					 });

					}else{}

				</script>";

			//<------sudip
		}

    }





    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */

    public function form( $instance ) {

		//print "<pre>";print_r($instance);

        // outputs the options form on admin

        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );

        $description = ! empty( $instance['description'] ) ? $instance['description'] : __( 'Description', 'text_domain' );

		$period = ! empty( $instance['period'] ) ? $instance['period'] :'';

		$tags = ! empty( $instance['tags'] ) && ! is_array( $instance['tags'] ) ? explode(',',$instance['tags']):array();

		$format = ! empty( $instance['format'] ) ? $instance['format'] :'';

		//$ff_from = ! empty( $instance['ff_from'] ) ? $instance['ff_from'] :'';

		//$ff_to = ! empty( $instance['ff_to'] ) ? $instance['ff_to'] :'';

        ?>

            <p>

				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>

				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">

            </p>

			<p>

				<label for="<?php echo $this->get_field_name( 'description' ); ?>"><?php _e( 'Description:' ); ?></label>

				<textarea class="widefat" id="<?php echo $this->get_field_id( 'description' ); ?>" name="<?php echo $this->get_field_name( 'description' ); ?>" type="text" ><?php echo esc_attr( $description ); ?></textarea>

			</p>

			<!--p>

				<label for="<?php echo $this->get_field_id( 'period' ); ?>"><?php _e( 'Please set the period:' ); ?></label><br/>



				<label for="<?php echo $this->get_field_id( 'ff_from' ); ?>"><?php _e( 'From:' ); ?></label>

				<input class="ff_from" id="<?php echo $this->get_field_id( 'ff_from' ); ?>" name="<?php echo $this->get_field_name( 'ff_from' ); ?>" type="text" value="<?php echo esc_attr( $ff_from ); ?>">

				<label for="<?php echo $this->get_field_id( 'ff_to' ); ?>"><?php _e( 'To:' ); ?></label>

				<input class="ff_to" id="<?php echo $this->get_field_id( 'ff_to' ); ?>" name="<?php echo $this->get_field_name( 'ff_to' ); ?>" type="text" value="<?php echo esc_attr( $ff_to ); ?>"  >


                <?php /*

				<select class="widefat" id="<?php echo $this->get_field_id( 'period' ); ?>" name="<?php echo $this->get_field_name( 'period' ); ?>">

					<option <?php if($period == 'today'){ echo 'selected="selected"';}?> value="today">Today</option>

					<option <?php if($period == 'yesterday'){ echo 'selected="selected"';}?> value="yesterday">Yesterday</option>

					<option <?php if($period == '7days'){ echo 'selected="selected"';}?> value="7days">Last 7 days</option>

					<option <?php if($period == '90days'){ echo 'selected="selected"';}?> value="90days">Last 90 days</option>

					<option <?php if($period == '1year'){ echo 'selected="selected"';}?> value="1year">One Year</option>

					<option <?php if($period == '3years'){ echo 'selected="selected"';}?> value="3years">Three Years</option>

				</select>

				*/?>

			</p-->

			<p>

				<label for="<?php echo $this->get_field_id( 'tags' ); ?>"><?php _e( 'Users with this Tag/Tags:' ); ?></label>

				<?php $terms = get_terms( array( 'taxonomy' => 'fast_tag', 'hide_empty' => false ) );

				//print "<pre>"; print_r($terms);

                printf('<select class="widefat tags_field" id="%s" name="%s[]" multiple="multiple">',$this->get_field_id('tags'),$this->get_field_name('tags'));

					if ( ! empty( $terms ) && ! is_wp_error( $terms ) && is_array($terms) ){

                        foreach ( $terms as $term ) { ?>

							<option <?php if( in_array($term->term_id,$tags)){ echo 'selected="selected"';} ?> value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>

                <?php   }

                    }

				?>

				</select>

			</p>

			<p>

				<label for="<?php echo $this->get_field_id( 'format' ); ?>" ><?php _e('Data Format');?></label>

				<select class="widefat" id="<?php echo $this->get_field_id( 'format' ); ?>" name="<?php echo $this->get_field_name( 'format' ); ?>">

					<option <?php if($format == 'stats'){ echo 'selected=selected';}?> value="stats" >Stats </option>

					<option <?php if($format == 'barchart'){ echo 'selected=selected';}?> value="barchart" >Bar Chart</option>

					<option <?php if($format == 'piechart'){ echo 'selected=selected';}?> value="piechart" >Pie Chart</option>
					<option <?php if($format == 'gauge'){ echo 'selected=selected';}?> value="gauge" >Gauge</option>
					<option <?php if($format == 'donut'){ echo 'selected=selected';}?> value="donut" >Donut</option>

				</select>

			</p>

		<?php

    }





    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */

    public function update( $new_instance, $old_instance ) {

        // processes widget options to be saved



        foreach( $new_instance as $key => $value )

        {

			if($key == 'tags'){

				$updated_instance[$key] = implode(",",$value);

			}else{

				$updated_instance[$key] = sanitize_text_field($value);

			}

        }



        return $updated_instance;

    }

}
