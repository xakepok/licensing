ALTER TABLE `#__licensing_claims` CHANGE `status_time` `status_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `#__licensing_claims`
  DROP `software`,
  DROP `status`,
  DROP `scan_pic`;