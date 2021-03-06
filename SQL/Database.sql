CREATE TABLE accounts (
	ID VARCHAR(20) NOT NULL UNIQUE,
	NAME_F VARCHAR(10) NOT NULL,
	NAME_S VARCHAR(10),
	EMAIL VARCHAR(50) NOT NULL UNIQUE,
	ACTIVATED INT(1) NOT NULL,
	PASSWORD VARCHAR(100) NOT NULL,
	ACCOUNT_TYPE VARCHAR(10) NOT NULL,
	PRIMARY KEY (ID)
);

CREATE TABLE time_slots (
	ID VARCHAR(20) NOT NULL UNIQUE,
	SLOT_TIME DATE DEFAULT CURRENT_TIMESTAMP NOT NULL,
	SLOT_CAPACITY INT(100) DEFAULT 5 NOT NULL,
	SLOT_VACANCY INT(100) DEFAULT 0 NOT NULL,
	ZOOM_LINK VARCHAR(100) NOT NULL,
	ADMIN_ID VARCHAR(20) NOT NULL,
	PRIMARY KEY (ID),
	FOREIGN KEY (ADMIN_ID) REFERENCES accounts (ID)
);

CREATE TABLE bookings (
	ID VARCHAR(20) NOT NULL UNIQUE,
	SLOT_ID VARCHAR(20) NOT NULL,
	ADMIN_ID VARCHAR(20) NOT NULL,
	SUBJECT_ID VARCHAR(20) NOT NULL,
	PRIMARY KEY (ID),
	FOREIGN KEY (SLOT_ID) REFERENCES time_slots (ID),
	FOREIGN KEY (ADMIN_ID) REFERENCES accounts (ID),
	FOREIGN KEY (SUBJECT_ID) REFERENCES accounts (ID) 
);