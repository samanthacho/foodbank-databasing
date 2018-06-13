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

//ADMIN SCREEN

// Generating report: args(uname)
SELECT username
FROM money_collect
WHERE username=uname

// Adding Employees
INSERT into employee
VALUES(a,b,c,e);

//Updating Employees
UPDATE employee
SET name = b
WHERE name = a

// Employee report
SELECT dname, amount
FROM money_collect
WHERE username = uname

//
SELECT name, item
FROM item_collects
WHERE username=unmame

// Staff list: args; NONE
SELECT name, phone
FROM employee

// Donation list: args; NONE  
SELECT dname, moneydate, amount
FROM money_collect 

//
SELECT name, itemdate, item
FROM item_collects

// Purchase list: args; NONE
SELECT item, pamount
FROM purchase_make


// Making purchase: args(item, amount)
(SELECT sum(amount) FROM money_collect GROUP BY amount) -
amount -
(SELECT sum(pamount) FROM purchase_make GROUP BY pamount)

// Amount Available: args; NONE
(SELECT sum(amount) FROM money_collect GROUP BY amount) -
(SELECT sum(pamount) FROM purchase_make GROUP BY pamount)



//COLLECTION SCREEN

//Item Lookup
SELECT name
FROM item
WHERE name = instItem/*not sure what to put here*/

INSERT into money_collect 
VALUES (a,b,c,d,e,f,g);

SELECT name
FROM item
WHERE name= IDItem

SELECT location
FROM item
WHERE name =itemID;

INSERT into item_collects 
VALUES (a,b,c,d,e,f)

SELECT username
FROM ADMIN
WHERE username=uname

INSERT into item
VALUES (a,b,c)

SELECT exDate
FROM expirationDate
WHERE exDate =

INSERT expirationDate 
VALUES ()

// LOGIN SCREEN

// Login
SELECT password
FROM employee 
WHERE username= uname

SELECT username
FROM admin
WHERE username=uname


// VOLUNTEER SCREEN





















