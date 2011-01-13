CREATE TABLE jos_assets (
  id int NOT NULL IDENTITY(1,1),
  parent_id int NOT NULL DEFAULT '0',
  lft int NOT NULL DEFAULT '0',
  rgt int NOT NULL DEFAULT '0',
  level int NOT NULL,
  name varchar(50) NOT NULL,
  title varchar(100) NOT NULL ,
  rules varchar(5120) NOT NULL,
  PRIMARY KEY (id)
);

CREATE INDEX [idx_assets_lft_rgt] ON jos_assets (lft,rgt)
CREATE INDEX idx_parent_id ON jos_assets (parent_id)
CREATE UNIQUE INDEX idx_asset_name On jos_assets (name)

SET IDENTITY_INSERT jos_assets  ON 

INSERT INTO jos_assets (id, parent_id, lft, rgt, level, name, title, rules)
SELECT 1,0,0,61,0,'root.1','Root Asset','{"core.login.site":{"6":1,"2":1},"core.login.admin":{"6":1},"core.admin":{"8":1},"core.manage":{"7":1},"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1}}'
UNION ALL
SELECT 2,1,1,2,1,'com_admin','com_admin','{}'
UNION ALL
SELECT 3,1,3,6,1,'com_banners','com_banners','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 4,1,7,8,1,'com_cache','com_cache','{"core.admin":{"7":1},"core.manage":{"7":1}}'
UNION ALL
SELECT 5,1,9,10,1,'com_checkin','com_checkin','{"core.admin":{"7":1},"core.manage":{"7":1}}'
UNION ALL
SELECT 6,1,11,12,1,'com_config','com_config','{}'
UNION ALL
SELECT 7,1,13,16,1,'com_contact','com_contact','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 8,1,17,20,1,'com_content','com_content','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.delete":[],"core.edit":{"4":1},"core.edit.state":{"5":1}}'
UNION ALL
SELECT 9,1,21,22,1,'com_cpanel','com_cpanel','{}'
UNION ALL
SELECT 10,1,23,24,1,'com_installer','com_installer','{"core.admin":{"7":1},"core.manage":{"7":1},"core.create":[],"core.delete":[],"core.edit.state":[]}'
UNION ALL
SELECT 11,1,25,26,1,'com_languages','com_languages','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 12,1,27,28,1,'com_login','com_login','{}'
UNION ALL
SELECT 13,1,29,30,1,'com_mailto','com_mailto','{}'
UNION ALL
SELECT 14,1,31,32,1,'com_massmail','com_massmail','{}'
UNION ALL
SELECT 15,1,33,34,1,'com_media','com_media','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.delete":{"5":1},"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 16,1,35,36,1,'com_menus','com_menus','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 17,1,37,38,1,'com_messages','com_messages','{"core.admin":{"7":1},"core.manage":{"7":1}}'
UNION ALL
SELECT 18,1,39,40,1,'com_modules','com_modules','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 19,1,41,44,1,'com_newsfeeds','com_newsfeeds','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 20,1,45,46,1,'com_plugins','com_plugins','{"core.admin":{"7":1},"core.manage":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 21,1,47,48,1,'com_redirect','com_redirect','{"core.admin":{"7":1},"core.manage":[]}'
UNION ALL
SELECT 22,1,49,50,1,'com_search','com_search','{"core.admin":{"7":1},"core.manage":{"6":1}}'
UNION ALL
SELECT 23,1,51,52,1,'com_templates','com_templates','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 24,1,53,54,1,'com_users','com_users','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 25,1,55,58,1,'com_weblinks','com_weblinks','{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":{"3":1},"core.delete":[],"core.edit":{"4":1},"core.edit.state":{"5":1}}'
UNION ALL
SELECT 26,1,59,60,1,'com_wrapper','com_wrapper','{}'
UNION ALL
SELECT 27, 8, 18, 19, 2, 'com_content.category.2', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 28, 3, 4, 5, 2, 'com_banners.category.3', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 29, 7, 14, 15, 2, 'com_contact.category.4', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 30, 19, 42, 43, 2, 'com_newsfeeds.category.5', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'
UNION ALL
SELECT 31, 25, 56, 57, 2, 'com_weblinks.category.6', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}'

SET IDENTITY_INSERT jos_assets  OFF 


CREATE TABLE jos_banners (
  id INTEGER NOT NULL IDENTITY(1,1),
  cid INTEGER NOT NULL DEFAULT '0',
  type INTEGER NOT NULL DEFAULT '0',
  name VARCHAR(255) NOT NULL DEFAULT '',
  alias VARCHAR(255) NOT NULL DEFAULT '',
  imptotal INTEGER NOT NULL DEFAULT '0',
  impmade INTEGER NOT NULL DEFAULT '0',
  clicks INTEGER NOT NULL DEFAULT '0',
  clickurl VARCHAR(200) NOT NULL DEFAULT '',
  state smallint NOT NULL DEFAULT '0',
  catid INTEGER NOT NULL DEFAULT 0,
  description TEXT NOT NULL,
  custombannercode VARCHAR(2048) NOT NULL,
  sticky smallint NOT NULL DEFAULT 0,
  ordering INTEGER NOT NULL DEFAULT 0,
  metakey TEXT NOT NULL,
  params TEXT NOT NULL,
  own_prefix smallint NOT NULL DEFAULT '0',
  metakey_prefix VARCHAR(255) NOT NULL DEFAULT '',
  purchase_type smallint NOT NULL DEFAULT '-1',
  track_clicks smallint NOT NULL DEFAULT '-1',
  track_impressions smallint NOT NULL DEFAULT '-1',
  checked_out INTEGER NOT NULL DEFAULT '0',
  checked_out_time DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  publish_up DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  publish_down DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  reset DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  created DATETIME NOT NULL DEFAULT '1900-01-01 00:00:00',
  language char NOT NULL DEFAULT '',
  PRIMARY KEY  (id)  
);

 CREATE INDEX idx_state ON jos_banners (state)
 CREATE INDEX idx_own_prefix ON  jos_banners (own_prefix)
 CREATE INDEX idx_metakey_prefix ON jos_banners (metakey_prefix)
 CREATE INDEX idx_banner_catid ON jos_banners (catid)
 CREATE INDEX idx_language ON jos_banners (language)


CREATE TABLE jos_banner_clients (
  id INTEGER NOT NULL IDENTITY(1,1),
  name VARCHAR(255) NOT NULL DEFAULT '',
  contact VARCHAR(255) NOT NULL DEFAULT '',
  email VARCHAR(255) NOT NULL DEFAULT '',
  extrainfo TEXT NOT NULL,
  state smallint NOT NULL DEFAULT '0',
  checked_out INTEGER NOT NULL DEFAULT '0',
  checked_out_time DATETIME NOT NULL default '1900-01-01 00:00:00',
  metakey TEXT NOT NULL,
  own_prefix smallint NOT NULL DEFAULT '0',
  metakey_prefix VARCHAR(255) NOT NULL default '',
  purchase_type smallint NOT NULL DEFAULT '-1',
  track_clicks smallint NOT NULL DEFAULT '-1',
  track_impressions smallint NOT NULL DEFAULT '-1',
  PRIMARY KEY  (id)
);

CREATE INDEX idx_own_prefix ON jos_banner_clients (own_prefix)
CREATE INDEX idx_metakey_prefix ON jos_banner_clients (metakey_prefix)



CREATE TABLE  jos_banner_tracks (
  track_date DATETIME NOT NULL,
  track_type INTEGER NOT NULL,
  banner_id INTEGER NOT NULL,
  count INTEGER NOT NULL DEFAULT '0',
  PRIMARY KEY (track_date, track_type, banner_id)
);

 CREATE INDEX idx_track_date ON jos_banner_tracks (track_date)
 CREATE INDEX idx_track_type ON jos_banner_tracks (track_type)
 CREATE INDEX idx_banner_id ON jos_banner_tracks (banner_id)


CREATE TABLE jos_categories (
  id int NOT NULL IDENTITY(1,1),
  asset_id INTEGER NOT NULL DEFAULT 0,
  parent_id int NOT NULL default '0',
  lft int NOT NULL default '0',
  rgt int NOT NULL default '0',
  level int NOT NULL default '0',
  path varchar(255) NOT NULL default '',
  extension varchar(50) NOT NULL default '',
  title varchar(255) NOT NULL,
  alias varchar(255) NOT NULL default '',
  note varchar(255) NOT NULL default '',
  description varchar(5120) NOT NULL default '',
  published smallint NOT NULL default '0',
  checked_out int NOT NULL default '0',
  checked_out_time datetime NOT NULL default '1900-01-01 00:00:00',
  access smallint NOT NULL default '0',
  params varchar(2048) NOT NULL default '',
  metadesc varchar(1024) NOT NULL,
  metakey varchar(1024) NOT NULL,
  metadata varchar(2048) NOT NULL,
  created_user_id int NOT NULL default '0',
  created_time datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  modified_user_id int NOT NULL default '0',
  modified_time datetime NOT NULL default '1900-01-01 00:00:00',
  hits int NOT NULL default '0',
  language char NOT NULL,
  PRIMARY KEY  (id)
) ;

 CREATE INDEX cat_idx ON jos_categories (extension,published,access)
  CREATE INDEX idx_access ON jos_categories (access)
  CREATE INDEX idx_checkout ON jos_categories (checked_out)
  CREATE INDEX idx_path ON jos_categories  (path)
  CREATE INDEX idx_left_right ON jos_categories (lft,rgt)
  CREATE INDEX idx_alias ON jos_categories (alias)
  CREATE INDEX idx_language ON jos_categories (language)

SET IDENTITY_INSERT jos_categories  ON 

INSERT INTO jos_categories (id, asset_id, parent_id, lft, rgt,level, path, extension, title, alias, note, description, published, checked_out, checked_out_time, access, params, metadesc, metakey, metadata, created_user_id,created_time, modified_user_id, modified_time, hits,language)
SELECT 1, 0, 0, 0, 11, 0, '', 'system', 'ROOT', 'root', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{}', '', '', '', 0, '2009-10-18 16:07:09', 0, '1900-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 2, 27, 1, 1, 2, 1, 'uncategorised', 'com_content', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:26:37', 0, '1900-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 3, 28, 1, 3, 4, 1, 'uncategorised', 'com_banners', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":"","foobar":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:27:35', 0, '1900-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 4, 29, 1, 5, 6, 1, 'uncategorised', 'com_contact', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:27:57', 0, '1900-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 5, 30, 1, 7, 8, 1, 'uncategorised', 'com_newsfeeds', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:28:15', 0, '1900-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 6, 31, 1, 9, 10, 1, 'uncategorised', 'com_weblinks', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1900-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:28:33', 0, '1900-01-01 00:00:00', 0, '*'

SET IDENTITY_INSERT jos_categories  OFF 

CREATE TABLE jos_contact_details (
  id integer NOT NULL identity(1,1),
  name varchar(255) NOT NULL default '',
  alias varchar(255) NOT NULL default '',
  con_position varchar(255) default NULL,
  address text,
  suburb varchar(100) default NULL,
  state varchar(100) default NULL,
  country varchar(100) default NULL,
  postcode varchar(100) default NULL,
  telephone varchar(255) default NULL,
  fax varchar(255) default NULL,
  misc text,
  image varchar(255) default NULL,
  imagepos varchar(20) default NULL,
  email_to varchar(255) default NULL,
  default_con smallint NOT NULL default '0',
  published smallint NOT NULL default '0',
  checked_out integer NOT NULL default '0',
  checked_out_time datetime NOT NULL default '1900-01-01 00:00:00',
  ordering integer NOT NULL default '0',
  params text NOT NULL,
  user_id integer NOT NULL default '0',
  catid integer NOT NULL default '0',
  access smallint NOT NULL default '0',
  mobile varchar(255) NOT NULL default '',
  webpage varchar(255) NOT NULL default '',
  sortname1 varchar(255) NOT NULL,
  sortname2 varchar(255) NOT NULL,
  sortname3 varchar(255) NOT NULL,
  language char(7) NOT NULL,
  created datetime NOT NULL default '1900-01-01 00:00:00',
  created_by int NOT NULL default '0',
  created_by_alias varchar(255) NOT NULL default '',
  modified datetime NOT NULL default '1900-01-01 00:00:00',
  modified_by int NOT NULL default '0',
  metakey text NOT NULL,
  metadesc text NOT NULL,
  metadata text NOT NULL,
  featured smallint NOT NULL default '0', 
  xreference varchar(50) NOT NULL,
  publish_up datetime NOT NULL default '1900-01-01 00:00:00',
  publish_down datetime NOT NULL default '1900-01-01 00:00:00',
  PRIMARY KEY  (id)
) ;

  CREATE INDEX idx_access ON jos_contact_details (access)
  CREATE INDEX idx_checkout ON jos_contact_details (checked_out)
  CREATE INDEX idx_state ON jos_contact_details (published)
  CREATE INDEX idx_catid ON jos_contact_details (catid)
  CREATE INDEX idx_createdby ON jos_contact_details (created_by)
  CREATE INDEX idx_featured_catid ON jos_contact_details (featured,catid)
  CREATE INDEX idx_language ON jos_contact_details (language)
  CREATE INDEX idx_xreference ON jos_contact_details (xreference)



CREATE TABLE jos_content (
  id integer  NOT NULL identity(1,1),
  asset_id INTEGER  NOT NULL DEFAULT 0,
  title varchar(255) NOT NULL default '',
  alias varchar(255) NOT NULL default '',
  title_alias varchar(255) NOT NULL default '',
  introtext text NOT NULL,
  fulltext text NOT NULL,
  state smallint NOT NULL default '0',
  sectionid integer  NOT NULL default '0',
  mask integer  NOT NULL default '0',
  catid integer  NOT NULL default '0',
  created datetime NOT NULL default '1900-01-01 00:00:00',
  created_by integer  NOT NULL default '0',
  created_by_alias varchar(255) NOT NULL default '',
  modified datetime NOT NULL default '1900-01-01 00:00:00',
  modified_by integer  NOT NULL default '0',
  checked_out integer  NOT NULL default '0',
  checked_out_time datetime NOT NULL default '1900-01-01 00:00:00',
  publish_up datetime NOT NULL default '1900-01-01 00:00:00',
  publish_down datetime NOT NULL default '1900-01-01 00:00:00',
  images text NOT NULL,
  urls text NOT NULL,
  attribs varchar(5120) NOT NULL,
  version integer  NOT NULL default '1',
  parentid integer  NOT NULL default '0',
  ordering integer NOT NULL default '0',
  metakey text NOT NULL,
  metadesc text NOT NULL,
  access integer  NOT NULL default '0',
  hits integer  NOT NULL default '0',
  metadata text NOT NULL,
  featured smallint  NOT NULL default '0',
  language char(7) NOT NULL,
  xreference varchar(50) NOT NULL,
  PRIMARY KEY  (id)
);

CREATE INDEX idx_access ON jos_content (access)
  CREATE INDEX idx_checkout ON jos_content (checked_out)
  CREATE INDEX idx_state ON jos_content (state)
  CREATE INDEX idx_catid ON jos_content (catid)
  CREATE INDEX idx_createdby ON jos_content (created_by)
  CREATE INDEX idx_featured_catid ON jos_content (featured,catid)
  CREATE INDEX idx_language ON jos_content (language)
  CREATE INDEX idx_xreference ON jos_content (xreference)


CREATE TABLE jos_content_frontpage (
  content_id integer NOT NULL default '0',
  ordering integer NOT NULL default '0',
  PRIMARY KEY  (content_id)
);


CREATE TABLE jos_content_rating (
  content_id integer NOT NULL default '0',
  rating_sum integer  NOT NULL default '0',
  rating_count integer  NOT NULL default '0',
  lastip varchar(50) NOT NULL default '',
  PRIMARY KEY  (content_id)
);



CREATE TABLE jos_core_log_searches (
  search_term varchar(128) NOT NULL default '',
  hits integer  NOT NULL default '0'
) ;



CREATE TABLE jos_extensions (
  extension_id INT  NOT NULL identity(1,1),
  name VARCHAR(100)  NOT NULL,
  type VARCHAR(20)  NOT NULL,
  element VARCHAR(100) NOT NULL,
  folder VARCHAR(100) NOT NULL,
  client_id smallint NOT NULL,
  enabled smallint NOT NULL DEFAULT '1',
  access smallint  NOT NULL DEFAULT '1',
  protected smallint NOT NULL DEFAULT '0',
  manifest_cache TEXT  NOT NULL,
  params TEXT NOT NULL,
  custom_data text NOT NULL,
  system_data text NOT NULL,
  checked_out int  NOT NULL default '0',
  checked_out_time datetime NOT NULL default '1900-01-01 00:00:00',
  ordering int default '0',
  state int default '0',
  PRIMARY KEY (extension_id)
) ;

CREATE INDEX element_clientid ON jos_extensions (element, client_id)
 CREATE INDEX element_folder_clientid ON jos_extensions (element, folder, client_id)
 CREATE INDEX extension ON jos_extensions (type,element,folder,client_id)

SET IDENTITY_INSERT jos_extensions  ON 


INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 100, 'PHPMailer', 'library', 'phpmailer', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 101, 'SimplePie', 'library', 'simplepie', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 102, 'Bitfolge', 'library', 'simplepie', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 103, 'phputf8', 'library', 'simplepie', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0



INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 200, 'mod_articles_archive', 'module', 'mod_articles_archive', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 201, 'mod_articles_latest', 'module', 'mod_articles_latest', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 202, 'mod_articles_popular', 'module', 'mod_articles_popular', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 203, 'mod_banners', 'module', 'mod_banners', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 204, 'mod_breadcrumbs', 'module', 'mod_breadcrumbs', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 205, 'mod_custom', 'module', 'mod_custom', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 206, 'mod_feed', 'module', 'mod_feed', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 207, 'mod_footer', 'module', 'mod_footer', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 208, 'mod_login', 'module', 'mod_login', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 209, 'mod_menu', 'module', 'mod_menu', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 210, 'mod_articles_news', 'module', 'mod_articles_news', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 211, 'mod_random_image', 'module', 'mod_random_image', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 212, 'mod_related_items', 'module', 'mod_related_items', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 213, 'mod_search', 'module', 'mod_search', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 214, 'mod_stats', 'module', 'mod_stats', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 215, 'mod_syndicate', 'module', 'mod_syndicate', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 216, 'mod_users_latest', 'module', 'mod_users_latest', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 217, 'mod_weblinks', 'module', 'mod_weblinks', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 218, 'mod_whosonline', 'module', 'mod_whosonline', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 219, 'mod_wrapper', 'module', 'mod_wrapper', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 220, 'mod_articles_category', 'module', 'mod_articles_category', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 221, 'mod_articles_categories', 'module', 'mod_articles_categories', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 222, 'mod_languages', 'module', 'mod_languages', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0



INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 300, 'mod_custom', 'module', 'mod_custom', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 301, 'mod_feed', 'module', 'mod_feed', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 302, 'mod_latest', 'module', 'mod_latest', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 303, 'mod_logged', 'module', 'mod_logged', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 304, 'mod_login', 'module', 'mod_login', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 305, 'mod_menu', 'module', 'mod_menu', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 306, 'mod_online', 'module', 'mod_online', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 307, 'mod_popular', 'module', 'mod_popular', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 308, 'mod_quickicon', 'module', 'mod_quickicon', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 309, 'mod_status', 'module', 'mod_status', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 310, 'mod_submenu', 'module', 'mod_submenu', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 311, 'mod_title', 'module', 'mod_title', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 312, 'mod_toolbar', 'module', 'mod_toolbar', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 313, 'mod_unread', 'module', 'mod_unread', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0




INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 400, 'plg_authentication_gmail', 'plugin', 'gmail', 'authentication', 0, 0, 1, 0, '', '{"applysuffix":"0","suffix":"","verifypeer":"1","user_blacklist":""}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 401, 'plg_authentication_joomla', 'plugin', 'joomla', 'authentication', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 402, 'plg_authentication_ldap', 'plugin', 'ldap', 'authentication', 0, 0, 1, 0, '', '{"host":"","port":"389","use_ldapV3":"0","negotiate_tls":"0","no_referrals":"0","auth_method":"bind","base_dn":"","search_string":"","users_dn":"","username":"admin","password":"bobby7","ldap_fullname":"fullName","ldap_email":"mail","ldap_uid":"uid"}', '', '', 0, '1900-01-01 00:00:00', 3, 0
UNION ALL
SELECT 403, 'plg_authentication_openid', 'plugin', 'openid', 'authentication', 0, 0, 1, 0, '', '{"usermode":"2","phishing-resistant":"0","multi-factor":"0","multi-factor-physical":"0"}', '', '', 0, '1900-01-01 00:00:00', 4, 0
UNION ALL
SELECT 404, 'plg_content_emailcloak', 'plugin', 'emailcloak', 'content', 0, 1, 1, 0, '', '{"mode":"1"}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 405, 'plg_content_geshi', 'plugin', 'geshi', 'content', 0, 1, 1, 0, '', '{}', '', '', 0, '1900-01-01 00:00:00', 2, 0
UNION ALL
SELECT 406, 'plg_content_loadmodule', 'plugin', 'loadmodule', 'content', 0, 1, 1, 0, '', '{"style":"table"}', '', '', 0, '1900-01-01 00:00:00', 3, 0
UNION ALL
SELECT 407, 'plg_content_pagebreak', 'plugin', 'pagebreak', 'content', 0, 1, 1, 1, '', '{"title":"1","multipage_toc":"1","showall":"1"}', '', '', 0, '1900-01-01 00:00:00', 4, 0
UNION ALL
SELECT 408, 'plg_content_pagenavigation', 'plugin', 'pagenavigation', 'content', 0, 1, 1, 1, '', '{"position":"1"}', '', '', 0, '1900-01-01 00:00:00', 5, 0
UNION ALL
SELECT 409, 'plg_content_vote', 'plugin', 'vote', 'content', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 6, 0
UNION ALL
SELECT 410, 'plg_editors_codemirror', 'plugin', 'codemirror', 'editors', 0, 1, 1, 1, '', '{"linenumbers":"0","tabmode":"indent"}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 411, 'plg_editors_none', 'plugin', 'none', 'editors', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 2, 0
UNION ALL
SELECT 412, 'plg_editors_tinymce', 'plugin', 'tinymce', 'editors', 0, 1, 1, 1, '', '{"mode":"1","skin":"0","compressed":"0","cleanup_startup":"0","cleanup_save":"2","entity_encoding":"raw","lang_mode":"0","lang_code":"en","text_direction":"ltr","content_css":"1","content_css_custom":"","relative_urls":"1","newlines":"0","invalid_elements":"script,applet,iframe","extended_elements":"","toolbar":"top","toolbar_align":"left","html_height":"550","html_width":"750","element_path":"1","fonts":"1","paste":"1","searchreplace":"1","insertdate":"1","format_date":"%Y-%m-%d","inserttime":"1","format_time":"%H:%M:%S","colors":"1","table":"1","smilies":"1","media":"1","hr":"1","directionality":"1","fullscreen":"1","style":"1","layer":"1","xhtmlxtras":"1","visualchars":"1","nonbreaking":"1","template":"1","blockquote":"1","wordcount":"1","advimage":"1","advlink":"1","autosave":"1","contextmenu":"1","inlinepopups":"1","safari":"0","custom_plugin":"","custom_button":""}', '', '', 0, '1900-01-01 00:00:00', 3, 0
UNION ALL
SELECT 413, 'plg_editors-xtd_article', 'plugin', 'article', 'editors-xtd', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 414, 'plg_editors-xtd_image', 'plugin', 'image', 'editors-xtd', 0, 1, 1, 0, '', '{}', '', '', 0, '1900-01-01 00:00:00', 2, 0
UNION ALL
SELECT 415, 'plg_editors-xtd_pagebreak', 'plugin', 'pagebreak', 'editors-xtd', 0, 1, 1, 0, '', '{}', '', '', 0, '1900-01-01 00:00:00', 3, 0
UNION ALL
SELECT 416, 'plg_editors-xtd_readmore', 'plugin', 'readmore', 'editors-xtd', 0, 1, 1, 0, '', '{}', '', '', 0, '1900-01-01 00:00:00', 4, 0
UNION ALL
SELECT 417, 'plg_search_categories', 'plugin', 'categories', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 418, 'plg_search_contacts', 'plugin', 'contacts', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 419, 'plg_search_content', 'plugin', 'content', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 420, 'plg_search_newsfeeds', 'plugin', 'newsfeeds', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 421, 'plg_search_weblinks', 'plugin', 'weblinks', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 422, 'plg_system_cache', 'plugin', 'cache', 'system', 0, 0, 1, 1, '', '{"browsercache":"0","cachetime":"15"}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 423, 'plg_system_debug', 'plugin', 'debug', 'system', 0, 1, 1, 0, '', '{"profile":"1","queries":"1","memory":"1","language_files":"1","language_strings":"1","strip-first":"1","strip-prefix":"","strip-suffix":""}', '', '', 0, '1900-01-01 00:00:00', 2, 0
UNION ALL
SELECT 424, 'plg_system_log', 'plugin', 'log', 'system', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 3, 0
UNION ALL
SELECT 425, 'plg_system_redirect', 'plugin', 'redirect', 'system', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 4, 0
UNION ALL
SELECT 426, 'plg_system_remember', 'plugin', 'remember', 'system', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 5, 0
UNION ALL
SELECT 427, 'plg_system_sef', 'plugin', 'sef', 'system', 0, 1, 1, 0, '', '{}', '', '', 0, '1900-01-01 00:00:00', 6, 0
UNION ALL
SELECT 428, 'plg_user_contactcreator', 'plugin', 'contactcreator', 'user', 0, 0, 1, 1, '', '{"autowebpage":"","category":"26","autopublish":"0"}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 429, 'plg_user_joomla', 'plugin', 'joomla', 'user', 0, 1, 1, 0, '', '{"autoregister":"1"}', '', '', 0, '1900-01-01 00:00:00', 2, 0
UNION ALL
SELECT 430, 'plg_user_profile', 'plugin', 'profile', 'user', 0, 0, 1, 1, '', '{"register-require_address1":"0","register-require_address2":"0","register-require_city":"0","register-require_region":"0","register-require_country":"0","register-require_postal_code":"0","register-require_phone":"0","register-require_website":"0","profile-require_address1":"1","profile-require_address2":"1","profile-require_city":"1","profile-require_region":"1","profile-require_country":"1","profile-require_postal_code":"1","profile-require_phone":"1","profile-require_website":"1"}', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 431, 'plg_extension_joomla', 'plugin', 'joomla', 'extension', 0, 1, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 1, 0
UNION ALL
SELECT 432, 'plg_system_languagefilter', 'plugin', 'languagefilter', 'system', 0, 0, 1, 1, '', '{}', '', '', 0, '1900-01-01 00:00:00', 0, 0





INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 500, 'atomic', 'template', 'atomic', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 501, 'rhuk_milkyway', 'template', 'rhuk_milkyway', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 502, 'bluestork', 'template', 'bluestork', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 503, 'beez_20', 'template', 'beez_20', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 504, 'hathor', 'template', 'hathor', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 505, 'Beez5', 'template', 'beez5', '', 0, 1, 1, 0, 'a:11:{s:6:"legacy";b:1;s:4:"name";s:5:"Beez5";s:4:"type";s:8:"template";s:12:"creationDate";s:11:"21 May 2010";s:6:"author";s:12:"Angie Radtke";s:9:"copyright";s:72:"Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.";s:11:"authorEmail";s:23:"a.radtke@derauftritt.de";s:9:"authorUrl";s:26:"http://www.der-auftritt.de";s:7:"version";s:5:"1.6.0";s:11:"description";s:22:"A Easy Version of Beez";s:5:"group";s:0:"";}', '{"wrapperSmall":"53","wrapperLarge":"72","sitetitle":"BEEZ 2.0","sitedescription":"Your site name","navposition":"center","html5":"0"}', '', '', 0, '1900-01-01 00:00:00', 0, 0


INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 600, 'English (United Kingdom)', 'language', 'en-GB', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 601, 'English (United Kingdom)', 'language', 'en-GB', '', 1, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 604, 'XXTestLang', 'language', 'xx-XX', '', 1, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0
UNION ALL
SELECT 605, 'XXTestLang', 'language', 'xx-XX', '', 0, 1, 1, 0, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0

INSERT INTO jos_extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 700, 'Joomla! CMS', 'file', 'joomla', '', 0, 1, 1, 1, '', '', '', '', 0, '1900-01-01 00:00:00', 0, 0

SET IDENTITY_INSERT jos_extensions  OFF 

CREATE TABLE jos_languages (
  lang_id int  NOT NULL identity(1,1),
  lang_code char(7) NOT NULL,
  title varchar(50) NOT NULL,
  title_native varchar(50) NOT NULL,
  sef varchar(50) NOT NULL,
  image varchar(50) NOT NULL,
  description varchar(512) NOT NULL,
  metakey text NOT NULL,
  metadesc text NOT NULL,
  published int NOT NULL default '0',
  PRIMARY KEY  (lang_id)
);

CREATE UNIQUE INDEX idx_sef ON jos_languages (sef)

SET IDENTITY_INSERT jos_languages  ON 

INSERT INTO jos_languages (lang_id,lang_code,title,title_native,sef,image,description,metakey,metadesc,published)
SELECT 1, 'en-GB', 'English (UK)', 'English (UK)', 'en', 'en', '', '', '', 1
UNION ALL
SELECT 3, 'xx-XX', 'xx (Test)', 'xx (Test)', 'xx', 'br', '', '', '', 1

SET IDENTITY_INSERT jos_languages  OFF 

CREATE TABLE jos_menu (
  id integer NOT NULL identity(1,1),
  menutype varchar(24) NOT NULL,
  title varchar(255) NOT NULL,
  alias varchar(255) NOT NULL,
  note varchar(255) NOT NULL default '',
  path varchar(900) NOT NULL,
  link varchar(1024) NOT NULL,
  type varchar(16) NOT NULL,
  published smallint NOT NULL default '0',
  parent_id integer  NOT NULL default '1',
  level integer  NOT NULL default '0',
  component_id integer  NOT NULL default '0',
  ordering integer NOT NULL default '0',
  checked_out integer  NOT NULL default '0',
  checked_out_time datetime NOT NULL default '1900-01-01 00:00:00',
  browserNav smallint NOT NULL default '0',
  access smallint  NOT NULL default '0',
  img varchar(255) NOT NULL,
  template_style_id integer  NOT NULL default '0',
  params varchar(8000) NOT NULL,
  lft integer NOT NULL default '0',
  rgt integer NOT NULL default '0',
  home smallint  NOT NULL default '0',
  language char(7) NOT NULL DEFAULT '',
  PRIMARY KEY  (id)
);

 CREATE INDEX idx_componentid ON jos_menu (component_id,menutype,published,access)
 CREATE INDEX  idx_menutype ON jos_menu (menutype)
 CREATE INDEX  idx_left_right ON jos_menu (lft,rgt)
 CREATE INDEX  idx_alias ON jos_menu (alias)
 CREATE INDEX idx_path ON jos_menu (path)  
 CREATE UNIQUE INDEX idx_alias_parent_id ON jos_menu (alias, parent_id) 
 CREATE INDEX idx_language ON jos_menu (language)  


SET IDENTITY_INSERT jos_menu  ON 

INSERT INTO jos_menu (id, menutype, title, alias, note, path, link,type, published,parent_id, level, component_id,ordering, checked_out, checked_out_time, browserNav,
  access, img, template_style_id, params, lft, rgt, home, language)
SELECT 1,'','Menu_Item_Root','root','','','','',1,0,0,0,0,0,'1900-01-01 00:00:00',0,0,'',0,'',0,217,0,'*'
UNION ALL
SELECT 2,'_adminmenu','com_banners','Banners','','Banners','index.php?option=com_banners','component',0,1,1,4,0,0,'1900-01-01 00:00:00',0,0,'class:banners',0,'',1,10,0,'*'
UNION ALL
SELECT 3,'_adminmenu','com_banners','Banners','','Banners/Banners','index.php?option=com_banners','component',0,2,2,4,0,0,'1900-01-01 00:00:00',0,0,'class:banners',0,'',2,3,0,'*'
UNION ALL
SELECT 4,'_adminmenu','com_banners_clients','Clients','','Banners/Clients','index.php?option=com_banners&view=clients','component',0,2,2,4,0,0,'1900-01-01 00:00:00',0,0,'class:banners-clients',0,'',4,5,0,'*'
UNION ALL
SELECT 5,'_adminmenu','com_banners_tracks','Tracks','','Banners/Tracks','index.php?option=com_banners&view=tracks','component',0,2,2,4,0,0,'1900-01-01 00:00:00',0,0,'class:banners-tracks',0,'',6,7,0,'*'
UNION ALL
SELECT 6,'_adminmenu','com_banners_categories','Categories','','Banners/Categories','index.php?option=com_categories&extension=com_banners','component',0,2,2,6,0,0,'1900-01-01 00:00:00',0,0,'class:banners-cat',0,'',8,9,0,'*'
UNION ALL
SELECT 7,'_adminmenu','com_contact','Contacts','','Contacts','index.php?option=com_contact','component',0,1,1,8,0,0,'1900-01-01 00:00:00',0,0,'class:contact',0,'',11,16,0,'*'
UNION ALL
SELECT 8,'_adminmenu','com_contact','Contacts','','Contacts/Contacts','index.php?option=com_contact','component',0,7,2,8,0,0,'1900-01-01 00:00:00',0,0,'class:contact',0,'',12,13,0,'*'
UNION ALL
SELECT 9,'_adminmenu','com_contact_categories','Categories','','Contacts/Categories','index.php?option=com_categories&extension=com_contact','component',0,7,2,6,0,0,'1900-01-01 00:00:00',0,0,'class:contact-cat',0,'',14,15,0,'*'
UNION ALL
SELECT 10,'_adminmenu','com_messages','Messaging','','Messaging','index.php?option=com_messages','component',0,1,1,15,0,0,'1900-01-01 00:00:00',0,0,'class:messages',0,'',17,22,0,'*'
UNION ALL
SELECT 11,'_adminmenu','com_messages_add','New Private Message','','Messaging/New Private Message','index.php?option=com_messages&task=message.add','component',0,10,2,15,0,0,'1900-01-01 00:00:00',0,0,'class:messages-add',0,'',18,19,0,'*'
UNION ALL
SELECT 12,'_adminmenu','com_messages_read','Read Private Message','','Messaging/Read Private Message','index.php?option=com_messages','component',0,10,2,15,0,0,'1900-01-01 00:00:00',0,0,'class:messages-read',0,'',20,21,0,'*'
UNION ALL
SELECT 13,'_adminmenu','com_newsfeeds','News Feeds','','News Feeds','index.php?option=com_newsfeeds','component',0,1,1,17,0,0,'1900-01-01 00:00:00',0,0,'class:newsfeeds',0,'',23,28,0,'*'
UNION ALL
SELECT 14,'_adminmenu','com_newsfeeds_feeds','Feeds','','News Feeds/Feeds','index.php?option=com_newsfeeds','component',0,13,2,17,0,0,'1900-01-01 00:00:00',0,0,'class:newsfeeds',0,'',24,25,0,'*'
UNION ALL
SELECT 15,'_adminmenu','com_newsfeeds_categories','Categories','','News Feeds/Categories','index.php?option=com_categories&extension=com_newsfeeds','component',0,13,2,6,0,0,'1900-01-01 00:00:00',0,0,'class:newsfeeds-cat',0,'',26,27,0,'*'
UNION ALL
SELECT 16,'_adminmenu','com_redirect','Redirect','','Redirect','index.php?option=com_redirect','component',0,1,1,24,0,0,'1900-01-01 00:00:00',0,0,'class:redirect',0,'',37,38,0,'*'
UNION ALL
SELECT 17,'_adminmenu','com_search','Search','','Search','index.php?option=com_search','component',0,1,1,19,0,0,'1900-01-01 00:00:00',0,0,'class:search',0,'',29,30,0,'*'
UNION ALL
SELECT 18,'_adminmenu','com_weblinks','Weblinks','','Weblinks','index.php?option=com_weblinks','component',0,1,1,21,0,0,'1900-01-01 00:00:00',0,0,'class:weblinks',0,'',31,36,0,'*'
UNION ALL
SELECT 19,'_adminmenu','com_weblinks_links','Links','','Weblinks/Links','index.php?option=com_weblinks','component',0,18,2,21,0,0,'1900-01-01 00:00:00',0,0,'class:weblinks',0,'',32,33,0,'*'
UNION ALL
SELECT 20,'_adminmenu','com_weblinks_categories','Categories','','Weblinks/Categories','index.php?option=com_categories&extension=com_weblinks','component',0,18,2,6,0,0,'1900-01-01 00:00:00',0,0,'class:weblinks-cat',0,'',34,35,0,'*'
UNION ALL
SELECT 101, 'mainmenu', 'Home', 'home', '', 'home', 'index.php?option=com_content&view=featured', 'component', 1, 1, 1, 22, 0, 0, '1900-01-01 00:00:00', 0, 1, '', 0, '{"num_leading_articles":"1","num_intro_articles":"3","num_columns":"3","num_links":"0","orderby_pri":"","orderby_sec":"front","order_date":"","multi_column_order":"1","show_pagination":"2","show_pagination_results":"1","show_noauth":"","article-allow_ratings":"","article-allow_comments":"","show_feed_link":"1","feed_summary":"","show_title":"","link_titles":"","show_intro":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_readmore":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_hits":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","show_page_heading":1,"page_title":"","page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 231, 232, 1,'*'

SET IDENTITY_INSERT jos_menu  OFF

CREATE TABLE jos_menu_types (
  id integer  NOT NULL identity(1,1),
  menutype varchar(24) NOT NULL,
  title varchar(48) NOT NULL,
  description varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
);

CREATE UNIQUE Index idx_menutype on jos_menu_types (menutype)



CREATE TABLE jos_messages (
  message_id integer  NOT NULL identity(1,1),
  user_id_from integer  NOT NULL default '0',
  user_id_to integer  NOT NULL default '0',
  folder_id smallint  NOT NULL DEFAULT '0',
  date_time datetime NOT NULL default '1900-01-01 00:00:00',
  state smallint NOT NULL DEFAULT '0',
  priority smallint  NOT NULL DEFAULT '0',
  subject varchar(255) NOT NULL DEFAULT '',
  message text NOT NULL,
  PRIMARY KEY  (message_id)
);

CREATE Index useridto_state on jos_messages (user_id_to, state)



CREATE TABLE jos_messages_cfg (
  user_id integer  NOT NULL default '0',
  cfg_name varchar(100) NOT NULL default '',
  cfg_value varchar(255) NOT NULL default ''
);

CREATE  UNIQUE Index  idx_user_var_name on jos_messages_cfg (user_id,cfg_name)



CREATE TABLE jos_modules (
  id int NOT NULL identity(1,1),
  title varchar(100) NOT NULL DEFAULT '',
  note varchar(255) NOT NULL default '',
  content text NOT NULL,
  ordering int NOT NULL DEFAULT '0',
  position varchar(50) DEFAULT NULL,
  checked_out int  NOT NULL DEFAULT '0',
  checked_out_time datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  publish_up datetime NOT NULL default '1900-01-01 00:00:00',
  publish_down datetime NOT NULL default '1900-01-01 00:00:00',
  published smallint NOT NULL DEFAULT '0',
  module varchar(50) DEFAULT NULL,
  access smallint  NOT NULL DEFAULT '0',
  showtitle smallint  NOT NULL DEFAULT '1',
  params varchar(5120) NOT NULL DEFAULT '',
  client_id smallint NOT NULL DEFAULT '0',
  language char(7) NOT NULL,
  PRIMARY KEY  (id)
);

CREATE  Index  published on jos_modules (published,access)
CREATE  Index  newsfeeds on jos_modules (module,published)
CREATE  Index  idx_language on jos_modules (language)


SET IDENTITY_INSERT jos_modules  ON 

INSERT INTO jos_modules (id, title, note, content, ordering, position, checked_out,checked_out_time, publish_up, publish_down, published, module, access, showtitle, params,
  client_id, language)
SELECT 1, 'Main Menu', '', '', 1, 'position-7', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_menu', 1, 1, '{"menutype":"mainmenu","startLevel":"0","endLevel":"0","showAllChildren":"0","tag_id":"","class_sfx":"","window_open":"","layout":"","moduleclass_sfx":"_menu","cache":"1","cache_time":"900","cachemode":"itemid"}', 0, '*'
UNION ALL
SELECT 2, 'Login', '', '', 1, 'login', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_login', 1, 1, '', 1, '*'
UNION ALL
SELECT 3, 'Popular Articles', '', '', 3, 'cpanel', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_popular', 3, 1, '{"count":"5","catid":"","user_id":"0","layout":"","moduleclass_sfx":"","cache":"0"}', 1, '*'
UNION ALL
SELECT 4, 'Recently Added Articles', '', '', 4, 'cpanel', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_latest', 3, 1, '{"count":"5","ordering":"c_dsc","catid":"","user_id":"0","layout":"","moduleclass_sfx":"","cache":"0"}', 1, '*'
UNION ALL
SELECT 6, 'Unread Messages', '', '', 1, 'header', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_unread', 3, 1, '', 1, '*'
UNION ALL
SELECT 7, 'Online Users', '', '', 2, 'header', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_online', 3, 1, '', 1, '*'
UNION ALL
SELECT 8, 'Toolbar', '', '', 1, 'toolbar', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_toolbar', 3, 1, '', 1, '*'
UNION ALL
SELECT 9, 'Quick Icons', '', '', 1, 'icon', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_quickicon', 3, 1, '', 1, '*'
UNION ALL
SELECT 10, 'Logged-in Users', '', '', 2, 'cpanel', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_logged', 3, 1, '', 1, '*'
UNION ALL
SELECT 12, 'Admin Menu', '', '', 1, 'menu', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_menu', 3, 1, '{"layout":"","moduleclass_sfx":"","shownew":"1","showhelp":"1","cache":"0"}', 1, '*'
UNION ALL
SELECT 13, 'Admin Submenu', '', '', 1, 'submenu', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_submenu', 3, 1, '', 1, '*'
UNION ALL
SELECT 14, 'User Status', '', '', 1, 'status', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_status', 3, 1, '', 1, '*'
UNION ALL
SELECT 15, 'Title', '', '', 1, 'title', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_title', 3, 1, '', 1, '*'
UNION ALL
SELECT 16, 'Login Form', '', '', 7, 'position-7', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_login', 1, 1, '{"greeting":"1","name":"0"}', 0, '*'
UNION ALL
SELECT 17, 'Breadcrumbs', '', '', 1, 'position-2', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 1, 'mod_breadcrumbs', 1, 1, '{"moduleclass_sfx":"","showHome":"1","homeText":"Home","showComponent":"1","separator":"","cache":"1","cache_time":"900","cachemode":"itemid"}', 0, '*'
UNION ALL
SELECT 18, 'Banners', '', '', 1, 'position-5', 0, '1900-01-01 00:00:00', '1900-01-01 00:00:00', '1900-01-01 00:00:00', 0, 'mod_banners', 1, 1, '{"target":"1","count":"1","cid":"1","catid":["27"],"tag_search":"0","ordering":"0","header_text":"","footer_text":"","layout":"","moduleclass_sfx":"","cache":"1","cache_time":"900"}', 0, '*'

SET IDENTITY_INSERT jos_modules  OFF


CREATE TABLE jos_modules_menu (
  moduleid integer NOT NULL default '0',
  menuid integer NOT NULL default '0',
  PRIMARY KEY  (moduleid,menuid)
);


INSERT INTO jos_modules_menu (moduleid,menuid)
SELECT 1,0
UNION ALL
SELECT 2,0
UNION ALL
SELECT 3,0
UNION ALL
SELECT 4,0
UNION ALL
SELECT 6,0
UNION ALL
SELECT 7,0
UNION ALL
SELECT 8,0
UNION ALL
SELECT 9,0
UNION ALL
SELECT 10,0
UNION ALL
SELECT 12,0
UNION ALL
SELECT 13,0
UNION ALL
SELECT 14,0
UNION ALL
SELECT 15,0
UNION ALL
SELECT 16,0
UNION ALL
SELECT 17,0
UNION ALL
SELECT 18,0



CREATE TABLE jos_newsfeeds (
  catid integer NOT NULL default '0',
  id integer  NOT NULL identity(1,1),
  name  varchar(100) NOT NULL DEFAULT '',
  alias varchar(100) NOT NULL default '',
  link varchar(200) NOT NULL DEFAULT '',
  filename varchar(200) default NULL,
  published smallint NOT NULL default '0',
  numarticles integer  NOT NULL default '1',
  cache_time integer  NOT NULL default '3600',
  checked_out integer  NOT NULL default '0',
  checked_out_time datetime NOT NULL default '1900-01-01 00:00:00',
  ordering integer NOT NULL default '0',
  rtl smallint NOT NULL default '0',
  access smallint  NOT NULL DEFAULT '0',
  language char(7) NOT NULL DEFAULT '',
  params text NOT NULL,
  created datetime NOT NULL default '1900-01-01 00:00:00',
  created_by int  NOT NULL default '0',
  created_by_alias varchar(255) NOT NULL default '',
  modified datetime NOT NULL default '1900-01-01 00:00:00',
  modified_by int  NOT NULL default '0',
  metakey text NOT NULL,
  metadesc text NOT NULL,
  metadata text NOT NULL,
  xreference varchar(50) NOT NULL,
  publish_up datetime NOT NULL default '1900-01-01 00:00:00',
  publish_down datetime NOT NULL default '1900-01-01 00:00:00',

  PRIMARY KEY  (id)
);

 CREATE INDEX idx_access on jos_newsfeeds (access)
 CREATE INDEX idx_checkout on jos_newsfeeds (checked_out)
 CREATE INDEX idx_state on jos_newsfeeds (published)
 CREATE INDEX idx_catid on jos_newsfeeds(catid)
  CREATE INDEX idx_createdby on jos_newsfeeds(created_by)
  CREATE INDEX idx_language on jos_newsfeeds(language)
  CREATE INDEX idx_xreference on jos_newsfeeds(xreference)



CREATE TABLE jos_redirect_links (
  id integer  NOT NULL identity(1,1),
  old_url varchar(150) NOT NULL,
  new_url varchar(150) NOT NULL,
  referer varchar(150) NOT NULL,
  comment varchar(255) NOT NULL,
  published smallint NOT NULL,
  created_date datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  modified_date datetime NOT NULL DEFAULT '1900-01-01 00:00:00',
  PRIMARY KEY  (id)
);

 CREATE UNIQUE INDEX idx_link_old on jos_redirect_links(old_url)
 CREATE UNIQUE INDEX idx_link_modifed on jos_redirect_links(modified_date)


CREATE TABLE jos_schemas (
  extension_id int NOT NULL,
  version_id varchar(20) NOT NULL,
  PRIMARY KEY (extension_id, version_id)
);



CREATE TABLE jos_session (
  session_id varchar(32) NOT NULL default '',
  client_id smallint  NOT NULL default '0',
  guest smallint  default '1',
  time varchar(14) default '',
  data varchar(8000) default NULL,
  userid int default '0',
  username varchar(150) default '',
  usertype varchar(50) default '',
  PRIMARY KEY  (session_id)
);

CREATE INDEX whosonline on jos_session(guest,usertype)
CREATE INDEX userid on jos_session(userid)
CREATE INDEX time on jos_session(time)



CREATE TABLE  jos_updates (
  update_id int NOT NULL identity(1,1),
  update_site_id int default '0',
  extension_id int default '0',
  categoryid int default '0',
  name varchar(100) default '',
  description text NOT NULL,
  element varchar(100) default '',
  type varchar(20) default '',
  folder varchar(20) default '',
  client_id smallint default '0',
  version varchar(10) default '',
  data text NOT NULL,
  detailsurl text NOT NULL,
  PRIMARY KEY  (update_id)
);



CREATE TABLE  jos_update_sites (
  update_site_id int NOT NULL identity(1,1),
  name varchar(100) default '',
  type varchar(20) default '',
  location text NOT NULL,
  enabled int default '0',
  PRIMARY KEY  (update_site_id)
);

SET IDENTITY_INSERT jos_update_sites  ON 

INSERT INTO jos_update_sites (update_site_id, name, type, location, enabled)
SELECT 1, 'Joomla Core', 'collection', 'http://update.joomla.org/core/list.xml', 1
UNION ALL
SELECT 2, 'Joomla Extension Directory', 'collection', 'http://update.joomla.org/jed/list.xml', 1

SET IDENTITY_INSERT jos_update_sites  OFF 

CREATE TABLE jos_update_sites_extensions (
  update_site_id INT DEFAULT 0,
  extension_id INT DEFAULT 0,
  PRIMARY KEY(update_site_id, extension_id)
);

 

CREATE TABLE  jos_update_categories (
  categoryid int NOT NULL identity(1,1),
  name varchar(20) default '',
  description text NOT NULL,
  parent int default '0',
  updatesite int default '0',
  PRIMARY KEY  (categoryid)
) ;



CREATE TABLE jos_template_styles (
  id integer  NOT NULL identity(1,1),
  template varchar(50) NOT NULL DEFAULT '',
  client_id smallint  NOT NULL DEFAULT 0,
  home smallint  NOT NULL DEFAULT 0,
  title varchar(255) NOT NULL DEFAULT '',
  params varchar(2048) NOT NULL DEFAULT '',
  PRIMARY KEY  (id)
) ;

CREATE INDEX idx_template on jos_template_styles (template)
  CREATE INDEX  idx_home on jos_template_styles(home)


CREATE TABLE jos_user_usergroup_map (
  user_id integer  NOT NULL default '0',
  group_id integer  NOT NULL default '0',
  PRIMARY KEY  (user_id,group_id)
);



CREATE TABLE jos_usergroups (
  id integer  NOT NULL identity(1,1),
  parent_id integer  NOT NULL default '0',
  lft integer NOT NULL default '0',
  rgt integer NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  PRIMARY KEY  (id)
);

 CREATE UNIQUE INDEX   idx_usergroup_title_lookup on jos_usergroups(title)
 CREATE INDEX  idx_usergroup_adjacency_lookup on jos_usergroups (parent_id)
 CREATE INDEX  idx_usergroup_nested_set_lookup on jos_usergroups (lft,rgt) -- using BTREE not allowed


SET IDENTITY_INSERT jos_usergroups  ON 
INSERT INTO jos_usergroups (id ,parent_id ,lft ,rgt ,title)
SELECT 1, 0, 1, 20, 'Public'
UNION ALL
SELECT 2, 1, 6, 17, 'Registered'
UNION ALL
SELECT 3, 2, 7, 14, 'Author'
UNION ALL
SELECT 4, 3, 8, 11, 'Editor'
UNION ALL
SELECT 5, 4, 9, 10, 'Publisher'
UNION ALL
SELECT 6, 1, 2, 5, 'Manager'
UNION ALL
SELECT 7, 6, 3, 4, 'Administrator'
UNION ALL
SELECT 8, 1, 18, 19, 'Super Users'

SET IDENTITY_INSERT jos_usergroups  OFF

CREATE TABLE jos_users (
  id integer NOT NULL identity(1,1),
  name varchar(255) NOT NULL default '',
  username varchar(150) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  password varchar(100) NOT NULL default '',
  usertype varchar(25) NOT NULL default '',
  block smallint NOT NULL default '0',
  sendEmail smallint default '0',
  registerDate datetime NOT NULL default '1900-01-01 00:00:00',
  lastvisitDate datetime NOT NULL default '1900-01-01 00:00:00',
  activation varchar(100) NOT NULL default '',
  params text NOT NULL,
  PRIMARY KEY  (id)
);

 CREATE INDEX usertype on jos_users (usertype)
 CREATE INDEX idx_name on jos_users (name)
 CREATE INDEX idx_block on jos_users (block)
 CREATE INDEX username on jos_users (username)
 CREATE INDEX email on jos_users (email)


CREATE TABLE jos_user_profiles (
  user_id int NOT NULL,
  profile_key varchar(100) NOT NULL,
  profile_value varchar(255) NOT NULL,
  ordering int NOT NULL default '0'
) ;

CREATE UNIQUE INDEX idx_user_id_profile_key on jos_user_profiles(user_id,profile_key)


CREATE TABLE jos_weblinks (
  id integer  NOT NULL identity(1,1),
  catid integer NOT NULL default '0',
  sid integer NOT NULL default '0',
  title varchar(250) NOT NULL default '',
  alias varchar(255) NOT NULL default '',
  url varchar(250) NOT NULL default '',
  description TEXT NOT NULL,
  date datetime NOT NULL default '1900-01-01 00:00:00',
  hits integer NOT NULL default '0',
  state smallint NOT NULL default '0',
  checked_out integer NOT NULL default '0',
  checked_out_time datetime NOT NULL default '1900-01-01 00:00:00',
  ordering integer NOT NULL default '0',
  archived smallint NOT NULL default '0',
  approved smallint NOT NULL default '1',
  access integer NOT NULL default '1',
  params text NOT NULL,
  language char(7) NOT NULL DEFAULT '',
  created datetime NOT NULL default '1900-01-01 00:00:00',
  created_by int  NOT NULL default '0',
  created_by_alias varchar(255) NOT NULL default '',
  modified datetime NOT NULL default '1900-01-01 00:00:00',
  modified_by int  NOT NULL default '0',
  metakey text NOT NULL,
  metadesc text NOT NULL,
  metadata text NOT NULL,
  featured smallint  NOT NULL default '0',
  xreference varchar(50) NOT NULL,
  publish_up datetime NOT NULL default '1900-01-01 00:00:00',
  publish_down datetime NOT NULL default '1900-01-01 00:00:00',

  PRIMARY KEY  (id)
);

  CREATE INDEX idx_access on jos_weblinks (access)
  CREATE INDEX idx_checkout on jos_weblinks(checked_out)
  CREATE INDEX idx_state on jos_weblinks(state)
  CREATE INDEX idx_catid on jos_weblinks(catid)
  CREATE INDEX idx_createdby on jos_weblinks(created_by)
  CREATE INDEX idx_featured_catid on jos_weblinks(featured,catid)
  CREATE INDEX idx_language on jos_weblinks(language)
  CREATE INDEX idx_xreference on jos_weblinks(xreference)



CREATE TABLE jos_viewlevels (
  id int  NOT NULL identity(1,1),
  title varchar(100) NOT NULL DEFAULT '',
  ordering int NOT NULL DEFAULT '0',
  rules varchar(5120) NOT NULL,
  PRIMARY KEY (id)
);

 CREATE UNIQUE INDEX idx_assetgroup_title_lookup ON jos_viewlevels (title)

SET IDENTITY_INSERT jos_viewlevels  ON 
INSERT INTO jos_viewlevels (id, title, ordering, rules) 
SELECT 1, 'Public', 0, '[]'
UNION ALL
SELECT 2, 'Registered', 1, '[6,2]'
UNION ALL
SELECT 3, 'Special', 2, '[6,7,8]'

SET IDENTITY_INSERT jos_viewlevels  OFF
