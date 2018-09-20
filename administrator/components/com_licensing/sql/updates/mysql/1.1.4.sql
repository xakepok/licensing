ALTER TABLE `#__licensing_licenses` DROP INDEX `licenseNumber`;
ALTER TABLE `#__licensing_licenses` CHANGE `number` `number` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Номер лицензии';
UPDATE `#__licensing_licenses` SET `number`=NULL WHERE `number` LIKE '-';