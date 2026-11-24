-- Hardened & Normalized MySQL/MariaDB Schema for Owner-Only Portfolio CMS
-- Compatible with MySQL 8.0+ and MariaDB 10.4+ (XAMPP / Laragon / Cloud MySQL)

CREATE DATABASE IF NOT EXISTS `my_portfolio`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `my_portfolio`;

CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(160) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_admins_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `profile` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` INT UNSIGNED NOT NULL,
  `full_name` VARCHAR(120) NOT NULL,
  `first_name` VARCHAR(80) NOT NULL,
  `nickname` VARCHAR(40) NOT NULL,
  `role` VARCHAR(160) NOT NULL,
  `status` VARCHAR(160) NOT NULL,
  `location` VARCHAR(120) NOT NULL,
  `email` VARCHAR(160) NOT NULL,
  `university` VARCHAR(160) NOT NULL,
  `degree` VARCHAR(160) NOT NULL,
  `graduation` VARCHAR(80) NOT NULL,
  `headline_summary` TEXT NOT NULL,
  `about_bio_1` TEXT NOT NULL,
  `about_bio_2` TEXT NOT NULL,
  `github_url` VARCHAR(255) NOT NULL DEFAULT '',
  `linkedin_url` VARCHAR(255) NOT NULL DEFAULT '',
  `facebook_url` VARCHAR(255) NOT NULL DEFAULT '',
  `portrait_url` VARCHAR(255) NOT NULL DEFAULT 'images/rei-portrait.jpg',
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_profile_admin` (`admin_id`),
  CONSTRAINT `fk_profile_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `skills` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` INT UNSIGNED NOT NULL,
  `category` ENUM('languages', 'tools', 'systems') NOT NULL DEFAULT 'languages',
  `name` VARCHAR(100) NOT NULL,
  `level` ENUM('Proficient', 'Comfortable', 'Foundational') NOT NULL DEFAULT 'Comfortable',
  `percent` TINYINT UNSIGNED NOT NULL DEFAULT 70,
  `note` VARCHAR(255) NOT NULL DEFAULT '',
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_skills_admin_name` (`admin_id`, `name`),
  KEY `idx_skills_category` (`admin_id`, `category`, `sort_order`),
  CONSTRAINT `fk_skills_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` INT UNSIGNED NOT NULL,
  `slug` VARCHAR(120) NOT NULL,
  `title` VARCHAR(140) NOT NULL,
  `subtitle` VARCHAR(180) NOT NULL DEFAULT '',
  `category` ENUM('Web App', 'Full-Stack', 'UI & Frontend') NOT NULL DEFAULT 'Web App',
  `status` ENUM('Live', 'Completed', 'In Progress') NOT NULL DEFAULT 'Completed',
  `featured` TINYINT(1) NOT NULL DEFAULT 0,
  `year` VARCHAR(24) NOT NULL DEFAULT '2025',
  `role` VARCHAR(120) NOT NULL DEFAULT 'Developer',
  `summary` TEXT NOT NULL,
  `highlights_json` TEXT NOT NULL,
  `tech_json` TEXT NOT NULL,
  `visual_type` ENUM('windsurf', 'cms', 'weather', 'calculator') NOT NULL DEFAULT 'windsurf',
  `image_url` VARCHAR(255) DEFAULT NULL,
  `live_url` VARCHAR(255) DEFAULT NULL,
  `repo_url` VARCHAR(255) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_projects_admin_slug` (`admin_id`, `slug`),
  KEY `idx_projects_sort` (`admin_id`, `sort_order`),
  CONSTRAINT `fk_projects_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `inquiries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(120) NOT NULL,
  `email` VARCHAR(160) NOT NULL,
  `subject` VARCHAR(160) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_inquiries_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `admin_id` INT UNSIGNED NOT NULL,
  `action` VARCHAR(180) NOT NULL,
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_logs_admin_created` (`admin_id`, `created_at`),
  CONSTRAINT `fk_logs_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
