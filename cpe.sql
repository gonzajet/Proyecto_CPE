
	CREATE TABLE public.sector (
	       sector_id    SERIAL PRIMARY KEY,
	       descripcion   varchar(140),
	       shortname	varchar(15)
	);

	CREATE TABLE public.usuario (
	       usuario_id     SERIAL PRIMARY KEY,
	       sector_id     integer REFERENCES sector,
	       nombre        varchar(40),
	       apellido        varchar(40),
	       passwordUser varchar(250) not null,
	       mailUser varchar(250) not null,
	       authkeyUser varchar(250) ,
	       activUser integer DEFAULT 0
	       --avatar        varchar(120)

	);

	CREATE TABLE public.instituto (
	       instituto_id     SERIAL PRIMARY KEY,
	       nombre       varchar(50)
	);


	CREATE TABLE public.carrera (
	       carrera_id    SERIAL PRIMARY KEY,
	       instituto_id  integer REFERENCES instituto,
	       descripcion   varchar(75)
	);

	CREATE TABLE public.usuariocarrera (
               usuariocarrera_id SERIAL PRIMARY KEY,
               usuario_id     integer REFERENCES usuario,
               carrera_id     integer REFERENCES carrera
        );
     
	CREATE TABLE public.ano (
	       ano_id    SERIAL PRIMARY KEY,
	       ano       integer
	);
	
	CREATE TABLE public.planestudio (
	       planestudio_id    SERIAL PRIMARY KEY,
	       carrera_id  integer REFERENCES carrera,
	       ano_id  integer REFERENCES ano,
		   plan   varchar(60)
	);
	
	CREATE TABLE public.materia (
	       materia_id    SERIAL PRIMARY KEY,
	       nombre        varchar(180),
	       optativa      boolean
	);

	CREATE TABLE public.planmateria (
	       planmateria_id    SERIAL PRIMARY KEY,
	       planestudio_id  integer REFERENCES planestudio,
	       materia_id  integer REFERENCES materia
	);

	CREATE TABLE public.programa (
		programa_id  SERIAL PRIMARY KEY,
		planmateria_id  integer REFERENCES planmateria,
		ano_id  integer REFERENCES ano,
		fecha date,
	    descripcion   varchar(175)
	);

	CREATE TABLE public.estado (
	       estado_id     SERIAL PRIMARY KEY,
	       descripcion   varchar(40)
	);

	CREATE TABLE public.moderw (
		moderw_id SERIAL PRIMARY KEY,
		moderw     varchar(100) not null
	);
	
	CREATE TABLE public.archivoprograma (
		archivoprograma_id SERIAL PRIMARY KEY,
		programa_id   integer REFERENCES programa,
		usuario_id    integer REFERENCES usuario,
		estado_id     integer REFERENCES estado,
		archivo       varchar(100) not null,
		fecha date,
		moderw_id integer REFERENCES moderw
	);


---
-- Acciones registradas de todo el circuito administrativo (tabla fija)
-- El objetivo de esta tabla es poder completar la tabla asignsector que hace funcionar al
-- comando app\commands\RoleAccessChecker
	CREATE TABLE public.actionrole (
		actionrole_id SERIAL PRIMARY KEY,
		action_disp       varchar(100) not null,
	    descripcion   varchar(40)
	);
	
---
-- Acciones registradas de todo el circuito administrativo (tabla fija)
-- El objetivo de esta tabla es poder completar la tabla asignsector que hace funcionar al
-- comando app\commands\RoaleAccessChecker
	CREATE TABLE public.asignsector (
		asignsector_id SERIAL PRIMARY KEY,
		actionrole_id  integer REFERENCES actionrole,
	    sector_id   integer REFERENCES sector,
	    UNIQUE(actionrole_id,sector_id)
	);

	CREATE TYPE enum_ano_niv AS ENUM (
		' - ',
		'1º año',
		'2º año',
		'3º año',
		'4º año',
		'5º año',
		'6º año');

	CREATE TABLE public.planes(
		planes_id SERIAL PRIMARY KEY,
		ano_id  integer REFERENCES ano,
		carrera_id  integer REFERENCES carrera,
		ano_nivel enum_ano_niv,
		instituto_id integer REFERENCES instituto,
		materia_id  integer REFERENCES materia
	);
	
	CREATE TABLE public.historial
	(
		historial_id SERIAL PRIMARY KEY,
		usuario_id integer REFERENCES usuario,
		programa_id integer REFERENCES programa,
		archivoprograma_id integer REFERENCES archivoprograma,
		archivo varchar(255),
		comentario varchar(255)
	);

	---
	--FUNCION TRIGGER. Inserta los id de carrera y ano (de la tabla planes) en la tabla planestudio,  
	--en la tabla planmateria y en la tabla programa
	CREATE OR REPLACE FUNCTION test()
	RETURNS trigger AS
	$$
	BEGIN
		INSERT INTO planestudio(carrera_id,ano_id) VALUES(NEW.carrera_id,NEW.ano_id);
		INSERT INTO planmateria(planestudio_id,materia_id)VALUES (
			(SELECT planestudio_id FROM planestudio WHERE carrera_id=
				(SELECT NEW.carrera_id FROM carrera WHERE carrera_id=NEW.carrera_id)
				AND ano_id=NEW.ano_id)
			,NEW.materia_id);
		INSERT INTO programa(planmateria_id,ano_id,fecha,descripcion)
			VALUES ((SELECT planmateria_id FROM planmateria WHERE planestudio_id=(
				SELECT planestudio_id FROM planestudio WHERE carrera_id=NEW.carrera_id AND ano_id=NEW.ano_id)
				AND materia_id=NEW.materia_id),
				NEW.ano_id,(SELECT CURRENT_DATE),
				(SELECT CONCAT((SELECT nombre FROM instituto WHERE instituto_id=NEW.instituto_id),'-',
				(SELECT descripcion FROM carrera WHERE carrera_id=NEW.carrera_id),'-',
				(SELECT nombre FROM materia WHERE materia_id=NEW.materia_id),'-',
				(SELECT ano FROM ano WHERE ano_id=NEW.ano_id))));
RETURN NEW;
	END;
	$$ language plpgsql;
	
	--TRIGGER DECLARADO. Dispara el trigger luego de que se insertan valores en planes. Se guardan en planestudio
	CREATE TRIGGER planestudio
		AFTER INSERT
		ON planes
		FOR EACH ROW
		EXECUTE PROCEDURE test();

COMMIT;
