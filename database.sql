drop table money_collects;
drop tableitem_collects;
drop table is;
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



create table money_collects(	
	did int,
	name varchar(40),
	phone char(10),
	moneydate date,
	startTime float(53) not null,
	length float(53) not null,
	letter char(1) not null,
	amount float(53),
	medium varchar(255),
	primary key (did),
	foreign key (startTime,length,letter) references collection_shift (startTime,length,letter)
	ON DELETE CASCADE, ON UPDATE CASCADE
	);

grant select on money_collects to public;

create table item_collects(
	did int,
	name varchar(40),
	phone char(10),
	itemdate date,
	startTime float(53)  not null,
	length float not null,
	letter char(1) not null,
	primary key (did),
	foreign key (startTime,length,letter) references collection_shift (startTime,length,letter) ON UPDATE CASCADE
);

grant select on item_collects to public;

create table is(
	string varchar(255),
	did int,
	primary key (string,did),
	foreign key(string) references item (string) ON UPDATE CASCADE,
	foreign key (did) references item_donation (did) ON UPDATE CASCADE,
);

grant select on is to public;

create table item_distributes(
	string varchar(255),
	category varchar(255),
	location varchar(255),
	startTime float(53) ,
	length float(53) 
	letter char(1),
	primary key (string)
	foreign key(startTime,length,letter) references distribution_shift (startTime,length,letter) ON UPDATE CASCADE
);

grant select on item_distributes to public;

create table group (
	value varchar(255) primary key
);

grant select on group to public;

create table has (
	value varchar(255),
	string varchar(255),
	primary key(value,string),
	foreign key(value) references group (value),
	foreign key(string) references item (string)
);

grant select on has to public;

create table collection (
	startTime float(53) ,
	length float(53) ,
	letter char(1),
	primary key (startTime,length,letter),
	foreign key(startTime,length,letter) references shift (startTime,length,letter) ON UPDATE CASCADE
);

grant select on collection to public;

create table distribution(
	startTime float(53) ,
	length float(53) ,
	length char(1),
	primary key(startTime,length,letter),
	foreign key(startTime,length,letter) references shift (startTime,length,letter) ON UPDATE CASCADE
);

grant select on distribution to public;

create table adds(
	string varchar(255),
	purchaseID int,
	primary key(string,purchaseID),
	foreign key(string) references item(string),
	foreign key(purchaseID) references purchase (purchaseID)
);

grant select on adds to public;

create table purchase_makes(
	purchaseID int primary key,
	amount float(53) ,
	userName varchar(255),
	item varchar(255),
	foreign key(userName) references admin (userName)
);

grant select on purchase_makes to public;

create table shift_logDelete(
	userName varchar(255) not null,
	startTime float(53) ,
	length float(53) ,
	letter char(1),
	primary key(startTime,length,letter),
	foreign key (userName) references admin (userName) ON UPDATE CASCADE
);

grant select on shift_logDelete to public;

create table works(
	startTime float(53) ,
	length float(53) ,
	userName varchar(255),
	letter char(1),
	primary key (startTime,length,letter,userName),
	foreign key (startTime,length,letter) references shift(startTime,length,letter),
	foreign key (userName) references volunteer(userName)

);

grant select on  works to public;

create table volunteer_add(
	userName varchar(255) primary key
	a_userName varchar(255),
	foreign key(userName)references employee (userName) ON UPDATE CASCADE,
	foreign key(a_userName) references administrator(a_userName) ON UPDATE CASCADE
);

grant select on volunteer_add to public;

create table administrator(
	userName varchar(255),
	primary key(userName),
	foreign key (userName) references employee (userName) ON UPDATE CASCADE
);

grant select on administrator to public;

create table employee(
	userName varchar(255) primary key,
	phone char(13),
	name varchar(255),
	password varchar(255),
);

grant select on employee to public;

create table expiresOn(
	string varchar(255),
	expiryDate date,
	primary key (string, exDate),
	foreign key (string) references item (string),
	foreign key (expiryDate) references expirationDate (exDate)
	);
grant select on expiresOn to public;

create table expirationDate(
	exDate date primary key
);

grant select on expirationDate to public;

create table shift (
	startTime float(53) ,
	length float(53) ,
	letter char(1),
	primary key (startTime, length,letter)

);

grant select on shift to public;

create table item(
	string varchar primary key,
	category varchar(255),
	itemdate date,
	location varchar(255),
	foreign key (itemdate) references expirationDate (exDate)
);

grant select on item to public;

insert into money_collects values (1,"John Smith", 6041234567,DATE, 100, "credit",)

