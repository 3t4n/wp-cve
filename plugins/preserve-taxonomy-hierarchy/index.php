<?php
/*
  Plugin Name: Preserve Taxonomy Hierarchy
  Plugin URI: https://neatma.com/
  Description: A super simple plugin that preserves hierarchy in posts category and nav menu editor lists.
  Version: 1.0.1
  Author: Amin Nazemi
  Author URI: https://neatma.com/
  License: GPLv2 +
*/

use preserveHierarchy\TaxonomyHierarchy;

require_once dirname(__FILE__) . '/preserve-taxonomy-hierarchy.php';

if (!class_exists('TaxonomyHierarchy')) {
    new TaxonomyHierarchy;
}

