# youtube-to-quora

Migration :

```sql
-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  mar. 29 déc. 2020 à 14:48
-- Version du serveur :  5.7.17
-- Version de PHP :  5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `channel-storage`
--

-- --------------------------------------------------------

--
-- Structure de la table `quora_account`
--

CREATE TABLE `quora_account` (
  `id` int(11) NOT NULL,
  `api_url` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `api_token` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `quora_account_youtube_channel`
--

CREATE TABLE `quora_account_youtube_channel` (
  `id` int(11) NOT NULL,
  `quora_id` int(11) NOT NULL,
  `youtube_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `quora_post`
--

CREATE TABLE `quora_post` (
  `id` int(11) NOT NULL,
  `account_id` INT NOT NULL,
  `quora_id` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `quora_post_youtube_video`
--

CREATE TABLE `quora_post_youtube_video` (
  `id` int(11) NOT NULL,
  `quora_id` int(11) NOT NULL,
  `youtube_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


--
-- Structure de la table `youtube_video_unpostable_on_quora`
--

CREATE TABLE `youtube_video_unpostable_on_quora` (
  `id` int(11) NOT NULL,
  `youtube_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `quora_account`
--
ALTER TABLE `quora_account`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `quora_account_youtube_channel`
--
ALTER TABLE `quora_account_youtube_channel`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `quora_post`
--
ALTER TABLE `quora_post`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `quora_post_youtube_video`
--
ALTER TABLE `quora_post_youtube_video`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `youtube_video_unpostable_on_quora`
--
ALTER TABLE `youtube_video_unpostable_on_quora`
  ADD PRIMARY KEY (`id`);


--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `quora_account`
--
ALTER TABLE `quora_account`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `quora_account_youtube_channel`
--
ALTER TABLE `quora_account_youtube_channel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `quora_post`
--
ALTER TABLE `quora_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `quora_post_youtube_video`
--
ALTER TABLE `quora_post_youtube_video`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `youtube_video_unpostable_on_quora`
--
ALTER TABLE `youtube_video_unpostable_on_quora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

```
