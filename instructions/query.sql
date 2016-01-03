/*
  Version 1.8
*/
CREATE DATABASE TheFoodApp;
USE TheFoodApp;

CREATE TABLE FA_RESTORANTS (
  ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,

  FA_Email varchar(100) NOT NULL unique,
  FA_Pass varchar(100) NOT NULL,
  FA_Username varchar(100) NOT NULL,

  FA_Restaurant_Name varchar(100) NOT NULL,

  FA_Country varchar(100),
  FA_State_Province varchar(100),
  FA_City varchar(100),
  FA_Address varchar(100),
  FA_Postal_Code varchar(100),

  FA_Token varchar(100) NOT NULL DEFAULT '0',
  FA_Hash varchar(100) NOT NULL DEFAULT '0',
  FA_Active int DEFAULT 0,

  FA_Validated int DEFAULT 0,
  FA_Dat_Reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  /* NEW v1.8 */
  FA_Phone_number varchar(100) DEFAULT '',
  FA_Website varchar(100) DEFAULT '',

  FA_Pic varchar(200),

  Schedule_Monday varchar(50) DEFAULT '',
  Schedule_Tuesday varchar(50) DEFAULT '',
  Schedule_Wednesday varchar(50) DEFAULT '',
  Schedule_Thursday varchar(50) DEFAULT '',
  Schedule_Friday varchar(50) DEFAULT '',
  Schedule_Saturday varchar(50) DEFAULT '',
  Schedule_Sunday varchar(50) DEFAULT ''
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

  FOREIGN KEY (RESTAURANT_ID) REFERENCES FA_RESTORANTS(ID)
);


CREATE TABLE FA_ADMIN_PANEL (
  ID int NOT NULL PRIMARY KEY AUTO_INCREMENT,
  FA_Username varchar(100) NOT NULL,
  FA_Pass varchar(100) NOT NULL,

  FA_Token varchar(100) NOT NULL DEFAULT '0'
);
