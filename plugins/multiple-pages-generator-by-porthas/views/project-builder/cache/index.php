 <div class="tab-pane main-tabpane" id="cache" role="tabpanel" aria-labelledby="cache-tab">
     <div class='main-inner-content shadowed'>

         <div class="cache-page">
             <div class="cache-tab-top">
                 <h2><?php _e('Cache', 'mpg'); ?></h2>
             </div>

             <div class="tiles-block">
                 <div class="col-sm-12 col-md-3">
                     <div class="card disk">
                         <svg class="card-img-top " enable-background="new 0 0 46 58" height="120" viewBox="0 0 46 58" width="120" xmlns="http://www.w3.org/2000/svg">
                             <path d="m41.996 58h-37.992c-2.212 0-4.004-1.792-4.004-4.004v-49.992c0-2.212 1.792-4.004 4.004-4.004h37.993c2.211 0 4.003 1.792 4.003 4.004v49.993c0 2.211-1.792 4.003-4.004 4.003z" fill="#405163" />
                             <path d="m23 5c-10.493 0-19 8.507-19 19 0 6.901 3.691 12.924 9.195 16.252l5.515 2.243c1.381.32 2.812.505 4.29.505 10.493 0 19-8.507 19-19s-8.507-19-19-19z" fill="#e7eced" />
                             <circle cx="23" cy="24" fill="#afb6bb" r="8" />
                             <circle cx="23" cy="24" fill="#fff" r="3" />
                             <path d="m13.2 49c-2.32 0-4.2-1.88-4.2-4.2 0-1.696 1.019-3.225 2.585-3.877l9.415-3.923-3.923 9.415c-.652 1.566-2.181 2.585-3.877 2.585z" fill="#afb6bb" />
                             <path d="m14 53c-4.975 0-9-4.025-9-9 0 0-2.959 0-5 0v9.996c0 2.212 1.792 4.004 4.003 4.004h9.997 5v-5z" fill="#637687" /></svg>
                         <div class="card-body">
                             <h5 class="card-title"><?php _e('Disk', 'mpg'); ?></h5>
                             <p class="card-text"><?php _e('Save cached pages on disk as static files.', 'mpg'); ?></p>
                         </div>

                         <div class="card-body">
                             <div class="statistic-block">
                                 <h6><?php _e('Statistic', 'mpg'); ?></h6>

                                 <div>
                                     <span><?php _e('Pages in cache:', 'mpg'); ?></span>
                                     <span class="pages-in-cache"><?php _e('Loading', 'mpg'); ?></span>
                                 </div>

                                 <div>
                                     <span><?php _e('Cache size:', 'mpg'); ?></span>
                                     <span class="cache-size"><?php _e('Loading', 'mpg'); ?></span>
                                 </div>
                             </div>
                         </div>

                         <div class="card-body buttons" data-cache-type="disk">
                             <button disabled="disabled" class="btn btn-success enable-cache"><?php _e('Enable', 'mpg'); ?></button>
                             <button disabled="disabled" class="btn btn-light flush-cache"><?php _e('Flush', 'mpg'); ?></button>
                         </div>
                     </div>
                 </div>

                 <div class="col-sm-12 col-md-3">
                     <div class="card database">
                         <svg class="card-img-top" style="margin-top: -2rem" width="140" height="160" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 96 96" xml:space="preserve">
                             <g>
                                 <path class="st0" d="M21.4,56.9v8.6v1.2v0.3c0,0,0,0,0,0.3c0,7.1,11.9,13.3,26.6,13.3c14.7,0,26.6-6.2,26.6-13.3v-0.6v-0.9v-8.9H21.4z" />
                                 <ellipse class="st1" cx="48" cy="56.9" rx="26.6" ry="11.8" />
                                 <path class="st2" d="M21.6,67.2c-0.1,0.3-0.2,0.9-0.2,1.5c0,6.5,11.9,11.8,26.6,11.8c14.7,0,26.6-5.3,26.6-11.8c0-0.6-0.1-1.2-0.2-1.5C72.8,72.8,61.6,77.5,48,77.5C34.4,77.5,23.2,72.8,21.6,67.2z" />
                                 <path class="st0" d="M21.4,42.1v8.6v1.2v0.3c0,0,0,0,0,0.3c0,7.1,11.9,13.3,26.6,13.3c14.7,0,26.6-6.2,26.6-13.3v-0.6V51v-8.9H21.4z" />
                                 <ellipse class="st1" cx="48" cy="42.1" rx="26.6" ry="11.8" />
                                 <path class="st2" d="M21.6,52.4c-0.1,0.3-0.2,0.9-0.2,1.5c0,6.5,11.9,11.8,26.6,11.8c14.7,0,26.6-5.3,26.6-11.8c0-0.6-0.1-1.2-0.2-1.5C72.8,58,61.6,62.8,48,62.8C34.4,62.8,23.2,58,21.6,52.4z" />
                                 <path class="st0" d="M21.4,27.3v8.6v1.2v0.3c0,0,0,0,0,0.3C21.4,44.8,33.3,51,48,51c14.7,0,26.6-6.2,26.6-13.3v-0.6v-0.9v-8.9H21.4z" />
                                 <path class="st2" d="M21.6,37.7c-0.1,0.3-0.2,0.9-0.2,1.5C21.4,45.6,33.3,51,48,51c14.7,0,26.6-5.3,26.6-11.8c0-0.6-0.1-1.2-0.2-1.5C72.8,43.3,61.6,48,48,48C34.4,48,23.2,43.3,21.6,37.7z" />
                                 <path class="st1" d="M48,56.9v23.6c14.7,0,26.6-6.2,26.6-13.3v-0.6v-0.9v-8.9H48z" />
                                 <path class="st3" d="M48,45v23.6c14.7,0,26.6-5.3,26.6-11.8C74.6,50.3,62.7,45,48,45z" />
                                 <path class="st1" d="M48,42.1v23.6c14.7,0,26.6-6,26.6-13.3v-0.6V51v-8.9H48z" />
                                 <path class="st0" d="M74.4,52.4C72.8,58.3,61.6,62.8,48,62.8v3c14.7,0,26.6-5.3,26.6-11.8C74.6,53.4,74.5,52.9,74.4,52.4z" />
                                 <path class="st3" d="M48,30.3v23.6c14.7,0,26.6-5.3,26.6-11.8C74.6,35.6,62.7,30.3,48,30.3z" />
                                 <path class="st1" d="M48,27.3V51c14.7,0,26.6-6,26.6-13.3v-0.6v-0.9v-8.9H48z" />
                                 <path class="st0" d="M74.4,37.7C72.8,43.5,61.6,48,48,48v3c14.7,0,26.6-5.3,26.6-11.8C74.6,38.6,74.5,38.1,74.4,37.7z" />
                                 <path class="st0" d="M74.4,67.2C72.8,72.8,61.6,77.5,48,77.5v3c14.7,0,26.6-5.3,26.6-11.8C74.6,68.1,74.5,67.5,74.4,67.2z" />
                                 <ellipse class="st1" cx="48" cy="27.3" rx="26.6" ry="11.8" />
                             </g>
                         </svg>

                         <div class="card-body">
                             <h5 class="card-title"><?php _e('Database', 'mpg'); ?></h5>
                             <p class="card-text"><?php _e('Save content of pages as record in database', 'mpg') ?></p>
                         </div>

                         <div class="card-body">
                             <div class="statistic-block">
                                 <h6><?php _e('Statistic', 'mpg'); ?></h6>

                                 <div>
                                     <span><?php _e('Pages in cache:', 'mpg'); ?></span>
                                     <span class="pages-in-cache"><?php _e('Loading', 'mpg'); ?></span>
                                 </div>
                             </div>
                         </div>

                         <div class="card-body buttons" data-cache-type="database">
                             <button disabled="disabled" class="btn btn-success enable-cache"><?php _e('Enable', 'mpg'); ?></button>
                             <button disabled="disabled" class="btn btn-light flush-cache"><?php _e('Flush', 'mpg'); ?></button>
                         </div>
                     </div>
                 </div>
                </div>

             <div class="col-sm-12">
                 <p style="margin-left: 10px;" class="mt-4 text-danger"><?php _e('Important: Cache works with non-logged in users only. If you are logged as WP admin and visit MPG generated page, it will not be cached. Use private/incognito window to test.', 'mpg'); ?></p>
             </div>
         </div>
     </div>
     <!--.col-md-6 -->
     <div class="sidebar-container">
         <?php require_once('sidebar.php') ?>
     </div>
 </div>