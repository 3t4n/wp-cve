<?php
namespace Enteraddons\Admin;
/**
 * Enteraddons admin
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

trait System_Status {

    public function system_status_content() {

        ?>
        <div class="system-status">
            <h3><?php esc_html_e( 'System Status', 'enteraddons' ); ?></h3>
            <p><?php esc_html_e( 'When you install a demo it provides pages, images, theme options, posts, slider, widgets and etc.', 'enteraddons' ); ?></p>
            <p class="important-text"><strong><?php esc_html_e( 'IMPORTANT:', 'enteraddons' ); ?></strong> <?php esc_html_e( 'Please check below status to see if your server meets all essential requirements for a successful import.', 'enteraddons' ); ?></p>

            <table class="system">
                <tbody>
                    <?php 
                    $data = self::system_status_data();
                    if( !empty( $data ) ) {
                        foreach( $data as $val ) {
                            echo '<tr>';

                                if( !empty( $val['title'] ) ) {

                                    $sclass = 'times';
                                    $iclass = 'fa-times-circle-o';
                                    if( !empty( $val['status'] ) ) {
                                        $sclass = 'check';
                                        $iclass = 'fa-check-circle-o';
                                    }
                                    echo '<td class="'.esc_attr( $sclass ).'"><i class="fa '.esc_attr( $iclass ).'"></i> '.esc_html( $val['title'] ).'</td>';
                                }
                                //
                                if( !empty( $val['currently'] ) ) {
                                    echo '<td>'.esc_html__( 'Currently:', 'enteraddons' ).' <b>'.esc_html( $val['currently'] ).'</b></td>';
                                }
                                //
                                if( !empty( $val['min'] ) ) {
                                    echo '<td class="min">'.esc_html( $val['min'] ).'</td>';
                                }
                                //
                                if( !empty( $val['note'] ) ) {
                                    echo '<td class="note">Note: '.esc_html( $val['note'] ).'</td>';
                                }
                                //
                                if( !empty( $val['link'] ) ) {
                                    echo '<td><a href="'.esc_url( $val['link'] ).'" class="btn table-btn">'.esc_html__( 'How To Fix!', 'enteraddons' ).'</a></td>';
                                }
                                
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public function system_status_data() {

        $max_execution_time = ini_get('max_execution_time');
        $max_input_vars     = ini_get('max_input_vars');
        $post_max_size      = ini_get('post_max_size');
        $upload_max_filesize = ini_get('upload_max_filesize');
        $memory_limit        = ini_get('memory_limit');

        return [

            [
                'title' => esc_html__( 'PHP Version:', 'enteraddons' ),
                'currently' => ENTERADDONS_CURRENT_PHPVERSION,
                'min' => esc_html__( 'Min: 7.4.0', 'enteraddons' ),
                'status' => version_compare( ENTERADDONS_CURRENT_PHPVERSION, "7.4.0", ">=" ),
                'link' => ''

            ],
            [
                'title' => esc_html__( 'PHP Maximum Execution Time', 'enteraddons' ),
                'currently' => $max_execution_time,
                'min' => esc_html__( 'Min: 120', 'enteraddons' ),
                'note' => '',
                'status' => $max_execution_time >= 120 ? true : false,
                'link' => ''

            ],
            [
                'title' => esc_html__( 'PHP Maximum Input Vars', 'enteraddons' ),
                'currently' => $max_input_vars,
                'min' => esc_html__( 'Min: 2500', 'enteraddons' ),
                'note' => '',
                'status' => $max_input_vars >= 2500 ? true : false,
                'link' => ''

            ],
            [
                'title' => esc_html__( 'Maximum Post Size', 'enteraddons' ),
                'currently' => $post_max_size,
                'min' => esc_html__( 'Min: 64M', 'enteraddons' ),
                'note' => '',
                'status' => absint( $post_max_size ) >= 64 ? true : false,
                'link' => ''

            ],
            [
                'title' => esc_html__( 'Upload Maximum Filesize', 'enteraddons' ),
                'currently' => $upload_max_filesize,
                'min'    => esc_html__( 'Min: 64M', 'enteraddons' ),
                'note'   => '',
                'status' => absint( $upload_max_filesize ) >= 64 ? true : false,
                'link'   => ''

            ],
            [
                'title'     => esc_html__( 'WP Memory Limit', 'enteraddons' ),
                'currently' => $memory_limit,
                'min'    => esc_html__( 'Min: 256M', 'enteraddons' ),
                'note'   => '',
                'status' => absint( $memory_limit ) >= 256 ? true : false,
                'link'   => ''

            ]

        ];

    }


}