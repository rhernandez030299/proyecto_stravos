/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

DROP DATABASE IF EXISTS `proyecto`;
CREATE DATABASE IF NOT EXISTS `proyecto` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `proyecto`;

DROP TABLE IF EXISTS `archivo_historia`;
CREATE TABLE IF NOT EXISTS `archivo_historia` (
  `idarchivo_historia` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idhistoria` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla historia',
  `ruta` varchar(255) NOT NULL DEFAULT '' COMMENT 'Corresponde al nombre generado por el sistema',
  `client_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Corresponde al nombre del archivo ingresado por el usuario',
  `peso` varchar(255) NOT NULL DEFAULT '' COMMENT 'Corresponde al peso del archivo ',
  PRIMARY KEY (`idarchivo_historia`),
  KEY `idhistoria` (`idhistoria`),
  CONSTRAINT `FK_archivo_historia_historia` FOREIGN KEY (`idhistoria`) REFERENCES `historia` (`idhistoria`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el nombre de todos los documentos, pdf, imagenes, entre otros que haran parte de la historia de usuario';

DROP TABLE IF EXISTS `archivo_metodologia`;
CREATE TABLE IF NOT EXISTS `archivo_metodologia` (
  `idarchivo_metodologia` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idmetodologia` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla metodologia',
  `ruta` varchar(255) NOT NULL DEFAULT '' COMMENT 'Corresponde al nombre generado por el sistema',
  `client_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Corresponde al nombre del archivo ingresado por el usuario',
  `peso` varchar(255) NOT NULL DEFAULT '' COMMENT 'Corresponde al peso del archivo ',
  PRIMARY KEY (`idarchivo_metodologia`),
  KEY `idmetodologia` (`idmetodologia`),
  CONSTRAINT `FK_archivo_metodologia_metodologia` FOREIGN KEY (`idmetodologia`) REFERENCES `metodologia` (`idmetodologia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el nombre de todos los documentos, pdf, imagenes, entre otros que haran parte de la metodologia\r\n';

DROP TABLE IF EXISTS `archivo_proyecto`;
CREATE TABLE IF NOT EXISTS `archivo_proyecto` (
  `idarchivo_proyecto` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idproyecto` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla proyecto',
  `ruta` varchar(255) NOT NULL DEFAULT '' COMMENT 'Corresponde al nombre generado por el sistema',
  `client_name` varchar(255) NOT NULL DEFAULT '' COMMENT 'Corresponde al nombre del archivo ingresado por el usuario',
  `peso` varchar(255) NOT NULL DEFAULT '' COMMENT 'Corresponde al peso del archivo ',
  PRIMARY KEY (`idarchivo_proyecto`),
  KEY `idproyecto` (`idproyecto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el nombre de todos los documentos, pdf, imagenes, entre otros que haran parte del proyecto';

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE IF NOT EXISTS `categoria` (
  `idcategoria` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(255) NOT NULL COMMENT 'Corresponde al nombre de la categoría ',
  PRIMARY KEY (`idcategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el nombre de las categoría ';

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) NOT NULL COMMENT 'Corresponde a la llave principal de la tabla',
  `ip_address` varchar(45) NOT NULL COMMENT 'Corresponde a la ip publica del usuario ',
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Corresponde a la fecha donde se ingresa el registro ',
  `data` blob NOT NULL COMMENT 'Corresponde a la data registrada por el usuario, como el nombre, correo, entre otros datos'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='En esta tabla se almacenara los datos de sesion del usuario ';

DROP TABLE IF EXISTS `fase`;
CREATE TABLE IF NOT EXISTS `fase` (
  `idfase` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idproyecto_metodologia` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla proyeto metodologia',
  `nombre` varchar(50) NOT NULL COMMENT 'Corresponde al nombre de la fase',
  `url` varchar(250) DEFAULT NULL COMMENT 'Corresponde a la ruta de la fase, este no puede tener espacios ni caracteres especiales',
  `posicion` int(11) DEFAULT NULL COMMENT 'Corresponde a la posicón de la fase, la cual se encarga de darle un orden a las fase',
  `estado` int(2) DEFAULT 1 COMMENT 'Corresponde al estado de la fase, 1: activo, 0: inativo',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idfase`),
  KEY `FK__fase_metodologia` (`idproyecto_metodologia`),
  CONSTRAINT `FK__fase_proyecto_metodologia` FOREIGN KEY (`idproyecto_metodologia`) REFERENCES `proyecto_metodologia` (`idproyecto_metodologia`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro de las fases';

DROP TABLE IF EXISTS `formulario`;
CREATE TABLE IF NOT EXISTS `formulario` (
  `idformulario` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(255) NOT NULL COMMENT 'Corresponde al nombre del formulario',
  `descripcion` varchar(255) DEFAULT NULL COMMENT 'Corresponde a una breve descripción del formiulario',
  `estado` tinyint(3) unsigned NOT NULL DEFAULT 1 COMMENT 'Corresponde al estado del formulario,1: activo, 2: programado, 3: inactivo',
  `fecha_inicio` date DEFAULT NULL COMMENT 'Corresponde a la fecha inicial del formulario el cual se encargara de validar si esta publico el formulario',
  `fecha_final` date DEFAULT NULL COMMENT 'Corresponde a la fecha final del formulario el cual se encargara de validar si esta publico el formulario',
  `idusuario_creacion` int(11) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla usuario',
  PRIMARY KEY (`idformulario`),
  KEY `idusuario_creacion` (`idusuario_creacion`),
  CONSTRAINT `formulario_idusuario_creacion_usuario` FOREIGN KEY (`idusuario_creacion`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara los datos del formulario con el fin de evaluar a los estudiantes o profesores';

DROP TABLE IF EXISTS `formulario_participante`;
CREATE TABLE IF NOT EXISTS `formulario_participante` (
  `idformulario_participante` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idusuario` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla usuario',
  `idformulario` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla formulario',
  `estado` int(10) unsigned DEFAULT 0 COMMENT 'Corresponde al estado, 0: pendiente de respuesta, 1: resuelto',
  PRIMARY KEY (`idformulario_participante`),
  KEY `idusuario` (`idusuario`),
  KEY `idformulario` (`idformulario`),
  CONSTRAINT `formulario_participante_idformulario` FOREIGN KEY (`idformulario`) REFERENCES `formulario` (`idformulario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `formulario_participante_idusuario_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara los datos resultantes del usuario y el formulario con el fin de anexar el formulario al usuario.';

DROP TABLE IF EXISTS `grupo`;
CREATE TABLE IF NOT EXISTS `grupo` (
  `idgrupo` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(50) DEFAULT NULL COMMENT 'Corresponde al nombre del grupo',
  `idusuario_creacion` int(11) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla usuario',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idgrupo`),
  KEY `idusuario_creacion` (`idusuario_creacion`),
  CONSTRAINT `FK_grupo_usuario` FOREIGN KEY (`idusuario_creacion`) REFERENCES `usuario` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el nombre los grupos de estudiantes registrados';

DROP TABLE IF EXISTS `grupo_usuario`;
CREATE TABLE IF NOT EXISTS `grupo_usuario` (
  `idgrupo_usuario` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idgrupo` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla grupo',
  `idusuario` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla usuario',
  PRIMARY KEY (`idgrupo_usuario`),
  KEY `idgrupo` (`idgrupo`),
  KEY `idusuario` (`idusuario`),
  CONSTRAINT `FK_grupo_usuario_grupo` FOREIGN KEY (`idgrupo`) REFERENCES `grupo` (`idgrupo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_grupo_usuario_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara los datos resultantes del grupo y el usuario';

DROP TABLE IF EXISTS `historia`;
CREATE TABLE IF NOT EXISTS `historia` (
  `idhistoria` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `titulo` varchar(150) NOT NULL COMMENT 'Corresponde al título de la historia de usuario',
  `fecha_ini` date NOT NULL COMMENT 'Corresponde a la fecha inicial de la historia de usuario',
  `fecha_fin` date NOT NULL COMMENT 'Corresponde a la fecha final de la historia de usuario',
  `objetivo` varchar(150) NOT NULL COMMENT 'Corresponde al objetivo principal de la historia de usuario',
  `riesgodesarrollo` varchar(150) NOT NULL COMMENT 'Corresponde al riesgo que con lleva desarrollar la historia de usuario',
  `descripcion` text NOT NULL DEFAULT '' COMMENT 'Corresponde a la descripción de la historia de usuario',
  `observaciones` varchar(150) DEFAULT ' ' COMMENT 'Corresponde a las observaciones realizadas por el tutor',
  `tiempo_estimado` varchar(150) NOT NULL COMMENT 'Corresponde al tiempo estimado para el desarrollo de la historia de usuario',
  `estado` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Corresponde al estado, 0. historia pendiente, 1. historia aprobada, 2. historia entregada, 3. historia incompleta, 4. historia finalizada',
  `numeracion` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Corresponde a un número identificativo en la historia de usuario',
  `posicion` tinyint(3) unsigned DEFAULT NULL COMMENT 'Corresponde a la posicón de la historia, la cual se encarga de darle un orden a las historia',
  `idprioridad` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla prioridad',
  `idriesgo_desarrollo` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla riesgo de desarrollo',
  `idusuario` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla usuario',
  `idmodulo` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla modulo',
  `idusuario_modifica` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla usuario',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idhistoria`),
  KEY `FK_historia_modulo` (`idmodulo`),
  KEY `FK_historia_ususario` (`idusuario`),
  KEY `prioridad` (`idprioridad`),
  KEY `idriesgo_desarrollo` (`idriesgo_desarrollo`),
  KEY `idusuario_modifica` (`idusuario_modifica`),
  CONSTRAINT `FK_historia_modulo` FOREIGN KEY (`idmodulo`) REFERENCES `modulo` (`idmodulo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_historia_prioridad` FOREIGN KEY (`idprioridad`) REFERENCES `prioridad` (`idprioridad`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_historia_riesgo_desarrollo` FOREIGN KEY (`idriesgo_desarrollo`) REFERENCES `riesgo_desarrollo` (`idriesgo_desarrollo`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_historia_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara los datos de la historia de usuario registrada por el usuario \r\n';

DROP TABLE IF EXISTS `menu`;
CREATE TABLE IF NOT EXISTS `menu` (
  `idmenu` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a llave primaria',
  `padre` varchar(100) NOT NULL COMMENT 'Corresponde a llave foreanea del menú, la cual se encarga de diferenciar a los hijos de los padres',
  `ruta` varchar(100) NOT NULL COMMENT 'Corresponde a la ruta del menú, este no puede tener espacios ni caracteres especiales',
  `nombre` varchar(100) NOT NULL COMMENT 'Corresponde al nombre del menú',
  `clase` varchar(100) NOT NULL COMMENT 'Corresponde a la clase la cual sirve como identificador para dar estilos personalizados a los menús',
  `icono` varchar(100) NOT NULL COMMENT 'Corresponde al icono del menú',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idmenu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='En esta tabla se almacenara el registro de los menús del sistema';

DROP TABLE IF EXISTS `metodologia`;
CREATE TABLE IF NOT EXISTS `metodologia` (
  `idmetodologia` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(50) NOT NULL COMMENT 'Corresponde al nombre de la metología',
  `url` varchar(255) DEFAULT NULL COMMENT 'Corresponde a la ruta de la metodología, este no puede tener espacios ni caracteres especiales',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idmetodologia`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el nombre de las metodologías ';

DROP TABLE IF EXISTS `miembro`;
CREATE TABLE IF NOT EXISTS `miembro` (
  `idmiembro` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idusuario` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla usuario',
  `idproyecto` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla proyecto',
  `sent_email` tinyint(1) unsigned NOT NULL DEFAULT 0 COMMENT 'Corresponde al estado de enviado del correo, con el fin de informar que fue agregado a un proyecto, 1: enviado, 0. pendiente',
  PRIMARY KEY (`idmiembro`),
  KEY `FK__ususario` (`idusuario`),
  KEY `FK_miembro_proyecto` (`idproyecto`),
  CONSTRAINT `FK__ususario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_miembro_proyecto` FOREIGN KEY (`idproyecto`) REFERENCES `proyecto` (`idproyecto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara los estudiantes que corresponde a cada proyecto';

DROP TABLE IF EXISTS `miembro_profesor`;
CREATE TABLE IF NOT EXISTS `miembro_profesor` (
  `idmiembro` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idusuario` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla usuario',
  `idproyecto` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla proyecto',
  `usuario_creacion` tinyint(2) unsigned NOT NULL DEFAULT 0 COMMENT 'Corresponde al usuario de creación, 1: activo, 0: inactivo',
  PRIMARY KEY (`idmiembro`),
  KEY `FK__ususario` (`idusuario`),
  KEY `FK_miembro_proyecto` (`idproyecto`),
  CONSTRAINT `FK_miembro_profesor_proyecto` FOREIGN KEY (`idproyecto`) REFERENCES `proyecto` (`idproyecto`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_miembro_profesor_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara los profesores que corresponde a cada proyecto';

DROP TABLE IF EXISTS `modulo`;
CREATE TABLE IF NOT EXISTS `modulo` (
  `idmodulo` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a llave primaria',
  `nombre` varchar(50) NOT NULL DEFAULT '' COMMENT 'Corresponde al nombre del modulo',
  `descripcion` longtext DEFAULT '' COMMENT 'Corresponde a la descripción del modulo',
  `estado` int(2) DEFAULT 0 COMMENT 'Corresponde al estado del modulo, 1: creado, 2: pendiente, 3: finalizado',
  `posicion` int(11) DEFAULT NULL COMMENT 'Corresponde a la posicón del modulo, la cual se encarga de darle un orden a los modulos',
  `url` varchar(50) NOT NULL COMMENT 'Corresponde a la ruta del modulo, este no puede tener espacios ni caracteres especiales',
  `vista` tinyint(3) unsigned DEFAULT 0 COMMENT 'Corresponde al estado de la vista, 1: activo, 2:inactivo',
  `idfase` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla fase',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idmodulo`),
  KEY `FK_modulo_fase` (`idfase`),
  CONSTRAINT `FK_modulo_fase` FOREIGN KEY (`idfase`) REFERENCES `fase` (`idfase`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro de los modulos';

DROP TABLE IF EXISTS `opcion`;
CREATE TABLE IF NOT EXISTS `opcion` (
  `idopcion` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(255) NOT NULL COMMENT 'Corresponde al nombre de la opcion',
  `idpregunta` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla pregunta',
  `posicion` int(10) unsigned NOT NULL COMMENT 'Corresponde a la posicón de la opcion, la cual se encarga de darle un orden a las opciones',
  PRIMARY KEY (`idopcion`),
  KEY `idpregunta` (`idpregunta`),
  CONSTRAINT `opcion_idpregunta_pregunta` FOREIGN KEY (`idpregunta`) REFERENCES `pregunta` (`idpregunta`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro de las opciones del formulario';

DROP TABLE IF EXISTS `permiso_arbol`;
CREATE TABLE IF NOT EXISTS `permiso_arbol` (
  `idpermiso_arbol` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `ruta` varchar(100) NOT NULL COMMENT 'Corresponde a la ruta del permiso',
  `padre` int(11) NOT NULL COMMENT 'Corresponde a llave foreanea del permiso, la cual se encarga de diferenciar a los hijos de los padres',
  `estado` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Corresponde al estado, 1: público, 2: privado, 3: protegido',
  `alias` varchar(100) NOT NULL COMMENT 'Corresponde al alias del permiso',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idpermiso_arbol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='En esta tabla se almacenara el registro del arbol de permisos';

DROP TABLE IF EXISTS `permiso_menu`;
CREATE TABLE IF NOT EXISTS `permiso_menu` (
  `idpermiso_menu` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idrol` int(11) NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla rol',
  `idmenu` int(11) NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla menu',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idpermiso_menu`),
  KEY `FK_a6b4206e5a51951e6adee670a35` (`idrol`),
  KEY `FK_b6117c20a86f4f0e2e90c522d77` (`idmenu`),
  CONSTRAINT `FK_a6b4206e5a51951e6adee670a35` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_b6117c20a86f4f0e2e90c522d77` FOREIGN KEY (`idmenu`) REFERENCES `menu` (`idmenu`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='En esta tabla se almacenara la relación entre el permiso y el menú';

DROP TABLE IF EXISTS `permiso_rol`;
CREATE TABLE IF NOT EXISTS `permiso_rol` (
  `idpermiso_rol` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idrol` int(11) NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla rol',
  `idpermiso_arbol` int(11) DEFAULT NULL COMMENT 'Corresponde a la llave foránea de la tabla permiso arbol',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idpermiso_rol`),
  KEY `FK_743ba4399972090f1f822d3e74d` (`idrol`),
  KEY `FK_cc3bb0d93de16ca59e12b234e25` (`idpermiso_arbol`),
  CONSTRAINT `FK_743ba4399972090f1f822d3e74d` FOREIGN KEY (`idrol`) REFERENCES `rol` (`idrol`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `FK_cc3bb0d93de16ca59e12b234e25` FOREIGN KEY (`idpermiso_arbol`) REFERENCES `permiso_arbol` (`idpermiso_arbol`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='En esta tabla se almacenara la relación entre el permiso y el rol';

DROP TABLE IF EXISTS `pregunta`;
CREATE TABLE IF NOT EXISTS `pregunta` (
  `idpregunta` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(255) NOT NULL COMMENT 'Corresponde al nombre de la pregunta',
  `requerido` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT 'Corresponde al estado de la pregunta, 1: obligatorio, 2: no requerido',
  `posicion` int(10) unsigned NOT NULL COMMENT 'Corresponde a la posicón de la pregunta, la cual se encarga de darle un orden a las preguntas',
  `idformulario` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla formulario',
  `idtipo_pregunta` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla tipo pregunta',
  PRIMARY KEY (`idpregunta`),
  KEY `idformulario` (`idformulario`),
  KEY `idtipo_pregunta` (`idtipo_pregunta`),
  CONSTRAINT `pregunta_idformulario_formulario` FOREIGN KEY (`idformulario`) REFERENCES `formulario` (`idformulario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pregunta_idtipo_pregunta_tipo_pregunta` FOREIGN KEY (`idtipo_pregunta`) REFERENCES `tipo_pregunta` (`idtipo_pregunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara las preguntas registradas en el formulario';

DROP TABLE IF EXISTS `presupuesto`;
CREATE TABLE IF NOT EXISTS `presupuesto` (
  `idpresupuesto` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `descripcion` text NOT NULL COMMENT 'Corresponde a la descripción del presupuesto',
  `cantidad` int(11) NOT NULL COMMENT 'Corresponde a la cantidad de objetos registrados en el presupuesto',
  `valor_unidad` int(11) NOT NULL COMMENT 'Corresponde al valor de la unidad ',
  `total` int(11) NOT NULL COMMENT 'Corresponde al total invertido en el presupuesto',
  `idresponsable` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla responsable',
  `idhistoria` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla historia ',
  `idcategoria` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla categoria',
  PRIMARY KEY (`idpresupuesto`),
  KEY `idresponsabble` (`idresponsable`),
  KEY `idhistoria_usuario` (`idhistoria`),
  KEY `idcategoria` (`idcategoria`),
  CONSTRAINT `categoria_idcategoria_foranea` FOREIGN KEY (`idcategoria`) REFERENCES `categoria` (`idcategoria`),
  CONSTRAINT `historia_idhistoria_foranea` FOREIGN KEY (`idhistoria`) REFERENCES `historia` (`idhistoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `usuario_idresponsable_foranea` FOREIGN KEY (`idresponsable`) REFERENCES `usuario` (`idusuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro del presupuesto de cada historia de usuario';

DROP TABLE IF EXISTS `prioridad`;
CREATE TABLE IF NOT EXISTS `prioridad` (
  `idprioridad` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(50) NOT NULL COMMENT 'Corresponde al nombre de la prioridad',
  PRIMARY KEY (`idprioridad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro de las prioridades';

DROP TABLE IF EXISTS `proyecto`;
CREATE TABLE IF NOT EXISTS `proyecto` (
  `idproyecto` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(50) NOT NULL COMMENT 'Corresponde al nombre del proyecto',
  `url` varchar(255) DEFAULT NULL COMMENT 'Corresponde a la ruta del proyecto, este no puede tener espacios ni caracteres especiales',
  `proyecto_base` tinyint(3) unsigned DEFAULT 0 COMMENT 'Corresponde a un proyecto base para que el usuario tenga una guia al momento de iniciar, 0: inactivo, 1: activo',
  `subtitulo` varchar(255) DEFAULT NULL COMMENT 'Corresponde al subtitulo del proyecto',
  `ruta_imagen` varchar(255) DEFAULT NULL COMMENT 'Corresponde a la ruta de la imagen',
  `descripcion` longtext DEFAULT NULL COMMENT 'Corresponde a la descripción del proyecto',
  `estado` int(2) DEFAULT 1 COMMENT 'Corresponde al estado del proyecto, 1:activo, 0: inactivo',
  `porcentaje_cumplimiento` int(3) DEFAULT 0 COMMENT 'Corresponde al porcentaje de cumplimiento que equivale a la cantidad de modulos creados sobre los modulos finalizados',
  `favorito` int(2) DEFAULT 0 COMMENT 'Corresponde al favorito del proyecto, 1: activo, 0: inactivo',
  `visibilizacion` int(2) DEFAULT 1 COMMENT 'Corresponde a la visibilizacion del proyecto, 1: activo, 0: inactivo',
  `fecha_inicio` date DEFAULT NULL COMMENT 'Corresponde a la fecha inicial del proyecto',
  `fecha_finalizacion` date DEFAULT NULL COMMENT 'Corresponde a la fecha final del proyecto',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idproyecto`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro de los proyectos';

DROP TABLE IF EXISTS `proyecto_metodologia`;
CREATE TABLE IF NOT EXISTS `proyecto_metodologia` (
  `idproyecto_metodologia` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idproyecto` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla proyecto',
  `idmetodologia` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla metodologia',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idproyecto_metodologia`),
  KEY `FK_proyecto_metodologia_proyecto` (`idproyecto`),
  KEY `FK_proyecto_metodologia_metodologia` (`idmetodologia`),
  CONSTRAINT `FK_proyecto_metodologia_metodologia` FOREIGN KEY (`idmetodologia`) REFERENCES `metodologia` (`idmetodologia`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_proyecto_metodologia_proyecto` FOREIGN KEY (`idproyecto`) REFERENCES `proyecto` (`idproyecto`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro de la unión entre el proyecto y la metología';

DROP TABLE IF EXISTS `respuesta`;
CREATE TABLE IF NOT EXISTS `respuesta` (
  `idrespuesta` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idparticipante` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla formulario_participante',
  `idpregunta` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla pregunta',
  `idopcion` int(11) unsigned DEFAULT NULL COMMENT 'Corresponde a la llave foránea de la tabla opción',
  `valor` text DEFAULT NULL COMMENT 'Corresponde al valor de la pregunta',
  PRIMARY KEY (`idrespuesta`),
  KEY `idpregunta` (`idpregunta`),
  KEY `idusuario` (`idparticipante`),
  KEY `idopcion` (`idopcion`),
  CONSTRAINT `respuesta_idopcion_opcion` FOREIGN KEY (`idopcion`) REFERENCES `opcion` (`idopcion`),
  CONSTRAINT `respuesta_idpregunta_pregunta` FOREIGN KEY (`idpregunta`) REFERENCES `pregunta` (`idpregunta`),
  CONSTRAINT `respuesta_idusuario_usuario` FOREIGN KEY (`idparticipante`) REFERENCES `formulario_participante` (`idformulario_participante`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro de las respuestas del formulario';

DROP TABLE IF EXISTS `riesgo_desarrollo`;
CREATE TABLE IF NOT EXISTS `riesgo_desarrollo` (
  `idriesgo_desarrollo` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(50) NOT NULL COMMENT 'Corresponde al nombre del riesgo de desarrollo',
  PRIMARY KEY (`idriesgo_desarrollo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro del riesgo de desarrollo ';

DROP TABLE IF EXISTS `rol`;
CREATE TABLE IF NOT EXISTS `rol` (
  `idrol` int(11) NOT NULL AUTO_INCREMENT COMMENT 'En esta tabla se almacenara el registro de las fases',
  `nombre` varchar(100) NOT NULL COMMENT 'Corresponde al nombre del rol, estudiante, profesor y administrador',
  `ruta` varchar(100) NOT NULL COMMENT 'Corresponde a la ruta principal del rol',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idrol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='En esta tabla se almacenara el registro de los roles del sistema';

DROP TABLE IF EXISTS `tipo_pregunta`;
CREATE TABLE IF NOT EXISTS `tipo_pregunta` (
  `idtipo_pregunta` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(255) DEFAULT NULL COMMENT 'Corresponde al nombre de l tipo de pregunta',
  PRIMARY KEY (`idtipo_pregunta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro de los tipos de preguntas en el formulario';

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `idusuario` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `nombre` varchar(255) NOT NULL COMMENT 'Corresponde al nombre del usuario',
  `apellido` varchar(255) NOT NULL COMMENT 'Corresponde al apellido del usuario',
  `clave` varchar(255) NOT NULL COMMENT 'Corresponde al clave del usuario',
  `estado` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Corresponde a los estados del usuario, 1: activo, 0: inactivo',
  `ruta_imagen` varchar(150) DEFAULT NULL COMMENT 'Corresponde a la ruta de la imagen ',
  `nombre_usuario` varchar(255) NOT NULL COMMENT 'Corresponde al nombre de usuario',
  `correo` varchar(255) NOT NULL COMMENT 'Corresponde al correo electronico',
  `idrol` int(11) NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla rol',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Corresponde a la fecha de la última actualización registrada',
  PRIMARY KEY (`idusuario`),
  UNIQUE KEY `IDX_78a916df40e02a9deb1c4b75ed` (`nombre_usuario`),
  UNIQUE KEY `correo` (`correo`),
  KEY `FK_8a74d5470eaf33a31ae41a44b55` (`idrol`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='En esta tabla se almacenara el registro de los usuarios y accesos al sistema';

DROP TABLE IF EXISTS `vista`;
CREATE TABLE IF NOT EXISTS `vista` (
  `idvista` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Corresponde a la llave principal de la tabla',
  `idhistoria` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla historia',
  `idusuario` int(10) unsigned NOT NULL COMMENT 'Corresponde a la llave foránea de la tabla usuario',
  `estado` tinyint(3) unsigned NOT NULL DEFAULT 0 COMMENT 'Corresponde al estado de la vista, 0: no revisado, 1: revisado',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Corresponde a la fecha de creación del registro',
  PRIMARY KEY (`idvista`),
  KEY `idusuario` (`idusuario`),
  KEY `idhistoria` (`idhistoria`),
  CONSTRAINT `FK_vista_historia` FOREIGN KEY (`idhistoria`) REFERENCES `historia` (`idhistoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_vista_usuario` FOREIGN KEY (`idusuario`) REFERENCES `usuario` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='En esta tabla se almacenara el registro de las alertas desarrolladas por el estudiante y el profesor';

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
