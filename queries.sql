-- SELECTION + PROJECTION QUERY
// ADMIN PAGE: Enter username and field user wants to look up
SELECT finder
FROM employee
WHERE username = input

-- UPDATE OPERATION
//ADMIN PAGE: Updates new phone number
UPDATE employee 
SET phone = newphone
WHERE username = userinput

-- AGGREGATION QUERY ; GROUP BY
// DISTRIBUTION PAGE : search item shows a list of wanted item along with quantity and expiration date
SELECT name, count(*), exDate
FROM expiresOn 
WHERE name = itemlookup
GROUP BY name, exDate

-- JOIN QUERY
//ADMIN PAGE: purchase report button - displays purchase list w/ amount + buyer
SELECT purchase_make.item, purchase_make.pamount, employee.username 
FROM purchase_make 
INNER JOIN employee ON employee.username = purchase_make.username

-- DIVISION QUERY
// ADMIN PAGE: add a volunteer, press 'get all employees' to see the updated list
INSERT into employee
VALUES(a,b,c,e);

SELECT name, phone
FROM employee

-- DELETE OPERATION
// ADMIN PAGE: Deletes an admin
DELETE from admin
WHERE username = userinput

-- NESTED AGGREGATION WITH GROUP BY
// ADMIN PAGE: shows max/min donation average
SELECT max(avg(amount))
FROM money_collect
GROUP BY dname, dphone

SELECT min(avg (amount))
FROM money_collect
GROUP BY dname, dphone
