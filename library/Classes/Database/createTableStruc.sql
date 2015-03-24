select concat(name, ', ', email) as combinedlist
from `players`
where activate = 1
order by email







CREATE TABLE `dealer_account` (
  `id` int(11) NOT NULL auto_increment,
  `surname` varchar (255) NOT NULL,
  `forename` varchar (255) NOT NULL,
  `username` varchar (255) NOT NULL,
  `password` varchar (255) NOT NULL,
  `email` varchar (255) NOT NULL,
  `phone_number` varchar (255) NOT NULL,
  `first_line_address` varchar (255) NOT NULL,
  `second_line_address` varchar (255) NOT NULL,
  `active` binary(1) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




<!-- creating user_account table 

CREATE TABLE `user_account` (
  `user_id` int(111) NOT NULL auto_increment,
  
  
  `surname` varchar (255) NOT NULL,
  `forename` varchar (255) NOT NULL,
   `email` varchar (255) NOT NULL,
  `username` varchar (255) NOT NULL,
  `password` varchar (255) NOT NULL,
  `phone_number` varchar (255) NOT NULL,
  `act_code` varchar (255) NOT NULL,
  `active` binary(1) NOT NULL default '0',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


<!-- creating itemTable 

CREATE TABLE events (
  `evnt_id` int(11) NOT NULL auto_increment,
  `evnt_name` varchar (255) NOT NULL,
  `evnt_category` varchar (255) NOT NULL,
  `evnt_publish_date` datetime  NOT NULL,
  `evnt_date` datetime NOT NULL,
  `evnt_cost` varchar (255) NOT NULL,
  `evnt_descr` varchar (255) NOT NULL,
  `evnt_location` varchar (255) NOT NULL,
  `coach_id` int(11) NOT NULL,

  FOREIGN KEY (`coach_id`) REFERENCES coaches(`c_id`),
  PRIMARY KEY  (`event_id`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE order_event (
  `event_order_id` int(11) NOT NULL auto_increment,
  `event_id` int(11) NOT NULL,
  `event_name` varchar (255) NOT NULL,
  `player_id` varchar (255) NOT NULL,
  

  FOREIGN KEY (`event_id`) REFERENCES events(`evnt_id`),
  PRIMARY KEY  (`event_order_id`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




<!-- creating itemTable 

CREATE TABLE itemTable2 (
  `item_id` int(11) NOT NULL auto_increment,  
  `user_id` int(11) NOT NULL,

  `album_title` varchar (255) NOT NULL,
  `artist` varchar (255) NOT NULL,
  `contentType` varchar (255) NOT NULL,
  `genre` varchar (255) NOT NULL,
  `release_year` int(11) NOT NULL,
  `mediaType` varchar (255) NOT NULL,
  `discription` varchar (255) NOT NULL,
  `image_path` varchar (255) NOT NULL,
  `itemSample` varchar (255) NOT NULL,


  FOREIGN KEY (`user_id`) REFERENCES user_account(`user_id`),
  PRIMARY KEY  (`item_id`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;




<!-- creating image table 
CREATE TABLE imageTable (
  `id` int(10) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `type` varchar (64) NOT NULL,
  `name` varchar (64) NOT NULL,
  `alt` text (46) NOT NULL,
  `img` blob(3) NOT NULL,
    

FOREIGN KEY (`user_id`) REFERENCES user_account(`user_id`),
 PRIMARY KEY  (`id`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


<!-- creating media table 
CREATE TABLE mediaTable (
  `mediaId` int(10) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `type` varchar (64) NOT NULL,
  `name` varchar (64) NOT NULL,
  `new_name` text (46) NOT NULL,
  `media` blob(3) NOT NULL,
    

    
	FOREIGN KEY (`user_id`) REFERENCES user_account(`user_id`),
  PRIMARY KEY  (`mediaId`)
)
ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


INSERT INTO dealer_account (surname,forename, username, password,phone_number, email,date)
VALUES ('Oginni', 'Tosin', 'tosyn800', 'password', '07949568565','oo750@gre.ac.uk', '08/10/2009')