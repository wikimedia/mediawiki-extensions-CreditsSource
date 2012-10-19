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

BEGIN;
SET client_min_messages = 'ERROR';

CREATE TABLE revsrc (

  -- The revision's ID as used in 'revision.rev_id'.
  revsrc_revid      INTEGER   	NOT NULL	REFERENCES revision(rev_id) ON DELETE CASCADE,

  -- The source work's ID as used in 'srcwork.srcwork_id'.
  revsrc_srcworkid  INTEGER   	NOT NULL	REFERENCES zsamm.srcwork(srcwork_id) ON DELETE RESTRICT,

  -- A MW timestamp when the attribution was made.
  revsrc_timestamp 	TIMESTAMPTZ NOT NULL,

  -- The user who has made the attribution.
  revsrc_user      	INTEGER   	NOT NULL	REFERENCES zsamm.mwuser(user_id) ON DELETE RESTRICT,

  revsrc_user_text 	TEXT      	NOT NULL,

  revsrc_comment  	TEXT				NOT NULL 	DEFAULT '',

	UNIQUE (revsrc_revid, revsrc_srcworkid)
);
-- CREATE UNIQUE INDEX revsrc_rs_unique ON revsrc (revsrc_revid, revsrc_srcworkid);
CREATE INDEX revsrc_revid_index ON revsrc (revsrc_revid);
CREATE INDEX revsrc_timestamp_index ON revsrc (revsrc_timestamp);

COMMIT;

