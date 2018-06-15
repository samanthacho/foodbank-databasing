
drop table money_collect;
drop table item_collects;
drop table f_has;
drop table collection_work;
drop table distribution_work;
drop table purchase_make;
drop table volunteer_add;
drop table admin;
drop table expiresOn;
drop table food_g;
drop table item;
drop table adreport;
drop table shift;
drop table expirationDate;
drop table employee;

create table adreport(
	prep varchar(255) not null,
	username varchar(30),
	action varchar(255)
);

create table item (
	name varchar(30) not null primary key,
	category varchar(30),
	location varchar(30) not null
);
grant select on item to public;


create table food_g (
	value varchar(30) not null primary key
);
grant select on food_g to public;


create table shift (
	startTime number not null,
	length decimal(5,2) not null,
	letter varchar(4) not null,
	sdate date not null,
	primary key(startTime,length,letter,sdate)
);
grant select on shift to public;

create table expirationDate(
	exDate number not null primary key
);
grant select on expirationDate to public;

create table employee(
	username varchar(30) not null,
	phone number,
	name varchar(30),
	password varchar(30),
	check (length(phone)=10),
	primary key (username)
);
grant select on employee to public;


create table expiresOn(
	id varchar(255) not null,
	name varchar(30),
	exdate number,
	primary key (id),
	check (length(exdate)=8)
);
grant select on expiresOn to public;

create table money_collect(
	did varchar(255) not null,
	dname varchar(30),
	dphone number,
	moneydate date not null,
	username varchar(30) not null,
	amount decimal(10,2) not null,
	medium varchar(30) not null,
	primary key (did),
	foreign key (username) references employee ON DELETE CASCADE
);
grant select on money_collect to public;


create table item_collects(
	did varchar(255) not null,
	name varchar(30),
	phone number,
	itemdate date,
	username varchar(30) not null,
	item varchar(30) not null,
	primary key (did),
	foreign key (username) references employee (username)
);
grant select on item_collects to public;

create table admin (
	userName varchar(30) not null,
	primary key(userName),
	foreign key (userName) references employee (userName) ON DELETE CASCADE
);
grant select on admin to public;


create table purchase_make (
	pid varchar(255) not null primary key,
	pamount decimal(10,2) not null,
	userName varchar(30),
	item varchar(30) not null,
	foreign key(userName) references admin (userName) ON DELETE SET NULL
);
grant select on purchase_make to public;


create table f_has (
	value varchar(30) not null,
	name varchar(30) not null,
	primary key(value,name),
	foreign key(value) references food_g (value),
	foreign key(name) references item (name)
);
grant select on f_has to public;

create table collection_work (
	startTime number not null,
	length decimal(5,2) not null,
	letter varchar(4) not null,
	sdate date not null,
	username varchar(30) not null,
	primary key (startTime,length,letter, sdate),
	foreign key(startTime,length,letter,sdate) references shift ON DELETE CASCADE,
	foreign key (username) references employee (username) ON DELETE CASCADE
);
grant select on collection_work to public;


create table distribution_work (
	startTime number not null,
	length decimal(5,2) not null,
	letter varchar(4) not null,
	sdate date not null,
	username varchar(30) not null,
	primary key(startTime,length,letter,sdate),
	foreign key(startTime,length,letter,sdate) references shift ON DELETE CASCADE,
	foreign key (username) references employee (username) ON DELETE CASCADE
);
grant select on distribution_work to public; -- ERORR; DNE

create table volunteer_add(
	v_uname varchar(30) not null primary key,
	userName varchar(30),
	foreign key(v_uname)references employee (userName) ON DELETE CASCADE,
	foreign key(userName) references admin (userName) ON DELETE SET NULL
);
grant select on volunteer_add to public;


-- INSERT adreport data
insert into adreport values('prep', 'volunteer1', 'sorting');
insert into adreport values('prep', 'volunteer2', 'cleaning');



-- INSERT shift data
-- ERROR letter COLUMN DNE
insert into shift values(1045, 1.25, 'A',TO_DATE('2018-01-01','YYYY-MM-DD'));
insert into shift values(1245, 2.50, 'A',TO_DATE('2018-02-10','YYYY-MM-DD'));
insert into shift values(1300, 1.00, 'A',TO_DATE('2017-08-11','YYYY-MM-DD'));
insert into shift values(1500, 2.00, 'A',TO_DATE('2018-05-12','YYYY-MM-DD'));
insert into shift values(1430, 2.15, 'A',TO_DATE('2018-11-10','YYYY-MM-DD'));
-- distribution shift
insert into shift values(2045, 1.25, 'B', TO_DATE('2018-01-01','YYYY-MM-DD'));
insert into shift values(2045, 2.50, 'C',TO_DATE('2018-02-10','YYYY-MM-DD'));
insert into shift values(2000, 1.00, 'D',TO_DATE('2017-08-11','YYYY-MM-DD'));
insert into shift values(2000, 2.00, 'E',TO_DATE('2018-05-12','YYYY-MM-DD'));
insert into shift values(2030, 2.15, 'F',TO_DATE('2018-11-10','YYYY-MM-DD'));


-- INSERT Employee data
-- PASSWORD NO COLUMN
insert into employee values ('admin0', 6045939281, 'Test User', 'Password');
insert into employee values ('admin1', 6045939281, 'Test User', 'Password');
insert into employee values ('admin2', 6045939281, 'Test User', 'Password');
insert into employee values ('volunteer1', 6048392860, 'Test Volun1', 'PAssword');
insert into employee values ('volunteer2', 6048271843, 'Test Volun2', 'PaSsword');
insert into employee values ('volunteer3', 6049402281, 'Test Volun3', 'PasSword');
insert into employee values ('volunteer4', 6041111111, 'Test Volun4', 'PassWord');


-- INSERT item data
-- ERROR: Location column not allowed there??
insert into item values('Bread', 'Food',  'PantryC');
insert into item values('Linen', 'Other', 'PantryB');
insert into item values('Vegetable Oil', 'Food', 'PantryA');
insert into item values('Pasta', 'Food', 'PantryA');
insert into item values('Canned Beans', 'Food', 'PantryA');

-- INSERT food_g data
-- ERROR column DNE
insert into food_g values('Carbohydrates');
insert into food_g values('Other');
insert into food_g values('Vegetable Oil');
insert into food_g values('Fats');
insert into food_g values('Protein');


-- INSERT expireson data
-- ERORR BREAD COLUMN DNE
insert into expireson values('1', 'Bread', 20190412);
insert into expireson values('2','Linen', 19830129);
insert into expireson values('3','Vegetable Oil',20200304);
insert into expireson values('4','Pasta', 20040404);
insert into expireson values('5','Canned Beans',20231207);


-- INSERT admin data
-- ERROR COLUMN DNE
insert into admin values ('admin0');
insert into admin values ('admin1');
insert into admin values ('admin2');


-- INSERT volunteer_add data
-- ERROR TABLE DNE
insert into volunteer_add values ('volunteer1', 'admin0');
insert into volunteer_add values ('volunteer2', 'admin1');
insert into volunteer_add values ('volunteer3', 'admin2');
insert into volunteer_add values ('volunteer4', 'admin0');


-- INSERT purchase_make data
-- ERROR BREAD COLUMN DNE
insert into purchase_make values('1',34.23, 'admin0', 'Bread');
insert into purchase_make values('2',23.56, 'admin1', 'Linen');
insert into purchase_make values('3',30.01, 'admin2', 'Canned Soup');
insert into purchase_make values('4',65.98, 'admin0', 'Tampons');
insert into purchase_make values('5',77.02, 'admin0', 'Pasta');

-- INSERT distribution_work data
-- ERROR TABLE DNE
insert into distribution_work values(2045, 1.25, 'B', TO_DATE('2018-01-01','YYYY-MM-DD'), 'volunteer1');
insert into distribution_work values(2045, 2.50, 'C',TO_DATE('2018-02-10','YYYY-MM-DD'), 'volunteer1');
insert into distribution_work values(2000, 1.00, 'D',TO_DATE('2017-08-11','YYYY-MM-DD'),'volunteer2');
insert into distribution_work values(2000, 2.00, 'E',TO_DATE('2018-05-12','YYYY-MM-DD'),'volunteer3');
insert into distribution_work values(2030, 2.15, 'F',TO_DATE('2018-11-10','YYYY-MM-DD'),'volunteer4');





-- INSERT collection_work data
-- ERROR TABLE DNE
insert into collection_work values(1045, 1.25, 'A',TO_DATE('2018-01-01','YYYY-MM-DD'), 'volunteer1');
insert into collection_work values(1245, 2.50, 'A',TO_DATE('2018-02-10','YYYY-MM-DD'), 'volunteer1');
insert into collection_work values(1300, 1.00, 'A',TO_DATE('2017-08-11','YYYY-MM-DD'),'volunteer2');
insert into collection_work values(1500, 2.00, 'A',TO_DATE('2018-05-12','YYYY-MM-DD'),'volunteer3');
insert into collection_work values(1430, 2.15, 'A',TO_DATE('2018-11-10','YYYY-MM-DD'),'volunteer4');




-- INSERT f_has data
-- ERROR column BREAD dne
insert into f_has values('Carbohydrates','Bread');
insert into f_has values('Other', 'Linen' );
insert into f_has values('Fats','Vegetable Oil' );
insert into f_has values('Carbohydrates','Pasta');
insert into f_has values('Protein','Canned Beans');




-- INSERT item_collects data
-- ERROR TABLE DNE
insert into item_collects values('3','John Doe', 6048593123,TO_DATE('2018-01-01', 'YYYY-MM-DD'),'volunteer1','Bread');
insert into item_collects values('5','Jessica Hope', 77892847293,TO_DATE('2018-02-10', 'YYYY-MM-DD'),'volunteer1','Canned Pineapple');
insert into item_collects values('8','Jack Ko', 2048573859,TO_DATE('2017-08-11', 'YYYY-MM-DD'),'volunteer2','Vegetable Oil');
insert into item_collects values('9','Liz Apple', 4739174824,TO_DATE('2018-05-12', 'YYYY-MM-DD'), 'volunteer3','Pasta');
insert into item_collects values('10','Nick Brown ', 6045829104,TO_DATE('2018-11-10', 'YYYY-MM-DD'),'volunteer3','Canned Beans');

-- INSERT expirationDate data
insert into expirationDate values(20190412);
insert into expirationDate values(19830129);
insert into expirationDate values(20200304);
insert into expirationDate values(2004044);
insert into expirationDate values(20231207);



-- INSERT money_collect data
-- ERROR TABLE DNE
insert into money_collect values('1','John Smith', 6041234567,TO_DATE('2018-05-31', 'YYYY-MM-DD'),'volunteer1', 100.25, 'credit');
insert into money_collect values('2','Jane Doe', 6048463847,TO_DATE('2018-05-31','YYYY-MM-DD'),'volunteer4', 125.25, 'cash');
insert into money_collect values('4','Chad Smith', 7788297482,TO_DATE('2018-12-03', 'YYYY-MM-DD'),'volunteer2', 110.25, 'credit');
insert into money_collect values('6','Sarah Lee', 8391848294,TO_DATE('2015-01-13', 'YYYY-MM-DD'),'volunteer3', 200.00, 'cash');
insert into money_collect values('7','Amber Chan', 7784920412,TO_DATE('2000-07-23', 'YYYY-MM-DD'),'volunteer4',135.00, 'cash');


COMMIT;
