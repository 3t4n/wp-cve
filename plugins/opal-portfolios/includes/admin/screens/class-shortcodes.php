<?php
/**
 * @var OSF_Menu_Setup $this
 * @var string          $welcome
 */
?>
<div class="post-box-container">
        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-2">
            
                <!--How it workd HTML -->
                <div id="post-body-content">
                    <div class="metabox-holder">
                        <div class="meta-box-sortables ui-sortable">
                            <div class="postbox">
                                
                                <h2 class="hndle">
                                    <span><?php _e( 'How It Works - Display and shortcode', 'opalportfolios' ); ?></span>
                                </h2>
                                
                                <div class="inside">
                                    <h3><?php _e( 'Carousel Shortcode', 'opalportfolios' ); ?>: [portfolio_carousel limit="20"]</h3>
                                    <table class="form-table">
                                        <thead>
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Default</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>limit</td>
                                                <td>20</td>
                                                <td>Add posts per page.</td>
                                            </tr>

                                             <tr>
                                                <td>category</td>
                                                <td></td>
                                                <td>Add Category(slug) separated by ','</td>
                                            </tr>

                                            <tr>
                                                <td>loop</td>
                                                <td>True (1)</td>
                                                <td>Set to <b>true</b> (1) to enable continuous loop mode.</td>
                                            </tr>

                                            <tr>
                                                <td>order</td>
                                                <td>DESC</td>
                                                <td>Value can be ASC or DESC</td>
                                            </tr>

                                            <tr>
                                                <td>orderby</td>
                                                <td>date</td>
                                                <td>Value can be any column name</td>
                                            </tr>

                                            <tr>
                                                <td>autoplay</td>
                                                <td>False (0)</td>
                                                <td>Set to <b>true</b> (1) to enable continuous Autoplay mode.</td>
                                            </tr>

                                            <tr>
                                                <td>pagination</td>
                                                <td>True (1)</td>
                                                <td>Set to <b>true</b> (1) to enable continuous Pagination mode.</td>
                                            </tr>                       
                                                
                                            <tr>
                                                <td>nav</td>
                                                <td>True (1)</td>
                                                <td>Set to <b>true</b> (1) to enable continuous navigation mode.</td>
                                            </tr>

                                            <tr>
                                                <td>padding</td>
                                                <td>80px</td>
                                                <td>Add padding between items</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div><!-- .inside -->

                                <div class="inside">
                                    <h3><?php _e( 'Filter Shortcode', 'opalportfolios' ); ?>: [portfolio_filter limit="20"]</h3>
                                    <table class="form-table">
                                        <thead>
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Default</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>style</td>
                                                <td>classic</td>
                                                <td>Value can be 'classic', 'boxed' or 'list'.</td>
                                            </tr>

                                            <tr>
                                                <td>limit</td>
                                                <td>20</td>
                                                <td>Add posts per page.</td>
                                            </tr>

                                             <tr>
                                                <td>category</td>
                                                <td></td>
                                                <td>Add Category(slug) separated by ','</td>
                                            </tr>

                                            <tr>
                                                <td>column</td>
                                                <td>3</td>
                                                <td>Add column numbers in 1 row.</td>
                                            </tr>

                                            <tr>
                                                <td>order</td>
                                                <td>DESC</td>
                                                <td>Value can be ASC or DESC.</td>
                                            </tr>

                                            <tr>
                                                <td>orderby</td>
                                                <td>date</td>
                                                <td>Value can be any column name.</td>
                                            </tr>

                                            <tr>
                                                <td>show_category</td>
                                                <td>yes</td>
                                                <td>Set to <b>yes</b> to display category.</td>
                                            </tr>

                                            <tr>
                                                <td>show_description</td>
                                                <td>yes</td>
                                                <td>Set to <b>yes</b> to display description.</td>
                                            </tr>                       
                                                
                                            <tr>
                                                <td>show_readmore</td>
                                                <td>no</td>
                                                <td>Set to <b>yes</b> to display readmore button.</td>
                                            </tr>

                                            <tr>
                                                <td>padding</td>
                                                <td>80px</td>
                                                <td>Add padding between items</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div><!-- .inside -->

                                <div class="inside">
                                    <h3><?php _e( 'Grid Shortcode', 'opalportfolios' ); ?>: [portfolio_grid limit="20"]</h3>
                                    <table class="form-table">
                                        <thead>
                                            <tr>
                                                <th>Parameter</th>
                                                <th>Default</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>style</td>
                                                <td>classic</td>
                                                <td>Value can be 'classic', 'boxed' or 'list'.</td>
                                            </tr>

                                            <tr>
                                                <td>limit</td>
                                                <td>20</td>
                                                <td>Add posts per page.</td>
                                            </tr>

                                             <tr>
                                                <td>category</td>
                                                <td></td>
                                                <td>Add Category(slug) separated by ','</td>
                                            </tr>

                                            <tr>
                                                <td>column</td>
                                                <td>3</td>
                                                <td>Add column numbers in 1 row.</td>
                                            </tr>

                                            <tr>
                                                <td>order</td>
                                                <td>DESC</td>
                                                <td>Value can be ASC or DESC.</td>
                                            </tr>

                                            <tr>
                                                <td>orderby</td>
                                                <td>date</td>
                                                <td>Value can be any column name.</td>
                                            </tr>

                                            <tr>
                                                <td>masonry</td>
                                                <td>no</td>
                                                <td>Set to <b>yes</b> to enable continuous Masonry.</td>
                                            </tr>

                                            <tr>
                                                <td>show_category</td>
                                                <td>yes</td>
                                                <td>Set to <b>yes</b> to display category.</td>
                                            </tr>

                                            <tr>
                                                <td>show_description</td>
                                                <td>yes</td>
                                                <td>Set to <b>yes</b> to display description.</td>
                                            </tr>                       
                                                
                                            <tr>
                                                <td>show_readmore</td>
                                                <td>no</td>
                                                <td>Set to <b>yes</b> to display readmore button.</td>
                                            </tr>

                                            <tr>
                                                <td>padding</td>
                                                <td>80px</td>
                                                <td>Add padding between items</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div><!-- .inside -->

                            </div><!-- #general -->
                        </div><!-- .meta-box-sortables ui-sortable -->
                    </div><!-- .metabox-holder -->
                </div><!-- #post-body-content -->

            </div><!-- #post-body -->
        </div><!-- #poststuff -->
    </div><!-- #post-box-container -->