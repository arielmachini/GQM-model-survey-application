DROP DATABASE IF EXISTS GQM;

CREATE DATABASE GQM;
USE GQM;

CREATE TABLE Goal (
    ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Description VARCHAR(128) NOT NULL,

    PRIMARY KEY (ID),
    UNIQUE (Description)
);

CREATE TABLE Question (
    ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    GoalID INT UNSIGNED NOT NULL,
    Description VARCHAR(128) NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (GoalID) REFERENCES Goal(ID), -- Each question is associated to a single goal.
    UNIQUE (Description)
);

CREATE TABLE Metric (
    ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Name VARCHAR(256) NOT NULL,
    Description VARCHAR(1024) NOT NULL,
    Type ENUM('Objective', 'Subjective') NOT NULL,
    ValueList JSON NOT NULL,
    Weight DECIMAL(2,1) NOT NULL CHECK (Weight between 0.0 and 1.0),
    NotApplicableIf VARCHAR(256), -- Optional, to indicate when this metric would not applicable to an API.

    PRIMARY KEY (ID),
    UNIQUE (Name)
);

CREATE TABLE Attribute (
    ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Name VARCHAR(256) NOT NULL,
    Description VARCHAR(256) NOT NULL,
    
    PRIMARY KEY (ID),
    UNIQUE (Name)
);

CREATE TABLE Source (
    ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    Description VARCHAR(512) NOT NULL,
    Type ENUM('Paper', 'Book', 'Thesis', 'Website', 'Other') NOT NULL,

    PRIMARY KEY (ID),
    UNIQUE (Description)
);

-- Tables to represent many-to-many relationships.

CREATE TABLE Goal_Attribute ( -- Many goals ↔ Many usability attributes.
    ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    GoalID INT UNSIGNED NOT NULL,
    AttributeID INT UNSIGNED NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (GoalID) REFERENCES Goal(ID),
    FOREIGN KEY (AttributeID) REFERENCES Attribute(ID)
);

CREATE TABLE Question_Metric ( -- Many questions ↔ Many metrics.
    ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    QuestionID INT UNSIGNED NOT NULL,
    MetricID INT UNSIGNED NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (QuestionID) REFERENCES Question(ID),
    FOREIGN KEY (MetricID) REFERENCES Metric(ID)
);

CREATE TABLE Metric_Source ( -- Many metrics ↔ Many sources.
    ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    MetricID INT UNSIGNED NOT NULL,
    SourceID INT UNSIGNED NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (MetricID) REFERENCES Metric(ID),
    FOREIGN KEY (SourceID) REFERENCES Source(ID)
);

CREATE TABLE Attribute_Source ( -- Many usability attributes ↔ Many sources.
    ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    AttributeID INT UNSIGNED NOT NULL,
    SourceID INT UNSIGNED NOT NULL,

    PRIMARY KEY (ID),
    FOREIGN KEY (AttributeID) REFERENCES Attribute(ID),
    FOREIGN KEY (SourceID) REFERENCES Source(ID)
);

CREATE TABLE Response (
	ID INT UNSIGNED NOT NULL AUTO_INCREMENT,
    DateTime DATETIME NOT NULL,
    ExpertiseLevel ENUM('Low', 'Medium', 'High'),
    EmailAddress VARCHAR(256) NOT NULL,
    InterviewParticipation ENUM('Yes', 'No') NOT NULL,
    Content JSON NOT NULL,
    
    PRIMARY KEY (ID)
);
