START TRANSACTION;

DROP TABLE IF EXISTS `#__licensing_claims`;
CREATE TABLE `#__licensing_claims` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `empl_guid` varchar(36) DEFAULT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `empl_fio` text,
  `email` varchar(128) DEFAULT NULL,
  `phone` varchar(10) DEFAULT NULL,
  `dat` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `structure` text,
  `software` text,
  `status` tinyint(1) DEFAULT NULL,
  `status_user` int(11) DEFAULT NULL,
  `status_time` timestamp NULL DEFAULT NULL,
  `comment` text,
  `scan_pic` text,
  `state` smallint(6) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `#__licensing_companies`
--

DROP TABLE IF EXISTS `#__licensing_companies`;
CREATE TABLE `#__licensing_companies` (
  `id` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Список компаний';

--
-- Структура таблицы `#__licensing_keys`
--

DROP TABLE IF EXISTS `#__licensing_keys`;
CREATE TABLE `#__licensing_keys` (
  `id` smallint(6) NOT NULL,
  `softwareID` int(11) NOT NULL COMMENT 'ID продукта',
  `type` smallint(6) NOT NULL COMMENT 'Тип ключа',
  `text` text NOT NULL COMMENT 'Текстовый ключ либо путь к файлу',
  `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ключи продуктов';

-- --------------------------------------------------------

--
-- Структура таблицы `#__licensing_licenses`
--

DROP TABLE IF EXISTS `#__licensing_licenses`;
CREATE TABLE `#__licensing_licenses` (
  `id` int(11) NOT NULL,
  `licenseType` smallint(6) NOT NULL COMMENT 'Ттип лицензии',
  `name` varchar(255) NOT NULL COMMENT 'Название лицензии',
  `dogovor` varchar(255) DEFAULT NULL,
  `number` varchar(255) NOT NULL COMMENT 'Номер лицензии',
  `dateStart` date NOT NULL,
  `dateExpires` date DEFAULT NULL,
  `unlim` tinyint(4) DEFAULT NULL COMMENT 'Бессрочная',
  `available` tinyint(1) NOT NULL COMMENT 'Доступна для получения ключей',
  `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `#__licensing_orders`
--

DROP TABLE IF EXISTS `#__licensing_orders`;
CREATE TABLE `#__licensing_orders` (
  `id` int(11) NOT NULL,
  `claimID` int(11) NOT NULL,
  `softwareID` int(6) NOT NULL,
  `cnt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Список ПО в заявках';

--
-- Дублирующая структура для представления `#__licensing_order_software`
-- (См. Ниже фактическое представление)
--
DROP VIEW IF EXISTS `#__licensing_order_software`;
CREATE TABLE `#__licensing_order_software` (
`id` int(11)
,`product` varchar(255)
,`license` varchar(255)
,`countAvalible` int(11)
);

-- --------------------------------------------------------

--
-- Структура таблицы `#__licensing_software`
--

DROP TABLE IF EXISTS `#__licensing_software`;
CREATE TABLE `#__licensing_software` (
  `id` int(11) NOT NULL,
  `licenseID` int(11) NOT NULL COMMENT 'ID Лицензии',
  `name` varchar(255) NOT NULL COMMENT 'Название продукта',
  `count` int(11) DEFAULT NULL COMMENT 'Всего копий закуплено',
  `countAvalible` int(11) DEFAULT NULL COMMENT 'Доступно копий',
  `countReserv` int(11) DEFAULT NULL COMMENT 'В резерве',
  `unlim` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Копии безорграничений',
  `about` text COMMENT 'Описание продукта',
  `state` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Структура таблицы `#__licensing_type_keys`
--

DROP TABLE IF EXISTS `#__licensing_type_keys`;
CREATE TABLE `#__licensing_type_keys` (
  `id` smallint(6) NOT NULL,
  `type` varchar(255) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Типы ключей продуктов';

--
-- Дамп данных таблицы `#__licensing_type_keys`
--

INSERT INTO `#__licensing_type_keys` (`id`, `type`, `state`) VALUES
(1, 'Текстовый ключ', 1),
(2, 'Файл ключа (локальный)', 1),
(3, 'Файл ключа (сетевой)', 1),
(4, 'Другой', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `#__licensing_type_licenses`
--

DROP TABLE IF EXISTS `#__licensing_type_licenses`;
CREATE TABLE `#__licensing_type_licenses` (
  `id` smallint(11) NOT NULL,
  `companyID` smallint(11) NOT NULL COMMENT 'Владелец типа лицензии',
  `type` varchar(255) NOT NULL,
  `state` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Тип лицензий';

--
-- Структура для представления `#__licensing_order_software`
--
DROP TABLE IF EXISTS `#__licensing_order_software`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `#__licensing_order_software`  AS  select `soft`.`id` AS `id`,`soft`.`name` AS `product`,`lic`.`name` AS `license`,`soft`.`countAvalible` AS `countAvalible` from (`#__licensing_software` `soft` left join `#__licensing_licenses` `lic` on((`lic`.`id` = `soft`.`licenseID`))) where ((`soft`.`state` = 1) and (`lic`.`state` = 1) and (`lic`.`available` > 0) and ((`lic`.`dateExpires` >= curdate()) or (`lic`.`unlim` = 1)) and (`lic`.`dateStart` <= curdate()) and ((`soft`.`countAvalible` > 0) or (`soft`.`unlim` > 0))) order by `soft`.`name` ;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `#__licensing_claims`
--
ALTER TABLE `#__licensing_claims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `phone` (`phone`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `empl_guid` (`empl_guid`),
  ADD KEY `email` (`email`),
  ADD KEY `status` (`status`);

--
-- Индексы таблицы `#__licensing_companies`
--
ALTER TABLE `#__licensing_companies`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `#__licensing_keys`
--
ALTER TABLE `#__licensing_keys`
  ADD PRIMARY KEY (`id`),
  ADD KEY `type` (`type`),
  ADD KEY `softwareID` (`softwareID`);

--
-- Индексы таблицы `#__licensing_licenses`
--
ALTER TABLE `#__licensing_licenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `licenseNumber` (`number`) USING BTREE,
  ADD KEY `licenseType` (`licenseType`);

--
-- Индексы таблицы `#__licensing_orders`
--
ALTER TABLE `#__licensing_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `claimID_3` (`claimID`,`softwareID`),
  ADD KEY `claimID` (`claimID`),
  ADD KEY `claimID_2` (`claimID`),
  ADD KEY `softwareID` (`softwareID`);

--
-- Индексы таблицы `#__licensing_software`
--
ALTER TABLE `#__licensing_software`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_license` (`licenseID`);

--
-- Индексы таблицы `#__licensing_type_keys`
--
ALTER TABLE `#__licensing_type_keys`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `#__licensing_type_licenses`
--
ALTER TABLE `#__licensing_type_licenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyID` (`companyID`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `#__licensing_claims`
--
ALTER TABLE `#__licensing_claims`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `#__licensing_companies`
--
ALTER TABLE `#__licensing_companies`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `#__licensing_keys`
--
ALTER TABLE `#__licensing_keys`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT для таблицы `#__licensing_licenses`
--
ALTER TABLE `#__licensing_licenses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT для таблицы `#__licensing_orders`
--
ALTER TABLE `#__licensing_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `#__licensing_software`
--
ALTER TABLE `#__licensing_software`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT для таблицы `#__licensing_type_keys`
--
ALTER TABLE `#__licensing_type_keys`
  MODIFY `id` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `#__licensing_type_licenses`
--
ALTER TABLE `#__licensing_type_licenses`
  MODIFY `id` smallint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `#__licensing_keys`
--
ALTER TABLE `#__licensing_keys`
  ADD CONSTRAINT `softwate_id` FOREIGN KEY (`softwareID`) REFERENCES `#__licensing_software` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `type_key_id` FOREIGN KEY (`type`) REFERENCES `#__licensing_type_keys` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `#__licensing_licenses`
--
ALTER TABLE `#__licensing_licenses`
  ADD CONSTRAINT `licenses_types` FOREIGN KEY (`licenseType`) REFERENCES `#__licensing_type_licenses` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `#__licensing_orders`
--
ALTER TABLE `#__licensing_orders`
  ADD CONSTRAINT `#__licensing_orders_ibfk_1` FOREIGN KEY (`claimID`) REFERENCES `#__licensing_claims` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `#__licensing_orders_ibfk_2` FOREIGN KEY (`softwareID`) REFERENCES `#__licensing_software` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `#__licensing_software`
--
ALTER TABLE `#__licensing_software`
  ADD CONSTRAINT `product_license` FOREIGN KEY (`licenseID`) REFERENCES `#__licensing_licenses` (`id`) ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `#__licensing_type_licenses`
--
ALTER TABLE `#__licensing_type_licenses`
  ADD CONSTRAINT `type_license_company` FOREIGN KEY (`companyID`) REFERENCES `#__licensing_companies` (`id`) ON UPDATE CASCADE;
COMMIT;