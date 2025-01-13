create table users (
    int id,
    username varchar(255),
    password varchar(64),
    session_token varchar(64),
    session_time timestamp
) ENGINE=InnoDB;

create table user_shares (
    int id,
    username varchar(255),
    CONSTRAINT username CHECK (myfield REGEXP '^[a-z]+$')
) ENGINE=InnoDB;

