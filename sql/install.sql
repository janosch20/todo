CREATE SCHEMA `todo` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE `todo`.`user` (
  `userId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `userName` VARCHAR(255) NOT NULL,
  `userMail` VARCHAR(255) NOT NULL,
  `userPasshash` VARCHAR(65) NULL DEFAULT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE INDEX `userName_UNIQUE` (`userName` ASC))
  ENGINE = InnoDB;


CREATE TABLE `todo`.`task` (
  `taskId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `userId` INT UNSIGNED NOT NULL,
  `taskTitle` VARCHAR(255) NOT NULL,
  `taskDescription` TEXT NULL,
  `taskCreate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `taskEdit` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`taskId`),
  INDEX `fk_task_1_idx` (`userId` ASC),
  CONSTRAINT `fk_task_1`
  FOREIGN KEY (`userId`)
  REFERENCES `todo`.`user` (`userId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;

ALTER TABLE `todo`.`task`
ADD COLUMN `taskDone` TINYINT NOT NULL DEFAULT 0 AFTER `taskDescription`;

INSERT INTO `todo`.`user` (`userName`, `userMail`, `userPasshash`) VALUES ('test', 'test@testmail.com', '$2y$10$0LtG0zCf8s2oGrVzppme6.vhPpBVULjDHIpQbSBur4n6HG7Dra7Bq');

CREATE USER 'todo_user'@'%' IDENTIFIED BY 'todo_password';
GRANT ALL PRIVILEGES ON todo.* TO 'todo_user'@'%';