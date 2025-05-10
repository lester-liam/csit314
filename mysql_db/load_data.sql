CREATE DATABASE IF NOT EXISTS csit314;

use csit314;

DROP TABLE IF EXISTS ServiceHistory;
DROP TABLE IF EXISTS Shortlist;
DROP TABLE IF EXISTS CleanerService;
DROP TABLE IF EXISTS ServiceCategory;
DROP TABLE IF EXISTS UserAccount;
DROP TABLE IF EXISTS UserProfile;

CREATE TABLE UserProfile (
    `id` INT AUTO_INCREMENT,
    `role` VARCHAR(64) UNIQUE NOT NULL,
    `description` VARCHAR(255) NULL,
    `isSuspend` BOOL NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
);

CREATE TABLE UserAccount (
    `id` INT AUTO_INCREMENT,
    `username` VARCHAR(64) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `fullName` VARCHAR(64) NOT NULL,
    `email` VARCHAR(64) NOT NULL,
    `phone` VARCHAR(64) NOT NULL,
    `userProfile` VARCHAR(64) NOT NULL,
    `isSuspend` BOOL NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`userProfile`) REFERENCES UserProfile(`role`),
    UNIQUE (`username`)
);

CREATE TABLE ServiceCategory (
    `id` INT AUTO_INCREMENT,
    `category` VARCHAR(64) NOT NULL,
    `description` VARCHAR(64) NOT NULL DEFAULT 'No Description',
    PRIMARY KEY (`id`),
    UNIQUE (`category`)
);

CREATE TABLE CleanerService (
    `id` INT AUTO_INCREMENT,
    `serviceCategoryID` INT NOT NULL,
    `cleanerID` INT NOT NULL,
    `serviceName` VARCHAR(64) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `numViews` INT DEFAULT 0,
    `numShortlists` INT DEFAULT 0,
    `createdAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`serviceCategoryID`) REFERENCES ServiceCategory(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`cleanerID`) REFERENCES UserAccount(`id`)
);

CREATE TABLE Shortlist (
    `homeownerID` INT NOT NULL,
    `serviceID` INT NOT NULL,
    PRIMARY KEY (`homeownerID`, `serviceID`),
    FOREIGN KEY (`homeownerID`) REFERENCES UserAccount(`id`),
    FOREIGN KEY (`serviceID`) REFERENCES CleanerService(`id`) ON DELETE CASCADE
);

CREATE TABLE ServiceHistory (
    `id` INT AUTO_INCREMENT,
    `category` VARCHAR(64) NOT NULL,
    `cleanerID` INT NOT NULL,
    `homeownerID` INT NOT NULL,
    `serviceDate` TIMESTAMP NOT NULL,
    PRIMARY KEY(`id`)
);

INSERT INTO UserProfile (id, role, description, isSuspend) VALUES
(1, "User Admin", "For User Management", 0),
(2, "Platform Management", "For Service Categories Management", 0),
(3, "Cleaner", "For cleaners to manage their services", 0),
(4, "Homeowner", "For homeowners to access Cleaner Services.", 0);

INSERT INTO UserAccount (id, username, password, fullName, email, phone, userProfile, isSuspend) VALUES
(1, 'u1', md5('u1'), 'Adam Mayer', 'adam.mayer@example.com', '43202034', 'User Admin', 0),
(2, 'pm1', md5('pm1'), 'Clara Ho', 'clara.h@example.com', '98620160', 'Platform Management', 0),
(3, 'c1', md5('c1'), 'John Doe', 'john.doe@example.com', '78515250', 'Cleaner', 0),
(4, 'c2', md5('c2'), 'Lora Emma', 'lora.em1@example.com', '98645160', 'Cleaner', 0),
(5, 'c3', md5('c3'), 'Jake Browns', 'jakebrowns@example.com', '98698160', 'Cleaner', 0),
(6, 'ho1', md5('ho1'), 'Kelly Clarkson', 'k.clarkson@example.com', '24666160', 'Homeowner', 0),
(7, 'ho2', md5('ho2'), 'Alexander Joe', 'j.alex@example.com', '45666160', 'Homeowner', 0),
(8, 'ho3', md5('ho3'), 'Steve', 'ssteve@example.com', '45976160', 'Homeowner', 0);

INSERT INTO ServiceCategory (category, description) VALUES
('Deep Cleaning Service', 'For Specialized Cleaning Services'),
('Laundry Service', 'Wash, Fold, Iron');
INSERT INTO ServiceCategory (category) VALUES ('Air Conditioning Service');

INSERT INTO CleanerService (serviceCategoryID, cleanerID, serviceName, price, numViews, numShortlists, createdAt, updatedAt) VALUES
(1, 3, "Ironing Service", 25.00, 5, 2, '2025-04-28 09:00:00', '2025-04-28 09:00:00'),
(2, 4, "Laundry Service", 30.50, 10, 3, '2025-04-28 11:30:00', '2025-04-28 11:30:00'),
(3, 5, "Cloth Service", 40.00, 15, 5, '2025-04-25 14:00:00', '2025-04-25 14:00:00'),
(1, 3, "Ironing Service", 28.00, 20, 7, '2025-04-24 16:45:00', '2025-04-28 13:15:00'),
(2, 4, "Laundry Service", 35.00, 8, 2, '2025-04-20 10:00:00', '2025-04-20 10:00:00'),
(3, 5, "Mop Service", 50.00, 25, 10, '2025-01-15 18:00:00', '2025-01-15 18:00:00'),
(1, 3, "Kitchen Service", 22.00, 12, 4, '2025-03-01 09:30:00', '2025-03-01 09:30:00'),
(2, 4, "Specialized Service", 38.00, 3, 1, '2025-04-28 15:00:00', '2025-04-28 16:30:00'),
(3, 5, "Garden Service", 45.00, 18, 6, '2024-11-20 12:00:00', '2024-11-20 12:00:00'),
(1, 3, "Toilet Service", 27.50, 9, 3, '2025-04-22 08:00:00', '2025-04-26 17:45:00');

INSERT INTO Shortlist (homeownerID, serviceID) VALUES (6, 1);
INSERT INTO Shortlist (homeownerID, serviceID) VALUES (6, 3);
INSERT INTO Shortlist (homeownerID, serviceID) VALUES (7, 5);
INSERT INTO Shortlist (homeownerID, serviceID) VALUES (8, 7);
INSERT INTO Shortlist (homeownerID, serviceID) VALUES (8, 9);

INSERT INTO ServiceHistory (category, cleanerID, homeownerID, serviceDate) VALUES
("Deep Cleaning", 3, 6, '2025-05-05 10:00:00'),
("Laundry Cleaning", 4, 7, '2025-05-12 14:30:00'),
("Specialized Cleaning", 5, 8, '2025-05-06 09:00:00'),
("Deep Cleaning", 3, 7, '2025-03-26 16:00:00'),
("Laundry Cleaning", 4, 6, '2025-03-02 11:45:00');