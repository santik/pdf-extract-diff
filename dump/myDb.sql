SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


CREATE TABLE `twitter` (
                          `id` varchar(125) NOT NULL,
                          `name` varchar(100) NOT NULL,
                          `screen_name` varchar(100) NOT NULL,
                          `location` varchar(255) NOT NULL,
                          `description` text NOT NULL,
                          `url` varchar(255) NOT NULL,
                          `followers_count` int(10) NOT NULL,
                          `friends_count` int(10) NOT NULL,
                          `listed_count` int(10) NOT NULL,
                          `created_at` text NOT NULL,
                          `favourites_count` int(10) NOT NULL,
                          `statuses_count` int(10) NOT NULL,
                           `verified` varchar(255) NOT NULL,
                            `last_active` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;