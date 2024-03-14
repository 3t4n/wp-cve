<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
  * Generates The Meta Box For Editing The Quote
  *
  * @since 7.0.0
  */
class QM_Post_Meta_Box
{
    /**
      * Main Construct Function
      *
      * Call functions within class
      *
      * @since 7.0.0
      * @uses CLASSNAME::load_dependencies() Loads required filed
      * @uses CLASSNAME::add_hooks() Adds actions to hooks and filters
      * @return void
      */
    function __construct()
    {
      $this->load_dependencies();
      $this->add_hooks();
    }

    /**
      * Load File Dependencies
      *
      * @since 7.0.0
      * @return void
      */
    public function load_dependencies()
    {
      //Insert code
    }

    /**
      * Add Hooks
      *
      * Adds functions to relavent hooks and filters
      *
      * @since 7.0.0
      * @return void
      */
    public function add_hooks()
    {
      add_action( 'add_meta_boxes', array($this, 'quote_meta_boxes') );
      add_action( 'save_post', 'qm_post_quote_save', 10, 3);
    }


    public function quote_meta_boxes()
    {
      add_meta_box(
          'settings_link_box',
          __( 'Author And Source', 'quote-master' ),
          array($this,'post_meta_box_content'),
          'quote',
          'normal',
          'high'
      );
    }

    public function post_meta_box_content($post)
    {
      $author = get_post_meta( $post->ID, 'quote_author', true );
      $source = get_post_meta( $post->ID, 'source', true );
      ?>
      <label>Author</label> <input type="text" name="quote_author" value="<?php echo esc_attr($author); ?>" /><br />
      <label>Source</label> <input type="text" name="quote_source" value="<?php echo esc_attr($source); ?>" /><br />
      <?php
    }
}
$qm_post_meta_box = new QM_Post_Meta_Box();


function qm_post_quote_save( $post_id, $post, $update )
{
  if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ( defined( 'DOING_AJAX') && DOING_AJAX ) || isset( $_REQUEST['bulk_edit'] ) ) {
    return;
  }
  if ( isset( $post->post_type ) && 'revision' == $post->post_type ) {
    return;
  }

  if ( isset( $post->post_type ) && 'quote' == $post->post_type ) {
     if (!current_user_can('moderate_comments'))
      return;
  }
  else
  {
    return;
  }

  if (!isset($_POST["quote_author"])) {
    return;
  }

  if ($update)
  {
    update_post_meta($post_id, "quote_author", sanitize_text_field($_POST["quote_author"]));
    update_post_meta($post_id, "source", sanitize_text_field($_POST["quote_source"]));
  }
  else
  {
    add_post_meta($post_id, "quote_author", sanitize_text_field($_POST["quote_author"]), true);
    add_post_meta($post_id, "source", sanitize_text_field($_POST["quote_source"]), true);
  }
  // unhook this function so it doesn't loop infinitely
  remove_action( 'save_post', 'qm_post_quote_save', 10, 3 );

  $my_post = array(
      'ID'           => $post_id,
      'post_title'  => sanitize_text_field($post->post_content)
  );
  wp_update_post( $my_post );

  // re-hook this function
  add_action( 'save_post', 'qm_post_quote_save', 10, 3);
}
?>
