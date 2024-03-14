<?php

class WootablepressControllerWtbp extends ControllerWtbp {

	protected $_code = 'wootablepress';

	protected function _prepareTextLikeSearch( $val ) {
		$query = '(title LIKE "%' . $val . '%"';
		if ( is_numeric( $val ) ) {
			$query .= ' OR id LIKE "%' . (int) $val . '%"';
		}
		$query .= ')';

		return $query;
	}

	public function _prepareListForTbl( $data ) {
		foreach ( $data as $key => $row ) {
			$id                        = $row['id'];
			$shortcode                 = '[' . WTBP_SHORTCODE . ' id=' . $id . ']';
			$titleUrl                  = '<a href=' . esc_url( $this->getModule()->getEditLink( $id ) ) . '>' . esc_html( $row['title'] ) . " <i class='fa fa-fw fa-pencil'></i></a>";
			$data[ $key ]['shortcode'] = $shortcode;
			$data[ $key ]['title']     = $titleUrl;
		}

		return $data;
	}

	public function getSearchProducts() {
		$params = ReqWtbp::get( 'post' );
		$html   = $this->getView()->getSearchProducts( $params );
		echo json_encode( $html );
		die();
	}

	public function getProductContent() {
		$res    = new ResponseWtbp();
		$params = ReqWtbp::get( 'post' );

		$frontend = ! empty( $params['frontend'] ) ? true : false;
		$settings = false;
		if ( $frontend && ! empty( $params['settings'] ) ) {
			parse_str( $params['settings'], $settings );
			unset( $params['settings'] );
		}

		$htmlAndIds = $this->getView()->getProductContentBackend( $params, $frontend, $settings );

		if ( ! empty( $htmlAndIds ) ) {
			$res->addMessage( esc_html__( 'Done', 'woo-product-tables' ) );
			$res->setHtml( $htmlAndIds['html'] );
			$total   = isset( $htmlAndIds['total'] ) ? $htmlAndIds['total'] : false;
			$notices = isset( $htmlAndIds['notices'] ) ? $htmlAndIds['notices'] : false;
			$res->addData( array( 'filter' => $htmlAndIds['filter'], 'css' => $htmlAndIds['css'], 'total' => $total, 'notices' => $notices ) );
			if ( ! empty( $params['prewiew'] ) ) {
				$res->addData( array( 'settings' => $htmlAndIds['settings'] ) );
			}
			if ( ! empty( $params['returnIds'] ) ) {
				$res->addData( array( 'ids' => $htmlAndIds['ids'] ) );
			}
		} else {
			$res->addMessage( esc_html__( 'Post not exist!', 'woo-product-tables' ) );
		}

		return $res->ajaxExec();
	}

	public function getProductContentPage() {
		$res    = new ResponseWtbp();
		$params = ReqWtbp::get( 'post' );

		$result = $this->getView()->getProductPage( $params );

		if ( ! empty( $result ) ) {
			$res->addMessage( esc_html__( 'Done', 'woo-product-tables' ) );
			$res->setHtml( $result['html'] );
			$res->recordsFiltered = $result['total'];
			$res->recordsTotal    = $result['total'];
			$res->draw            = $params['draw'];
			if ( isset( $result['ids'] ) ) {
				$res->ids = $result['ids'];
			}
		} else {
			$res->addMessage( esc_html__( 'Products not exist!', 'woo-product-tables' ) );
		}

		return $res->ajaxExec();
	}

	public function save() {
		check_ajax_referer( 'wtbp-save-nonce', 'wtbpNonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}
		$res  = new ResponseWtbp();
		$data = ReqWtbp::get( 'post' );
		if ( ! isset( $data['id'] ) && ! isset( $data['settings'] ) ) {
			$data['settings'] = array(
				'productids'               => $this->getView()->calcProductIds( $data, true ),
				'header_show'              => 1,
				'show_add_to_cart_message' => 1
			);
		}
		$id = $this->getModel( 'wootablepress' )->save( $data );
		if ( false != $id ) {
			$res->addMessage( esc_html__( 'Done', 'woo-product-tables' ) );
			$res->addData( 'edit_link', $this->getModule()->getEditLink( $id ) );
		} else {
			$res->pushError( $this->getModel( 'wootablepress' )->getErrors() );
		}

		return $res->ajaxExec();
	}

	public function cloneTable() {
		check_ajax_referer( 'wtbp-save-nonce', 'wtbpNonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}
		$res = new ResponseWtbp();
		$id  = $this->getModel( 'wootablepress' )->cloneTable( ReqWtbp::get( 'post' ) );
		if ( false != $id ) {
			$res->addMessage( esc_html__( 'Done', 'woo-product-tables' ) );
			$res->addData( 'edit_link', $this->getModule()->getEditLink( $id ) );
		} else {
			$res->pushError( $this->getModel( 'wootablepress' )->getErrors() );
		}

		return $res->ajaxExec();
	}

	public function deleteByID() {
		check_ajax_referer( 'wtbp-save-nonce', 'wtbpNonce' );
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die();
		}
		$res = new ResponseWtbp();

		if ( $this->getModel( 'wootablepress' )->delete( ReqWtbp::get( 'post' ) ) != false ) {
			$res->addMessage( esc_html__( 'Done', 'woo-product-tables' ) );
		} else {
			$res->pushError( $this->getModel( 'wootablepress' )->getErrors() );
		}

		return $res->ajaxExec();
	}

	public function createTable() {
		$res = new ResponseWtbp();
		$id  = $this->getModel( 'wootablepress' )->save( ReqWtbp::get( 'post' ) );
		if ( false != $id ) {
			$res->addMessage( esc_html__( 'Done', 'woo-product-tables' ) );
			$res->addData( 'edit_link', $this->getModule()->getEditLink( $id ) );
		} else {
			$res->pushError( $this->getModel( 'wootablepress' )->getErrors() );
		}

		return $res->ajaxExec();
	}

	public function multyProductAddToCart() {
		$res              = new ResponseWtbp();
		$params           = ReqWtbp::get( 'post' );
		$selectedProducts = isset( $params['selectedProduct'] ) ? $params['selectedProduct'] : 0;
		$productQuantity  = 0;

		if ( ! empty( $selectedProducts ) ) {
			foreach ( $selectedProducts as $selectedProduct ) {

				$quantity = ( '0' === $selectedProduct['quantity'] ) ? 1 : $selectedProduct['quantity'];

				if ( ! empty( $selectedProduct['id'] ) && ! empty( $quantity ) ) {
					global $woocommerce;
					$variation = empty( $selectedProduct['variation'] ) ? array() : $selectedProduct['variation'];
					$addData   = empty( $selectedProduct['addData'] ) ? array() : $selectedProduct['addData'];

					WC()->cart->add_to_cart( $selectedProduct['id'], $quantity, isset( $selectedProduct['varId'] ) ? $selectedProduct['varId'] : 0, $variation, $addData );
					$productQuantity += $quantity;
				}
			}
		} elseif ( empty( $params['alreadyInCart'] ) ) {
			$res->addMessage( esc_html__( 'Please select at least one product from table', 'woo-product-tables' ) );
		}

		if ( ! empty( $params['alreadyInCart'] ) ) {
			$productQuantity = $params['alreadyInCart'];
		}

		if ( $productQuantity ) {
			$res->addMessage( $productQuantity . ' ' . esc_html__( 'Product(s) added to the cart', 'woo-product-tables' ) );
		}

		$res->addData( 'added', $productQuantity );

		return $res->ajaxExec();
	}

	public function toggleFavorites() {
		$res     = new ResponseWtbp();
		$user_id = get_current_user_id();

		if ( 0 === $user_id ) {
			$res->pushError( esc_html__( 'user is not logged in' ) );
		} else {
			$params       = ReqWtbp::get( 'post' );
			$where        = "`user_id` = {$user_id} AND `from_order` IS NOT NULL";

			$favoritesFromOrderRaw = $this->getModel( 'favorites' )->addWhere( $where )->getFromTbl();

			if ( ! empty( $favoritesFromOrderRaw ) ) {
				foreach ( $favoritesFromOrderRaw as $product ) {
					$favoritesFromOrder[] = $product['product_id'];
				}
			} else {
				$favoritesFromOrder = array();
			}

			$data = array(
				'user_id'    => $user_id,
				'product_id' => $params['productId'],
			);

			if ( 'true' === $params['active'] ) {
				if ( in_array( $params['productId'], $favoritesFromOrder, true ) ) {
					$this->getModel( 'favorites' )->update( array( 'from_order' => - 1 ), $data );
				} else {
					$this->getModel( 'favorites' )->delete( $data );
				}
				$res->addData( 'active', false );
			} else {
				if ( in_array( $params['productId'], $favoritesFromOrder, true ) ) {
					$this->getModel( 'favorites' )->update( array( 'from_order' => 1 ), $data );
				} else {
					$this->getModel( 'favorites' )->insert( $data );
				}
				$res->addData( 'active', true );
			}

		}

		return $res->ajaxExec();
	}
}
