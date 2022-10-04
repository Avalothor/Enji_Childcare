--
--  園児 Enji childcare -- 
--


-- Table Structure for admin --

CREATE TABLE IF NOT EXISTS admin(
    email VARCHAR(40) PRIMARY KEY,
    fName VARCHAR(30) NOT NULL,
    lName VARCHAR(30) NOT NULL,
    pass VARCHAR(50) NOT NULL
);


-- Insert data in admin --

INSERT INTO admin VALUES('mert.bekar@student.griffith.ie', 'Mert', 'Bekar', '1234$Dark');
INSERT INTO admin VALUES('vinipbellomo@msn.com', 'Vinicius Pepe', 'Bellomo', '1234$Dark');
INSERT INTO admin VALUES('admin@admin.com', 'admin', 'admin', 'admin');



-- Table Structure for parent --

CREATE TABLE IF NOT EXISTS parent(
    email VARCHAR(40) PRIMARY KEY,
    fName VARCHAR(30) NOT NULL,
    lName VARCHAR(30) NOT NULL,
    pass VARCHAR(30) NOT NULL,
    address TEXT NOT NULL
);


-- Insert data in parent --

INSERT INTO parent VALUES('parent1@parent.com', 'John', 'Smith', '1234$Dark', 'Dublin 8');
INSERT INTO parent VALUES('parent2@parent.com', 'Max', 'Teberkesh', '1234$Dark', 'Kayseri');
INSERT INTO parent VALUES ('parent3@parent.com', 'Choko', 'latte', '1234$Dark', 'willy wonka');
INSERT INTO parent VALUES ('parent4@parent.com', 'Ruan', 'Cokertesh', '1234$Dark', 'not willy wonka');



-- Table structure for phone --

CREATE TABLE IF NOT EXISTS phone(
    phone VARCHAR(15) PRIMARY KEY,
    pID VARCHAR(30) NOT NULL,
    FOREIGN KEY(pID) REFERENCES parent(email)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);


-- Insert data in phone --

INSERT INTO phone VALUES('123456789', 'parent1@parent.com');
INSERT INTO phone VALUES('111111111', 'parent1@parent.com');
INSERT INTO phone VALUES('222222222', 'parent2@parent.com');
INSERT INTO phone VALUES('333333333', 'parent3@parent.com'); 



-- Table structure for service --

CREATE TABLE IF NOT EXISTS service(
    name VARCHAR(10) PRIMARY KEY
);

-- Insert data in service --

INSERT INTO service VALUES('baby');
INSERT INTO service VALUES('wobbler');
INSERT INTO service VALUES('toddler');
INSERT INTO service VALUES('preschool');



-- Table structure for testimonial --

CREATE TABLE IF NOT EXISTS testimonial(
    pID VARCHAR(30) NOT NULL,
    service VARCHAR(10) NOT NULL,
    testimony TEXT NOT NULL,
    date DATE NOT NULL,
    approved TINYINT(1),
    PRIMARY KEY(pID, service),
    FOREIGN KEY(pID) REFERENCES parent(email)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY(service) REFERENCES service(name)
    ON UPDATE CASCADE
);

-- Insert data in testimonial --

--Baby--

INSERT INTO testimonial VALUES('parent1@parent.com','baby','Baby Comment 1','2020-05-12',TRUE);
INSERT INTO testimonial VALUES('parent2@parent.com','baby','Comment comment bla bla....','2020-05-12',false);
INSERT INTO testimonial VALUES('parent3@parent.com','baby','Baby Comment 2','2020-05-12',true);
INSERT INTO testimonial VALUES('parent4@parent.com','baby','Comment comment bla bla....','2020-05-12',False);

--Wobbler--

INSERT INTO testimonial VALUES('parent1@parent.com','wobbler','Comment comment bla bla....','2020-05-12',false);
INSERT INTO testimonial VALUES('parent2@parent.com','wobbler','Wobbler Comment 1','2020-05-12',TRUE);
INSERT INTO testimonial VALUES('parent3@parent.com','wobbler','Comment comment bla bla....','2020-05-12',false);
INSERT INTO testimonial VALUES('parent4@parent.com','wobbler','Wobbler Comment 2','2020-05-12',True);

--Toddler--

INSERT INTO testimonial VALUES('parent1@parent.com','toddler','Toddler Comment 1','2020-05-12',TRUE);
INSERT INTO testimonial VALUES('parent2@parent.com','toddler','Comment comment bla bla....','2020-05-12',false);
INSERT INTO testimonial VALUES('parent3@parent.com','toddler','Toddler Comment 2','2020-05-12',TRUE);
INSERT INTO testimonial VALUES('parent4@parent.com','toddler','Comment comment bla bla....','2020-05-12',false);

--Pre-School--

INSERT INTO testimonial VALUES('parent1@parent.com','preschool','Comment comment bla bla....','2020-05-12',false);
INSERT INTO testimonial VALUES('parent2@parent.com','preschool','Pre-School Comment 1','2020-05-12',TRUE);
INSERT INTO testimonial VALUES('parent3@parent.com','preschool','Comment comment bla bla....','2020-05-12',false);
INSERT INTO testimonial VALUES('parent4@parent.com','preschool','Pre-School Comment 2','2020-05-12',TRUE);



-- Table structure for child --

CREATE TABLE IF NOT EXISTS child(
    cID INT PRIMARY KEY AUTO_INCREMENT,
    fName VARCHAR(30) NOT NULL,
    lName VARCHAR(30) NOT NULL,
    DoB DATE NOT NULL,
    pID VARCHAR(30),  
    category VARCHAR(10),                          -- If parents want to change parent account of an child, we don't want to remove child data --
    FOREIGN KEY(pID) REFERENCES parent(email)
    ON DELETE SET NULL
    ON UPDATE CASCADE,
    FOREIGN KEY(category) REFERENCES service(name)
    ON UPDATE CASCADE
);


-- Insert data in child --

INSERT INTO child VALUES('1', 'kemilnez', 'kemtirkesik','0003-03-03', 'parent1@parent.com', 'baby');
INSERT INTO child VALUES('2', 'teber', 'pet','1999-03-03','parent2@parent.com', 'wobbler');
INSERT INTO child VALUES('3', 'abdulkerim', 'bebek','2023-07-15','parent3@parent.com', 'preschool');
INSERT INTO child VALUES('4', 'kamilnaz', 'nazkamil','1234-12-1','parent4@parent.com', 'toddler');
INSERT INTO child VALUES(NULL, 'ryker', 'kemtirkesik','0003-03-03', 'parent1@parent.com', 'wobbler');
INSERT INTO child VALUES(NULL, 'stryker', 'kemtirkesik','0003-03-03', 'parent1@parent.com', 'toddler');
INSERT INTO child VALUES(NULL, 'kabal', 'kemtirkesik','0003-03-03', 'parent1@parent.com', 'preschool');
INSERT INTO child VALUES(NULL, 'kitana', 'pet','1999-03-03','parent2@parent.com', 'baby');
INSERT INTO child VALUES(NULL, 'milena', 'pet','1999-03-03','parent2@parent.com', 'toddler');
INSERT INTO child VALUES(NULL, 'jade', 'pet','1999-03-03','parent3@parent.com', 'preschool');
INSERT INTO child VALUES(NULL, 'abdulrezzak', 'bebek','2023-07-15','parent3@parent.com', 'baby');
INSERT INTO child VALUES(NULL, 'ibn-i hatil', 'bebek','2023-07-15','parent3@parent.com', 'wobbler');
INSERT INTO child VALUES(NULL, 'abdulhamit', 'bebek','2023-07-15','parent3@parent.com', 'preschool');
INSERT INTO child VALUES(NULL, 'kamilcan', 'nazkamil','1234-12-1','parent4@parent.com', 'baby');
INSERT INTO child VALUES(NULL, 'kamilcem', 'nazkamil','1234-12-1','parent4@parent.com', 'wobbler');
INSERT INTO child VALUES(NULL, 'kamilkerem', 'nazkamil','1234-12-1','parent4@parent.com', 'preschool');



-- Table structure for fee -- 

CREATE TABLE IF NOT EXISTS fee(
    day TINYINT PRIMARY KEY,                   -- days per week (1,3,5) --
    feePerHour FLOAT(2,1) NOT NULL
);


--Insert data in fee --

INSERT INTO fee VALUES('1', '5');
INSERT INTO fee VALUES('3', '4.5');
INSERT INTO fee VALUES('5', '4');



-- Table structure for contract --

CREATE TABLE IF NOT EXISTS contract(
    contractID INT AUTO_INCREMENT PRIMARY KEY,
    child INT NOT NULL,
    day TINYINT NOT NULL,
    isFull TINYINT(1) NOT NULL,
    FOREIGN KEY(child) REFERENCES child(cID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY(day) REFERENCES fee(day)
);

-- Insert data in contract --

INSERT INTO contract VALUES('1','1','3',true);
INSERT INTO contract VALUES('2','2','5',false);
INSERT INTO contract VALUES('3','3','1',false);
INSERT INTO contract VALUES('4','4','1',true);



-- Table structure for activity --

CREATE TABLE IF NOT EXISTS activity(
    name VARCHAR(30) PRIMARY KEY,
    info TEXT
);

-- Insert data in activity --

INSERT INTO activity VALUES('Face Painting','We will paint the faces of toddlers and preschool children');
INSERT INTO activity VALUES('Tour to National Zoo','A tour to national zoo, zoo themed lunch and souvenir shop');
INSERT INTO activity VALUES('Easter_Event', 'In the Easter event paiting eggs and drawings will be the weekend activity in the preschool classes');
INSERT INTO activity VALUES('Sport_Week', 'During the sport week every sports from different coutries and styles will be introduced to the kids, parents are allowed to join the activities on the weekend');




-- Table structure for day_detail --

CREATE TABLE IF NOT EXISTS day_detail(
    cID INT NOT NULL,
    date DATE NOT NULL,
    activity VARCHAR(30),
    bFast TEXT NOT NULL,
    lunch TEXT NOT NULL,
    temperature FLOAT(3,1) NOT NULL,
    PRIMARY KEY(cID, date),
    FOREIGN KEY(cID) REFERENCES child(cID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY(activity) REFERENCES activity(name)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

-- Insert data in day_detail --

INSERT INTO day_detail VALUES('1','2022-04-27','Face_Painting','omelette','sandwich','27.5');
INSERT INTO day_detail VALUES('2','2022-04-27','Tour_to_National_Zoo','omelette','sandwich','27.5');
INSERT INTO day_detail VALUES('3','2022-04-27','Face_Painting','omelette','sandwich','27.5');



-- Table Structure for page --

CREATE TABLE IF NOT EXISTS page(
    info VARCHAR(50) PRIMARY KEY,
    feature1 VARCHAR(30),
    feature2 VARCHAR(30),
    FOREIGN KEY(feature1) REFERENCES activity(name)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY(feature2) REFERENCES activity(name)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);



-- Insert data in page --

INSERT INTO page VALUES('Website of Enji Childcare', 'Face_Painting', 'Face_Painting');



-- Table structure for contact_us --

CREATE TABLE IF NOT EXISTS contact_us(
    query INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(40) NOT NULL,
    name VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    msg TEXT NOT NULL,
    phone VARCHAR(15)
);

-- Insert data in contact_us --

INSERT INTO contact_us VALUES(NULL, 'vinipbellomo@msn.com', 'Vinny', '2022-04-27', 'Test message here', NULL);
INSERT INTO contact_us VALUES(NULL, 'mert.bekar@student.griffith.ie', 'Mert', '2022-04-27', 'Test message 2 here', 'phone');

-- DROP TABLE contact_us;
-- DROP TABLE page;
-- DROP TABLE day_detail;
-- DROP TABLE activity;
-- DROP TABLE contract;
-- DROP TABLE fee;
-- DROP TABLE child;
-- DROP TABLE testimonial;
-- DROP TABLE service;
-- DROP TABLE phone;
-- DROP TABLE parent;
-- DROP TABLE admin;

UPDATE page set feature2 = 'Tour_To_National_Zoo' WHERE feature2 = 'Tour_To_National_Zook';