CREATE TABLE `aandd_reward_item` (
`rew_id` int(11) unsigned NOT NULL COMMENT '評分項目序號',
  `rew_name` varchar(255) NOT NULL COMMENT '評分項目名稱',
  `rew_score` tinyint(3) NOT NULL COMMENT '評分項目分數',
  PRIMARY KEY (`rew_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `aandd_reward_log` (
`log_id` int(11) unsigned NOT NULL,
  `tea_id` smallint(5) unsigned NOT NULL,
  `stu_id` smallint(6) unsigned NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `rew_name` varchar(255) NOT NULL,
  `rew_score` tinyint(3) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `aandd_reward_student` (
`stu_id` smallint(6) unsigned NOT NULL COMMENT '學生序號',
  `stu_sid` varchar(255) NOT NULL,
  `stu_name` varchar(255) NOT NULL COMMENT '學生名字',
  `stu_class` tinyint(3) unsigned NOT NULL COMMENT '學生班級',
  `stu_sex` tinyint(3) unsigned NOT NULL COMMENT '學生性別',
  `stu_num` varchar(8) NOT NULL COMMENT '學生學號',
  PRIMARY KEY (`stu_id`),
  UNIQUE KEY (`stu_num`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE `aandd_reward_teacher` (
`tea_id` smallint(6) unsigned NOT NULL,
  `tea_name` varchar(255) NOT NULL,
  `tea_score` smallint(6) unsigned NOT NULL,
  PRIMARY KEY (`tea_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;