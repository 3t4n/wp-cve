CREATE TABLE IF NOT EXISTS `{{db.tables.votes}}` (
  `submission_id` BIGINT(20) UNSIGNED NOT NULL,
  `contest_id` BIGINT(20) UNSIGNED NOT NULL,
  `votes` BIGINT(20) NOT NULL,
  `last_vote_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`submission_id`),
  UNIQUE KEY `SUBMISSION_ID_UNIQUE` (`submission_id`),
  KEY `CONTEST_ID` (`contest_id`),
  KEY `VOTES` (`votes`),
  KEY `LAST_VOTE_AT` (`last_vote_at`)
) {{db.charset}};