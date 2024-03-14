<?php 


/**
 * Adds Book_Press_Widget widget.
 */
class Book_Press_Pro_Widget extends WP_Widget {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'book_press_pro_widget', // Base ID
			esc_html__( 'BookPress Premium (Books)', 'text_domain' ), // Name
			array( 'description' => esc_html__( 'A Book Press Pro Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$hide_name = ! empty( $instance['hide_name'] ) ? $instance['hide_name'] : null;
		$hide_author = ! empty( $instance['hide_author'] ) ? $instance['hide_author'] : null;
		$hide_cover = ! empty( $instance['hide_cover'] ) ? $instance['hide_cover'] : null;
		$hide_url = ! empty( $instance['hide_url'] ) ? $instance['hide_url'] : null;
		$summery = ! empty( $instance['summery'] ) ? $instance['summery'] : null;


if($summery){
	$summery = explode('|', $summery);
} else {
	$summery = array();
}


		if(isset($instance['book_id'])) {




			$book_ids = $instance['book_id'];
$keycou = 0;
foreach ($book_ids as $key => $book_id) {




			$book = get_post($book_id);
			if($book) {

				$thumbnail = null;

				$argsb = array(
					'post_parent' => $book_id,
					'post_type'   => 'book', 
					'numberposts' => -1,
					'post_status' => 'any',
					'orderby' => 'menu_order',
					'order' => 'ASC',
				);
				$sections = get_children( $argsb );
				foreach ($sections as $key => $section) {
					if($section->post_title==='Cover Matter') {
						$argsb = array(
							'post_parent' => $section->ID,
							'post_type'   => 'book', 
							'numberposts' => -1,
							'post_status' => 'any',
							'orderby' => 'menu_order',
							'order' => 'ASC',
						);
						$elements = get_children( $argsb );
						foreach ($elements as $key => $element) {
							if($element->post_title==='Cover Image'){
								$thumbnail = get_the_post_thumbnail_url($element->ID, 'full');
							}
						}
					}
				}

		?>


<table>

	<tr>
		<td valign="top">
		<?php if(!$hide_cover) { ?>
			<?php if($thumbnail) { ?>
				<a href="<?php echo get_the_permalink($book->ID); ?>">
				  <img style="max-width: 60px" src="<?php echo $thumbnail; ?>">
			  </a>
	   	<?php } ?>
   	<?php } ?>
		</td>
		<td valign="top">
		<?php if(!$hide_name || !$hide_author) { ?>
			<div style="font-size: 20px;    line-height: initial;">
				<?php if(!$hide_name) { ?> <strong> <?php echo $book->post_title; ?> </strong>  <?php  } ?>
			</div>
				<?php if(!$hide_author) { ?> 
					<p style="margin-top: -1px; margin-bottom: 5px;">Author : <?php echo get_the_author_meta( 'display_name', $book->post_author ) ;  ?></p> 
				<?php } ?>
			
		<?php } ?>



		<?php if($summery[$keycou]) { ?> 
			<p><?php echo $summery[$keycou]; ?><br> 
		<?php } ?>

		<?php if(!$hide_url) { ?>
		<a href="<?php echo get_the_permalink($book->ID); ?>">Read the Book</a>
   	<?php } ?>

		<?php if($summery[$keycou]) { ?> </p> <?php } ?>

		</td>
	</tr>
</table>





		<?php
	}
$keycou++;

}



		}


		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {



		$book_id = ! empty( $instance['book_id'] ) ? $instance['book_id'] : null;

		$books = new Book_Press_Public($this->plugin_name, $this->version);
		$books = $books->get_all_books();

		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'New title', 'text_domain' );

		$hide_name = ! empty( $instance['hide_name'] ) ? $instance['hide_name'] : null;
		$hide_author = ! empty( $instance['hide_author'] ) ? $instance['hide_author'] : null;
		$hide_cover = ! empty( $instance['hide_cover'] ) ? $instance['hide_cover'] : null;
		$hide_url = ! empty( $instance['hide_url'] ) ? $instance['hide_url'] : null;
		$summery = ! empty( $instance['summery'] ) ? $instance['summery'] : null;





		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'text_domain' ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>

<div class="selection_area_container">
<div class="selection_arae">
	
<div class="single_selection">
		<p>
			<select multiple class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'book_id' ) ); ?>[]" name="<?php echo esc_attr( $this->get_field_name( 'book_id' ) ); ?>[]">
				<option value="0" >Select a Book</option>
				<?php 
				foreach ($books as $key => $value) {
					?>
					<option value="<?php echo $value->ID; ?>" <?php if($book_id){if(in_array( $value->ID, $book_id)) { echo "selected";}}?>><?php echo $value->post_title; ?></option>
					<?php
				}
			?>
		</select>
		</p>

		<p><input  type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_name' ) ); ?>" <?php if($hide_name) { echo "checked";} ?>> Hide Book Name</p>
		<p><input  type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_author' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_author' ) ); ?>" <?php if($hide_author) { echo "checked";} ?>> Hide Author Name</p>
		<p><input  type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_cover' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_cover' ) ); ?>" <?php if($hide_cover) { echo "checked";} ?>> Hide Book Cover</p>
		<p><input  type="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'hide_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_url' ) ); ?>" <?php if($hide_url) { echo "checked";} ?>> Hide Book Read More Url</p>
		<p>
			<strong>Promo Text :</strong><br>
			<textarea style="width: 100%; margin-top:8px;" id="<?php echo esc_attr( $this->get_field_id( 'summery' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'summery' ) ); ?>"><?php if($summery) { echo $summery;} ?></textarea>
			<br>
		<small> <i>Pro text should divided by "|". Ex: Promo text for book one. | Prom text for book two.</i></small>
		</p>
</div>




</div>



</div>




		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['book_id'] = esc_sql( $new_instance['book_id'] );


		$instance['hide_name'] = ( ! empty( $new_instance['hide_name'] ) ) ? sanitize_text_field( $new_instance['hide_name'] ) : '';
		$instance['hide_author'] = ( ! empty( $new_instance['hide_author'] ) ) ? sanitize_text_field( $new_instance['hide_author'] ) : '';
		$instance['hide_cover'] = ( ! empty( $new_instance['hide_cover'] ) ) ? sanitize_text_field( $new_instance['hide_cover'] ) : '';
		$instance['hide_url'] = ( ! empty( $new_instance['hide_url'] ) ) ? sanitize_text_field( $new_instance['hide_url'] ) : '';
		$instance['summery'] = ( ! empty( $new_instance['summery'] ) ) ? sanitize_text_field( $new_instance['summery'] ) : '';

		return $instance;
	}

} // class Book_Press_Widget


// register Book_Press_Widget widget
function register_book_press_pro_widget() {
    register_widget( 'Book_Press_Pro_Widget' );
}
add_action( 'widgets_init', 'register_book_press_pro_widget' );