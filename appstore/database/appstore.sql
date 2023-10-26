CREATE DATABASE appstore;
USE appstore;

CREATE TABLE categorias (
	idcategoria INT PRIMARY KEY AUTO_INCREMENT,
	categoria 	VARCHAR(30)	NOT NULL,
	create_at 	DATETIME		DEFAULT NOW(),
	update_at	DATETIME		NULL,
	inactive_at	CHAR(1)		NULL
)ENGINE = INNODB;


CREATE TABLE productos(
	idproducto		INT PRIMARY KEY AUTO_INCREMENT,
	idcategoria 	INT 			NOT NULL,
	descripcion 	VARCHAR(150)	NOT NULL,
	precio			FLOAT(7,2)		NOT NULL,
	garantia		TINYINT			NOT NULL,
	fotografia		VARCHAR(200)	NULL,
	create_at 		DATETIME		DEFAULT NOW(),
	update_at		DATETIME		NULL,
	inactive_at		DATETIME 		NULL,
	CONSTRAINT fk_idcategoria FOREIGN KEY (idcategoria) REFERENCES categorias(idcategoria)
)ENGINE = INNODB;

INSERT INTO categorias(categoria) VALUES
	('Computadoras'),
	('Telefonos Moviles'),
	('Monitores'),
	('Accesorios'),
	('Perifericos');

DELIMITER $$
CREATE PROCEDURE spu_productos_listar ()
BEGIN 
	SELECT pro.idproducto, 
	cat.categoria, 
	pro.descripcion, 
	pro.precio, 
	pro.garantia, 
	pro.fotografia
	FROM productos pro
	INNER JOIN categorias cat ON cat.idcategoria = pro.idcategoria
	WHERE pro.inactive_at IS NULL;
END $$

DELIMITER $$
CREATE PROCEDURE spu_productos_buscar (IN idproducto INT)
BEGIN 
	SELECT pro.idproducto, 
	cat.categoria, 
	pro.descripcion, 
	pro.precio, 
	pro.garantia, 
	pro.fotografia
	FROM productos pro
	INNER JOIN categorias cat ON pro.idcategoria = cat.idcategoria
	WHERE pro.idproducto = idproducto;
END $$

DELIMITER $$
CREATE PROCEDURE spu_productos_registrar
(
	IN _idcategoria		INT,
	IN _descripcion 	VARCHAR(150),
	IN _precio			FLOAT(7,2),
	IN _garantia		TINYINT,
	IN _fotografia		VARCHAR(200)
)
BEGIN
	INSERT INTO productos
		(idcategoria, descripcion, precio, garantia, fotografia)
		VALUES
		(_idcategoria, _descripcion, _precio, _garantia, NULLIF(_fotografia, ''));
	
    SELECT @@last_insert_id 'idproducto';
END $$


DELIMITER $$
CREATE PROCEDURE spu_productos_eliminar(IN _idproducto INT)
BEGIN
	-- Eliminación lógica => UPDATE
    -- Eliminación física => DELETE
    UPDATE productos 
		SET inactive_at = NOW() 
        WHERE idproducto = _idproducto;
END $$

-- En cualquier proceso de consulta/listado/búsqueda, debemos recuperar PK
DELIMITER $$
CREATE PROCEDURE spu_categorias_listar()
BEGIN
	SELECT idcategoria, categoria 
		FROM categorias
		WHERE inactive_at IS NULL;
END $$

SELECT * FROM productos;

DELIMITER $$
CREATE PROCEDURE spu_categorias_registrar(
	IN _categoria 	VARCHAR(30)
)
BEGIN
	INSERT INTO categorias (categoria) VALUES (_categoria);
END $$


CREATE VIEW vs_productos_categorias
AS
	SELECT
		PRD.idproducto,
        PRD.idcategoria,
		CAT.categoria,
		PRD.descripcion,
		PRD.precio, 
		PRD.garantia,
		PRD.fotografia, PRD.create_at
		FROM productos PRD
		INNER JOIN categorias CAT ON CAT.idcategoria = PRD.idcategoria
		WHERE PRD.inactive_at IS NULL
		LIMIT 12;
	
DELIMITER $$
CREATE PROCEDURE spu_productos_filtrar_categoria(IN _idcategoria INT)
BEGIN
	IF _idcategoria = -1 THEN
		SELECT * FROM vs_productos_categorias ORDER BY create_at;
	ELSE
		SELECT * FROM vs_productos_categorias WHERE idcategoria = _idcategoria ORDER BY create_at;
    END IF;
	
END $$

CALL spu_productos_filtrar_categoria(5);
SELECT * FROM productos;