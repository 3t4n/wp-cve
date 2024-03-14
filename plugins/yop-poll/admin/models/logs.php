<?php
class YOP_Poll_Logs {
	private static $errors_present = false,
		$error_text,
		$sort_order_allowed = array( 'asc', 'desc' ),
		$order_by_allowed = array( 'name', 'user_id', 'email', 'user_type', 'ipaddress', 'added_date', 'vote_message' ),
		$logs_per_page = 20;
    private static $_instance = null;
    public static function get_instance() {
        if ( null === self::$_instance ) {
            $class           = __CLASS__;
            self::$_instance = new $class;
        }
        return self::$_instance;
    }
	public static function add( $vote, $has_errors, $message ) {
		if ( false === $has_errors ) {
			array_push( $message, 'success' );
		}
		$data = array(
			'poll_id' => sanitize_text_field( $vote->pollId ),
			'poll_author' => sanitize_text_field( $vote->pollAuthor ),
			'user_id' => sanitize_text_field( $vote->user->id ),
			'user_email' => sanitize_text_field( $vote->user->email ),
			'user_type' => sanitize_text_field( $vote->user->type ),
			'ipaddress' => sanitize_text_field( $vote->user->ipaddress ),
			'tracking_id' => sanitize_text_field( $vote->trackingId ),
			'voter_id' => sanitize_text_field( $vote->user->c_data ),
			'voter_fingerprint' => sanitize_text_field( $vote->user->f_data ),
			'vote_data' => serialize( YOP_Poll_Votes::create_meta_data( $vote ) ),
			'vote_message' => serialize( $message ),
			'added_date' => sanitize_text_field( $vote->added_date ),
		);
		$GLOBALS['wpdb']->insert( $GLOBALS['wpdb']->yop_poll_logs, $data );
	}
	public static function paginate( $params ) {
		$total_pages = 0;
		$query = '';
		$total_logs = 0;
		$current_user = wp_get_current_user();
		if ( current_user_can( 'yop_poll_results_others' ) ) {
			$query = "SELECT COUNT(*) FROM `{$GLOBALS['wpdb']->yop_poll_logs}` INNER JOIN `{$GLOBALS['wpdb']->yop_poll_polls}` ON ";
			$query .= "`{$GLOBALS['wpdb']->yop_poll_logs}`.`poll_id` = `{$GLOBALS['wpdb']->yop_poll_polls}`.`id`";
			if ( isset( $params['q'] ) && ( '' !== $params['q'] ) ) {
				$search_string = '%' . $GLOBALS['wpdb']->esc_like( $params['q'] ) . '%';
				$query .= ' WHERE `user_email` LIKE %s';
				$query .= ' OR `ipaddress` LIKE %s';
                $query .= ' OR `name` LIKE %s';
                $query = $GLOBALS['wpdb']->prepare(
                    $query,
                    $search_string,
                    $search_string,
                    $search_string
                );
			}
		} else if ( current_user_can( 'yop_poll_results_own' ) ) {
			$query = "SELECT COUNT(*) FROM `{$GLOBALS['wpdb']->yop_poll_logs}` INNER JOIN `{$GLOBALS['wpdb']->yop_poll_polls}` ON ";
			$query .= "`{$GLOBALS['wpdb']->yop_poll_logs}`.`poll_id` = `{$GLOBALS['wpdb']->yop_poll_polls}`.`id`";
            $query .= ' WHERE `author` = %s';
            $query = $GLOBALS['wpdb']->prepare(
                $query,
                $current_user->ID
            );
			if ( isset( $params['q'] ) && ( '' !== $params['q'] ) ) {
                $search_string = '%' . esc_sql( $GLOBALS['wpdb']->esc_like( $params['q'] ) ) . '%';
                $query .= ' AND (`user_email` LIKE %s';
                $query .= ' or `ipaddress` LIKE %s';
                $query .= ' or `name` LIKE %s)';
                $query = $GLOBALS['wpdb']->prepare(
                    $query,
                    $search_string,
                    $search_string,
                    $search_string
                );
			}
		}
		if ( '' !== $query ) {
			$total_logs = $GLOBALS['wpdb']->get_var( $query );
		}
		if ( $total_logs > 0 ) {
			if ( $total_logs <= self::$logs_per_page ) {
				$data['pagination'] = '';
				$page = 1;
				$total_pages = 1;
			} else {
				$total_pages = intval( ceil( $total_logs / self::$logs_per_page ) );
			}
		} else {
			$data['pagination'] = '';
		}
		if ( $total_pages > 1 ) {
			$pagination['first_page'] = '<span class="tablenav-pages-navspan" aria-hidden="true">
							«
						  </span>';
			$pagination['previous_page'] = '<span class="screen-reader-text">
								' . esc_html__( 'Previous page', 'yop-poll' ) . '
							</span>
							<span class="tablenav-pages-navspan" aria-hidden="true">
								‹
							</span>';
			$pagination['next_page'] = '<span class="screen-reader-text">' . esc_html__( 'Next page', 'yop-poll' ) . '
							</span>
							<span aria-hidden="true">›</span>';
			$pagination['last_page'] = '<span class="tablenav-pages-navspan" aria-hidden="true">
							»
							</span>';
			if ( 1 === intval( $params['page_no'] ) ) {
				//we're on the first page.
				$links['next_page'] = esc_url(
					add_query_arg(
						array(
							'action' => false,
							'ban_id' => false,
							'_token' => false,
							'order_by' => $params['order_by'],
							'sort_order' => $params['sort_order'],
							'q' => ( isset( $params['q'] ) && ( '' != $params['q'] ) ) ? $params['q'] : false,
							'page_no' => $params['page_no'] + 1,
						)
					)
				);
				$pagination['next_page'] = "<a
										class=\"next-page\"
										href=\"{$links['next_page']}\">{$pagination['next_page']}</a>";
				if ( 2 < intval( $total_pages ) ) {
					$links['last_page'] = esc_url(
						add_query_arg(
							array(
								'action' => false,
								'ban_id' => false,
								'_token' => false,
								'order_by' => $params['order_by'],
								'sort_order' => $params['sort_order'],
								'q' => ( isset( $params['q'] ) && ( '' != $params['q'] ) ) ? $params['q'] : false,
								'page_no' => intval( $total_pages ),
							)
						)
					);
					$pagination['last_page'] = "<a
												class=\"last-page\"
												href=\"{$links['last_page']}\">{$pagination['last_page']}</a>";
				}
			} else if ( intval( $params['page_no'] ) === intval( $total_pages ) ) {
				//we're on the last page
				$links['previous_page'] = esc_url(
					add_query_arg(
						array(
							'action' => false,
							'ban_id' => false,
							'_token' => false,
							'order_by' => $params['order_by'],
							'sort_order' => $params['sort_order'],
							'q' => ( isset( $params['q'] ) && ( '' != $params['q'] ) ) ? $params['q'] : false,
							'page_no' => $params['page_no'] - 1,
						)
					)
				);
				$pagination['previous_page'] = "<a
											class=\"prev-page\"
											href=\"{$links['previous_page']}\">{$pagination['previous_page']}</a>";
				if ( 2 < intval( $total_pages ) ) {
					$links['first_page'] = esc_url(
						add_query_arg(
							array(
								'action' => false,
								'ban_id' => false,
								'_token' => false,
								'order_by' => $params['order_by'],
								'sort_order' => $params['sort_order'],
								'q' => ( isset( $params['q'] ) && ( '' != $params['q'] ) ) ? $params['q'] : false,
								'page_no' => 1,
							)
						)
					);
					$pagination['first_page'] = "<a
												class=\"first-page\"
												href=\"{$links['first_page']}\">{$pagination['first_page']}</a>";
				}
			} else {
				//we're on an intermediary page
				$links['previous_page'] = esc_url(
					add_query_arg(
						array(
							'action' => false,
							'ban_id' => false,
							'_token' => false,
							'order_by' => $params['order_by'],
							'sort_order' => $params['sort_order'],
							'q' => ( isset( $params['q'] ) && ( '' != $params['q'] ) ) ? $params['q'] : false,
							'page_no' => $params['page_no'] - 1,
						)
					)
				);
				$links['next_page'] = esc_url(
					add_query_arg(
						array(
							'action' => false,
							'ban_id' => false,
							'_token' => false,
							'order_by' => $params['order_by'],
							'sort_order' => $params['sort_order'],
							'q' => ( isset( $params['q'] ) && ( '' != $params['q'] ) ) ? $params['q'] : false,
							'page_no' => $params['page_no'] + 1,
						)
					)
				);
				$pagination['previous_page'] = "<a
											class=\"prev-page\"
											href=\"{$links['previous_page']}\">{$pagination['previous_page']}</a>";
				$pagination['next_page'] = "<a
											class=\"prev-page\"
											href=\"{$links['next_page']}\">{$pagination['next_page']}</a>";
				if ( 2 < intval( $params['page_no'] ) ) {
					$links['first_page'] = esc_url(
						add_query_arg(
							array(
								'action' => false,
								'ban_id' => false,
								'_token' => false,
								'order_by' => $params['order_by'],
								'sort_order' => $params['sort_order'],
								'q' => ( isset( $params['q'] ) && ( '' != $params['q'] ) ) ? $params['q'] : false,
								'page_no' => 1,
							)
						)
					);
					$pagination['first_page'] = "<a
												class=\"first-page\"
												href=\"{$links['first_page']}\">{$pagination['first_page']}</a>";
				}
				if ( ( intval( $params['page_no'] + 2 ) ) <= $total_pages ) {
					$links['last_page'] = esc_url(
						add_query_arg(
							array(
								'action' => false,
								'ban_id' => false,
								'_token' => false,
								'order_by' => $params['order_by'],
								'sort_order' => $params['sort_order'],
								'q' => ( isset( $params['q'] ) && ( '' != $params['q'] ) ) ? $params['q'] : false,
								'page_no' => intval( $total_pages ),
							)
						)
					);
					$pagination['last_page'] = "<a
												class=\"last-page\"
												href=\"{$links['last_page']}\">{$pagination['last_page']}</a>";
				}
			}
		} else {
			$pagination['first_page'] = '';
			$pagination['previous_page'] = '';
			$pagination['next_page'] = '';
			$pagination['last_page'] = '';
		}
		return array(
			'total_logs' => $total_logs,
			'total_pages' => $total_pages,
			'pagination' => $pagination,
		);
	}
	public static function get_logs( $params ) {
		$query = '';
		$logs = array();
		$order_by = array();
		$current_user = wp_get_current_user();
		if ( 0 >= intval( $params['page_no'] ) ) {
			$params['page_no'] = 1;
		}
		$pagination = self::paginate( $params );
		if ( ! in_array( $params['sort_order'], self::$sort_order_allowed ) ) {
			$params['sort_order'] = SORT_ASC;
		} elseif ( 'desc' === $params['sort_order'] ) {
			$params['sort_order'] = SORT_DESC;
		} else {
			$params['sort_order'] = SORT_ASC;
		}
		if ( ! in_array( $params['order_by'], self::$order_by_allowed ) ) {
			$params['order_by'] = 'id';
		}
		if ( $params['page_no'] > $pagination['total_pages'] ) {
			$params['page_no'] = 1;
		}
		$limit = self::$logs_per_page * ( $params['page_no'] - 1 );
		$limit_query = " LIMIT {$limit}, " . self::$logs_per_page;
		if ( current_user_can( 'yop_poll_results_others' ) ) {
			$query = "SELECT logs.*, polls.name FROM {$GLOBALS['wpdb']->yop_poll_logs}"
			        . " as logs LEFT JOIN {$GLOBALS['wpdb']->yop_poll_polls} as polls"
			        . ' ON logs.`poll_id` = polls.`id`';
			if ( isset( $params['q'] ) && ( '' !== $params['q'] ) ) {
				$params['q'] = '%' . $GLOBALS['wpdb']->esc_like( $params['q'] ) . '%';
				$query .= ' WHERE `ipaddress` LIKE %s';
                $query .= ' OR `user_email` LIKE %s';
                $query .= ' OR `name` LIKE %s';
                $query = $GLOBALS['wpdb']->prepare(
                    $query,
                    $params['q'],
                    $params['q'],
                    $params['q']
                );
			}
		} else if ( current_user_can( 'yop_poll_results_own' ) ) {
			$query = "SELECT logs.*, polls.name FROM {$GLOBALS['wpdb']->yop_poll_logs} as logs LEFT JOIN {$GLOBALS['wpdb']->yop_poll_polls} as polls"
			         . " ON {$GLOBALS['wpdb']->yop_poll_logs}.`poll_id` = {$GLOBALS['wpdb']->yop_poll_polls}.`id`"
			         . " WHERE {$GLOBALS['wpdb']->yop_poll_logs}.`poll_author` = %s";
            $query = $GLOBALS['wpdb']->prepapre(
                $query,
                $current_user->ID
            );
			if ( isset( $params['q'] ) && ( '' !== $params['q'] ) ) {
				$params['q'] = '%' . $GLOBALS['wpdb']->esc_like( $params['q'] ) . '%';
                $query .= ' AND (`ipaddress` LIKE %s';
                $query .= ' OR `user_email` LIKE %s';
                $query .= ' OR `name` LIKE %s)';
                $query = $GLOBALS['wpdb']->prepapre(
                    $query,
                    $params['q'],
                    $params['q'],
                    $params['q']
                );
			}
		}
		if ( '' !== $query ) {
            $query .= ' LIMIT %d, %d';
            $query = $GLOBALS['wpdb']->prepare(
                $query,
                $limit,
                self::$logs_per_page
            );
			$logs = $GLOBALS['wpdb']->get_results( $query, ARRAY_A );
            foreach ( $logs as $key => $row ) {
                $log_message = unserialize( $row['vote_message'] );
                $order_by['id'][$key] = $row['id'];
                $order_by['name'][$key] = $row['name'];
                if ( 'wordpress' === $row['user_type'] ) {
                    $log_user_obj = get_user_by( 'id', $row['user_id'] );
                    $order_by['user_id'][$key] = $log_user_obj->user_login;
                    $logs[$key]['user_id'] = $log_user_obj->user_login;
                } else {
                    $order_by['user_id'][$key] = '';
                    $logs[$key]['user_id'] = '';
                }
                $order_by['email'][$key] = $row['user_email'];
                $order_by['user_type'][$key] = $row['user_type'];
                $order_by['ipaddress'][$key] = $row['ipaddress'];
                $order_by['added_date'][$key] = $row['added_date'];
                $order_by['vote_message'][$key] = $log_message[0];
                $logs[$key]['vote_message'] = $log_message[0];
            }
		}
		if ( count( $logs ) > 0 ) {
			array_multisort( $order_by[$params['order_by']], $params['sort_order'], $logs );
		}
		return array(
			'logs' => $logs,
			'total_logs' => $pagination['total_logs'],
			'total_pages' => $pagination['total_pages'],
			'pagination' => $pagination['pagination'],
		);
	}
	public static function get_export_logs( $params ) {
        $query = '';
        $logs = '';
		$current_user = wp_get_current_user();
        if ( current_user_can( 'yop_poll_results_others' ) ) {
            $query = "SELECT logs.*, polls.name FROM {$GLOBALS['wpdb']->yop_poll_logs}"
                . " as logs LEFT JOIN {$GLOBALS['wpdb']->yop_poll_polls} as polls"
                . ' ON logs.`poll_id` = polls.`id`';
            if ( isset( $params['q'] ) && ( '' !== $params['q'] ) ) {
                $params['q'] = '%' . $GLOBALS['wpdb']->esc_like( $params['q'] ) . '%';
                $query .= ' WHERE `ipaddress` LIKE %s';
                $query .= ' OR `user_email` LIKE %s';
                $query .= ' OR `name` LIKE %s';
                $query = $GLOBALS['wpdb']->prepare(
                    $query,
                    $params['q'],
                    $params['q'],
                    $params['q']
                );
            }
        } else if ( current_user_can( 'yop_poll_results_own' ) ) {
            $query = "SELECT logs.*, polls.name FROM {$GLOBALS['wpdb']->yop_poll_logs} as logs LEFT JOIN {$GLOBALS['wpdb']->yop_poll_polls} as polls"
                . " ON {$GLOBALS['wpdb']->yop_poll_logs}.`poll_id` = {$GLOBALS['wpdb']->yop_poll_polls}.`id`"
                . " WHERE {$GLOBALS['wpdb']->yop_poll_logs}.`poll_author` = %s";
            $query = $GLOBALS['wpdb']->prepare(
                $query,
                $current_user->ID
            );
            if ( isset( $params['q'] ) && ( '' !== $params['q'] ) ) {
                $params['q'] = '%' . $GLOBALS['wpdb']->esc_like( $params['q'] ) . '%';
                $query .= ' AND (`ipaddress` LIKE %s';
                $query .= ' OR `user_email` LIKE %s';
                $query .= ' OR `name` LIKE %s';
                $query = $GLOBALS['wpdb']->prepare(
                    $query,
                    $params['q'],
                    $params['q'],
                    $params['q']
                );
            }
        }
        if ( '' !== $query ) {
            $logs = $GLOBALS['wpdb']->get_results( $query, ARRAY_A );
        }
        foreach ( $logs as $key => $row ) {
            $log_message = unserialize( $row['vote_message'] );
            $order_by['id'][ $key ] = $row['id'];
            $order_by['name'][ $key ] = $row['name'];
            if ( 'wordpress' === $row['user_type'] ) {
                $log_user_obj = get_user_by( 'id', $row['user_id'] );
                $order_by['user_id'][ $key ] = $log_user_obj->user_login;
                $logs[ $key ]['user_id'] = $log_user_obj->user_login;
            } else {
                $order_by['user_id'][ $key ] = '';
                $logs[ $key ]['user_id'] = '';
            }
            $order_by['email'][ $key ] = $row['user_email'];
            $order_by['user_type'][ $key ] = $row['user_type'];
            $order_by['ipaddress'][ $key ] = $row['ipaddress'];
            $order_by['added_date'][ $key ] = $row['added_date'];
            $order_by['vote_message'][ $key ] = $log_message[0];
            $logs[ $key ]['vote_message'] = $log_message[0];
        }
        return $logs;
    }
    public static function send_logs_to_download() {
        $date_format = get_option( 'date_format' );
        $time_format = get_option( 'time_format' );
        if ( isset( $_REQUEST ['doExport'] ) && 'true' === $_REQUEST['doExport'] ) {
            $params['q'] = isset( $_REQUEST['sterm'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['sterm'] ) ) : '';
            $logs = self::get_export_logs( $params );
            $csv_file_name    = 'logs_export.' . date( 'YmdHis' ) . '.csv';
            $csv_header_array = array(
                esc_html__( 'Poll Name', 'yop-poll' ),
                esc_html__( 'Username', 'yop-poll' ),
                esc_html__( 'Email', 'yop-poll' ),
                esc_html__( 'User Type', 'yop-poll' ),
                esc_html__( 'IP', 'yop-poll' ),
                esc_html__( 'Date', 'yop-poll' ),
                esc_html__( 'Message', 'yop-poll' ),
                esc_html__( 'Vote data', 'yop-poll' ),
			);
            header( 'Content-Type: text/csv' );
            header( 'Cache-Control: must-revalidate, post-check=0,pre-check=0' );
            header( "Content-Transfer-Encoding: binary\n" );
            header( 'Content-Disposition: attachment; filename="' . $csv_file_name . '"' );
            header( 'Content-Transfer-Encoding: binary' );
            header( 'Connection: Keep-Alive' );
            header( 'Expires: 0' );
            ob_start();
            $f = fopen( 'php://output', 'w' ) or show_error( esc_html__( "Can't open php://output!", 'yop-poll' ) );
            if ( ! YOP_Poll_Helper::yop_fputcsv( $f, $csv_header_array ) ) {
                esc_html_e( "Can't write header!", 'yop-poll' );
            }
            $logs_for_csv = [];
            if ( count( $logs ) > 0 ) {
                 foreach ( $logs as $log ) {
                     $log_details = self::get_log_details( $log['id'] );
                     $details_string = '';
                     foreach ( $log_details as $res ) {
                         if ( 'custom-field' === $res['question'] ) {
                             $details_string .= esc_html__( 'Custom Field', 'yop-poll' ) . ': ' . $res['caption'] . ';';
                             $details_string .= esc_html__( 'Answer', 'yop-poll' ) . ': ' . $res['answers'][0]['answer_value'] . ';';
                         } else {
                             $details_string .= esc_html( 'Question', 'yop-poll' ) . ': ' . $res['question'] . ';';
                             foreach ( $res['answers'] as $ra ) {
                                 $details_string .= esc_html__( 'Answer', 'yop-poll' ) . ': ' . $ra['answer_value'] . ';';
                             }
                         }
                     }
                     $logs_data = array(
                         $log ['name'],
                         $log['user_id'],
                         $log ['user_email'],
                         $log ['user_type'],
                         $log ['ipaddress'],
                         date( $date_format . ' @ ' . $time_format, strtotime( $log['added_date'] ) ),
                         $log['vote_message'],
                         $details_string,
					 );
                     $logs_for_csv[] = $logs_data;
                     if ( ! YOP_Poll_Helper::yop_fputcsv( $f, $logs_data, ',', '"' ) ) {
                        esc_html_e( "Can't write logs!", 'yop-poll' );
                     }
                 }
             }
            fclose( $f ) or show_error( esc_html__( "Can't close php://output!", 'yop-poll' ) );
            $csvStr = ob_get_contents();
            ob_end_clean();
            echo wp_kses( $csvStr, array() );
            exit();
        }
    }
    public static function get_owner( $log_id ) {
        $query = $GLOBALS['wpdb']->prepare(
            "SELECT * FROM {$GLOBALS['wpdb']->yop_poll_logs} WHERE `id` = %s",
			$log_id
        );
        $log = $GLOBALS['wpdb']->get_row( $query, OBJECT );
        if ( null !== $log ) {
            return $log->poll_author;
        } else {
            return false;
        }
    }
    public static function delete( $log_id ) {
        $delete_log_result = $GLOBALS['wpdb']->delete(
            $GLOBALS['wpdb']->yop_poll_logs,
            array(
                'id' => $log_id,
            )
        );
        if ( false !== $delete_log_result ) {
            self::$errors_present = false;
        } else {
            self::$errors_present = true;
            self::$error_text = esc_html__( 'Error deleting log', 'yop-poll' );
        }
        return array(
            'success' => ! self::$errors_present,
            'error' => self::$error_text,
        );
    }
    public static function get_log_details( $log_id ) {
        $query = $GLOBALS['wpdb']->prepare(
            "SELECT * FROM {$GLOBALS['wpdb']->yop_poll_logs} WHERE `id` = %d",
			$log_id
        );
        $log = $GLOBALS['wpdb']->get_row( $query, OBJECT );
        if ( null !== $log ) {
            $vote_data = unserialize( $log->vote_data );
            if ( count( $vote_data ) > 0 ) {
                if ( isset( $vote_data['elements'] ) ) {
                    $vote_elements = $vote_data['elements'];
                    $questions          = array();
                    $questions_ids      = array();
                    $answers_ids        = array();
                    $questions_results  = array();
                    $answers_results    = array();
                    if ( count( $vote_elements ) > 0 ) {
                        foreach ( $vote_elements as $ve ) {
                            $qanswers = array();
                            if ( isset( $ve['id'] ) ) {
                                $questions_ids[] = $ve['id'];
                            }
                            if ( isset( $ve['data'] ) ) {
                                foreach ( $ve['data'] as $vdata ) {
                                    if ( isset( $vdata['id'] ) ) {
                                        if ( 0 != $vdata['id'] ) {
                                            $answers_ids[] = $vdata['id'];
                                        }
                                    }
                                }
                            }
                        }
                        if ( count( $questions_ids ) > 0 ) {
                            $question_ids_escaped = array_map(
								function( $question_id ) {
                                	return "'" . esc_sql( $question_id ) . "'";
                            	},
								$questions_ids
							);
                            $questions_ids_string = '(' . implode( ',', $question_ids_escaped ) . ')';
                            $questions_query = "SELECT * FROM {$GLOBALS['wpdb']->yop_poll_elements} where `id` IN $questions_ids_string";
                            $questions_results = $GLOBALS['wpdb']->get_results( $questions_query, OBJECT );
                        }
                        if ( count( $answers_ids ) > 0 ) {
                            $answers_ids_escaped = array_map(
								function( $answer_id ) {
                                	return "'" . esc_sql( $answer_id ) . "'";
                            	},
								$answers_ids
							);
                            $answers_ids_string = '(' . implode( ',', $answers_ids_escaped ) . ')';
                            $answers_query = "SELECT * FROM {$GLOBALS['wpdb']->yop_poll_subelements} where `id` IN $answers_ids_string";
                            $answers_results = $GLOBALS['wpdb']->get_results( $answers_query, OBJECT );
                        }
                        foreach ( $vote_elements as $ve ) {
                                $pqa = array(
									'question' => '',
									'answers' => array(),
								);
                                switch ( $ve['type'] ) {
                                    case 'question': {
                                        if ( isset( $ve['id'] ) ) {
                                            foreach ( $questions_results as $qres ) {
                                                if ( $ve['id'] == $qres->id ) {
                                                    $pqa['question'] = $qres->etext;
                                                }
                                            }
                                        }
                                        if ( isset( $ve['data'] ) ) {
                                            foreach ( $ve['data'] as $vdata ) {
                                                if ( 0 == $vdata['id'] ) {
                                                    $pqa['answers'][] = array(
														'answer_text' => 'other',
														'answer_value' => $vdata['data'],
													);
                                                } else {
                                                    foreach ( $answers_results as $ares ) {
                                                        if ( $vdata['id'] == $ares->id ) {
                                                            $pqa['answers'][] = array(
																'answer_text' => '',
																'answer_value' => $ares->stext,
															);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                        $questions[] = $pqa;
                                        break;
                                    }
                                    case 'custom-field': {
                                        if ( isset( $ve['id'] ) ) {
                                            foreach ( $questions_results as $qres ) {
                                                if ( $ve['id'] == $qres->id ) {
                                                    $pqa['question'] = 'custom-field';
                                                    $pqa['caption'] = $qres->etext;
                                                }
                                            }
                                        }
                                        if ( isset( $ve['data'] ) ) {
                                            $pqa['answers'][] = array(
												'answer_text' => 'custom-field',
												'answer_value' => $ve['data'][0],
											);
                                        }
                                        $questions[] = $pqa;
                                        break;
                                    }
                                }
                        }
                        return $questions;
                    } else {
                        return array();
                    }
                } else {
                    return array();
                }
            }
        } else {
            return array();
        }
	}
	public static function delete_all_for_poll( $poll_id ) {
		$delete_log_result = $GLOBALS['wpdb']->delete(
            $GLOBALS['wpdb']->yop_poll_logs,
            array(
                'poll_id' => $poll_id,
            )
        );
        if ( false !== $delete_log_result ) {
            self::$errors_present = false;
        } else {
            self::$errors_present = true;
            self::$error_text = esc_html__( 'Error deleting logs', 'yop-poll' );
        }
        return array(
            'success' => ! self::$errors_present,
            'error' => self::$error_text,
        );
	}
}
