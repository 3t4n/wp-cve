<?php
$hta = '';
foreach ($opts as $opt => $value) {
    if ($value) {
        switch ($opt) {
            case 'autoindex':
                $hta .= "Options +Indexes\n";
                break;
                    
            case 'authorid':
                $hta .= "RewriteRule ^author/(.+) \"-\" [F]\n";
                break;
                    
            case 'comments':
                $hta .= "<If \"%{REQUEST_FILENAME} =~ /wp-comments-post.php/ && ( %{HTTP_USER_AGENT} == '' || %{HTTP_REFERER} == '' )\">\n    RewriteRule wp-comments-post\\.php$ \"-\" [F]\n</If>\n";
                break;
                    
            case 'sqlfiles':
                $hta .= "RewriteRule (.+)\\.sql$ \"-\" [F]\n";
                break;
                    
            case 'readmelicense':
                $hta .= "RewriteRule (license\\.txt|readme\\.html)$ \"-\" [F]\n";
                break;
                    
            case 'xmlrpc':
                $hta .= "RewriteRule xmlrpc\\.php$ \"-\" [F]\n";
                break;
                    
            case 'phpuploads':
                $hta .= "<If \"%{REQUEST_URI} =~ m#wp-content/uploads/.+\\.php#\">\n    SetHandler !\n</If>\n";
                break;
        }
    }
}
$hta = <<<EOD
	# BEGIN WP MANAGER
	# Règles ajoutées par LWS Wordpress Manager, ne pas éditer à la main
	# Rules added by LWS Wordpress Manager, do not edit by hand
	$hta# END WP MANAGER

EOD;
if (is_file(ABSPATH . '.htaccess')) {
    $file = file_get_contents(ABSPATH . '.htaccess');
    $file = preg_replace("/(# BEGIN WP MANAGER)([\S\s]*?)(# END WP MANAGER)/", $hta, $file);
    file_put_contents(ABSPATH . '.htaccess', $file);
}
