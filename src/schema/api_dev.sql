create table if not exists `citizen`(
	`id` int not null auto_increment,
	`name` varchar(50) not null,
	`lastname` varchar(50) not null,
	`identification_number` varchar(11) not null unique,
	primary key(`id`)
);

create index `citizen_index`
on `citizen` ( `name` );

create table if not exists `address`(
	`id` int not null auto_increment,
	`citizen_id` int not null,
	`zip_code` varchar(10) not null unique,
	`street` varchar(50) not null,
	`neighbourhood` varchar(30) not null,
	`city` varchar(30) not null,
	`district` char(2) not null,
	primary key(`id`),
	foreign key(`citizen_id`) 
		references `citizen`(`id`)
		on delete cascade
);

create index `address_index`
on `address` ( `zip_code` );

create table if not exists `contact`(
	`id` int not null auto_increment,
	`citizen_id` int not null,
	`email` varchar(50) not null,
	`cellphone` varchar(15) not null,
	primary key(`id`),
	foreign key(`citizen_id`) 
		references `cidadao`(`id`)
		on delete cascade
);

create index `contact_index`
on `contact` ( `citizen_id`, `email` );