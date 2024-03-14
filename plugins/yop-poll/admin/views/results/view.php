<div id="yop-main-area" class="bootstrap-yop wrap">
    <input type="hidden" name="_token" value="<?php echo esc_attr( wp_create_nonce( 'yop-poll-get-vote-details' ) ); ?>">
    <div id="icon-options-general" class="icon32"></div>
    <h1>
        <span class="glyphicon glyphicon-signal" style="margin-right:10px;"></span><?php esc_html_e( 'Poll results for', 'yop-poll' ); ?> <?php echo esc_html( $poll->name ); ?>
        <a href="
			<?php
			echo esc_url(
				add_query_arg(
					array(
						'page' => 'yop-polls',
						'action' => false,
						'poll_id' => false,
						'_token' => false,
						'order_by' => false,
						'sort_order' => false,
						'q' => false,
						'exportCustoms' => false,
					)
				)
			);
			?>
			" class="page-title-action">
            <?php esc_html_e( 'All Polls', 'yop-poll' ); ?>
        </a>
    </h1>
    <div class="container-fluid">
        <div class="row submenu" style="margin-top:30px; margin-bottom: 50px;">
            <div class="col-md-4">
                <a class="btn btn-link btn-block btn-underline">
                    <?php esc_html_e( 'Results', 'yop-poll' ); ?>
                </a>
            </div>
            <div class="col-md-4">
                <a href="
					<?php
					echo esc_url(
						add_query_arg(
							array(
								'page' => 'yop-polls',
								'action' => 'view-votes',
								'poll_id' => $poll->id,
								'_token' => false,
								'order_by' => false,
								'sort_order' => false,
								'q' => false,
								'exportCustoms' => false,
							)
						)
					);
					?>
					" class="btn btn-link btn-block">
                    <?php esc_html_e( 'View votes', 'yop-poll' ); ?>
                </a>
            </div>
            <div class="col-md-4"></div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel-group" id="accordion">
                    <?php
                    $custom_fields       = array();
                    $x = 0;
                    foreach ( $poll->elements as $element ) {
                        if ( ( 'text-question' === $element->etype ) || ( 'media-question' === $element->etype ) ) {
                            $answersArray = YOP_Poll_Helper::objectToArray( $element->answers );
                            usort(
								$answersArray,
								function( $a, $b ) {
                                	return $b['total_submits'] - $a['total_submits'];
                            	}
							);
                            $data = array();
                            $labels = array();
                            $resultsColors = array();
                            $icons = array();
                            foreach ( $answersArray as $a ) {
                                $data[] = $a['total_submits'];
                                if ( 0 == $a['author'] ) {
                                    $icons[] = 'other';
                                } else {
                                    $icons[] = '';
                                }
                                $resultsColors[] = $a['meta_data']['resultsColor'];
                                switch ( $a['stype'] ) {
                                    case 'text': {
                                        $labels[] = $a['stext'];
                                        break;
                                    }
                                    case 'image': {
                                        if ( 'yes' === $a['meta_data']['addText'] ) {
                                            $labels[] = $a['meta_data']['text'];
                                        } else {
                                            $labels[] = $a['stext'];
                                        }
                                        break;
                                    }
                                    case 'video': {
                                        if ( 'yes' === $a['meta_data']['addText'] ) {
                                            $labels[] = $a['meta_data']['text'];
                                        } else {
                                            $labels[] = $a['stext'];
                                        }
                                        break;
                                    }
                                }
                            }
                            if ( isset( $other_answers[$element->id] ) ) {
                                if ( count( $other_answers[$element->id] ) > 0 ) {
                                    $data[] = count( $other_answers[$element->id] );
                                    $labels[] = esc_html__( 'Other' );
                                    $resultsColors[] = '#000';
                                }
                            }
                            ?>
                            <div class="panel panel-default" id="panel<?php echo esc_attr( $element->id ); ?>">
                                <div class="panel-heading element-results-header">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-target="#collapse<?php echo esc_attr( $element->id ); ?>"
                                           href="#collapse<?php echo esc_attr( $element->id ); ?>">
                                            <?php echo esc_html( $element->etext ); ?>
                                        </a>
                                    </h4>
                                </div>
                                <div id="collapse<?php echo esc_attr( $element->id ); ?>" class="panel-collapse collapse in">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                        <label>
                                                            <?php esc_html_e( 'Answers', 'yop-poll' ); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                        <select class="answers-chart-type admin-select" id="answers-chart" style="width: 100%">
                                                            <option value="bar" selected><?php esc_html_e( 'Bar', 'yop-poll' ); ?></option>
                                                            <option value="pie"><?php esc_html_e( 'Pie', 'yop-poll' ); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="row">
                                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                                        <label>
                                                            <?php esc_html_e( 'Voters', 'yop-poll' ); ?>
                                                        </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-10">
                                                        <select class="voters-chart-type admin-select" id="voters-chart" style="width:100%">
                                                            <option value="bar" selected><?php esc_html_e( 'Bar', 'yop-poll' ); ?></option>
                                                            <option value="pie"><?php esc_html_e( 'Pie', 'yop-poll' ); ?></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" style="padding-top: 10px;">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <canvas id="chart<?php echo esc_attr( $element->id ); ?>" width="200" height="200" class="chart-canvas" data-cid="<?php echo esc_attr( $element->id ); ?>" data-jsondata='<?php echo json_encode( $data ); ?>' data-jsonlabels='<?php echo json_encode( $labels, JSON_HEX_APOS | JSON_HEX_QUOT ); ?>' style="display: none" data-resultscolor='<?php echo json_encode( $resultsColors ); ?>' data-icons='<?php echo json_encode( $icons ); ?>'></canvas>
                                                <div class="bar-chart-div">
                                                    <?php
                                                    $z = 0;
                                                    foreach ( $answersArray as $answer ) {
                                                        ?>
                                                        <?php
                                                        if ( 0 == $z && $answer['total_submits'] > 0 ) {
															echo '<span class="dashicons dashicons-awards"></span>&nbsp;';
														}
                                                        ?>
                                                        <?php
                                                        switch ( $answer['stype'] ) {
                                                            case 'text': {
                                                                if ( 0 == $answer['author'] ) {
                                                                    echo '<span class="glyphicon glyphicon-user"></span>&nbsp;&nbsp;';
                                                                }
                                                                echo '<label>' . esc_html( $answer['stext'] );
                                                                break;
                                                            }
                                                            case 'image': {
                                                                if ( 'yes' === $answer['meta_data']['addText'] ) {
                                                                    echo '<label>' . esc_html( $answer['meta_data']['text'] );
                                                                } else {
                                                                    ?>
                                                                    <div>
                                                                        <img src="<?php echo esc_html( $answer['stext'] ); ?>">
                                                                    </div>
                                                                    <?php
                                                                }
                                                                break;
                                                            }
                                                            case 'video': {
                                                                if ( 'yes' === $answer['meta_data']['addText'] ) {
                                                                    echo '<label>' . esc_html( $answer['meta_data']['text'] );
                                                                } else {
                                                                    ?>
                                                                    <div>
                                                                        <?php
                                                                        echo esc_html( $answer['stext'] );
                                                                        ?>
                                                                    </div>
                                                                    <?php
                                                                }
                                                                break;
                                                            }
                                                        }
                                                        if ( isset( $total_votes_per_question[$element->id] ) && $total_votes_per_question[$element->id] > 0 ) {
                                                            $percentage = $answer['total_submits'] / $total_votes_per_question[$element->id] * 100;
                                                            echo '&nbsp;';
                                                            echo esc_html( number_format( $percentage, 2, '.', '' ) . '%' . " ({$answer['total_submits']}" );
                                                            if ( $answer['total_submits'] > 1 || 0 == $answer['total_submits'] ) {
                                                                esc_html_e( ' votes', 'yop-poll' );
                                                            } else {
                                                                esc_html_e( ' vote', 'yop-poll' );
                                                            }
                                                            echo ')';
                                                        } else {
                                                            echo ' 0%';
                                                        }
                                                        echo '</label>';
                                                        ?>
                                                        <div class="progress" style="margin-bottom: 15px;">
                                                            <div class="progress-bar" role="progressbar" data-transitiongoal="<?php echo esc_attr( $answer['total_submits'] ); ?>" aria-valuemax="<?php if ( isset( $total_votes_per_question[$element->id] ) ) echo esc_attr( $total_votes_per_question[$element->id] ); else echo 0; ?>" style="border-radius: 4px;">
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $z++;
                                                    }
                                                    $total_others = 0;
                                                    foreach ( $other_answers as $key => $value ) {
                                                        if ( $key == $element->id ) {
                                                            $total_others += count( $value );
                                                        }
                                                    }
                                                    if ( $total_others > 0 ) {
                                                        echo '<label>';
                                                        esc_html_e( 'Other', 'yop-poll' );
                                                        if ( $total_votes_per_question[$element->id] > 0 ) {
                                                            $percentage = $total_others / $total_votes_per_question[$element->id] * 100;
                                                            echo '&nbsp;';
                                                            echo esc_html( number_format( $percentage, 2, '.', '' ) . '%' . " ({$total_others}" );
                                                            if ( $total_others > 1 || 0 == $total_others ) {
                                                                esc_html_e( ' votes', 'yop-poll' );
                                                            } else {
                                                                esc_html_e( ' vote', 'yop-poll' );
                                                            }
                                                            echo ')';
                                                        } else {
                                                            echo ' 0%';
                                                        }
                                                        echo '</label>';
                                                        ?>
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar" data-transitiongoal="<?php echo esc_attr( $total_others ); ?>" aria-valuemax="<?php if ( isset( $total_votes_per_question[$element->id] ) ) echo esc_attr( $total_votes_per_question[$element->id] ); else echo 0; ?>" style="border-radius: 4px;">
                                                            </div>
                                                        </div>
                                                        <?php
                                                    }
                                                    ?>

                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                                <div class="row">
                                                    <?php
                                                    $z = 0;
                                                    if ( isset( $total_voters_per_question[$element->id] ) ) {
                                                        $total_votes_for_question_array = $total_voters_per_question[$element->id];
                                                    } else {
                                                        $total_votes_for_question_array = [];
                                                    }
                                                    $total_votes_for_question = 0;
                                                    foreach ( $total_votes_for_question_array as $key => $value ) {
                                                        $total_votes_for_question += $value;
                                                    }
                                                    uasort(
														$total_votes_for_question_array,
														function( $a, $b ) {
                                                       	 return $b - $a;
                                                    	}
													);
                                                    $voters_data = array();
                                                    $voters_labels = array();
                                                    $voters_colors = array();
                                                    foreach ( $total_votes_for_question_array as $key => $value ) {
                                                        $voters_labels[] = $key;
                                                        $voters_data[]   = $value;
                                                        $voters_colors[] = '#000';
                                                    }
                                                    ?>
                                                    <canvas id="chartVoters<?php echo esc_attr( $element->id ); ?>" width="200" height="200" class="chart-voters-canvas" data-cid="<?php echo esc_attr( $element->id ); ?>" data-jsondata='<?php echo json_encode( $voters_data ); ?>' data-jsonlabels='<?php echo json_encode( $voters_labels ); ?>' style="display: none" data-resultscolor='<?php echo json_encode( $voters_colors ); ?>'></canvas>
                                                    <div class="bar-chart-voters-div">
                                                        <?php
                                                        foreach ( $total_votes_for_question_array as $key => $value ) {
                                                            ?>
                                                            <?php
															if ( 0 == $z ) {
																echo '<span class="dashicons dashicons-awards"></span>&nbsp;';
															}
															?>
                                                            <label>
                                                                <?php
                                                                echo esc_attr( $key );
                                                                ?>
                                                            </label>
                                                            <?php
                                                            echo '<label>';
                                                            if ( isset( $total_votes_for_question ) && $total_votes_for_question > 0 ) {
                                                                $percentage = $value / $total_votes_for_question * 100;
                                                                echo '&nbsp;';
                                                                echo esc_html( number_format( $percentage, 2, '.', '' ) . '%' . " ({$value}" );
                                                                if ( $total_votes_for_question > 1 || 0 == $total_votes_for_question ) {
                                                                    esc_html_e( ' votes', 'yop-poll' );
                                                                } else {
                                                                    esc_html_e( ' vote', 'yop-poll' );
                                                                }
                                                                echo ')';
                                                            } else {
                                                                echo ' 0%';
                                                            }
                                                            echo '</label>';
                                                            ?>
                                                            <div class="progress">
                                                                <div class="progress-bar" role="progressbar" data-transitiongoal="<?php echo esc_attr( $value ); ?>"
                                                                     aria-valuemax="<?php echo esc_attr( $total_votes_for_question ); ?>" style="border-radius: 4px;"></div>
                                                            </div>
                                                            <?php
                                                            $z++;
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $x++;
                        } elseif ( 'custom-field' === $element->etype ) {
                            $custom_fields[] = array(
								'id' => $element->id,
								'cftext' => $element->etext,
							);
                        }
                    }
                    if ( count( $custom_fields ) > 0 ) {
                        ?>
                        <div class="panel panel-default" id="panelCustomFields">
                            <div class="panel-heading element-results-header">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-target="#collapseCustomFields"
                                       href="#collapseCustomFields" <?php if ( 0 < $x ) echo 'class="collapsed"'; ?>>
                                        <?php esc_html_e( 'Custom fields', 'yop-poll' ); ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapseCustomFields" class="panel-collapse collapse <?php if ( 0 == $x ) echo 'in'; ?>">
                                <div class="panel-body">
                                    <form method="get">
                                        <input type="hidden" name="page" value="yop-polls">
                                        <input type="hidden" name="exportCustoms" value="true">
                                        <input type="hidden" name="action" value="results">
                                        <input type="hidden" name="poll_id" value="<?php echo esc_attr( $poll->id ); ?>">
                                        <button class="button" type="submit"><?php esc_html_e( 'Export', 'yop-poll' ); ?></button>
                                    </form>
                                    <table class="table" id="cf-table">
                                        <thead>
                                        <tr>
                                            <?php
                                            foreach ( $custom_fields as $cf ) {
                                                echo '<td>' . esc_html( $cf['cftext'] ) . '</td>';
                                            }
                                            ?>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php echo $cf_string; ?>
                                        </tbody>
                                    </table>
                                    <?php echo $cf_hidden; ?>
                                    <?php
                                    if ( $cf_total_pages > 1 ) {
                                        ?>
                                        <div id="cf-pagination"></div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
