DROP DATABASE SSP2_Assignment01;
CREATE DATABASE SSP2_Assignment01;
use SSP2_Assignment01;
SET GLOBAL sql_mode = STRICT_ALL_TABLES;

CREATE table sCategory (
	id INT(10) AUTO_INCREMENT,
	name VARCHAR(40),
	CONSTRAINT category_pk PRIMARY KEY (id)
);

CREATE table sProduct (
	id INT(10) AUTO_INCREMENT,
	name VARCHAR(20),
	description VARCHAR(255),
	price DECIMAL(6, 2),
	image VARCHAR(255),
	category INT(10),
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT product_fk FOREIGN KEY (category) REFERENCES sCategory(id),
	CONSTRAINT product_pk PRIMARY KEY (id)
);

CREATE table sUser (
	id INT(10) AUTO_INCREMENT,
	first_name VARCHAR(20) NOT NULL,
	last_name VARCHAR(20) NOT NULL,
	email VARCHAR(50) NOT NULL,
	username VARCHAR(50) UNIQUE NOT NULL,
	password VARCHAR(250) NOT NULL,
	CONSTRAINT users_pk PRIMARY KEY (id)
);

CREATE table sOrder (
	id INT(10) AUTO_INCREMENT,
	ordered_by INT(10) NOT NULL,
	date_ordered TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	order_total INT(10),
	recipient_first_name VARCHAR(20) NOT NULL,
	recipient_last_name VARCHAR(20) NOT NULL,
	CONSTRAINT order_user_fk FOREIGN KEY (ordered_by) REFERENCES sUser(id),
	CONSTRAINT order_pk PRIMARY KEY (id)
);

CREATE table sAddress (
	id INT(10) AUTO_INCREMENT,
	user_id INT(10) UNIQUE,
	order_id INT(10) UNIQUE,
	houseName VARCHAR(40),
	street VARCHAR(40),
	town VARCHAR(40),
	county VARCHAR(40),
	country VARCHAR(40),
	zipCode VARCHAR(40),
	CONSTRAINT address_order_fk FOREIGN KEY (order_id) REFERENCES sOrder(id),
	CONSTRAINT address_user_fk FOREIGN KEY (user_id) REFERENCES sUser(id),
	CONSTRAINT address_pk PRIMARY KEY (id)
);

CREATE table sOrder_items (
	order_id INT(10) NOT NULL,
	product_id INT(10) NOT NULL,
	product_name VARCHAR(20),
	number_items INT(10) DEFAULT 1,
	selling_price DECIMAL(6, 2),
	CONSTRAINT orderItems_order_fk FOREIGN KEY (order_id) REFERENCES sOrder(id),
	CONSTRAINT orderItems_productId_fk FOREIGN KEY (product_id) REFERENCES sProduct(id),
	CONSTRAINT orderitems_pk PRIMARY KEY(order_id, product_id)
);

INSERT INTO sCategory(name) VALUES("All Products");
INSERT INTO sCategory(name) VALUES("Teddy Bears");
INSERT INTO sCategory(name) VALUES("Toy Cars");
INSERT INTO sCategory(name) VALUES("Dolls");
INSERT INTO sProduct(name, description, price, image, category) VALUES("Twin Teddy Bears", "Honey and Brown bears", 22.00, "twin-teddy-bears.png", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Teddy Bear", "Brown cuddly bear", 12.00, "teddy-bear.png", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Giraffe", "Combination teething ring", 18.00, "giraffe.jpg", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Elmo", "Seasme Street", 19.00, "elmo.png", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Monkey", "Talking monkey", 14.00, "monkey.jpg", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Yoda", "Star Wars", 22.00, "yoda.jpg", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Minion", "Despicable Me", 12.00, "minion.png", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Car", "Remote controlled car", 10.00, "car.jpg", 3);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Truck", "Concrete Mixer", 15.00, "truck.jpg", 3);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Blocks Car", "Build and race", 8.00, "blocks-car.png", 3);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Racing Car", "With adjustable headlights", 13.00, "racing-car.jpg", 3);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Mini Cooper", "Replica Mini Cooper S", 11.00, "mini-cooper.png", 3);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Blue Racer", "Wind up car", 16.00, "blue-racer.jpg", 3);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Anna Doll", "Handcrafted doll", 14.00, "anna-doll.png", 4);
INSERT INTO sProduct(name, description, price, image, category) VALUES("My First Doll", "Soft plush doll", 8.00, "my-first-dolly.png", 4);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Belle", "Beauty and the Beast", 12.00, "belle-doll.jpg", 4);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Baby Born", "Comes with 11 accessories", 15.00, "baby-born.jpg", 4);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Jessie Doll", "Toy Story", 16.00, "jessie-doll.jpg", 4);
INSERT INTO sProduct(name, description, price, image) VALUES("Furby", "Interactive Pet", 12.00, "furby.png");
INSERT INTO sUser(first_name, last_name, email, username, password) VALUES("Laura", "Pigott", "pigottlaura@gmail.com", "pigottlaura", SHA1("test"));
INSERT INTO sAddress(user_id, houseName, street, town, county , country, zipCode) VALUES(1, "Angel Heights", "Dromleigh South", "Bantry", "Cork", "Ireland", "XN11254");