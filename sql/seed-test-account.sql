-- username: shaitest / password: ShaiTest123
-- register form referral code: SHAITEST01

INSERT INTO `referral_code`
  (`code`, `Referral`, `level`, `set_saldo`, `used_by`, `created_by`, `created_at`, `updated_at`, `acc_expiration`)
VALUES
  ('82a5584f55bc419be35cf2944366fe5b', 'SHAITEST01', 1, 0, '', 'Owner', NOW(), NOW(), DATE_ADD(NOW(), INTERVAL 365 DAY));

INSERT INTO `users`
  (`fullname`, `username`, `email`, `reset_link_token`, `exp_date`, `level`, `saldo`, `status`,
   `uplink`, `password`, `user_ip`, `created_at`, `updated_at`, `expiration_date`, `logged_in`)
VALUES
  ('Shai Test', 'shaitest', 'test@test.com', '', '', 1, 0, 1,
   'admin', '$2y$08$PlxcY95N/yZxbJa/umUxhexFdf4m/RJrfPCO6sdQTpPVpx.HiRPay', '127.0.0.1', NOW(), NOW(), DATE_ADD(NOW(), INTERVAL 365 DAY), 0);

-- DELETE FROM `users` WHERE `username` = 'shaitest';
-- DELETE FROM `referral_code` WHERE `Referral` = 'SHAITEST01';
