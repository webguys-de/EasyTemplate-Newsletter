<?php

/* @var $installer Mage_Core_Model_Resource_Setup */

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE  `" . $this->getTable('newsletter/template') . "` ADD `view_mode` VARCHAR( 20 ) NOT NULL DEFAULT 'core';
");

$installer->endSetup();

