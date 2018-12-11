ALTER TABLE `#__licensing_licenses`
  ADD `files` TEXT NULL DEFAULT NULL COMMENT 'Путь к архиву с файлами лицензий и договоров' AFTER `unlim`;
