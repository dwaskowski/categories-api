/* Replace this file with actual dump of your database */

DELETE FROM category WHERE name LIKE 'TestName%';

INSERT INTO category(uuid, name, slug, isVisible) VALUES('uuid', 'TestNameOne', 'tno', false);
