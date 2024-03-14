<?php /**
 * Portfolio
 */
if ( ! function_exists( 'icycp_industryup_portfolio' ) ) :

  function icycp_industryup_portfolio() {
    
    $portfolio_section_title = get_theme_mod('portfolio_section_title',__('Our Portfolio','icyclub'));
    $portfolio_section_discription = get_theme_mod('portfolio_section_discription','laoreet ipsum eu laoreet. ugiignissimat Vivamus dignissim feugiat erat sit amet convallis.');
    $portfolio_section_subtitle = get_theme_mod('portfolio_section_subtitle','OUR PORTFOLIO');
    
    $project_image_one = get_theme_mod('project_image_one',ICYCP_PLUGIN_URL .'inc/industryup/images/portfolio/portfolio1.jpg');
    $project_title_one = get_theme_mod('project_title_one',__('Financial Project','industryup'));
    
    $project_image_two = get_theme_mod('project_image_two',ICYCP_PLUGIN_URL .'inc/industryup/images/portfolio/portfolio2.jpg');
    $project_title_two = get_theme_mod('project_title_two',__('Investment','industryup'));
    
    
    $project_image_three = get_theme_mod('project_image_three',ICYCP_PLUGIN_URL .'inc/industryup/images/portfolio/portfolio3.jpg');
    $project_title_three = get_theme_mod('project_title_three',__('Invoicing','industryup'));

      $project_image_four = get_theme_mod('project_image_four',ICYCP_PLUGIN_URL .'inc/industryup/images/portfolio/portfolio4.jpg');
      $project_title_four = get_theme_mod('project_title_four',__('Team Management','industryup'));
    
    $project_section_enable         = get_theme_mod('project_section_enable','1');
    if($project_section_enable == '1') {
    ?>    
<!-- Portfolio Section -->
<section class="bs-section portfolios" id="portfolio-section">
   <div class="overlay">
    <div class="container">
     <?php if( ($portfolio_section_title) || ($portfolio_section_discription) || ($portfolio_section_subtitle)!='' ) { ?>
      <div class="col text-center">
    <!-- Section Title -->
     <div class="bs-heading">
        <h3 class="bs-subtitle"><?php echo $portfolio_section_subtitle; ?></h3>
        <div class="clearfix"></div>
              <h2 class="bs-title"><?php echo $portfolio_section_title; ?></h2>
        <p><?php echo $portfolio_section_discription; ?></p>
      </div> 
         </div>
    <!-- /Section Title -->
    <?php } ?>
    <!--container-->
      <!--row-->
      <div class="row">
        <!--portfolio-->
          <!--item-->
      <!--col-md-12-->
            <div class="col-lg-3 col-md-6 project-one">
              <div class="bs-portfolio one shd">
                <div class="img_area">
                      <img src="<?php echo $project_image_one;?>" class="img-fluid" alt="">
                </div>
              <!--portfolio-->
                  <div class="bs-portfolio-inner">
                     <div class="topbox">
                      <div class="text clearfix">
                        <div class="ser-icon"><i class="fas fa-search"></i></div>
                        <h4><a href="#"><?php echo $project_title_one; ?></a></h4>
                        <p>Recent Project</p>
                      </div> <!-- /.text -->
                    </div>
                  </div> <!-- /.hover-content -->
              <!--/portfolio-->
            </div>
          </div>
            <!--col-md-12-->
          <div class="col-lg-3 col-md-6 project-one">
              <div class="bs-portfolio one shd">
                <div class="img_area">
                      <img src="<?php echo $project_image_two;?>" class="img-fluid" alt="">
                </div>
              <!--portfolio-->
                  <div class="bs-portfolio-inner">
                     <div class="topbox">
                      <div class="text clearfix">
                        <div class="ser-icon"><i class="fas fa-search"></i></div>
                        <h4><a href="#"><?php echo $project_title_two; ?></a></h4>
                        <p>Recent Project</p>
                      </div> <!-- /.text -->
                    </div>
                  </div> <!-- /.hover-content -->
              <!--/portfolio-->
            </div>
          </div>
           <div class="col-lg-3 col-md-6 project-one">
              <div class="bs-portfolio one shd">
                <div class="img_area">
                      <img src="<?php echo $project_image_three;?>" class="img-fluid" alt="">
                </div>
              <!--portfolio-->
                  <div class="bs-portfolio-inner">
                     <div class="topbox">
                      <div class="text clearfix">
                        <div class="ser-icon"><i class="fas fa-search"></i></div>
                        <h4><a href="#"><?php echo $project_title_three; ?></a></h4>
                        <p>Recent Project</p>
                      </div> <!-- /.text -->
                    </div>
                  </div> <!-- /.hover-content -->
              <!--/portfolio-->
            </div>
          </div>

            <div class="col-lg-3 col-md-6 project-one">
              <div class="bs-portfolio one shd">
                <div class="img_area">
                      <img src="<?php echo $project_image_four;?>" class="img-fluid" alt="">
                </div>
              <!--portfolio-->
                  <div class="bs-portfolio-inner">
                     <div class="topbox">
                      <div class="text clearfix">
                        <div class="ser-icon"><i class="fas fa-search"></i></div>
                        <h4><a href="#"><?php echo $project_title_four; ?></a></h4>
                        <p>Recent Project</p>
                      </div> <!-- /.text -->
                    </div>
                  </div> <!-- /.hover-content -->
              <!--/portfolio-->
            </div>
          </div>
          <!--/item-->
        </div>
        <!--/portfolio-->
      </div>
      <!--/row-->
    </div>
    <!--/conconsultupiner-->
  </section>
<!-- /Portfolio Section -->

<div class="clearfix"></div>  
<?php } }

endif;

    if ( function_exists( 'icycp_industryup_portfolio' ) ) {
    $section_priority = apply_filters( 'icycp_industryup_homepage_section_priority', 13, 'icycp_industryup_portfolio' );
    add_action( 'icycp_industryup_homepage_sections', 'icycp_industryup_portfolio', absint( $section_priority ) );

}