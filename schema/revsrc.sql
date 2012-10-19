--
-- Tables to be added to the wikischema.
--


--
-- Table structure for table revsrc
--
-- This table links source works to revisions. Only the first revision of an article
-- that is derived from a source should be linked to its source.
-- Each revision may have more than one source work. 
-- Vice versa, one source work may be attributed by more than one revision.
-- Each line holds one revision-source pair plus some additional info.
-- Thus, we use 'revsrc_rs' as unique KEY.

CREATE TABLE /*$wgDBprefix*/revsrc (

  -- The revision's ID as used in 'revision.rev_id'.
  revsrc_revid      INTEGER   	NOT NULL,

  -- The source work's ID as used in 'srcwork.srcwork_id'.
  revsrc_srcworkid  INTEGER   	NOT NULL,

  -- A MW timestamp when the attribution was made.
  revsrc_timestamp 	binary(14) NOT NULL,

  -- The user who has made the attribution.
  revsrc_user      	INTEGER   	NOT NULL,

  revsrc_user_text 	TEXT      	NOT NULL,

  revsrc_comment  	TEXT				NOT NULL 	DEFAULT ''

)/*$wgDBTableOptions*/;
CREATE UNIQUE INDEX /*i*/revsrc_rs_unique ON /*$wgDBprefix*/revsrc (revsrc_revid, revsrc_srcworkid);
CREATE INDEX /*i*/revsrc_revid_index ON /*$wgDBprefix*/revsrc (revsrc_revid);
CREATE INDEX /*i*/revsrc_timestamp_index ON /*$wgDBprefix*/revsrc (revsrc_timestamp);


