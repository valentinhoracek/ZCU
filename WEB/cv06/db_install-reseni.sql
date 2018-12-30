
SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

DROP TABLE IF EXISTS `horacekv_users` ;
DROP TABLE IF EXISTS `horacekv_rights` ;

-- -----------------------------------------------------
-- Table `horacekv_rights`
-- -----------------------------------------------------


CREATE TABLE IF NOT EXISTS `horacekv_rights` (
  `ID` INT NOT NULL,
  `TITLE` VARCHAR(20) NOT NULL,
  `WEIGHT` INT(2) NOT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
DEFAULT COLLATE utf8_czech_ci;


-- -----------------------------------------------------
-- Table `horacekv_users`
-- -----------------------------------------------------


CREATE TABLE IF NOT EXISTS `horacekv_users` (
  `ID` INT NOT NULL AUTO_INCREMENT,
  `FULL_NAME` VARCHAR(60) NOT NULL,
  `LOGIN` VARCHAR(30) NOT NULL,
  `PASSWORD` VARCHAR(40) NOT NULL,
  `EMAIL` VARCHAR(35) NOT NULL,
  `ID_RIGHTS` INT NOT NULL DEFAULT 3,
  PRIMARY KEY (`ID`),
  INDEX `fk_users_rights_idx` (`ID` ASC),
  CONSTRAINT `fk_users_rights`
    FOREIGN KEY (`ID`)
    REFERENCES `horacekv_rights` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
DEFAULT COLLATE utf8_czech_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `nyklm_prava`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `horacekv_rights` (`ID`, `TITLE`, `WEIGHT`) VALUES (1, 'Admin', 10);
INSERT INTO `horacekv_rights` (`ID`, `TITLE`, `WEIGHT`) VALUES (2, 'Reviewer', 5);
INSERT INTO `horacekv_rights` (`ID`, `TITLE`, `WEIGHT`) VALUES (3, 'Author', 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `nyklm_uzivatele`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `horacekv_users` (`ID`, `FULL_NAME`, `LOGIN`, `PASSWORD`, `EMAIL`, `ID_RIGHTS`) VALUES (1, 'Pepa Jahoda', 'pepa', 'abcd', 'nyklm@kiv.zcu.cz', 1);
INSERT INTO `horacekv_users` (`ID`, `FULL_NAME`, `LOGIN`, `PASSWORD`, `EMAIl`, `ID_RIGHTS`) VALUES (2, 'Pokusný uživatel', 'pokus', 'pokus', 'pokus@kiv.zcu.cz', 2);

COMMIT;

