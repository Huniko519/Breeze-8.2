-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.43 - MySQL Community Server (GPL)
-- Операционная система:         Win64
-- HeidiSQL Версия:              10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица test2.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(256) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.admins: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
REPLACE INTO `admins` (`id`, `username`, `password`) VALUES
	(1, 'admin', '21232f297a57a5a743894a0e4a801fc3');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;

-- Дамп структуры для таблица test2.ads
CREATE TABLE IF NOT EXISTS `ads` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `by_id` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `v_left` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.ads: 0 rows
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;

-- Дамп структуры для таблица test2.blogs
CREATE TABLE IF NOT EXISTS `blogs` (
  `blog_id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_by_id` int(11) NOT NULL,
  `blog_heading` varchar(250) NOT NULL,
  `blog_description` varchar(250) NOT NULL,
  `blog_image` varchar(256) NOT NULL,
  `blog_text` text NOT NULL,
  `blog_tags` varchar(256) NOT NULL,
  `blog_type` int(11) NOT NULL DEFAULT '0',
  `posted_as` int(11) NOT NULL DEFAULT '0',
  `posted_at` int(11) NOT NULL DEFAULT '0',
  `blog_loves` int(11) NOT NULL DEFAULT '0',
  `blog_comments` int(11) NOT NULL DEFAULT '0',
  `blog_views` int(11) NOT NULL,
  `blog_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`blog_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.blogs: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;

-- Дамп структуры для таблица test2.blog_categories
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(250) NOT NULL,
  `cat_counts` int(11) NOT NULL,
  `cat_img` varchar(250) NOT NULL DEFAULT '0',
  `cat_protected` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.blog_categories: 20 rows
/*!40000 ALTER TABLE `blog_categories` DISABLE KEYS */;
REPLACE INTO `blog_categories` (`cid`, `cat_name`, `cat_counts`, `cat_img`, `cat_protected`) VALUES
	(1, '_uni-News', 0, '0', 1),
	(2, '_uni-Lifestyle', 0, '0', 0),
	(3, '_uni-Sports', 0, '0', 0),
	(4, '_uni-Food', 0, '0', 0),
	(5, '_uni-Comedy', 0, '0', 0),
	(6, '_uni-Developers', 0, '0', 0),
	(7, '_uni-Design_Illustration', 0, '0', 0),
	(8, '_uni-Movies_Animation', 0, '0', 0),
	(9, '_uni-Gaming', 0, '0', 0),
	(10, '_uni-History_Facts', 0, '0', 0),
	(11, '_uni-Education', 0, '0', 0),
	(12, '_uni-Places_Regions', 0, '0', 0),
	(13, '_uni-Science_Technology', 0, '0', 0),
	(14, '_uni-Travel_Events', 0, '0', 0),
	(15, '_uni-Economics_Trade', 0, '0', 0),
	(16, '_uni-Cars_Vehicles', 0, '0', 0),
	(17, '_uni-Nature', 0, '0', 0),
	(18, '_uni-Fashion', 0, '0', 0),
	(19, '_uni-Mobiles', 0, '0', 0),
	(20, '_uni-Computers', 0, '0', 0);
/*!40000 ALTER TABLE `blog_categories` ENABLE KEYS */;

-- Дамп структуры для таблица test2.blog_comments
CREATE TABLE IF NOT EXISTS `blog_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `comment_text` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.blog_comments: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;

-- Дамп структуры для таблица test2.blog_loves
CREATE TABLE IF NOT EXISTS `blog_loves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blog_id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.blog_loves: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `blog_loves` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_loves` ENABLE KEYS */;

-- Дамп структуры для таблица test2.categories
CREATE TABLE IF NOT EXISTS `categories` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `cat_name` varchar(300) NOT NULL,
  `cat_sub` int(11) NOT NULL,
  `cat_type` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=141 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.categories: 139 rows
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
REPLACE INTO `categories` (`cid`, `cat_name`, `cat_sub`, `cat_type`) VALUES
	(1, 'pcat_Company', 2, 0),
	(2, 'pcat_Organization', 2, 0),
	(3, 'pcat_Institution', 2, 0),
	(4, 'pcat_Brand', 3, 0),
	(5, 'pcat_Product', 3, 0),
	(6, 'pcat_Artist', 4, 0),
	(7, 'pcat_Band', 4, 0),
	(8, 'pcat_Public_Figure', 4, 0),
	(9, 'pcat_Other', 5, 0),
	(10, 'pcat_Community', 6, 0),
	(11, 'pcat_Travelc', 2, 1),
	(12, 'pcat_Tobacco', 2, 1),
	(13, 'pcat_Telecom', 2, 1),
	(14, 'pcat_Engineering', 2, 1),
	(15, 'pcat_ScienceT', 2, 1),
	(16, 'pcat_School', 2, 1),
	(17, 'pcat_Retialc', 2, 1),
	(18, 'pcat_Religiouso', 2, 1),
	(19, 'pcat_Preschool', 2, 1),
	(20, 'pcat_Politicalp', 2, 1),
	(21, 'pcat_Politicalo', 2, 1),
	(22, 'pcat_Nonp', 2, 1),
	(23, 'pcat_NGO', 2, 1),
	(24, 'pcat_Motorc', 2, 1),
	(25, 'pcat_Minningc', 2, 1),
	(26, 'pcat_schoolm', 2, 1),
	(27, 'pcat_Median', 2, 1),
	(28, 'pcat_Labouru', 2, 1),
	(29, 'pcat_Internetc', 2, 1),
	(30, 'pcat_Insurancec', 2, 1),
	(31, 'pcat_Industrialc', 2, 1),
	(32, 'pcat_schoolh', 2, 1),
	(33, 'pcat_Healthbeauty', 2, 1),
	(34, 'pcat_Government_o', 2, 1),
	(35, 'pcat_Energy_Company', 2, 1),
	(36, 'pcat_Elementry_s', 2, 1),
	(37, 'pcat_Education', 2, 1),
	(38, 'pcat_Consulting_Agency', 2, 1),
	(39, 'pcat_Computer_c', 2, 1),
	(40, 'pcat_Community_o', 2, 1),
	(41, 'pcat_University', 2, 1),
	(42, 'pcat_College', 2, 1),
	(43, 'pcat_College_University', 2, 1),
	(44, 'pcat_Cargo', 2, 1),
	(45, 'pcat_Biotechnology', 2, 1),
	(46, 'pcat_Cause', 2, 1),
	(47, 'pcat_Chemical_company', 2, 1),
	(48, 'pcat_Aerospace_company', 2, 1),
	(49, 'pcat_Community_services', 2, 1),
	(140, 'pcat_Actor', 4, 1),
	(139, 'pcat_Athlete', 4, 1),
	(50, 'pcat_Spiritsw', 3, 1),
	(51, 'pcat_Website', 3, 1),
	(52, 'pcat_Vitaminss', 3, 1),
	(53, 'pcat_Videog', 3, 1),
	(54, 'pcat_Toolse', 3, 1),
	(55, 'pcat_Software', 3, 1),
	(56, 'pcat_Products', 3, 1),
	(57, 'pcat_Phonet', 3, 1),
	(58, 'pcat_Pharmaceuticals', 3, 1),
	(59, 'pcat_Pets', 3, 1),
	(60, 'pcat_Patiov', 3, 1),
	(61, 'pcat_Offices', 3, 1),
	(62, 'pcat_Kichenc', 3, 1),
	(63, 'pcat_Jewelry', 3, 1),
	(64, 'pcat_Households', 3, 1),
	(65, 'pcat_Homed', 3, 1),
	(66, 'pcat_Health_beatuy_2', 3, 1),
	(67, 'pcat_Games_ty', 3, 1),
	(68, 'pcat_Games_ty', 3, 1),
	(69, 'pcat_Electronics', 3, 1),
	(70, 'pcat_Computers', 3, 1),
	(71, 'pcat_Commerciale', 3, 1),
	(72, 'pcat_Clothing', 3, 1),
	(73, 'pcat_Cars', 3, 1),
	(74, 'pcat_Camera', 3, 1),
	(75, 'pcat_Buildingm', 3, 1),
	(76, 'pcat_BoardG', 3, 1),
	(77, 'pcat_Bags', 3, 1),
	(78, 'pcat_Babyg', 3, 1),
	(79, 'pcat_Appliances', 3, 1),
	(80, 'pcat_App', 3, 1),
	(81, 'pcat_Writer', 4, 1),
	(82, 'pcat_Videoc', 4, 1),
	(83, 'pcat_Scientist', 4, 1),
	(84, 'pcat_Producer', 4, 1),
	(85, 'pcat_Politician', 4, 1),
	(86, 'pcat_Politicalc', 4, 1),
	(87, 'pcat_Photographer', 4, 1),
	(88, 'pcat_Newsp', 4, 1),
	(89, 'pcat_Musicianb', 4, 1),
	(90, 'pcat_Musicians', 4, 1),
	(91, 'pcat_Moviec', 4, 1),
	(92, 'pcat_Motivationals', 4, 1),
	(93, 'pcat_Journalist', 4, 1),
	(94, 'pcat_Governmento', 4, 1),
	(95, 'pcat_Filmd', 4, 1),
	(96, 'pcat_Fashionm', 4, 1),
	(97, 'pcat_Entrepreneur', 4, 1),
	(98, 'pcat_Dancer', 4, 1),
	(99, 'pcat_Comedian', 4, 1),
	(100, 'pcat_Coach', 4, 1),
	(101, 'pcat_Chef', 4, 1),
	(102, 'pcat_Blogger', 4, 1),
	(104, 'pcat_TVa', 5, 1),
	(138, 'pcat_Author', 4, 1),
	(105, 'pcat_Moviea', 5, 1),
	(106, 'pcat_TVs', 5, 1),
	(107, 'pcat_TVn', 5, 1),
	(108, 'pcat_TVc', 5, 1),
	(109, 'pcat_Theatricalpp', 5, 1),
	(110, 'pcat_Theatricalp', 5, 1),
	(111, 'pcat_Stadiumsasv', 5, 1),
	(112, 'pcat_Sportst', 5, 1),
	(113, 'pcat_Sportsl', 5, 1),
	(114, 'pcat_Song', 5, 1),
	(115, 'pcat_Schoolss', 5, 1),
	(116, 'pcat_Radios', 5, 1),
	(117, 'pcat_Podcast', 5, 1),
	(118, 'pcat_Performingaa', 5, 1),
	(119, 'pcat_Performancea', 5, 1),
	(120, 'pcat_Performanceev', 5, 1),
	(121, 'pcat_Musicv', 5, 1),
	(122, 'pcat_Musicc', 5, 1),
	(123, 'pcat_Musica', 5, 1),
	(124, 'pcat_Moviets', 5, 1),
	(125, 'pcat_Moviet', 5, 1),
	(126, 'pcat_Moviec', 5, 1),
	(127, 'pcat_Movie', 5, 1),
	(128, 'pcat_Magazine', 5, 1),
	(129, 'pcat_Literarya', 5, 1),
	(130, 'pcat_Library', 5, 1),
	(131, 'pcat_Festival', 5, 1),
	(132, 'pcat_Concertt', 5, 1),
	(133, 'pcat_Bookstoresss', 5, 1),
	(134, 'pcat_Bookss', 5, 1),
	(135, 'pcat_Books', 5, 1),
	(136, 'pcat_Amateurs', 5, 1),
	(137, 'pcat_Association', 6, 1);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;

-- Дамп структуры для таблица test2.chat_forms
CREATE TABLE IF NOT EXISTS `chat_forms` (
  `form_id` int(11) NOT NULL AUTO_INCREMENT,
  `form_type` tinyint(4) NOT NULL DEFAULT '1',
  `form_icon` varchar(250) NOT NULL DEFAULT 'default.png',
  `form_cover` varchar(250) NOT NULL DEFAULT 'default.png',
  `form_name` text NOT NULL,
  `form_description` text NOT NULL,
  `form_date` date NOT NULL,
  `form_by` int(11) NOT NULL,
  `form_to` int(11) NOT NULL,
  `form_active` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.chat_forms: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `chat_forms` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_forms` ENABLE KEYS */;

-- Дамп структуры для таблица test2.chat_messages
CREATE TABLE IF NOT EXISTS `chat_messages` (
  `mid` int(11) NOT NULL AUTO_INCREMENT,
  `m_type` tinyint(4) NOT NULL,
  `m_text` text NOT NULL,
  `by` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `posted_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`mid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.chat_messages: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `chat_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_messages` ENABLE KEYS */;

-- Дамп структуры для таблица test2.chat_users
CREATE TABLE IF NOT EXISTS `chat_users` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `form_id` int(11) NOT NULL,
  `on_form` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.chat_users: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `chat_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `chat_users` ENABLE KEYS */;

-- Дамп структуры для таблица test2.comment_likes
CREATE TABLE IF NOT EXISTS `comment_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.comment_likes: 0 rows
/*!40000 ALTER TABLE `comment_likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `comment_likes` ENABLE KEYS */;

-- Дамп структуры для таблица test2.extensions
CREATE TABLE IF NOT EXISTS `extensions` (
  `eid` int(11) NOT NULL AUTO_INCREMENT,
  `ext_name` varchar(250) NOT NULL,
  `ext_extras` varchar(800) NOT NULL,
  `ext_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ext_status` tinyint(11) NOT NULL,
  `ext_author` varchar(250) NOT NULL,
  `ext_description` varchar(400) NOT NULL,
  PRIMARY KEY (`eid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.extensions: 0 rows
/*!40000 ALTER TABLE `extensions` DISABLE KEYS */;
/*!40000 ALTER TABLE `extensions` ENABLE KEYS */;

-- Дамп структуры для таблица test2.friendships
CREATE TABLE IF NOT EXISTS `friendships` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user1` int(11) NOT NULL,
  `user2` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.friendships: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `friendships` DISABLE KEYS */;
/*!40000 ALTER TABLE `friendships` ENABLE KEYS */;

-- Дамп структуры для таблица test2.groups
CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_username` varchar(200) NOT NULL,
  `group_name` varchar(256) NOT NULL,
  `group_icon` int(11) NOT NULL,
  `group_cover` varchar(256) NOT NULL DEFAULT 'default.png',
  `group_owner` int(11) NOT NULL,
  `group_description` text NOT NULL,
  `group_location` varchar(256) NOT NULL,
  `group_web` varchar(256) NOT NULL,
  `group_email` varchar(256) NOT NULL,
  `group_privacy` int(11) NOT NULL DEFAULT '1',
  `group_approval_type` int(11) NOT NULL DEFAULT '1',
  `group_posting` int(11) NOT NULL DEFAULT '1',
  `group_users` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.groups: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
REPLACE INTO `groups` (`group_id`, `group_username`, `group_name`, `group_icon`, `group_cover`, `group_owner`, `group_description`, `group_location`, `group_web`, `group_email`, `group_privacy`, `group_approval_type`, `group_posting`, `group_users`, `time`) VALUES
	(1, 'mygroups', 'Моя прекрасная', 1, 'default.png', 2, '', '', '', '', 1, 1, 1, 1, '2020-04-28 00:30:31');
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;

-- Дамп структуры для таблица test2.group_logs
CREATE TABLE IF NOT EXISTS `group_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.group_logs: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `group_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `group_logs` ENABLE KEYS */;

-- Дамп структуры для таблица test2.group_users
CREATE TABLE IF NOT EXISTS `group_users` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `group_role` int(11) NOT NULL,
  `p_post` tinyint(4) NOT NULL DEFAULT '0',
  `p_cover` tinyint(4) NOT NULL DEFAULT '0',
  `p_activity` tinyint(4) NOT NULL DEFAULT '0',
  `f_feeds` tinyint(4) NOT NULL DEFAULT '1',
  `group_partner_id` int(11) NOT NULL,
  `group_status` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.group_users: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `group_users` DISABLE KEYS */;
REPLACE INTO `group_users` (`gid`, `user_id`, `group_id`, `group_role`, `p_post`, `p_cover`, `p_activity`, `f_feeds`, `group_partner_id`, `group_status`, `time`) VALUES
	(1, 2, 1, 2, 0, 0, 0, 1, 2, 1, '2020-04-28 00:30:31');
/*!40000 ALTER TABLE `group_users` ENABLE KEYS */;

-- Дамп структуры для таблица test2.info_pages
CREATE TABLE IF NOT EXISTS `info_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `title_nav` varchar(250) NOT NULL,
  `title_big` varchar(500) NOT NULL,
  `text` text NOT NULL,
  `public` tinyint(4) NOT NULL DEFAULT '0',
  `published` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.info_pages: 4 rows
/*!40000 ALTER TABLE `info_pages` DISABLE KEYS */;
REPLACE INTO `info_pages` (`id`, `title`, `title_nav`, `title_big`, `text`, `public`, `published`) VALUES
	(3, 'Developers', 'API Documentation', 'API Documentation - 3.0.0', 'Our API allows you to retrieve informations from our website via <strong>GET</strong> request and supports the following query parameters:\n<br><br>\n<table class="border" width="100%">\n    <tr>\n        <td class="bottom-divider b2 padding-5" width="20%" valign="top">Name</td>\n        <td class="bottom-divider b2 padding-5" width="20%" valign="top">Meaning</td>\n        <td class="bottom-divider b2 padding-5 left-divider" width="60%" valign="top">Description</td>\n    </tr>\n    <tr>\n        <td class="padding-5" width="20%" valign="top"><strong>get(required)</strong></td>\n        <td class="padding-5" width="20%" valign="top">Specify query type</td>\n        <td class="padding-5 left-divider" width="60%" valign="top">\n      Supports following types\n   <ul>\n    <li><strong>get=user</strong> to get user</li>\n    <li><strong>get=posts</strong> to get posts</li>\n    <li><strong>get=special</strong> to use special params</li>\n   </ul>\n  </td>\n </tr>\n    <tr>\n        <td class="bottom-divider padding-5" width="20%" valign="top"></td>\n        <td class="bottom-divider padding-5" width="20%" valign="top"></td>\n        <td class="bottom-divider padding-5 left-divider" width="60%" valign="top"></td>\n </tr>\n \n    <tr>\n        <td class="padding-5" width="20%" valign="top"><strong>id</strong></td>\n        <td class="padding-5" width="20%" valign="top">Specify user id or username</td>\n        <td class="padding-5 left-divider" width="60%" valign="top">\n      Supports following params\n   <ul>\n    <li><strong>id=username</strong> if you have username</li>\n    <li><strong>id=userid</strong> if you have user id</li>\n   </ul>\n  </td>\n </tr>\n    <tr>\n        <td class="bottom-divider padding-5" width="20%" valign="top"></td>\n        <td class="bottom-divider padding-5" width="20%" valign="top"></td>\n        <td class="bottom-divider left-divider padding-5" width="60%" valign="top"></td>\n </tr>\n \n    <tr>\n        <td class="padding-5" width="20%" valign="top"><strong>pt</strong></td>\n        <td class="padding-5" width="20%" valign="top">Specify posts type</td>\n        <td class="padding-5 left-divider" width="60%" valign="top">\n      Supports following params\n   <ul>\n    <li><strong>pt=photos</strong> to fetch photos</li>\n    <li><strong>pt=videos</strong> to fetch videos</li>\n    <li><strong>pt=status</strong> to fetch status</li>\n   </ul>\n  </td>\n </tr>\n    <tr>\n        <td class="bottom-divider padding-5" width="20%" valign="top"></td>\n        <td class="bottom-divider padding-5" width="20%" valign="top"></td>\n        <td class="bottom-divider left-divider padding-5" width="60%" valign="top"></td>\n </tr>\n</table>\n<br><br>\n<div class="h7 b2 dark-font-only">Examples</div>\n<br>\n<table class="border" width="100%">\n    <tr>\n        <td class="bottom-divider b2 padding-5" width="60%" valign="top">Address</td>\n        <td class="bottom-divider b2 padding-5 left-divide" width="40%" valign="top">Data</td>\n    </tr>\n    <tr>\n        <td class="bottom-divider padding-5" width="60%" valign="top">{URL}/api.php?get=user&id=myusername</td>\n        <td class="bottom-divider padding-5 left-divider" width="40%" valign="top">Details of user having username <strong>myusername</strong></td>\n </tr>\n\n    <tr>\n        <td class="bottom-divider padding-5" width="60%" valign="top">{URL}/api.php?get=user&id=2654</td>\n        <td class="bottom-divider padding-5 left-divider" width="40%" valign="top">Details of user having user id <strong>2654</strong></td>\n </tr>\n \n    <tr>\n        <td class="bottom-divider padding-5" width="60%" valign="top">{URL}/api.php?get=posts&id=2654</td>\n        <td class="bottom-divider padding-5 left-divider" width="40%" valign="top">Recent 12 posts(Only status updates) of user having user id <strong>2654</strong></td>\n </tr>\n \n    <tr>\n        <td class="bottom-divider padding-5" width="60%" valign="top">{URL}/api.php?get=posts&id=2654&pt=photos</td>\n        <td class="bottom-divider padding-5 left-divider" width="40%" valign="top">Recent 12 posts(Only photos) of user having user id <strong>2654</strong></td>\n </tr> \n\n    <tr>\n        <td class="bottom-divider padding-5" width="60%" valign="top">{URL}/api.php?get=posts&id=myusername&pt=videos</td>\n        <td class="bottom-divider padding-5 left-divider" width="40%" valign="top">Recent 12 posts(Only videos) of user having userame <strong>myusername</strong></td>\n </tr>\n \n</table>\n<br><br>\n<div class="h7 b2 dark-font-only">Data</div>\n<br>\n<table class="border" width="100%">\n    <tr>\n        <td class="bottom-divider b2 padding-5" width="30%" valign="top">Parameter</td>\n        <td class="bottom-divider b2 padding-5 left-divide" width="70%" valign="top">Return</td>\n    </tr>\n    <tr>\n        <td class="bottom-divider padding-5" width="30%" valign="top"><strong>get=user</strong></td>\n        <td class="bottom-divider padding-5 left-divider" width="70%" valign="top">\n      Array(s) with Indexs\n   <ul>\n    <li><strong>id</strong> = user id</li>\n    <li><strong>username</strong> = username</li>\n    <li><strong>first_name</strong> = first name</li>\n    <li><strong>last_name</strong> = last name</li>\n    <li><strong>image</strong> = profile image</li>\n    <li><strong>cover</strong> = profile cover</li>\n    <li><strong>verified</strong> = is user profile verified (BOOLEAN)</li>\n    <li><strong>profession</strong> = user profession</li>\n    <li><strong>study</strong> = user education</li>\n    <li><strong>gender</strong> = gender (BOOLEAN)</li>\n    <li><strong>hometown</strong> = user hometown</li>\n    <li><strong>living</strong> = user current location</li>\n    <li><strong>relationship</strong> = user relationship (BOOLEAN)</li>\n    <li><strong>website</strong> = user website</li>\n    <li><strong>bio</strong> = user bio</li>\n   </ul>\n  </td>\n </tr>\n \n    <tr>\n        <td class="bottom-divider padding-5" width="30%" valign="top"><strong>get=posts<strong></td>\n        <td class="bottom-divider padding-5 left-divider" width="70%" valign="top">\n      Array(s) with Indexs\n   <ul>\n    <li><strong>post_id</strong> = post id</li>\n    <li><strong>post_text</strong> = post text</li>\n    <li><strong>post_likes</strong> = number of post likes</li>\n    <li><strong>post_comments</strong> = number of post comments</li>\n    <li><strong>post_time</strong> = posted time (TIMESTAMP)</li>\n   </ul>\n  </td>\n </tr>\n\n <tr>\n        <td class="bottom-divider padding-5" width="30%" valign="top"><strong>get=posts</strong>&<strong>pt=photos</strong></td>\n        <td class="bottom-divider padding-5 left-divider" width="70%" valign="top">\n      Return all standard indexs of parameter <strong>get=posts</strong> plus \n   <ul>\n    <li><strong>post_photo</strong> = image name on network (e.g. 892j74.jpg)</li>\n   </ul>\n  </td>\n </tr>\n <tr>\n        <td class="bottom-divider padding-5" width="30%" valign="top"><strong>get=posts</strong>&<strong>pt=videos</strong></td>\n        <td class="bottom-divider padding-5 left-divider" width="70%" valign="top">\n      Return all standard indexs of parameter <strong>get=posts</strong> plus \n   <ul>\n    <li><strong>post_video_link</strong> = youtube embed link(e.g. http://youtube.com/embed/3js4kj4fs)</li>\n   </ul>\n  </td>\n </tr> \n \n</table>\n<br><br>\n<div class="h7 b2 dark-font-only">Advance parameters</div>\n<br>\n<table class="border" width="100%">\n    <tr>\n        <td class="bottom-divider b2 padding-5" width="15%" valign="top">Name</td>\n        <td class="bottom-divider b2 padding-5 left-divider" width="10%" valign="top">Possible values</td>\n        <td class="bottom-divider b2 padding-5 left-divider" width="30%" valign="top">Meaning</td>\n        <td class="bottom-divider b2 padding-5 left-divider" width="45%" valign="top">Example</td>\n    </tr>\n    <tr>\n        <td class="bottom-divider padding-5" width="15%" valign="top"><strong>limit</strong></td>\n        <td class="bottom-divider padding-5 left-divider" width="10%" valign="top">1,2,5,10,12,15,20</td>\n        <td class="bottom-divider padding-5 left-divider" width="30%" valign="top">Set maximum number of posts</td>\n        <td class="bottom-divider padding-5 left-divider" width="45%" valign="top">{URL}/api.php?get=posts&limit=2</td>\n </tr>\n    <tr>\n        <td class="bottom-divider padding-5" width="15%" valign="top"><strong>verify</strong></td>\n        <td class="bottom-divider padding-5 left-divider" width="10%" valign="top">User id or username</td>\n        <td class="bottom-divider padding-5 left-divider" width="30%" valign="top">Return index <strong>verified=1</strong> if user profile is verified</td>\n        <td class="bottom-divider padding-5 left-divider" width="45%" valign="top">{URL}/api.php?get=special&verify=myusername</td>\n </tr>\n    <tr>\n        <td class="bottom-divider padding-5" width="15%" valign="top"><strong>isusername</strong></td>\n        <td class="bottom-divider padding-5 left-divider" width="10%" valign="top">Username</td>\n        <td class="bottom-divider padding-5 left-divider" width="30%" valign="top">Return index <strong>in_use=1</strong> if username is in use</td>\n        <td class="bottom-divider padding-5 left-divider" width="45%" valign="top">{URL}/api.php?get=special&isusername=myusername</td>\n </tr> \n    <tr>\n        <td class="bottom-divider padding-5" width="15%" valign="top"><strong>ismail</strong></td>\n        <td class="bottom-divider padding-5 left-divider" width="10%" valign="top">Email</td>\n        <td class="bottom-divider padding-5 left-divider" width="30%" valign="top">Return index <strong>in_use=1</strong> if email  is in use</td>\n        <td class="bottom-divider padding-5 left-divider" width="45%" valign="top">{URL}/api.php?get=special&ismail=myemail@sd.net</td>\n </tr>\n</table>', 1, 1),
	(2, 'Privacy', 'Privacy Policy', 'We love Privacy', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse eu interdum purus. Vestibulum dapibus nulla vel eros gravida, ac finibus enim mattis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec venenatis dapibus nunc ut iaculis. Suspendisse lobortis lorem sit amet erat feugiat, eu semper arcu molestie. In hendrerit dapibus feugiat. Donec aliquam, libero dapibus tristique ultricies, eros eros feugiat sem, nec eleifend purus diam quis magna. Nam neque enim, rhoncus quis dictum ac, ultricies vitae magna. Curabitur porttitor ornare ipsum a laoreet. Sed at commodo dui, ac pharetra nulla.\n<br><br>\nVestibulum ut metus quis risus accumsan accumsan sed non dui. Etiam efficitur quis metus et facilisis. Cras consequat accumsan bibendum. Nam at purus nec arcu ullamcorper molestie id ac diam. Donec vestibulum, quam quis rutrum commodo, metus nibh malesuada nulla, sit amet accumsan tortor tellus eget odio. Nam leo erat, varius vitae pellentesque eu, convallis in velit. Donec luctus id enim id ultricies. Suspendisse a tortor ac ex vehicula tincidunt sit amet ac risus. Proin vitae turpis ac est finibus mattis a vitae ligula. Vivamus eget molestie mi. Phasellus ex mauris, tincidunt vel tortor a, viverra pellentesque magna. Pellentesque in turpis ut velit mattis placerat vel vitae tellus. Duis tincidunt posuere suscipit. Donec nibh urna, tempor sit amet ex vel, dignissim aliquam turpis. In vehicula eget elit nec volutpat.\n<br><br>\nMorbi quis iaculis massa, et ornare est. Nunc maximus id nisi ac luctus. Phasellus pretium mauris vitae imperdiet pharetra. Mauris hendrerit eu nisl eget egestas. Suspendisse felis mi, tincidunt et dignissim vestibulum, tincidunt in nisl. Fusce vehicula nunc at volutpat aliquam. Aliquam aliquam eros a nisi hendrerit, non vehicula neque tincidunt. Donec blandit condimentum tempor.\n<br><br>\nNam imperdiet enim sit amet ex posuere, sit amet condimentum diam luctus. Vestibulum vitae felis gravida, vestibulum enim a, posuere leo. Aenean mollis, tellus non cursus faucibus, velit neque euismod diam, id iaculis dolor purus id dui. Etiam maximus tristique nibh. Integer id purus ut nisi maximus ultricies sed sit amet est. Etiam vehicula arcu quis feugiat eleifend. Suspendisse justo magna, vehicula at commodo eget, tempor quis diam. Duis maximus rhoncus est ac ultricies.\n<br><br>\nNunc vitae efficitur nunc. Pellentesque pharetra ante eros, nec venenatis tellus vestibulum vitae. Phasellus varius orci eu pretium commodo. Aenean sollicitudin odio at vulputate gravida. Suspendisse potenti. Nam fringilla in orci at ullamcorper. Cras vel congue mauris. Duis pulvinar lorem at ultricies molestie. Maecenas luctus varius congue. Aliquam quis neque non velit egestas dignissim a ac eros. Interdum et malesuada fames ac ante ipsum primis in faucibus. Sed quis urna suscipit, ullamcorper tellus viverra, ornare diam. ', 1, 1),
	(1, 'Docs', 'Doc Center', 'Welcome to Doc Center', '<div class="h7 b3 padding-10 dark-font-only">\nNavigate\n</div>\n\n<div onclick="browseData(2);" class="h5 b1 padding-10 pointer dark-font">\n   Privacy Policy\n</div>\n<div onclick="browseData(3);" class="h5 b1 padding-10 pointer dark-font">\n   API Documentation\n</div>', 0, 0),
	(4, 'Libraries', 'Libraries', 'Appreciate those who helps !', 'We would like to thank all the authors/creators of the following projects/packages.\r\n<br><br>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">Flaticon - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">Icons</span></span><br>\r\n    All SVG and PNG icons are made by the various authors from <a href="https://www.flaticon.com/" class="theme-font h5 b2" title="Flaticon">flaticon.com</a> and licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" class="theme-font h5 b2" target="_blank">CC 3.0</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">JQuery - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">JavaScript</span></span><br>\r\n    Copyright (c) JQuery Foundation, Inc. JQuery.org/license <a href="https://github.com/jquery/jquery" class="theme-font h5 b2">GitHub</a> / <a href="https://jquery.com/" class="theme-font h5 b2" target="_blank">Jquery.com</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">TinyMCE - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">JavaScript</span></span><br>\r\n    Copyright (c) 2016 Artem Tabalin <a href="http://js-socials.com/" class="theme-font h5 b2">Website</a> / <a href="http://js-socials.com/docs/" class="theme-font h5 b2" target="_blank">Documentation</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">JSSocials - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">JavaScript</span></span><br>\r\n    Copyright (C) 1991, 1999 <a href="https://www.tinymce.com/" class="theme-font h5 b2">Website</a> / <a href="https://www.tinymce.com/docs/" class="theme-font h5 b2" target="_blank">Documentation</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">Chart.Js - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">JavaScript</span></span><br>\r\n    Copyright (c) Nick Downie Released under the MIT license <a href="https://www.chartjs.org/" class="theme-font h5 b2">ChatJs.com</a> / <a href="https://github.com/chartjs" class="theme-font h5 b2" target="_blank">GitHub</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">Nice Select - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">Jquery</span></span><br>\r\n    A lightweight jQuery plugin that replaces native select elements with customizable dropdowns.Made by <a href="http://hernansartorio.com/?r=ns" class="theme-font h5 b2">Hernan Sartorio</a> / <a href="https://github.com/hernansartorio/jquery-nice-select" class="theme-font h5 b2" target="_blank">GitHub</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">Nice Scroll - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">Jquery</span></span><br>\r\n    Copyright (c) InuYaksa Released under the MIT license <a href="https://areaaperta.com/nicescroll/" class="theme-font h5 b2">InuYaksa</a> / <a href="https://github.com/hernansartorio/jquery-nice-select" class="theme-font h5 b2" target="_blank">GitHub</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">Timeago - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">Jquery</span></span><br>\r\n    Copyright (c) Ryan McGeary Released under the MIT license <a href="http://timeago.yarp.com/" class="theme-font h5 b2">TimeAgo</a> / <a href="https://github.com/rmm5t/jquery-timeago" class="theme-font h5 b2" target="_blank">GitHub</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">Slick Slider - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">Jquery</span></span><br>\r\n    Copyright (c) Madebyoliver Released under the MIT license <a href="http://kenwheeler.github.io/" class="theme-font h5 b2">Portfolio</a> / <a href="https://github.com/kenwheeler/slick/" class="theme-font h5 b2" target="_blank">GitHub</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">Owl Carousel - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">Jquery</span></span><br>\r\n    Copyright (c) David Deutsch Released under the MIT license <a href="https://owlcarousel2.github.io/OwlCarousel2/" class="theme-font h5 b2">OwlCarousel</a> / <a href="https://github.com/OwlCarousel2/OwlCarousel2" class="theme-font h5 b2" target="_blank">GitHub</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only"> HTML5 Placeholder - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">Jquery</span></span><br>\r\n    Copyright (c) Mathias Bynens Released under the MIT license <a href="https://github.com/mathiasbynens/jquery-placeholder" class="theme-font h5 b2" target="_blank">GitHub</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only"> Initialize - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">Jquery</span></span><br>\r\n    Copyright (c) Adam Pietrasiak Released under the MIT license <a href="https://github.com/pie6k/jquery.initialize" class="theme-font h5 b2" target="_blank">GitHub</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">Jelly Radios - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">CSS</span></span><br>\r\n    Copyright (c) <a href="https://codepen.io/tomma5o/" class="theme-font h5 b2">Tommaso Poletti</a> / <a href="https://codepen.io/tomma5o/pen/grJyzL" class="theme-font h5 b2" target="_blank">CodePen</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">HTML Purifier - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">PHP</span></span><br>\r\n    Copyright (C) 2006-2008 Edward Z. Yang <a href="http://htmlpurifier.org/" class="theme-font h5 b2">Project</a> / <a href="http://htmlpurifier.org/docs" class="theme-font h5 b2" target="_blank">Documentation</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">TimThumb - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">PHP</span></span><br>\r\n    Copyright (c) Ben Gillbanks and Mark Maunder Based on work done by Tim McDaniels and Darren Hoyt Licensed under GNU General Public License <a href="https://code.google.com/archive/p/timthumb/" class="theme-font h5 b2">Project</a> / <a href="https://www.binarymoon.co.uk/projects/timthumb/" class="theme-font h5 b2" target="_blank">Documentation</a>\r\n</div>\r\n<div class="padding-10-0 bottom-divider">\r\n    <span class="h7 b2 dark-font-only">Font Awesome - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">Font Icons</span></span><br>\r\n    Copyright (c) <a href="https://twitter.com/davegandy" class="theme-font h5 b2">Dave Gandy</a> / <a href="https://fontawesome.com/" class="theme-font h5 b2" target="_blank">FontAwesome.com</a>\r\n</div>\r\n<div class="padding-10-0">\r\n    <span class="h7 b2 dark-font-only">Roboto - <span style="padding:2px 4px;" class="background-0 h4 b1 rounded white-font-only">Fonts</span></span><br>\r\n    Copyright (c) Christian Robertson <a href="https://github.com/google/roboto/" class="theme-font h5 b2">Github</a>\r\n</div>', 1, 1);
/*!40000 ALTER TABLE `info_pages` ENABLE KEYS */;

-- Дамп структуры для таблица test2.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `not_from` int(11) NOT NULL,
  `not_to` int(11) NOT NULL,
  `not_content_id` int(11) NOT NULL,
  `not_content` int(11) NOT NULL,
  `not_data` varchar(250) NOT NULL DEFAULT 'Empty',
  `not_type` int(11) NOT NULL,
  `not_read` int(11) NOT NULL,
  `not_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.notifications: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;

-- Дамп структуры для таблица test2.pages
CREATE TABLE IF NOT EXISTS `pages` (
  `page_id` int(11) NOT NULL AUTO_INCREMENT,
  `page_username` varchar(200) NOT NULL,
  `page_name` varchar(300) NOT NULL,
  `page_verified` tinyint(4) NOT NULL DEFAULT '0',
  `page_icon` varchar(256) NOT NULL,
  `page_cover` varchar(256) NOT NULL,
  `page_owner` int(11) NOT NULL,
  `page_cat` int(11) NOT NULL,
  `page_sub_cat` varchar(200) NOT NULL,
  `page_location` varchar(256) NOT NULL,
  `page_description` text NOT NULL,
  `page_email` varchar(256) NOT NULL,
  `page_web` varchar(256) NOT NULL,
  `page_likes` int(11) NOT NULL,
  `page_follows` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.pages: 0 rows
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;

-- Дамп структуры для таблица test2.page_likes
CREATE TABLE IF NOT EXISTS `page_likes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.page_likes: 0 rows
/*!40000 ALTER TABLE `page_likes` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_likes` ENABLE KEYS */;

-- Дамп структуры для таблица test2.page_logs
CREATE TABLE IF NOT EXISTS `page_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `type` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.page_logs: 0 rows
/*!40000 ALTER TABLE `page_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_logs` ENABLE KEYS */;

-- Дамп структуры для таблица test2.page_roles
CREATE TABLE IF NOT EXISTS `page_roles` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `page_partner_id` int(11) NOT NULL,
  `page_role` int(11) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.page_roles: 0 rows
/*!40000 ALTER TABLE `page_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_roles` ENABLE KEYS */;

-- Дамп структуры для таблица test2.page_users
CREATE TABLE IF NOT EXISTS `page_users` (
  `pfid` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL,
  `f_feeds` tinyint(4) NOT NULL DEFAULT '1',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pfid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.page_users: 0 rows
/*!40000 ALTER TABLE `page_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_users` ENABLE KEYS */;

-- Дамп структуры для таблица test2.patches
CREATE TABLE IF NOT EXISTS `patches` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `p_name` varchar(250) NOT NULL,
  `p_name_main` varchar(250) NOT NULL,
  `p_description` varchar(500) NOT NULL,
  `p_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.patches: 0 rows
/*!40000 ALTER TABLE `patches` DISABLE KEYS */;
/*!40000 ALTER TABLE `patches` ENABLE KEYS */;

-- Дамп структуры для таблица test2.payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `by_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `payer_email` varchar(128) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `txn_id` varchar(32) NOT NULL,
  `currency` varchar(3) NOT NULL,
  `type` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `time` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.payments: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;

-- Дамп структуры для таблица test2.post_comments
CREATE TABLE IF NOT EXISTS `post_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `commented_as` tinyint(4) NOT NULL DEFAULT '0',
  `comment_text` text NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `safe` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.post_comments: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `post_comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_comments` ENABLE KEYS */;

-- Дамп структуры для таблица test2.post_loves
CREATE TABLE IF NOT EXISTS `post_loves` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.post_loves: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `post_loves` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_loves` ENABLE KEYS */;

-- Дамп структуры для таблица test2.reports
CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `content_id` int(11) NOT NULL,
  `content_owner` int(11) NOT NULL,
  `type` tinyint(11) NOT NULL DEFAULT '0',
  `val1` tinyint(4) NOT NULL DEFAULT '0',
  `val2` tinyint(4) NOT NULL DEFAULT '0',
  `val3` tinyint(4) NOT NULL DEFAULT '0',
  `val4` tinyint(4) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.reports: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `reports` DISABLE KEYS */;
/*!40000 ALTER TABLE `reports` ENABLE KEYS */;

-- Дамп структуры для таблица test2.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `sid` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(256) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.settings: ~128 rows (приблизительно)
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
REPLACE INTO `settings` (`sid`, `key`, `value`) VALUES
	(1, 'web_name', 'Моя социальная сеть'),
	(2, 'title', 'Моя социальная сеть'),
	(3, 'theme', 'Standards'),
	(4, 'posts_per_page', '15'),
	(5, 'photos_per_page', '18'),
	(6, 'results_per_page', '10'),
	(7, 'lovers_per_page', '15'),
	(8, 'comments_per_widget', '6'),
	(9, 'search_results_per_page', '15'),
	(10, 'chats_per_page', '15'),
	(11, 'max_post_length', '1500'),
	(12, 'max_comment_length', '1500'),
	(13, 'max_message_length', '500'),
	(14, 'jpeg_quality', '100'),
	(15, 'max_img_size', '10'),
	(16, 'max_image_size', '1080'),
	(17, 'max_main_pics', '720'),
	(18, 'max_cover_pics', '1080'),
	(19, 'max_chat_icons', '1080'),
	(20, 'max_chat_covers', '1080'),
	(21, 'font_colors_welcome', 'white'),
	(22, 'captcha', '1'),
	(23, 'mentions_type', '0'),
	(24, 'inf_scroll', '1'),
	(25, 'smtp_email', '0'),
	(26, 'smtp_host', ''),
	(27, 'smtp_port', ''),
	(28, 'smtp_auth', '0'),
	(29, 'smtp_username', ''),
	(30, 'smtp_password', ''),
	(31, 'username_min_len', '6'),
	(32, 'username_max_len', '32'),
	(33, 'password_min_len', '4'),
	(34, 'password_max_len', '32'),
	(35, 'emails_verification', '0'),
	(36, 'def_p_verified', '0'),
	(37, 'def_p_image', 'default.png'),
	(38, 'def_p_cover', 'default.png'),
	(39, 'def_n_per_page', '10'),
	(40, 'def_n_accept', '1'),
	(41, 'def_n_type', '1'),
	(42, 'def_n_follower', '1'),
	(43, 'def_n_like', '1'),
	(44, 'def_n_comment', '1'),
	(45, 'def_p_moderators', '0'),
	(46, 'def_p_posts', '0'),
	(47, 'def_p_followers', '0'),
	(48, 'def_p_followings', '0'),
	(49, 'def_p_profession', '0'),
	(50, 'def_p_hometown', '0'),
	(51, 'def_p_location', '0'),
	(52, 'def_p_private', '0'),
	(53, 'def_b_posts', '0'),
	(54, 'def_b_comments', '0'),
	(55, 'def_b_users', '0'),
	(56, 'def_r_posts_per_page', '5'),
	(57, 'def_r_followers_per_page', '8'),
	(58, 'def_r_followings_per_page', '8'),
	(59, 'po_add_visit', ''),
	(60, 'po_add_out', ''),
	(61, 'po_add_home', ''),
	(62, 'po_add_trending', ''),
	(63, 'po_add_conn_user', ''),
	(64, 'po_add_conn_post', ''),
	(65, 'fi_add_home1', ''),
	(66, 'fi_add_search', ''),
	(67, 'fi_add_feed', ''),
	(68, 'fi_add_trending', ''),
	(69, 'fi_add_post', ''),
	(70, 'fi_add_relatives', ''),
	(71, 'post_backgrounds', '1,2,3,4,5,6,7,8,9,10'),
	(72, 'default_lang', 'Russian'),
	(73, 'public_profile_followers', '25'),
	(74, 'public_profile_followings', '25'),
	(75, 'trendind_per_limit', '25'),
	(76, 'suggestions_limit', '15'),
	(77, 'active_limit', '15'),
	(78, 'express_activity_per_limit', '15'),
	(79, 'public_profile_similar', '10'),
	(80, 'MAX_IMAGES', '10'),
	(81, 'min_chat_len1', '6'),
	(82, 'max_chat_len1', '32'),
	(83, 'max_chat_len2', '500'),
	(84, 'group_results_per_page', '15'),
	(85, 'group_requests_per_page', '15'),
	(86, 'group_log_per_page', '15'),
	(87, 'groups_joined_limit', '15'),
	(88, 'feature_expressfriends', '1'),
	(89, 'feature_expressactivity', '1'),
	(90, 'feature_expressautoplay', '1'),
	(91, 'feature_expresssuggestions', '1'),
	(92, 'feature_tags_on_home', '1'),
	(93, 'feature_tags_on_search', '1'),
	(94, 'feature_tags_on_group_search', '1'),
	(95, 'feature_tags_on_top_search', '1'),
	(96, 'feature_tags_on_video_search', '1'),
	(97, 'feature_tags_on_searchtags', '1'),
	(98, 'feature_tags_on_searchphotos', '1'),
	(99, 'groups_on_home', '1'),
	(100, 'groups_on_top_search', '1'),
	(101, 'groups_on_group_search', '1'),
	(102, 'groups_on_page_search', '1'),
	(103, 'poll_inbox', '0'),
	(104, 'poll_notifications', '0'),
	(105, 'poll_messages', '0'),
	(106, 'bible_view', '0,0,0,0,0,0'),
	(107, 'refresh_code', '?8.2.0.0'),
	(108, 'font_size', '10'),
	(109, 'sp_feeds', '1'),
	(110, 'v_price', '0.01'),
	(111, 'v_cc', 'USD'),
	(112, 'pp_sandbox', '1'),
	(113, 'pp_email', ''),
	(114, 'pp_user', ''),
	(115, 'pp_pass', ''),
	(116, 'pp_sign', ''),
	(117, 'bo_ads', '0'),
	(118, 'video_extensions', 'mp4,avi,webm'),
	(119, 'max_vid_size', '2'),
	(120, 'timezone', 'Asia/Chita'),
	(121, 'reactions', '0,like,reactions/like.svg;1,love,reactions/love.svg;2,haha,reactions/haha.svg;3,wow,reactions/wow.svg;4,sad,reactions/sad.svg;5,angry,reactions/angry.svg;'),
	(122, 'readable_blogs', '3'),
	(123, 'joinable_groups', '10'),
	(124, 'trendind_pages_limit', '12'),
	(125, 't_posts_per_load', '10'),
	(126, 'readable_categories', '40'),
	(127, 'websocketserver', '0'),
	(128, 'socket_host', 'ws://127.0.0.1:9000');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;

-- Дамп структуры для таблица test2.updates
CREATE TABLE IF NOT EXISTS `updates` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `u_name` varchar(250) NOT NULL,
  `u_description` text NOT NULL,
  `u_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `u_version` varchar(250) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.updates: 0 rows
/*!40000 ALTER TABLE `updates` DISABLE KEYS */;
/*!40000 ALTER TABLE `updates` ENABLE KEYS */;

-- Дамп структуры для таблица test2.users
CREATE TABLE IF NOT EXISTS `users` (
  `idu` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(256) NOT NULL,
  `password` varchar(256) NOT NULL,
  `email` varchar(256) NOT NULL,
  `salt` varchar(250) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `date` date NOT NULL,
  `active` int(11) NOT NULL,
  `image` varchar(128) NOT NULL DEFAULT 'default.png',
  `cover` varchar(128) NOT NULL DEFAULT 'default.png',
  `verified` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `onfeeds` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `onmessenger` timestamp NOT NULL,
  `b_day` varchar(64) NOT NULL DEFAULT '0',
  `profession` varchar(250) NOT NULL,
  `from` varchar(250) NOT NULL,
  `living` varchar(250) NOT NULL,
  `gender` tinyint(4) NOT NULL,
  `study` varchar(64) NOT NULL,
  `interest` tinyint(4) NOT NULL,
  `relationship` tinyint(4) NOT NULL,
  `website` varchar(64) NOT NULL,
  `bio` varchar(200) NOT NULL,
  `posts` int(11) NOT NULL,
  `blogs` int(11) NOT NULL,
  `photos` int(11) NOT NULL,
  `pin` int(11) NOT NULL DEFAULT '0',
  `followers` int(11) NOT NULL,
  `group_feeds` blob NOT NULL,
  `page_feeds` blob NOT NULL,
  `r_posts_per_page` int(11) NOT NULL DEFAULT '5',
  `r_followers_per_page` int(11) NOT NULL DEFAULT '10',
  `r_followings_per_page` int(11) NOT NULL DEFAULT '10',
  `p_moderators` tinyint(10) NOT NULL DEFAULT '0',
  `n_per_page` int(100) NOT NULL DEFAULT '5',
  `n_accept` int(11) NOT NULL DEFAULT '0',
  `n_type` tinyint(10) NOT NULL DEFAULT '1',
  `n_follower` tinyint(10) NOT NULL DEFAULT '1',
  `n_like` tinyint(10) NOT NULL DEFAULT '1',
  `n_comment` tinyint(10) NOT NULL DEFAULT '1',
  `n_mention` tinyint(4) NOT NULL DEFAULT '1',
  `e_accept` tinyint(4) NOT NULL,
  `e_follower` tinyint(4) NOT NULL,
  `e_like` tinyint(4) NOT NULL,
  `e_comment` tinyint(4) NOT NULL,
  `e_mention` tinyint(4) NOT NULL,
  `p_posts` tinyint(10) NOT NULL DEFAULT '0',
  `p_followers` tinyint(10) NOT NULL DEFAULT '0',
  `p_followings` tinyint(10) NOT NULL DEFAULT '0',
  `p_profession` tinyint(10) NOT NULL DEFAULT '0',
  `p_hometown` tinyint(10) NOT NULL DEFAULT '0',
  `p_location` tinyint(10) NOT NULL DEFAULT '0',
  `p_image` tinyint(4) NOT NULL DEFAULT '0',
  `p_cover` tinyint(4) NOT NULL DEFAULT '0',
  `p_mention` tinyint(4) NOT NULL DEFAULT '0',
  `p_private` tinyint(10) NOT NULL DEFAULT '0',
  `p_study` tinyint(4) NOT NULL,
  `p_relationship` tinyint(4) NOT NULL,
  `p_interest` tinyint(4) NOT NULL,
  `p_gender` tinyint(4) NOT NULL,
  `p_bday` tinyint(4) NOT NULL,
  `p_web` tinyint(4) NOT NULL,
  `b_posts` tinyint(1) NOT NULL DEFAULT '0',
  `b_comments` tinyint(1) NOT NULL DEFAULT '0',
  `b_users` tinyint(1) NOT NULL DEFAULT '0',
  `state` tinyint(4) NOT NULL DEFAULT '2',
  `safe` tinyint(4) NOT NULL DEFAULT '0',
  `p_chn` int(11) NOT NULL,
  UNIQUE KEY `id` (`idu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.users: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

-- Дамп структуры для таблица test2.user_blocks
CREATE TABLE IF NOT EXISTS `user_blocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blocked_id` int(11) NOT NULL,
  `by_id` int(11) NOT NULL,
  `follow` int(11) NOT NULL DEFAULT '0',
  `chat` int(11) NOT NULL DEFAULT '0',
  `profile` int(11) NOT NULL DEFAULT '0',
  `page_invite` int(11) NOT NULL DEFAULT '0',
  `groups` int(11) NOT NULL DEFAULT '0',
  `search` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы test2.user_blocks: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `user_blocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_blocks` ENABLE KEYS */;

-- Дамп структуры для таблица test2.user_posts
CREATE TABLE IF NOT EXISTS `user_posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `post_by_id` int(11) NOT NULL,
  `post_content` text NOT NULL,
  `post_text` text NOT NULL,
  `post_tags` varchar(256) NOT NULL,
  `post_type` varchar(10) NOT NULL DEFAULT '0',
  `posted_as` int(11) NOT NULL DEFAULT '0',
  `posted_at` int(11) NOT NULL DEFAULT '0',
  `post_loves` int(11) NOT NULL DEFAULT '0',
  `post_views` int(11) NOT NULL DEFAULT '0',
  `post_comments` int(11) NOT NULL DEFAULT '0',
  `post_extras` varchar(200) NOT NULL DEFAULT '0,0,0',
  `link_title` varchar(250) NOT NULL,
  `link` varchar(250) NOT NULL,
  `link_description` varchar(250) NOT NULL,
  `link_img` varchar(250) NOT NULL,
  `post_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `edited` tinyint(4) NOT NULL DEFAULT '0',
  `safe` tinyint(4) NOT NULL DEFAULT '0',
  `post_deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы test2.user_posts: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `user_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_posts` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
