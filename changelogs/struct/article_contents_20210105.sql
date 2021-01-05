ALTER TABLE `article_contents`
MODIFY COLUMN `content`  mediumtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '内容' AFTER `article_id`;

