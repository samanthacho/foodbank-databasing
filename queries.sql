//ADMIN SCREEN

// Generating report: args(uname)
SELECT username
FROM money_collect
WHERE username=uname

// Staff list: args; NONE
SELECT name, phone
FROM employee

// Donation list: args; NONE
SELECT dname, date, amount
FROM money_collect

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
