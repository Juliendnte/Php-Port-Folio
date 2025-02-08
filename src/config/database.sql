CREATE DATABASE IF NOT EXISTS projetb2julien;

USE projetb2julien;

CREATE USER IF NOT EXISTS 'projetb2'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON projetb2julien.* TO 'projetb2'@'localhost';
FLUSH PRIVILEGES;


CREATE TABLE IF NOT EXISTS users (
                                     id INT AUTO_INCREMENT PRIMARY KEY,
                                     email VARCHAR(100) NOT NULL UNIQUE,
                                     username VARCHAR(100),
                                     password VARCHAR(100) NOT NULL,
                                     role ENUM('admin', 'user') DEFAULT 'user',
                                     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS skills (
                                      id INT AUTO_INCREMENT PRIMARY KEY,
                                      name VARCHAR(20) NOT NULL,
                                      level ENUM('débutant', 'intermédiaire', 'expert'),
                                      UNIQUE KEY(name, level)
);

CREATE TABLE IF NOT EXISTS projects (
                                        id INT AUTO_INCREMENT PRIMARY KEY,
                                        user_id INT NOT NULL,
                                        title VARCHAR(100) NOT NULL,
                                        description TEXT,
                                        image VARCHAR(130),
                                        link VARCHAR(100),
                                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                        FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (email, username, password) VALUES ('caca@caca.com', 'caca', 'caca')