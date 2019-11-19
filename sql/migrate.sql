RENAME TABLE arxiu.partitures TO arxiu.scores;
ALTER TABLE arxiu.scores CHANGE nom name varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;
ALTER TABLE arxiu.scores CHANGE compositorId composerId int(3) NOT NULL;
ALTER TABLE arxiu.scores CHANGE lletristaId lyricistId int(3) NULL;
ALTER TABLE arxiu.scores CHANGE segle century varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL;
ALTER TABLE arxiu.scores CHANGE estilId styleId int(3) NULL;
ALTER TABLE arxiu.scores CHANGE veuId choirTypeId int(2) NULL;
ALTER TABLE arxiu.scores CHANGE idiomaId languageId int(3) NULL;
ALTER TABLE arxiu.scores CHANGE armariId cupboardId int(2) NULL;
ALTER TABLE arxiu.scores CHANGE caixaId boxId int(4) NULL;

RENAME TABLE arxiu.armaris TO arxiu.cupboards;
ALTER TABLE arxiu.cupboards CHANGE nom name varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

RENAME TABLE arxiu.caixes TO arxiu.boxes;
ALTER TABLE arxiu.boxes CHANGE nom name varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

RENAME TABLE arxiu.compositors TO arxiu.composers;
ALTER TABLE arxiu.composers CHANGE nom name varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

RENAME TABLE arxiu.estils TO arxiu.styles ;
ALTER TABLE arxiu.styles  CHANGE nom name varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

RENAME TABLE arxiu.idiomes TO arxiu.languages;
ALTER TABLE arxiu.languages CHANGE nom name varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

RENAME TABLE arxiu.lletristes TO arxiu.lyricists;
ALTER TABLE arxiu.lyricists CHANGE nom name varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

RENAME TABLE arxiu.veus TO arxiu.choirTypes;
ALTER TABLE arxiu.choirTypes CHANGE nom name varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL;

