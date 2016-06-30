CREATE TABLE IF NOT EXISTS auth_sms_key (
  id mediumint(9) NOT NULL auto_increment,
  login varchar(255) NOT NULL,
  password varchar(255) NOT NULL,
  smskey varchar(32) NOT NULL,
  moment int(16) NOT NULL,
  userid int(8) NOT NULL,
  PRIMARY KEY  (id)
);