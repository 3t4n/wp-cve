<?php

/**
 * Elementor Table Block
 *
 * @package Copy the Code
 * @since 3.4.0
 */
namespace CopyTheCode\Elementor\Block;

use  CopyTheCode\Helpers ;
use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
/**
 * Table Block
 *
 * @since 3.4.0
 */
class Table extends Widget_Base
{
    /**
     * Constructor
     *
     * @param array $data
     * @param array $args
     *
     * @since 3.4.0
     */
    public function __construct( $data = array(), $args = null )
    {
        parent::__construct( $data, $args );
        // Block.
        wp_enqueue_style(
            'ctc-el-table',
            COPY_THE_CODE_URI . 'classes/elementor/widgets/table/style.css',
            [ 'ctc-blocks-core' ],
            COPY_THE_CODE_VER,
            'all'
        );
    }
    
    /**
     * Get style dependencies
     */
    public function get_style_depends()
    {
        return [ 'ctc-el-table' ];
    }
    
    /**
     * Get name
     */
    public function get_name()
    {
        return 'ctc_table';
    }
    
    /**
     * Get title
     */
    public function get_title()
    {
        return esc_html__( 'Table', 'copy-the-code' );
    }
    
    /**
     * Get icon
     */
    public function get_icon()
    {
        return 'eicon-table';
    }
    
    /**
     * Get categories
     */
    public function get_categories()
    {
        return Helpers::get_categories();
    }
    
    /**
     * Get keywords
     */
    public function get_keywords()
    {
        return Helpers::get_keywords( [
            'table',
            'copy',
            'code',
            'copy the code',
            'vertical',
            'horizontal',
            'compare',
            'comparison',
            'pricing',
            'pricing table'
        ] );
    }
    
    /**
     * Render
     */
    public function render()
    {
        $is_horizontal = $this->get_settings_for_display( 'is_horizontal' );
        $content = $this->get_settings_for_display( 'copy_content' );
        if ( !$content ) {
            return;
        }
        // Convert the content which is the csv comma seperated values to array.
        $content = explode( "\n", $content );
        // Remove the first element from the array which is the header.
        $header = array_shift( $content );
        // Convert the header to array.
        $header = explode( ',', $header );
        // Convert the content to array.
        $content = array_map( function ( $item ) {
            return explode( ',', $item );
        }, $content );
        ?>
		<div class="ctc-block ctc-table">
			<div class="ctc-table-wrap">
				<?php 
        
        if ( $is_horizontal === 'yes' ) {
            $this->render_horizontal( $header, $content );
        } else {
            $this->render_vertical( $header, $content );
        }
        
        ?>
			</div>
			<div class="ctc-block-actions">
				<?php 
        Helpers::render_copy_button( $this );
        ?>
			</div>
			<?php 
        Helpers::render_copy_content( $this );
        ?>
		</div>
		<?php 
    }
    
    /**
     * Render horizontal
     */
    function render_horizontal( $header, $content )
    {
        echo  '<table class="ctc-table--horizontal">' ;
        foreach ( $header as $heading_index => $heading ) {
            echo  '<tr>' ;
            echo  '<td class="ctc-table__key">' . esc_html( $heading ) . '</td>' ;
            foreach ( $content as $row ) {
                echo  '<td class="ctc-table__value">' . do_shortcode( '[copy_inline text="' . esc_html( $row[$heading_index] ) . '"]' ) . '</td>' ;
            }
            echo  '</tr>' ;
        }
        echo  '</table>' ;
    }
    
    /**
     * Render vertical
     */
    function render_vertical( $header, $content )
    {
        echo  '<table class="ctc-table--vertical">' ;
        echo  '<tr>' ;
        // Output table headers
        foreach ( $header as $heading ) {
            echo  '<th class="ctc-table__key">' . esc_html( $heading ) . '</th>' ;
        }
        echo  '</tr>' ;
        // Output table content
        foreach ( $content as $row ) {
            echo  '<tr>' ;
            foreach ( $row as $value ) {
                echo  '<td class="ctc-table__value">' . do_shortcode( '[copy_inline text="' . esc_html( $value ) . '"]' ) . '</td>' ;
            }
            echo  '</tr>' ;
        }
        echo  '</table>' ;
    }
    
    /**
     * Register controls
     */
    protected function _register_controls()
    {
        Helpers::register_copy_content_section( $this, [
            'label'           => esc_html__( 'Table Content (Comma Separated Values)', 'copy-the-code' ),
            'default'         => 'Processor number, Graphics, Cores/Threads, Graphics (EUs), Cache, Memory, Operating range, Base Freq (GHz), Max Single Core Turbo (GHz; up to), Max All Core Turbo (GHz; up to), Graphics Max Freq (GHz; up to), Intel DL Bost/Intel GMA 2.0
Intel Core i7-1160G7, Intel Iris X, 4/8, 96, 12MB, LPDDR4x-4266, 7-15W, 1.2, 4.4, 3.6, 1.1, Yes
Intel Core i5-1130G7, Intel Iris X, 4/8, 80, 8MB, LPDDR4x-4266, 7-15W, 1.1, 4.0, 3.4, 1.1, Yes
Intel Core i3-1120G4, Intel UHD Graphics, 4/8, 48, 8MB, LPDDR4x-4266, 7-15W, 1.1, 3.5, 3.0, 1.1, Yes
Intel Core i3-1110G4, Intel UHD Graphics, 2/4, 48, 6MB, LPDDR4x-4266, 7-15W, 1.8, 3.9, 3.9, 1.1, Yes',
            'before_controls' => [
            'is_horizontal' => [
            'label'        => esc_html__( 'Is Horizontal', 'copy-the-code' ),
            'type'         => Controls_Manager::SWITCHER,
            'label_on'     => esc_html__( 'Yes', 'copy-the-code' ),
            'label_off'    => esc_html__( 'No', 'copy-the-code' ),
            'return_value' => 'yes',
            'default'      => 'yes',
        ],
        ],
        ] );
        Helpers::register_copy_button_section( $this );
        Helpers::register_pro_sections( $this, [
            'Table',
            'Headings',
            'Odd Rows',
            'Even Rows'
        ] );
        Helpers::register_copy_button_style_section( $this );
    }

}