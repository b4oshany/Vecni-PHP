DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE addUser(in user_email varchar(55), in user_password varchar(25), in first_name varchar(25), in last_name varchar(25), in user_dob date, in sex varchar(7))
BEGIN
DECLARE userid int;
insert into users(email, password) values(user_email, md5(user_password));
SET userid = (select LAST_INSERT_ID());
insert into user_profile(user_id, first_name, last_name, dob, email, gender, date_joined) values(userid, first_name, last_name, user_dob, user_email, sex, CURDATE());
select * from user_profile where user_id = userid and email = user_email;
END$$

DELIMITER ;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table users
--

CREATE TABLE IF NOT EXISTS users (
  user_id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(30) NOT NULL,
  password varchar(64) NOT NULL,
  PRIMARY KEY (user_id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table user_profile
--

CREATE TABLE IF NOT EXISTS user_profile (
  user_id int(11) NOT NULL,
  first_name varchar(55) DEFAULT NULL,
  last_name varchar(55) DEFAULT NULL,
  email varchar(65) DEFAULT NULL,
  gender varchar(7) DEFAULT NULL,
  occupation varchar(25) NOT NULL,
  dob date DEFAULT NULL,
  profile_pic varchar(255) NOT NULL DEFAULT 'static/img/icons/user.png',
  date_joined datetime NOT NULL,
  is_login tinyint(1) NOT NULL DEFAULT '1',
  last_seen timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP COMMENT 'last logged in date',
  PRIMARY KEY (user_id),
  UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
