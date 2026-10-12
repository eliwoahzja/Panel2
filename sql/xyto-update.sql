-- SHAI Panel database update
-- Import into `itwqfbgt_ethnir` (phpMyAdmin -> Import, or SQL tab).
-- Safe to run repeatedly: every statement is guarded.

CREATE TABLE IF NOT EXISTS `Keygen_Links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `8BP_Link` varchar(255) NOT NULL DEFAULT '',
  `CODM_Link` varchar(255) NOT NULL DEFAULT '',
  `FL84_Link` varchar(255) NOT NULL DEFAULT '',
  `MLBB_Link` varchar(255) NOT NULL DEFAULT '',
  `PUBG_Link` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `Keygen_Links` (`id`, `8BP_Link`, `CODM_Link`, `FL84_Link`, `MLBB_Link`, `PUBG_Link`)
SELECT 1, '', '', '', '', '' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `Keygen_Links` WHERE `id` = 1);

CREATE TABLE IF NOT EXISTS `mod_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modname` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `mod_settings` (`id`, `modname`)
SELECT 1, 'SHAI Panel' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `mod_settings` WHERE `id` = 1);

-- test referral code you can type in the register form: SHAITEST01
INSERT INTO `referral_code`
  (`code`, `Referral`, `level`, `set_saldo`, `used_by`, `created_by`, `created_at`, `updated_at`, `acc_expiration`)
SELECT '82a5584f55bc419be35cf2944366fe5b', 'SHAITEST01', 1, 0, '', 'Owner', NOW(), NOW(),
       DATE_ADD(NOW(), INTERVAL 365 DAY) FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `referral_code` WHERE `Referral` = 'SHAITEST01');

-- test account, ready to log in: shaitest / ShaiTest123
INSERT INTO `users`
  (`fullname`, `username`, `email`, `reset_link_token`, `exp_date`, `level`, `saldo`, `status`,
   `uplink`, `password`, `user_ip`, `created_at`, `updated_at`, `expiration_date`, `logged_in`)
SELECT 'Shai Test', 'shaitest', 'test@test.com', '', '', 1, 0, 1, 'admin',
       '$2y$08$PlxcY95N/yZxbJa/umUxhexFdf4m/RJrfPCO6sdQTpPVpx.HiRPay', '127.0.0.1',
       NOW(), NOW(), DATE_ADD(NOW(), INTERVAL 365 DAY), 0 FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `users` WHERE `username` = 'shaitest');

-- cleanup when you are done testing
-- DELETE FROM `users` WHERE `username` = 'shaitest';
-- DELETE FROM `referral_code` WHERE `Referral` = 'SHAITEST01';
