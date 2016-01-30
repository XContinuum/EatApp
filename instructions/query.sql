/*
  Version 2.1
*/
CREATE DATABASE EatApp;
USE EatApp;

CREATE TABLE ADMIN_PANEL (
    ID int NOT NULL PRIMARY KEY AUTO_INCREMENT, /**/
    Username varchar(30) NOT NULL, /**/
    Password varchar(100) NOT NULL, /**/
    Restriction int(3),
    Token varchar(100) NOT NULL DEFAULT '0' /**/
);

CREATE TABLE CHAIN_OWNER (
    ID int NOT NULL PRIMARY KEY AUTO_INCREMENT, /**/
    Email varchar(100) NOT NULL unique, /**/
    Password varchar(100) NOT NULL, /**/

    Link varchar(30) NOT NULL unique, /**/
    Restaurant_Name varchar(25) NOT NULL, /**/
    Website varchar(100), /**/
    Picture varchar(200), /**/

    Token varchar(100) DEFAULT 0, /**/
    Validated int(1) DEFAULT 0, /**/
    Validated_By int(10),
    Dat_Reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    Hash varchar(32) DEFAULT 0, /**/
    Active int(1) NOT NULL DEFAULT 0, /**/

    FOREIGN KEY (Validated_By) REFERENCES ADMIN_PANEL(ID)
);

CREATE TABLE SCHEDULE (
    ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    OWNER_ID int(11) NOT NULL,

    Schedule_Monday varchar(20),
    Schedule_Tuesday varchar(20),
    Schedule_Wednesday varchar(20),
    Schedule_Thursday varchar(20),
    Schedule_Friday varchar(20),
    Schedule_Saturday varchar(20),
    Schedule_Sunday varchar(20),

    FOREIGN KEY (OWNER_ID) REFERENCES CHAIN_OWNER(ID)
);

CREATE TABLE RESTORANTS (
    ID int NOT NULL AUTO_INCREMENT,
    OWNER_ID int(11) NOT NULL,
    R_Order int(11) NOT NULL,
    Menu_Name varchar(30),

    Date_Added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    Schedule_ID int(11),

    Link varchar(30) NOT NULL unique,
    Country varchar(200),
    State_Province varchar(200),
    City varchar(200),
    Postal_Code varchar(25),
    Address varchar(100),
    Longitude double,
    Latitude double,

    Phone_Number varchar(25),

    Picture varchar(200),

    PRIMARY KEY (OWNER_ID,R_Order),
    KEY ID (ID),
    FOREIGN KEY (OWNER_ID) REFERENCES CHAIN_OWNER(ID),
    FOREIGN KEY (Schedule_ID) REFERENCES SCHEDULE(ID)
);



CREATE TABLE FA_MENUS (
  ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  FA_Order int NOT NULL,
  RESTAURANT_ID int(11) NOT NULL,

  FA_Product_Name varchar(200) NOT NULL,
  FA_Price double NOT NULL,
  FA_Desc varchar(200),
  FA_Contents varchar(200),
  FA_Pic varchar(200),

  FA_Section varchar(200),

  FA_Last_Modified date,

  FOREIGN KEY (RESTAURANT_ID) REFERENCES RESTORANTS(ID),

  /* NEW v2.0 */
  FA_Currency varchar(200)
);

CREATE TABLE MENUS (
    ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    OWNER_ID int(11) NOT NULL, /**/
    Name varchar(30) NOT NULL,

    Product_Name varchar(200) NOT NULL,
    Price double NOT NULL,
    Description varchar(200),
    Picture varchar(200),
    Contents varchar(200),
    Section varchar(200),
    Currency varchar(15),

    Last_Modified TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (OWNER_ID) REFERENCES CHAIN_OWNER(ID) /**/
);





CREATE TABLE SEARCHES (
    ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    IP_Address varchar(30) NOT NULL,
    Input varchar(124),
    Chain_id int(11),
    Results_Found int(50),
    Date_search TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (Chain_id) REFERENCES CHAIN_OWNER(ID)
);
