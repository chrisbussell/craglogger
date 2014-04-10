ALTER TABLE users ADD COLUMN expiry date NOT NULL AFTER activation_code;
ALTER TABLE users ADD COLUMN regdate date NOT NULL AFTER expiry;
ALTER TABLE users ADD COLUMN internaluser int(1) NOT NULL AFTER regdate;
ALTER TABLE users ADD COLUMN timestamp timestamp NOT NULL AFTER internaluser;

ALTER TABLE cragvisit ADD COLUMN event varchar(255) NOT NULL AFTER date;
ALTER TABLE users CHANGE COLUMN internaluser virtualuser INT(1);
