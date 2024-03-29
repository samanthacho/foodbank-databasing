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
// ADMIN PAGE: Find button w/ input - displays Min/max cost of items purchased depending on user input
SELECT item, max(pamount)
FROM purchase_make
GROUP BY item

SELECT item, min(pamount)
FROM purchase_make
GROUP BY item

-- JOIN QUERY
//ADMIN PAGE: purchase report button - displays purchase list w/ amount + buyer
SELECT purchase_make.item, purchase_make.pamount, employee.username
FROM purchase_make
INNER JOIN employee ON employee.username = purchase_make.username

-- DIVISION QUERY
// ADMIN PAGE: add a volunteer, press 'get all employees' to see the updated list
SELECT sdate, length from collection_work e
WHERE NOT EXISTS (
  (SELECT username from employee
  WHERE username = 'user input')
  MINUS (SELECT usernmae from collection_work
    WHERE length = e.length and sdate = e.sdate and letter = e.letter)
)


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
