<?php
    ###############################################
    # htaccess
    #
    # Codes d'erreurs 7XX
    // Liste des options
    $opts = [
        'autoindex'		=> false,
        'authorid'		=> false,
        'comments'		=> false,
        'sqlfiles'		=> false,
        'readmelicense'	=> false,
        'xmlrpc'		=> false,
        'phpuploads'	=> false,
        'sslredirect'   => false,
    ];
            
    if (is_file(ABSPATH . '.htaccess')) {
        // Options hors balise
        $f      = file(ABSPATH . '.htaccess');
        $file = file_get_contents(ABSPATH . '.htaccess');
        $read   = false;
        $balise = false;
        $rCond  = false;
        $datas  = [];
                
        foreach ($f as $line) {
            if ($read && preg_match("#^<(.[^>]+)>$#", trim($line), $a)) {
                $balise = $a[1];
            }
                        
            if ($read && preg_match("#^</.[^>]+>$#", trim($line))) {
                $balise = false;
            }
                        
            if ($read && preg_match("#^(RewriteCond .+)#", trim($line), $a)) {
                $rCond = $a[1];
            }
                        
            if (trim($line) == '# BEGIN WP MANAGER') {
                $read = true;
            }
                        
            if ($read && trim($line) != '' && trim($line)[0] != '#') {
                if (! $balise && ! $rCond) {
                    // Auto index
                    if (trim(mb_strtolower($line)) == 'options +indexes') {
                        $opts['autoindex'] = true;
                    }
                                
                    // Blocage de la page permettant de recuperer les infos sur l'auteur
                    if (trim(mb_strtolower($line)) == 'rewriterule ^author/(.+) "-" [f]') {
                        $opts['authorid'] = true;
                    }
                            
                    // Blocage fichiers .sql
                    if (trim(mb_strtolower($line)) == 'rewriterule (.+)\.sql$ "-" [f]') {
                        $opts['sqlfiles'] = true;
                    }
                                
                    // Blocage fichiers readme et licence
                    if (trim(mb_strtolower($line)) == 'rewriterule (license\.txt|readme\.html)$ "-" [f]') {
                        $opts['readmelicense'] = true;
                    }
                                
                    // Blocage fichier xmlrpc
                    if (trim(mb_strtolower($line)) == 'rewriterule xmlrpc\.php$ "-" [f]') {
                        $opts['xmlrpc'] = true;
                    }
                } elseif (trim(mb_strtolower($balise)) == 'if "%{request_uri} =~ m#wp-content/uploads/.+\.php#"') {
                    if (trim(mb_strtolower($line)) == 'sethandler !') {
                        $opts['phpuploads'] = true;
                    }
                } elseif (trim(mb_strtolower($balise)) == 'if "%{request_filename} =~ /wp-comments-post.php/ && ( %{http_user_agent} == \'\' || %{http_referer} == \'\' )"') {
                    if (trim(mb_strtolower($line)) == 'rewriterule wp-comments-post\.php$ "-" [f]') {
                        $opts['comments'] = true;
                    }
                } elseif (trim(mb_strtolower($rCond)) == 'rewritecond %{https} !on') {
                    if (trim(mb_strtolower($line)) == 'rewriterule ^(.*)$ https://%{http_host}%{request_uri} [redirect=301,l]') {
                        $rCond               = false;
                        $opts['sslredirect'] = true;
                    }
                }
            }
                        
            if (trim($line) == '# END WP MANAGER') {
                $read = false;
            }
        }
        
        // $hta = '';
        // foreach ($opts as $opt => $value) {
        //     if ($value) {
        //         switch ($opt) {
        //             case 'autoindex':
        //                 $hta .= "Options +Indexes\n";
        //                 break;
                            
        //             case 'authorid':
        //                 $hta .= "RewriteRule ^author/(.+) \"-\" [F]\n";
        //                 break;
                            
        //             case 'comments':
        //                 $hta .= "<If \"%{REQUEST_FILENAME} =~ /wp-comments-post.php/ && ( %{HTTP_USER_AGENT} == '' || %{HTTP_REFERER} == '' )\">\n    RewriteRule wp-comments-post\\.php$ \"-\" [F]\n</If>\n";
        //                 break;
                            
        //             case 'sqlfiles':
        //                 $hta .= "RewriteRule (.+)\\.sql$ \"-\" [F]\n";
        //                 break;
                            
        //             case 'readmelicense':
        //                 $hta .= "RewriteRule (license\\.txt|readme\\.html)$ \"-\" [F]\n";
        //                 break;
                            
        //             case 'xmlrpc':
        //                 $hta .= "RewriteRule xmlrpc\\.php$ \"-\" [F]\n";
        //                 break;
                            
        //             case 'phpuploads':
        //                 $hta .= "<If \"%{REQUEST_URI} =~ m#wp-content/uploads/.+\\.php#\">\n    SetHandler !\n</If>\n";
        //                 break;
                            
        //             case 'sslredirect':
        //                 $hta .= "RewriteCond %{HTTP:X-Forwarded-Proto} !https\nRewriteCond %{HTTPS} !on\nRewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [redirect=301,L]\n";
        //                 break;
        //         }
        //     }
        // }
        // preg_replace("/# BEGIN WP MANAGER([\S\s]*?)# END WP MANAGER/", $hta, $file);
        // file_put_contents(ABSPATH . '.htaccess', $file);
    }
