CREATE TABLE IF NOT EXISTS roles
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    role VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS users
(
    id             INT AUTO_INCREMENT PRIMARY KEY,
    email          VARCHAR(100) NOT NULL UNIQUE,
    username       VARCHAR(100),
    password       VARCHAR(100) NOT NULL,
    remember_token VARCHAR(200),
    id_role        INT          NOT NULL DEFAULT 1,
    created_at     TIMESTAMP             DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_role) REFERENCES roles (id)
);

CREATE TABLE IF NOT EXISTS skills
(
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(20)                                  NOT NULL,
    level ENUM ('débutant', 'intermédiaire', 'expert') NOT NULL DEFAULT 'débutant',
    UNIQUE KEY (name, level)
);

CREATE TABLE IF NOT EXISTS projects
(
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT          NOT NULL,
    title       VARCHAR(100) NOT NULL,
    description TEXT,
    image       VARCHAR(130),
    link        VARCHAR(100),
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE IF NOT EXISTS users_skills
(
    user_id  INT,
    skill_id INT,
    PRIMARY KEY (user_id, skill_id),
    FOREIGN KEY (user_id) REFERENCES users (id),
    FOREIGN KEY (skill_id) REFERENCES skills (id)
);

INSERT INTO roles (id, role)
VALUES (1, 'user'),
       (2, 'admin');

INSERT INTO users (email, username, password, id_role)
VALUES ('julien.dante@ynov.com', 'Julien', '$2y$10$yetby0XYcEX6NhP1gQPzd.4QkgaFZ1nbhI1UM7o67SGhWQFan4vYS', 2),
       ('kantin.fagniart@ynov.com', 'Kantin', '$2y$10$yetby0XYcEX6NhP1gQPzd.4QkgaFZ1nbhI1UM7o67SGhWQFan4vYS', 1),
       ('nathanael.pivot@ynov.com', 'Nathanael', '$2y$10$yetby0XYcEX6NhP1gQPzd.4QkgaFZ1nbhI1UM7o67SGhWQFan4vYS', 1)