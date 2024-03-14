<?php

if (!defined('ABSPATH'))
    die('Restricted Access');
class JSJOBSactivation {

    static function jsjobs_activate() {
        // Install Database
        JSJOBSactivation::runSQL();
        JSJOBSactivation::insertMenu();
        JSJOBSactivation::checkUpdates();
        JSJOBSactivation::addCapabilites();
    }

    static private function checkUpdates() {
        include_once JSJOBS_PLUGIN_PATH . 'includes/updates/updates.php';
        JSJOBSupdates::checkUpdates();
    }

    static private function addCapabilites() {
        $role = get_role( 'administrator' );
        $role->add_cap( 'jsjobs' );
    }

    static private function insertMenu() {
        $pageexist = jsjobs::$_db->get_var("Select COUNT(id) FROM `" . jsjobs::$_db->prefix . "posts` WHERE post_content LIKE '%[jsjobs_jobseeker_controlpanel]%'");
        if ($pageexist == 0) {
            $post = array(
                'post_name' => 'js-jobs-jobseeker-controlpanel',
                'post_title' => 'Jobseeker',
                'post_status' => 'publish',
                'post_content' => '[jsjobs_jobseeker_controlpanel]',
                'post_type' => 'page'
            );
            wp_insert_post($post);
        } else {
            jsjobs::$_db->get_var("UPDATE `" . jsjobs::$_db->prefix . "posts` SET post_status = 'publish' WHERE post_content LIKE '%[jsjobs_jobseeker_controlpanel]%'");
        }
        update_option('rewrite_rules', '');
    }

    static private function runSQL() {
        $query = "CREATE TABLE IF NOT EXISTS `".jsjobs::$_db->prefix."js_job_config` (
                  `configname` varchar(100) NOT NULL DEFAULT '',
                  `configvalue` varchar(255) NOT NULL DEFAULT '',
                  `configfor` varchar(50) DEFAULT NULL,
                  PRIMARY KEY (`configname`),
                  FULLTEXT KEY `config_name` (`configname`),
                  FULLTEXT KEY `config_for` (`configfor`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        jsjobs::$_db->query($query);
        $runConfig = jsjobs::$_db->get_var("SELECT COUNT(configname) FROM `" . jsjobs::$_db->prefix . "js_job_config`");
        if ($runConfig == 0) {

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_ages` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(45) NOT NULL,
              `status` tinyint(1) NOT NULL,
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11;";
            jsjobs::$_db->query($query);

            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_ages` (`id`, `title`, `status`, `isdefault`, `ordering`, `serverid`) VALUES(1, '10 Years', 1, 0, 1, 0),(2, '15 Years', 1, 0, 2, 0),(3, '20 Years', 1, 0, 3, 0),(4, '25 Years', 1, 1, 4, 0),(5, '30 Years', 1, 0, 5, 0),(6, '35 Years', 1, 0, 6, 0),(7, '40 Years', 1, 0, 7, 0),(8, '45 Years', 1, 0, 8, 0),(9, '50 Years', 1, 0, 9, 0),(10, '55 Years', 1, 0, 10, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_careerlevels` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(150) NOT NULL,
              `status` tinyint(4) NOT NULL,
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_careerlevels` (`id`, `title`, `status`, `isdefault`, `ordering`, `serverid`) VALUES(1, 'Student (Undergraduate)', 1, 0, 1, 0),(2, 'Student (Graduate)', 1, 0, 2, 0),(3, 'Entry Level', 1, 1, 3, 0),(4, 'Experienced (Non-Manager)', 1, 0, 4, 0),(5, 'Manager', 1, 0, 5, 0),(6, 'Executive (Department Head, SVP, VP etc)', 1, 0, 6, 0),(7, 'Senior Executive (President, CEO, etc)', 1, 0, 7, 0);";
            jsjobs::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_categories` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `cat_value` varchar(255) DEFAULT NULL,
              `cat_title` varchar(255) DEFAULT NULL,
              `alias` varchar(225) NOT NULL,
              `isactive` smallint(1) DEFAULT '1',
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `parentid` int(11) NOT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=239 ;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_categories` (`id`, `cat_value`, `cat_title`, `alias`, `isactive`, `isdefault`, `ordering`, `parentid`, `serverid`) VALUES (1, NULL, 'Accounting/Finance', 'accounting-finance', 1, 0, 1, 0, 0),(2, NULL, 'Administrative', 'administrative', 1, 0, 2, 0, 0),(3, NULL, 'Advertising', 'advertising', 1, 0, 3, 0, 0),(4, NULL, 'Airlines/Avionics/Aerospace', 'airlines-avionics-aerospace', 1, 0, 4, 0, 0),(5, NULL, 'Architectural', 'architectural', 1, 0, 5, 0, 0),(6, NULL, 'Automotive', 'automotive', 1, 0, 6, 0, 0),(7, NULL, 'Banking/Finance', 'banking-finance', 1, 0, 7, 0, 0),(8, NULL, 'Biotechnology', 'biotechnology', 1, 0, 8, 0, 0),(9, NULL, 'Civil/Construction', 'civil-construction', 1, 0, 9, 0, 0),(10, NULL, 'Engineering', 'engineering', 1, 0, 10, 0, 0),(11, NULL, 'Cleared Jobs', 'cleared-jobs', 1, 0, 11, 0, 0),(12, NULL, 'Communications', 'communications', 1, 0, 12, 0, 0),(13, NULL, 'Computer/IT', 'computer-it', 1, 1, 13, 0, 0),(14, NULL, 'Construction', 'construction', 1, 0, 14, 0, 0),(15, NULL, 'Consultant/Contractual', 'consultant-contractual', 1, 0, 15, 0, 0),(16, NULL, 'Customer Service', 'customer-service', 1, 0, 16, 0, 0),(17, NULL, 'Defense', 'defense', 1, 0, 17, 0, 0),(18, NULL, 'Design', 'design', 1, 0, 18, 0, 0),(19, NULL, 'Education', 'education', 1, 0, 19, 0, 0),(20, NULL, 'Electrical Engineering', 'electrical-engineering', 1, 0, 20, 0, 0),(21, NULL, 'Electronics Engineering', 'electronics-engineering', 1, 0, 21, 0, 0),(22, NULL, 'Energy', 'energy', 1, 0, 22, 0, 0),(23, NULL, 'Environmental/Safety', 'environmental-safety', 1, 0, 24, 0, 0),(24, NULL, 'Fundraising', 'fundraising', 1, 0, 25, 0, 0),(25, NULL, 'Health/Medicine', 'health-medicine', 1, 0, 26, 0, 0),(26, NULL, 'Homeland Security', 'homeland-security', 1, 0, 27, 0, 0),(27, NULL, 'Human Resources', 'human-resources', 1, 0, 28, 0, 0),(28, NULL, 'Insurance', 'insurance', 1, 0, 29, 0, 0),(29, NULL, 'Intelligence Jobs', 'intelligence-jobs', 1, 0, 30, 0, 0),(30, NULL, 'Internships/Trainees', 'internships-trainees', 1, 0, 31, 0, 0),(31, NULL, 'Legal', 'legal', 1, 0, 32, 0, 0),(32, NULL, 'Logistics/Transportation', 'logistics-transportation', 1, 0, 33, 0, 0),(33, NULL, 'Maintenance', 'maintenance', 1, 0, 34, 0, 0),(34, NULL, 'Management', 'management', 1, 0, 35, 0, 0),(35, NULL, 'Manufacturing/Warehouse', 'manufacturing-warehouse', 1, 0, 36, 0, 0),(36, NULL, 'Marketing', 'marketing', 1, 0, 37, 0, 0),(37, NULL, 'Materials Management', 'materials-management', 1, 0, 38, 0, 0),(38, NULL, 'Mechanical Engineering', 'mechanical-engineering', 1, 0, 39, 0, 0),(39, NULL, 'Mortgage/Real Estate', 'mortgage-real estate', 1, 0, 40, 0, 0),(40, NULL, 'National Security', 'national-security', 1, 0, 41, 0, 0),(41, NULL, 'Part-time/Freelance', 'part-time-freelance', 1, 0, 42, 0, 0),(42, NULL, 'Printing', 'printing', 1, 0, 43, 0, 0),(43, NULL, 'Product Design', 'product-design', 1, 0, 44, 0, 0),(44, NULL, 'Public Relations', 'public-relations', 1, 0, 45, 0, 0),(45, NULL, 'Public Safety', 'public-safety', 1, 0, 46, 0, 0),(46, NULL, 'Research', 'research', 1, 0, 47, 0, 0),(47, NULL, 'Retail', 'retail', 1, 0, 48, 0, 0),(48, NULL, 'Sales', 'sales', 1, 0, 49, 0, 0),(49, NULL, 'Scientific', 'scientific', 1, 0, 50, 0, 0),(50, NULL, 'Shipping/Distribution', 'shipping-distribution', 1, 0, 51, 0, 0),(51, NULL, 'Technicians', 'technicians', 1, 0, 52, 0, 0),(52, NULL, 'Trades', 'trades', 1, 0, 53, 0, 0),(53, NULL, 'Transportation', 'transportation', 1, 0, 54, 0, 0),(54, NULL, 'Transportation Engineering', 'transportation-engineering', 1, 0, 55, 0, 0),(55, NULL, 'Web Site Development', 'web-site-development', 1, 0, 56, 0, 0),(56, NULL, 'Cast Accounting ', 'cast-accounting-', 1, 0, 1, 1, 0),(57, NULL, 'Controllership & Accounting Managment', 'controllership-and-accounting-managment', 1, 0, 2, 1, 0),(58, NULL, 'Payroll ', 'payroll-', 1, 0, 3, 1, 0),(59, NULL, 'Corporate Finance', 'corporate-finance', 1, 0, 4, 1, 0),(60, NULL, 'Administrative Division', 'administrative-division', 1, 0, 1, 2, 0),(61, NULL, 'Autonomous Territories', 'autonomous-territories', 1, 0, 2, 2, 0),(62, NULL, 'Administrative County', 'administrative-county', 1, 0, 3, 2, 0),(63, NULL, 'Administrative Communes', 'administrative-communes', 1, 0, 4, 2, 0),(64, NULL, 'Finance Advertising ', 'finance-advertising-', 1, 0, 1, 3, 0),(65, NULL, 'Advertising-Tourism', 'advertising-tourism', 1, 0, 2, 3, 0),(66, NULL, 'Advertising Social Net', 'advertising-social-net', 1, 0, 3, 3, 0),(67, NULL, 'Distributor Marketing', 'distributor-marketing', 1, 0, 4, 3, 0),(68, NULL, 'Facebook Advertising', 'facebook-advertising', 1, 0, 5, 3, 0),(69, NULL, 'Quality Engineer ', 'quality-engineer-', 1, 0, 1, 4, 0),(70, NULL, 'Office Assistant ', 'office-assistant-', 1, 0, 2, 4, 0),(71, NULL, 'Air Host/hostess', 'air host-hostess', 1, 0, 3, 4, 0),(72, NULL, 'Ticketing/reservation', 'ticketing-reservation', 1, 0, 4, 4, 0),(73, NULL, 'Architectural Drafting', 'architectural-drafting', 1, 0, 1, 5, 0),(74, NULL, 'Enterprize Architecture', 'enterprize-architecture', 1, 0, 2, 5, 0),(75, NULL, 'Architecture Frameworks', 'architecture-frameworks', 1, 0, 3, 5, 0),(76, NULL, 'Automotive Design', 'automotive-design', 1, 0, 1, 6, 0),(77, NULL, 'Autmotive Paints', 'autmotive-paints', 1, 0, 2, 6, 0),(78, NULL, 'Automotive Equipment/Parts', 'automotive equipment-parts', 1, 0, 3, 6, 0),(79, NULL, 'Automotive Search Engine', 'automotive-search-engine', 1, 0, 4, 6, 0),(80, NULL, 'Private Banking', 'private-banking', 1, 0, 1, 7, 0),(81, NULL, 'Stock Brocker', 'stock-brocker', 1, 0, 2, 7, 0),(82, NULL, 'Fractional-reserve Banking', 'fractional-reserve-banking', 1, 0, 3, 7, 0),(83, NULL, 'Mobile Banking', 'mobile-banking', 1, 0, 4, 7, 0),(84, NULL, 'Plant Biotechnology', 'plant-biotechnology', 1, 0, 1, 8, 0),(85, NULL, 'Animal Biotechnology', 'animal-biotechnology', 1, 0, 2, 8, 0),(86, NULL, 'Biotechnology & Medicine', 'biotechnology-and-medicine', 1, 0, 3, 8, 0),(87, NULL, 'Biotechnology & Society', 'biotechnology-and-society', 1, 0, 4, 8, 0),(88, NULL, 'Industrail & Microbial Biotechnonogy', 'industrail-and-microbial-biotechnonogy', 1, 0, 5, 8, 0),(89, NULL, 'Construction (Design & Managment)', 'construction-(design-and-managment)', 1, 0, 1, 9, 0),(90, NULL, 'Construction Engineering ', 'construction-engineering-', 1, 0, 2, 9, 0),(91, NULL, 'Composite Construction', 'composite-construction', 1, 0, 3, 9, 0),(92, NULL, 'Civil Engineering', 'civil-engineering', 1, 0, 1, 10, 0),(93, NULL, 'Software Engineering', 'software-engineering', 1, 0, 2, 10, 0),(94, NULL, 'Nuclear Engineering', 'nuclear-engineering', 1, 0, 3, 10, 0),(95, NULL, 'Ocean Engingeering', 'ocean-engingeering', 1, 0, 4, 10, 0),(96, NULL, 'Transpotation Engineering', 'transpotation-engineering', 1, 0, 5, 10, 0),(97, NULL, 'Security Cleared Jobs', 'security-cleared-jobs', 1, 0, 1, 11, 0),(98, NULL, 'Security Cleared IT Jobs', 'security-cleared-it-jobs', 1, 0, 2, 11, 0),(99, NULL, 'Confidential & Secret Security Clearance Job', 'confidential-and-secret-security-clearance-job', 1, 0, 3, 11, 0),(100, NULL, 'Verbal', 'verbal', 1, 0, 1, 12, 0),(101, NULL, 'E-mail', 'e-mail', 1, 0, 2, 12, 0),(102, NULL, 'Non-verbal', 'non-verbal', 1, 0, 3, 12, 0),(103, NULL, 'Computer Consulting Services', 'computer-consulting-services', 1, 0, 1, 13, 0),(104, NULL, 'Computer Installations Services', 'computer-installations-services', 1, 0, 2, 13, 0),(105, NULL, 'Software Vendors', 'software-vendors', 1, 1, 3, 13, 0),(106, NULL, 'Renovaiton', 'renovaiton', 1, 0, 1, 14, 0),(107, NULL, 'Addition', 'addition', 1, 0, 2, 14, 0),(108, NULL, 'New Construction', 'new-construction', 1, 0, 3, 14, 0),(109, NULL, 'Organization Development', 'organization-development', 1, 0, 1, 15, 0),(110, NULL, 'Construction Management', 'construction-management', 1, 0, 2, 15, 0),(111, NULL, 'Managment Consulting ', 'managment-consulting-', 1, 0, 3, 15, 0),(112, NULL, 'High Touch Customer Service', 'high-touch-customer-service', 1, 0, 1, 16, 0),(113, NULL, 'Low Touch Customer Service', 'low-touch-customer-service', 1, 0, 2, 16, 0),(114, NULL, 'Bad Touch Customer Service', 'bad-touch-customer-service', 1, 0, 3, 16, 0),(115, NULL, 'By Using legal services for the poor', 'by-using-legal-services-for-the-poor', 1, 0, 1, 17, 0),(116, NULL, 'By Using Retained Counsel', 'by-using-retained-counsel', 1, 0, 2, 17, 0),(117, NULL, 'By Self-representation', 'by-self-representation', 1, 0, 3, 17, 0),(118, NULL, 'Project Subtype Design', 'project-subtype-design', 1, 0, 1, 18, 0),(119, NULL, 'Graphic Design', 'graphic-design', 1, 0, 2, 18, 0),(120, NULL, 'Interior Desing', 'interior-desing', 1, 0, 3, 18, 0),(121, NULL, 'IT or Engineering Education', 'it-or-engineering-education', 1, 0, 1, 19, 0),(122, NULL, 'Commerce & Managment', 'commerce-and-managment', 1, 0, 2, 19, 0),(123, NULL, 'Medical Education', 'medical-education', 1, 0, 3, 19, 0),(124, NULL, 'Power Engineering', 'power-engineering', 1, 0, 1, 20, 0),(125, NULL, 'Instrumentation', 'instrumentation', 1, 0, 2, 20, 0),(126, NULL, 'Telecommunication', 'telecommunication', 1, 0, 3, 20, 0),(127, NULL, 'Signal Processing', 'signal-processing', 1, 0, 4, 20, 0),(128, NULL, 'Electromagnetics', 'electromagnetics', 1, 0, 1, 21, 0),(129, NULL, 'Network Analysis', 'network-analysis', 1, 0, 2, 21, 0),(130, NULL, 'Control Systems', 'control-systems', 1, 0, 3, 21, 0),(131, NULL, 'Thermal Energy', 'thermal-energy', 1, 0, 1, 22, 0),(132, NULL, 'Chemical Energy', 'chemical-energy', 1, 0, 2, 22, 0),(133, NULL, 'Electrical Energy', 'electrical-energy', 1, 0, 3, 22, 0),(134, NULL, 'Nuclear Energy', 'nuclear-energy', 1, 0, 4, 22, 0),(135, NULL, 'Software Engineering ', 'software-engineering', 1, 0, 1, 23, 0),(136, NULL, 'Civil Engineering', 'civil-engineering-', 1, 0, 2, 23, 0),(137, NULL, 'Nuclear Engineering', 'nuclear-engineering', 1, 0, 3, 23, 0),(138, NULL, 'Nuclear Safety', 'nuclear-safety', 1, 0, 1, 24, 0),(139, NULL, 'Agriculture Safety', 'agriculture-safety', 1, 0, 2, 24, 0),(140, NULL, 'Occupational Health Safety', 'occupational-health-safety', 1, 0, 3, 24, 0),(141, NULL, 'Unique Fundraisers', 'unique-fundraisers', 1, 0, 1, 25, 0),(142, NULL, 'Sports Fundraiserse', 'sports-fundraiserse', 1, 0, 2, 25, 0),(143, NULL, 'Fundraisers', 'fundraisers', 1, 0, 3, 25, 0),(144, NULL, 'Staying Informed', 'staying-informed', 1, 0, 1, 26, 0),(145, NULL, 'Medical Edcuation ', 'medical-edcuation-', 1, 0, 2, 26, 0),(146, NULL, 'Managing a partucular disease', 'managing-a-partucular-disease', 1, 0, 3, 26, 0),(147, NULL, 'Customs & Border Protection', 'customs-and-border-protection', 1, 0, 1, 27, 0),(148, NULL, 'Federal Law & Enforcement', 'federal-law-and-enforcement', 1, 0, 2, 27, 0),(149, NULL, 'Nation Protection', 'nation-protection', 1, 0, 3, 27, 0),(150, NULL, 'Benefits Administrators', 'benefits-administrators', 1, 0, 1, 28, 0),(151, NULL, 'Executive Compensation Analysts', 'executive-compensation-analysts', 1, 0, 2, 28, 0),(152, NULL, 'Managment Analysts', 'managment-analysts', 1, 0, 3, 28, 0),(153, NULL, 'Health Insurance ', 'health-insurance-', 1, 0, 1, 29, 0),(154, NULL, 'Life Insurance', 'life-insurance', 1, 0, 2, 29, 0),(155, NULL, 'Vehicle Insurance', 'vehicle-insurance', 1, 0, 3, 29, 0),(156, NULL, 'Artificial Intelligence ', 'artificial-intelligence', 1, 0, 1, 30, 0),(157, NULL, 'Predictive Analytics ', 'predictive-analytics', 1, 0, 2, 30, 0),(158, NULL, 'Science & Technology', 'science-and-technology', 1, 0, 3, 30, 0),(159, NULL, 'Work Experience internship', 'work-experience-internship', 1, 0, 1, 31, 0),(160, NULL, 'Research internship', 'research-internship', 1, 0, 2, 31, 0),(161, NULL, 'Sales & Marketing Intern', 'sales-and-marketing-intern', 1, 0, 3, 31, 0),(162, NULL, 'According To Law', 'according-to-law', 1, 0, 1, 32, 0),(163, NULL, 'Defined Rule', 'defined-rule', 1, 0, 2, 32, 0),(164, NULL, 'Shipping ', 'shipping-', 1, 0, 1, 33, 0),(165, NULL, 'Transpotation Managment', 'transpotation-managment', 1, 0, 2, 33, 0),(166, NULL, 'Third-party Logistics Provider', 'third-party-logistics-provider', 1, 0, 3, 33, 0),(167, NULL, 'General Maintenance', 'general-maintenance', 1, 0, 1, 34, 0),(168, NULL, 'Automobile Maintenance', 'automobile-maintenance', 1, 0, 2, 34, 0),(169, NULL, 'Equipment Manitenance', 'equipment-manitenance', 1, 0, 3, 34, 0),(170, NULL, 'Project Managment', 'project-managment', 1, 0, 1, 35, 0),(171, NULL, 'Planning ', 'planning-', 1, 0, 2, 35, 0),(172, NULL, 'Risk Managment', 'risk-managment', 1, 0, 3, 35, 0),(173, NULL, 'Quality Assurance', 'quality-assurance', 1, 0, 1, 36, 0),(174, NULL, 'Product Manager', 'product-manager', 1, 0, 2, 36, 0),(175, NULL, 'Planning Supervisor', 'planning-supervisor', 1, 0, 3, 36, 0),(176, NULL, 'Networking ', 'networking-', 1, 0, 1, 37, 0),(177, NULL, 'Direct Mail Marketing', 'direct-mail-marketing', 1, 0, 2, 37, 0),(178, NULL, 'Media Advertising ', 'media-advertising-', 1, 0, 3, 37, 0),(179, NULL, 'Supply Chain', 'supply-chain', 1, 0, 1, 38, 0),(180, NULL, 'Hazardous Materials Management', 'hazardous-materials-management', 1, 0, 2, 38, 0),(181, NULL, 'Materials Inventory Managment', 'materials-inventory-managment', 1, 0, 3, 38, 0),(182, NULL, 'Aerospace', 'aerospace', 1, 0, 1, 39, 0),(183, NULL, 'Automotive', 'automotive', 1, 0, 2, 39, 0),(184, NULL, 'Biomedical', 'biomedical', 1, 0, 3, 39, 0),(185, NULL, 'Mechanical', 'mechanical', 1, 0, 4, 39, 0),(186, NULL, 'Naval', 'naval', 1, 0, 5, 39, 0),(187, NULL, 'Conventional Mortgage', 'conventional-mortgage', 1, 0, 1, 40, 0),(188, NULL, 'Adjustable Rate Mortgage', 'adjustable-rate-mortgage', 1, 0, 2, 40, 0),(189, NULL, 'Commercial Mortgages', 'commercial-mortgages', 1, 0, 3, 40, 0),(190, NULL, 'Economic Security', 'economic-security', 1, 0, 1, 41, 0),(191, NULL, 'Environmental Security', 'environmental-security', 1, 0, 2, 41, 0),(192, NULL, 'Military Security', 'military-security', 1, 0, 3, 41, 0),(193, NULL, 'Freelance Portfolios', 'freelance-portfolios', 1, 0, 1, 42, 0),(194, NULL, 'Freelance Freedom', 'freelance-freedom', 1, 0, 2, 42, 0),(195, NULL, 'Freelance Jobs', 'freelance-jobs', 1, 0, 3, 42, 0),(196, NULL, 'Offset Lithographp', 'offset-lithographp', 1, 0, 1, 43, 0),(197, NULL, 'Themography Raised Printing', 'themography-raised-printing', 1, 0, 2, 43, 0),(198, NULL, 'Digital Printing ', 'digital-printing-', 1, 0, 3, 43, 0),(199, NULL, 'idea Generation', 'idea-generation', 1, 0, 1, 44, 0),(200, NULL, 'Need Based Generation', 'need-based-generation', 1, 0, 2, 44, 0),(201, NULL, 'Design Solution', 'design-solution', 1, 0, 3, 44, 0),(202, NULL, 'Media Relations', 'media-relations', 1, 0, 1, 45, 0),(203, NULL, 'Media Tours ', 'media-tours-', 1, 0, 2, 45, 0),(204, NULL, 'Newsletters ', 'newsletters-', 1, 0, 3, 45, 0),(205, NULL, 'Automised Security', 'automised-security', 1, 0, 1, 46, 0),(206, NULL, 'Environmental & Social Safety', 'environmental-and-social-safety', 1, 0, 2, 46, 0),(207, NULL, 'Basic Research', 'basic-research', 1, 0, 1, 47, 0),(208, NULL, 'Applied Research', 'applied-research', 1, 0, 2, 47, 0),(209, NULL, 'Methods & Appraches', 'methods-and-appraches', 1, 0, 3, 47, 0),(210, NULL, 'Department Stores', 'department-stores', 1, 0, 1, 48, 0),(211, NULL, 'Discount Stores', 'discount-stores', 1, 0, 2, 48, 0),(212, NULL, 'Supermarkets', 'supermarkets', 1, 0, 3, 48, 0),(213, NULL, 'Sales Contracts', 'sales-contracts', 1, 0, 1, 49, 0),(214, NULL, 'Sales Forecasts', 'sales-forecasts', 1, 0, 2, 49, 0),(215, NULL, 'Sales Managment', 'sales-managment', 1, 0, 3, 49, 0),(216, NULL, 'Scientific Managment', 'scientific-managment', 1, 0, 1, 50, 0),(217, NULL, 'Scientific Research', 'scientific-research', 1, 0, 2, 50, 0),(218, NULL, 'Scientific invenctions', 'scientific-invenctions', 1, 0, 3, 50, 0),(219, NULL, 'Shppping/Distrubution Companies', 'shppping-distrubution companies', 1, 0, 1, 51, 0),(220, NULL, 'Services', 'services', 1, 0, 2, 51, 0),(221, NULL, 'Channels & Softwares', 'channels-and-softwares', 1, 0, 3, 51, 0),(222, NULL, 'Medical Technicians', 'medical-technicians', 1, 0, 1, 52, 0),(223, NULL, 'Electrical Technicians', 'electrical-technicians', 1, 0, 2, 52, 0),(224, NULL, 'Accounting Technicians', 'accounting-technicians', 1, 0, 3, 52, 0),(225, NULL, 'Construction Trade', 'construction-trade', 1, 0, 1, 53, 0),(226, NULL, 'Stock Trade', 'stock-trade', 1, 0, 2, 53, 0),(227, NULL, 'skilled Trade', 'skilled-trade', 1, 0, 3, 53, 0),(228, NULL, 'Option Trade', 'option-trade', 1, 0, 4, 53, 0),(229, NULL, 'Transpotation System', 'transpotation-system', 1, 0, 1, 54, 0),(230, NULL, 'Human-Powered', 'human-powered', 1, 0, 2, 54, 0),(231, NULL, 'Airline,Train,bus,car', 'airline-train-bus-car', 1, 0, 3, 54, 0),(232, NULL, 'Subway & Civil', 'subway-and-civil', 1, 0, 1, 55, 0),(233, NULL, 'Traffic Highway Transpotation', 'traffic-highway-transpotation', 1, 0, 2, 55, 0),(234, NULL, 'Small Business', 'small-business', 1, 0, 1, 56, 0),(235, NULL, 'E-Commerce Sites', 'e-commerce-sites', 1, 0, 2, 56, 0),(236, NULL, 'Portals', 'portals', 1, 0, 3, 56, 0),(237, NULL, 'Search Engines', 'search-engines', 1, 0, 4, 56, 0),(238, NULL, 'Personal,Commercial,Govt', 'personal-commercial-govt', 1, 0, 5, 56, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_cities` (
                      `id` mediumint(6) NOT NULL AUTO_INCREMENT,
                      `cityName` varchar(70) DEFAULT NULL,
                      `name` varchar(60) DEFAULT NULL,
                      `stateid` smallint(8) DEFAULT NULL,
                      `countryid` smallint(9) DEFAULT NULL,
                      `isedit` tinyint(1) DEFAULT '0',
                      `enabled` tinyint(1) NOT NULL DEFAULT '0',
                      `serverid` int(11) DEFAULT NULL,
					  `latitude` varchar(100) DEFAULT NULL,
					  `longitude` varchar(100) DEFAULT NULL,
                      PRIMARY KEY (`id`),
                      KEY `countryid` (`countryid`),
                      KEY `stateid` (`stateid`),
                      FULLTEXT KEY `name` (`name`)
                    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76932;";
            jsjobs::$_db->query($query);

			$query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_cities` (`id`, `cityName`, `name`, `stateid`, `countryid`, `isedit`, `enabled`, `serverid`, `latitude`, `longitude`) VALUES(69785, 'Karachi', 'Karachi', NULL, 126, 0, 1, 69788, '24.8614622', '67.0099388'),(70143, 'Adilpur', 'Adilpur', 0, 126, 1, 1, 70146, '27.9383133', '69.3190425'),(70015, 'Ahmadpur Sial', 'AhmadpurSial', NULL, 126, 0, 1, 70018, '30.6844519', '71.7652137'),(69836, 'AhmadpurEast', 'AhmadpurEast', 0, 126, 1, 1, 76866, '29.1411551', '71.2577233'),(70025, 'Akora', 'Akora', 0, 126, 1, 1, 70028, '34', '72.1333329'),(70147, 'Alik Ghund', 'AlikGhund', NULL, 126, 0, 1, 70150, '34.2203425', '72.2639358'),(69963, 'Alipur', 'Alipur', NULL, 126, 0, 1, 69966, '29.3881554', '70.9190132'),(70159, 'Alizai', 'Alizai', NULL, 126, 0, 1, 70162, '33.537239', '70.3435088'),(70009, 'Amangarh', 'Amangarh', NULL, 126, 0, 1, 70012, '34.0057866', '71.9296662'),(69855, 'Attock City', 'AttockCity', NULL, 126, 0, 1, 69858, '33.7687344', '72.362147'),(70054, 'Baddomalhi', 'Baddomalhi', 0, 126, 1, 1, 70057, '31.9885417', '74.6605365'),(69867, 'Badin', 'Badin', NULL, 126, 0, 1, 69870, '24.6557191', '68.837241'),(70100, 'Baffa', 'Baffa', NULL, 126, 0, 1, 70103, '34.4406901', '73.2205065'),(69831, 'Bahawalnagar', 'Bahawalnagar', NULL, 126, 0, 1, 69834, '29.9991825', '73.2588441'),(69796, 'Bahawalpur', 'Bahawalpur', NULL, 126, 0, 1, 69799, '29.3957215', '71.6833331'),(70169, 'Bakhri Ahmad Khan', 'BakhriAhmadKhan', NULL, 126, 0, 1, 70172, '30.7530953', '70.8655074'),(69908, 'Bannu', 'Bannu', NULL, 126, 0, 1, 69911, '32.989724', '70.6038334'),(69926, 'Basirpur', 'Basirpur', NULL, 126, 0, 1, 69929, '30.5779666', '73.8360251'),(70176, 'Basti Aukharvand', 'BastiAukharvand', NULL, 126, 0, 1, 70179, '26.155163', '68.3484602'),(70167, 'Basti Dosa', 'BastiDosa', NULL, 126, 0, 1, 70170, '30.7877207', '70.8676441'),(69914, 'Bat Khela', 'BatKhela', NULL, 126, 0, 1, 69917, '34.6137694', '71.9282781'),(70128, 'Begowala', 'Begowala', NULL, 126, 0, 1, 70131, '32.4389602', '74.2692839'),(70043, 'Bela', 'Bela', NULL, 126, 0, 1, 70046, '26.2267942', '66.3110971'),(70126, 'Berani', 'Berani', NULL, 126, 0, 1, 70129, '25.7868964', '68.8064137'),(70144, 'Bagarji', 'Bagarji', NULL, 126, 0, 1, 70147, '27.7559788', '68.7497777'),(69857, 'Bhakkar', 'Bhakkar', NULL, 126, 0, 1, 69860, '31.6265504', '71.0616623'),(69865, 'Bhalwal', 'Bhalwal', NULL, 126, 0, 1, 69868, '32.275141', '72.9047136'),(70074, 'Bhawana', 'Bhawana', NULL, 126, 0, 1, 70077, '31.5675547', '72.6565084'),(69972, 'Bhera', 'Bhera', NULL, 126, 0, 1, 69975, '32.4816761', '72.9076669'),(70095, 'Bhag', 'Bhag', NULL, 126, 0, 1, 70098, '29.0444314', '67.8241911'),(69854, 'Bhai Pheru', 'BhaiPheru', NULL, 126, 0, 1, 69857, '31.205974', '73.9418603'),(69802, 'Bhimbar', 'Bhimbar', NULL, 126, 0, 1, 69805, '32.9816247', '74.0711359'),(70112, 'Bhiria', 'Bhiria', NULL, 126, 0, 1, 70115, '26.910384', '68.1863546'),(70049, 'Bhit Shah', 'BhitShah', NULL, 126, 0, 1, 70052, '25.8020588', '68.4919235'),(70064, 'Bhan', 'Bhan', NULL, 126, 0, 1, 70067, '26.5566671', '67.719871'),(70062, 'Bhopalwala', 'Bhopalwala', NULL, 126, 0, 1, 70065, '32.4293472', '74.3635275'),(70140, 'Bandhi', 'Bandhi', NULL, 126, 0, 1, 70143, '26.5862194', '68.3011359'),(70130, 'Bozdar', 'Bozdar', NULL, 126, 0, 1, 70133, '27.183333', '68.6333329'),(69816, 'Burewala', 'Burewala', NULL, 126, 0, 1, 69819, '30.1577112', '72.6739675'),(70139, 'Barkhan', 'Barkhan', NULL, 126, 0, 1, 70142, '29.8972421', '69.5276135'),(70158, 'Bulri', 'Bulri', NULL, 126, 0, 1, 70161, '24.866667', '68.333333'),(70097, 'Chak', 'Chak', NULL, 126, 0, 1, 70100, '32.8124916', '74.090226'),(70056, 'Chak Two Hundred Forty-Nine TDA', 'ChakTwoHundredForty-NineTDA', NULL, 126, 0, 1, 70059, '33.3876953', '71.3408707'),(69938, 'Chak Azam Saffo', 'ChakAzamSaffo', NULL, 126, 0, 1, 69941, '30.7513979', '73.0294589'),(69843, 'Chakwal', 'Chakwal', NULL, 126, 0, 1, 69846, '32.9310991', '72.8550863'),(69850, 'Chaman', 'Chaman', NULL, 126, 0, 1, 69853, '30.907255', '66.4509585'),(70108, 'Chambar', 'Chambar', NULL, 126, 0, 1, 70111, '25.293292', '68.8138658'),(70022, 'Chawinda', 'Chawinda', NULL, 126, 0, 1, 70025, '32.3445875', '74.7059059'),(69856, 'Chichawatni', 'Chichawatni', NULL, 126, 0, 1, 69859, '30.5391319', '72.6919814'),(76865, 'check synchronize 1', 'check synchronize 1', 0, 126, 1, 1, 76871, NULL, NULL),(76866, 'check synchronize 2', 'check synchronize 2', 0, 126, 1, 1, 76872, NULL, NULL),(76867, 'check synchronize 3', 'check synchronize 3', 0, 126, 1, 1, 76873, NULL, NULL),(76868, 'check synchronize 4', 'check synchronize 4', 0, 126, 1, 1, 76874, NULL, NULL),(70161, 'Cherat', 'Cherat', NULL, 126, 0, 1, 70164, '33.8225317', '71.8904844'),(69876, 'Chuhar Kana', 'ChuharKana', NULL, 126, 0, 1, 69879, '31.7451915', '73.8330844'),(69813, 'Chiniot', 'Chiniot', NULL, 126, 0, 1, 69816, '31.7285872', '72.9814877'),(69834, 'Chishtian Mandi', 'ChishtianMandi', NULL, 126, 0, 1, 69837, '29.9092481', '73.0529761'),(76854, 'Chitral', 'Chitral', 86, 126, 0, 1, 76857, '35.8522867', '71.7871069'),(69895, 'Chunian', 'Chunian', NULL, 126, 0, 1, 69898, '30.9673834', '73.9741874'),(70085, 'Choa Saidan Shah', 'ChoaSaidanShah', NULL, 126, 0, 1, 70088, '32.7227733', '72.9844401'),(70071, 'Chor', 'Chor', NULL, 126, 0, 1, 70074, '25.5114274', '69.7823059'),(70168, 'Chowki Jamali', 'ChowkiJamali', NULL, 126, 0, 1, 70171, '28.0209905', '67.9210645'),(69847, 'Charsadda', 'Charsadda', NULL, 126, 0, 1, 69850, '34.1494329', '71.7427812'),(70082, 'Chuhar Jamali', 'ChuharJamali', NULL, 126, 0, 1, 70085, '24.3967645', '67.9911139'),(70174, 'Dajjal wala', 'Dajjalwala', NULL, 126, 0, 1, 70177, '', ''),(70087, 'Darya Khan', 'DaryaKhan', NULL, 126, 0, 1, 70090, '31.7812058', '71.1032591'),(70133, 'Darya Khan Marri', 'DaryaKhanMarri', NULL, 126, 0, 1, 70136, '26.6842052', '68.2832496'),(69829, 'Daska', 'Daska', NULL, 126, 0, 1, 69832, '32.334984', '74.3528822'),(70094, 'Daulatpur', 'Daulatpur', NULL, 126, 0, 1, 70097, '26.4944244', '67.9732288'),(70115, 'Daultala', 'Daultala', NULL, 126, 0, 1, 70118, '33.1925909', '73.1379109'),(70061, 'Daur', 'Daur', NULL, 126, 0, 1, 70064, '26.4545363', '68.3190222'),(70005, 'Daud Khel', 'DaudKhel', NULL, 126, 0, 1, 70008, '32.8718898', '71.569428'),(70086, 'Dadhar', 'Dadhar', NULL, 126, 0, 1, 70089, '29.479429', '67.6399'),(69825, 'Dadu', 'Dadu', NULL, 126, 0, 1, 69828, '26.7340581', '67.7794817'),(70057, 'Dera Bugti', 'DeraBugti', NULL, 126, 0, 1, 70060, '29.0351582', '69.1596093'),(69807, 'Dera Ghazi Khan', 'DeraGhaziKhan', NULL, 126, 0, 1, 69810, '30.0324857', '70.6402456'),(69842, 'Dera Ismail Khan', 'DeraIsmailKhan', NULL, 126, 0, 1, 69845, '31.8423621', '70.8952337'),(70051, 'Dhanot', 'Dhanot', NULL, 126, 0, 1, 70054, '27.6501099', '68.0393671'),(70114, 'Dhaunkal', 'Dhaunkal', NULL, 126, 0, 1, 70117, '32.4074383', '74.1416127'),(70044, 'Dhoro Naro', 'DhoroNaro', NULL, 126, 0, 1, 70047, '25.5048266', '69.5693225'),(69971, 'Digri', 'Digri', NULL, 126, 0, 1, 69974, '25.1511235', '69.1119247'),(69999, 'Dijkot', 'Dijkot', NULL, 126, 0, 1, 70002, '31.2179046', '72.9962491'),(69931, 'Dinga', 'Dinga', NULL, 126, 0, 1, 69934, '32.6417589', '73.7213063'),(70116, 'Diplo', 'Diplo', NULL, 126, 0, 1, 70119, '24.465469', '69.5827286'),(70088, 'Daira Din Panah', 'DairaDinPanah', NULL, 126, 0, 1, 70091, '30.5683543', '70.9365945'),(70060, 'Dajal', 'Dajal', NULL, 126, 0, 1, 70063, '29.5599903', '70.3747545'),(70093, 'Dalbandin', 'Dalbandin', NULL, 126, 0, 1, 70096, '28.8854045', '64.3963673'),(70135, 'Doaba', 'Doaba', NULL, 126, 0, 1, 70138, '33.4248352', '70.7376623'),(70091, 'Dokri', 'Dokri', NULL, 126, 0, 1, 70094, '27.371442', '68.0999173'),(69866, 'Dipalpur', 'Dipalpur', NULL, 126, 0, 1, 69869, '30.6673293', '73.6595075'),(69981, 'Dir', 'Dir', NULL, 126, 0, 1, 69984, '35.1976595', '71.8749209'),(70120, 'Daro Mehar', 'DaroMehar', NULL, 126, 0, 1, 70123, '27.1129952', '67.9575794'),(70154, 'Duki', 'Duki', NULL, 126, 0, 1, 70157, '30.1522019', '68.5783733'),(69909, 'Dullewala', 'Dullewala', NULL, 126, 0, 1, 69912, '31.8427075', '71.4358733'),(70008, 'Dunga Bunga', 'DungaBunga', NULL, 126, 0, 1, 70011, '29.7472966', '73.2440996'),(69960, 'Dunyapur', 'Dunyapur', NULL, 126, 0, 1, 69963, '29.8045422', '71.7405672'),(70028, 'Eminabad', 'Eminabad', NULL, 126, 0, 1, 70031, '32.0433183', '74.2560806'),(69787, 'Faisalabad', 'Faisalabad', NULL, 126, 0, 1, 69790, '31.4187142', '73.0791073'),(70014, 'Faqirwali', 'Faqirwali', NULL, 126, 0, 1, 70017, '29.476868', '73.0464309'),(70036, 'Faruka', 'Faruka', NULL, 126, 0, 1, 70039, '31.8248486', '72.5151944'),(69992, 'Fazalpur', 'Fazalpur', NULL, 126, 0, 1, 69995, '29.2931391', '70.452117'),(69929, 'Fort Abbas', 'FortAbbas', NULL, 126, 0, 1, 69932, '29.193053', '72.8574558'),(70104, 'Gadani', 'Gadani', NULL, 126, 0, 1, 70107, '25.1163445', '66.7278021'),(69961, 'Gambat', 'Gambat', NULL, 126, 0, 1, 69964, '27.352443', '68.5206879'),(69987, 'Garh Maharaja', 'GarhMaharaja', NULL, 126, 0, 1, 69990, '30.8361718', '71.9030828'),(70102, 'Garhi Khairo', 'GarhiKhairo', NULL, 126, 0, 1, 70105, '28.0592418', '67.9791769'),(70089, 'Garhi Yasin', 'GarhiYasin', NULL, 126, 0, 1, 70092, '27.9065243', '68.5157718'),(69982, 'Ghauspur', 'Ghauspur', NULL, 126, 0, 1, 69985, '28.1371171', '69.0821211'),(69888, 'Ghotki', 'Ghotki', NULL, 126, 0, 1, 69891, '28.0006901', '69.3189399'),(70048, 'Gharo', 'Gharo', NULL, 126, 0, 1, 70051, '24.7403038', '67.5842645'),(70127, 'Gilgit', 'Gilgit', NULL, 126, 0, 1, 70130, '35.920154', '74.3080126'),(69874, 'Gujar Khan', 'GujarKhan', NULL, 126, 0, 1, 69877, '33.2512989', '73.3060197'),(69826, 'Gojra', 'Gojra', NULL, 126, 0, 1, 69829, '31.1467905', '72.6852207'),(70151, 'Goth Garelo', 'GothGarelo', NULL, 126, 0, 1, 70154, '26.2955335', '68.6290501'),(69791, 'Gujranwala', 'Gujranwala', NULL, 126, 0, 1, 69794, '32.1543783', '74.1842254'),(69804, 'Gujrat', 'Gujrat', NULL, 126, 0, 1, 69807, '32.5711443', '74.075005'),(70170, 'Gulishah Kach', 'GulishahKach', NULL, 126, 0, 1, 70173, '30.7877207', '70.8676441'),(69900, 'Gwadar', 'Gwadar', NULL, 126, 0, 1, 69903, '25.1986951', '62.3213153'),(69906, 'Hadali', 'Hadali', NULL, 126, 0, 1, 69909, '32.2920892', '72.1919614'),(69947, 'Hangu', 'Hangu', NULL, 126, 0, 1, 69950, '33.528665', '71.067605'),(70118, 'Harnai', 'Harnai', NULL, 126, 0, 1, 70121, '30.0999687', '67.9434205'),(70083, 'Harnoli', 'Harnoli', NULL, 126, 0, 1, 70086, '32.2773356', '71.5545911'),(69897, 'Haripur', 'Haripur', NULL, 126, 0, 1, 69900, '33.9959837', '72.9367618'),(69918, 'Hasan Abdal', 'HasanAbdal', NULL, 126, 0, 1, 69921, '33.8194868', '72.6890255'),(69884, 'Haveli', 'Haveli', NULL, 126, 0, 1, 69887, '33.4687969', '72.1182872'),(69930, 'Havelian', 'Havelian', NULL, 126, 0, 1, 69933, '34.0465833', '73.1438116'),(70007, 'Hazro', 'Hazro', NULL, 126, 0, 1, 70010, '33.9101872', '72.4938607'),(69822, 'Hafizabad', 'Hafizabad', NULL, 126, 0, 1, 69825, '32.0716989', '73.6857291'),(70032, 'Hingorja', 'Hingorja', NULL, 126, 0, 1, 70035, '27.2105474', '68.4159067'),(69910, 'Hala', 'Hala', NULL, 126, 0, 1, 69913, '25.8167566', '68.4233593'),(69871, 'Harunabad', 'Harunabad', NULL, 126, 0, 1, 69874, '29.612972', '73.1408613'),(69852, 'Hasilpur', 'Hasilpur', NULL, 126, 0, 1, 69855, '29.690255', '72.5382293'),(69893, 'Hujra', 'Hujra', NULL, 126, 0, 1, 69896, '30.7355827', '73.8213213'),(69790, 'Hyderabad', 'Hyderabad', NULL, 126, 0, 1, 69793, '25.3817509', '68.3693897'),(76864, 'isedit city', 'isedit city', 0, 126, 1, 1, 76870, '30.3829282', '67.7243417'),(69795, 'Islamabad', 'Islamabad', NULL, 126, 0, 1, 69798, '33.7293882', '73.0931461'),(70110, 'Islamkot', 'Islamkot', NULL, 126, 0, 1, 70113, '24.7013799', '70.1783251'),(69817, 'Jacobabad', 'Jacobabad', NULL, 126, 0, 1, 69820, '28.2829348', '68.4364877'),(69988, 'Jahanian Shah', 'JahanianShah', NULL, 126, 0, 1, 69991, '32.7340812', '73.9315733'),(69848, 'Jalalpur', 'Jalalpur', NULL, 126, 0, 1, 69851, '32.6494831', '74.0553484'),(69940, 'Jalalpur Pirwala', 'JalalpurPirwala', NULL, 126, 0, 1, 69943, '29.5052835', '71.2220832'),(70055, 'Jand', 'Jand', NULL, 126, 0, 1, 70058, '33.4364645', '72.0171862'),(69835, 'Jaranwala', 'Jaranwala', 0, 126, 0, 1, 69838, '31.3328773', '73.4175824'),(69912, 'Jatoi Shimali', 'JatoiShimali', NULL, 126, 0, 1, 69915, '31.8427075', '71.4358733'),(69913, 'Jauharabad', 'Jauharabad', NULL, 126, 0, 1, 69916, '32.2899398', '72.271908'),(69803, 'Jhang Sadr', 'JhangSadr', NULL, 126, 0, 1, 69806, '31.2654657', '72.3123725'),(69995, 'Jhawarian', 'Jhawarian', NULL, 126, 0, 1, 69998, '32.3594749', '72.623987'),(69820, 'Jhelum', 'Jhelum', NULL, 126, 0, 1, 69823, '32.9405479', '73.7276293'),(70076, 'Jhol', 'Jhol', NULL, 126, 0, 1, 70079, '25.957297', '68.8898751'),(69942, 'Jhumra', 'Jhumra', NULL, 126, 0, 1, 69945, '31.5673242', '73.1828061'),(70148, 'Jam Sahib', 'JamSahib', NULL, 126, 0, 1, 70151, '26.2955335', '68.6290501'),(69890, 'Jampur', 'Jampur', NULL, 126, 0, 1, 69893, '29.6459464', '70.5919357'),(70119, 'Jandiala Sher Khan', 'JandialaSherKhan', NULL, 126, 0, 1, 70122, '31.8176025', '73.9212858'),(70070, 'Johi', 'Johi', NULL, 126, 0, 1, 70073, '26.6930979', '67.6155573'),(70131, 'Jati', 'Jati', NULL, 126, 0, 1, 70134, '24.3552401', '68.2668538'),(70069, 'Jiwani', 'Jiwani', NULL, 126, 0, 1, 70072, '25.0472733', '61.7458759'),(69894, 'Kabirwala', 'Kabirwala', NULL, 126, 0, 1, 69897, '30.4010623', '71.8630625'),(70150, 'Kadhan', 'Kadhan', NULL, 126, 0, 1, 70153, '24.4808475', '68.9852555'),(70029, 'Kahuta', 'Kahuta', NULL, 126, 0, 1, 70032, '33.5896138', '73.3885526'),(70081, 'Kallar Kahar', 'KallarKahar', NULL, 126, 0, 1, 70084, '32.7760323', '72.7008489'),(70012, 'Kalur Kot', 'KalurKot', NULL, 126, 0, 1, 70015, '32.0974997', '71.5439451'),(70109, 'Kalaswala', 'Kalaswala', NULL, 126, 0, 1, 70112, '32.199328', '74.6502902'),(70004, 'Kalat', 'Kalat', NULL, 126, 0, 1, 70007, '29.0303612', '66.5878767'),(70080, 'Kamar Mushani', 'KamarMushani', NULL, 126, 0, 1, 70083, '32.8432577', '71.359738'),(69860, 'Kambar', 'Kambar', NULL, 126, 0, 1, 69863, '27.5859189', '68.0060183'),(69838, 'Kamalia', 'Kamalia', NULL, 126, 0, 1, 69841, '30.7221821', '72.6446829'),(69814, 'Kamoke', 'Kamoke', NULL, 126, 0, 1, 69817, '31.9755071', '74.223802'),(70021, 'Kamir', 'Kamir', NULL, 126, 0, 1, 70024, '30.4273414', '73.0434794'),(69851, 'Kandhkot', 'Kandhkot', NULL, 126, 0, 1, 69854, '28.2425261', '69.183451'),(70149, 'Kandiari', 'Kandiari', NULL, 126, 0, 1, 70152, '25.7982864', '69.0687093'),(70001, 'Kandiaro', 'Kandiaro', NULL, 126, 0, 1, 70004, '27.0587825', '68.2117046'),(70035, 'Kanganpur', 'Kanganpur', NULL, 126, 0, 1, 70038, '30.7678277', '74.1210597'),(70101, 'Karak', 'Karak', NULL, 126, 0, 1, 70104, '33.1104787', '71.0913748'),(70141, 'Karaundi', 'Karaundi', NULL, 126, 0, 1, 70144, '26.1322972', '81.8572681'),(70156, 'Kario', 'Kario', NULL, 126, 0, 1, 70159, '24.8050055', '68.6052022'),(70010, 'Karor', 'Karor', NULL, 126, 0, 1, 70013, '31.22331', '70.95005'),(69962, 'Kashmor', 'Kashmor', NULL, 126, 0, 1, 69965, '28.4323282', '69.5916659'),(69806, 'Kasur', 'Kasur', NULL, 126, 0, 1, 69809, '31.1164769', '74.4493744'),(70162, 'Keti Bandar', 'KetiBandar', NULL, 126, 0, 1, 70165, '24.1299447', '67.453141'),(70152, 'Khadan Khak', 'KhadanKhak', NULL, 126, 0, 1, 70155, '25.4356826', '68.332437'),(70125, 'Khadro', 'Khadro', NULL, 126, 0, 1, 70128, '26.150341', '68.7169879'),(69833, 'Khairpur', 'Khairpur', NULL, 126, 0, 1, 69836, '27.5299523', '68.7581419'),(69980, 'Khairpur Nathan Shah', 'KhairpurNathanShah', NULL, 126, 0, 1, 69983, '27.0922441', '67.7347735'),(69933, 'Khalabat', 'Khalabat', NULL, 126, 0, 1, 69936, '34.0194023', '72.9135734'),(69968, 'Khewra', 'Khewra', NULL, 126, 0, 1, 69971, '32.630728', '73.0117665'),(69990, 'Khipro', 'Khipro', NULL, 126, 0, 1, 69993, '25.8241828', '69.3756586'),(69916, 'Kahna', 'Kahna', NULL, 126, 0, 1, 69919, '31.373857', '74.3675446'),(70046, 'Khangarh', 'Khangarh', NULL, 126, 0, 1, 70049, '29.9206348', '71.1626756'),(69979, 'Khangah Dogran', 'KhangahDogran', NULL, 126, 0, 1, 69982, '31.8315', '73.6241855'),(69824, 'Khanpur', 'Khanpur', NULL, 126, 0, 1, 69827, '28.6491211', '70.6514213'),(69858, 'Kharian', 'Kharian', NULL, 126, 0, 1, 69861, '32.8269819', '73.8459847'),(69977, 'Kharan', 'Kharan', NULL, 126, 0, 1, 69980, '28.5812029', '65.422281'),(69951, 'Khurrianwala', 'Khurrianwala', NULL, 126, 0, 1, 69954, '31.5215898', '73.2602441'),(69840, 'Khushab', 'Khushab', NULL, 126, 0, 1, 69843, '32.3054193', '72.3482384'),(70078, 'Kalabagh', 'Kalabagh', NULL, 126, 0, 1, 70081, '32.9623645', '71.5471724'),(70068, 'Kaleke Mandi', 'KalekeMandi', NULL, 126, 0, 1, 70071, '31.9750078', '73.6032755'),(69924, 'Kamra', 'Kamra', NULL, 126, 0, 1, 69927, '33.8558023', '72.3944135'),(70117, 'Kohlu', 'Kohlu', NULL, 126, 0, 1, 70120, '29.8975881', '69.2490136'),(69873, 'Kohror Pakka', 'KohrorPakka', NULL, 126, 0, 1, 69876, '29.6437628', '71.9164217'),(69823, 'Kohat', 'Kohat', NULL, 126, 0, 1, 69826, '33.5834014', '71.4332193'),(69839, 'Kot Addu', 'KotAddu', NULL, 126, 0, 1, 69842, '30.4615356', '70.9695403'),(70011, 'Kot Diji', 'KotDiji', NULL, 126, 0, 1, 70014, '27.3395429', '68.7065547'),(70041, 'Kot Ghulam Muhammad', 'KotGhulamMuhammad', NULL, 126, 0, 1, 70044, '25.289933', '69.2490136'),(69875, 'Kot Malik', 'KotMalik', NULL, 126, 0, 1, 69878, '28.4922222', '64.69'),(69936, 'Kot Mumin', 'KotMumin', NULL, 126, 0, 1, 69939, '32.1900283', '73.0257691'),(69915, 'Kot Radha Kishan', 'KotRadhaKishan', NULL, 126, 0, 1, 69918, '31.1687588', '74.1006772'),(70027, 'Kot Samaba', 'KotSamaba', NULL, 126, 0, 1, 70030, '28.5521456', '70.4699683');";
			jsjobs::$_db->query($query);

			$query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_cities` (`id`, `cityName`, `name`, `stateid`, `countryid`, `isedit`, `enabled`, `serverid`, `latitude`, `longitude`) VALUES(70124, 'Kot Sultan', 'KotSultan', NULL, 126, 0, 1, 70127, '30.7750133', '70.9368469'),(69794, 'Kotli', 'Kotli', NULL, 126, 0, 1, 69797, '33.5145095', '73.8993095'),(70037, 'Kotli Loharan', 'KotliLoharan', NULL, 126, 0, 1, 70040, '32.5877557', '74.4965215'),(69869, 'Kotri', 'Kotri', NULL, 126, 0, 1, 69872, '25.3540609', '68.2683443'),(70031, 'Kulachi', 'Kulachi', NULL, 126, 0, 1, 70034, '31.9328279', '70.4610427'),(69949, 'Kundian', 'Kundian', NULL, 126, 0, 1, 69952, '32.4525158', '71.4833652'),(69994, 'Kunjah', 'Kunjah', NULL, 126, 0, 1, 69997, '32.531894', '73.9741874'),(70003, 'Kunri', 'Kunri', NULL, 126, 0, 1, 70006, '25.1772515', '69.5648538'),(69932, 'Ladhewala Waraich', 'LadhewalaWaraich', NULL, 126, 0, 1, 69935, '32.1581941', '74.115187'),(69786, 'Lahore', 'Lahore', NULL, 126, 0, 1, 69789, '31.5546061', '74.3571581'),(70103, 'Lakhi', 'Lakhi', NULL, 126, 0, 1, 70106, '27.848942', '68.6998301'),(69945, 'Lakki Marwat', 'LakkiMarwat', NULL, 126, 0, 1, 69948, '32.6044506', '70.9130684'),(70164, 'Landi Kotal', 'LandiKotal', NULL, 126, 0, 1, 70167, '34.0909899', '71.1457517'),(70053, 'Lachi', 'Lachi', NULL, 126, 0, 1, 70056, '33.3876953', '71.3408707'),(69859, 'Leiah', 'Leiah', NULL, 126, 0, 1, 69862, '30.9647503', '70.9399349'),(70111, 'Liliani', 'Liliani', NULL, 126, 0, 1, 70114, '30.9909796', '70.5153376'),(69885, 'Lala Musa', 'LalaMusa', NULL, 126, 0, 1, 69888, '32.7037264', '73.9585169'),(69976, 'Lalian', 'Lalian', NULL, 126, 0, 1, 69979, '31.8233973', '72.8072342'),(69845, 'Lodhran', 'Lodhran', NULL, 126, 0, 1, 69848, '29.5363422', '71.6317359'),(69939, 'Loralai', 'Loralai', NULL, 126, 0, 1, 69942, '30.3740712', '68.5902973'),(69800, 'Larkana', 'Larkana', NULL, 126, 0, 1, 69803, '27.563994', '68.2151309'),(70065, 'Mach', 'Mach', NULL, 126, 0, 1, 70068, '29.8645414', '67.3294806'),(70090, 'Madeji', 'Madeji', NULL, 126, 0, 1, 70093, '27.753545', '68.4516583'),(69886, 'Mailsi', 'Mailsi', NULL, 126, 0, 1, 69889, '29.8042254', '72.1740413'),(69954, 'Malakwal City', 'MalakwalCity', NULL, 126, 0, 1, 69957, '32.5498983', '73.20281'),(69956, 'Malakwal', 'Malakwal', NULL, 126, 0, 1, 69959, '32.5498983', '73.20281'),(69827, 'Mandi Bahauddin', 'MandiBahauddin', NULL, 126, 0, 1, 69830, '32.5881687', '73.4973431'),(70073, 'Mangla', 'Mangla', NULL, 126, 0, 1, 70076, '33.1007838', '73.6514134'),(70121, 'Mankera', 'Mankera', NULL, 126, 0, 1, 70124, '31.3821495', '71.4477469'),(69805, 'Mardan', 'Mardan', NULL, 126, 0, 1, 69808, '34.2001138', '72.0508013'),(69989, 'Mastung', 'Mastung', NULL, 126, 0, 1, 69992, '29.7984166', '66.8469103'),(70050, 'Matiari', 'Matiari', NULL, 126, 0, 1, 70053, '25.597796', '68.4442267'),(69978, 'Mehar', 'Mehar', NULL, 126, 0, 1, 69981, '27.175294', '67.8152491'),(70157, 'Mehmand Chak', 'MehmandChak', NULL, 126, 0, 1, 70160, '32.7884623', '73.8227917'),(69952, 'Mehrabpur', 'Mehrabpur', NULL, 126, 0, 1, 69955, '27.102101', '68.4150795'),(69862, 'Mian Channun', 'MianChannun', NULL, 126, 0, 1, 69865, '30.4361208', '72.3488721'),(69986, 'Minchinabad', 'Minchinabad', NULL, 126, 0, 1, 69989, '30.1598144', '73.5682467'),(69810, 'Mingaora', 'Mingaora', NULL, 126, 0, 1, 69813, '34.7717466', '72.3601512'),(69849, 'Mianwali', 'Mianwali', NULL, 126, 0, 1, 69852, '32.5788912', '71.560526'),(70006, 'Mitha Tiwana', 'MithaTiwana', NULL, 126, 0, 1, 70009, '32.2416242', '72.1090303'),(70024, 'Mithi', 'Mithi', NULL, 126, 0, 1, 70027, '24.7400111', '69.7983444'),(69969, 'Mamu Kanjan', 'MamuKanjan', NULL, 126, 0, 1, 69972, '30.7287704', '72.661514'),(69991, 'Mananwala', 'Mananwala', NULL, 126, 0, 1, 69994, '31.4729556', '74.4195864'),(69882, 'Mansehra', 'Mansehra', NULL, 126, 0, 1, 69885, '34.3338823', '73.2010622'),(69861, 'Moro', 'Moro', NULL, 126, 0, 1, 69864, '26.6591938', '68.0060183'),(70171, 'Moza Shahwala', 'MozaShahwala', NULL, 126, 0, 1, 70174, '28.0209905', '67.9210645'),(70160, 'Miram Shah', 'MiramShah', NULL, 126, 0, 1, 70163, '33.0073', '70.0652004'),(70123, 'Miro Khan', 'MiroKhan', NULL, 126, 0, 1, 70126, '27.7589814', '68.0924649'),(70136, 'Mirpur Batoro', 'MirpurBatoro', NULL, 126, 0, 1, 70139, '24.7304503', '68.2594012'),(69812, 'Mirpur Khas', 'MirpurKhas', NULL, 126, 0, 1, 69815, '25.5291051', '69.0135706'),(69907, 'Mirpur Mathelo', 'MirpurMathelo', NULL, 126, 0, 1, 69910, '28.0208426', '69.552937'),(70142, 'Mirpur Sakro', 'MirpurSakro', NULL, 126, 0, 1, 70145, '24.5473691', '67.6259883'),(70113, 'Mirwah Gorchani', 'MirwahGorchani', NULL, 126, 0, 1, 70116, '25.3097741', '69.0478462'),(69904, 'Matli', 'Matli', NULL, 126, 0, 1, 69907, '25.0467638', '68.6558788'),(69789, 'Multan', 'Multan', NULL, 126, 0, 1, 69792, '30.1983807', '71.4687028'),(69819, 'Muridke', 'Muridke', NULL, 126, 0, 1, 69822, '31.8024737', '74.2590148'),(70013, 'Murree', 'Murree', NULL, 126, 0, 1, 70016, '33.9077736', '73.3914997'),(69917, 'Mustafabad', 'Mustafabad', NULL, 126, 0, 1, 69920, '32.1913216', '74.2324224'),(70045, 'Muzaffarabad', 'Muzaffarabad', NULL, 126, 0, 1, 70048, '34.3596867', '73.471054'),(69818, 'Muzaffargarh', 'Muzaffargarh', NULL, 126, 0, 1, 69821, '30.0736087', '71.1804988'),(70155, 'Nabisar', 'Nabisar', NULL, 126, 0, 1, 70158, '25.0681398', '69.6437982'),(69898, 'Nankana Sahib', 'NankanaSahib', NULL, 126, 0, 1, 69901, '31.4507815', '73.7036514'),(69996, 'Nasirabad', 'Nasirabad', NULL, 126, 0, 1, 69999, '27.3799987', '67.915103'),(69964, 'Naudero', 'Naudero', NULL, 126, 0, 1, 69967, '27.6670437', '68.3637381'),(70033, 'Naukot', 'Naukot', NULL, 126, 0, 1, 70036, '24.854836', '69.4099249'),(69928, 'Naushahra Virkan', 'NaushahraVirkan', NULL, 126, 0, 1, 69931, '31.9661054', '73.977126'),(70059, 'Naushahro Firoz', 'NaushahroFiroz', NULL, 126, 0, 1, 70062, '26.8462951', '68.1252555'),(69809, 'Nawabshah', 'Nawabshah', NULL, 126, 0, 1, 69812, '26.2442211', '68.4100338'),(69934, 'New Badah', 'NewBadah', NULL, 126, 0, 1, 69937, '31.1141733', '74.4292967'),(70173, 'Noorabad', 'Noorabad', NULL, 126, 0, 1, 70176, '26.155163', '68.3484602'),(70175, 'Nooriabad', 'Nooriabad', NULL, 126, 0, 1, 70178, '25.2739885', '68.4869861'),(69846, 'Nowshera Cantonment', 'NowsheraCantonment', NULL, 126, 0, 1, 69849, '34.0055051', '72.0082966'),(69955, 'Narang', 'Narang', NULL, 126, 0, 1, 69958, '33.1796667', '72.7643901'),(69878, 'Narowal', 'Narowal', NULL, 126, 0, 1, 69881, '32.0994756', '74.8747353'),(69997, 'Nushki', 'Nushki', NULL, 126, 0, 1, 70000, '29.5558052', '66.0195894'),(69811, 'Okara', 'Okara', NULL, 126, 0, 1, 69814, '30.8090496', '73.4508207'),(70107, 'Ormara', 'Ormara', NULL, 126, 0, 1, 70110, '25.2665929', '64.6095785'),(69941, 'Pabbi', 'Pabbi', NULL, 126, 0, 1, 69944, '34.0113944', '71.7941607'),(70040, 'Pad Idan', 'PadIdan', NULL, 126, 0, 1, 70043, '26.7757114', '68.2951738'),(70063, 'Paharpur', 'Paharpur', NULL, 126, 0, 1, 70066, '32.1056403', '70.9722619'),(76858, 'Panjpai', 'Panjpai', 88, 126, 0, 1, 76861, '29.9132123', '66.4956023'),(69965, 'Pasni', 'Pasni', NULL, 126, 0, 1, 69968, '25.281566', '63.4036287'),(69899, 'Pasrur', 'Pasrur', NULL, 126, 0, 1, 69902, '32.2639737', '74.6693186'),(69872, 'Pattoki', 'Pattoki', NULL, 126, 0, 1, 69875, '31.0249269', '73.8479317'),(69792, 'Peshawar', 'Peshawar', NULL, 126, 0, 1, 69795, '34.0149748', '71.5804899'),(70016, 'Phalia', 'Phalia', NULL, 126, 0, 1, 70019, '32.4217135', '73.5770801'),(70145, 'Phulji', 'Phulji', NULL, 126, 0, 1, 70148, '26.8813924', '67.6826153'),(70034, 'Pind Dadan Khan', 'PindDadanKhan', NULL, 126, 0, 1, 70037, '32.5883708', '73.0434794'),(69953, 'Pindi Bhattian', 'PindiBhattian', NULL, 126, 0, 1, 69956, '31.8950469', '73.2706389'),(76856, 'Pindi Gheb', 'Pindi Gheb', 89, 126, 0, 1, 76859, '33.2451991', '72.2659868'),(69958, 'Pindi Gheb', 'PindiGheb', NULL, 126, 0, 1, 69961, '33.2451991', '72.2659868'),(70020, 'Pishin', 'Pishin', NULL, 126, 0, 1, 70023, '30.5842128', '66.9958226'),(70153, 'Pithoro', 'Pithoro', NULL, 126, 0, 1, 70156, '25.5105787', '69.3756586'),(69830, 'Pakpattan', 'Pakpattan', NULL, 126, 0, 1, 69833, '30.3524565', '73.3885526'),(69868, 'Pano Aqil', 'PanoAqil', NULL, 126, 0, 1, 69871, '27.8144456', '69.1089444'),(69948, 'Pirjo Goth', 'PirjoGoth', NULL, 126, 0, 1, 69951, '27.6321715', '68.5962593'),(69950, 'Pir Mahal', 'PirMahal', NULL, 126, 0, 1, 69953, '30.7654756', '72.4343685'),(70042, 'Qadirpur Ran', 'QadirpurRan', NULL, 126, 0, 1, 70045, '30.2911176', '71.6719869'),(69793, 'Quetta', 'Quetta', NULL, 126, 0, 1, 69796, '30.1829713', '66.998734'),(70096, 'Rasulnagar', 'Rasulnagar', NULL, 126, 0, 1, 70099, '32.3272407', '73.7794679'),(69911, 'Ratodero', 'Ratodero', NULL, 126, 0, 1, 69914, '27.8025876', '68.2892117'),(70066, 'Radhan', 'Radhan', NULL, 126, 0, 1, 70069, '27.1984727', '67.9534808'),(69943, 'Renala Khurd', 'RenalaKhurd', NULL, 126, 0, 1, 69946, '30.8800345', '73.6003308'),(69853, 'Arifwala', 'Arifwala', NULL, 126, 0, 1, 69856, '30.2978601', '73.0582368'),(69944, 'Risalpur', 'Risalpur', NULL, 126, 0, 1, 69947, '34.0751141', '71.9875531'),(69975, 'Raiwind', 'Raiwind', NULL, 126, 0, 1, 69978, '31.2456587', '74.2128875'),(70018, 'Raja Jang', 'RajaJang', NULL, 126, 0, 1, 70021, '31.2205548', '74.2560806'),(69901, 'Rajanpur', 'Rajanpur', NULL, 126, 0, 1, 69904, '29.1017686', '70.3244659'),(70146, 'Rajo Khanani', 'RajoKhanani', NULL, 126, 0, 1, 70149, '24.9845653', '68.8541063'),(70030, 'Ranipur', 'Ranipur', NULL, 126, 0, 1, 70033, '27.2847838', '68.5071364'),(69902, 'Rohri', 'Rohri', NULL, 126, 0, 1, 69905, '27.675196', '68.9003075'),(70106, 'Rojhan', 'Rojhan', NULL, 126, 0, 1, 70109, '28.6794257', '69.9609892'),(70132, 'Rustam jo Goth', 'RustamjoGoth', NULL, 126, 0, 1, 70135, '24.8810774', '67.0316723'),(69905, 'Rawala Kot', 'RawalaKot', NULL, 126, 0, 1, 69908, '33.8567701', '73.7588945'),(69788, 'Rawalpindi', 'Rawalpindi', NULL, 126, 0, 1, 69791, '33.598394', '73.0441352'),(69973, 'Sakrand', 'Sakrand', NULL, 126, 0, 1, 69976, '26.1520262', '68.2683443'),(70138, 'Samaro', 'Samaro', NULL, 126, 0, 1, 70141, '25.2841367', '69.3935368'),(69891, 'Sambrial', 'Sambrial', NULL, 126, 0, 1, 69894, '32.4785685', '74.3528822'),(70122, 'Sanjwal', 'Sanjwal', NULL, 126, 0, 1, 70125, '33.7601226', '72.4339514'),(70137, 'Sann', 'Sann', NULL, 126, 0, 1, 70140, '26.0382473', '68.1341985'),(76859, 'Sararogha', 'Sararogha', 87, 126, 0, 1, 76862, '33.2451991', '72.2659868'),(69797, 'Sargodha', 'Sargodha', NULL, 126, 0, 1, 69800, '32.0837411', '72.6718596'),(69922, 'Sarai Alamgir', 'SaraiAlamgir', NULL, 126, 0, 1, 69925, '32.9002189', '73.7590686'),(70047, 'Sarai Naurang', 'SaraiNaurang', NULL, 126, 0, 1, 70050, '32.8230028', '70.7822635'),(70084, 'Sarai Sidhu', 'SaraiSidhu', NULL, 126, 0, 1, 70087, '30.5975528', '71.9727354'),(69815, 'Sadiqabad', 'Sadiqabad', NULL, 126, 0, 1, 69818, '28.3083941', '70.133673'),(69927, 'Sehwan', 'Sehwan', NULL, 126, 0, 1, 69930, '26.4234157', '67.8629399'),(69967, 'Setharja Old', 'SetharjaOld', NULL, 126, 0, 1, 69970, '27.6670437', '68.3637381'),(69881, 'Shabqadar', 'Shabqadar', NULL, 126, 0, 1, 69884, '34.2185757', '71.5545911'),(70075, 'Shahr Sultan', 'ShahrSultan', NULL, 126, 0, 1, 70078, '29.5756193', '71.0209738'),(69887, 'Shakargarr', 'Shakargarr', NULL, 126, 0, 1, 69890, '32.2642802', '75.1598723'),(69970, 'Sharqpur', 'Sharqpur', NULL, 126, 0, 1, 69973, '31.4632106', '74.103441'),(69801, 'Sheikhupura', 'Sheikhupura', NULL, 126, 0, 1, 69804, '31.7166617', '73.9850243'),(76860, 'Shewa', 'Shewa', 87, 126, 0, 1, 76863, '33.2536611', '70.4967441'),(69864, 'Shahdadkot', 'Shahdadkot', NULL, 126, 0, 1, 69867, '27.848312', '67.9106318'),(69880, 'Shahdadpur', 'Shahdadpur', NULL, 126, 0, 1, 69883, '25.9268081', '68.6260691'),(70098, 'Shahpur', 'Shahpur', NULL, 126, 0, 1, 70101, '32.2866118', '72.4302529'),(70038, 'Shahpur Chakar', 'ShahpurChakar', NULL, 126, 0, 1, 70041, '26.1543553', '68.6505791'),(69821, 'Shikarpur', 'Shikarpur', NULL, 126, 0, 1, 69824, '27.9570397', '68.637993'),(70172, 'Shinpokh', 'Shinpokh', NULL, 126, 0, 1, 70175, '34.8644422', '-89.860363'),(69879, 'Shorko', 'Shorko', NULL, 126, 0, 1, 69882, '30.8318336', '72.0764437'),(69883, 'Shujaabad', 'Shujaabad', NULL, 126, 0, 1, 69886, '29.8764183', '71.3171162'),(69808, 'Sahiwal', 'Sahiwal', NULL, 126, 0, 1, 69811, '30.6611813', '73.1085756'),(69889, 'Sibi', 'Sibi', NULL, 126, 0, 1, 69892, '29.5395519', '67.8759078'),(69798, 'Sialkot', 'Sialkot', NULL, 126, 0, 1, 69801, '32.4924769', '74.5310403'),(70000, 'Sillanwali', 'Sillanwali', NULL, 126, 0, 1, 70003, '31.8248489', '72.5411869'),(70079, 'Sinjhoro', 'Sinjhoro', NULL, 126, 0, 1, 70082, '26.03067', '68.8079041'),(70166, 'Skardu', 'Skardu', NULL, 126, 0, 1, 70169, '35.2900896', '75.6453431'),(69892, 'Sanghar', 'Sanghar', NULL, 126, 0, 1, 69895, '26.0444193', '68.9538789'),(69896, 'Sangla', 'Sangla', NULL, 126, 0, 1, 69899, '31.713381', '73.373016'),(70092, 'Sobhadero', 'Sobhadero', NULL, 126, 0, 1, 70095, '27.3076123', '68.4024919'),(70077, 'Sodhra', 'Sodhra', NULL, 126, 0, 1, 70080, '32.465509', '74.1856472'),(70134, 'Sohbatpur', 'Sohbatpur', NULL, 126, 0, 1, 70137, '28.5188198', '68.5411106'),(76855, 'Spinwam', 'Spinwam', 87, 126, 0, 1, 76858, '33.1737486', '70.401907'),(70105, 'Surab', 'Surab', NULL, 126, 0, 1, 70108, '28.4901014', '66.2634928'),(69998, 'Sita Road', 'SitaRoad', NULL, 126, 0, 1, 70001, '27.042879', '67.853367'),(69966, 'Sukheke Mandi', 'SukhekeMandi', NULL, 126, 0, 1, 69969, '31.8665471', '73.5019838'),(69799, 'Sukkur', 'Sukkur', NULL, 126, 0, 1, 69802, '27.7066038', '68.8481971'),(76857, 'Swat', 'Swat', 86, 126, 0, 1, 76860, '35.4920326', '72.5204827'),(69844, 'Swabi', 'Swabi', NULL, 126, 0, 1, 69847, '34.1164164', '72.4642776'),(69919, 'Talagang', 'Talagang', NULL, 126, 0, 1, 69922, '32.9298214', '72.4198967'),(69993, 'Talamba', 'Talamba', NULL, 126, 0, 1, 69996, '30.5239319', '72.2393399'),(70039, 'Talhar', 'Talhar', NULL, 126, 0, 1, 70042, '24.8883678', '68.8139269'),(69828, 'Tando Allahyar', 'TandoAllahyar', NULL, 126, 0, 1, 69831, '25.4570396', '68.7214593'),(70099, 'Tando Bago', 'TandoBago', NULL, 126, 0, 1, 70102, '24.7887164', '68.9655333'),(69832, 'Tando Adam', 'TandoAdam', NULL, 126, 0, 1, 69835, '25.7685204', '68.6625448'),(70072, 'Tando Ghulam Ali', 'TandoGhulamAli', NULL, 126, 0, 1, 70075, '25.1242171', '68.8913654'),(69974, 'Tando Jam', 'TandoJam', NULL, 126, 0, 1, 69977, '25.4298913', '68.5426011'),(69870, 'Tando Muhammad Khan', 'TandoMuhammadKhan', NULL, 126, 0, 1, 69873, '25.1228751', '68.5350842'),(69983, 'Tangi', 'Tangi', NULL, 126, 0, 1, 69986, '34.3008142', '71.6525026'),(70129, 'Tangwani', 'Tangwani', NULL, 126, 0, 1, 70132, '28.242434', '68.779462'),(69920, 'Taunsa', 'Taunsa', NULL, 126, 0, 1, 69923, '30.7059921', '70.6484471'),(69921, 'Thatta', 'Thatta', NULL, 126, 0, 1, 69924, '24.7469887', '67.9255357'),(70058, 'Tharu Shah', 'TharuShah', NULL, 126, 0, 1, 70061, '26.9426981', '68.1163126'),(69957, 'Thul', 'Thul', NULL, 126, 0, 1, 69960, '28.2396296', '68.7755104'),(69985, 'Tal', 'Tal', NULL, 126, 0, 1, 69988, '34.6855556', '73.2611111'),(69937, 'Tandlianwala', 'Tandlianwala', NULL, 126, 0, 1, 69940, '31.0368217', '73.1379109'),(70163, 'Tando Mittha Khan', 'TandoMitthaKhan', NULL, 126, 0, 1, 70166, '25.995584', '69.199842'),(69935, 'Tank', 'Tank', NULL, 126, 0, 1, 69938, '32.2142959', '70.3777302'),(69877, 'Toba Tek Singh', 'TobaTekSingh', NULL, 126, 0, 1, 69880, '30.9726705', '72.4849861'),(69946, 'Topi', 'Topi', NULL, 126, 0, 1, 69949, '34.065904', '72.6358134'),(69863, 'Turbat', 'Turbat', NULL, 126, 0, 1, 69866, '26.0080546', '63.0383059'),(70023, 'Ubauro', 'Ubauro', NULL, 126, 0, 1, 70026, '28.1659641', '69.7316714'),(69925, 'Umarkot', 'Umarkot', NULL, 126, 0, 1, 69928, '25.3614443', '69.7435857'),(69923, 'Usta Muhammad', 'UstaMuhammad', NULL, 126, 0, 1, 69926, '28.1792746', '68.04477'),(70067, 'Uthal', 'Uthal', NULL, 126, 0, 1, 70070, '25.8014437', '66.6206225'),(69984, 'Utmanzai', 'Utmanzai', NULL, 126, 0, 1, 69987, '34.2204177', '71.4430876'),(69837, 'Vihari', 'Vihari', NULL, 126, 0, 1, 69840, '30.0452462', '72.3488721'),(70019, 'Warburton', 'Warburton', NULL, 126, 0, 1, 70022, '31.5398053', '73.8330844'),(69841, 'Wazirabad', 'Wazirabad', NULL, 126, 0, 1, 69844, '32.4386254', '74.1169906'),(70165, 'Wana', 'Wana', NULL, 126, 0, 1, 70168, '32.3006562', '69.5797495'),(70052, 'Warah', 'Warah', NULL, 126, 0, 1, 70055, '27.4476667', '67.7943847'),(70017, 'Yazman Mandi', 'YazmanMandi', NULL, 126, 0, 1, 70020, '29.1257774', '71.7513816'),(70026, 'Zafarwal', 'Zafarwal', NULL, 126, 0, 1, 70029, '32.3401285', '74.9062622'),(70002, 'Zaida', 'Zaida', NULL, 126, 0, 1, 70005, '34.0548135', '72.467236'),(69959, 'Zahir Pir', 'ZahirPir', NULL, 126, 0, 1, 69962, '28.8136245', '70.5145939'),(69903, 'Zhob', 'Zhob', NULL, 126, 0, 1, 69906, '31.3412607', '69.4486593'),(76861, 'Ziarat', 'Ziarat', 88, 126, 0, 1, 76864, '30.3829282', '67.7243417');";
			jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_companies` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) DEFAULT NULL,
              `category` int(11) NOT NULL DEFAULT '0',
              `name` varchar(255) NOT NULL DEFAULT '',
              `alias` varchar(225) NOT NULL,
              `url` varchar(255) DEFAULT NULL,
              `logofilename` varchar(100) DEFAULT NULL,
              `logoisfile` tinyint(1) DEFAULT '-1',
              `logo` blob,
              `smalllogofilename` varchar(100) DEFAULT NULL,
              `smalllogoisfile` tinyint(1) DEFAULT '-1',
              `smalllogo` tinyblob,
              `aboutcompanyfilename` varchar(100) DEFAULT NULL,
              `aboutcompanyisfile` tinyint(1) DEFAULT '-1',
              `aboutcompanyfilesize` varchar(100) DEFAULT NULL,
              `aboutcompany` mediumblob,
              `contactname` varchar(255) NOT NULL DEFAULT '',
              `contactphone` varchar(255) DEFAULT NULL,
              `companyfax` varchar(250) DEFAULT NULL,
              `contactemail` varchar(255) NOT NULL DEFAULT '',
              `since` datetime DEFAULT NULL,
              `companysize` varchar(255) DEFAULT NULL,
              `income` varchar(255) DEFAULT NULL,
              `description` text,
              `country` varchar(255) NOT NULL DEFAULT '0',
              `state` varchar(255) DEFAULT NULL,
              `county` varchar(255) DEFAULT NULL,
              `city` varchar(255) DEFAULT NULL,
              `zipcode` varchar(25) DEFAULT NULL,
              `address1` varchar(255) DEFAULT NULL,
              `address2` varchar(255) DEFAULT NULL,
              `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `modified` datetime DEFAULT NULL,
              `hits` int(11) DEFAULT NULL,
              `metadescription` text,
              `metakeywords` text,
              `status` tinyint(1) NOT NULL DEFAULT '0',
              `packageid` int(11) DEFAULT NULL,
              `paymenthistoryid` int(11) DEFAULT NULL,
              `isgoldcompany` tinyint(1) DEFAULT '0',
              `startgolddate` datetime DEFAULT NULL,
              `endgolddate` datetime NOT NULL,
              `endfeatureddate` datetime NOT NULL,
              `isfeaturedcompany` tinyint(1) DEFAULT '0',
              `startfeatureddate` datetime DEFAULT NULL,
              `params` longtext,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              `facebook` varchar(300) NOT NULL,
              `twitter` varchar(300) NOT NULL,
              `googleplus` varchar(300) NOT NULL,
              `linkedin` varchar(300) NOT NULL,
              PRIMARY KEY (`id`),
              KEY `companies_uid` (`uid`),
              KEY `companies_category` (`category`),
              KEY `companies_packageid` (`packageid`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            jsjobs::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_companycities` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `companyid` int(11) NOT NULL,
              `cityid` int(11) NOT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `companyid` (`companyid`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            jsjobs::$_db->query($query);



            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_config` (
              `configname` varchar(100) NOT NULL DEFAULT '',
              `configvalue` varchar(255) NOT NULL DEFAULT '',
              `configfor` varchar(50) DEFAULT NULL,
              PRIMARY KEY (`configname`),
              FULLTEXT KEY `config_name` (`configname`),
              FULLTEXT KEY `config_for` (`configfor`)
            ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
            jsjobs::$_db->query($query);

            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_config` (`configname`, `configvalue`, `configfor`) VALUES
            ('companyautoapprove', '1', 'company'),
            ('comp_city', '1', 'company'),
            ('comp_zipcode', '1', 'company'),
            ('cur_location', '1', 'jsjobs'),
            ('empautoapprove', '1', 'resume'),
            ('jobautoapprove', '1', 'job'),
            ('job_editor', '1', 'job'),
            ('mailfromaddress', 'sender@yourdomain.com', 'email'),
            ('mailfromname', 'JS Jobs', 'email'),
            ('newdays', '7', 'job'),
            ('indeedjob_showafter', '', 'indeedjob'),
            ('indeedjob_jobperrequest', '', 'indeedjob'),
            ('js_cpnotification', '1', 'jscontrolpanel'),
            ('js_cpmessage', '1', 'jscontrolpanel'),
            ('rss_resume_email', '1', 'rss'),
            ('search_job_showsave', '1', 'searchjob'),
            ('careerbuilder_enabled', '0', 'careerbuilder'),
            ('actk', '0', 'jsjobs'),
            ('careerbuilder_showafter', '0', 'careerbuilder'),
            ('careerbuilder_jobperrequest', '', 'careerbuilder'),
            ('careerbuilder_emptype', '', 'careerbuilder'),
            ('search_resume_showsave', '1', 'resume'),
            ('careerbuilder_countrycode', '', 'careerbuilder'),
            ('showemployerlink', '1', 'jsjobs'),
            ('title', 'JS Jobs', 'default'),
            ('data_directory', 'jsjobsdata', 'jsjobs'),
            ('refercode', '', 'jsjobs'),
            ('careerbuilder_category', '', 'careerbuilder'),
            ('company_logofilezize', '50', 'company'),
            ('resume_photofilesize', '2048', 'resume'),
            ('offline', '0', 'jsjobs'),
            ('offline_text', '', 'jsjobs'),
            ('careerbuilder_developerkey', '', 'careerbuilder'),
            ('visitorview_js_controlpanel', '1', 'visitor'),
            ('visitorview_js_jobcat', '1', 'visitor'),
            ('indeedjob_jobtype', '', 'indeedjob'),
            ('visitorview_js_newestjobs', '1', 'visitor'),
            ('visitorview_js_jobsearch', '1', 'visitor'),
            ('visitorview_js_jobsearchresult', '1', 'visitor'),
            ('visitorview_emp_conrolpanel', '1', 'visitor'),
            ('visitorview_emp_viewcompany', '1', 'visitor'),
            ('visitorview_emp_viewjob', '1', 'visitor'),
            ('allow_jobshortlist', '1', 'jsjobs'),
            ('featuredjob_autoapprove', '1', 'featuredjob'),
            ('goldjob_autoapprove', '1', 'goldjob'),
            ('featuredcompany_autoapprove', '1', 'company'),
            ('goldcompany_autoapprove', '1', 'company'),
            ('featuredresume_autoapprove', '1', 'featuredresume'),
            ('goldresume_autoapprove', '1', 'goldresume'),
            ('date_format', 'm/d/Y', 'default'),
            ('adminemailaddress', 'admin@yourdomain.com', 'email'),
            ('message_auto_approve', '1', 'messages'),
            ('conflict_message_auto_approve', '1', 'messages'),
            ('overwrite_jobalert_settings', '1', 'visitor'),
            ('visitor_can_apply_to_job', '1', 'visitor'),
            ('visitor_show_login_message', '1', 'visitor'),
            ('folder_auto_approve', '1', 'folder'),
            ('department_auto_approve', '1', 'department'),
            ('formcompany', '1', 'emcontrolpanel'),
            ('mycompanies', '1', 'emcontrolpanel'),
            ('formjob', '1', 'emcontrolpanel'),
            ('myjobs', '1', 'emcontrolpanel'),
            ('formdepartment', '1', 'emcontrolpanel'),
            ('mydepartment', '1', 'emcontrolpanel'),
            ('empmessages', '1', 'emcontrolpanel'),
            ('alljobsappliedapplications', '1', 'emcontrolpanel'),
            ('resumesearch', '1', 'emcontrolpanel'),
            ('my_resumesearches', '1', 'emcontrolpanel'),
            ('my_stats', '1', 'emcontrolpanel'),
            ('myfolders', '1', 'emcontrolpanel'),
            ('formresume', '1', 'jscontrolpanel'),
            ('myresumes', '1', 'jscontrolpanel'),
            ('formcoverletter', '1', 'jscontrolpanel'),
            ('mycoverletters', '1', 'jscontrolpanel'),
            ('jspurchasehistory', '1', 'jscontrolpanel'),
            ('jobalertsetting', '1', 'jscontrolpanel'),
            ('jobcat', '1', 'jscontrolpanel'),
            ('listnewestjobs', '1', 'jscontrolpanel'),
            ('myappliedjobs', '1', 'jscontrolpanel'),
            ('jobsearch', '1', 'jscontrolpanel'),
            ('my_jobsearches', '1', 'jscontrolpanel'),
            ('jsmy_stats', '1', 'jscontrolpanel'),
            ('jsmessages', '1', 'jscontrolpanel'),
            ('tmenu_emcontrolpanel', '1', 'topmenu'),
            ('tmenu_emnewjob', '1', 'topmenu'),
            ('tmenu_emmyjobs', '1', 'topmenu'),
            ('tmenu_emmycompanies', '1', 'topmenu'),
            ('tmenu_emsearchresume', '1', 'topmenu'),
            ('tmenu_jscontrolpanel', '1', 'topmenu'),
            ('tmenu_jsjobcategory', '1', 'topmenu'),
            ('tmenu_jssearchjob', '1', 'topmenu'),
            ('tmenu_jsnewestjob', '1', 'topmenu'),
            ('tmenu_jsmyresume', '1', 'topmenu'),
            ('show_applied_resume_status', '1', 'jobapply'),
            ('jobalert_auto_approve', '0', 'jobalert'),
            ('resume_contact_detail', '1', 'resume'),
            ('api_primary', 'a2d09c7d76fced01f8be4b1f4cce8bec', 'api'),
            ('api_secondary', '', 'api'),
            ('comp_show_url', '1', 'company'),
            ('employerview_js_controlpanel', '1', 'jscontrolpanel'),
            ('vis_emformjob', '1', 'emcontrolpanel'),
            ('vis_emresumesearch', '1', 'emcontrolpanel'),
            ('vis_emmycompanies', '1', 'emcontrolpanel'),
            ('vis_emalljobsappliedapplications', '1', 'emcontrolpanel'),
            ('vis_emformcompany', '1', 'emcontrolpanel'),
            ('tmenu_vis_emsearchresume', '1', 'topmenu'),
            ('tmenu_vis_emmycompanies', '1', 'topmenu'),
            ('tmenu_vis_emmyjobs', '1', 'topmenu'),
            ('tmenu_vis_emnewjob', '1', 'topmenu'),
            ('tmenu_vis_emcontrolpanel', '1', 'topmenu'),
            ('vis_emmy_resumesearches', '1', 'emcontrolpanel'),
            ('vis_emmyjobs', '1', 'emcontrolpanel'),
            ('vis_emformdepartment', '1', 'emcontrolpanel'),
            ('vis_emmydepartment', '1', 'emcontrolpanel'),
            ('vis_emmy_stats', '1', 'emcontrolpanel'),
            ('vis_emmessages', '1', 'emcontrolpanel'),
            ('vis_emmyfolders', '1', 'emcontrolpanel'),
            ('tmenu_vis_jscontrolpanel', '1', 'topmenu'),
            ('tmenu_vis_jsjobcategory', '1', 'topmenu'),
            ('tmenu_vis_jsnewestjob', '1', 'topmenu'),
            ('tmenu_vis_jsmyresume', '1', 'topmenu'),
            ('vis_jsformresume', '1', 'jscontrolpanel'),
            ('vis_jsjobcat', '1', 'jscontrolpanel'),
            ('vis_jsmyresumes', '1', 'jscontrolpanel'),
            ('vis_jslistnewestjobs', '1', 'jscontrolpanel'),
            ('vis_jsformcoverletter', '1', 'jscontrolpanel'),
            ('vis_jsmyappliedjobs', '1', 'jscontrolpanel'),
            ('vis_jsmycoverletters', '1', 'jscontrolpanel'),
            ('vis_jsmy_jobsearches', '1', 'jscontrolpanel'),
            ('vis_jspurchasehistory', '1', 'jscontrolpanel'),
            ('vis_jsjobsearch', '1', 'jscontrolpanel'),
            ('vis_jsmy_stats', '1', 'jscontrolpanel'),
            ('vis_jsjobalertsetting', '1', 'jscontrolpanel'),
            ('vis_jsmessages', '1', 'jscontrolpanel'),
            ('tmenu_vis_jssearchjob', '1', 'topmenu'),
            ('rss_job_title', 'Jobs RSS title', 'rss'),
            ('rss_job_description', 'this is some desc text for job rss', 'rss'),
            ('rss_job_categories', '1', 'rss'),
            ('rss_job_image', '1', 'rss'),
            ('rss_resume_categories', '1', 'rss'),
            ('rss_resume_image', '1', 'rss'),
            ('rss_resume_title', 'Resume RSS', 'rss'),
            ('rss_resume_description', 'Resume RSS Show the Latest Resume On Our Sites', 'rss'),
            ('rss_job_ttl', '12', 'rss'),
            ('rss_job_copyright', 'Copyright 2009-2016', 'rss'),
            ('rss_job_webmaster', 'admin@domain.com', 'rss'),
            ('rss_job_editor', 'admin@domain.com', 'rss'),
            ('rss_resume_copyright', 'copy right text', 'rss'),
            ('rss_resume_webmaster', 'web master text', 'rss'),
            ('rss_resume_editor', 'editor text', 'rss'),
            ('rss_resume_ttl', '', 'rss'),
            ('rss_resume_file', '1', 'rss'),
            ('visitor_can_post_job', '0', 'visitor'),
            ('visitor_can_edit_job', '0', 'visitor'),
            ('job_captcha', '1', 'captcha'),
            ('resume_captcha', '1', 'captcha'),
            ('job_rss', '1', 'rss'),
            ('resume_rss', '1', 'rss'),
            ('empresume_rss', '1', 'emcontrolpanel'),
            ('jsjob_rss', '1', 'jscontrolpanel'),
            ('vis_resume_rss', '1', 'emcontrolpanel'),
            ('vis_job_rss', '1', 'jscontrolpanel'),
            ('default_longitude', '73.03280589999997', 'default'),
            ('default_latitude', '33.654887', 'default'),
            ('noofgoldjobsinlisting', '3', 'job'),
            ('nooffeaturedjobsinlisting', '2', 'job'),
            ('showgoldjobsinlistjobs', '1', 'job'),
            ('showfeaturedjobsinlistjobs', '1', 'job'),
            ('googleadsenseclient', 'ca-pub-8827762976015158', 'googleadds'),
            ('googleadsenseslot', '9560237528', 'googleadds'),
            ('googleadsensewidth', '717', 'googleadds'),
            ('googleadsenseheight', '90', 'googleadds'),
            ('googleadsensecustomcss', '', 'googleadds'),
            ('googleadsenseshowafter', '5', 'googleadds'),
            ('googleadsenseshowinlistjobs', '1', 'googleadds'),
            ('cron_job_alert_key', 'f1877c1756a68271d12db39ddc87dad7', 'jsjobs'),
            ('subcategory_limit', '2', 'category'),
            ('defaultradius', '2', 'default'),
            ('mapwidth', '700', 'default'),
            ('mapheight', '400', 'default'),
            ('comp_name', '1', 'company'),
            ('comp_email_address', '1', 'company'),
            ('labelinlisting', '1', 'default'),
            ('jsregister', '1', 'jscontrolpanel'),
            ('vis_jsregister', '1', 'jscontrolpanel'),
            ('empregister', '1', 'emcontrolpanel'),
            ('vis_emempregister', '1', 'emcontrolpanel'),
            ('authentication_client_key', '', 'jobsharing'),
            ('employer_share_fb_like', '1', 'social'),
            ('employer_share_fb_share', '1', 'social'),
            ('employer_share_fb_comments', '1', 'social'),
            ('employer_share_google_like', '1', 'social'),
            ('employer_share_google_share', '1', 'social'),
            ('employer_share_blog_share', '1', 'social'),
            ('employer_share_friendfeed_share', '1', 'social'),
            ('employer_share_linkedin_share', '1', 'social'),
            ('employer_share_digg_share', '1', 'social'),
            ('employer_share_twitter_share', '1', 'social'),
            ('employer_share_myspace_share', '1', 'social'),
            ('employer_share_yahoo_share', '1', 'social'),
            ('newfolders', '1', 'emcontrolpanel'),
            ('vis_emnewfolders', '1', 'emcontrolpanel'),
            ('employer_resume_alert_fields', '2', 'email'),
            ('defaultaddressdisplaytype', 'csc', 'default'),
            ('jobseeker_defaultgroup', 'subscriber', 'jsjobs'),
            ('employer_defaultgroup', 'subscriber', 'jsjobs'),
            ('default_sharing_city', '', 'jobsharing'),
            ('default_sharing_state', '', 'jobsharing'),
            ('default_sharing_country', '', 'jobsharing'),
            ('job_alert_captcha', '1', 'captcha'),
            ('jobseeker_resume_applied_status', '0', 'email'),
            ('server_serial_number', '', 'jobsharing'),
            ('jsjobupdatecount', '2401', 'jsjobs'),
            ('serialnumber', '34267', 'hostdata'),
            ('hostdata', '', 'hostdata'),
            ('zvdk', '', 'hostdata'),
            ('showapplybutton', '1', 'jobapply'),
            ('applybuttonredirecturl', '', 'jobapply'),
            ('image_file_type', 'png,jpeg,gif,jpg', 'jsjobs'),
            ('document_file_type', 'jpeg,png,jpg', 'jsjobs'),
            ('jobsloginlogout', '1', 'jscontrolpanel'),
            ('emploginlogout', '1', 'emcontrolpanel'),
            ('number_of_cities_for_autocomplete', '3', 'city'),
            ('document_file_size', '500', 'jsjobs'),
            ('document_max_files', '5', 'jsjobs'),
            ('max_resume_addresses', '3', 'resume'),
            ('max_resume_institutes', '3', 'resume'),
            ('max_resume_employers', '3', 'resume'),
            ('max_resume_references', '3', 'resume'),
            ('max_resume_languages', '3', 'resume'),
            ('show_only_section_that_have_value', '0', 'resume'),
            ('pagination_default_page_size', '10', 'default'),
            ('vis_jslistjobshortlist', '1', 'jscontrolpanel'),
            ('listallcompanies', '1', 'jscontrolpanel'),
            ('listjobbytype', '1', 'jscontrolpanel'),
            ('vis_jslistallcompanies', '1', 'jscontrolpanel'),
            ('vis_jslistjobbytype', '1', 'jscontrolpanel'),
            ('listjobshortlist', '1', 'jscontrolpanel'),
            ('currency_align', '2', 'default'),
            ('system_slug', 'js-jobs', 'jsjobs'),
            ('jobtype_per_row', '2', 'jobtype'),
            ('company_contact_detail', '1', 'company'),
            ('system_have_gold_company', '1', 'company'),
            ('system_have_featured_company', '1', 'company'),
            ('system_have_gold_resume', '1', 'resume'),
            ('system_have_featured_resume', '1', 'resume'),
            ('system_have_gold_job', '1', 'job'),
            ('system_have_featured_job', '1', 'job'),
            ('searchjobtag', '4', 'job'),
            ('categories_colsperrow', '3', 'category'),
            ('productcode', 'jsjobs', 'default'),
            ('versioncode', '2.0.1', 'default'),
            ('producttype', 'free', 'default'),
            ('vis_jscredits', '1', 'jscontrolpanel'),
            ('vis_empcredits', '1', 'emcontrolpanel'),
            ('empcredits', '1', 'emcontrolpanel'),
            ('jscreditlog', '1', 'jscontrolpanel'),
            ('vis_jscreditlog', '1', 'jscontrolpanel'),
            ('vis_empcreditlog', '1', 'emcontrolpanel'),
            ('empcreditlog', '1', 'emcontrolpanel'),
            ('emppurchasehistory', '1', 'emcontrolpanel'),
            ('vis_emppurchasehistory', '1', 'emcontrolpanel'),
            ('jscredits', '1', 'jscontrolpanel'),
            ('empratelist', '1', 'emcontrolpanel'),
            ('vis_empratelist', '1', 'emcontrolpanel'),
            ('visitor_can_add_resume', '1', 'resume'),
            ('jsratelist', '1', 'jscontrolpanel'),
            ('vis_jsratelist', '1', 'jscontrolpanel'),
            ('activity_log_filter', '', 'jsjobs'),
            ('recaptcha_publickey', '', 'captcha'),
            ('recaptcha_privatekey', '', 'captcha'),
            ('captcha_selection', '2', 'captcha'),
            ('owncaptcha_calculationtype', '0', 'captcha'),
            ('indeedjob_location', '', 'indeedjob'),
            ('disable_employer', '1', 'jsjobs'),
            ('newtyped_cities', '1', 'city'),
            ('indeedjob_enabled', '0', 'indeedjob'),
            ('indeedjob_apikey', '', 'indeedjob'),
            ('indeedjob_category', '', 'indeedjob'),
            ('owncaptcha_totaloperand', '2', 'captcha'),
            ('owncaptcha_subtractionans', '0', 'captcha'),
            ('number_of_tags_for_autocomplete', '15', 'tag'),
            ('newtyped_tags', '1', 'tag'),
            ('loginwithfacebook', '0', 'login'),
            ('apikeyfacebook', '', 'facebook'),
            ('apikeylinkedin', '', 'linkedin'),
            ('loginwithlinkedin', '0', 'login'),
            ('applywithfacebook', '0', 'jobapply'),
            ('applywithxing', '0', 'jobapply'),
            ('clientsecretfacebook', '', 'facebook'),
            ('clientsecretlinkedin', '', 'linkedin'),
            ('loginwithxing', '0', 'login'),
            ('apikeyxing', '', 'xing'),
            ('clientsecretxing', '', 'xing'),
            ('applywithlinkedin', '0', 'jobapply'),
            ('jobs_graph', '1', 'emcontrolpanel'),
            ('resume_graph', '1', 'emcontrolpanel'),
            ('box_newestresume', '1', 'emcontrolpanel'),
            ('box_appliedresume', '1', 'emcontrolpanel'),
            ('vis_jobs_graph', '1', 'emcontrolpanel'),
            ('vis_resume_graph', '1', 'emcontrolpanel'),
            ('vis_box_newestresume', '1', 'emcontrolpanel'),
            ('vis_box_appliedresume', '1', 'emcontrolpanel'),
            ('jsactivejobs_graph', '1', 'jscontrolpanel'),
            ('jssuggestedjobs_box', '1', 'jscontrolpanel'),
            ('jsappliedresume_box', '1', 'jscontrolpanel'),
            ('vis_jsactivejobs_graph', '1', 'jscontrolpanel'),
            ('vis_jssuggestedjobs_box', '1', 'jscontrolpanel'),
            ('vis_jsappliedresume_box', '1', 'jscontrolpanel'),
            ('cap_on_reg_form', '1', 'captcha'),
            ('em_cpmessage', '1', 'emcontrolpanel'),
            ('em_cpnotification', '1', 'emcontrolpanel'),
            ('categories_numberofjobs', '1', 'category'),
            ('categories_numberofresumes', '1', 'category'),
            ('jobtype_numberofjobs', '1', 'jobtype'),
            ('job_seo', '[title][company][location]', 'seo'),
            ('company_seo', '[name][location]', 'seo'),
            ('resume_seo', '[title][location]', 'seo'),
            ('empmystats', '1', 'emcontrolpanel'),
            ('vis_empmystats', '1', 'emcontrolpanel'),
            ('jsmystats', '1', 'jscontrolpanel'),
            ('vis_jsmystats', '1', 'jscontrolpanel'),
            ('allow_tellafriend', '1', 'job'),
            ('emresumebycategory', '1', 'emcontrolpanel'),
            ('vis_emresumebycategory', '1', 'emcontrolpanel'),
            ('default_pageid', '', 'default'),
            ('visitorview_emp_resumesearch', '1', 'visitor'),
            ('visitorview_emp_viewresume', '1', 'visitor'),
            ('visitorview_emp_resumecat', '1', 'visitor'),
            ('google_map_api_key', 'AIzaSyCZcnAK0DiGg8lAXej74e7PlrhkfCM86-M', 'default'),
			('tell_a_friend_captcha', '1', 'captcha'),
			('auto_assign_free_package', '1', 'creditpack'),
			('free_package_purchase_only_once', '1', 'creditpack'),
			('free_package_auto_approve', '1', 'creditpack'),
			('register_jobseeker_redirect_page', '146', 'register'),
			('register_employer_redirect_page', '', 'register'),
			('visitor_add_resume_redirect_page', '146', 'visitor'),
			('visitor_add_job_redirect_page', '', 'visitor'),
			('temp_employer_dashboard_stats_graph', '1', 'emcontrolpanel'),
			('temp_employer_dashboard_useful_links', '1', 'emcontrolpanel'),
			('temp_employer_dashboard_applied_resume', '1', 'emcontrolpanel'),
			('temp_employer_dashboard_saved_search', '1', 'emcontrolpanel'),
			('temp_employer_dashboard_credits_log', '1', 'emcontrolpanel'),
			('temp_employer_dashboard_purchase_history', '1', 'emcontrolpanel'),
			('temp_employer_dashboard_newest_resume', '1', 'emcontrolpanel'),
			('vis_temp_employer_dashboard_stats_graph', '1', 'emcontrolpanel'),
			('vis_temp_employer_dashboard_useful_links', '1', 'emcontrolpanel'),
			('vis_temp_employer_dashboard_applied_resume', '1', 'emcontrolpanel'),
			('vis_temp_employer_dashboard_saved_search', '1', 'emcontrolpanel'),
			('vis_temp_employer_dashboard_credits_log', '1', 'emcontrolpanel'),
			('vis_temp_employer_dashboard_purchase_history', '1', 'emcontrolpanel'),
			('vis_temp_employer_dashboard_newest_resume', '1', 'emcontrolpanel'),
			('temp_jobseeker_dashboard_jobs_graph', '1', 'jscontrolpanel'),
			('temp_jobseeker_dashboard_useful_links', '1', 'jscontrolpanel'),
			('temp_jobseeker_dashboard_apllied_jobs', '1', 'jscontrolpanel'),
			('temp_jobseeker_dashboard_shortlisted_jobs', '1', 'jscontrolpanel'),
			('temp_jobseeker_dashboard_credits_log', '1', 'jscontrolpanel'),
			('temp_jobseeker_dashboard_purchase_history', '1', 'jscontrolpanel'),
			('temp_jobseeker_dashboard_newest_jobs', '1', 'jscontrolpanel'),
			('vis_temp_jobseeker_dashboard_jobs_graph', '1', 'jscontrolpanel'),
			('vis_temp_jobseeker_dashboard_useful_links', '1', 'jscontrolpanel'),
			('vis_temp_jobseeker_dashboard_apllied_jobs', '1', 'jscontrolpanel'),
			('vis_temp_jobseeker_dashboard_shortlisted_jobs', '1', 'jscontrolpanel'),
			('vis_temp_jobseeker_dashboard_credits_log', '1', 'jscontrolpanel'),
			('vis_temp_jobseeker_dashboard_purchase_history', '1', 'jscontrolpanel'),
			('vis_temp_jobseeker_dashboard_newest_jobs', '1', 'jscontrolpanel'),
			('slug_prefix', 'jm-', 'default'),
			('home_slug_prefix', 'js-', 'default'),
			('show_total_number_of_jobs', '1', 'job'),
			('vis_jobsbycities', '1', 'jscontrolpanel'),
			('jobsbycities', '1', 'jscontrolpanel'),
			('jobsbycities_jobcount', '1', 'default'),
			('jobsbycities_countryname', '1', 'default'),
			('terms_and_conditions_page_company', '0', 'default'),
			('terms_and_conditions_page_job', '0', 'default'),
			('terms_and_conditions_page_resume', '0', 'default'),
			('job_resume_show_all_categories', '1', 'default'),
			('system_has_cover_letter', '1', 'default');";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_countries` (
              `id` smallint(9) NOT NULL AUTO_INCREMENT,
              `name` varchar(40) DEFAULT NULL,
              `shortCountry` varchar(30) DEFAULT NULL,
              `continentID` tinyint(11) DEFAULT NULL,
              `dialCode` smallint(8) DEFAULT NULL,
              `enabled` tinyint(1) NOT NULL DEFAULT '0',
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              FULLTEXT KEY `name` (`name`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=215;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_countries` (`id`, `name`, `shortCountry`, `continentID`, `dialCode`, `enabled`, `serverid`) VALUES (1, 'United States', 'US', 1, 1, 1, 0),(2, 'Canada', 'Canada', 1, 1, 1, 0),(3, 'Bahamas', 'Bahamas', 1, 242, 1, 0),(4, 'Barbados', 'Barbados', 1, 246, 1, 0),(5, 'Belize', 'Belize', 1, 501, 1, 0),(6, 'Bermuda', 'Bermuda', 1, 441, 1, 0),(7, 'British Virgin Islands', 'BVI', 1, 284, 1, 0),(8, 'Cayman Islands', 'CaymanIsl', 1, 345, 1, 0),(9, 'Costa Rica', 'CostaRica', 1, 506, 1, 0),(10, 'Cuba', 'Cuba', 1, 53, 1, 0),(11, 'Dominica', 'Dominica', 1, 767, 1, 0),(12, 'Dominican Republic', 'DominicanRep', 1, 809, 1, 0),(13, 'El Salvador', 'ElSalvador', 1, 503, 1, 0),(14, 'Greenland', 'Greenland', 1, 299, 1, 0),(15, 'Grenada', 'Grenada', 1, 473, 1, 0),(16, 'Guadeloupe', 'Guadeloupe', 1, 590, 1, 0),(17, 'Guatemala', 'Guatemala', 1, 502, 1, 0),(18, 'Haiti', 'Haiti', 1, 509, 1, 0),(19, 'Honduras', 'Honduras', 1, 503, 1, 0),(20, 'Jamaica', 'Jamaica', 1, 876, 1, 0),(21, 'Martinique', 'Martinique', 1, 596, 1, 0),(22, 'Mexico', 'Mexico', 1, 52, 1, 0),(23, 'Montserrat', 'Montserrat', 1, 664, 1, 0),(24, 'Nicaragua', 'Nicaragua', 1, 505, 1, 0),(25, 'Panama', 'Panama', 1, 507, 1, 0),(26, 'Puerto Rico', 'PuertoRico', 1, 787, 1, 0),(27, 'Trinidad and Tobago', 'Trinidad-Tobago', 1, 868, 1, 0),(28, 'United States Virgin Islands', 'USVI', 1, 340, 1, 0),(29, 'Argentina', 'Argentina', 2, 54, 1, 0),(30, 'Bolivia', 'Bolivia', 2, 591, 1, 0),(31, 'Brazil', 'Brazil', 2, 55, 1, 0),(32, 'Chile', 'Chile', 2, 56, 1, 0),(33, 'Colombia', 'Colombia', 2, 57, 1, 0),(34, 'Ecuador', 'Ecuador', 2, 593, 1, 0),(35, 'Falkland Islands', 'FalklandIsl', 2, 500, 1, 0),(36, 'French Guiana', 'FrenchGuiana', 2, 594, 1, 0),(37, 'Guyana', 'Guyana', 2, 592, 1, 0),(38, 'Paraguay', 'Paraguay', 2, 595, 1, 0),(39, 'Peru', 'Peru', 2, 51, 1, 0),(40, 'Suriname', 'Suriname', 2, 597, 1, 0),(41, 'Uruguay', 'Uruguay', 2, 598, 1, 0),(42, 'Venezuela', 'Venezuela', 2, 58, 1, 0),(43, 'Albania', 'Albania', 3, 355, 1, 0),(44, 'Andorra', 'Andorra', 3, 376, 1, 0),(45, 'Armenia', 'Armenia', 3, 374, 1, 0),(46, 'Austria', 'Austria', 3, 43, 1, 0),(47, 'Azerbaijan', 'Azerbaijan', 3, 994, 1, 0),(48, 'Belarus', 'Belarus', 3, 375, 1, 0),(49, 'Belgium', 'Belgium', 3, 32, 1, 0),(50, 'Bosnia and Herzegovina', 'Bosnia-Herzegovina', 3, 387, 1, 0),(51, 'Bulgaria', 'Bulgaria', 3, 359, 1, 0),(52, 'Croatia', 'Croatia', 3, 385, 1, 0),(53, 'Cyprus', 'Cyprus', 3, 357, 1, 0),(54, 'Czech Republic', 'CzechRep', 3, 420, 1, 0),(55, 'Denmark', 'Denmark', 3, 45, 1, 0),(56, 'Estonia', 'Estonia', 3, 372, 1, 0),(57, 'Finland', 'Finland', 3, 358, 1, 0),(58, 'France', 'France', 3, 33, 1, 0),(59, 'Georgia', 'Georgia', 3, 995, 1, 0),(60, 'Germany', 'Germany', 3, 49, 1, 0),(61, 'Gibraltar', 'Gibraltar', 3, 350, 1, 0),(62, 'Greece', 'Greece', 3, 30, 1, 0),(63, 'Guernsey', 'Guernsey', 3, 44, 1, 0),(64, 'Hungary', 'Hungary', 3, 36, 1, 0),(65, 'Iceland', 'Iceland', 3, 354, 1, 0),(66, 'Ireland', 'Ireland', 3, 353, 1, 0),(67, 'Isle of Man', 'IsleofMan', 3, 44, 1, 0),(68, 'Italy', 'Italy', 3, 39, 1, 0),(69, 'Jersey', 'Jersey', 3, 44, 1, 0),(70, 'Kosovo', 'Kosovo', 3, 381, 1, 0),(71, 'Latvia', 'Latvia', 3, 371, 1, 0),(72, 'Liechtenstein', 'Liechtenstein', 3, 423, 1, 0),(73, 'Lithuania', 'Lithuania', 3, 370, 1, 0),(74, 'Luxembourg', 'Luxembourg', 3, 352, 1, 0),(75, 'Macedonia', 'Macedonia', 3, 389, 1, 0),(76, 'Malta', 'Malta', 3, 356, 1, 0),(77, 'Moldova', 'Moldova', 3, 373, 1, 0),(78, 'Monaco', 'Monaco', 3, 377, 1, 0),(79, 'Montenegro', 'Montenegro', 3, 381, 1, 0),(80, 'Netherlands', 'Netherlands', 3, 31, 1, 0),(81, 'Norway', 'Norway', 3, 47, 1, 0),(82, 'Poland', 'Poland', 3, 48, 1, 0),(83, 'Portugal', 'Portugal', 3, 351, 1, 0),(84, 'Romania', 'Romania', 3, 40, 1, 0),(85, 'Russia', 'Russia', 3, 7, 1, 0),(86, 'San Marino', 'SanMarino', 3, 378, 1, 0),(87, 'Serbia', 'Serbia', 3, 381, 1, 0),(88, 'Slovakia', 'Slovakia', 3, 421, 1, 0),(89, 'Slovenia', 'Slovenia', 3, 386, 1, 0),(90, 'Spain', 'Spain', 3, 34, 1, 0),(91, 'Sweden', 'Sweden', 3, 46, 1, 0),(92, 'Switzerland', 'Switzerland', 3, 41, 1, 0),(93, 'Turkey', 'Turkey', 3, 90, 1, 0),(94, 'Ukraine', 'Ukraine', 3, 380, 1, 0),(95, 'United Kingdom', 'UK', 3, 44, 1, 0),(96, 'Vatican City', 'Vatican', 3, 39, 1, 0),(97, 'Afghanistan', 'Afghanistan', 4, 93, 1, 0),(98, 'Bahrain', 'Bahrain', 4, 973, 1, 0),(99, 'Bangladesh', 'Bangladesh', 4, 880, 1, 0),(100, 'Bhutan', 'Bhutan', 4, 975, 1, 0),(101, 'Brunei', 'Brunei', 4, 673, 1, 0),(102, 'Cambodia', 'Cambodia', 4, 855, 1, 0),(103, 'China', 'China', 4, 86, 1, 0),(104, 'East Timor', 'EastTimor', 4, 670, 1, 0),(105, 'Hong Kong', 'HongKong', 4, 852, 1, 0),(106, 'India', 'India', 4, 91, 1, 0),(107, 'Indonesia', 'Indonesia', 4, 62, 1, 0),(108, 'Iran', 'Iran', 4, 98, 1, 0),(109, 'Iraq', 'Iraq', 4, 964, 1, 0),(110, 'Israel', 'Israel', 4, 972, 1, 0),(111, 'Japan', 'Japan', 4, 81, 1, 0),(112, 'Jordan', 'Jordan', 4, 962, 1, 0),(113, 'Kazakhstan', 'Kazakhstan', 4, 7, 1, 0),(114, 'Kuwait', 'Kuwait', 4, 965, 1, 0),(115, 'Kyrgyzstan', 'Kyrgyzstan', 4, 996, 1, 0),(116, 'Laos', 'Laos', 4, 856, 1, 0),(117, 'Lebanon', 'Lebanon', 4, 961, 1, 0),(118, 'Macau', 'Macau', 4, 853, 1, 0),(119, 'Malaysia', 'Malaysia', 4, 60, 1, 0),(120, 'Maldives', 'Maldives', 4, 960, 1, 0),(121, 'Mongolia', 'Mongolia', 4, 976, 1, 0),(122, 'Myanmar (Burma)', 'Myanmar(Burma)', 4, 95, 1, 0),(123, 'Nepal', 'Nepal', 4, 977, 1, 0),(124, 'North Korea', 'NorthKorea', 4, 850, 1, 0),(125, 'Oman', 'Oman', 4, 968, 1, 0),(126, 'Pakistan', 'Pakistan', 4, 92, 1, 0),(127, 'Philippines', 'Philippines', 4, 63, 1, 0),(128, 'Qatar', 'Qatar', 4, 974, 1, 0),(129, 'Saudi Arabia', 'SaudiArabia', 4, 966, 1, 0),(130, 'Singapore', 'Singapore', 4, 65, 1, 0),(131, 'South Korea', 'SouthKorea', 4, 82, 1, 0),(132, 'Sri Lanka', 'SriLanka', 4, 94, 1, 0),(133, 'Syria', 'Syria', 4, 963, 1, 0),(134, 'Taiwan', 'Taiwan', 4, 886, 1, 0),(135, 'Tajikistan', 'Tajikistan', 4, 992, 1, 0),(136, 'Thailand', 'Thailand', 4, 66, 1, 0),(137, 'Turkmenistan', 'Turkmenistan', 4, 993, 1, 0),(138, 'United Arab Emirates', 'UAE', 4, 971, 1, 0),(139, 'Uzbekistan', 'Uzbekistan', 4, 998, 1, 0),(140, 'Vietnam', 'Vietnam', 4, 84, 1, 0),(141, 'Yemen', 'Yemen', 4, 967, 1, 0),(142, 'Algeria', 'Algeria', 5, 213, 1, 0),(143, 'Angola', 'Angola', 5, 244, 1, 0),(144, 'Benin', 'Benin', 5, 229, 1, 0),(145, 'Botswana', 'Botswana', 5, 267, 1, 0),(146, 'Burkina Faso', 'BurkinaFaso', 5, 226, 1, 0),(147, 'Burundi', 'Burundi', 5, 257, 1, 0),(148, 'Cameroon', 'Cameroon', 5, 237, 1, 0),(149, 'Cape Verde', 'CapeVerde', 5, 238, 1, 0),(150, 'Central African Republic', 'CentralAfricanRep', 5, 236, 1, 0),(151, 'Chad', 'Chad', 5, 235, 1, 0),(152, 'Congo', 'Congo', 5, 242, 1, 0),(153, 'Democoratic Republic of Congo', 'D.R Congo', 5, 242, 1, 0),(154, 'Djibouti', 'Djibouti', 5, 253, 1, 0),(155, 'Egypt', 'Egypt', 5, 20, 1, 0),(156, 'Equatorial Guinea', 'EquatorialGuinea', 5, 240, 1, 0),(157, 'Eritrea', 'Eritrea', 5, 291, 1, 0),(158, 'Ethiopia', 'Ethiopia', 5, 251, 1, 0),(159, 'Gabon', 'Gabon', 5, 241, 1, 0),(160, 'Gambia', 'Gambia', 5, 220, 1, 0),(161, 'Ghana', 'Ghana', 5, 233, 1, 0),(162, 'Guinea', 'Guinea', 5, 224, 1, 0),(163, 'Guinea-Bissau', 'Guinea-Bissau', 5, 245, 1, 0),(164, 'Cote DIvory', 'IvoryCoast', 5, 225, 1, 0),(165, 'Kenya', 'Kenya', 5, 254, 1, 0),(166, 'Lesotho', 'Lesotho', 5, 266, 1, 0),(167, 'Liberia', 'Liberia', 5, 231, 1, 0),(168, 'Libya', 'Libya', 5, 218, 1, 0),(169, 'Madagascar', 'Madagascar', 5, 261, 1, 0),(170, 'Malawi', 'Malawi', 5, 265, 1, 0),(171, 'Mali', 'Mali', 5, 223, 1, 0),(172, 'Mauritania', 'Mauritania', 5, 222, 1, 0),(173, 'Mauritius', 'Mauritius', 5, 230, 1, 0),(174, 'Morocco', 'Morocco', 5, 212, 1, 0),(175, 'Mozambique', 'Mozambique', 5, 258, 1, 0),(176, 'Namibia', 'Namibia', 5, 264, 1, 0),(177, 'Niger', 'Niger', 5, 227, 1, 0),(178, 'Nigeria', 'Nigeria', 5, 234, 1, 0),(179, 'Reunion', 'Reunion', 5, 262, 1, 0),(180, 'Rwanda', 'Rwanda', 5, 250, 1, 0),(181, 'Sao Tome and Principe', 'SaoTome-Principe', 5, 239, 1, 0),(182, 'Senegal', 'Senegal', 5, 221, 1, 0),(183, 'Seychelles', 'Seychelles', 5, 248, 1, 0),(184, 'Sierra Leone', 'SierraLeone', 5, 232, 1, 0),(185, 'Somalia', 'Somalia', 5, 252, 1, 0),(186, 'South Africa', 'SouthAfrica', 5, 27, 1, 0),(187, 'Sudan', 'Sudan', 5, 249, 1, 0),(188, 'Swaziland', 'Swaziland', 5, 268, 1, 0),(189, 'Tanzania', 'Tanzania', 5, 255, 1, 0),(190, 'Togo', 'Togo', 5, 228, 1, 0),(191, 'Tunisia', 'Tunisia', 5, 216, 1, 0),(192, 'Uganda', 'Uganda', 5, 256, 1, 0),(193, 'Western Sahara', 'WesternSahara', 5, 212, 1, 0),(194, 'Zambia', 'Zambia', 5, 260, 1, 0),(195, 'Zimbabwe', 'Zimbabwe', 5, 263, 1, 0),(196, 'Australia', 'Australia', 6, 61, 1, 0),(197, 'New Zealand', 'NewZealand', 6, 64, 1, 0),(198, 'Fiji', 'Fiji', 6, 679, 1, 0),(199, 'French Polynesia', 'FrenchPolynesia', 6, 689, 1, 0),(200, 'Guam', 'Guam', 6, 671, 1, 0),(201, 'Kiribati', 'Kiribati', 6, 686, 1, 0),(202, 'Marshall Islands', 'MarshallIsl', 6, 692, 1, 0),(203, 'Micronesia', 'Micronesia', 6, 691, 1, 0),(204, 'Nauru', 'Nauru', 6, 674, 1, 0),(205, 'New Caledonia', 'NewCaledonia', 6, 687, 1, 0),(206, 'Papua New Guinea', 'PapuaNewGuinea', 6, 675, 1, 0),(207, 'Samoa', 'Samoa', 6, 684, 1, 0),(208, 'Solomon Islands', 'SolomonIsl', 6, 677, 1, 0),(209, 'Tonga', 'Tonga', 6, 676, 1, 0),(210, 'Tuvalu', 'Tuvalu', 6, 688, 1, 0),(211, 'Vanuatu', 'Vanuatu', 6, 678, 1, 0),(212, 'Wallis and Futuna', 'Wallis-Futuna', 6, 681, 1, 0),(213, 'Comoros', 'Comoros', 0, 0, 1, 0),(214, 'Cote DIvorie', 'Cote-DIvorie', NULL, NULL, 1, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_coverletters` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) NOT NULL,
              `title` varchar(300) NOT NULL,
              `alias` varchar(225) NOT NULL,
              `description` text NOT NULL,
              `hits` int(11) DEFAULT NULL,
              `published` tinyint(1) NOT NULL,
              `searchable` tinyint(1) DEFAULT NULL,
              `status` tinyint(1) DEFAULT NULL,
              `created` datetime DEFAULT NULL,
              `packageid` int(11) DEFAULT NULL,
              `paymenthistoryid` int(11) DEFAULT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              PRIMARY KEY (`id`),
              KEY `coverletter_uid` (`uid`),
              KEY `coverletter_packgeid` (`packageid`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_currencies` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(60) DEFAULT NULL,
              `symbol` varchar(60) DEFAULT NULL,
              `code` varchar(10) NOT NULL,
              `status` tinyint(1) DEFAULT NULL,
              `default` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_currencies` (`id`, `title`, `symbol`, `code`, `status`, `default`, `ordering`, `serverid`) VALUES (1, 'US Doller', '$', 'USD', 1, 1, 1, 0);";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_currencies` (`id`, `title`, `symbol`, `code`, `status`, `default`, `ordering`, `serverid`) VALUES (2, 'Pakistani Rupee', 'Rs.', 'PKR', 1, 0, 2, 0);";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_currencies` (`id`, `title`, `symbol`, `code`, `status`, `default`, `ordering`, `serverid`) VALUES (3, 'Pound', '£', 'GBP', 1, 0, 3, 0);";

            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_currencies` (`id`, `title`, `symbol`, `code`, `status`, `default`, `ordering`, `serverid`) VALUES (4, 'Euro', ' €', 'EUR', 1, 0, 4, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_departments` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) NOT NULL,
              `companyid` int(11) NOT NULL,
              `name` varchar(70) NOT NULL,
              `alias` varchar(225) NOT NULL,
              `description` text,
              `status` tinyint(4) NOT NULL,
              `created` datetime NOT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              PRIMARY KEY (`id`,`companyid`),
              KEY `departments` (`companyid`),
              KEY `departments_uid` (`uid`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            jsjobs::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) DEFAULT NULL,
              `templatefor` varchar(50) DEFAULT NULL,
              `title` varchar(50) DEFAULT NULL,
              `subject` varchar(255) DEFAULT NULL,
              `body` text,
              `status` tinyint(1) DEFAULT NULL,
              `created` datetime DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23;";
            jsjobs::$_db->query($query);


            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (1, 0, 'company-status', NULL, 'JS Jobs: Company {COMPANY_NAME} has been {COMPANY_STATUS}', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {EMPLOYER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">Your company {COMPANY_NAME} has been <strong>{COMPANY_STATUS}</strong>.</p>\n<p style=\"color: #4f4f4f;\"><strong> {COMPANY_CREDITS}</strong> credits consumed to {COMPANY_STATUS} company.</p>\n<p style=\"color: #4f4f4f;\">Click here to view {COMPANY_LINK}.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', 1, '2009-08-17 18:08:41');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (2, 0, 'company-delete', NULL, 'JS Jobs: Your Company {COMPANY_NAME} has been deleted', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {COMPANY_OWNER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">Your company <strong>{COMPANY_NAME}</strong> has been deleted.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply! </p>\n</div>\n', NULL, '2009-08-17 17:54:48');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (3, 0, 'job-status', '', 'JS Jobs: Your job {JOB_TITLE} has been {JOB_STATUS}.', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {EMPLOYER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">Your job <strong>{JOB_TITLE}</strong> has been {JOB_STATUS}.</p>\n<p style=\"color: #4f4f4f;\"><strong>{JOB_CREDITS}</strong> credits consumed for this job.</p>\n<p style=\"color: #4f4f4f;\">Click here to view {JOB_LINK}.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', 0, '2009-08-17 22:10:27');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (4, 0, 'job-delete', NULL, 'JS Jobs: Your job {JOB_TITLE} has been deleted.', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {EMPLOYER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">{COMPANY_NAME} job <strong>{JOB_TITLE}</strong> has been deleted.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '2009-08-17 22:12:43');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (5, 0, 'resume-status', NULL, 'JS Jobs: Your resume {RESUME_TITLE} has been {RESUME_STATUS}.', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {JOBSEEKER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">Your resume <strong>{RESUME_TITLE}</strong> has been <strong>{RESUME_STATUS}</strong>.</p>\n<p style=\"color: #4f4f4f;\"><strong>{RESUME_CREDITS}</strong>credits consumed for this resume.</p>\n<p style=\"color: #4f4f4f;\">Click here to view {RESUME_LINK}.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '2009-08-17 22:15:12');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (6, 0, 'employer-purchase-credit-pack', NULL, 'JS Jobs: You have purchased new package {PACKAGE_NAME}', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {EMPLOYER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">Your have purchased new package <strong>{PACKAGE_NAME}</strong> .</p>\n<p style=\"color: #4f4f4f;\">Click here to view {PACKAGE_LINK}.</p>\n<p style=\"color: #4f4f4f;\">{PACKAGE_PRICE} credits consumed for this package</p>\n<p style=\"color: #4f4f4f;\">Package purchased date {PACKAGE_PURCHASE_DATE}</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '2009-08-17 22:14:52');";
            jsjobs::$_db->query($query);

            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (7, 0, 'jobapply-jobseeker', NULL, 'JS Jobs: Applied for {JOB_TITLE} job', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Hello {JOBSEEKER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">you have to applied for <strong>{JOB_TITLE}</strong> job in <strong>{COMPANY_NAME}</strong> company by <strong>{RESUME_TITLE}</strong> resume</p>\n<p style=\"color: #4f4f4f;\">your resume has been {RESUME_APPLIED_STATUS} .</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '2009-08-18 16:46:16');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (8, 0, 'company-new', '', 'JS Jobs: New company {COMPANY_NAME} has been received', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Hello {EMPLOYER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">We receive new <strong>{COMPANY_NAME}</strong> company.</p>\n<p style=\"color: #4f4f4f;\">Company status is <strong>{COMPANY_STATUS}</strong></p>\n<p style=\"color: #4f4f4f;\">{COMPANY_CREDITS} credits consumed for this company.</p>\n<p style=\"color: #4f4f4f;\">Click here to view {COMPANY_LINK}.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', 0, '2009-08-18 16:46:16');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (9, 0, 'job-new', '', 'JS Jobs: New Job {JOB_TITLE} has been received of {COMPANY_NAME} company', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {EMPLOYER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">We receive new <strong>{JOB_TITLE}</strong> job of your <strong>{COMPANY_NAME}</strong> company.</p>\n<p style=\"color: #4f4f4f;\">Your job status is {JOB_STATUS}</p>\n<p style=\"color: #4f4f4f;\">{JOB_CREDITS} credits consumed for this job.</p>\n<p style=\"color: #4f4f4f;\">Click here to view {JOB_LINK}.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', 0, '2009-08-18 16:46:16');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (10, 0, 'resume-new', NULL, 'JS Jobs: New resume {RESUME_TITLE} has beed received', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Hello {JOBSEEKER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">We receive new resume.<strong>{RESUME_TITLE}</strong></p>\n<p style=\"color: #4f4f4f;\">your resume has been <strong>{RESUME_STATUS}</strong></p>\n<p style=\"color: #4f4f4f;\">Click here to view {RESUME_LINK}.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '2009-08-18 16:46:16');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (11, 0, 'jobseeker-package-expire', NULL, 'JS Jobs: {PACKAGE_NAME} has been expired', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {JOBSEEKER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">your {PACKAGE_NAME} has been expired.</p>\n<p style=\"color: #4f4f4f;\">you had purchased this package at {PACKAGE_PURCHASE_DATE}</p>\n<p style=\"color: #4f4f4f;\">For view this click here{PACKAGE_LINK}</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '2009-08-18 16:46:16');";
            jsjobs::$_db->query($query);

            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (12, 0, 'jobseeker-purchase-credit-pack', NULL, 'JS Jobs: You purchased new package {PACKAGE_NAME}', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {JOBSEEKER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">Your have purchased new package <strong>{PACKAGE_NAME}</strong> .</p>\n<p style=\"color: #4f4f4f;\">Click here to view {PACKAGE_LINK}.</p>\n<p style=\"color: #4f4f4f;\">{PACKAGE_PRICE} credits consumed for this package</p>\n<p style=\"color: #4f4f4f;\">Package purchase date {PACKAGE_PURCHASE_DATE}</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '2009-08-18 16:46:16');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (13, NULL, 'employer-package-expire', NULL, 'JS Jobs: {PACKAGE_NAME} has been expired', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {EMPLOYER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">your {PACKAGE_NAME} has been expired.</p>\n<p style=\"color: #4f4f4f;\">you have to purchased this package at {PACKAGE_PURCHASE_DATE}</p>\n<p style=\"color: #4f4f4f;\">For view this click here{PACKAGE_LINK}</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '0000-00-00 00:00:00');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (14, NULL, 'jobapply-employer', '', 'JS Jobs: Job seeker have applied for {JOB_TITLE} job ', '<div style=\"background: #6DC6DD; height: 20px;\"> </div>\r\n\r\n<p style=\"color: #2191ad;\">Hello {EMPLOYER_NAME} ,</p>\r\n\r\n<p style=\"color: #4f4f4f;\">Mr/Mrs {JOBSEEKER_NAME} applied for your job {JOB_TITLE}.</p>\r\n\r\n<p style=\"color: #4f4f4f;\">Current Applied Resume status is {RESUME_APPLIED_STATUS}.</p>\r\n<p style=\"color: #4f4f4f;\">Click here to view {RESUME_LINK}.</p>\r\n\r\n<p style=\"color: #4f4f4f;\">{COVER_LETTER_TITLE}</p>\r\n<p style=\"color: #4f4f4f;\">{COVER_LETTER_DESCRIPTION}</p>\r\n\r\n<p >{RESUME_DATA}</p>\r\n\r\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\r\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br /> It is automatically generated and is for information purposes only.</p>\r\n</div> ', NULL, '0000-00-00 00:00:00');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (15, 0, 'job-new-vis', '', 'JS Jobs: New Visitor Job {JOB_TITLE} has beed received of {COMPANY_NAME} company', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Hello {EMPLOYER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">We receive new <strong>{JOB_TITLE}</strong> job of your {COMPANY_NAME} company</p>\n<p style=\"color: #4f4f4f;\">Your new added job status is {JOB_STATUS}.</p>\n<p style=\"color: #4f4f4f;\">Click here to view {JOB_LINK}.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we wont receive your reply!</p>\n</div>\n', NULL, '0000-00-00 00:00:00');";
            jsjobs::$_db->query($query);

            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (16, NULL, 'employer-new', '', 'JS Jobs : New user registered as a employer ', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Hello {USER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">you are registered as <strong>{USER_ROLE}</strong> in this application.</p>\n<p style=\"color: #4f4f4f;\">Click here to view {CONTROL_PANEL_LINK}.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '0000-00-00 00:00:00');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (17, NULL, 'jobseeker-new', NULL, 'JS Jobs: New user registered as a jobseeker', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Hello {USER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">you are registered as <strong>{USER_ROLE}</strong> in this application.</p>\n<p style=\"color: #4f4f4f;\">Click here to view {CONTROL_PANEL_LINK}.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '0000-00-00 00:00:00');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (18, NULL, 'resume-new-vis', NULL, 'JS Jobs:  New resume {RESUME_TITLE} has beed received', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Hello {JOBSEEKER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">We receive new resume.<strong>{RESUME_TITLE}</strong></p>\n<p style=\"color: #4f4f4f;\">your resume has been <strong>{RESUME_STATUS}</strong></p>\n<p style=\"color: #4f4f4f;\">Click here to view {RESUME_LINK}.</p>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '0000-00-00 00:00:00');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (19, 0, 'jobapply-jobapply', NULL, 'JS Jobs:  {JOBSEEKER_NAME} apply for {JOB_TITLE}', '<div style=\"background: #6DC6DD; height: 20px;\"> </div>\r\n<p style=\"color: #2191ad;\">Hello Admin ,</p>\r\n<p style=\"color: #4f4f4f;\">Mr/Mrs {JOBSEEKER_NAME} applied for job {JOB_TITLE} from Employer {EMPLOYER_NAME}.</p>\r\n<p style=\"color: #4f4f4f;\">Click here to view {RESUME_LINK} .</p>\r\n<p>{RESUME_DATA}</p>\r\n<p style=\"color: #4f4f4f;\">{COVER_LETTER_TITLE}</p>\r\n<p style=\"color: #4f4f4f;\">{COVER_LETTER_DESCRIPTION}</p>\r\n\r\n\r\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\r\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\r\n</div>', NULL, '2009-08-18 16:46:16');";
            jsjobs::$_db->query($query);

            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (20, 0, 'resume-delete', '', 'JS Jobs: Your Resume {RESUME_TITLE} has been deleted', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n\n<p style=\"color: #2191ad;\">\nDear {JOBSEEKER_NAME} ,</p>\n<p style=\"color: #4f4f4f;\">\nYour Resume <strong>{RESUME_TITLE}</strong> has been deleted.</p>\n\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n\n<p><span style=\"color: red;\">\n<strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span>\n<br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!\n</p>\n</div>\n', NULL, '2009-08-17 17:54:48');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (21, 0, 'applied-resume_status', NULL, 'JS Jobs: Your applied resume status update', '<div style=\"background: #6DC6DD; height: 20px;\"></div>\n<p style=\"color: #2191ad;\">Dear {JOBSEEKER_NAME}</p>\n<p style=\"color: #4f4f4f;\">You are applied for job {JOB_TITLE}.</p>\n<p style=\"color: #4f4f4f;\">Your resume has been mark as {RESUME_STATUS}.</p>\n<div style=\"color: #4f4f4f;\">Click here to view <span class=\"js-email-paramater\">{RESUME_LINK}</span> .Thank you.</div>\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\nThis is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\n</div>\n', NULL, '2011-03-31 16:46:16');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (22, NULL, 'job-alert', NULL, 'JS Jobs: New Job', '<div style=\"background: #6DC6DD; height: 20px;\"> </div>\r\n<p style=\"color: #2191ad;\">Dear {JOBSEEKER_NAME} ,</p>\r\n<p style=\"color: #4f4f4f;\">We receive new job.</p>\r\n<p>{JOBS_INFO}</p>\r\n<p style=\"color: #4f4f4f;\">Login and view detail at</p>\r\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\r\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />\n This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\r\n</div>', 1, '2016-05-07 00:00:00');";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates` (`id`, `uid`, `templatefor`, `title`, `subject`, `body`, `status`, `created`) VALUES (23, NULL, 'job-to-friend', NULL, 'JS Jobs: Your friend find a job', '<div style=\"background: #6DC6DD; height: 20px;\"> </div>\r\n<p style=\"color: #2191ad;\">Dear</p>\r\n<p style=\"color: #4f4f4f;\">Your Friend {SENDER_NAME} will send you this mail through our site {SITE_NAME} to inform you for a job.</p>\r\n<div style=\"display: block; padding: 20px; color: #4f4f4f; background: #F5FEFF; border: 1px solid #B7EAF7;\">\r\n<p><strong><span style=\"text-decoration: underline;\">Summary</span></strong></p>\r\n<p>Title: {JOB_TITLE}</p>\r\n<p>Category {JOB_CATEGORY}</p>\r\n<p>Company : {COMPANY_NAME}</p>\r\n<p>{CLICK_HERE_TO_VISIT} the job detail.</p>\r\n<p>{SENDER_MESSAGE}</p>\r\n<p>Thank you.</p>\r\n</div>\r\n<div style=\"margin-top: 10px; padding: 10px 20px; color: #000000; background: #FAF2F2; border: 1px solid #F7C1C1;\">\r\n<p><span style=\"color: red;\"><strong>*DO NOT REPLY TO THIS E-MAIL*</strong></span><br />This is an automated e-mail message sent from our support system. Do not reply to this e-mail as we will not receive your reply!</p>\r\n</div>', 1, '2016-05-07 00:00:00');";
            jsjobs::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_emailtemplates_config` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `emailfor` varchar(255) NOT NULL,
              `admin` tinyint(1) NOT NULL,
              `employer` tinyint(1) NOT NULL,
              `jobseeker` tinyint(1) NOT NULL,
              `jobseeker_visitor` tinyint(1) NOT NULL,
              `employer_visitor` int(11) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_emailtemplates_config` (`id`, `emailfor`, `admin`, `employer`, `jobseeker`, `jobseeker_visitor`, `employer_visitor`) VALUES
            (1, 'add_new_company', 1, 0, 0, 0, 0),
            (2, 'delete_company', 0, 0, 0, 0, 0),
            (3, 'company_status', 0, 1, 0, 0, 0),
            (4, 'job_status', 0, 1, 0, 0, 0),
            (5, 'add_new_job', 1, 0, 0, 0, 0),
            (6, 'add_new_resume', 1, 0, 0, 0, 0),
            (7, 'resume_status', 0, 0, 0, 0, 0),
            (8, 'employer_purchase_credits_pack', 0, 0, 0, 0, 0),
            (9, 'jobseeker_purchase_credits_pack', 0, 0, 0, 0, 0),
            (10, 'jobseeker_package_expire', 0, 0, 0, 0, 0),
            (11, 'employer_package_expire', 0, 0, 0, 0, 0),
            (12, 'employer_purchase_package_status', 0, 0, 0, 0, 0),
            (13, 'jobseeker_purchase_package_status', 0, 0, 0, 0, 0),
            (14, 'jobapply_jobapply', 0, 1, 0, 0, 0),
            (17, 'delete_job', 0, 0, 0, 0, 0),
            (19, 'add_new_employer', 0, 0, 0, 0, 0),
            (20, 'add_new_jobseeker', 0, 0, 0, 0, 0),
            (21, 'add_new_resume_visitor', 0, 0, 0, 0, 0),
            (23, 'add_new_job_visitor', 0, 0, 0, 0, 0),
            (24, 'resume-delete', 0, 0, 0, 0, 0),
            (25, 'applied-resume_status', 0, 0, 0, 0, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_experiences` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(100) NOT NULL,
              `status` tinyint(4) NOT NULL,
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=30;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_experiences` (`id`, `title`, `status`, `isdefault`, `ordering`, `serverid`) VALUES (1, 'Fresh', 1, 0, 1, 0), (2, 'Less then 1 Year', 1, 0, 2, 0), (3, '1 Year', 1, 0, 3, 0), (4, '2 Year', 1, 0, 4, 0), (5, '3 Year', 1, 1, 5, 0), (6, '4 Year', 1, 0, 6, 0), (7, '5 Year', 1, 0, 7, 0), (8, '6 Year', 1, 0, 8, 0), (9, '7 Year', 1, 0, 9, 0), (10, '8 Year', 1, 0, 10, 0), (11, '9 Year', 1, 0, 11, 0), (12, '10 Year', 1, 0, 12, 0), (13, '11 Year', 1, 0, 13, 0), (14, '12 Year', 1, 0, 14, 0), (15, '13 Year', 1, 0, 15, 0), (16, '14 Year', 1, 0, 16, 0), (17, '15 Year', 1, 0, 17, 0), (18, '16 Year', 1, 0, 18, 0), (19, '17 Year', 1, 0, 19, 0), (20, '18 Year', 1, 0, 20, 0), (21, '19 Year', 1, 0, 21, 0), (22, '20 Year', 1, 0, 22, 0), (23, '21 Year', 1, 0, 23, 0), (24, '22 Year', 1, 0, 24, 0), (25, '23 Year', 1, 0, 25, 0), (26, '24 Year', 1, 0, 26, 0), (27, '25 Year', 1, 0, 27, 0);";
            jsjobs::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_fieldsordering` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `field` varchar(50) NOT NULL,
              `fieldtitle` varchar(50) NOT NULL,
              `ordering` int(11) NOT NULL,
              `section` varchar(20) NOT NULL,
              `fieldfor` tinyint(2) NOT NULL,
              `published` tinyint(1) NOT NULL,
              `isvisitorpublished` tinyint(1) NOT NULL,
              `sys` tinyint(1) NOT NULL,
              `cannotunpublish` tinyint(1) NOT NULL,
              `required` tinyint(1) NOT NULL,
              `isuserfield` tinyint(1) NOT NULL,
              `userfieldtype` varchar(250) NOT NULL,
              `userfieldparams` text NOT NULL,
              `search_user` tinyint(1) NOT NULL,
              `search_visitor` tinyint(1) NOT NULL,
              `search_ordering` TINYINT NULL,
			  `cannotsearch` tinyint(1) NOT NULL,
              `showonlisting` tinyint(1) NOT NULL,
              `cannotshowonlisting` tinyint(1) NOT NULL,
              `depandant_field` varchar(250) NOT NULL,
              `readonly` tinyint(4) NOT NULL,
              `size` int(11) NOT NULL,
              `maxlength` int(11) NOT NULL,
              `cols` int(11) NOT NULL,
              `rows` int(11) NOT NULL,
              `j_script` text NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=401;";
            jsjobs::$_db->query($query);

			$query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_fieldsordering` (`id`, `field`, `fieldtitle`, `ordering`, `section`, `fieldfor`, `published`, `isvisitorpublished`, `sys`, `cannotunpublish`, `required`, `isuserfield`, `userfieldtype`, `userfieldparams`, `search_user`, `search_visitor`, `search_ordering`, `cannotsearch`, `showonlisting`, `cannotshowonlisting`, `depandant_field`, `readonly`, `size`, `maxlength`, `cols`, `rows`, `j_script`) VALUES
            (1, 'uid', 'User Id', 1, '', 1, 1, 1, 0, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(2, 'name', 'Name', 2, '', 1, 1, 1, 1, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 1, 0, 0, 0, 0, ''),
			(3, 'url', 'URL', 3, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(4, 'contactname', 'Contact Name', 4, '', 1, 1, 1, 0, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(5, 'contactphone', 'Contact Phone', 5, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 1, 0, 0, 0, 0, ''),
			(6, 'contactemail', 'Contact Email', 6, '', 1, 1, 1, 1, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 1, 0, 0, 0, 0, ''),
			(7, 'contactfax', 'Contact Fax', 7, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(8, 'category', 'Category', 8, '', 1, 1, 1, 0, 0, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(9, 'logo', 'Logo', 9, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(10, 'since', 'Since', 10, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(11, 'companysize', 'Company Size', 11, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(12, 'income', 'Income', 12, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(13, 'description', 'Description', 13, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(14, 'address1', 'Address1', 14, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(15, 'address2', 'Address2', 15, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(16, 'city', 'City', 16, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 0, 1, 1, '', 0, 0, 0, 0, 0, ''),
			(17, 'zipcode', 'Zip Code', 17, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(18, 'facebook', 'Facebook', 18, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(19, 'twitter', 'Twitter', 19, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(20, 'googleplus', 'Googleplus', 20, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(21, 'linkedin', 'Linkedin', 21, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(22, 'status', 'Status', 22, '', 1, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(23, 'termsandconditions', 'Terms And Conditions', 25, '', 1, 0, 0, 0, 0, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(101, 'jobtitle', 'Title', 1, '', 2, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(102, 'company', 'Company', 2, '', 2, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 1, 1, '', 0, 0, 0, 0, 0, ''),
			(103, 'department', 'Department', 3, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(104, 'jobcategory', 'Category', 4, '', 2, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, ''),
			(106, 'jobtype', 'Type', 6, '', 2, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, ''),
			(107, 'jobstatus', 'Status', 7, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 1, 1, '', 0, 0, 0, 0, 0, ''),
			(108, 'gender', 'Gender', 8, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(109, 'age', 'Age', 9, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(110, 'jobsalaryrange', 'Salary Range', 10, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, ''),
			(111, 'jobshift', 'Shift', 11, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(112, 'heighesteducation', 'Highest Education', 12, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(113, 'experience', 'Experience', 13, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(114, 'noofjobs', 'No of Jobs', 14, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 1, 1, '', 0, 0, 0, 0, 0, ''),
			(115, 'duration', 'Duration', 15, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(116, 'careerlevel', 'Career Level', 16, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(117, 'workpermit', 'Work Permit', 17, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(118, 'requiredtravel', 'Required Travel', 18, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(120, 'map', 'Map', 20, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(121, 'startpublishing', 'Start Publishing', 21, '', 2, 1, 1, 1, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(122, 'stoppublishing', 'Stop Publishing', 22, '', 2, 1, 1, 1, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(125, 'city', 'City', 25, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, ''),
			(126, 'zipcode', 'Zip Code', 26, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(127, 'sendemail', 'Send Email', 27, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(128, 'sendmeresume', 'Send me Resume', 28, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(129, 'description', 'Description', 29, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(130, 'qualifications', 'Qualifications', 30, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(131, 'prefferdskills', 'Prefered Skills', 31, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(132, 'agreement', 'Agreement', 32, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(133, 'filter', 'Filter', 33, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(134, 'emailsetting', 'Email Setting', 34, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(135, 'joblink', 'Redirect on apply', 35, '', 2, 0, 0, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(136, 'tags', 'Tags', 36, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(137, 'metadescription', 'Meta Description', 37, '', 2, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(138, 'metakeywords', 'Meta Keywords', 38, '', 2, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(139, 'termsandconditions', 'Terms And Conditions', 39, '', 2, 0, 0, 0, 0, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(301, 'section_personal', 'Personal Information', 0, '1', 3, 1, 1, 1, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(302, 'application_title', 'Application Title', 1, '1', 3, 1, 1, 0, 0, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, ''),
			(303, 'first_name', 'First Name', 3, '1', 3, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(304, 'middle_name', 'Middle Name', 3, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(305, 'last_name', 'Last Name', 4, '1', 3, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(306, 'email_address', 'Email Address', 5, '1', 3, 1, 1, 1, 1, 1, 0, '', '', 0, 0, NULL, 1, 1, 0, '', 0, 0, 0, 0, 0, ''),
			(307, 'cell', 'Cell', 6, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(308, 'nationality', 'Nationality', 7, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(309, 'gender', 'Gender', 8, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(310, 'photo', 'Photo', 9, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(311, 'resumefiles', 'Files', 10, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(312, 'job_category', 'Category', 11, '1', 3, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, ''),
			(313, 'jobtype', 'Type', 13, '1', 3, 1, 1, 1, 1, 1, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, ''),
			(314, 'heighestfinisheducation', 'Highest Education', 14, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(315, 'total_experience', 'Total Experience', 15, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 1, 0, '', 0, 0, 0, 0, 0, ''),
			(316, 'section_moreoptions', 'More Options', 16, '1', 3, 1, 1, 1, 1, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(317, 'home_phone', 'Home Phone', 17, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(318, 'work_phone', 'Work Phone', 18, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(319, 'date_of_birth', 'Date of Birth', 19, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(320, 'date_start', 'Date you can start', 20, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(321, 'salary', 'Salary', 21, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(322, 'desired_salary', 'Desired Salary', 22, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 1, 0, '', 0, 0, 0, 0, 0, ''),
			(323, 'video', 'Video', 23, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(324, 'keywords', 'Keywords', 24, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(325, 'searchable', 'Searchable', 25, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(326, 'iamavailable', 'I am Available', 26, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(327, 'driving_license', 'Driving License', 27, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(328, 'license_no', 'License Number', 28, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(329, 'license_country', 'License Country', 29, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(330, 'facebook', 'Facebook', 30, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(331, 'twitter', 'Twitter', 31, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(332, 'googleplus', 'Googleplus', 32, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(333, 'linkedin', 'Linkedin', 33, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(334, 'tags', 'Tags', 34, '1', 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(335, 'section_address', 'Add Address', 40, '2', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(336, 'address', 'Address', 41, '2', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(337, 'address_city', 'City', 42, '2', 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(338, 'address_zipcode', 'Zip Code', 43, '2', 3, 1, 1, 0, 0, 0, 0, '', '', 1, 1, NULL, 0, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(339, 'address_location', 'Location', 44, '2', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(340, 'section_education', 'Education', 50, '3', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(341, 'institute', 'Institute', 51, '3', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(342, 'institute_certificate_name', 'Certificate Name', 52, '3', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(343, 'institute_study_area', 'Study Area', 53, '3', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(344, 'institute_address', 'Address', 54, '3', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(345, 'institute_city', 'City', 54, '3', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(346, 'section_employer', 'Employer', 60, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(347, 'employer', 'Employer Name', 61, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(348, 'employer_position', 'Position', 62, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(349, 'employer_resp', 'Responsibilities', 63, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(350, 'employer_pay_upon_leaving', 'Pay Upon Leaving', 64, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(351, 'employer_supervisor', 'Supervisor', 65, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(352, 'employer_from_date', 'From Date', 66, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(353, 'employer_to_date', 'To Date', 67, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(354, 'employer_leave_reason', 'Leave Reason', 68, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(355, 'employer_phone', 'Phone', 69, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(356, 'employer_address', 'Address', 70, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(357, 'employer_city', 'City', 71, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(358, 'employer_zip', 'Zip Code', 72, '4', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(359, 'section_skills', 'Skills', 80, '5', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(360, 'skills', 'Skills', 81, '5', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(361, 'section_resume', 'Resume Editor', 82, '6', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(362, 'resume', 'Resume Editor', 83, '6', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(363, 'section_reference', 'Add References', 91, '7', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(364, 'reference', 'Reference', 92, '7', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(365, 'reference_name', 'Reference Name', 93, '7', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(366, 'reference_relation', 'Relation To Reference', 94, '7', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(367, 'reference_city', 'City', 95, '7', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(368, 'reference_zipcode', 'Zip Code', 96, '7', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(369, 'reference_address', 'Address', 97, '7', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(370, 'reference_phone', 'Phone Number', 98, '7', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(371, 'reference_years', 'Reference Years', 99, '7', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(372, 'section_language', 'Add Language', 100, '8', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(373, 'language', 'Language Name', 101, '8', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(374, 'language_reading', 'Reading', 102, '8', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(375, 'language_writing', 'Writing', 103, '8', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(376, 'language_understanding', 'Understanding', 104, '8', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(377, 'language_where_learned', 'Where Learned', 105, '8', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(380, 'institute_date_from', 'Date From', 55, '3', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(381, 'institute_date_to', 'Date To', 56, '3', 3, 1, 1, 0, 0, 0, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, ''),
			(382, 'termsandconditions', 'Terms And Conditions', 35, '1', 3, 0, 0, 0, 0, 1, 0, '', '', 0, 0, NULL, 1, 0, 1, '', 0, 0, 0, 0, 0, '');";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_heighesteducation` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(100) NOT NULL DEFAULT '',
              `isactive` tinyint(1) DEFAULT '1',
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_heighesteducation` (`id`, `title`, `isactive`, `isdefault`, `ordering`, `serverid`) VALUES(1, 'University', 1, 1, 1, 0),(2, 'College', 1, 0, 2, 0),(3, 'High School', 1, 0, 3, 0),(4, 'No School', 1, 0, 4, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_jobapply` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `jobid` int(11) NOT NULL DEFAULT '0',
              `uid` int(11) NOT NULL DEFAULT '0',
              `cvid` int(11) DEFAULT NULL,
              `apply_date` datetime DEFAULT NULL,
              `resumeview` tinyint(1) NOT NULL DEFAULT '0',
              `comments` varchar(1000) DEFAULT NULL,
              `rating` float NOT NULL,
              `coverletterid` int(11) DEFAULT NULL,
              `action_status` int(11) DEFAULT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              `socialapplied` tinyint(1) NOT NULL,
              `socialprofileid` int(11) NOT NULL,
              PRIMARY KEY (`id`),
              KEY `jobapply_uid` (`uid`),
              KEY `jobapply_jobid` (`jobid`),
              KEY `jobapply_cvid` (`cvid`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_jobcities` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `jobid` int(11) NOT NULL,
              `cityid` int(11) NOT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `jobid` (`jobid`),
              KEY `cityid` (`cityid`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_jobs` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) NOT NULL,
              `companyid` int(11) DEFAULT NULL,
              `title` varchar(255) NOT NULL DEFAULT '',
              `alias` varchar(225) NOT NULL,
              `jobcategory` varchar(255) NOT NULL DEFAULT '',
              `jobtype` tinyint(1) unsigned DEFAULT '0',
              `jobstatus` tinyint(3) NOT NULL DEFAULT '1',
              `jobsalaryrange` varchar(255) DEFAULT '',
              `salaryrangetype` varchar(20) DEFAULT NULL,
              `hidesalaryrange` tinyint(1) DEFAULT '1',
              `description` text,
              `qualifications` text,
              `prefferdskills` text,
              `applyinfo` text,
              `company` varchar(255) NOT NULL DEFAULT '',
              `country` varchar(255) DEFAULT '',
              `state` varchar(255) DEFAULT '',
              `county` varchar(255) DEFAULT '',
              `city` varchar(255) DEFAULT '',
              `zipcode` varchar(25) DEFAULT '',
              `address1` varchar(255) DEFAULT '',
              `address2` varchar(255) DEFAULT '',
              `companyurl` varchar(255) DEFAULT '',
              `contactname` varchar(255) DEFAULT '',
              `contactphone` varchar(255) DEFAULT '',
              `contactemail` varchar(255) DEFAULT '',
              `showcontact` tinyint(1) unsigned DEFAULT '0',
              `noofjobs` int(11) unsigned NOT NULL DEFAULT '1',
              `reference` varchar(255) NOT NULL DEFAULT '',
              `duration` varchar(255) NOT NULL DEFAULT '',
              `heighestfinisheducation` varchar(255) DEFAULT '',
              `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `created_by` int(11) unsigned NOT NULL DEFAULT '0',
              `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `modified_by` int(11) unsigned NOT NULL DEFAULT '0',
              `hits` int(11) unsigned NOT NULL DEFAULT '0',
              `experience` int(11) DEFAULT '0',
              `startpublishing` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `stoppublishing` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
              `departmentid` varchar(255) DEFAULT NULL,
              `shift` varchar(255) DEFAULT NULL,
              `sendemail` tinyint(1) NOT NULL DEFAULT '0',
              `metadescription` text,
              `metakeywords` text,
              `agreement` text,
              `ordering` tinyint(3) NOT NULL DEFAULT '0',
              `aboutjobfile` varchar(50) DEFAULT NULL,
              `status` int(11) DEFAULT '1',
              `educationminimax` tinyint(1) DEFAULT NULL,
              `educationid` int(11) DEFAULT NULL,
              `mineducationrange` int(11) DEFAULT NULL,
              `maxeducationrange` int(11) DEFAULT NULL,
              `iseducationminimax` tinyint(1) DEFAULT NULL,
              `degreetitle` varchar(255) DEFAULT NULL,
              `careerlevel` int(11) DEFAULT NULL,
              `experienceminimax` tinyint(1) DEFAULT NULL,
              `experienceid` int(11) DEFAULT NULL,
              `minexperiencerange` int(11) DEFAULT NULL,
              `maxexperiencerange` int(11) DEFAULT NULL,
              `isexperienceminimax` tinyint(1) DEFAULT NULL,
              `experiencetext` varchar(255) DEFAULT NULL,
              `workpermit` varchar(20) DEFAULT NULL,
              `requiredtravel` int(11) DEFAULT NULL,
              `agefrom` int(11) DEFAULT NULL,
              `ageto` int(11) DEFAULT NULL,
              `salaryrangefrom` int(11) DEFAULT NULL,
              `salaryrangeto` int(11) DEFAULT NULL,
              `gender` int(5) DEFAULT NULL,
              `map` varchar(1000) DEFAULT NULL,
              `packageid` int(11) DEFAULT NULL,
              `paymenthistoryid` int(11) DEFAULT NULL,
              `subcategoryid` int(11) DEFAULT NULL,
              `currencyid` int(11) DEFAULT NULL,
              `jobid` varchar(25) DEFAULT '',
              `longitude` varchar(1000) DEFAULT NULL,
              `latitude` varchar(1000) DEFAULT NULL,
              `isgoldjob` tinyint(1) DEFAULT '0',
              `startgolddate` datetime NOT NULL,
              `endgolddate` datetime NOT NULL,
              `startfeatureddate` datetime NOT NULL,
              `endfeatureddate` datetime NOT NULL,
              `isfeaturedjob` tinyint(1) DEFAULT '0',
              `raf_gender` tinyint(1) DEFAULT NULL,
              `raf_degreelevel` tinyint(1) DEFAULT NULL,
              `raf_experience` tinyint(1) DEFAULT NULL,
              `raf_age` tinyint(1) DEFAULT NULL,
              `raf_education` tinyint(1) DEFAULT NULL,
              `raf_category` tinyint(1) DEFAULT NULL,
              `raf_subcategory` tinyint(1) DEFAULT NULL,
              `raf_location` tinyint(1) DEFAULT NULL,
              `jobapplylink` tinyint(1) NOT NULL,
              `joblink` varchar(400) NOT NULL,
              `params` longtext NOT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              `tags` varchar(500) NOT NULL,
              PRIMARY KEY (`id`),
              KEY `jobcategory` (`jobcategory`),
              KEY `jobs_companyid` (`companyid`),
              KEY `jobsalaryrange` (`jobsalaryrange`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            jsjobs::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_jobstatus` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(100) NOT NULL DEFAULT '',
              `isactive` tinyint(1) DEFAULT '1',
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobstatus` (`id`, `title`, `isactive`, `isdefault`, `ordering`, `serverid`) VALUES(1, 'Sourcing', 1, 1, 1, 0),(2, 'Interviewing', 1, 0, 2, 0),(3, 'Closed to New Applicants', 1, 0, 3, 0),(4, 'Finalists Identified', 1, 0, 4, 0),(5, 'Pending Approval', 1, 0, 5, 0),(6, 'Hold', 1, 0, 6, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_jobtypes` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(100) NOT NULL DEFAULT '',
              `isactive` tinyint(1) DEFAULT '1',
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `status` tinyint(1) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              `alias` varchar(300) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_jobtypes` (`id`, `title`, `isactive`, `isdefault`, `ordering`, `status`, `serverid`, `alias`) VALUES(1, 'Full-Time', 1, 1, 1, 1, 1,'full-time'),(2, 'Part-Time', 1, 0, 2, 0, 0, 'part-time'),(3, 'Internship', 1, 0, 3, 0, 0, 'internship');";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_resume` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) DEFAULT NULL,
              `created` datetime NOT NULL,
              `last_modified` datetime DEFAULT NULL,
              `published` tinyint(1) DEFAULT NULL,
              `hits` int(11) DEFAULT NULL,
              `application_title` varchar(150) NOT NULL,
              `keywords` varchar(255) DEFAULT NULL,
              `alias` varchar(255) NOT NULL,
              `first_name` varchar(150) NOT NULL,
              `last_name` varchar(150) NOT NULL,
              `middle_name` varchar(150) DEFAULT NULL,
              `gender` varchar(10) DEFAULT NULL,
              `email_address` varchar(200) DEFAULT NULL,
              `home_phone` varchar(60) NOT NULL,
              `work_phone` varchar(60) DEFAULT NULL,
              `cell` varchar(60) DEFAULT NULL,
              `nationality` varchar(50) DEFAULT NULL,
              `iamavailable` tinyint(1) DEFAULT NULL,
              `searchable` tinyint(1) DEFAULT '1',
              `photo` varchar(150) DEFAULT NULL,
              `job_category` int(11) DEFAULT NULL,
              `jobsalaryrangestart` int(11) DEFAULT NULL,
              `jobsalaryrangeend` int(11) NOT NULL,
              `jobsalaryrangetype` int(11) DEFAULT NULL,
              `jobtype` int(11) DEFAULT NULL,
              `heighestfinisheducation` varchar(60) DEFAULT NULL,
              `status` int(11) NOT NULL,
              `resume` text,
              `date_start` datetime DEFAULT NULL,
              `desiredsalarystart` int(11) DEFAULT NULL,
              `desiredsalaryend` int(11) NOT NULL,
              `djobsalaryrangetype` int(11) DEFAULT NULL,
              `dcurrencyid` int(11) DEFAULT NULL,
              `can_work` varchar(250) DEFAULT NULL,
              `available` varchar(250) DEFAULT NULL,
              `unavailable` varchar(250) DEFAULT NULL,
              `experienceid` int(11) DEFAULT NULL,
              `skills` text,
              `driving_license` tinyint(1) DEFAULT NULL,
              `license_no` varchar(100) DEFAULT NULL,
              `license_country` varchar(50) DEFAULT NULL,
              `packageid` int(11) DEFAULT NULL,
              `paymenthistoryid` int(11) DEFAULT NULL,
              `currencyid` int(11) DEFAULT NULL,
              `job_subcategory` int(11) DEFAULT NULL,
              `date_of_birth` datetime DEFAULT NULL,
              `videotype` tinyint(1) NOT NULL,
              `video` text,
              `isgoldresume` tinyint(1) DEFAULT NULL,
              `startgolddate` datetime NOT NULL,
              `startfeatureddate` datetime NOT NULL,
              `endgolddate` datetime NOT NULL,
              `endfeatureddate` datetime NOT NULL,
              `isfeaturedresume` tinyint(1) DEFAULT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              `tags` varchar(500) NOT NULL,
              `facebook` varchar(300) NOT NULL,
              `twitter` varchar(300) NOT NULL,
              `googleplus` varchar(300) NOT NULL,
              `linkedin` varchar(300) NOT NULL,
              `params` longtext,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_resumeaddresses` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `resumeid` int(11) NOT NULL,
              `address` text,
              `address_country` varchar(100) DEFAULT NULL,
              `address_state` varchar(60) DEFAULT NULL,
              `address_city` varchar(100) DEFAULT NULL,
              `address_zipcode` varchar(60) DEFAULT NULL,
              `longitude` varchar(50) NOT NULL,
              `latitude` varchar(50) NOT NULL,
              `created` datetime NOT NULL,
              `last_modified` datetime NOT NULL,
              `params` longtext NOT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            jsjobs::$_db->query($query);



            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_resumereferences` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `resumeid` int(11) NOT NULL,
              `reference` varchar(50) DEFAULT NULL,
              `reference_name` varchar(50) DEFAULT NULL,
              `reference_country` varchar(50) DEFAULT NULL,
              `reference_state` varchar(50) DEFAULT NULL,
              `reference_city` varchar(50) DEFAULT NULL,
              `reference_zipcode` varchar(20) DEFAULT NULL,
              `reference_address` varchar(150) DEFAULT NULL,
              `reference_phone` varchar(50) DEFAULT NULL,
              `reference_relation` varchar(50) DEFAULT NULL,
              `reference_years` varchar(10) DEFAULT NULL,
              `created` datetime NOT NULL,
              `last_modified` datetime NOT NULL,
              `params` longtext NOT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            jsjobs::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_resumeemployers` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `resumeid` int(11) NOT NULL,
              `employer` varchar(250) DEFAULT NULL,
              `employer_position` varchar(150) DEFAULT NULL,
              `employer_resp` text,
              `employer_pay_upon_leaving` varchar(250) DEFAULT NULL,
              `employer_supervisor` varchar(100) DEFAULT NULL,
              `employer_from_date` varchar(60) DEFAULT NULL,
              `employer_to_date` varchar(60) DEFAULT NULL,
              `employer_leave_reason` text,
              `employer_country` varchar(100) DEFAULT NULL,
              `employer_state` varchar(100) DEFAULT NULL,
              `employer_city` varchar(100) DEFAULT NULL,
              `employer_zip` varchar(60) DEFAULT NULL,
              `employer_phone` varchar(60) DEFAULT NULL,
              `employer_address` varchar(150) DEFAULT NULL,
              `created` datetime NOT NULL,
              `last_modified` datetime NOT NULL,
              `params` longtext NOT NULL,
              `serverstatus` varchar(255) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
            jsjobs::$_db->query($query);


          $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_resumefiles` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `resumeid` int(11) NOT NULL,
            `filename` varchar(300) DEFAULT NULL,
            `filetype` varchar(255) DEFAULT NULL,
            `filesize` int(11) DEFAULT NULL,
            `created` datetime NOT NULL,
            `last_modified` datetime NOT NULL,
            `serverstatus` varchar(255) DEFAULT NULL,
            `serverid` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
          jsjobs::$_db->query($query);


          $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_resumeinstitutes` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `resumeid` int(11) NOT NULL,
            `institute` varchar(100) DEFAULT NULL,
            `institute_country` varchar(100) DEFAULT NULL,
            `institute_state` varchar(100) DEFAULT NULL,
            `institute_city` varchar(100) DEFAULT NULL,
            `institute_address` varchar(150) DEFAULT NULL,
            `institute_certificate_name` varchar(100) DEFAULT NULL,
            `institute_study_area` text,
            `created` datetime NOT NULL,
            `last_modified` datetime NOT NULL,
            `serverstatus` varchar(255) DEFAULT NULL,
            `serverid` int(11) DEFAULT NULL,
            `fromdate` VARCHAR(60) DEFAULT NULL,
            `todate` VARCHAR(60) DEFAULT NULL,
            `iscontinue` tinyint(4) DEFAULT NULL,
            `params` longtext NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
          jsjobs::$_db->query($query);



          $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_resumelanguages` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `resumeid` int(11) NOT NULL,
            `language` varchar(50) DEFAULT NULL,
            `language_reading` varchar(20) DEFAULT NULL,
            `language_writing` varchar(20) DEFAULT NULL,
            `language_understanding` varchar(20) DEFAULT NULL,
            `language_where_learned` varchar(250) DEFAULT NULL,
            `created` datetime NOT NULL,
            `last_modified` datetime NOT NULL,
            `params` longtext NOT NULL,
            `serverstatus` varchar(255) DEFAULT NULL,
            `serverid` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
          jsjobs::$_db->query($query);


            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_salaryrange` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `rangevalue` varchar(255) DEFAULT NULL,
              `rangestart` varchar(255) DEFAULT NULL,
              `rangeend` varchar(255) DEFAULT NULL,
              `status` tinyint(1) DEFAULT NULL,
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_salaryrange` (`id`, `rangevalue`, `rangestart`, `rangeend`, `status`, `isdefault`, `ordering`, `serverid`) VALUES(1, NULL, '1000', '1500', 1, 1, 1, 0),(2, NULL, '1500', '2000', 1, 0, 2, 0),(3, NULL, '2000', '2500', 1, 0, 3, 0),(4, NULL, '2500', '3000', 1, 0, 4, 0),(5, NULL, '3000', '3500', 1, 0, 5, 0),(6, NULL, '3500', '4000', 1, 0, 6, 0),(7, NULL, '4000', '4500', 1, 0, 7, 0),(8, NULL, '4500', '5000', 1, 0, 8, 0),(9, NULL, '5000', '5500', 1, 0, 9, 0),(10, NULL, '5500', '6000', 1, 0, 10, 0),(11, NULL, '6000', '7000', 1, 0, 11, 0),(12, NULL, '7000', '8000', 1, 0, 12, 0),(13, NULL, '8000', '9000', 1, 0, 13, 0),(14, NULL, '9000', '10000', 1, 0, 14, 0),(15, NULL, '10000', '10000', 1, 0, 15, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(45) NOT NULL,
              `status` tinyint(4) NOT NULL,
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;";
            jsjobs::$_db->query($query);


            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_salaryrangetypes` (`id`, `title`, `status`, `isdefault`, `ordering`, `serverid`) VALUES(1, 'Per Year', 1, 0, 1, 0),(2, 'Per Month', 1, 1, 2, 0),(3, 'Per Week', 1, 0, 3, 0),(4, 'Per Day', 1, 0, 4, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_shifts` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `title` varchar(100) NOT NULL DEFAULT '',
              `isactive` tinyint(1) DEFAULT '1',
              `isdefault` tinyint(1) DEFAULT NULL,
              `ordering` int(11) DEFAULT NULL,
              `status` tinyint(1) DEFAULT NULL,
              `serverid` int(11) DEFAULT '0',
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_shifts` (`id`, `title`, `isactive`, `isdefault`, `ordering`, `status`, `serverid`) VALUES(1, 'Morning', 1, 1, 1, 0, 0),(2, 'Evening', 1, 0, 2, 0, 0),(3, '8 PM to 4 AM', 1, 0, 3, 0, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_states` (
              `id` smallint(8) NOT NULL AUTO_INCREMENT,
              `name` varchar(35) DEFAULT NULL,
              `shortRegion` varchar(25) DEFAULT NULL,
              `countryid` smallint(9) DEFAULT NULL,
              `enabled` tinyint(1) NOT NULL DEFAULT '0',
              `serverid` int(11) DEFAULT NULL,
              PRIMARY KEY (`id`),
              KEY `countryid` (`countryid`),
              FULLTEXT KEY `name` (`name`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=91 ;";
            jsjobs::$_db->query($query);
            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_states` (`id`, `name`, `shortRegion`, `countryid`, `enabled`, `serverid`) VALUES(1, 'Alabama', 'AL', 1, 1, 0),(2, 'Alaska', 'AK', 1, 1, 0),(3, 'Arizona', 'AZ', 1, 1, 0),(4, 'Arkansas', 'AR', 1, 1, 0),(5, 'California', 'CA', 1, 1, 0),(6, 'Colorado', 'CO', 1, 1, 0),(7, 'Connecticut', 'CT', 1, 1, 0),(8, 'Delaware', 'DE', 1, 1, 0),(9, 'District of Columbia', 'DC', 1, 1, 0),(10, 'Florida', 'FL', 1, 1, 0),(11, 'Georgia', 'GA', 1, 1, 0),(12, 'Hawaii', 'HI', 1, 1, 0),(13, 'Idaho', 'ID', 1, 1, 0),(14, 'Illinois', 'IL', 1, 1, 0),(15, 'Indiana', 'IN', 1, 1, 0),(16, 'Iowa', 'IA', 1, 1, 0),(17, 'Kansas', 'KS', 1, 1, 0),(18, 'Kentucky', 'KY', 1, 1, 0),(19, 'Louisiana', 'LA', 1, 1, 0),(20, 'Maine', 'ME', 1, 1, 0),(21, 'Maryland', 'MD', 1, 1, 0),(22, 'Massachusetts', 'MA', 1, 1, 0),(23, 'Michigan', 'MI', 1, 1, 0),(24, 'Minnesota', 'MN', 1, 1, 0),(25, 'Mississippi', 'MS', 1, 1, 0),(26, 'Missouri', 'MO', 1, 1, 0),(27, 'Montana', 'MT', 1, 1, 0),(28, 'Nebraska', 'NE', 1, 1, 0),(29, 'Nevada', 'NV', 1, 1, 0),(30, 'New Hampshire', 'NH', 1, 1, 0),(31, 'New Jersey', 'NJ', 1, 1, 0),(32, 'New Mexico', 'NM', 1, 1, 0),(33, 'New York', 'NY', 1, 1, 0),(34, 'North Carolina', 'NC', 1, 1, 0),(35, 'North Dakota', 'ND', 1, 1, 0),(36, 'Ohio', 'OH', 1, 1, 0),(37, 'Oklahoma', 'OK', 1, 1, 0),(38, 'Oregon', 'OR', 1, 1, 0),(39, 'Pennsylvania', 'PA', 1, 1, 0),(40, 'Rhode Island', 'RI', 1, 1, 0),(41, 'South Carolina', 'SC', 1, 1, 0),(42, 'South Dakota', 'SD', 1, 1, 0),(43, 'Tennessee', 'TN', 1, 1, 0),(44, 'Texas', 'TX', 1, 1, 0),(45, 'Utah', 'UT', 1, 1, 0),(46, 'Vermont', 'VT', 1, 1, 0),(47, 'Virginia', 'VA', 1, 1, 0),(48, 'Washington', 'WA', 1, 1, 0),(49, 'West Virginia', 'WV', 1, 1, 0),(50, 'Wisconsin', 'WI', 1, 1, 0),(51, 'Wyoming', 'WY', 1, 1, 0),(52, 'Alberta', 'AB', 2, 1, 0),(53, 'British Columbia', 'BC', 2, 1, 0),(54, 'Manitoba', 'MB', 2, 1, 0),(55, 'New Brunswick', 'NB', 2, 1, 0),(56, 'Newfoundland and Labrador', 'NL', 2, 1, 0),(57, 'Northwest Territories', 'NT', 2, 1, 0),(58, 'Nova Scotia', 'NS', 2, 1, 0),(59, 'Nunavut', 'NU', 2, 1, 0),(60, 'Ontario', 'ON', 2, 1, 0),(61, 'Prince Edward Island', 'PE', 2, 1, 0),(62, 'Quebec', 'QC', 2, 1, 0),(63, 'Saskatchewan', 'SK', 2, 1, 0),(64, 'Yukon', 'YT', 2, 1, 0),(65, 'England', 'England', 95, 1, 0),(66, 'Northern Ireland', 'NorthernIreland', 95, 1, 0),(67, 'Scotland', 'Scottland', 95, 1, 0),(68, 'Wales', 'Wales', 95, 1, 0),(86, 'NWFP', 'NWFP', 126, 1, 0),(87, 'FATA', 'FATA', 126, 1, 0),(88, 'Balochistan', 'Balochistan', 126, 1, 0),(89, 'Punjab', 'Punjab', 126, 1, 0),(90, 'Capital', 'Capital', 126, 1, 0);";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_activitylog` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `description` text NOT NULL,
              `referencefor` varchar(50) NOT NULL,
              `referenceid` int(11) NOT NULL,
              `uid` int(11) NOT NULL,
              `created` datetime NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_system_errors` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) DEFAULT NULL,
              `error` text,
              `isview` tinyint(1) DEFAULT '0',
              `created` datetime DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_users` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `uid` int(11) NOT NULL,
              `roleid` int(11) NOT NULL,
              `first_name` varchar(300) NOT NULL,
              `last_name` varchar(300) NOT NULL,
              `emailaddress` varchar(250) NOT NULL,
              `socialid` varchar(250) NOT NULL,
              `socialmedia` varchar(250) NOT NULL,
              `status` tinyint(1) NOT NULL,
              `created` datetime NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
            jsjobs::$_db->query($query);

            $query = "CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_slug` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `slug` varchar(100) CHARACTER SET utf8 NOT NULL,
			  `defaultslug` varchar(100) CHARACTER SET utf8 NOT NULL,
			  `filename` varchar(100) CHARACTER SET utf8 NOT NULL,
			  `description` varchar(200) CHARACTER SET utf8 NOT NULL,
			  `status` tinyint(11) DEFAULT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=64;";
            jsjobs::$_db->query($query);

            $query = "INSERT INTO `" . jsjobs::$_db->prefix . "js_job_slug`  (`id`, `slug`, `defaultslug`, `filename`, `description`, `status`) VALUES
				(1, 'new-in-jsjobs', 'new-in-jsjobs', 'newinjsjobs', 'slug for new in js jobs page', 1),
				(2, 'jsjobs-login', 'jsjobs-login', 'login', 'slug for login page', 1),
				(3, 'jobseeker-control-panel', 'jobseeker-control-panel', 'controlpanel', 'slug for jobseeker control panel', 1),
				(4, 'employer-control-panel', 'employer-control-panel', 'controlpanel', 'slug for employer control panel', 1),
				(5, 'jobseeker-my-stats', 'jobseeker-my-stats', 'mystats', 'slug for job seeker my stats page', 1),
				(6, 'employer-my-stats', 'employer-my-stats', 'mystats', 'slug for employer my stats page', 1),
				(7, 'resumes', 'resumes', 'resumes', 'slug for resume main listing page', 1),
				(8, 'jobs', 'jobs', 'jobs', 'slug for job main listing page', 1),
				(9, 'my-companies', 'my-companies', 'mycompanies', 'slug for my companies page', 1),
				(10, 'add-company', 'add-company', 'addcompany', 'slug for add company page', 1),
				(11, 'my-jobs', 'my-jobs', 'myjobs', 'slug for my jobs page', 1),
				(12, 'add-job', 'add-job', 'addjob', 'slug for add job page', 1),
				(13, 'my-departments', 'my-departments', 'mydepartments', 'slug for my departments page', 1),
				(14, 'add-department', 'add-department', 'adddepartment', 'slug for add department page', 1),
				(15, 'department', 'department', 'viewdepartment', 'slug for view department page', 1),
				(16, 'cover-letter', 'cover-letter', 'viewcoverletter', 'slug for view cover letter page', 1),
				(17, 'company', 'company', 'viewcompany', 'slug for view company page', 1),
				(18, 'resume', 'resume', 'viewresume', 'slug for view resume page', 1),
				(19, 'job', 'job', 'viewjob', 'slug for view job page', 1),
				(20, 'my-folders', 'my-folders', 'myfolders', 'slug for my folders page', 1),
				(21, 'add-folder', 'add-folder', 'addfolder', 'slug for add folder page', 1),
				(22, 'folder', 'folder', 'viewfolder', 'slug for view folder page', 1),
				(23, 'folder-resumes', 'folder-resumes', 'folderresume', 'slug for folder resume page', 1),
				(24, 'jobseeker-messages', 'jobseeker-messages', 'jobseekermessages', 'slug for job seeker messages page', 1),
				(25, 'employer-messages', 'employer-messages', 'employermessages', 'slug for employer messages page', 1),
				(26, 'message', 'message', 'sendmessage', 'slug for send message page', 1),
				(27, 'job-messages', 'job-messages', 'jobmessages', 'slug for job messages page', 1),
				(28, 'job-types', 'job-types', 'jobsbytypes', 'slug for jobs by types page', 1),
				(29, 'messages', 'messages', 'messages', 'slug for messages page', 1),
				(30, 'resume-search', 'resume-search', 'resumesearch', 'slug for resume search page', 1),
				(31, 'resume-save-searches', 'resume-save-searches', 'resumesavesearch', 'slug for resume save search page', 1),
				(32, 'resume-categories', 'resume-categories', 'resumebycategory', 'slug for resume by category page', 1),
				(33, 'resume-rss', 'resume-rss', 'resumerss', 'slug for resume rss page', 1),
				(34, 'employer-credits', 'employer-credits', 'employercredits', 'slug for employer credits page', 1),
				(35, 'jobseeker-credits', 'jobseeker-credits', 'jobseekercredits', 'slug for job seeker credits page', 1),
				(36, 'employer-purchase-history', 'employer-purchase-history', 'employerpurchasehistory', 'slug for employer purchase history page', 1),
				(37, 'employer-my-stats', 'employer-my-stats', 'employermystats', 'employer my stats page', 1),
				(38, 'jobseker-my-stats', 'jobseker-my-stats', 'jobseekerstats', 'slug for job seeker stats page', 1),
				(39, 'employer-register', 'employer-register', 'regemployer', 'slug for register as employer page', 1),
				(40, 'jobseeker-register', 'jobseeker-register', 'regjobseeker', 'reg job seeker page', 1),
				(41, 'user-register', 'user-register', 'userregister', 'slug for user register page', 1),
				(42, 'add-resume', 'add-resume', 'addresume', 'slug for add resume page', 1),
				(43, 'my-resumes', 'my-resumes', 'myresumes', 'slug for my resumes page', 1),
				(44, 'add-cover-letter', 'add-cover-letter', 'addcoverletter', 'slug for add cover letter page', 1),
				(45, 'companies', 'companies', 'companies', 'slug for companies page', 1),
				(46, 'my-applied-jobs', 'my-applied-jobs', 'myappliedjobs', 'slug for my applied jobs page', 1),
				(47, 'job-applied-resume', 'job-applied-resume', 'jobappliedresume', 'slug for job applied resume page', 1),
				(48, 'my-cover-letters', 'my-cover-letters', 'mycoverletters', 'slug for my cover letters page', 1),
				(49, 'job-search', 'job-search', 'jobsearch', 'slug for job search page', 1),
				(50, 'job-save-searches', 'job-save-searches', 'jobsavesearch', 'slug for job save search page', 1),
				(51, 'job-alert', 'job-alert', 'jobalert', 'slug for job alert page', 1),
				(52, 'job-rss', 'job-rss', 'jobrss', 'slug for job rss page', 1),
				(53, 'shortlisted-jobs', 'shortlisted-jobs', 'shortlistedjobs', 'slug for shortlisted jobs page', 1),
				(54, 'jobseeker-purchase-history', 'jobseeker-purchase-history', 'jobseekerpurchasehistory', 'slug for job seeker purchase history page', 1),
				(55, 'jobseeker-rate-list', 'jobseeker-rate-list', 'ratelistjobseeker', 'slug for rate list job seeker page', 1),
				(56, 'employer-rate-list', 'employer-rate-list', 'ratelistemployer', 'slug for rate list employer page', 1),
				(57, 'jobseeker-credits-log', 'jobseeker-credits-log', 'jobseekercreditslog', 'slug for job seeker credits log page', 1),
				(58, 'employer-credits-log', 'employer-credits-log', 'employercreditslog', 'slug for employer credits log page', 1),
				(59, 'job-categories', 'job-categories', 'jobsbycategories', 'slug for jobs by categories page', 1),
				(60, 'newest-jobs', 'newest-jobs', 'newestjobs', 'slug for newest jobs page', 1),
				(61, 'job-by-types', 'job-by-types', 'jobsbytypes', 'slug for jobs by types page', 1),
				(62, 'resume-pdf', 'resume-pdf', 'pdf', 'slug for pdf page', 1),
				(63, 'resume-print', 'resume-print', 'printresume', 'slug for print resume page', 1),
				(64, 'jobs-by-cities', 'jobs-by-cities', 'jobsbycities', 'slug for jobs by cities page', 1),
        (65, 'jsjobs-lost-password', 'jsjobs-lost-password', 'passwordlostform', 'slug for lost password form', 1),
        (66, 'jsjobs-reset-new-password', 'jsjobs-reset-new-password', 'resetnewpasswordform', 'slug for reset password form', 1);";
            jsjobs::$_db->query($query);
        $query = "
            CREATE TABLE IF NOT EXISTS `" . jsjobs::$_db->prefix . "js_job_jsjobsessiondata` (
              `id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
              `usersessionid` char(64) NOT NULL,
              `sessionmsg` text CHARACTER SET utf8 NOT NULL,
              `sessionexpire` bigint(32) NOT NULL,
              `sessionfor` varchar(125) NOT NULL,
              `msgkey`varchar(125) NOT NULL
            ) ENGINE=MyISAM DEFAULT CHARSET=latin1
        ";
        jsjobs::$_db->query($query);

        }
      }
    }
?>
