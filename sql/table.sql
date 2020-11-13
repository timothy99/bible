CREATE TABLE bible (
	bible_idx int(11) NOT NULL AUTO_INCREMENT COMMENT '인덱스',
	testament varchar(10) NOT NULL COMMENT '신구약 구분',
	bible_name varchar(50) NOT NULL COMMENT '성경책 이름',
	bible_short varchar(50) NOT NULL COMMENT '성경책 짧은 이름',
	chapter int(11) NOT NULL COMMENT '장',
	verse int(11) NOT NULL COMMENT '절',
	title varchar(1000) DEFAULT NULL COMMENT '소제목',
	contents text NOT NULL COMMENT '내용',
	PRIMARY KEY (bible_idx),
	UNIQUE KEY bible_name (bible_name,chapter,verse),
	UNIQUE KEY bible_short (bible_short,chapter,verse)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='성경책';

CREATE TABLE search (
	search_idx int(11) NOT NULL AUTO_INCREMENT COMMENT '인덱스',
	testament varchar(10) NOT NULL COMMENT '신구약 구분',
	bible_name varchar(50) NOT NULL COMMENT '성경책 이름',
	bible_short varchar(50) NOT NULL COMMENT '성경책 짧은 이름',
	chapters varchar(1000) DEFAULT NULL COMMENT '장들 |로 구분하여 입력',
	PRIMARY KEY (search_idx),
	UNIQUE KEY testament (testament,bible_name),
	UNIQUE KEY bible_name (bible_name),
	UNIQUE KEY testament_2 (testament,bible_short)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COMMENT='검색어';
