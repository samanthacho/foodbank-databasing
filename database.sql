set FOREIGN_KEY_CHECKS=0;
drop table money_collects; --
drop table item_collects;--
drop table f_has;
drop table collection_work;--
drop table distribution_work;--
drop table purchase_makes;
drop table volunteer_add;--
drop table admin; -- pk in table referenced by fk
drop table expiresOn;
drop table food_g;
drop table expirationDate; -- pk ref fk
drop table item_Table; 
drop table employee; -- pk ref by fk
drop table shift;

create table item_Table(
	-- ERROR: ORA-00955: name is already used by an existing object
	name varchar2(30) not null primary key,
	category varchar2(30),
	location varchar2(30) not null
);

grant select on item_Table to public;

create table expirationDate(
	-- ERROR: ORA-00955: name is already used by an existing object
	exDate number not null primary key
);

grant select on expirationDate to public;

create table employee( 
	-- ERROR: ORA-00955: name is already used by an existing object
	username varchar2(30) not null,						
	phone number,
	name varchar2(30),
	password varchar2(30),
	primary key (username)
);

grant select on employee to public;

create table expiresOn( 						
	id varchar2(255) not null,
	name varchar2(30),
	exdate number,
	primary key (id)
);

grant select on expiresOn to public;

create table money_collects( 					
	did varchar2(255) not null,
	dname varchar2(30),
	dphone number,
	moneydate date not null,
	username varchar2(30) not null, 
-- ERROR ; ORA-00906: missing left parenthesis 
	amount decimal(10,2) not null,
	medium varchar2(30) not null,
	primary key (did),
	foreign key (username) references employee (username) ON DELETE CASCADE ON UPDATE CASCADE
	);

grant select on money_collects to public;
	-- EROOR: MONEY_COLLECTS DOESNT EXIST

create table item_collects(
	did varchar2(255) not null,
	name varchar2(30),
	phone number,
	itemdate date,
	username varchar2(30) not null,
	item varchar2(30) not null,
	primary key (did),
	foreign key (username) references employee (username) ON UPDATE CASCADE
	--ERROR: RA-00905: missing keyword


);

grant select on item_collects to public; -- ERORR; TABLE DOESNT EXIST

create table food_g (
	-- ERORR: ORA-00955: name is already used by an existing object
	value varchar2(30) not null primary key
);

grant select on food_g to public;

create table f_has (
	value varchar2(30) not null,
	name varchar2(30) not null,
	primary key(value,name),
	foreign key(value) references food_g (value),
	foreign key(name) references item_Table (name)
);

grant select on f_has to public;

create table collection_work (
	startTime number not null,
	length decimal(5,2) not null,
	letter varchar2(4) not null,
	sdate date not null,
	username varchar2(30) not null,
	primary key (startTime,length,letter, sdate),
	foreign key(startTime,length,letter,sdate) references shift (startTime,length,letter,sdate) ON DELETE CASCADE ON UPDATE CASCADE,
	-- ERROR: on update 00907: missing right parenthesis
	foreign key (username) references employee (username) ON UPDATE CASCADE ON DELETE CASCADE
);

grant select on collection_work to public;
	-- EROOR: DNE

create table distribution_work (
	startTime number not null,
	length decimal(5,2) not null,
	letter char(4) not null,
	sdate date not null,
	username varchar2(30) not null,
	primary key(startTime,length,letter,sdate),
	foreign key(startTime,length,letter,sdate) references shift (startTime,length,letter,sdate) ON DELETE CASCADE,
	-- error table dne
	foreign key (username) references employee (username) ON DELETE CASCADE
);

grant select on distribution_work to public; -- ERORR; DNE

create table purchase_makes(
	pid varchar2(255) not null primary key,
	pamount decimal(10,2) not null,
	userName varchar2(30) not null,
	item varchar2(30) not null,
	foreign key(userName) references admin (userName)
);

grant select on purchase_make to public;

create table shift (  						
	startTime number not null,
	length decimal(5,2) not null,
	letter varchar2(4) not null,
	sdate date not null,
	primary key(startTime,length,letter,sdate)
);

grant select on shift to public;

create table volunteer_add(
	v_uname varchar2(30) not null primary key
	userName varchar2(30) not null,
	-- erorr; missing right parenthesis
	foreign key(v_uname)references employee (userName) ON DELETE CASCADE,
	foreign key(userName) references admin (userName) ON DELETE CASCADE
);

grant select on volunteer_add to public;

create table admin (
	-- ERORR; ORA-00955: name is already used by an existing object
	userName varchar2(30) not null,
	primary key(userName),
	foreign key (userName) references employee (userName) ON DELETE CASCADE
);

grant select on admin to public;

-- INSERT item_Table data
-- ERROR: Location column not allowed there??
insert into item_Table values("Bread", "Food",  "PantryC");
insert into item_Table values("Linen", "Other", "PantryB");
insert into item_Table values("Vegetable Oil", "Food", "PantryA");
insert into item_Table values("Pasta", "Food", "PantryA");
insert into item_Table values("Canned Beans", "Food", "PantryA");

-- INSERT expirationDate data
-- eroor EXPECT DATE GOT NUMBER??? but we want number
insert into expirationDate values(20190412);
insert into expirationDate values(19830129);
insert into expirationDate values(20200304);
insert into expirationDate values(2004044);
insert into expirationDate values(20231207);

-- INSERT Employee data
-- PASSWORD NO COLUMN 
insert into employee values ("admim0", 6045939281, "Test User", "Password");
insert into employee values ("admim1", 6045939281, "Test User", "Password");
insert into employee values ("admim2", 6045939281, "Test User", "Password");
insert into employee values ("volunteer1", 6048392860, "Test Volun1", "PAssword");
insert into employee values ("volunteer2", 6048271843, "Test Volun2", "PaSsword");
insert into employee values ("volunteer3", 6049402281, "Test Volun3", "PasSword");
insert into employee values ("volunteer4", 6041111111, "Test Volun4", "PassWord");

-- INSERT expireson data
-- ERORR BREAD COLUMN DNE
insert into expireson values("1", "Bread", 20190412);
insert into expireson values("2","Linen", 19830129);
insert into expireson values("3","Vegetable Oil",20200304);
insert into expireson values("4","Pasta", 2004044);
insert into expireson values("5","Canned Beans",20231207);

-- INSERT money_collects data
-- ERROR TABLE DNE
insert into money_collects values("1","John Smith", 6041234567,2018-05-31,"volunteer1", 100.25, "credit");
insert into money_collects values("2","Jane Doe", 6048463847,2018-05-31,"volunteer4", 125.25, "cash");
insert into money_collects values("4","Chad Smith", 7788297482,2018-12-03,"volunteer2", 110.25, "credit");
insert into money_collects values("6","Sarah Lee", 8391848294,2015-01-13,"volunteer3", 200.00, "cash");
insert into money_collects values("7","Amber Chan", 7784920412,2000-07-23,"volunteer4",135.00, "cash");

-- INSERT item_collects data
-- ERROR TABLE DNE
insert into item_collects values("3","John Doe", 6048593123,2018-01-01,"volunteer1","Bread");
insert into item_collects values("5","Jessica Hope", 77892847293,2018-02-10,"volunteer1","Canned Pineapple");
insert into item_collects values("8","Jack Ko", 2048573859,2017-08-11,"volunteer2","Vegetable Oil");
insert into item_collects values("9","Liz Apple", 4739174824,2018-05-12, "volunteer3","Pasta");
insert into item_collects values("10","Nick Brown ", 6045829104,2018-11-10,"volunteer3","Canned Beans");

-- INSERT food_g data
-- ERROR column DNE 
insert into food_g values("Carbohydrates");
insert into food_g values("Other");
insert into food_g values("Vegetable Oil");
insert into food_g values("Carbohydrates");
insert into food_g values("Protein");

-- INSERT f_has data 
-- ERROR column BREAD dne
insert into f_has values("Carbohydrates","Bread");
insert into f_has values("Other", "Linen" );
insert into f_has values("Fats","Vegetable Oil" );
insert into f_has values("Carbohydrates","Pasta");
insert into f_has values("Protein","Canned Beans");


-- INSERT collection_work data
-- ERROR TABLE DNE
insert into collection_work values(1045, 1.25, "A", 2018-01-01, "volunteer1");
insert into collection_work values(1245, 2.50, "A",2018-02-10, "volunteer1");
insert into collection_work values(1300, 1.00, "A",2017-08-11,"volunteer2");
insert into collection_work values(1500, 2.00, "A",2018-05-12,"volunteer3");
insert into collection_work values(1430, 2.15, "A",2018-11-10,"volunteer4");

-- INSERT distribution_work data
-- ERROR TABLE DNE
insert into distribution_work values(2045, 1.25, "B", 2018-01-01, "volunteer1");
insert into distribution_work values(2045, 2.50, "C",2018-02-10, "volunteer1");
insert into distribution_work values(2000, 1.00, "D",2017-08-11,"volunteer2");
insert into distribution_work values(2000, 2.00, "E",2018-05-12,"volunteer3");
insert into distribution_work values(2030, 2.15, "F",2018-11-10,"volunteer4");


-- INSERT purchase_makes data
-- ERROR BREAD COLUMN DNE
insert into purchase_makes values("1",34.23, "admin0", "Bread");
insert into purchase_makes values("2",23.56, "admin1", "Linen");
insert into purchase_makes values("3",30.01, "admin2", "Canned Soup");
insert into purchase_makes values("4",65.98, "admin0", "Tampons");
insert into purchase_makes values("5",77.02, "admin0", "Pasta");

-- INSERT shift data
-- ERROR letter COLUMN DNE
insert into shift values(1045, 1.25, "A",2018-01-01);
insert into shift values(1245, 2.50, "A",2018-02-10);
insert into shift values(1300, 1.00, "A",2017-08-11);
insert into shift values(1500, 2.00, "A",2018-05-12);
insert into shift values(1430, 2.15, "A",2018-11-10);
-- distribution shift
insert into shift values(2045, 1.25, "B", 2018-01-01);
insert into shift values(2045, 2.50, "C",2018-02-10);
insert into shift values(2000, 1.00, "D",2017-08-11);
insert into shift values(2000, 2.00, "E",2018-05-12);
insert into shift values(2030, 2.15, "F",2018-11-10);

-- INSERT volunteer_add data
-- ERROR TABLE DNE
insert into volunteer_add values ("volunteer1", "admin0");
insert into volunteer_add values ("volunteer2", "admin1");
insert into volunteer_add values ("volunteer3", "admin2");
insert into volunteer_add values ("volunteer4", "admin0");

-- INSERT admin data
-- ERROR COLUMN DNE
insert into admin values ("admin0");
insert into admin values ("admin1");
insert into admin values ("admin2");
