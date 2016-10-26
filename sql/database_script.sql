DROP DATABASE SSP2_Assignment01;
CREATE DATABASE SSP2_Assignment01;
use SSP2_Assignment01;

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
	category INT(10) DEFAULT 1,
	CONSTRAINT product_fk FOREIGN KEY (category) REFERENCES sCategory(id),
	CONSTRAINT product_pk PRIMARY KEY (id)
);

CREATE table sUser (
	id INT(10) AUTO_INCREMENT,
	first_name VARCHAR(20) NOT NULL,
	last_name VARCHAR(20) NOT NULL,
	email VARCHAR(50) NOT NULL,
	username VARCHAR(50) UNIQUE NOT NULL,
	password VARCHAR(250) UNIQUE NOT NULL,
	CONSTRAINT users_pk PRIMARY KEY (id)
);

CREATE table sAddress (
	id INT(10) AUTO_INCREMENT,
	user_id INT(10) NOT NULL,
	address_houseNumber INT(4),
	address_houseName VARCHAR(40),
	address_street VARCHAR(40),
	address_town VARCHAR(40),
	address_county VARCHAR(40),
	address_country VARCHAR(40),
	address_zipCode VARCHAR(40),
	CONSTRAINT address_user_fk FOREIGN KEY (user_id) REFERENCES sUser(id),
	CONSTRAINT address_pk PRIMARY KEY (id)
);

CREATE table sOrder (
	id INT(10) AUTO_INCREMENT,
	ordered_by INT(10) NOT NULL,
	date_ordered TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	order_total INT(10),
	CONSTRAINT order_fk FOREIGN KEY (ordered_by) REFERENCES sUser(id),
	CONSTRAINT order_pk PRIMARY KEY (id)
);

CREATE table sOrder_items (
	order_id INT(10) NOT NULL,
	product_id INT(10) NOT NULL,
	number_items INT(10) DEFAULT 1,
	CONSTRAINT orderItems_order_fk FOREIGN KEY (order_id) REFERENCES sOrder(id),
	CONSTRAINT orderItems_productId_fk FOREIGN KEY (product_id) REFERENCES sProduct(id),
	CONSTRAINT orderitems_pk PRIMARY KEY(order_id, product_id)
);

INSERT INTO sCategory(name) VALUES("All Products");
INSERT INTO sCategory(name) VALUES("Teddy Bears");
INSERT INTO sCategory(name) VALUES("Toy Cars");
INSERT INTO sProduct(name, description, price, image, category) VALUES("Teddy Bear", "Brown cuddly bear", 12.00, "teddy-bear.png", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Car", "", 10.00, "car.jpg", 3);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Giraffe", "", 18.00, "giraffe.jpg", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Monkey", "", 14.00, "monkey.jpg", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Minion", "", 12.00, "minion.png", 2);
INSERT INTO sProduct(name, description, price, image, category) VALUES("Furby", "", 12.00, "furby.png", 1);
INSERT INTO sUser(first_name, last_name, email, username, password) VALUES("Laura", "Pigott", "pigottlaura@gmail.com", "pigottlaura", SHA1("test"));
INSERT INTO sAddress(user_id, address_houseName, address_street, address_town, address_county , address_country, address_zipCode) VALUES(1, "Angel Heights", "Dromleigh South", "Bantry", "Cork", "Ireland", "XN11254");