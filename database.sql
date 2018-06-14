set FOREIGN_KEY_CHECKS=0 ;
drop table money_collects;
drop table item_collects;
drop table item_is;
drop table item_distributes;
drop table group;
drop table has;
drop table collection;
drop table distribution;
drop table adds;
drop table purchase_makes;
drop table shift_logDelete;
drop table works;
drop table volunteer_add;
drop table administrator;
drop table employee;
drop table expiresOn;
drop table expirationDate;
drop table shift;
drop table item;

create table item(
	name varchar2(30) primary key,
	category varchar2(30),
	location varchar2(30) not null
);

grant select on item to public;

create table expirationDate(
	exDate date primary key
);

grant select on expirationDate to public;

create table employee(
	phone number,
	name varchar2(30),
	username varchar2(30),
	password varchar2(30),
	primary key (username)
);

grant select on employee to public;

create table expiresOn(
	id varchar2(255),
	name varchar2(30),
	exdate date,
	primary key (id)
);

grant select on expiresOn to public;

create table money_collect(
	did varchar2(255),
	name varchar2(30),
	phone number,
	moneydate date not null,
	username varchar2 not null,
	amount decimal(10,2),
	medium varchar2(30),
	primary key (did),
	foreign key (username) references employee (username) ON DELETE CASCADE ON UPDATE CASCADE
	);

grant select on money_collects to public;

create table item_collects(
	did varchar2(30),
	name varchar2(30),
	phone number,
	itemdate date,
	username varchar2(30) not null,
	item varchar2(30) not null,
	primary key (did),
	foreign key (username) references employee (username) ON UPDATE CASCADE
);

grant select on item_collects to public;

-- TODO: KIKI
-- create table item_distributes(
-- 	string varchar2(255),
-- 	category varchar2(255),
-- 	location varchar2(255),
-- 	startTime time,
-- 	length float(53)
-- 	letter char(1),
-- 	primary key (string)
-- 	foreign key(startTime,length,letter) references distribution_shift (startTime,length,letter) ON UPDATE CASCADE
-- );
--
-- grant select on item_distributes to public;

create table food_g (
	value varchar2(30) primary key
);

grant select on food_g to public;

create table f_has (
	value varchar2(30),
	name varchar2(30),
	primary key(value,name),
	foreign key(value) references food_g (value),
	foreign key(name) references item (name)
);

grant select on f_has to public;

create table collection_work (
	startTime number,
	length decimal(5,2),
	letter varchar2(4),
	sdate date,
	username varchar2(30) not null,
	primary key (startTime,length,letter, sdate),
	foreign key(startTime,length,letter,sdate) references shift (startTime,length,letter,sdate) ON DELETE CASCADE ON UPDATE CASCADE,
	foreign key (username) references employee (username) ON UPDATE CASCADE ON DELETE CASCADE
);

grant select on collection_work to public;

create table distribution_work (
	startTime number,
	length decimal(5,2),
	letter char(4),
	sdate date,
	username varchar2(30),
	primary key(startTime,length,letter,sdate),
	foreign key(startTime,length,letter,sdate) references shift (startTime,length,letter,sdate) ON DELETE CASCADE,
	foreign key (username) references employee (username) ON DELETE CASCADE
);

grant select on distribution_work to public;

create table purchase_make (
	pid varchar2(255) primary key,
	pamount decimal(10,2) not null,
	userName varchar2(30) not null,
	item varchar2(30) not null,
	foreign key(userName) references admin (userName)
);

grant select on purchase_make to public;

create table shift (
	startTime number not null,
	length decimal(5,2),
	letter varchar2(4),
	sdate date not null,
	primary key(startTime,length,letter,sdate)
);

grant select on shift to public;

create table volunteer_add(
	v_uname varchar2(30) primary key
	userName varchar2(30),
	foreign key(v_uname)references employee (userName) ON DELETE CASCADE,
	foreign key(userName) references admin (userName) ON DELETE CASCADE
);

--grant select on volunteer_add to public;

create table admin (
	userName varchar2(30),
	primary key(userName),
	foreign key (userName) references employee (userName) ON DELETE CASCADE
);

grant select on admin to public;

-- INSERT money_collects data

insert into money_collects values(1,"John Smith", 6041234567,2018-05-31, 100.25, "credit", 10:45:00, 1.25, "A");
insert into money_collects values(2,"Jane Doe", 6048463847,2018-05-31, 125.25, "cash", 12:45:00, 2.50, "A");
insert into money_collects values(4,"Chad Smith", 7788297482,2018-12-03, 110.25, "credit", 13:00:00, 1.00, "A");
insert into money_collects values(6,"Sarah Lee", 8391848294,2015-01-13, 200.00, "cash", 15:00:00, 2.00, "A");
insert into money_collects values(7,"Amber Chan", 7784920412,2000-07-23, 135.00, "cash", 14:30:00, 2.25, "A");

-- INSERT item_collects data
insert into item_collects values(3,"John Doe", 6048593123,2018-02-10, 10:45:00, 1.15, "A");
insert into item_collects values(5,"Jessica Hope", 77892847293,2018-02-10, 12:45:00, 3.00, "A");
insert into item_collects values(8,"Jack Ko", 2048573859,2017-08-11, 13:00:00, 2.15, "A");
insert into item_collects values(9,"Liz Apple", 4739174824,2018-05-12, 14:30:00, 2.00, "A");
insert into item_collects values(10,"Nick Brown ", 6045829104,2018-11-10, 15:00:00, 1.45, "A");

-- INSERT item_is data
insert into item_is values("Bread", 3);
insert into item_is values("Canned Pineapple", 5);
insert into item_is values("Vegetable Oil", 8);
insert into item_is values("Pasta", 9);
insert into item_is values("Canned Beans", 10);

-- INSERT item_distributes data
insert into item_distributes values("Bread", "Food","PantryC", 07:55:00, 00.50, "A");
insert into item_distributes values("Linen", "Non-Food","PantryB", 09:25:00, 00.25, "A");
insert into item_distributes values("Vegetable Oil", "Food","PantryA", 12:45:00, 00.15, "A");
insert into item_distributes values("Pasta", "Food","PantryA", 13:35:00, 00.10, "A");
insert into item_distributes values("Canned Beans", "Food","PantryA", 14:15:00, 00.15, "A");

-- INSERT group data
insert into group values("Carbohydrates");
insert into group values("Non-nutritional");
insert into group values("Protein");
insert into group values("Fats");
insert into group values("Produce");

-- INSERT has data
insert into has values("Bread", "Carbohydrates");
insert into has values("Linen", );
insert into has values("Vegetable Oil", "Fats");
insert into has values("Pasta", "Carbohydrates");
insert into has values("Canned Beans", "Protein");

-- INSERT collection data
insert into collection values(10:45:00, 1.25, "A");
insert into collection values(12:45:00, 2.50, "A");
insert into collection values(13:00:00, 1.00, "A");
insert into collection values(15:00:00, 2.00, "A");
insert into collection values(14:30:00, 2.15, "A");

-- INSERT distribution data
insert into distribution values(10:45:00, 1.25, "A");
insert into distribution values(12:45:00, 2.50, "A");
insert into distribution values(13:00:00, 1.00, "A");
insert into distribution values(15:00:00, 2.00, "A");
insert into distribution values(14:30:00, 2.15, "A");

-- INSERT adds data
insert into adds values("Bread", 1);
insert into adds values("Linen", 2);
insert into adds values("Canned Soup", 3);
insert into adds values("Tampons", 4);
insert into adds values("Pasta", 5);

-- INSERT purchase_makes data
insert into purchase_makes values(1,34.23, "admin0", "Bread");
insert into purchase_makes values(2,23.56, "admin3", "Linen");
insert into purchase_makes values(3,30.01, "admin4", "Canned Soup");
insert into purchase_makes values(4,65.98, "admin0", "Tampons");
insert into purchase_makes values(5,77.02, "admin0", "Pasta");

-- INSERT shift_logdelete data
insert into shift_logDelete values("admin0", 10:45:00, 1.25, "A");
insert into shift_logDelete values("admin0", 12:45:00, 2.50, "A");
insert into shift_logDelete values("admin3", 08:45:00, 1.25, "A");
insert into shift_logDelete values("admin1", 10:15:00, 3.50, "A");
insert into shift_logDelete values("admin1", 13:45:00, 4.00, "A");

-- INSERT works data
insert into works values (10:45:00, 1.25, "A", "volunteer0");
insert into works values (12:45:00, 2.50, "A", "volunteer1");
insert into works values (08:45:00, 1.25, "A", "volunteer2");
insert into works values (10:15:00, 3.50, "A", "volunteer3");
insert into works values (13:45:00, 4.00, "A", "volunteer4");

-- INSERT volunteer_add data
insert into volunteer_add values ("volunteer1", "admin0");
insert into volunteer_add values ("volunteer2", "admin1");
insert into volunteer_add values ("volunteer3", "admin1");
insert into volunteer_add values ("volunteer4", "admin0");
insert into volunteer_add values ("volunteer5", "admin1");

-- INSERT administrator data
insert into administrator values ("admin0");
insert into administrator values ("admin1");
insert into administrator values ("admin2");
insert into administrator values ("admin3");
insert into administrator values ("admin4");

-- INSERT Employee data
insert into employee values ("admim0", 6045939281, "Test User", "Password");
insert into employee values ("volunteer1", 6048392860, "Test Volun1", "PAssword");
insert into employee values ("volunteer2", 6048271843, "Test Volun2", "PaSsword");
insert into employee values ("volunteer3", 6049402281, "Test Volun3", "PasSword");
insert into employee values ("volunteer4", 6041111111, "Test Volun4", "PassWord");

-- INSERT expireson data
insert into expireson values("Bread", TO_DATE('2019-04-12', 'YYYY-MM-DD'));
insert into expireson values("Linen", TO_DATE('1983-01-29', 'YYYY-MM-DD'));
insert into expireson values("Vegetable Oil", TO_DATE('2020-03-04', 'YYYY-MM-DD'));
insert into expireson values("Pasta", TO_DATE('2004-04-4', 'YYYY-MM-DD'));
insert into expireson values("Canned Beans", TO_DATE('2023-12-07', 'YYYY-MM-DD'));

-- INSERT expirationDate data
insert into expirationDate values(TO_DATE('2019-04-12', 'YYYY-MM-DD'));
insert into expirationDate values(TO_DATE('1983-01-29', 'YYYY-MM-DD'));
insert into expirationDate values(TO_DATE('2020-03-04', 'YYYY-MM-DD'));
insert into expirationDate values(TO_DATE('2004-04-4', 'YYYY-MM-DD'));
insert into expirationDate values(TO_DATE('2023-12-07', 'YYYY-MM-DD'));

-- INSERT shfit data
insert into shift values(10:45:00, 1.25, "A");
insert into shift values(12:45:00, 2.50, "A");
insert into shift values(13:00:00, 1.00, "A");
insert into shift values(15:00:00, 2.00, "A");
insert into shift values(14:30:00, 2.15, "A");


-- INSERT item data
insert into item values("Bread", "Food", TO_DATE('2019-04-12', 'YYYY-MM-DD'), "PantryC");
insert into item values("Linen", "Other", TO_DATE('1983-01-29', 'YYYY-MM-DD'), "PantryB");
insert into item values("Vegetable Oil", "Food", TO_DATE('2020-03-04', 'YYYY-MM-DD'), "PantryA");
insert into item values("Pasta", "Food", TO_DATE('2004-04-4', 'YYYY-MM-DD'), "PantryA");
insert into item values("Canned Beans", "Food", TO_DATE('2023-12-07', 'YYYY-MM-DD'), "PantryA");
