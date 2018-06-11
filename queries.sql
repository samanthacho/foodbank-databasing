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
