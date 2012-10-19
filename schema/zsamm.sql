--
-- Tables to be added to the commonly used schema.
--

CREATE TABLE /*$wgDBprefix*/swsite (
  -- The site's ID.
  sws_id              int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  sws_name            varchar(255) binary         NOT NULL,
  sws_short_name      varchar(255) binary         NOT NULL,
  sws_site_uri        varchar(255) binary         NOT NULL,
  sws_user_uri        varchar(255) binary         NOT NULL,
  sws_work_uri        varchar(255) binary         NOT NULL
)/*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/swsite_name ON /*$wgDBprefix*/swsite (sws_name);
CREATE UNIQUE INDEX /*i*/swsite_short_name ON /*$wgDBprefix*/swsite (sws_short_name);
CREATE UNIQUE INDEX /*i*/swsite_site_uri ON /*$wgDBprefix*/swsite (sws_site_uri);
CREATE UNIQUE INDEX /*i*/swsite_user_uri ON /*$wgDBprefix*/swsite (sws_user_uri);
CREATE UNIQUE INDEX /*i*/swsite_work_uri ON /*$wgDBprefix*/swsite (sws_work_uri);

-- Insert a dummy site.
--INSERT INTO swsite (sws_name, sws_short_name, sws_site_uri, sws_user_uri, sws_work_uri) VALUES( 'dummySite', 'dummySite', 'dummy://DummySite', 'dummy://DummySite/User:', 'dummy://DummySite/Work:');


CREATE TABLE /*$wgDBprefix*/swauthor (
  -- The author's ID.
  swa_id            	int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,
  swa_site          	INTEGER    	NOT NULL,
  swa_user_name     	varchar(255) binary        	,
  swa_real_name     	varchar(255) binary        	,
-- Concatenated with swsite.sws_user_uri, should identify the work unambingously.
  swa_uri_part       	varchar(255) binary        	NOT NULL
)/*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/swauthor_namesite_unique ON /*$wgDBprefix*/swauthor (swa_site,swa_user_name);

-- Insert a dummy author.
-- INSERT INTO swauthor (swa_uri_part, swa_user_name, swa_real_name, swa_site) VALUES( 'DummyAuthor', 'DummyAuthor', 'DummyAuthor', 0);


--
-- Table structure for table srcwork
--
-- All RDF information about a source work is stored here, one source work per row. 
-- A source work is identified either by its ID or by the about-date pair. 
--

CREATE TABLE /*$wgDBprefix*/srcwork (

  -- A unique ID for each source work.
  srcwork_id      int unsigned NOT NULL PRIMARY KEY AUTO_INCREMENT,

  -- The site's id.
  srcwork_site    INTEGER   	NOT NULL,

  -- The creator's id.
  srcwork_creator INTEGER   	NOT NULL,

  -- Extracted from RDF. The version data of the work, stored as MW timestamp.
  srcwork_date    binary(14) NOT NULL,

  -- Usefull or not? Intended to be some short form of about usable for listings.
  srcwork_title   varchar(255) binary         NOT NULL	DEFAULT '',

  -- Concatenated with swsite.sws_work_uri, should identify the work unambingously.
  srcwork_uri_part     varchar(255) binary       	NOT NULL

	-- UNIQUE (srcwork_site, srcwork_uri_part, srcwork_date)
)/*$wgDBTableOptions*/;
-- Identifies the work if id not known.
CREATE UNIQUE INDEX /*i*/srcwork_uridate_unique ON /*$wgDBprefix*/srcwork (srcwork_site, srcwork_uri_part, srcwork_date);

-- Insert a dummy source work.
-- INSERT INTO srcwork (srcwork_id, srcwork_uri, srcwork_date, srcwork_creator) VALUES( 0, 'dummy://DummySrcwork', to_timestamp( 0), 0);
-- INSERT INTO srcwork (srcwork_uri_part, srcwork_date, srcwork_creator, srcwork_site) VALUES( 'DummySrcwork', '00000000000000', 0, 0);


CREATE TABLE /*$wgDBprefix*/swauthor_links (

  -- The source work's ID as used in 'srcwork.srcwork_id'.
  swal_srcworkid  	INTEGER   	NOT NULL,

  -- The source work's author's ID as used in 'swauthor.swa_id'.
  swal_authorid			INTEGER   	NOT NULL

	-- UNIQUE (swal_srcworkid, swal_authorid)
)/*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/swal_srcwork_author_unique ON /*$wgDBprefix*/swauthor_links (swal_srcworkid, swal_authorid);


CREATE TABLE /*$wgDBprefix*/swsource_links (

  -- The source work's ID as used in 'srcwork.srcwork_id'.
  swsl_workid     	INTEGER   	NOT NULL,

  -- The source work's own source ID as used in 'srcwork.srcwork_id'.
  swsl_sourceid   	INTEGER   	NOT NULL,

  swsl_comment    	varchar(255) binary       	NOT NULL	DEFAULT ''

	-- UNIQUE (swsl_workid, swsl_sourceid)
)/*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/swsl_work_source_unique ON /*$wgDBprefix*/swsource_links (swsl_workid, swsl_sourceid);


