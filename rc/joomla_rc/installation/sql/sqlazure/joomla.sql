
INSERT INTO #__viewlevels (id, title, ordering, rules) 
SELECT 1, 'Public', 0, '[1]'
UNION ALL
SELECT 2, 'Registered', 1, '[6,2,8]'
UNION ALL
SELECT 3, 'Special', 2, '[6,3,8]';

SET IDENTITY_INSERT #__viewlevels  OFF;
INSERT INTO #__usergroups (id ,parent_id ,lft ,rgt ,title)
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
SELECT 8, 1, 18, 19, 'Super Users';

SET IDENTITY_INSERT #__usergroups  OFF;
SELECT 1, 700
UNION ALL
SELECT 2, 700;
INSERT INTO #__template_styles (id, template, client_id, home, title, params) VALUES (1, 'rhuk_milkyway', '0', '0', 'Milkyway - Default', '{"colorVariation":"blue","backgroundVariation":"blue","widthStyle":"fmax"}');
INSERT INTO #__template_styles (id, template, client_id, home, title, params) VALUES (2, 'bluestork', '1', '1', 'Bluestork - Default', '{"useRoundedCorners":"1","showSiteName":"0"}');
INSERT INTO #__template_styles (id, template, client_id, home, title, params) VALUES (3, 'atomic', '0', '0', 'Atomic - Default', '{}');
INSERT INTO #__template_styles (id, template, client_id, home, title, params) VALUES (4, 'beez_20', 0, 1, 'Beez2 - Default', '{"wrapperSmall":"53","wrapperLarge":"72","logo":"images\\/joomla_black.gif","sitetitle":"Joomla!","sitedescription":"Open Source Content Management Beta","navposition":"left","templatecolor":"personal","html5":"0"}');
INSERT INTO #__template_styles (id, template, client_id, home, title, params) VALUES (5, 'hathor', '1', '0', 'Hathor - Default', '{"showSiteName":"0","colourChoice":"","boldText":"0"}');
INSERT INTO #__template_styles (id, template, client_id, home, title, params) VALUES (6, 'beez5', 0, 0, 'Beez5 - Default-Fruit Shop', '{"wrapperSmall":"53","wrapperLarge":"72","logo":"images\\/sampledata\\/fruitshop\\/fruits.gif","sitetitle":"Matuna Market ","sitedescription":"Fruit Shop Sample Site","navposition":"left","html5":"0"}');
SET IDENTITY_INSERT #__template_styles  OFF;
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
SELECT 18,0;
INSERT INTO #__modules (id, title, note, content, ordering, position, checked_out,checked_out_time, publish_up, publish_down, published, module, access, showtitle, params,
  client_id, language)
SELECT 1, 'Main Menu', '', '', 1, 'position-7', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_menu', 1, 1, '{"menutype":"mainmenu","startLevel":"0","endLevel":"0","showAllChildren":"0","tag_id":"","class_sfx":"","window_open":"","layout":"","moduleclass_sfx":"_menu","cache":"1","cache_time":"900","cachemode":"itemid"}', 0, '*'
UNION ALL
SELECT 2, 'Login', '', '', 1, 'login', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_login', 1, 1, '', 1, '*'
UNION ALL
SELECT 3, 'Popular Articles', '', '', 3, 'cpanel', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_popular', 3, 1, '{"count":"5","catid":"","user_id":"0","layout":"_:default","moduleclass_sfx":"","cache":"0","automatic_title":"1"}', 1, '*'
UNION ALL
SELECT 4, 'Recently Added Articles', '', '', 4, 'cpanel', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_latest', 3, 1, '{"count":"5","ordering":"c_dsc","catid":"","user_id":"0","layout":"_:default","moduleclass_sfx":"","cache":"0","automatic_title":"1"}', 1, '*'
UNION ALL
SELECT 6, 'Unread Messages', '', '', 1, 'header', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_unread', 3, 1, '', 1, '*'
UNION ALL
SELECT 7, 'Online Users', '', '', 2, 'header', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_online', 3, 1, '', 1, '*'
UNION ALL
SELECT 8, 'Toolbar', '', '', 1, 'toolbar', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_toolbar', 3, 1, '', 1, '*'
UNION ALL
SELECT 9, 'Quick Icons', '', '', 1, 'icon', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_quickicon', 3, 1, '', 1, '*'
UNION ALL
SELECT 10, 'Logged-in Users', '', '', 2, 'cpanel', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_logged', 3, 1, '{"count":"5","name":"1","layout":"_:default","moduleclass_sfx":"","cache":"0","automatic_title":"1"}', 1, '*'
UNION ALL
SELECT 12, 'Admin Menu', '', '', 1, 'menu', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_menu', 3, 1, '{"layout":"","moduleclass_sfx":"","shownew":"1","showhelp":"1","cache":"0"}', 1, '*'
UNION ALL
SELECT 13, 'Admin Submenu', '', '', 1, 'submenu', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_submenu', 3, 1, '', 1, '*'
UNION ALL
SELECT 14, 'User Status', '', '', 1, 'status', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_status', 3, 1, '', 1, '*'
UNION ALL
SELECT 15, 'Title', '', '', 1, 'title', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_title', 3, 1, '', 1, '*'
UNION ALL
SELECT 16, 'Login Form', '', '', 7, 'position-7', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_login', 1, 1, '{"greeting":"1","name":"0"}', 0, '*'
UNION ALL
SELECT 17, 'Breadcrumbs', '', '', 1, 'position-2', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 1, 'mod_breadcrumbs', 1, 1, '{"moduleclass_sfx":"","showHome":"1","homeText":"Home","showComponent":"1","separator":"","cache":"1","cache_time":"900","cachemode":"itemid"}', 0, '*'
UNION ALL
SELECT 18, 'Banners', '', '', 1, 'position-5', 0, '1999-01-01 00:00:00', '1999-01-01 00:00:00', '1999-01-01 00:00:00', 0, 'mod_banners', 1, 1, '{"target":"1","count":"1","cid":"1","catid":["27"],"tag_search":"0","ordering":"0","header_text":"","footer_text":"","layout":"","moduleclass_sfx":"","cache":"1","cache_time":"900"}', 0, '*';

SET IDENTITY_INSERT #__modules  OFF;

INSERT INTO #__menu (id, menutype, title, alias, note, path, link,type, published,parent_id, level, component_id,ordering, checked_out, checked_out_time, browserNav,
  access, img, template_style_id, params, lft, rgt, home, language, client_id)
SELECT 1,'','Menu_Item_Root','root','','','','',1,0,0,0,0,0,'1999-01-01 00:00:00',0,0,'',0,'',0,41,0,'*',0
UNION ALL
SELECT 2,'menu','com_banners','Banners','','Banners','index.php?option=com_banners','component',0,1,1,4,0,0,'1999-01-01 00:00:00',0,0,'class:banners',0,'',1,10,0,'*', 1
UNION ALL
SELECT 3,'menu','com_banners','Banners','','Banners/Banners','index.php?option=com_banners','component',0,2,2,4,0,0,'1999-01-01 00:00:00',0,0,'class:banners',0,'',2,3,0,'*', 1
UNION ALL
SELECT 4,'menu','com_banners_clients','Clients','','Banners/Clients','index.php?option=com_banners&view=clients','component',0,2,2,4,0,0,'1999-01-01 00:00:00',0,0,'class:banners-clients',0,'',4,5,0,'*', 1
UNION ALL
SELECT 5,'menu','com_banners_tracks','Tracks','','Banners/Tracks','index.php?option=com_banners&view=tracks','component',0,2,2,4,0,0,'1999-01-01 00:00:00',0,0,'class:banners-tracks',0,'',6,7,0,'*', 1
UNION ALL
SELECT 6,'menu','com_banners_categories','Categories','','Banners/Categories','index.php?option=com_categories&extension=com_banners','component',0,2,2,6,0,0,'1999-01-01 00:00:00',0,0,'class:banners-cat',0,'',8,9,0,'*', 1
UNION ALL
SELECT 7,'menu','com_contact','Contacts','','Contacts','index.php?option=com_contact','component',0,1,1,8,0,0,'1999-01-01 00:00:00',0,0,'class:contact',0,'',11,16,0,'*', 1
UNION ALL
SELECT 8,'menu','com_contact','Contacts','','Contacts/Contacts','index.php?option=com_contact','component',0,7,2,8,0,0,'1999-01-01 00:00:00',0,0,'class:contact',0,'',12,13,0,'*', 1
UNION ALL
SELECT 9,'menu','com_contact_categories','Categories','','Contacts/Categories','index.php?option=com_categories&extension=com_contact','component',0,7,2,6,0,0,'1999-01-01 00:00:00',0,0,'class:contact-cat',0,'',14,15,0,'*', 1
UNION ALL
SELECT 10,'menu','com_messages','Messaging','','Messaging','index.php?option=com_messages','component',0,1,1,15,0,0,'1999-01-01 00:00:00',0,0,'class:messages',0,'',17,22,0,'*', 1
UNION ALL
SELECT 11,'menu','com_messages_add','New Private Message','','Messaging/New Private Message','index.php?option=com_messages&task=message.add','component',0,10,2,15,0,0,'1999-01-01 00:00:00',0,0,'class:messages-add',0,'',18,19,0,'*', 1
UNION ALL
SELECT 12,'menu','com_messages_read','Read Private Message','','Messaging/Read Private Message','index.php?option=com_messages','component',0,10,2,15,0,0,'1999-01-01 00:00:00',0,0,'class:messages-read',0,'',20,21,0,'*', 1
UNION ALL
SELECT 13,'menu','com_newsfeeds','News Feeds','','News Feeds','index.php?option=com_newsfeeds','component',0,1,1,17,0,0,'1999-01-01 00:00:00',0,0,'class:newsfeeds',0,'',23,28,0,'*', 1
UNION ALL
SELECT 14,'menu','com_newsfeeds_feeds','Feeds','','News Feeds/Feeds','index.php?option=com_newsfeeds','component',0,13,2,17,0,0,'1999-01-01 00:00:00',0,0,'class:newsfeeds',0,'',24,25,0,'*', 1
UNION ALL
SELECT 15,'menu','com_newsfeeds_categories','Categories','','News Feeds/Categories','index.php?option=com_categories&extension=com_newsfeeds','component',0,13,2,6,0,0,'1999-01-01 00:00:00',0,0,'class:newsfeeds-cat',0,'',26,27,0,'*', 1
UNION ALL
SELECT 16,'menu','com_redirect','Redirect','','Redirect','index.php?option=com_redirect','component',0,1,1,24,0,0,'1999-01-01 00:00:00',0,0,'class:redirect',0,'',37,38,0,'*', 1
UNION ALL
SELECT 17,'menu','com_search','Search','','Search','index.php?option=com_search','component',0,1,1,19,0,0,'1999-01-01 00:00:00',0,0,'class:search',0,'',29,30,0,'*', 1
UNION ALL
SELECT 18,'menu','com_weblinks','Weblinks','','Weblinks','index.php?option=com_weblinks','component',0,1,1,21,0,0,'1999-01-01 00:00:00',0,0,'class:weblinks',0,'',31,36,0,'*', 1
UNION ALL
SELECT 19,'menu','com_weblinks_links','Links','','Weblinks/Links','index.php?option=com_weblinks','component',0,18,2,21,0,0,'1999-01-01 00:00:00',0,0,'class:weblinks',0,'',32,33,0,'*', 1
UNION ALL
SELECT 20,'menu','com_weblinks_categories','Categories','','Weblinks/Categories','index.php?option=com_categories&extension=com_weblinks','component',0,18,2,6,0,0,'1999-01-01 00:00:00',0,0,'class:weblinks-cat',0,'',34,35,0,'*', 1
UNION ALL
SELECT 101, 'mainmenu', 'Home', 'home', '', 'home', 'index.php?option=com_content&view=featured', 'component', 1, 1, 1, 22, 0, 0, '1999-01-01 00:00:00', 0, 1, '', 0, '{"num_leading_articles":"1","num_intro_articles":"3","num_columns":"3","num_links":"0","orderby_pri":"","orderby_sec":"front","order_date":"","multi_column_order":"1","show_pagination":"2","show_pagination_results":"1","show_noauth":"","article-allow_ratings":"","article-allow_comments":"","show_feed_link":"1","feed_summary":"","show_title":"","link_titles":"","show_intro":"","show_category":"","link_category":"","show_parent_category":"","link_parent_category":"","show_author":"","show_create_date":"","show_modify_date":"","show_publish_date":"","show_item_navigation":"","show_readmore":"","show_icons":"","show_print_icon":"","show_email_icon":"","show_hits":"","menu-anchor_title":"","menu-anchor_css":"","menu_image":"","show_page_heading":1,"page_title":"","page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}', 39, 40, 1,'*', 0;

SET IDENTITY_INSERT #__menu  OFF;

INSERT INTO #__languages (lang_id,lang_code,title,title_native,sef,image,description,metakey,metadesc,published)
SELECT 1, 'en-GB', 'English (UK)', 'English (UK)', 'en', 'en', '', '', '', 1
UNION ALL
SELECT 3, 'xx-XX', 'xx (Test)', 'xx (Test)', 'xx', 'br', '', '', '', 1;

SET IDENTITY_INSERT #__languages  OFF;
INSERT INTO #__extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 1, 'com_mailto', 'component', 'com_mailto', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 2, 'com_wrapper', 'component', 'com_wrapper', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 3, 'com_admin', 'component', 'com_admin', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 4, 'com_banners', 'component', 'com_banners', '', 1, 1, 1, 0, '', '{"purchase_type":"3","track_impressions":"0","track_clicks":"0","metakey_prefix":""}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 5, 'com_cache', 'component', 'com_cache', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 6, 'com_categories', 'component', 'com_categories', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 7, 'com_checkin', 'component', 'com_checkin', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 8, 'com_contact', 'component', 'com_contact', '', 1, 1, 1, 0, '', '{"show_contact_category":"hide","show_contact_list":"0","presentation_style":"sliders","show_name":"1","show_position":"1","show_email":"0","show_street_address":"1","show_suburb":"1","show_state":"1","show_postcode":"1","show_country":"1","show_telephone":"1","show_mobile":"1","show_fax":"1","show_webpage":"1","show_misc":"1","show_image":"1","image":"","allow_vcard":"0","show_articles":"0","show_profile":"0","show_links":"0","linka_name":"","linkb_name":"","linkc_name":"","linkd_name":"","linke_name":"","contact_icons":"0","icon_address":"","icon_email":"","icon_telephone":"","icon_mobile":"","icon_fax":"","icon_misc":"","show_headings":"1","show_position_headings":"1","show_email_headings":"0","show_telephone_headings":"1","show_mobile_headings":"0","show_fax_headings":"0","allow_vcard_headings":"0","show_suburb_headings":"1","show_state_headings":"1","show_country_headings":"1","show_email_form":"1","show_email_copy":"1","banned_email":"","banned_subject":"","banned_text":"","validate_session":"1","custom_reply":"0","redirect":"","show_category_crumb":"0","metakey":"","metadesc":"","robots":"","author":"","rights":"","xreference":""}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 9, 'com_cpanel', 'component', 'com_cpanel', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 10, 'com_installer', 'component', 'com_installer', '', 1, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 11, 'com_languages', 'component', 'com_languages', '', 1, 1, 1, 1, '', '{"administrator":"en-GB","site":"en-GB"}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 12, 'com_login', 'component', 'com_login', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 13, 'com_media', 'component', 'com_media', '', 1, 1, 0, 1, '', '{"upload_extensions":"bmp,csv,doc,gif,ico,jpg,jpeg,odg,odp,ods,odt,pdf,png,ppt,swf,txt,xcf,xls,BMP,CSV,DOC,GIF,ICO,JPG,JPEG,ODG,ODP,ODS,ODT,PDF,PNG,PPT,SWF,TXT,XCF,XLS","upload_maxsize":"10","file_path":"images","image_path":"images","restrict_uploads":"1","allowed_media_usergroup":"3","check_mime":"1","image_extensions":"bmp,gif,jpg,png","ignore_extensions":"","upload_mime":"image\\/jpeg,image\\/gif,image\\/png,image\\/bmp,application\\/x-shockwave-flash,application\\/msword,application\\/excel,application\\/pdf,application\\/powerpoint,text\\/plain,application\\/x-zip","upload_mime_illegal":"text\\/html","enable_flash":"0"}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 14, 'com_menus', 'component', 'com_menus', '', 1, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 15, 'com_messages', 'component', 'com_messages', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 16, 'com_modules', 'component', 'com_modules', '', 1, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 17, 'com_newsfeeds', 'component', 'com_newsfeeds', '', 1, 1, 1, 0, '', '{"show_feed_image":"1","show_feed_description":"1","show_item_description":"1","feed_word_count":"0","show_headings":"1","show_name":"1","show_articles":"0","show_link":"1","show_description":"1","show_description_image":"1","display_num":"","show_pagination_limit":"1","show_pagination":"1","show_pagination_results":"1","show_cat_items":"1"}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 18, 'com_plugins', 'component', 'com_plugins', '', 1, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 19, 'com_search', 'component', 'com_search', '', 1, 1, 1, 1, '', '{"enabled":"0","show_date":"1"}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 20, 'com_templates', 'component', 'com_templates', '', 1, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 21, 'com_weblinks', 'component', 'com_weblinks', '', 1, 1, 1, 0, '', '{"show_comp_description":"1","comp_description":"","show_link_hits":"1","show_link_description":"1","show_other_cats":"0","show_headings":"0","show_numbers":"0","show_report":"1","count_clicks":"1","target":"0","link_icons":""}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 22, 'com_content', 'component', 'com_content', '', 1, 1, 0, 1, '', '{"article_layout":"_:default","show_title":"1","link_titles":"1","show_intro":"1","show_category":"1","link_category":"1","show_parent_category":"0","link_parent_category":"0","show_author":"1","link_author":"0","show_create_date":"0","show_modify_date":"0","show_publish_date":"1","show_item_navigation":"1","show_vote":"0","show_readmore":"1","show_readmore_title":"1","readmore_limit":"100","show_icons":"1","show_print_icon":"1","show_email_icon":"1","show_hits":"1","show_noauth":"0","category_layout":"_:blog","show_category_title":"0","show_description":"0","show_description_image":"0","maxLevel":"1","show_empty_categories":"0","show_no_articles":"1","show_subcat_desc":"1","show_cat_num_articles":"0","show_base_description":"1","maxLevelcat":"-1","show_empty_categories_cat":"0","show_subcat_desc_cat":"1","show_cat_num_articles_cat":"1","num_leading_articles":"1","num_intro_articles":"4","num_columns":"2","num_links":"4","multi_column_order":"0","orderby_pri":"order","orderby_sec":"rdate","order_date":"published","show_pagination_limit":"1","filter_field":"hide","show_headings":"1","list_show_date":"0","date_format":"","list_show_hits":"1","list_show_author":"1","show_pagination":"2","show_pagination_results":"1","show_feed_link":"1","feed_summary":"0","filters":{"1":{"filter_type":"BL","filter_tags":"","filter_attributes":""},"6":{"filter_type":"BL","filter_tags":"","filter_attributes":""},"7":{"filter_type":"BL","filter_tags":"","filter_attributes":""},"2":{"filter_type":"BL","filter_tags":"","filter_attributes":""},"3":{"filter_type":"BL","filter_tags":"","filter_attributes":""},"4":{"filter_type":"BL","filter_tags":"","filter_attributes":""},"5":{"filter_type":"BL","filter_tags":"","filter_attributes":""},"10":{"filter_type":"BL","filter_tags":"","filter_attributes":""},"12":{"filter_type":"BL","filter_tags":"","filter_attributes":""},"8":{"filter_type":"BL","filter_tags":"","filter_attributes":""}}}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 23, 'com_config', 'component', 'com_config', '', 1, 1, 0, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 24, 'com_redirect', 'component', 'com_redirect', '', 1, 1, 0, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 25, 'com_users', 'component', 'com_users', '', 1, 1, 0, 1, '', '{"allowUserRegistration":"1","new_usertype":"2","useractivation":"1","frontend_userparams":"1","mailSubjectPrefix":"","mailBodySuffix":""}', '', '', 0, '1999-01-01 00:00:00', 0, 0;

INSERT INTO #__extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 100, 'PHPMailer', 'library', 'phpmailer', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 101, 'SimplePie', 'library', 'simplepie', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 102, 'Bitfolge', 'library', 'simplepie', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 103, 'phputf8', 'library', 'simplepie', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0;



INSERT INTO #__extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 200, 'mod_articles_archive', 'module', 'mod_articles_archive', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 201, 'mod_articles_latest', 'module', 'mod_articles_latest', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 202, 'mod_articles_popular', 'module', 'mod_articles_popular', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 203, 'mod_banners', 'module', 'mod_banners', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 204, 'mod_breadcrumbs', 'module', 'mod_breadcrumbs', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 205, 'mod_custom', 'module', 'mod_custom', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 206, 'mod_feed', 'module', 'mod_feed', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 207, 'mod_footer', 'module', 'mod_footer', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 208, 'mod_login', 'module', 'mod_login', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 209, 'mod_menu', 'module', 'mod_menu', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 210, 'mod_articles_news', 'module', 'mod_articles_news', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 211, 'mod_random_image', 'module', 'mod_random_image', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 212, 'mod_related_items', 'module', 'mod_related_items', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 213, 'mod_search', 'module', 'mod_search', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 214, 'mod_stats', 'module', 'mod_stats', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 215, 'mod_syndicate', 'module', 'mod_syndicate', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 216, 'mod_users_latest', 'module', 'mod_users_latest', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 217, 'mod_weblinks', 'module', 'mod_weblinks', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 218, 'mod_whosonline', 'module', 'mod_whosonline', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 219, 'mod_wrapper', 'module', 'mod_wrapper', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 220, 'mod_articles_category', 'module', 'mod_articles_category', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 221, 'mod_articles_categories', 'module', 'mod_articles_categories', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 222, 'mod_languages', 'module', 'mod_languages', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0;



INSERT INTO #__extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 300, 'mod_custom', 'module', 'mod_custom', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 301, 'mod_feed', 'module', 'mod_feed', '', 1, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 302, 'mod_latest', 'module', 'mod_latest', '', 1, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 303, 'mod_logged', 'module', 'mod_logged', '', 1, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 304, 'mod_login', 'module', 'mod_login', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 305, 'mod_menu', 'module', 'mod_menu', '', 1, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 306, 'mod_online', 'module', 'mod_online', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 307, 'mod_popular', 'module', 'mod_popular', '', 1, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 308, 'mod_quickicon', 'module', 'mod_quickicon', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 309, 'mod_status', 'module', 'mod_status', '', 1, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 310, 'mod_submenu', 'module', 'mod_submenu', '', 1, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 311, 'mod_title', 'module', 'mod_title', '', 1, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 312, 'mod_toolbar', 'module', 'mod_toolbar', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 313, 'mod_unread', 'module', 'mod_unread', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0;




INSERT INTO #__extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 400, 'plg_authentication_gmail', 'plugin', 'gmail', 'authentication', 0, 0, 1, 0, '', '{"applysuffix":"0","suffix":"","verifypeer":"1","user_blacklist":""}', '', '', 0, '1999-01-01 00:00:00', 1, 0
UNION ALL
SELECT 401, 'plg_authentication_joomla', 'plugin', 'joomla', 'authentication', 0, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 402, 'plg_authentication_ldap', 'plugin', 'ldap', 'authentication', 0, 0, 1, 0, '', '{"host":"","port":"389","use_ldapV3":"0","negotiate_tls":"0","no_referrals":"0","auth_method":"bind","base_dn":"","search_string":"","users_dn":"","username":"admin","password":"bobby7","ldap_fullname":"fullName","ldap_email":"mail","ldap_uid":"uid"}', '', '', 0, '1999-01-01 00:00:00', 3, 0
UNION ALL
SELECT 403, 'plg_authentication_openid', 'plugin', 'openid', 'authentication', 0, 0, 1, 0, '', '{"usermode":"2","phishing-resistant":"0","multi-factor":"0","multi-factor-physical":"0"}', '', '', 0, '1999-01-01 00:00:00', 4, 0
UNION ALL
SELECT 404, 'plg_content_emailcloak', 'plugin', 'emailcloak', 'content', 0, 1, 1, 0, '', '{"mode":"1"}', '', '', 0, '1999-01-01 00:00:00', 1, 0
UNION ALL
SELECT 405, 'plg_content_geshi', 'plugin', 'geshi', 'content', 0, 1, 1, 0, '', '{}', '', '', 0, '1999-01-01 00:00:00', 2, 0
UNION ALL
SELECT 406, 'plg_content_loadmodule', 'plugin', 'loadmodule', 'content', 0, 1, 1, 0, '', '{"style":"table"}', '', '', 0, '1999-01-01 00:00:00', 3, 0
UNION ALL
SELECT 407, 'plg_content_pagebreak', 'plugin', 'pagebreak', 'content', 0, 1, 1, 1, '', '{"title":"1","multipage_toc":"1","showall":"1"}', '', '', 0, '1999-01-01 00:00:00', 4, 0
UNION ALL
SELECT 408, 'plg_content_pagenavigation', 'plugin', 'pagenavigation', 'content', 0, 1, 1, 1, '', '{"position":"1"}', '', '', 0, '1999-01-01 00:00:00', 5, 0
UNION ALL
SELECT 409, 'plg_content_vote', 'plugin', 'vote', 'content', 0, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 6, 0
UNION ALL
SELECT 410, 'plg_editors_codemirror', 'plugin', 'codemirror', 'editors', 0, 1, 1, 1, '', '{"linenumbers":"0","tabmode":"indent"}', '', '', 0, '1999-01-01 00:00:00', 1, 0
UNION ALL
SELECT 411, 'plg_editors_none', 'plugin', 'none', 'editors', 0, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 2, 0
UNION ALL
SELECT 412, 'plg_editors_tinymce', 'plugin', 'tinymce', 'editors', 0, 1, 1, 1, '', '{"mode":"1","skin":"0","compressed":"0","cleanup_startup":"0","cleanup_save":"2","entity_encoding":"raw","lang_mode":"0","lang_code":"en","text_direction":"ltr","content_css":"1","content_css_custom":"","relative_urls":"1","newlines":"0","invalid_elements":"script,applet,iframe","extended_elements":"","toolbar":"top","toolbar_align":"left","html_height":"550","html_width":"750","element_path":"1","fonts":"1","paste":"1","searchreplace":"1","insertdate":"1","format_date":"%Y-%m-%d","inserttime":"1","format_time":"%H:%M:%S","colors":"1","table":"1","smilies":"1","media":"1","hr":"1","directionality":"1","fullscreen":"1","style":"1","layer":"1","xhtmlxtras":"1","visualchars":"1","nonbreaking":"1","template":"1","blockquote":"1","wordcount":"1","advimage":"1","advlink":"1","autosave":"1","contextmenu":"1","inlinepopups":"1","safari":"0","custom_plugin":"","custom_button":""}', '', '', 0, '1999-01-01 00:00:00', 3, 0
UNION ALL
SELECT 413, 'plg_editors-xtd_article', 'plugin', 'article', 'editors-xtd', 0, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 1, 0
UNION ALL
SELECT 414, 'plg_editors-xtd_image', 'plugin', 'image', 'editors-xtd', 0, 1, 1, 0, '', '{}', '', '', 0, '1999-01-01 00:00:00', 2, 0
UNION ALL
SELECT 415, 'plg_editors-xtd_pagebreak', 'plugin', 'pagebreak', 'editors-xtd', 0, 1, 1, 0, '', '{}', '', '', 0, '1999-01-01 00:00:00', 3, 0
UNION ALL
SELECT 416, 'plg_editors-xtd_readmore', 'plugin', 'readmore', 'editors-xtd', 0, 1, 1, 0, '', '{}', '', '', 0, '1999-01-01 00:00:00', 4, 0
UNION ALL
SELECT 417, 'plg_search_categories', 'plugin', 'categories', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 418, 'plg_search_contacts', 'plugin', 'contacts', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 419, 'plg_search_content', 'plugin', 'content', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 420, 'plg_search_newsfeeds', 'plugin', 'newsfeeds', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 421, 'plg_search_weblinks', 'plugin', 'weblinks', 'search', 0, 1, 1, 0, '', '{"search_limit":"50","search_content":"1","search_archived":"1"}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 422, 'plg_system_languagefilter', 'plugin', 'languagefilter', 'system', 0, 0, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 1, 0
UNION ALL
SELECT 425, 'plg_system_debug', 'plugin', 'debug', 'system', 0, 1, 1, 0, '', '{"profile":"1","queries":"1","memory":"1","language_files":"1","language_strings":"1","strip-first":"1","strip-prefix":"","strip-suffix":""}', '', '', 0, '1999-01-01 00:00:00', 4, 0
UNION ALL
SELECT 426, 'plg_system_log', 'plugin', 'log', 'system', 0, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 5, 0
UNION ALL
SELECT 427, 'plg_system_redirect', 'plugin', 'redirect', 'system', 0, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 6, 0
UNION ALL
SELECT 428, 'plg_system_remember', 'plugin', 'remember', 'system', 0, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 7, 0
UNION ALL
SELECT 429, 'plg_system_sef', 'plugin', 'sef', 'system', 0, 1, 1, 0, '', '{}', '', '', 0, '1999-01-01 00:00:00', 8, 0
UNION ALL
SELECT 430, 'plg_system_logout', 'plugin', 'logout', 'system', 0, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 9, 0
UNION ALL
SELECT 432, 'plg_user_joomla', 'plugin', 'joomla', 'user', 0, 1, 1, 0, '', '{"autoregister":"1"}', '', '', 0, '1999-01-01 00:00:00', 2, 0
UNION ALL
SELECT 433, 'plg_user_profile', 'plugin', 'profile', 'user', 0, 0, 1, 1, '', '{"register-require_address1":"1","register-require_address2":"1","register-require_city":"1","register-require_region":"1","register-require_country":"1","register-require_postal_code":"1","register-require_phone":"1","register-require_website":"1","register-require_favoritebook":"1","register-require_aboutme":"1","register-require_tos":"1","register-require_dob":"1","profile-require_address1":"1","profile-require_address2":"1","profile-require_city":"1","profile-require_region":"1","profile-require_country":"1","profile-require_postal_code":"1","profile-require_phone":"1","profile-require_website":"1","profile-require_favoritebook":"1","profile-require_aboutme":"1","profile-require_tos":"1","profile-require_dob":"1"}', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 434, 'plg_extension_joomla', 'plugin', 'joomla', 'extension', 0, 1, 1, 1, '', '{}', '', '', 0, '1999-01-01 00:00:00', 1, 0
UNION ALL
SELECT 435, 'plg_content_joomla', 'plugin', 'joomla', 'content', 0, 1, 1, 0, '', '{}', '', '', 0, '1999-01-01 00:00:00', 0, 0;





INSERT INTO #__extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 500, 'atomic', 'template', 'atomic', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 501, 'rhuk_milkyway', 'template', 'rhuk_milkyway', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 502, 'bluestork', 'template', 'bluestork', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 503, 'beez_20', 'template', 'beez_20', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 504, 'hathor', 'template', 'hathor', '', 1, 1, 1, 0, 'a:11:{s:6:"legacy";b:1;s:4:"name";s:6:"Hathor";s:4:"type";s:8:"template";s:12:"creationDate";s:10:"March 2010";s:6:"author";s:11:"Andrea Tarr";s:9:"copyright";s:72:"Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.";s:11:"authorEmail";s:25:"hathor@tarrconsulting.com";s:9:"authorUrl";s:29:"http://www.tarrconsulting.com";s:7:"version";s:5:"1.6.0";s:11:"description";s:33:"Accessible Administrator Template";s:5:"group";s:0:"";}', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 505, 'Beez5', 'template', 'beez5', '', 0, 1, 1, 0, 'a:11:{s:6:"legacy";b:1;s:4:"name";s:5:"Beez5";s:4:"type";s:8:"template";s:12:"creationDate";s:11:"21 May 2010";s:6:"author";s:12:"Angie Radtke";s:9:"copyright";s:72:"Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.";s:11:"authorEmail";s:23:"a.radtke@derauftritt.de";s:9:"authorUrl";s:26:"http://www.der-auftritt.de";s:7:"version";s:5:"1.6.0";s:11:"description";s:22:"A Easy Version of Beez";s:5:"group";s:0:"";}', '{"wrapperSmall":"53","wrapperLarge":"72","sitetitle":"BEEZ 2.0","sitedescription":"Your site name","navposition":"center","html5":"0"}', '', '', 0, '1999-01-01 00:00:00', 0, 0;


INSERT INTO #__extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 600, 'English (United Kingdom)', 'language', 'en-GB', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 601, 'English (United Kingdom)', 'language', 'en-GB', '', 1, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 604, 'XXTestLang', 'language', 'xx-XX', '', 1, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0
UNION ALL
SELECT 605, 'XXTestLang', 'language', 'xx-XX', '', 0, 1, 1, 0, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0;

INSERT INTO #__extensions (extension_id, name, type, element, folder, client_id, enabled, access, protected, manifest_cache, params, custom_data, system_data, checked_out, checked_out_time, ordering, state) 
SELECT 700, 'Joomla! CMS', 'file', 'joomla', '', 0, 1, 1, 1, '', '', '', '', 0, '1999-01-01 00:00:00', 0, 0;

SET IDENTITY_INSERT #__extensions  OFF;

INSERT INTO #__categories (id, asset_id, parent_id, lft, rgt,level, path, extension, title, alias, note, description, published, checked_out, checked_out_time, access, params, metadesc, metakey, metadata, created_user_id,created_time, modified_user_id, modified_time, hits,language)
SELECT 1, 0, 0, 0, 11, 0, '', 'system', 'ROOT', 'root', '', '', 1, 0, '1999-01-01 00:00:00', 1, '{}', '', '', '', 0, '2009-10-18 16:07:09', 0, '1999-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 2, 27, 1, 1, 2, 1, 'uncategorised', 'com_content', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1999-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:26:37', 0, '1999-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 3, 28, 1, 3, 4, 1, 'uncategorised', 'com_banners', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1999-01-01 00:00:00', 1, '{"target":"","image":"","foobar":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:27:35', 0, '1999-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 4, 29, 1, 5, 6, 1, 'uncategorised', 'com_contact', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1999-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:27:57', 0, '1999-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 5, 30, 1, 7, 8, 1, 'uncategorised', 'com_newsfeeds', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1999-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:28:15', 0, '1999-01-01 00:00:00', 0, '*'
UNION ALL
SELECT 6, 31, 1, 9, 10, 1, 'uncategorised', 'com_weblinks', 'Uncategorised', 'uncategorised', '', '', 1, 0, '1999-01-01 00:00:00', 1, '{"target":"","image":""}', '', '', '{"page_title":"","author":"","robots":""}', 42, '2010-06-28 13:28:33', 0, '1999-01-01 00:00:00', 0, '*';

SET IDENTITY_INSERT #__categories  OFF;

INSERT INTO #__assets (id, parent_id, lft, rgt, level, name, title, rules)
SELECT 1,0,1,414,0,'root.1','Root Asset','{"core.login.site":{"6":1,"2":1},"core.login.admin":{"6":1},"core.admin":{"8":1},"core.manage":{"7":1},"core.create":{"6":1,"3":1},"core.delete":{"6":1},"core.edit":{"6":1,"4":1},"core.edit.state":{"6":1,"5":1},"core.edit.own":{"6":1,"3":1}}'
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
SELECT 24,1,53,54,1,'com_users','com_users','{"core.admin":{"7":1},"core.manage":[],"core.create":[],"core.delete":[],"core.edit":[],"core.edit.own":{"6":1},"core.edit.state":[]}'
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
SELECT 31, 25, 56, 57, 2, 'com_weblinks.category.6', 'Uncategorised', '{"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}';

SET IDENTITY_INSERT #__assets  OFF;


