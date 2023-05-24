CREATE TABLE person (
   id INT AUTO_INCREMENT PRIMARY KEY,
   lastname VARCHAR(255) NOT NULL,
   firstname VARCHAR(255) NOT NULL,
   birthday DATE NOT NULL,
   address VARCHAR(255) NOT NULL,
   email VARCHAR(255),
   phone VARCHAR(255)
);