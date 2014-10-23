<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('feedback')};
CREATE TABLE {$this->getTable('feedback')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `email` varchar(255) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `detail` varchar(255) NOT NULL default '',
  `created_at` datetime NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

");

$installer->endSetup(); 
