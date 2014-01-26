SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `estado_objectos` CHANGE COLUMN `id` `id` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT  ;

ALTER TABLE `groups` ADD COLUMN `estado_objecto_id` TINYINT(3) UNSIGNED NULL DEFAULT 1  AFTER `modified_by` , 
  ADD CONSTRAINT `groups_estado_objectos_fk`
  FOREIGN KEY (`estado_objecto_id` )
  REFERENCES `estado_objectos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `groups_estado_objectos_fk_idx` (`estado_objecto_id` ASC) ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

----------------------------------------------------------------------------
INSERT INTO `estado_objectos` (`id`, `name`) VALUES (NULL, 'Activo'), (NULL, 'Inactivo'), (NULL, 'Removido');
-----------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `groups` DROP FOREIGN KEY `groups_estado_objectos_fk` ;

ALTER TABLE `groups` CHANGE COLUMN `estado_objecto_id` `estado_objecto_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT 1  , 
  ADD CONSTRAINT `groups_estado_objectos_fk`
  FOREIGN KEY (`estado_objecto_id` )
  REFERENCES `estado_objectos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
------------------------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `unidade_organicas` ADD COLUMN `codigo_interno` VARCHAR(45) NULL DEFAULT NULL  AFTER `codigo` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
-------------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE  TABLE IF NOT EXISTS `opensga_sessions` (
  `id` VARCHAR(255) NOT NULL ,
  `data` TEXT NULL DEFAULT NULL ,
  `expires` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
update unidade_organicas set codigo_interno='faculdade' where tipo_unidade_organica_id = 1
---------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `candidaturas` CHANGE COLUMN `estado_matricula_id` `estado_matricula_id` INT(11) NULL DEFAULT 5  ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
------------------------------------------------------------------------------------------------------------------------------------------
update unidade_organicas set codigo_interno='faculdade' where tipo_unidade_organica_id = 1
-------------------------------------------------------------------------------------------------------------------------------------
Mudar dr. Ivan para Departamento de Informatica
---------------------------------------------------------------------------------------------------------------------------
update planoestudos set duracao=4
	-------------------------------------------------------------
	update planoestudos set estado_objecto_id=1
		---------------------------------------------


SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `curso_tipo_pamentos` RENAME TO  `curso_tipo_pagamentos` ;

ALTER TABLE `cursos` CHANGE COLUMN `grauacademico_id` `grau_academico_id` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `tipocurso_id` `tipo_curso_id` INT(11) NULL DEFAULT NULL  , 
  ADD CONSTRAINT `cursos_grau_academicos_fk`
  FOREIGN KEY (`grau_academico_id` )
  REFERENCES `grau_academicos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `cursos_tipo_cursos_fk`
  FOREIGN KEY (`tipo_curso_id` )
  REFERENCES `tipo_cursos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `cursos_unidade_organicas_fk`
  FOREIGN KEY (`unidade_organica_id` )
  REFERENCES `unidade_organicas` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `cursos_grau_academicos_fk_idx` (`grau_academico_id` ASC) 
, ADD INDEX `cursos_tipo_cursos_fk_idx` (`tipo_curso_id` ASC) 
, ADD INDEX `cursos_unidade_organicas_fk_idx` (`unidade_organica_id` ASC) ;

ALTER TABLE `planoestudoanos` CHANGE COLUMN `planoestudo_id` `plano_estudo_id` INT(11) NOT NULL  , CHANGE COLUMN `ano` `ano_curricular` TINYINT(3) UNSIGNED NULL DEFAULT NULL  , CHANGE COLUMN `semestre` `semestre_curricular` TINYINT(4) NULL DEFAULT NULL  , CHANGE COLUMN `cargahorariateoricas` `carga_horaria_teoricas` TINYINT(4) NULL DEFAULT '0'  , CHANGE COLUMN `cargahorariapraticas` `carga_horaria_praticas` TINYINT(4) NULL DEFAULT '0'  , CHANGE COLUMN `creditos` `creditos` TINYINT(4) NULL DEFAULT NULL  , CHANGE COLUMN `disciplina_id` `disciplina_id` INT(11) NOT NULL DEFAULT '0'  , CHANGE COLUMN `horas_docente` `horas_docente` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `horas_individual` `horas_individual` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `carga_total` `carga_total` INT(11) NULL DEFAULT NULL  , ADD COLUMN `estado_objecto_id` TINYINT(3) UNSIGNED NOT NULL  AFTER `ciclo_numero` , 
  ADD CONSTRAINT `d_p_e_disciplinas_fk`
  FOREIGN KEY (`disciplina_id` )
  REFERENCES `disciplinas` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `d_p_e_plano_estudos_fk`
  FOREIGN KEY (`plano_estudo_id` )
  REFERENCES `plano_estudos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `d_p_e_ramos_fk`
  FOREIGN KEY (`ramo_id` )
  REFERENCES `ramos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `d_p_e_estado_objectos_fk`
  FOREIGN KEY (`estado_objecto_id` )
  REFERENCES `estado_objectos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `d_p_e_disciplinas_fk_idx` (`disciplina_id` ASC) 
, ADD INDEX `d_p_e_plano_estudos_fk_idx` (`plano_estudo_id` ASC) 
, ADD INDEX `d_p_e_ramos_fk_idx` (`ramo_id` ASC) 
, ADD INDEX `d_p_e_estado_objectos_fk_idx` (`estado_objecto_id` ASC) , RENAME TO  `disciplinas_plano_estudos` ;

ALTER TABLE `planoestudos` CHANGE COLUMN `name` `name` VARCHAR(255) NOT NULL  , CHANGE COLUMN `curso_id` `curso_id` INT(11) NOT NULL  , CHANGE COLUMN `duracao` `duracao` INT(11) NOT NULL  , CHANGE COLUMN `codigo` `codigo` VARCHAR(25) NULL DEFAULT NULL  , CHANGE COLUMN `ano_criacao` `ano_criacao` YEAR NOT NULL  , CHANGE COLUMN `estado_objecto_id` `estado_objecto_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT 1  , ADD COLUMN `created` DATETIME NULL DEFAULT NULL  AFTER `estado_objecto_id` , ADD COLUMN `modified` DATETIME NULL DEFAULT NULL  AFTER `created` , ADD COLUMN `created_by` INT(11) NULL DEFAULT NULL  AFTER `modified` , ADD COLUMN `modified_by` INT(11) NULL DEFAULT NULL  AFTER `created_by` , 
  ADD CONSTRAINT `plano_estudos_cursos`
  FOREIGN KEY (`curso_id` )
  REFERENCES `cursos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `plano_estudos_estado_objectos`
  FOREIGN KEY (`estado_objecto_id` )
  REFERENCES `estado_objectos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `plano_estudos_cursos_idx` (`curso_id` ASC) 
, ADD INDEX `plano_estudos_estado_objectos_idx` (`estado_objecto_id` ASC) , RENAME TO  `plano_estudos` ;

ALTER TABLE `tipocursos` RENAME TO  `tipo_cursos` ;

CREATE  TABLE IF NOT EXISTS `disciplinas_docentes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `disciplina_id` INT(11) NOT NULL ,
  `docente_id` INT(11) NOT NULL ,
  `estado_objecto_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT 1 ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  `created_by` INT(11) NULL DEFAULT NULL ,
  `modified_by` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `disciplinas_docentes_disciplinas_fk_idx` (`disciplina_id` ASC) ,
  INDEX `disciplinas_docentes_docentes_fk_idx` (`docente_id` ASC) ,
  INDEX `disciplinas_docentes_estado_objectos_idx` (`estado_objecto_id` ASC) ,
  CONSTRAINT `disciplinas_docentes_disciplinas_fk`
    FOREIGN KEY (`disciplina_id` )
    REFERENCES `disciplinas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `disciplinas_docentes_docentes_fk`
    FOREIGN KEY (`docente_id` )
    REFERENCES `docentes` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `disciplinas_docentes_estado_objectos`
    FOREIGN KEY (`estado_objecto_id` )
    REFERENCES `estado_objectos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `disciplinas_unidade_organicas` (
  `id` INT(11) NOT NULL ,
  `disciplina_id` INT(11) NOT NULL ,
  `unidade_organica_id` INT(11) NOT NULL ,
  `estado_objecto_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT 1 ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  `created_by` INT(11) NULL DEFAULT NULL ,
  `modified_by` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `disciplinas_unidade_disciplinas_fk_idx` (`disciplina_id` ASC) ,
  INDEX `disciplinas_unidade_unidade_fk_idx` (`unidade_organica_id` ASC) ,
  INDEX `disciplinas_unidade_estado_objectos_fk_idx` (`estado_objecto_id` ASC) ,
  CONSTRAINT `disciplinas_unidade_disciplinas_fk`
    FOREIGN KEY (`disciplina_id` )
    REFERENCES `disciplinas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `disciplinas_unidade_unidade_fk`
    FOREIGN KEY (`unidade_organica_id` )
    REFERENCES `unidade_organicas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `disciplinas_unidade_estado_objectos_fk`
    FOREIGN KEY (`estado_objecto_id` )
    REFERENCES `estado_objectos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

----------------------------------------------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE  TABLE IF NOT EXISTS `curso_tipo_pagamentos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `curso_id` INT(11) NULL DEFAULT NULL ,
  `tipopagamento_id` INT(11) NULL DEFAULT NULL ,
  `valor` DECIMAL(10,2) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `disciplinas` CHANGE COLUMN `name` `name` VARCHAR(200) NULL DEFAULT NULL  , ADD COLUMN `created` VARCHAR(45) NULL DEFAULT NULL  AFTER `codigo_antigo` , ADD COLUMN `modified` VARCHAR(45) NULL DEFAULT NULL  AFTER `created` , ADD COLUMN `created_by` VARCHAR(45) NULL DEFAULT NULL  AFTER `modified` , ADD COLUMN `modified_by` VARCHAR(45) NULL DEFAULT NULL  AFTER `created_by` ;

CREATE  TABLE IF NOT EXISTS `disciplinas_plano_estudos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `plano_estudo_id` INT(11) NOT NULL ,
  `ano_curricular` TINYINT(3) UNSIGNED NULL DEFAULT NULL ,
  `semestre_curricular` TINYINT(4) NULL DEFAULT NULL ,
  `carga_horaria_teoricas` TINYINT(4) NULL DEFAULT '0' ,
  `carga_horaria_praticas` TINYINT(4) NULL DEFAULT '0' ,
  `creditos` TINYINT(4) NULL DEFAULT NULL ,
  `disciplina_id` INT(11) NOT NULL DEFAULT '0' ,
  `codigo` VARCHAR(25) NULL DEFAULT NULL ,
  `ramo_id` INT(11) NULL DEFAULT NULL ,
  `semestre_sequencial` INT(11) NULL DEFAULT NULL ,
  `horas_docente` INT(11) NULL DEFAULT NULL ,
  `horas_individual` INT(11) NULL DEFAULT NULL ,
  `carga_total` INT(11) NULL DEFAULT NULL ,
  `ciclo_plano_estudo_id` INT(11) NULL DEFAULT NULL ,
  `ciclo_numero` INT(11) NULL DEFAULT NULL ,
  `estado_objecto_id` TINYINT(3) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `d_p_e_disciplinas_fk_idx` (`disciplina_id` ASC) ,
  INDEX `d_p_e_plano_estudos_fk_idx` (`plano_estudo_id` ASC) ,
  INDEX `d_p_e_ramos_fk_idx` (`ramo_id` ASC) ,
  INDEX `d_p_e_estado_objectos_fk_idx` (`estado_objecto_id` ASC) ,
  CONSTRAINT `d_p_e_disciplinas_fk`
    FOREIGN KEY (`disciplina_id` )
    REFERENCES `disciplinas` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `d_p_e_plano_estudos_fk`
    FOREIGN KEY (`plano_estudo_id` )
    REFERENCES `plano_estudos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `d_p_e_ramos_fk`
    FOREIGN KEY (`ramo_id` )
    REFERENCES `ramos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `d_p_e_estado_objectos_fk`
    FOREIGN KEY (`estado_objecto_id` )
    REFERENCES `estado_objectos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 457
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
--------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `disciplinas_plano_estudos` RENAME TO  `disciplina_plano_estudos` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `disciplinas_docentes` RENAME TO  `disciplina_docentes` ;

ALTER TABLE `disciplinas_unidade_organicas` RENAME TO  `disciplina_unidade_organicas` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-----------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `disciplina_unidade_organicas` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT  ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
------------------------------------------------------------------------------------------------------------------
update alunos set modified=null where modified = '0000-00-00'
---------------------------------------------------------------------------------------------------------------
update alunos set created=null where created = '0000-00-00';
-----------------------------------------------------------------------
update matriculas set data=null where data = '0000-00-00'
  --------------------------------------------------
  Actualizar discipliina_unidade_organica
  ------------------------------------------------------

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `anolectivoepocas` DROP FOREIGN KEY `anolectivo_fk` , DROP FOREIGN KEY `epocaavaliacao_fk` ;

ALTER TABLE `funcionarios` DROP FOREIGN KEY `funcionarios_tipo_funcionarios` ;

ALTER TABLE `alunos` CHANGE COLUMN `planoestudo_id` `plano estudo_id` INT(11) NULL DEFAULT NULL  ;

ALTER TABLE `anolectivoepocas` 
  ADD CONSTRAINT `anolectivo_fk`
  FOREIGN KEY (`anolectivo_id` )
  REFERENCES `ano_lectivos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `epocaavaliacao_fk`
  FOREIGN KEY (`epocaavaliacao_id` )
  REFERENCES `epoca_avaliacaos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, RENAME TO  `ano_lectivo_epocas` ;

ALTER TABLE `anolectivos` RENAME TO  `ano_lectivos` ;

ALTER TABLE `areatrabalhos` RENAME TO  `area_trabalhos` ;

ALTER TABLE `epocaavaliacaos` RENAME TO  `epoca_avaliacaos` ;

ALTER TABLE `estadoentidades` RENAME TO  `estado_entidades` ;

ALTER TABLE `estadomatriculas` RENAME TO  `estado_matriculas` ;

ALTER TABLE `estadomessages` RENAME TO  `estado_messages` ;

ALTER TABLE `estadoturmas` RENAME TO  `estado_turmas` ;

ALTER TABLE `funcionarios` DROP FOREIGN KEY `funcionarios_users` , DROP FOREIGN KEY `funcionarios_entidades` , DROP FOREIGN KEY `funcionarios_funcao_funcionarios` , DROP FOREIGN KEY `funcionarios_categorias_funcionarios` ;

ALTER TABLE `funcionarios` 
  ADD CONSTRAINT `funcionarios_users`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `funcionarios_tipo_funcionarios`
  FOREIGN KEY (`tipo_funcionario_id` )
  REFERENCES `tipo_funcionarios` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `funcionarios_entidades`
  FOREIGN KEY (`entidade_id` )
  REFERENCES `entidades` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `funcionarios_funcao_funcionarios`
  FOREIGN KEY (`funcao_profissional_id` )
  REFERENCES `funcao_profissionals` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `funcionarios_categorias_funcionarios`
  FOREIGN KEY (`categoria_funcionario_id` )
  REFERENCES `categoria_funcionarios` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `precedencias` CHANGE COLUMN `planoestudoano_id` `disciplina_plano_estudo_id` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `precedencia` `disciplina_precedente` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `tipoprecedencia_id` `tipo_precedencia_id` INT(11) NULL DEFAULT NULL  ;

ALTER TABLE `matriculas` CHANGE COLUMN `planoestudo_id` `plano_estudo_id` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `anolectivo_id` `ano_lectivo_id` INT(11) NULL DEFAULT NULL  ;

ALTER TABLE `plano_estudo_tipo_pagamentos` CHANGE COLUMN `planoestudo_id` `plano_estudo_id` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `tipopagamento_id` `tipo_pagamento_id` INT(11) NULL DEFAULT NULL  ;

ALTER TABLE `regimelectivos` RENAME TO  `regime_lectivos` ;

ALTER TABLE `semestrelectivos` CHANGE COLUMN `anolectivo_id` `ano_lectivo_id` INT(11) NULL DEFAULT NULL  , RENAME TO  `semestre_lectivos` ;

ALTER TABLE `tipoavaliacaos` RENAME TO  `tipo_avaliacaos` ;

ALTER TABLE `tipofuncionarios` RENAME TO  `tipo_funcionarios` ;

ALTER TABLE `tipomovimentos` RENAME TO  `tipo_movimentos` ;

ALTER TABLE `tipoprecedencias` RENAME TO  `tipo_precedencias` ;

ALTER TABLE `turmas` CHANGE COLUMN `anolectivo_id` `ano_lectivo_id` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `planoestudo_id` `plano_estudo_id` INT(11) NULL DEFAULT NULL  ;

DROP TABLE IF EXISTS `seqmovimentos` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
---------------------------------------------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

CREATE  TABLE IF NOT EXISTS `ano_lectivo_epocas` (
  `id` INT(11) NOT NULL ,
  `anolectivo_id` INT(11) NULL DEFAULT NULL ,
  `epocaavaliacao_id` INT(11) NULL DEFAULT NULL ,
  `limite` DATE NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `anolectivo_fk` (`anolectivo_id` ASC) ,
  INDEX `epocaavaliacao_fk` (`epocaavaliacao_id` ASC) ,
  CONSTRAINT `anolectivo_fk`
    FOREIGN KEY (`anolectivo_id` )
    REFERENCES `ano_lectivos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `epocaavaliacao_fk`
    FOREIGN KEY (`epocaavaliacao_id` )
    REFERENCES `epoca_avaliacaos` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `ano_lectivos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `codigo` VARCHAR(45) NULL DEFAULT NULL ,
  `ano` VARCHAR(30) NULL DEFAULT NULL ,
  `num_semestre` INT(11) NULL DEFAULT NULL ,
  `datainicio` DATE NULL DEFAULT NULL ,
  `datafim` DATE NULL DEFAULT NULL ,
  `created` DATETIME NULL DEFAULT NULL ,
  `modified` DATETIME NULL DEFAULT NULL ,
  `regimelectivo_id` INT(11) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `area_trabalhos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `epoca_avaliacaos` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `codigo` VARCHAR(25) NOT NULL ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 4
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `estado_entidades` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `estado_matriculas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `estado_turmas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
------------------------------------------------------------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `alunos` CHANGE COLUMN `plano estudo_id` `plano_estudo_id` INT(11) NULL DEFAULT NULL  ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `matriculas` CHANGE COLUMN `estadomatricula_id` `estado_matricula_id` INT(11) NULL DEFAULT NULL  ;

ALTER TABLE `turmas` CHANGE COLUMN `estadoturma_id` `estado_turma_id` INT(11) NULL DEFAULT NULL  ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

--------------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `financeiro_depositos` CHANGE COLUMN `semestrelectivo_id` `semestre_lectivo_id` INT(11) NULL DEFAULT NULL  ;

ALTER TABLE `financeiro_pagamentos` CHANGE COLUMN `anolectivo_id` `ano_lectivo_id` INT(11) NULL DEFAULT NULL  , CHANGE COLUMN `semestrelectivo_id` `semestre_lectivo_id` INT(11) NULL DEFAULT NULL  ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
---------------------------------------------------------------------------------------------------------------------------------------

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `disciplina_plano_estudos` DROP FOREIGN KEY `d_p_e_estado_objectos_fk` ;

ALTER TABLE `disciplina_plano_estudos` CHANGE COLUMN `estado_objecto_id` `estado_objecto_id` TINYINT(3) UNSIGNED NOT NULL DEFAULT 1  , 
  ADD CONSTRAINT `d_p_e_estado_objectos_fk`
  FOREIGN KEY (`estado_objecto_id` )
  REFERENCES `estado_objectos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
--------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `financeiro_depositos` DROP COLUMN `semestre_lectivo_id` , ADD COLUMN `semestre_lectivo_id` INT(11) NULL DEFAULT NULL  AFTER `valor` 
, DROP INDEX `financeiro_depositos_semestrelectivos_fk` 
, ADD INDEX `financeiro_depositos_semestrelectivos_fk` (`semestre_lectivo_id` ASC) ;

ALTER TABLE `financeiro_pagamentos` DROP COLUMN `semestre_lectivo_id` , DROP COLUMN `ano_lectivo_id` , ADD COLUMN `ano_lectivo_id` INT(11) NULL DEFAULT NULL  AFTER `data_limite` , ADD COLUMN `semestre_lectivo_id` INT(11) NULL DEFAULT NULL  AFTER `financeiro_transacao_id` 
, DROP INDEX `ano_lectivos_financeiro_pagamentos_fk` 
, ADD INDEX `ano_lectivos_financeiro_pagamentos_fk` (`ano_lectivo_id` ASC) ;

ALTER TABLE `precedencias` ADD COLUMN `disciplina_id` INT(11) NULL DEFAULT NULL  AFTER `tipo_precedencia_id` , ADD COLUMN `estado_objecto_id` TINYINT(3) UNSIGNED NULL DEFAULT NULL  AFTER `disciplina_id` , ADD COLUMN `created` DATETIME NULL DEFAULT NULL  AFTER `estado_objecto_id` , ADD COLUMN `modified` DATETIME NULL DEFAULT NULL  AFTER `created` , ADD COLUMN `created_by` INT(11) NULL DEFAULT NULL  AFTER `modified` , ADD COLUMN `modified_by` INT(11) NULL DEFAULT NULL  AFTER `created_by` , 
  ADD CONSTRAINT `disciplina_plano_precedencia_fk`
  FOREIGN KEY (`disciplina_plano_estudo_id` )
  REFERENCES `disciplina_plano_estudos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `disciplinas_precedencias_precedente_fk`
  FOREIGN KEY (`disciplina_precedente` )
  REFERENCES `disciplinas` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `precedencias_tipo_precedencias_fk`
  FOREIGN KEY (`tipo_precedencia_id` )
  REFERENCES `tipo_precedencias` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `precedencias_disciplinas_fk`
  FOREIGN KEY (`disciplina_id` )
  REFERENCES `disciplinas` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `precedencias_estado_objectos_fk`
  FOREIGN KEY (`estado_objecto_id` )
  REFERENCES `estado_objectos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `disciplina_plano_precedencia_fk_idx` (`disciplina_plano_estudo_id` ASC) 
, ADD INDEX `disciplinas_precedencias_precedente_fk_idx` (`disciplina_precedente` ASC) 
, ADD INDEX `precedencias_tipo_precedencias_fk_idx` (`tipo_precedencia_id` ASC) 
, ADD INDEX `precedencias_disciplinas_fk_idx` (`disciplina_id` ASC) 
, ADD INDEX `precedencias_estado_objectos_fk_idx` (`estado_objecto_id` ASC) ;

ALTER TABLE `matriculas` DROP COLUMN `estado_matricula_id` , ADD COLUMN `estado_matricula_id` INT(11) NULL DEFAULT NULL  AFTER `data` ;

ALTER TABLE `disciplina_plano_estudos` CHANGE COLUMN `estado_objecto_id` `estado_objecto_id` TINYINT(3) UNSIGNED NOT NULL  , DROP FOREIGN KEY `d_p_e_estado_objectos_fk` ;

ALTER TABLE `disciplina_plano_estudos` 
  ADD CONSTRAINT `d_p_e_estado_objectos_fk`
  FOREIGN KEY (`estado_objecto_id` )
  REFERENCES `estado_objectos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;

ALTER TABLE `turmas` DROP COLUMN `estado_turma_id` , ADD COLUMN `estado_turma_id` INT(11) NULL DEFAULT NULL  AFTER `mediaturma` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-------------------------------------------------------------------------------------------------------------------------
INSERT INTO `tipo_precedencias` (`id`, `name`) VALUES (NULL, 'Obrigatoria'), (NULL, 'Aconselhada');
---------------------------------------------------------------------------------------------------------------------------
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

ALTER TABLE `disciplina_plano_estudos` DROP FOREIGN KEY `d_p_e_plano_estudos_fk` ;

ALTER TABLE `disciplina_plano_estudos` CHANGE COLUMN `plano_estudo_id` `plano_estudo_id` INT(10) UNSIGNED NOT NULL DEFAULT 1  , CHANGE COLUMN `ano_curricular` `ano_curricular` TINYINT(4) NULL DEFAULT NULL  , DROP FOREIGN KEY `d_p_e_estado_objectos_fk` ;

ALTER TABLE `disciplina_plano_estudos` 
  ADD CONSTRAINT `d_p_e_plano_estudos_fk`
  FOREIGN KEY (`plano_estudo_id` )
  REFERENCES `plano_estudos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION, 
  ADD CONSTRAINT `d_p_e_estado_objectos_fk`
  FOREIGN KEY (`estado_objecto_id` )
  REFERENCES `estado_objectos` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

---------------------------------------------------------------------------------------------------------------------------------------------------------------

