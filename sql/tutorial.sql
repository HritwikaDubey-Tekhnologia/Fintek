
CREATE TABLE tblUser (
    UserId INT PRIMARY KEY AUTO_INCREMENT,
    UserName VARCHAR(50) NOT NULL,
    UserType VARCHAR(50),
    Email VARCHAR(200),
    Password VARCHAR(200),
    FOREIGN KEY (UserType) REFERENCES tblUserType(UserType)
);