INSERT INTO     `roomservice`.`room` 
                (`ID`, `user_ID`, `room_ID`)
       VALUES   (NULL, '1', '3'), (NULL, '1', '14');

INSERT INTO     `roomservice`.`reservations`
                (`ID`, `res_date`, `res_text`, `user_ID`, `room_ID`)
       VALUES   (NULL, '01.05.2013', 'Varaus 01.05.2013', '1', '14'),
                (NULL, '05.05.2013', 'Varaus 05.05.2013', '1', '3');
