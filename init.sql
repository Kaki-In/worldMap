-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : jeu. 09 juin 2022 à 11:25
-- Version du serveur : 8.0.29
-- Version de PHP : 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `worldMap`
--

-- --------------------------------------------------------

--
-- Structure de la table `Centre`
--

CREATE TABLE `Centre` (
  `id` int DEFAULT '0',
  `lon` double NOT NULL,
  `lat` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Points`
--

CREATE TABLE `Points` (
  `id` int NOT NULL,
  `longitude` double NOT NULL,
  `latitude` double NOT NULL,
  `name` varchar(1024) DEFAULT NULL,
  `type` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `Types`
--

CREATE TABLE `Types` (
  `id` int NOT NULL,
  `nom` varchar(32) NOT NULL,
  `couleur` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Centre`
--
ALTER TABLE `Centre`
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `Points`
--
ALTER TABLE `Points`
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `Types`
--
ALTER TABLE `Types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Points`
--
ALTER TABLE `Points`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Types`
--
ALTER TABLE `Types`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

