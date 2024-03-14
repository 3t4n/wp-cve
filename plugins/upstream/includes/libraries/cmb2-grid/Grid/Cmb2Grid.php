<?php

namespace Cmb2Grid\Grid;

/**
 * Description of Cmb2Grid.
 *
 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
 */

if ( ! class_exists('\Cmb2Grid\Grid\Cmb2Grid')) {
    class Cmb2Grid
    {
        private $cmb2Obj;
        private $cmb2Id;
        private $metaBoxConfig;
        private $rows = [];


        public function __construct($meta_box_config)
        {
            $this->setMetaBoxConfig($meta_box_config);
            $this->setCmb2Obj(\cmb2_get_metabox($this->getMetaBoxConfig()));
            //$cmb2Obj = $this->getCmb2Obj();
            //error_log( '--- DEBUG: $cmb2Obj ---' );
            //error_log( print_r( $cmb2Obj, true ) );
            //add_action( 'admin_init', array( $this, 'adminInit' ), 15 );
        }

        /**
         *
         * @param type $field
         *
         * @return \Cmb2Grid\Grid\Group\Cmb2GroupGrid
         */
        public function addCmb2GroupGrid($field)
        {
            $cmb2GroupGrid = new Group\Cmb2GroupGrid($this->getMetaBoxConfig());
            $cmb2GroupGrid->setParentFieldId($field);

            return $cmb2GroupGrid;
        }

        public function addRow()
        {
            $rows   = $this->getRows();
            $newRow = new Row($this);
            $rows[] = $newRow;
            $this->setRows($rows);

            return $newRow;
        }

        /**
         *
         * @return \CMB2
         */
        public function getCmb2Obj()
        {
            return $this->cmb2Obj;
        }

        public function setCmb2Obj($cmb2Obj)
        {
            $this->cmb2Obj = $cmb2Obj;
        }

        public function getCmb2Id()
        {
            return $this->cmb2Id;
        }

        public function setCmb2Id($cmb2Id)
        {
            $this->cmb2Id = $cmb2Id;
        }

        public function getMetaBoxConfig()
        {
            return $this->metaBoxConfig;
        }

        public function setMetaBoxConfig($metaBoxConfig)
        {
            $this->metaBoxConfig = $metaBoxConfig;
        }

        public function getRows()
        {
            return $this->rows;
        }

        public function setRows($rows)
        {
            $this->rows = $rows;
        }
    }
}
