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
    FOREIGN KEY (id_role) REFERENCES roles (id) ON DELETE RESTRICT
);

CREATE TABLE IF NOT EXISTS skills
(
    id   INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(20) NOT NULL,
    UNIQUE KEY (name)
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
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS users_skills
(
    user_id  INT,
    skill_id INT,
    level    ENUM ('débutant', 'intermédiaire', 'expert') NOT NULL DEFAULT 'débutant',
    PRIMARY KEY (user_id, skill_id),
    FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE,
    FOREIGN KEY (skill_id) REFERENCES skills (id) ON DELETE CASCADE
);

INSERT INTO roles (id, role)
VALUES (1, 'user'),
       (2, 'admin')
ON DUPLICATE KEY UPDATE role = VALUES(role);


INSERT INTO users (email, username, password, id_role)
VALUES ('julien.dante@ynov.com', 'Julien', '$2y$10$yetby0XYcEX6NhP1gQPzd.4QkgaFZ1nbhI1UM7o67SGhWQFan4vYS', 2),
       ('kantin.fagniart@ynov.com', 'Kantin', '$2y$10$yetby0XYcEX6NhP1gQPzd.4QkgaFZ1nbhI1UM7o67SGhWQFan4vYS', DEFAULT),
       ('nathanael.pivot@ynov.com', 'Nathanael', '$2y$10$yetby0XYcEX6NhP1gQPzd.4QkgaFZ1nbhI1UM7o67SGhWQFan4vYS',
        DEFAULT)
ON DUPLICATE KEY UPDATE email = VALUES(email);

INSERT INTO skills (name)
VALUES ('PHP'),
       ('Python'),
       ('CSS')
ON DUPLICATE KEY UPDATE name = VALUES(name);

INSERT INTO users_skills (user_id, skill_id, level)
VALUES
    ((SELECT id FROM users WHERE email = 'julien.dante@ynov.com'),
     (SELECT id FROM skills WHERE name = 'PHP'), 'expert'),

    ((SELECT id FROM users WHERE email = 'julien.dante@ynov.com'),
     (SELECT id FROM skills WHERE name = 'Python'), 'expert'),

    ((SELECT id FROM users WHERE email = 'julien.dante@ynov.com'),
     (SELECT id FROM skills WHERE name = 'CSS'), 'débutant'),

    ((SELECT id FROM users WHERE email = 'kantin.fagniart@ynov.com'),
     (SELECT id FROM skills WHERE name = 'CSS'), 'expert'),

    ((SELECT id FROM users WHERE email = 'kantin.fagniart@ynov.com'),
     (SELECT id FROM skills WHERE name = 'PHP'), 'intermédiaire'),

    ((SELECT id FROM users WHERE email = 'nathanael.pivot@ynov.com'),
     (SELECT id FROM skills WHERE name = 'Python'), 'intermédiaire'),

    ((SELECT id FROM users WHERE email = 'nathanael.pivot@ynov.com'),
     (SELECT id FROM skills WHERE name = 'CSS'), 'intermédiaire')

INSERT INTO projects (user_id, title, description, image, link)
VALUES
    ((SELECT id FROM users WHERE email = 'julien.dante@ynov.com'),
     'Projet PHP Avancé',
     'Un projet basé sur PHP pour des fonctionnalités avancées.',
     'default_image.png',
     'https://github.com/Juliendnte/Php-Port-Folio.git'),

    ((SELECT id FROM users WHERE email = 'julien.dante@ynov.com'),
     'Backend en Python',
     'Un backend performant construit avec Python.',
     'default_image.png',
     'https://github.com/Juliendnte/backend-python'),

    ((SELECT id FROM users WHERE email = 'julien.dante@ynov.com'),
     'Guide CSS pour Débutants',
     'Un tutoriel étape par étape sur CSS.',
     'default_image.png',
     'https://github.com/Juliendnte/guide-css');

INSERT INTO projects (user_id, title, description, image, link)
VALUES
    ((SELECT id FROM users WHERE email = 'kantin.fagniart@ynov.com'),
     'CSS Design Expert',
     'Un projet explorant des designs complexes en CSS.',
     'default_image.png',
     'https://github.com/KANTIN-FAGN/css-design-expert'),

    ((SELECT id FROM users WHERE email = 'kantin.fagniart@ynov.com'),
     'PHP Application Intermédiaire',
     'Application développée en PHP avec des fonctionnalités dynamiques.',
     'default_image.png',
     'https://github.com/KANTIN-FAGN/livrables-projet-final-php'),

    ((SELECT id FROM users WHERE email = 'kantin.fagniart@ynov.com'),
     'Portfolio Personnel',
     'Un portfolio interactif utilisant HTML/CSS.',
     'default_image.png',
     'https://github.com/KANTIN-FAGN/portfolio');

INSERT INTO projects (user_id, title, description, image, link)
VALUES
    ((SELECT id FROM users WHERE email = 'nathanael.pivot@ynov.com'),
     'Projet Python Intermédiaire',
     'Un projet explorant des concepts intermédiaires en Python.',
     'default_image.png',
     'https://github.com/NathanaelPivot/python-projet'),

    ((SELECT id FROM users WHERE email = 'nathanael.pivot@ynov.com'),
     'CSS Modern',
     'Créer des pages web modernes avec CSS.',
     'default_image.png',
     'https://github.com/NathanaelPivot/css-modern'),

    ((SELECT id FROM users WHERE email = 'nathanael.pivot@ynov.com'),
     'Gestion de Contenu PHP',
     'Un système de gestion de contenu basé sur PHP.',
     'default_image.png',
     'https://github.com/NathanaelPivot/portfolio-php');

UPDATE projects
SET image = 'project/default.png' WHERE id >0;