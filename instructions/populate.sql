/*
  email: michelbalamou@gmail.com
  pass: lmaolmao

  admin
  username: michael
  pass: lmaolmao
*/
USE TheFoodApp;

INSERT INTO  FA_RESTORANTS (FA_Email,FA_Pass,FA_Username,FA_Restaurant_Name,FA_Country,FA_State_Province,FA_City,FA_Address,FA_Postal_Code)
VALUES ('michelbalamou@gmail.com','$2a$10$ae11f5212398c736c468auIqanzbH7e3mou6x/pPUci/qZ3IW5FKW','Balamou','McDonalds','Canada','Ontario','Ottawa','318 Berrigan Dr','K2J5B5');

INSERT INTO  FA_ADMIN_PANEL (FA_Username, FA_Pass)
VALUES ('michael','$2a$10$ae11f5212398c736c468auIqanzbH7e3mou6x/pPUci/qZ3IW5FKW');

INSERT INTO FA_MENUS (RESTAURANT_ID,FA_Pic,FA_Order,FA_Product_Name,FA_Price,FA_Desc,FA_Contents,FA_Section)
VALUES ('1','5653e3af25a4a.jpg','1','Salad','9.99','Has tomatoes','vegan.','none'),
('1','5653dca4653cc.jpg','2','Burger','8.50','Contains cheese, beef, bread','meat.','none');
