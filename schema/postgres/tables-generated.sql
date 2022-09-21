-- This file is automatically generated using maintenance/generateSchemaSql.php.
-- Source: schema/tables.json
-- Do not modify this file directly.
-- See https://www.mediawiki.org/wiki/Manual:Schema_changes
CREATE TABLE swsite (
  sws_id SERIAL NOT NULL,
  sws_name TEXT NOT NULL,
  sws_short_name TEXT NOT NULL,
  sws_site_uri TEXT NOT NULL,
  sws_user_uri TEXT NOT NULL,
  sws_work_uri TEXT NOT NULL,
  PRIMARY KEY(sws_id)
);

CREATE UNIQUE INDEX swsite_name ON swsite (sws_name);

CREATE UNIQUE INDEX swsite_short_name ON swsite (sws_short_name);

CREATE UNIQUE INDEX swsite_site_uri ON swsite (sws_site_uri);

CREATE UNIQUE INDEX swsite_user_uri ON swsite (sws_user_uri);

CREATE UNIQUE INDEX swsite_work_uri ON swsite (sws_work_uri);


CREATE TABLE swauthor (
  swa_id SERIAL NOT NULL,
  swa_site INT NOT NULL,
  swa_user_name TEXT DEFAULT NULL,
  swa_real_name TEXT DEFAULT NULL,
  swa_uri_part TEXT NOT NULL,
  PRIMARY KEY(swa_id)
);

CREATE UNIQUE INDEX swauthor_namesite_unique ON swauthor (swa_site, swa_user_name);


CREATE TABLE srcwork (
  srcwork_id SERIAL NOT NULL,
  srcwork_site INT NOT NULL,
  srcwork_creator INT NOT NULL,
  srcwork_date TIMESTAMPTZ NOT NULL,
  srcwork_title TEXT DEFAULT '' NOT NULL,
  srcwork_uri_part TEXT NOT NULL,
  PRIMARY KEY(srcwork_id)
);

CREATE UNIQUE INDEX srcwork_uridate_unique ON srcwork (
  srcwork_site, srcwork_uri_part, srcwork_date
);


CREATE TABLE swauthor_links (
  swal_srcworkid INT NOT NULL, swal_authorid INT NOT NULL
);

CREATE UNIQUE INDEX swal_srcwork_author_unique ON swauthor_links (swal_srcworkid, swal_authorid);


CREATE TABLE swsource_links (
  swsl_workid INT NOT NULL, swsl_sourceid INT NOT NULL,
  swsl_comment TEXT DEFAULT '' NOT NULL
);

CREATE UNIQUE INDEX swsl_work_source_unique ON swsource_links (swsl_workid, swsl_sourceid);


CREATE TABLE revsrc (
  revsrc_revid INT NOT NULL, revsrc_srcworkid INT NOT NULL,
  revsrc_timestamp TIMESTAMPTZ NOT NULL,
  revsrc_user INT NOT NULL, revsrc_user_text TEXT NOT NULL,
  revsrc_comment TEXT NOT NULL
);

CREATE UNIQUE INDEX revsrc_rs_unique ON revsrc (revsrc_revid, revsrc_srcworkid);

CREATE INDEX revsrc_revid_index ON revsrc (revsrc_revid);

CREATE INDEX revsrc_timestamp_index ON revsrc (revsrc_timestamp);