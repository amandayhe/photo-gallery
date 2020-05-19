-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;

-- TODO: create tables
CREATE TABLE images (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	file_name TEXT NOT NULL,
	file_ext TEXT NOT NULL,
	photographer TEXT
);

CREATE TABLE tags (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
    tag TEXT NOT NULL UNIQUE
);

CREATE TABLE image_tags (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	image_id INTEGER NOT NULL,
    tag_id INTEGER NOT NULL
);

-- TODO: initial seed data
INSERT INTO images (file_name, file_ext, photographer) VALUES ('oona_1.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('oona_2.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('karen_1.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('karen_2.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('daniela_1.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('daniela_2.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('sophia.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('evelyn.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('preeth.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('gcbstudios.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('audrey.jpg', 'jpg', 'Amanda He');
INSERT INTO images (file_name, file_ext, photographer) VALUES ('headshot.jpg', 'jpg', 'Amanda He');

INSERT INTO tags (tag) VALUES ('fashion');
INSERT INTO tags (tag) VALUES ('lifestyle');
INSERT INTO tags (tag) VALUES ('graduation');
INSERT INTO tags (tag) VALUES ('editorial');
INSERT INTO tags (tag) VALUES ('artistic');
INSERT INTO tags (tag) VALUES ('headshot');

INSERT INTO image_tags(image_id, tag_id) VALUES (1, 1);
INSERT INTO image_tags(image_id, tag_id) VALUES (1, 4);
INSERT INTO image_tags(image_id, tag_id) VALUES (1, 5);
INSERT INTO image_tags(image_id, tag_id) VALUES (2, 1);
INSERT INTO image_tags(image_id, tag_id) VALUES (2, 4);
INSERT INTO image_tags(image_id, tag_id) VALUES (2, 5);
INSERT INTO image_tags(image_id, tag_id) VALUES (3, 1);
INSERT INTO image_tags(image_id, tag_id) VALUES (3, 4);
INSERT INTO image_tags(image_id, tag_id) VALUES (3, 5);
INSERT INTO image_tags(image_id, tag_id) VALUES (4, 1);
INSERT INTO image_tags(image_id, tag_id) VALUES (4, 4);
INSERT INTO image_tags(image_id, tag_id) VALUES (4, 5);
INSERT INTO image_tags(image_id, tag_id) VALUES (5, 2);
INSERT INTO image_tags(image_id, tag_id) VALUES (5, 3);
INSERT INTO image_tags(image_id, tag_id) VALUES (6, 2);
INSERT INTO image_tags(image_id, tag_id) VALUES (6, 3);
INSERT INTO image_tags(image_id, tag_id) VALUES (5, 1);
INSERT INTO image_tags(image_id, tag_id) VALUES (7, 2);
INSERT INTO image_tags(image_id, tag_id) VALUES (7, 3);
INSERT INTO image_tags(image_id, tag_id) VALUES (8, 2);
INSERT INTO image_tags(image_id, tag_id) VALUES (9, 5);
INSERT INTO image_tags(image_id, tag_id) VALUES (10, 4);
INSERT INTO image_tags(image_id, tag_id) VALUES (11, 5);
INSERT INTO image_tags(image_id, tag_id) VALUES (11, 6);
INSERT INTO image_tags(image_id, tag_id) VALUES (12, 6);

COMMIT;
