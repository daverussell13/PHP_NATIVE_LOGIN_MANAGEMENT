-- USER : ROOT --

CREATE DATABASE php_login_management;
CREATE DATABASE php_login_management_test;

use php_login_management;

CREATE TABLE users (
	id varchar(255) primary key,
    name varchar(255) not null,
    password varchar(255) not null
) ENGINE InnoDB;

CREATE TABLE sessions (
	id varchar(255) primary key,
    user_id varchar(255),
    CONSTRAINT fk_session_user FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE InnoDB;

use php_login_management_test;

CREATE TABLE users (
	id varchar(255) primary key,
    name varchar(255) not null,
    password varchar(255) not null
) ENGINE InnoDB;

CREATE TABLE sessions (
	id varchar(255) primary key,
    user_id varchar(255),
    CONSTRAINT fk_session_user FOREIGN KEY (user_id) REFERENCES users (id)
) ENGINE InnoDB;


