#
# Table structure for table cf_sudhaus7viewhelpers_metatags
CREATE TABLE cf_sudhaus7viewhelpers_metatags (
    id int(11) unsigned NOT NULL auto_increment,
    identifier varchar(250) DEFAULT '' NOT NULL,
    expires int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    content mediumblob,
    lifetime int(11) unsigned DEFAULT '0' NOT NULL,
    PRIMARY KEY (id),
    KEY cache_id (identifier)
);

#
# Table structure for table cf_sudhaus7viewhelpers_metatags_tags
#
CREATE TABLE cf_sudhaus7viewhelpers_metatags_tags (
    id int(11) unsigned NOT NULL auto_increment,
    identifier varchar(250) DEFAULT '' NOT NULL,
    tag varchar(250) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    KEY cache_id (identifier),
    KEY cache_tag (tag)
) ;

#
# Table structure for table cf_sudhaus7viewhelpers_cache
CREATE TABLE cf_sudhaus7viewhelpers_cache (
    id int(11) unsigned NOT NULL auto_increment,
    identifier varchar(250) DEFAULT '' NOT NULL,
    expires int(11) unsigned DEFAULT '0' NOT NULL,
    crdate int(11) unsigned DEFAULT '0' NOT NULL,
    content mediumblob,
    lifetime int(11) unsigned DEFAULT '0' NOT NULL,
    PRIMARY KEY (id),
    KEY cache_id (identifier)
) ;

#
# Table structure for table cf_sudhaus7viewhelpers_cache_tags
#
CREATE TABLE cf_sudhaus7viewhelpers_cache_tags (
    id int(11) unsigned NOT NULL auto_increment,
    identifier varchar(250) DEFAULT '' NOT NULL,
    tag varchar(250) DEFAULT '' NOT NULL,
    PRIMARY KEY (id),
    KEY cache_id (identifier),
    KEY cache_tag (tag)
) ;

CREATE TABLE sys_file_reference (
    tx_sudhaus7viewhelpers_posterimage varchar(256) DEFAULT '' NOT NULL
);
