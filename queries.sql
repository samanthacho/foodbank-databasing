
/*REQUIREMENTS
DONE: Selection + projection query
DONE: update operation

aggregation query : GROUP BY
join query : JOIN/ inner /outer/etc
division query :
delete operation
nested aggregation with group-by
graphical user interface
*/
///////////////////ADMIN SCREEN/////////////////////////////////

// Adding Employees
INSERT into employee
VALUES(a,b,c,e);
--"insert into employee values (:bind1, :bind2, :bind3, :bind4)", $alltuples);

//Updating Employees
UPDATE employee
SET name = ///////////////HELP////////////:bind2
WHERE name = :bind2
--"update employee set name=:bind2 where name=:bind1", $alltuples);

// Employee report
SELECT finder
FROM employee
WHERE username =
--"select $finder from employee where username = '$unametouse'");

// Adreport
INSERT into adreport VALUES ()
-- "insert into adreport values ('$in1', '$in2', '$in3')

// Staff list: args; NONE
SELECT name, phone
FROM employee

// Donation list: args; NONE
SELECT dname, moneydate, amount
FROM money_collect

//Physical Donations
SELECT name, itemdate, item
FROM item_collects

// Purchase list + Buyer
SELECT purchase_make.item, purchase_make.pamount, employee.username
FROM purchase_make
INNER JOIN employee ON employee.username = purchase_make.username

SELECT sum(pamount)
FROM purchase_make

SELECT sum(amount)
FROM money_collect

SELECT letter
FROM shift
WHERE startTime=  and length= and sdate=
-- select letter from shift where startTime='$int' and length='$slength' and letter='$schar' and sdate='$sdate'

INSERT into shift VALUES ()
--"insert into shift values (:bind1, :bind2, :bind3, :bind4)", $alltuples);

SELECT username
FROM employee
WHERE username =
--"select username from employee where username='$uinput'");

INSERT into collection_work values ()
--"insert into collection_work values (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);

INSERT into distribution_work values ()
--"insert into distribution_work values (:bind1, :bind2, :bind3, :bind4, :bind5)", $alltuples);

INSERT into adreport values ()
--("insert into adreport values ('$in1', '$in2', '$in3')

SELECT max(avg(amount))
FROM money_collect
GROUP BY dname, dphone

SELECT min(avg (amount))
FROM money_collect
GROUP BY dname, dphone

UPDATE employee
SET phone = '$newnumb'
WHERE username =
-- "update employee set phone = '$newnumb' where username = '$user'");

SELECT action
FROM adreport
WHERE username =
--"select action from adreport where username = '$login'"

DELETE from admin
WHERE username =
--("delete from admin where username = '$ins'");

SELECT sdate, length
FROM collection_work e
WHERE NOT EXIST (( SELECT username FROM employee WHERE username = '$login')
MINUS (SELECT username FROM collection_work WHERE length = e.length and sdate = e.sdate
and letter = e.letter))
--"select sdate, length from collection_work e where not exists((select username from employee where username='$login') minus (select username from collection_work where length = e.length and sdate = e.sdate and letter = e.letter))



///////////////////COLLECTION SCREEN////////////////////////////
//Item Lookup
SELECT name
FROM item
WHERE name = instItem/*not sure what to put here*/
--select name from item where name='$check1'");


INSERT into money_collect
VALUES (a,b,c,d,e,f,g);
--insert into money_collect values (:bind1, :bind2, :bind3, :bind4, :bind5, :bind6, :bind7)", $alltuples);


SELECT name
FROM item
WHERE name= IDItem
--"select name from item where name='$ivar'");


SELECT location
FROM item
WHERE name =itemID;
--"select location from item where name='$itemID'");


INSERT into item_collects
VALUES (a,b,c,d,e,f)
--"insert into item_collects values(:bind1,:bind2,:bind3,:bind4,:bind5,:bind6)", $alltuples);

SELECT exDate
FROM expirationDate
WHERE exDate =
--"select exDate from expirationDate where exDate='$int'");

INSERT into expirationDate VALUES ()--"insert into expirationDate values ('$int')");

INSERT into expiresOn VALUES() --"insert into expiresOn values (:bind1, :bind2, :bind3)", $alltuples);


SELECT username
FROM ADMIN
WHERE username=uname
--"select username from admin where username='$un'");

INSERT into item VALUES (a,b,c) --("insert into item values(:bind1, :bind2,:bind3)", $alltuples)

SELECT value
FROM food_g
WHERE value = --"select value from food_g where value='$nutvar'")

INSERT into food_g VALUES () --insert into food_g values ('$nutvar')");

INSERT into f_has VALUES () --"insert into f_has values('$nutvar', '$iname')");

//////////////////////////// DISTRIBUTION FILE//////////////////////////////

SELECT username
FROM admin
WHERE username=
--"select username from admin where username='$un'")

SELECT name
FROM item
WHERE name = /*user input*/
--select name from item where name='$check1'");

SELECT name, count(*), exDate
FROM expiresOn
WHERE name = /*user input*/
GROUP BY name, exDate
--"select name, count(*), exdate from expiresOn where name='$check1' group by name, exdate")

SELECT name
FROM item
WHERE name = /*user input*/
--"select name from item where name='$check1'");

SELECT id
FROM expiresOn
WHERE name  = /*user input*/ and exDate = /*user input*/
--"select id from expireson where name='$itemnm' and exdate='$int'");


DELETE from expireson
WHERE id = /*user input*/ and name = /*user input*/ and exDate = /*user input*/
-- delete from expireson where id = '$curr_id' and name = '$itemnm' and exdate = '$int'");

SELECT count(*)
FROM expiresOn
WHERE name = /*user input*/ and exdate = /*user input*/ group by exdate, name
-- select count(*) from expireson where name='$itemnm' and exdate='$int' group by exdate, name


//////////////////////////// INVENTORY FILE//////////////////////////////
SELECT username
FROM admin
WHERE username =
--select username from admin where username='$login'");

SELECT name, count(*), exDate
FROM expiresOn
GROUP BY name, exDate


//////////////////////////// LOGIN FILE//////////////////////////////

// Login
SELECT password, user
FROM employee
WHERE username= uname
--("select password,username from employee where username='$un'");

SELECT username
FROM admin
WHERE username=uname
--"select username from admin where username='$un'");


//////////////////////////// PURCHASE FILE//////////////////////////////
SELECT name
FROM item
WHERE name =
--"select name from item where name='$check1'");

INSERT into item VALUES ()
--"insert into item values(:bind1, :bind2,:bind3)", $alltuples);

SELECT value
FROM food_g
WHERE value=
--"select value from food_g where value='$nutvar'"

INSERT into food_g values()
--"insert into food_g values ('$nutvar')");

INSERT into f_has values()
--insert into f_has values('$nutvar', '$iname')");

SELECT name
FROM item
WHERE name =
--select name from item where name='$ivar'");

SELECT sum(amount)
FROM money_collect
GROUP BY amount

SELECT sum(pamount)
FROM purchase_make
GROUP BY pamount

INSERT into purchase_make values ()
--insert into purchase_make values(:bind1, :bind2, :bind3, :bind4)", $alltuples);

SELECT exDate
FROM expirationDate
WHERE exDate =
--"select exDate from expirationDate where exDate='$int'");

INSERT into expirationDate VALUES ()
--"insert into expirationDate values ('$int')");

INSERT into expireson VALUES ()
--"insert into expiresOn values (:bind1, :bind2, :bind3)", $alltuples);

SELECT username
FROM admin
WHERE username =
--"select username from admin where username='$un'");



//////////////////////////// VOLUNTEER FILE//////////////////////////////
SELECT sdate
FROM collection_work
WHERE startTime <=  and ( - startTime)/ 100 <=any (
SELECT length from collection_work
WHERE startTime <= and = username and =sdate)
--"select sdate from collection_work where startTime <= '$int' and
-- ('$int' - startTime)/100 <= any (select length from collection_work where startTime <= '$int' and '$login' = username and '$currdate' = sdate)");

SELECT sdate
FROM distribution_work
WHERE startTime
--"select sdate from distribution_work where startTime <= '$int' and
 --('$int' - startTime)/100 <= any (select length from distribution_work where startTime <= '$int' and '$login' = username and '$currdate' = sdate)");
