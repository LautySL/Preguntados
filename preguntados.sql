CREATE TABLE usuario (
	id int auto_increment primary key,
    nombre_de_usuario varchar(50),
    contrasena varchar(50),
    nombre varchar(50),
    apellido varchar(50),
    ano_de_nacimiento year(4),
    sexo ENUM('Femenino', 'Masculino', 'Prefiero no cargarlo'),
    mail varchar(50),
    foto_de_perfil varchar(50),
    pais varchar(50),
    ciudad varchar(50),
    cuenta_verificada boolean,
    hash_activacion varchar(500),
    latitud decimal(10, 8),
    longitud decimal(11, 8),
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    token int default 0
);

ALTER TABLE usuario
MODIFY COLUMN pais varchar(50);

CREATE TABLE administrador(
	 id int,
	constraint primary key (id),
    foreign key (id) references usuario(id)
);

CREATE TABLE editor(
	id int,
	constraint primary key (id),
    foreign key (id) references usuario(id)
);

CREATE TABLE jugador(
	id int,
    constraint primary key (id),
    foreign key (id) references usuario(id)
);

CREATE TABLE pregunta(
	id int auto_increment primary key,
    pregunta varchar(150),
    categoría ENUM('Geografía', 'Ciencia', 'Historia', 'Deporte', 'Arte', 'Entretenimiento'),
    veces_que_salio INT DEFAULT 0,
    veces_correcta INT DEFAULT 0,
    dificultad DECIMAL(5,2) DEFAULT 0.00,
    ultima_vez_que_salio DATE,
    fecha_creacion_pregunta TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE respuesta(
	id int auto_increment primary key,
	respuesta varchar(150),
	es_la_correcta boolean,
	pregunta int references pregunta(id)
);

CREATE TABLE preguntas_sugeridas (
id INT AUTO_INCREMENT PRIMARY KEY,
pregunta VARCHAR(255) NOT NULL,
categoría ENUM('Geografía', 'Ciencia', 'Historia', 'Deporte', 'Arte', 'Entretenimiento'),
usuario_id INT NOT NULL,
fecha_creacion_pregunta TIMESTAMP DEFAULT CURRENT_TIMESTAMp
);

CREATE TABLE respuestas_sugeridas (
id INT AUTO_INCREMENT PRIMARY KEY,
pregunta INT NOT NULL,
respuesta VARCHAR(255) NOT NULL,
es_la_correcta BOOLEAN NOT NULL,
FOREIGN KEY (pregunta) REFERENCES preguntas_sugeridas(id)
);
  
CREATE TABLE partida (
	id int auto_increment primary key,
	puntaje int,
	jugador int references jugador(id),
    fecha_creacion_partida TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE partida
ADD COLUMN modo_versus BOOLEAN DEFAULT FALSE,
ADD COLUMN resultado_versus ENUM('Ganada', 'Perdida', 'Empatada') DEFAULT 'Empatado';

CREATE TABLE partida_pregunta (
	partida int,
    pregunta int,
    se_respondio_bien boolean,
    constraint primary key (partida, pregunta),
    foreign key (partida) references partida(id),
    foreign key (pregunta) references pregunta(id)
);

CREATE TABLE reportes_preguntas (
     id INT AUTO_INCREMENT PRIMARY KEY,
     pregunta_id INT NOT NULL,
     usuario_id INT NOT NULL,
     fecha_reporte TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
     FOREIGN KEY (pregunta_id) REFERENCES pregunta(id),
     FOREIGN KEY (usuario_id) REFERENCES usuario(id)
);

INSERT INTO usuario (ID, nombre_de_usuario, contrasena, nombre, apellido, ano_de_nacimiento, sexo, mail, foto_de_perfil, pais, ciudad, cuenta_verificada, hash_activacion, latitud, longitud) VALUES 
(1000, 'bot_desafío', 123, 'Bot', 'Desafío', '2024', 'Prefiero no cargarlo', 'bot@desafio.com', 'fotoGenerica.png', 'Argentina', 'San Justo', true, '1223', 1, 1);

INSERT INTO jugador (id) VALUES (1000);

INSERT INTO pregunta (pregunta, categoría, veces_que_salio, veces_correcta, dificultad, ultima_vez_que_salio) VALUES

('¿Cuál es la capital de Francia?', 'Geografía', 10, 9, 10.00, '2024-06-01'),
('¿Quién formuló la teoría de la relatividad?', 'Ciencia', 15, 12, 20.00, '2024-06-02'),
('¿En qué año comenzó la Segunda Guerra Mundial?', 'Historia', 20, 15, 25.00, '2024-06-03'),
('¿Cuál es el deporte nacional de Japón?', 'Deporte', 5, 3, 40.00, '2024-06-04'),
('¿Quién pintó la Mona Lisa?', 'Arte', 8, 6, 25.00, '2024-06-05'),
('¿Cuál es el nombre del actor que interpreta a Iron Man en el MCU?', 'Entretenimiento', 12, 10, 16.67, '2024-06-06'),
('¿Cuál es el río más largo del mundo?', 'Geografía', 7, 5, 28.57, '2024-06-07'),
('¿Qué gas es esencial para la respiración humana?', 'Ciencia', 10, 9, 10.00, '2024-06-08'),
('¿Quién fue el primer presidente de Estados Unidos?', 'Historia', 9, 7, 22.22, '2024-06-09'),
('¿En qué deporte se utiliza una raqueta?', 'Deporte', 11, 9, 18.18, '2024-06-10'),

('¿Quién es el autor de "El Quijote"?', 'Arte', 10, 7, 30.00, '2024-06-01'),
('¿Cuál es la serie de televisión con más temporadas?', 'Entretenimiento', 12, 5, 58.33, '2024-06-02'),
('¿Cuál es el país más grande del mundo?', 'Geografía', 15, 13, 13.33, '2024-06-03'),
('¿Cuál es el símbolo químico del oro?', 'Ciencia', 8, 4, 50.00, '2024-06-04'),
('¿En qué año llegó el hombre a la Luna?', 'Historia', 20, 15, 25.00, '2024-06-05'),
('¿Qué deporte se juega con un bate y una pelota?', 'Deporte', 9, 8, 11.11, '2024-06-06'),
('¿Quién pintó "La última cena"?', 'Arte', 14, 10, 28.57, '2024-06-07'),
('¿Qué película ganó el Óscar a la mejor película en 1994?', 'Entretenimiento', 11, 9, 18.18, '2024-06-08'),
('¿Cuál es el desierto más grande del mundo?', 'Geografía', 7, 3, 57.14, '2024-06-09'),
('¿Cuál es el planeta más grande del sistema solar?', 'Ciencia', 12, 11, 8.33, '2024-06-10'),

('¿Quién descubrió América?', 'Historia', 15, 10, 33.33, '2024-06-01'),
('¿Cuántos jugadores hay en un equipo de fútbol?', 'Deporte', 12, 9, 25.00, '2024-06-02'),
('¿Quién escribió "Romeo y Julieta"?', 'Arte', 10, 6, 40.00, '2024-06-03'),
('¿Cuál es el nombre del mago protagonista en "Harry Potter"?', 'Entretenimiento', 14, 11, 21.43, '2024-06-04'),
('¿Cuál es la montaña más alta del mundo?', 'Geografía', 13, 9, 30.77, '2024-06-05'),
('¿Cuál es el elemento químico más abundante en el universo?', 'Ciencia', 16, 13, 18.75, '2024-06-06'),
('¿En qué año terminó la Primera Guerra Mundial?', 'Historia', 20, 15, 25.00, '2024-06-07'),
('¿Qué deporte se juega en el Wimbledon?', 'Deporte', 11, 7, 36.36, '2024-06-08'),
('¿Quién pintó "La noche estrellada"?', 'Arte', 12, 8, 33.33, '2024-06-09'),
('¿Cuál es la canción más famosa de Queen?', 'Entretenimiento', 18, 14, 22.22, '2024-06-10'),

('¿Cuál es el océano más grande del mundo?', 'Geografía', 15, 13, 13.33, '2024-06-01'),
('¿Quién desarrolló la teoría de la evolución?', 'Ciencia', 18, 16, 11.11, '2024-06-02'),
('¿En qué año cayó el Muro de Berlín?', 'Historia', 20, 17, 15.00, '2024-06-03'),
('¿Cuál es el país con la mayor cantidad de Copas del Mundo de fútbol?', 'Deporte', 12, 11, 8.33, '2024-06-04'),
('¿Quién es el escultor de "El David"?', 'Arte', 14, 12, 14.29, '2024-06-05'),
('¿Cuál es la película más taquillera de todos los tiempos?', 'Entretenimiento', 16, 14, 12.50, '2024-06-06'),
('¿Cuál es el continente más grande?', 'Geografía', 11, 9, 18.18, '2024-06-07'),
('¿Qué es H2O?', 'Ciencia', 19, 17, 10.53, '2024-06-08'),
('¿Quién fue el emperador de Francia en 1804?', 'Historia', 13, 11, 15.38, '2024-06-09'),
('¿Qué deporte se juega en la NBA?', 'Deporte', 12, 10, 16.67, '2024-06-10'),

('¿Quién pintó "Guernica"?', 'Arte', 15, 2, 86.67, '2024-06-01'),
('¿Cuál es el nombre del villano en "Los Vengadores"?', 'Entretenimiento', 12, 1, 91.67, '2024-06-02'),
('¿Cuál es el río más largo de África?', 'Geografía', 14, 2, 85.71, '2024-06-03'),
('¿Qué órgano bombea la sangre en el cuerpo humano?', 'Ciencia', 16, 3, 81.25, '2024-06-04'),
('¿Quién fue el primer emperador romano?', 'Historia', 13, 2, 84.62, '2024-06-05'),
('¿En qué deporte se utilizan las bicicletas?', 'Deporte', 18, 3, 83.33, '2024-06-06'),
('¿Quién escribió "Cien años de soledad"?', 'Arte', 20, 4, 80.00, '2024-06-07'),
('¿Cuál es la serie de televisión sobre un químico convertido en fabricante de drogas?', 'Entretenimiento', 17, 2, 88.24, '2024-06-08');



INSERT INTO respuesta (respuesta, es_la_correcta, pregunta) VALUES
('Paris', TRUE, 1),
('Londres', FALSE, 1),
('Madrid', FALSE, 1),
('Roma', FALSE, 1),

('Albert Einstein', TRUE, 2),
('Isaac Newton', FALSE, 2),
('Galileo Galilei', FALSE, 2),
('Nikola Tesla', FALSE, 2),

('1939', TRUE, 3),
('1941', FALSE, 3),
('1935', FALSE, 3),
('1945', FALSE, 3),

('Sumo', TRUE, 4),
('Karate', FALSE, 4),
('Judo', FALSE, 4),
('Béisbol', FALSE, 4),

                                                                ('Leonardo da Vinci', TRUE, 5),
                                                                ('Vincent van Gogh', FALSE, 5),
                                                                ('Pablo Picasso', FALSE, 5),
                                                                ('Claude Monet', FALSE, 5),

                                                                ('Robert Downey Jr.', TRUE, 6),
                                                                ('Chris Evans', FALSE, 6),
                                                                ('Mark Ruffalo', FALSE, 6),
                                                                ('Chris Hemsworth', FALSE, 6),

                                                                ('Amazonas', TRUE, 7),
                                                                ('Nilo', FALSE, 7),
                                                                ('Misisipi', FALSE, 7),
                                                                ('Yangtsé', FALSE, 7),

                                                                ('Oxígeno', TRUE, 8),
                                                                ('Nitrógeno', FALSE, 8),
                                                                ('Hidrógeno', FALSE, 8),
                                                                ('Dióxido de Carbono', FALSE, 8),

                                                                ('George Washington', TRUE, 9),
                                                                ('Thomas Jefferson', FALSE, 9),
                                                                ('Abraham Lincoln', FALSE, 9),
                                                                ('John Adams', FALSE, 9),

                                                                ('Tenis', TRUE, 10),
                                                                ('Fútbol', FALSE, 10),
                                                                ('Baloncesto', FALSE, 10),
                                                                ('Natación', FALSE, 10),

                                                                ('Miguel de Cervantes', TRUE, 11),
                                                                ('Gabriel García Márquez', FALSE, 11),
                                                                ('Julio Cortázar', FALSE, 11),
                                                                ('Mario Vargas Llosa', FALSE, 11),

                                                                ('Los Simpson', TRUE, 12),
                                                                ('Friends', FALSE, 12),
                                                                ('Breaking Bad', FALSE, 12),
                                                                ('Game of Thrones', FALSE, 12),

                                                                ('Rusia', TRUE, 13),
                                                                ('Canadá', FALSE, 13),
                                                                ('China', FALSE, 13),
                                                                ('Estados Unidos', FALSE, 13),

                                                                ('Au', TRUE, 14),
                                                                ('Ag', FALSE, 14),
                                                                ('Fe', FALSE, 14),
                                                                ('Hg', FALSE, 14),

                                                                ('1969', TRUE, 15),
                                                                ('1965', FALSE, 15),
                                                                ('1970', FALSE, 15),
                                                                ('1975', FALSE, 15),

                                                                ('Béisbol', TRUE, 16),
                                                                ('Fútbol', FALSE, 16),
                                                                ('Baloncesto', FALSE, 16),
                                                                ('Críquet', FALSE, 16),

                                                                ('Leonardo da Vinci', TRUE, 17),
                                                                ('Michelangelo', FALSE, 17),
                                                                ('Raphael', FALSE, 17),
                                                                ('Donatello', FALSE, 17),

                                                                ('Forrest Gump', TRUE, 18),
                                                                ('Pulp Fiction', FALSE, 18),
                                                                ('The Shawshank Redemption', FALSE, 18),
                                                                ('The Lion King', FALSE, 18),

                                                                ('Sahara', TRUE, 19),
                                                                ('Gobi', FALSE, 19),
                                                                ('Kalahari', FALSE, 19),
                                                                ('Patagonia', FALSE, 19),

                                                                ('Júpiter', TRUE, 20),
                                                                ('Saturno', FALSE, 20),
                                                                ('Marte', FALSE, 20),
                                                                ('Neptuno', FALSE, 20),

                                                                ('Cristóbal Colón', TRUE, 21),
                                                                ('Américo Vespucio', FALSE, 21),
                                                                ('Fernando de Magallanes', FALSE, 21),
                                                                ('Hernán Cortés', FALSE, 21),

                                                                ('11', TRUE, 22),
                                                                ('9', FALSE, 22),
                                                                ('10', FALSE, 22),
                                                                ('12', FALSE, 22),

                                                                ('William Shakespeare', TRUE, 23),
                                                                ('Charles Dickens', FALSE, 23),
                                                                ('Jane Austen', FALSE, 23),
                                                                ('Mark Twain', FALSE, 23),

                                                                ('Harry Potter', TRUE, 24),
                                                                ('Hermione Granger', FALSE, 24),
                                                                ('Ron Weasley', FALSE, 24),
                                                                ('Albus Dumbledore', FALSE, 24),

                                                                ('Everest', TRUE, 25),
                                                                ('K2', FALSE, 25),
                                                                ('Kangchenjunga', FALSE, 25),
                                                                ('Lhotse', FALSE, 25),

                                                                ('Hidrógeno', TRUE, 26),
                                                                ('Oxígeno', FALSE, 26),
                                                                ('Carbono', FALSE, 26),
                                                                ('Helio', FALSE, 26),

                                                                ('1918', TRUE, 27),
                                                                ('1917', FALSE, 27),
                                                                ('1916', FALSE, 27),
                                                                ('1919', FALSE, 27),

                                                                ('Tenis', TRUE, 28),
                                                                ('Fútbol', FALSE, 28),
                                                                ('Baloncesto', FALSE, 28),
                                                                ('Golf', FALSE, 28),

                                                                ('Vincent van Gogh', TRUE, 29),
                                                                ('Pablo Picasso', FALSE, 29),
                                                                ('Claude Monet', FALSE, 29),
                                                                ('Salvador Dalí', FALSE, 29),

                                                                ('Bohemian Rhapsody', TRUE, 30),
                                                                ('We Will Rock You', FALSE, 30),
                                                                ('We Are the Champions', FALSE, 30),
                                                                ('Another One Bites the Dust', FALSE, 30),

                                                                ('Pacífico', TRUE, 31),
                                                                ('Atlántico', FALSE, 31),
                                                                ('Índico', FALSE, 31),
                                                                ('Ártico', FALSE, 31),

                                                                ('Charles Darwin', TRUE, 32),
                                                                ('Isaac Newton', FALSE, 32),
                                                                ('Albert Einstein', FALSE, 32),
                                                                ('Galileo Galilei', FALSE, 32),

                                                                ('1989', TRUE, 33),
                                                                ('1987', FALSE, 33),
                                                                ('1990', FALSE, 33),
                                                                ('1991', FALSE, 33),

                                                                ('Brasil', TRUE, 34),
                                                                ('Alemania', FALSE, 34),
                                                                ('Italia', FALSE, 34),
                                                                ('Argentina', FALSE, 34),

                                                                ('Michelangelo', TRUE, 35),
                                                                ('Donatello', FALSE, 35),
                                                                ('Raphael', FALSE, 35),
                                                                ('Leonardo da Vinci', FALSE, 35),

                                                                ('Avatar', TRUE, 36),
                                                                ('Avengers: Endgame', FALSE, 36),
                                                                ('Titanic', FALSE, 36),
                                                                ('Star Wars: The Force Awakens', FALSE, 36),

                                                                ('Asia', TRUE, 37),
                                                                ('África', FALSE, 37),
                                                                ('América del Norte', FALSE, 37),
                                                                ('Europa', FALSE, 37),

                                                                ('Agua', TRUE, 38),
                                                                ('Oxígeno', FALSE, 38),
                                                                ('Hidrógeno', FALSE, 38),
                                                                ('Helio', FALSE, 38),

                                                                ('Napoleón Bonaparte', TRUE, 39),
                                                                ('Luis XIV', FALSE, 39),
                                                                ('Carlos V', FALSE, 39),
                                                                ('Francisco I', FALSE, 39),

                                                                ('Baloncesto', TRUE, 40),
                                                                ('Fútbol', FALSE, 40),
                                                                ('Béisbol', FALSE, 40),
                                                                ('Hockey', FALSE, 40),

                                                                ('Pablo Picasso', TRUE, 41),
                                                                ('Salvador Dalí', FALSE, 41),
                                                                ('Joan Miró', FALSE, 41),
                                                                ('Diego Velázquez', FALSE, 41),

                                                                ('Thanos', TRUE, 42),
                                                                ('Loki', FALSE, 42),
                                                                ('Ultron', FALSE, 42),
                                                                ('Red Skull', FALSE, 42),

                                                                ('Nilo', TRUE, 43),
                                                                ('Congo', FALSE, 43),
                                                                ('Zambeze', FALSE, 43),
                                                                ('Níger', FALSE, 43),

                                                                ('Corazón', TRUE, 44),
                                                                ('Pulmones', FALSE, 44),
                                                                ('Hígado', FALSE, 44),
                                                                ('Riñones', FALSE, 44),

                                                                ('Augusto', TRUE, 45),
                                                                ('Julio César', FALSE, 45),
                                                                ('Nerón', FALSE, 45),
                                                                ('Calígula', FALSE, 45),

                                                                ('Ciclismo', TRUE, 46),
                                                                ('Atletismo', FALSE, 46),
                                                                ('Natación', FALSE, 46),
                                                                ('Boxeo', FALSE, 46),

                                                                ('Gabriel García Márquez', TRUE, 47),
                                                                ('Jorge Luis Borges', FALSE, 47),
                                                                ('Pablo Neruda', FALSE, 47),
                                                                ('Octavio Paz', FALSE, 47),

                                                                ('Breaking Bad', TRUE, 48),
                                                                ('The Sopranos', FALSE, 48),
                                                                ('The Wire', FALSE, 48),
                                                                ('Mad Men', FALSE, 48);
                                               
INSERT INTO pregunta (pregunta, categoría, veces_que_salio, veces_correcta, dificultad, ultima_vez_que_salio) VALUES


('¿Cuál es la ciudad más poblada del mundo?', 'Geografía', 14, 7, 50.00, '2024-06-01'),
('¿Qué partícula subatómica tiene carga negativa?', 'Ciencia', 15, 6, 60.00, '2024-06-02'),
('¿Quién fue el primer hombre en orbitar la Tierra?', 'Historia', 18, 8, 55.56, '2024-06-03'),
('¿Qué deporte se juega con un disco en lugar de una pelota?', 'Deporte', 20, 9, 55.00, '2024-06-04'),
('¿Quién compuso la ópera "La flauta mágica"?', 'Arte', 16, 7, 56.25, '2024-06-05'),
('¿Cuál es el nombre del protagonista de "El Señor de los Anillos"?', 'Entretenimiento', 12, 5, 58.33, '2024-06-06'),
('¿Cuál es la segunda lengua más hablada en el mundo?', 'Geografía', 14, 6, 57.14, '2024-06-07'),
('¿Cuál es el símbolo químico del sodio?', 'Ciencia', 13, 4, 69.23, '2024-06-08'),
('¿Quién fue el líder del Imperio Mongol?', 'Historia', 17, 7, 58.82, '2024-06-09'),
('¿En qué deporte se usa un "birdie"?', 'Deporte', 19, 9, 52.63, '2024-06-10'),


('¿Quién pintó "La persistencia de la memoria"?', 'Arte', 12, 4, 66.67, '2024-06-01'),
('¿Qué serie de televisión es conocida por la frase "Winter is Coming"?', 'Entretenimiento', 15, 5, 66.67, '2024-06-02'),
('¿Cuál es el país más pequeño del mundo?', 'Geografía', 18, 6, 66.67, '2024-06-03'),
('¿Qué gas se utiliza en los globos de helio?', 'Ciencia', 14, 5, 64.29, '2024-06-04'),
('¿En qué año se firmó la Declaración de Independencia de Estados Unidos?', 'Historia', 20, 7, 65.00, '2024-06-05'),
('¿Qué deporte se practica en el Tour de Francia?', 'Deporte', 13, 5, 61.54, '2024-06-06'),
('¿Quién es el autor de "La Odisea"?', 'Arte', 16, 6, 62.50, '2024-06-07'),
('¿Cuál fue la primera película de animación en ganar un Óscar?', 'Entretenimiento', 19, 7, 63.16, '2024-06-08'),
('¿Cuál es la isla más grande del mundo?', 'Geografía', 17, 6, 64.71, '2024-06-09'),
('¿Qué elemento químico tiene el símbolo K?', 'Ciencia', 15, 5, 66.67, '2024-06-10'),


('¿Cuál es el nombre del río que atraviesa Egipto?', 'Geografía', 15, 2, 86.67, '2024-06-01'),
('¿Qué tipo de partícula es un fotón?', 'Ciencia', 12, 1, 91.67, '2024-06-02'),
('¿Quién fue el primer presidente de Sudáfrica después del apartheid?', 'Historia', 14, 2, 85.71, '2024-06-03'),
('¿En qué deporte se utiliza una tabla y una vela?', 'Deporte', 16, 3, 81.25, '2024-06-04'),
('¿Quién es el autor de "La Divina Comedia"?', 'Arte', 13, 2, 84.62, '2024-06-05'),
('¿Cuál es el nombre del detective en las novelas de Arthur Conan Doyle?', 'Entretenimiento', 18, 3, 83.33, '2024-06-06'),
('¿Cuál es el país con más islas en el mundo?', 'Geografía', 20, 4, 80.00, '2024-06-07'),
('¿Qué planeta es conocido como el Planeta Rojo?', 'Ciencia', 17, 2, 88.24, '2024-06-08'),
('¿Quién fue el primer emperador de China?', 'Historia', 19, 3, 84.21, '2024-06-09'),
('¿En qué deporte se compite en el "Tour de Francia"?', 'Deporte', 16, 2, 87.50, '2024-06-10'),


('¿Quién pintó "El grito"?', 'Arte', 13, 1, 107.69, '2024-06-01'),
('¿Cuál es el nombre del protagonista en la serie "Breaking Bad"?', 'Entretenimiento', 15, 1, 106.67, '2024-06-02'),
('¿Cuál es el lago más profundo del mundo?', 'Geografía', 17, 2, 111.76, '2024-06-03'),
('¿Qué metal es líquido a temperatura ambiente?', 'Ciencia', 18, 2, 111.11, '2024-06-04'),
('¿En qué año comenzó la Revolución Francesa?', 'Historia', 16, 1, 115.63, '2024-06-05'),
('¿Cuál es el deporte nacional de Canadá?', 'Deporte', 14, 1, 114.29, '2024-06-06'),
('¿Quién escribió "1984"?', 'Arte', 19, 2, 115.79, '2024-06-07'),
('¿Qué película de Disney tiene un personaje llamado Simba?', 'Entretenimiento', 12, 1, 108.33, '2024-06-08'),
('¿Cuál es la ciudad capital de Australia?', 'Geografía', 15, 2, 113.33, '2024-06-09'),
('¿Cuál es el gas más abundante en la atmósfera de la Tierra?', 'Ciencia', 20, 2, 110.00, '2024-06-10'),



('¿Quién fue el último zar de Rusia?', 'Historia', 14, 9, 35.71, '2024-06-01'),
('¿Qué deporte se juega en Wimbledon?', 'Deporte', 15, 10, 33.33, '2024-06-02'),
('¿Quién es el autor de "Crimen y Castigo"?', 'Arte', 16, 11, 31.25, '2024-06-03'),
('¿Cuál es el nombre del parque de atracciones en Anaheim, California?', 'Entretenimiento', 13, 9, 30.77, '2024-06-04'),
('¿Cuál es el país más densamente poblado del mundo?', 'Geografía', 18, 12, 33.33, '2024-06-05'),
('¿Cuál es el nombre común del ácido acético?', 'Ciencia', 17, 11, 35.29, '2024-06-06'),
('¿En qué año se produjo el primer vuelo de los hermanos Wright?', 'Historia', 20, 13, 35.00, '2024-06-07'),
('¿Cuál es el deporte más popular en India?', 'Deporte', 12, 8, 33.33, '2024-06-08'),
('¿Quién pintó "La joven de la perla"?', 'Arte', 14, 9, 35.71, '2024-06-09'),
('¿Qué banda lanzó el álbum "The Dark Side of the Moon"?', 'Entretenimiento', 19, 13, 31.58, '2024-06-10'),


('¿Cuál es el idioma oficial de Brasil?', 'Geografía', 18, 9, 50.00, '2024-06-01'),
('¿Qué partícula subatómica tiene una carga negativa?', 'Ciencia', 17, 8, 52.94, '2024-06-02'),
('¿Quién escribió "La Ilíada" y "La Odisea"?', 'Historia', 16, 7, 56.25, '2024-06-03'),
('¿En qué deporte se utiliza un balón y una canasta?', 'Deporte', 20, 10, 50.00, '2024-06-04'),
('¿Quién es el autor de "El Principito"?', 'Arte', 14, 7, 50.00, '2024-06-05'),
('¿Cuál es el nombre del planeta donde vive Yoda en "Star Wars"?', 'Entretenimiento', 19, 10, 47.37, '2024-06-06'),
('¿Qué país tiene la mayor población del mundo?', 'Geografía', 15, 8, 46.67, '2024-06-07'),
('¿Qué vitamina se obtiene principalmente del sol?', 'Ciencia', 12, 5, 58.33, '2024-06-08'),
('¿Quién fue el primer ministro del Reino Unido durante la Segunda Guerra Mundial?', 'Historia', 13, 6, 53.85, '2024-06-09'),
('¿En qué deporte se utiliza un disco y un palo?', 'Deporte', 20, 11, 45.00, '2024-06-10'),



('¿Quién pintó "Las Meninas"?', 'Arte', 12, 8, 70.00, '2024-06-20'),
('¿Cuál es el nombre del mago oscuro en "El Señor de los Anillos"?', 'Entretenimiento', 14, 10, 71.43, '2024-06-19'),
('¿Cuál es el continente más pequeño en términos de superficie?', 'Geografía', 11, 6, 72.73, '2024-06-18'),
('¿Qué elemento químico tiene el símbolo He?', 'Ciencia', 13, 7, 75.00, '2024-06-21'),
('¿Quién fue la primera mujer en volar sola a través del Atlántico?', 'Historia', 10, 5, 70.00, '2024-06-22'),
('¿En qué deporte se compite en los "X Games"?', 'Deporte', 15, 11, 73.33, '2024-06-17'),
('¿Quién es el autor de "Orgullo y Prejuicio"?', 'Arte', 9, 4, 66.67, '2024-06-15'),
('¿Cuál es el nombre del villano en la serie "Harry Potter"?', 'Entretenimiento', 16, 12, 75.00, '2024-06-14'),
('¿Cuál es el país más grande de América del Sur?', 'Geografía', 13, 8, 61.54, '2024-06-13'),
('¿Qué gas es conocido como el gas de la risa?', 'Ciencia', 14, 9, 64.29, '2024-06-12'),


('¿Quién fue el principal líder de la Revolución Cubana?', 'Historia', 18, 15, 86.67, '2024-06-20'),
('¿En qué deporte se usa una pelota ovalada?', 'Deporte', 20, 18, 90.00, '2024-06-19'),
('¿Quién escribió "El Retrato de Dorian Gray"?', 'Arte', 22, 20, 95.45, '2024-06-18'),
('¿Cuál es el nombre del parque temático en París relacionado con Disney?', 'Entretenimiento', 17, 14, 82.35, '2024-06-17'),
('¿Cuál es el río más largo de Europa?', 'Geografía', 19, 16, 84.21, '2024-06-16'),
('¿Qué elemento químico tiene el símbolo K?', 'Ciencia', 21, 19, 90.48, '2024-06-15'),
('¿Quién fue el presidente de los Estados Unidos durante la Guerra Civil?', 'Historia', 23, 21, 91.30, '2024-06-14'),
('¿En qué deporte se utiliza un arco y flechas?', 'Deporte', 16, 13, 81.25, '2024-06-13'),
('¿Quién pintó "La persistencia de la memoria"?', 'Arte', 18, 15, 86.67, '2024-06-12'),
('¿Cuál es el nombre del protagonista en la serie "Stranger Things"?', 'Entretenimiento', 20, 17, 85.00, '2024-06-11'),


('¿Cuál es la moneda oficial de Japón?', 'Geografía', 5, 4, 16.67, '2024-06-20'),
('¿Qué científico descubrió la penicilina?', 'Ciencia', 7, 6, 19.05, '2024-06-19'),
('¿En qué año se firmó la Declaración de Independencia de los Estados Unidos?', 'Historia', 4, 3, 15.00, '2024-06-18'),
('¿Qué deporte se juega con un volante y una raqueta?', 'Deporte', 6, 5, 18.33, '2024-06-17'),
('¿Quién es el autor de "El viejo y el mar"?', 'Arte', 3, 2, 13.33, '2024-06-16'),
('¿Cuál es el nombre del dragón en "El Hobbit"?', 'Entretenimiento', 8, 7, 20.00, '2024-06-15'),
('¿Cuál es el país más pequeño del mundo?', 'Geografía', 6, 5, 18.33, '2024-06-14'),
('¿Qué planeta es conocido como el planeta rojo?', 'Ciencia', 5, 4, 16.67, '2024-06-13'),
('¿Quién fue el primer presidente de Sudáfrica elegido democráticamente?', 'Historia', 9, 8, 20.00, '2024-06-12'),
('¿En qué deporte se utiliza un balón y una red alta?', 'Deporte', 2, 1, 10.00, '2024-06-11'),


('¿Quién pintó "El grito"?', 'Arte', 12, 10, 83.33, '2024-06-20'),
('¿Cuál es el nombre del barco en "Titanic"?', 'Entretenimiento', 11, 9, 81.82, '2024-06-19'),
('¿Cuál es el desierto más grande de América del Norte?', 'Geografía', 10, 8, 80.00, '2024-06-18'),
('¿Qué elemento químico tiene el símbolo Na?', 'Ciencia', 13, 11, 84.62, '2024-06-17'),
('¿Quién fue el líder soviético durante la Segunda Guerra Mundial?', 'Historia', 15, 13, 86.67, '2024-06-16'),
('¿En qué deporte se compite en el Tour de Francia?', 'Deporte', 14, 12, 85.71, '2024-06-15'),
('¿Quién es el autor de "Crimen y castigo"?', 'Arte', 16, 14, 87.50, '2024-06-14'),
('¿Cuál es el nombre del villano en la serie "Batman"?', 'Entretenimiento', 17, 15, 88.24, '2024-06-13'),
('¿Cuál es la ciudad más poblada del mundo?', 'Geografía', 18, 16, 88.89, '2024-06-12'),
('¿Qué científico propuso la ley de la gravitación universal?', 'Ciencia', 20, 18, 90.00, '2024-06-11'),


('¿En qué año se inició la Revolución Francesa?', 'Historia', 5, 4, 16.67, '2024-06-20'),
('¿Qué deporte se juega en la NFL?', 'Deporte', 3, 2, 13.33, '2024-06-19'),
('¿Quién escribió "La Odisea"?', 'Arte', 4, 3, 15.00, '2024-06-18'),
('¿Cuál es el nombre del león en "El Rey León"?', 'Entretenimiento', 2, 1, 10.00, '2024-06-17'),
('¿Cuál es el idioma oficial de Brasil?', 'Geografía', 1, 1, 10.00, '2024-06-16'),
('¿Qué es el ADN?', 'Ciencia', 3, 2, 13.33, '2024-06-15'),
('¿En qué año se disolvió la Unión Soviética?', 'Historia', 4, 3, 15.00, '2024-06-14'),
('¿Qué deporte se practica en el Tour de Francia?', 'Deporte', 6, 5, 18.33, '2024-06-13'),
('¿Quién escribió "La Metamorfosis"?', 'Arte', 5, 4, 16.67, '2024-06-12'),
('¿Cuál es el nombre del protagonista en "Breaking Bad"?', 'Entretenimiento', 7, 6, 19.05, '2024-06-11'),



('¿Cuál es la capital de Australia?', 'Geografía', 8, 6, 46.67, '2024-06-23'),
('¿Qué órgano del cuerpo humano produce insulina?', 'Ciencia', 9, 7, 43.33, '2024-06-22'),
('¿En qué año comenzó la Primera Guerra Mundial?', 'Historia', 10, 8, 50.00, '2024-06-21'),
('¿Qué deporte se juega con una pelota y un aro?', 'Deporte', 7, 6, 42.86, '2024-06-20'),
('¿Quién es el autor de "El Principito"?', 'Arte', 8, 6, 46.67, '2024-06-19'),
('¿Cuál es el nombre del personaje principal en "The Matrix"?', 'Entretenimiento', 9, 7, 43.33, '2024-06-18'),
('¿Cuál es el río más largo de Europa?', 'Geografía', 10, 8, 50.00, '2024-06-17'),
('¿Qué partícula subatómica tiene carga negativa?', 'Ciencia', 7, 6, 42.86, '2024-06-16'),
('¿Quién fue el primer ministro del Reino Unido durante la Segunda Guerra Mundial?', 'Historia', 8, 6, 46.67, '2024-06-15'),
('¿En qué deporte se usa una tabla y olas?', 'Deporte', 9, 7, 43.33, '2024-06-14'),


('¿Quién pintó "La persistencia de la memoria"?', 'Arte', 2, 1, 20.00, '2024-06-23'),
('¿Cuál es el nombre del superhéroe en "Spider-Man"?', 'Entretenimiento', 3, 2, 13.33, '2024-06-22'),
('¿Cuál es la montaña más alta de América del Sur?', 'Geografía', 1, 1, 10.00, '2024-06-21'),
('¿Qué vitamina es producida por el cuerpo cuando está expuesto al sol?', 'Ciencia', 3, 2, 13.33, '2024-06-20'),
('¿En qué año cayó el Imperio Romano de Occidente?', 'Historia', 4, 3, 15.00, '2024-06-19'),
('¿Qué deporte se practica en el Super Bowl?', 'Deporte', 6, 5, 18.33, '2024-06-18'),
('¿Quién es el autor de "Matar a un ruiseñor"?', 'Arte', 5, 4, 16.67, '2024-06-17'),
('¿Cuál es el nombre del barco en "Piratas del Caribe"?', 'Entretenimiento', 2, 1, 10.00, '2024-06-16'),
('¿Cuál es la capital de Canadá?', 'Geografía', 1, 1, 10.00, '2024-06-15'),
('¿Qué es la fotosíntesis?', 'Ciencia', 3, 2, 13.33, '2024-06-14'),

('¿En qué año se firmó la Declaración de Independencia de los Estados Unidos?', 'Historia', 8, 6, 73.33, '2024-06-23'),
('¿En qué deporte se usa una red y un volante?', 'Deporte', 7, 6, 66.67, '2024-06-22'),
('¿Quién escribió "1984"?', 'Arte', 8, 6, 73.33, '2024-06-21'),
('¿Cuál es el nombre del robot en "Star Wars"?', 'Entretenimiento', 9, 7, 70.00, '2024-06-20'),
('¿Qué país tiene la mayor cantidad de islas?', 'Geografía', 10, 8, 80.00, '2024-06-19'),
('¿Qué es el pH?', 'Ciencia', 7, 6, 66.67, '2024-06-18'),
('¿En qué año terminó la Segunda Guerra Mundial?', 'Historia', 8, 6, 73.33, '2024-06-17'),
('¿Qué deporte se juega con un disco en el hielo?', 'Deporte', 9, 7, 70.00, '2024-06-16'),
('¿Quién escribió "Orgullo y prejuicio"?', 'Arte', 8, 6, 73.33, '2024-06-15'),
('¿Cuál es el nombre del parque de atracciones de Disney en California?', 'Entretenimiento', 9, 7, 70.00, '2024-06-14'),


('¿Cuál es el país más pequeño del mundo?', 'Geografía', 5, 4, 83.33, '2024-06-23'),
('¿Qué órgano es responsable de filtrar la sangre en el cuerpo humano?', 'Ciencia', 3, 2, 33.33, '2024-06-22'),
('¿En qué año comenzó la Revolución Rusa?', 'Historia', 7, 6, 70.00, '2024-06-21'),
('¿Qué deporte se juega en Wimbledon?', 'Deporte', 6, 5, 50.00, '2024-06-20'),
('¿Quién escribió "Hamlet"?', 'Arte', 4, 3, 40.00, '2024-06-19'),
('¿Cuál es el nombre del villano en "Harry Potter"?', 'Entretenimiento', 2, 1, 20.00, '2024-06-18'),
('¿Cuál es el continente más frío?', 'Geografía', 7, 6, 70.00, '2024-06-17'),
('¿Qué es la teoría del Big Bang?', 'Ciencia', 4, 3, 40.00, '2024-06-16'),
('¿En qué año se firmó el Tratado de Versalles?', 'Historia', 8, 6, 73.33, '2024-06-15'),
('¿Qué deporte se juega con un palo y una pelota pequeña en un campo de césped?', 'Deporte', 6, 5, 50.00, '2024-06-14'),
('¿Quién pintó "El grito"?', 'Arte', 5, 4, 60.00, '2024-06-13'),
('¿Cuál es el nombre del protagonista en "El señor de los anillos"?', 'Entretenimiento', 3, 2, 30.00, '2024-06-12');


INSERT INTO respuesta (respuesta, es_la_correcta, pregunta) VALUES
('Tokio', TRUE, 49),
('Nueva York', FALSE, 49),
('Delhi', FALSE, 49),
('Shanghai', FALSE, 49),

('Electrón', TRUE, 50),
('Protón', FALSE, 50),
('Neutrón', FALSE, 50),
('Quark', FALSE, 50),

('Yuri Gagarin', TRUE, 51),
('Neil Armstrong', FALSE, 51),
('Buzz Aldrin', FALSE, 51),
('John Glenn', FALSE, 51),

('Hockey sobre hielo', TRUE, 52),
('Balonmano', FALSE, 52),
('Lacrosse', FALSE, 52),
('Fútbol', FALSE, 52),

('Wolfgang Amadeus Mozart', TRUE, 53),
('Ludwig van Beethoven', FALSE, 53),
('Johann Sebastian Bach', FALSE, 53),
('Giuseppe Verdi', FALSE, 53),

('Frodo Bolsón', TRUE, 54),
('Gandalf', FALSE, 54),
('Aragorn', FALSE, 54),
('Legolas', FALSE, 54),

('Español', TRUE, 55),
('Inglés', FALSE, 55),
('Chino mandarín', FALSE, 55),
('Hindú', FALSE, 55),

('Na', TRUE, 56),
('S', FALSE, 56),
('N', FALSE, 56),
('Si', FALSE, 56),

('Genghis Khan', TRUE, 57),
('Tamerlán', FALSE, 57),
('Kublai Khan', FALSE, 57),
('Atila', FALSE, 57),

('Bádminton', TRUE, 58),
('Tenis', FALSE, 58),
('Golf', FALSE, 58),
('Ping-pong', FALSE, 58),

('Salvador Dalí', TRUE, 59),
('Pablo Picasso', FALSE, 59),
('Claude Monet', FALSE, 59),
('Edvard Munch', FALSE, 59),

('Juego de Tronos', TRUE, 60),
('Breaking Bad', FALSE, 60),
('Los Soprano', FALSE, 60),
('Mad Men', FALSE, 60),

('Ciudad del Vaticano', TRUE, 61),
('Mónaco', FALSE, 61),
('San Marino', FALSE, 61),
('Liechtenstein', FALSE, 61),

('Helio', TRUE, 62),
('Hidrógeno', FALSE, 62),
('Oxígeno', FALSE, 62),
('Nitrógeno', FALSE, 62),

('1776', TRUE, 63),
('1789', FALSE, 63),
('1812', FALSE, 63),
('1804', FALSE, 63),

('Ciclismo', TRUE, 64),
('Automovilismo', FALSE, 64),
('Motociclismo', FALSE, 64),
('Esquí', FALSE, 64),

('Homero', TRUE, 65),
('Virgilio', FALSE, 65),
('Sófocles', FALSE, 65),
('Eurípides', FALSE, 65),

('Blancanieves y los siete enanitos', TRUE, 66),
('La bella y la bestia', FALSE, 66),
('El rey león', FALSE, 66),
('Aladdin', FALSE, 66),

('Groenlandia', TRUE, 67),
('Nueva Guinea', FALSE, 67),
('Borneo', FALSE, 67),
('Madagascar', FALSE, 67),

('Potasio', TRUE, 68),
('Calcio', FALSE, 68),
('Hierro', FALSE, 68),
('Plomo', FALSE, 68),

('Nilo', TRUE, 69),
('Amazonas', FALSE, 69),
('Mississippi', FALSE, 69),
('Ganges', FALSE, 69),

('Bosón', FALSE, 70),
('Fermión', FALSE, 70),
('Quark', FALSE, 70),
('Partícula de luz', TRUE, 70),

('Nelson Mandela', TRUE, 71),
('Desmond Tutu', FALSE, 71),
('Thabo Mbeki', FALSE, 71),
('Jacob Zuma', FALSE, 71),

('Windsurf', TRUE, 72),
('Surf', FALSE, 72),
('Kitesurf', FALSE, 72),
('Esquí acuático', FALSE, 72),

('Dante Alighieri', TRUE, 73),
('Geoffrey Chaucer', FALSE, 73),
('John Milton', FALSE, 73),
('Homer', FALSE, 73),

('Sherlock Holmes', TRUE, 74),
('Hercule Poirot', FALSE, 74),
('Miss Marple', FALSE, 74),
('Sam Spade', FALSE, 74),

('Suecia', TRUE, 75),
('Indonesia', FALSE, 75),
('Filipinas', FALSE, 75),
('Noruega', FALSE, 75),

('Marte', TRUE, 76),
('Venus', FALSE, 76),
('Júpiter', FALSE, 76),
('Saturno', FALSE, 76),

('Qin Shi Huang', TRUE, 77),
('Kublai Khan', FALSE, 77),
('Emperador Wu', FALSE, 77),
('Yongle', FALSE, 77),

('Ciclismo', TRUE, 78),
('Automovilismo', FALSE, 78),
('Maratón', FALSE, 78),
('Esquí', FALSE, 78),

('Edvard Munch', TRUE, 79),
('Vincent van Gogh', FALSE, 79),
('Claude Monet', FALSE, 79),
('Gustav Klimt', FALSE, 79),

('Walter White', TRUE, 80),
('Jesse Pinkman', FALSE, 80),
('Hank Schrader', FALSE, 80),
('Saul Goodman', FALSE, 80),

('Lago Baikal', TRUE, 81),
('Lago Tanganica', FALSE, 81),
('Lago Superior', FALSE, 81),
('Lago Victoria', FALSE, 81),

('Mercurio', TRUE, 82),
('Plomo', FALSE, 82),
('Aluminio', FALSE, 82),
('Hierro', FALSE, 82),

('1789', TRUE, 83),
('1776', FALSE, 83),
('1799', FALSE, 83),
('1804', FALSE, 83),

('Hockey sobre hielo', TRUE, 84),
('Fútbol', FALSE, 84),
('Lacrosse', FALSE, 84),
('Curling', FALSE, 84),

('George Orwell', TRUE, 85),
('Aldous Huxley', FALSE, 85),
('Ray Bradbury', FALSE, 85),
('Kurt Vonnegut', FALSE, 85),

('El Rey León', TRUE, 86),
('La Bella y la Bestia', FALSE, 86),
('Aladdín', FALSE, 86),
('Mulan', FALSE, 86),

('Canberra', TRUE, 87),
('Sídney', FALSE, 87),
('Melbourne', FALSE, 87),
('Brisbane', FALSE, 87),

('Nitrógeno', TRUE, 88),
('Oxígeno', FALSE, 88),
('Argón', FALSE, 88),
('Dióxido de Carbono', FALSE, 88),

('Nicolás II', TRUE, 89),
('Pedro el Grande', FALSE, 89),
('Alejandro II', FALSE, 89),
('Iván el Terrible', FALSE, 89),

('Tenis', TRUE, 90),
('Críquet', FALSE, 90),
('Golf', FALSE, 90),
('Fútbol', FALSE, 90),

('Fiódor Dostoyevski', TRUE, 91),
('León Tolstói', FALSE, 91),
('Aleksandr Pushkin', FALSE, 91),
('Antón Chéjov', FALSE, 91),

('Disneyland', TRUE, 92),
('Universal Studios', FALSE, 92),
('SeaWorld', FALSE, 92),
('Magic Mountain', FALSE, 92),

('Mónaco', TRUE, 93),
('Singapur', FALSE, 93),
('Hong Kong', FALSE, 93),
('Ciudad del Vaticano', FALSE, 93),

('Vinagre', TRUE, 94),
('Alcohol', FALSE, 94),
('Glicerina', FALSE, 94),
('Metanol', FALSE, 94),

('1903', TRUE, 95),
('1899', FALSE, 95),
('1912', FALSE, 95),
('1920', FALSE, 95),

('Críquet', TRUE, 96),
('Hockey sobre césped', FALSE, 96),
('Kabaddi', FALSE, 96),
('Fútbol', FALSE, 96),

('Johannes Vermeer', TRUE, 97),
('Rembrandt', FALSE, 97),
('Jan van Eyck', FALSE, 97),
('Pieter Bruegel', FALSE, 97),

('Pink Floyd', TRUE, 98),
('The Beatles', FALSE, 98),
('Led Zeppelin', FALSE, 98),
('The Rolling Stones', FALSE, 98),

('Portugués', TRUE, 99),
('Español', FALSE, 99),
('Inglés', FALSE, 99),
('Francés', FALSE, 99),

('Electrón', TRUE, 100),
('Protón', FALSE, 100),
('Neutrón', FALSE, 100),
('Quark', FALSE, 100),

('Homero', TRUE, 101),
('Sófocles', FALSE, 101),
('Eurípides', FALSE, 101),
('Virgilio', FALSE, 101),

('Baloncesto', TRUE, 102),
('Fútbol', FALSE, 102),
('Voleibol', FALSE, 102),
('Rugby', FALSE, 102),

('Antoine de Saint-Exupéry', TRUE, 103),
('J.K. Rowling', FALSE, 103),
('Gabriel García Márquez', FALSE, 103),
('Ernest Hemingway', FALSE, 103),

('Dagobah', TRUE, 104),
('Tatooine', FALSE, 104),
('Naboo', FALSE, 104),
('Hoth', FALSE, 104),

('China', TRUE, 105),
('India', FALSE, 105),
('Estados Unidos', FALSE, 105),
('Indonesia', FALSE, 105),

('Vitamina D', TRUE, 106),
('Vitamina C', FALSE, 106),
('Vitamina A', FALSE, 106),
('Vitamina B12', FALSE, 106),

('Winston Churchill', TRUE, 107),
('Neville Chamberlain', FALSE, 107),
('Clement Attlee', FALSE, 107),
('Margaret Thatcher', FALSE, 107),

('Hockey sobre hielo', TRUE, 108),
('Lacrosse', FALSE, 108),
('Rugby', FALSE, 108),
('Béisbol', FALSE, 108),

('Diego Velázquez', TRUE, 109),
('Francisco Goya', FALSE, 109),
('El Greco', FALSE, 109),
('Bartolomé Esteban Murillo', FALSE, 109),

('Sauron', TRUE, 110),
('Saruman', FALSE, 110),
('Gollum', FALSE, 110),
('Smaug', FALSE, 110),

('Australia', TRUE, 111),
('Europa', FALSE, 111),
('Antártida', FALSE, 111),
('Sudamérica', FALSE, 111),

('Helio', TRUE, 112),
('Hidrógeno', FALSE, 112),
('Oxígeno', FALSE, 112),
('Carbono', FALSE, 112),

('Amelia Earhart', TRUE, 113),
('Harriet Quimby', FALSE, 113),
('Bessie Coleman', FALSE, 113),
('Valentina Tereshkova', FALSE, 113),

('Deportes extremos', TRUE, 114),
('Atletismo', FALSE, 114),
('Natación', FALSE, 114),
('Gimnasia', FALSE, 114),

('Jane Austen', TRUE, 115),
('Charlotte Brontë', FALSE, 115),
('Mary Shelley', FALSE, 115),
('Emily Brontë', FALSE, 115),

('Voldemort', TRUE, 116),
('Dumbledore', FALSE, 116),
('Severus Snape', FALSE, 116),
('Hagrid', FALSE, 116),

('Brasil', TRUE, 117),
('Argentina', FALSE, 117),
('Colombia', FALSE, 117),
('Perú', FALSE, 117),

('Óxido nitroso', TRUE, 118),
('Dióxido de carbono', FALSE, 118),
('Metano', FALSE, 118),
('Oxígeno', FALSE, 118),

('Fidel Castro', TRUE, 119),
('Che Guevara', FALSE, 119),
('Raúl Castro', FALSE, 119),
('Camilo Cienfuegos', FALSE, 119),

('Rugby', TRUE, 120),
('Baloncesto', FALSE, 120),
('Tenis', FALSE, 120),
('Golf', FALSE, 120),

('Oscar Wilde', TRUE, 121),
('Charles Dickens', FALSE, 121),
('Edgar Allan Poe', FALSE, 121),
('Mark Twain', FALSE, 121),

('Disneyland Paris', TRUE, 122),
('Disney World', FALSE, 122),
('Universal Studios Paris', FALSE, 122),
('Magic Kingdom Paris', FALSE, 122),

('Volga', TRUE, 123),
('Danubio', FALSE, 123),
('Rin', FALSE, 123),
('Elba', FALSE, 123),

('Potasio', TRUE, 124),
('Calcio', FALSE, 124),
('Krypton', FALSE, 124),
('Fósforo', FALSE, 124),

('Abraham Lincoln', TRUE, 125),
('George Washington', FALSE, 125),
('Thomas Jefferson', FALSE, 125),
('John Adams', FALSE, 125),

('Tiro con arco', TRUE, 126),
('Esgrima', FALSE, 126),
('Natación', FALSE, 126),
('Atletismo', FALSE, 126),

('Salvador Dalí', TRUE, 127),
('René Magritte', FALSE, 127),
('Max Ernst', FALSE, 127),
('Joan Miró', FALSE, 127),

('Eleven', TRUE, 128),
('Mike', FALSE, 128),
('Lucas', FALSE, 128),
('Dustin', FALSE, 128),

('Yen', TRUE, 129),
('Dólar', FALSE, 129),
('Euro', FALSE, 129),
('Peso', FALSE, 129),

('Alexander Fleming', TRUE, 130),
('Marie Curie', FALSE, 130),
('Louis Pasteur', FALSE, 130),
('Isaac Newton', FALSE, 130),

('1776', TRUE, 131),
('1783', FALSE, 131),
('1775', FALSE, 131),
('1781', FALSE, 131),

('Bádminton', TRUE, 132),
('Tenis', FALSE, 132),
('Voleibol', FALSE, 132),
('Squash', FALSE, 132),

('Ernest Hemingway', TRUE, 133),
('F. Scott Fitzgerald', FALSE, 133),
('Mark Twain', FALSE, 133),
('John Steinbeck', FALSE, 133),

('Smaug', TRUE, 134),
('Drogon', FALSE, 134),
('Fírnen', FALSE, 134),
('Glaurung', FALSE, 134),

('El Vaticano', TRUE, 135),
('Mónaco', FALSE, 135),
('San Marino', FALSE, 135),
('Liechtenstein', FALSE, 135),

('Marte', TRUE, 136),
('Júpiter', FALSE, 136),
('Saturno', FALSE, 136),
('Venus', FALSE, 136),

('Nelson Mandela', TRUE, 137),
('Desmond Tutu', FALSE, 137),
('F. W. de Klerk', FALSE, 137),
('Jacob Zuma', FALSE, 137),

('Voleibol', TRUE, 138),
('Tenis', FALSE, 138),
('Baloncesto', FALSE, 138),
('Balonmano', FALSE, 138),

('Edvard Munch', TRUE, 139),
('Gustav Klimt', FALSE, 139),
('Claude Monet', FALSE, 139),
('Vincent van Gogh', FALSE, 139),

('RMS Titanic', TRUE, 140),
('SS Titanic', FALSE, 140),
('HMS Titanic', FALSE, 140),
('MS Titanic', FALSE, 140),

('Desierto de Sonora', TRUE, 141),
('Desierto de Mojave', FALSE, 141),
('Desierto de Chihuahua', FALSE, 141),
('Desierto de Great Basin', FALSE, 141),

('Sodio', TRUE, 142),
('Nitrógeno', FALSE, 142),
('Neón', FALSE, 142),
('Níquel', FALSE, 142),

('Joseph Stalin', TRUE, 143),
('Vladimir Lenin', FALSE, 143),
('Leon Trotsky', FALSE, 143),
('Nikita Khrushchev', FALSE, 143),

('Ciclismo', TRUE, 144),
('Automovilismo', FALSE, 144),
('Atletismo', FALSE, 144),
('Motociclismo', FALSE, 144),

('Fyodor Dostoevsky', TRUE, 145),
('Leo Tolstoy', FALSE, 145),
('Anton Chekhov', FALSE, 145),
('Vladimir Nabokov', FALSE, 145),

('Joker', TRUE, 146),
('Penguin', FALSE, 146),
('Riddler', FALSE, 146),
('Two-Face', FALSE, 146),

('Tokio', TRUE, 147),
('Nueva York', FALSE, 147),
('Delhi', FALSE, 147),
('Shanghái', FALSE, 147),

('Isaac Newton', TRUE, 148),
('Albert Einstein', FALSE, 148),
('Galileo Galilei', FALSE, 148),
('Johannes Kepler', FALSE, 148),

('1789', TRUE, 149),
('1776', FALSE, 149),
('1804', FALSE, 149),
('1815', FALSE, 149),

('Fútbol Americano', TRUE, 150),
('Béisbol', FALSE, 150),
('Baloncesto', FALSE, 150),
('Hockey sobre hielo', FALSE, 150),

('Homero', TRUE, 151),
('Sófocles', FALSE, 151),
('Virgilio', FALSE, 151),
('Eurípides', FALSE, 151),

('Simba', TRUE, 152),
('Mufasa', FALSE, 152),
('Scar', FALSE, 152),
('Nala', FALSE, 152),

('Portugués', TRUE, 153),
('Español', FALSE, 153),
('Inglés', FALSE, 153),
('Francés', FALSE, 153),

('Ácido desoxirribonucleico', TRUE, 154),
('Adenina de nucleótidos', FALSE, 154),
('Ácido ribonucleico', FALSE, 154),
('Aminoácido neoclásico', FALSE, 154),

('1991', TRUE, 155),
('1989', FALSE, 155),
('1990', FALSE, 155),
('1992', FALSE, 155),

('Ciclismo', TRUE, 156),
('Atletismo', FALSE, 156),
('Motociclismo', FALSE, 156),
('Automovilismo', FALSE, 156),

('Franz Kafka', TRUE, 157),
('Friedrich Nietzsche', FALSE, 157),
('James Joyce', FALSE, 157),
('Marcel Proust', FALSE, 157),

('Walter White', TRUE, 158),
('Jesse Pinkman', FALSE, 158),
('Hank Schrader', FALSE, 158),
('Saul Goodman', FALSE, 158),

('Canberra', TRUE, 159),
('Sídney', FALSE, 159),
('Melbourne', FALSE, 159),
('Brisbane', FALSE, 159),

('Páncreas', TRUE, 160),
('Hígado', FALSE, 160),
('Riñón', FALSE, 160),
('Estómago', FALSE, 160),

('1914', TRUE, 161),
('1915', FALSE, 161),
('1916', FALSE, 161),
('1917', FALSE, 161),

('Baloncesto', TRUE, 162),
('Voleibol', FALSE, 162),
('Tenis', FALSE, 162),
('Fútbol', FALSE, 162),

('Antoine de Saint-Exupéry', TRUE, 163),
('Gabriel García Márquez', FALSE, 163),
('Paulo Coelho', FALSE, 163),
('Miguel de Cervantes', FALSE, 163),

('Neo', TRUE, 164),
('Morpheus', FALSE, 164),
('Trinity', FALSE, 164),
('Smith', FALSE, 164),

('Volga', TRUE, 165),
('Danubio', FALSE, 165),
('Rin', FALSE, 165),
('Elba', FALSE, 165),

('Electrón', TRUE, 166),
('Protón', FALSE, 166),
('Neutrón', FALSE, 166),
('Quark', FALSE, 166),

('Winston Churchill', TRUE, 167),
('Neville Chamberlain', FALSE, 167),
('Clement Attlee', FALSE, 167),
('Anthony Eden', FALSE, 167),

('Surf', TRUE, 168),
('Skateboard', FALSE, 168),
('Snowboard', FALSE, 168),
('Windsurf', FALSE, 168),

('Salvador Dalí', TRUE, 169),
('Pablo Picasso', FALSE, 169),
('Vincent van Gogh', FALSE, 169),
('Joan Miró', FALSE, 169),

('Peter Parker', TRUE, 170),
('Clark Kent', FALSE, 170),
('Bruce Wayne', FALSE, 170),
('Tony Stark', FALSE, 170),

('Aconcagua', TRUE, 171),
('Montaña de Cristal', FALSE, 171),
('Monte Pissis', FALSE, 171),
('Nevado Ojos del Salado', FALSE, 171),

('Vitamina D', TRUE, 172),
('Vitamina A', FALSE, 172),
('Vitamina C', FALSE, 172),
('Vitamina B12', FALSE, 172),

('476 d.C.', TRUE, 173),
('410 d.C.', FALSE, 173),
('1453 d.C.', FALSE, 173),
('1492 d.C.', FALSE, 173),

('Fútbol Americano', TRUE, 174),
('Béisbol', FALSE, 174),
('Baloncesto', FALSE, 174),
('Hockey sobre hielo', FALSE, 174),

('Harper Lee', TRUE, 175),
('J.D. Salinger', FALSE, 175),
('Truman Capote', FALSE, 175),
('F. Scott Fitzgerald', FALSE, 175),

('Perla Negra', TRUE, 176),
('Halcón Milenario', FALSE, 176),
('Nautilus', FALSE, 176),
('Reina Ana', FALSE, 176),

('Ottawa', TRUE, 177),
('Toronto', FALSE, 177),
('Vancouver', FALSE, 177),
('Montreal', FALSE, 177),

('El proceso por el cual las plantas convierten la luz solar en energía', TRUE, 178),
('El proceso de respiración celular', FALSE, 178),
('El ciclo del agua', FALSE, 178),
('La formación de rocas', FALSE, 178),

('1776', TRUE, 179),
('1783', FALSE, 179),
('1775', FALSE, 179),
('1781', FALSE, 179),

('Bádminton', TRUE, 180),
('Tenis', FALSE, 180),
('Voleibol', FALSE, 180),
('Ping-pong', FALSE, 180),

('George Orwell', TRUE, 181),
('Aldous Huxley', FALSE, 181),
('Ray Bradbury', FALSE, 181),
('Philip K. Dick', FALSE, 181),

('R2-D2', TRUE, 182),
('C-3PO', FALSE, 182),
('BB-8', FALSE, 182),
('K-2SO', FALSE, 182),

('Suecia', TRUE, 183),
('Filipinas', FALSE, 183),
('Indonesia', FALSE, 183),
('Noruega', FALSE, 183),

('Una medida de acidez o alcalinidad de una solución', TRUE, 184),
('Un tipo de proteína', FALSE, 184),
('Un tipo de azúcar', FALSE, 184),
('Una unidad de energía', FALSE, 184),

('1945', TRUE, 185),
('1944', FALSE, 185),
('1943', FALSE, 185),
('1946', FALSE, 185),

('Hockey sobre hielo', TRUE, 186),
('Curling', FALSE, 186),
('Patinaje artístico', FALSE, 186),
('Esquí', FALSE, 186),

('Jane Austen', TRUE, 187),
('Charlotte Brontë', FALSE, 187),
('Emily Brontë', FALSE, 187),
('Louisa May Alcott', FALSE, 187),

('Disneyland', TRUE, 188),
('Disney World', FALSE, 188),
('Epcot', FALSE, 188),
('Magic Kingdom', FALSE, 188),

('Ciudad del Vaticano', TRUE, 189),
('Mónaco', FALSE, 189),
('San Marino', FALSE, 189),
('Liechtenstein', FALSE, 189),

('Riñones', TRUE, 190),
('Pulmones', FALSE, 190),
('Hígado', FALSE, 190),
('Corazón', FALSE, 190),

('1917', TRUE, 191),
('1918', FALSE, 191),
('1916', FALSE, 191),
('1919', FALSE, 191),

('Tenis', TRUE, 192),
('Críquet', FALSE, 192),
('Golf', FALSE, 192),
('Fútbol', FALSE, 192),

('William Shakespeare', TRUE, 193),
('Christopher Marlowe', FALSE, 193),
('Ben Jonson', FALSE, 193),
('John Milton', FALSE, 193),

('Voldemort', TRUE, 194),
('Dumbledore', FALSE, 194),
('Snape', FALSE, 194),
('Sirius', FALSE, 194),

('Antártida', TRUE, 195),
('Ártico', FALSE, 195),
('Europa', FALSE, 195),
('Asia', FALSE, 195),

('Una explicación científica sobre el origen del universo', TRUE, 196),
('Una teoría sobre la extinción de los dinosaurios', FALSE, 196),
('Una teoría sobre la formación de la Tierra', FALSE, 196),
('Una explicación sobre el cambio climático', FALSE, 196),

('1919', TRUE, 197),
('1918', FALSE, 197),
('1920', FALSE, 197),
('1921', FALSE, 197),

('Golf', TRUE, 198),
('Hockey sobre césped', FALSE, 198),
('Críquet', FALSE, 198),
('Polo', FALSE, 198),

('Edvard Munch', TRUE, 199),
('Claude Monet', FALSE, 199),
('Vincent van Gogh', FALSE, 199),
('Pablo Picasso', FALSE, 199),

('Frodo Baggins', TRUE, 200),
('Bilbo Baggins', FALSE, 200),
('Gandalf', FALSE, 200),
('Aragorn', FALSE, 200);



