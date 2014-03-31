ALTER TABLE users ADD COLUMN expiry date NOT NULL AFTER activation_code;

ALTER TABLE users ADD COLUMN regdate date NOT NULL AFTER emailshow;
ALTER TABLE users ADD COLUMN timestamp timestamp NOT NULL AFTER regdate;

ALTER TABLE cragvisit ADD COLUMN event varchar(255) NOT NULL AFTER date;
