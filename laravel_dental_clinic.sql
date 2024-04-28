/*
SQLyog Community v13.2.0 (64 bit)
MySQL - 8.0.33 : Database - laravel_dental_clinic
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`laravel_dental_clinic` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `laravel_dental_clinic`;

/*Table structure for table `appointments` */

DROP TABLE IF EXISTS `appointments`;

CREATE TABLE `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned DEFAULT NULL,
  `service_id` bigint unsigned NOT NULL,
  `status` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT 'Pending',
  `name` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `details` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_patient_id_foreign` (`patient_id`),
  KEY `appointments_service_id_foreign` (`service_id`),
  CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  CONSTRAINT `appointments_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `appointments` */

insert  into `appointments`(`id`,`patient_id`,`service_id`,`status`,`name`,`email`,`contact`,`start`,`end`,`details`,`created_at`,`updated_at`,`deleted_at`) values 
(2,NULL,1,'No Show','Jhaymark Jhon Palacios Lopez','jhaymark@gmail.com','09653659636','2023-10-06 13:00:00','2023-10-06 14:00:00','Tooth Extraction','2023-09-30 23:58:18','2023-10-01 00:00:33','2023-10-01 00:00:33'),
(3,NULL,1,'Scheduled','Joshua Arosco','aroscojoshua@gmail.com','09463978618','2023-10-03 13:00:00','2023-10-03 14:00:00','Tooth Extraction','2023-10-01 00:02:44','2023-10-01 00:02:44',NULL),
(4,1,1,'Scheduled',NULL,NULL,NULL,'2023-10-02 13:00:00','2023-10-02 14:00:00','Please paki schedule ako ng October 2','2023-10-01 00:08:37','2023-10-01 00:13:04',NULL),
(5,NULL,1,'Scheduled','Jhaymark Jhon Palacios Lopez','jhaymark@gmail.com','09653659636','2023-10-06 13:00:00','2023-10-06 14:00:00','Tooth Extraction','2023-10-01 00:18:18','2023-10-01 00:18:18',NULL);

/*Table structure for table `availed_services` */

DROP TABLE IF EXISTS `availed_services`;

CREATE TABLE `availed_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `record_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned DEFAULT NULL,
  `date` date DEFAULT NULL,
  `next_procedure` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `payment` double(8,2) DEFAULT NULL,
  `balance` double(8,2) DEFAULT NULL,
  `doctor` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `availed_services_record_id_foreign` (`record_id`),
  KEY `availed_services_service_id_foreign` (`service_id`),
  CONSTRAINT `availed_services_record_id_foreign` FOREIGN KEY (`record_id`) REFERENCES `treatment_records` (`id`),
  CONSTRAINT `availed_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `availed_services` */

insert  into `availed_services`(`id`,`record_id`,`service_id`,`date`,`next_procedure`,`payment`,`balance`,`doctor`,`created_at`,`updated_at`,`deleted_at`) values 
(1,4,1,'2023-09-25','Sukat Pustiso',1500.00,500.00,'Dr. Rizal','2023-09-25 02:12:58','2023-09-25 02:12:58',NULL),
(2,4,1,'2023-09-26','Kabit Pustiso',2000.00,100.00,'Dr. Calayan','2023-09-25 02:12:58','2023-09-25 02:21:49',NULL),
(3,4,NULL,NULL,NULL,NULL,NULL,NULL,'2023-09-25 02:12:58','2023-09-25 02:12:58',NULL),
(4,4,1,NULL,NULL,NULL,NULL,NULL,'2023-09-25 02:12:58','2023-09-25 02:22:20',NULL),
(5,4,NULL,NULL,NULL,NULL,NULL,NULL,'2023-09-25 02:12:58','2023-09-25 02:12:58',NULL),
(6,4,NULL,NULL,NULL,NULL,NULL,NULL,'2023-09-25 02:12:58','2023-09-25 02:12:58',NULL),
(7,4,NULL,NULL,NULL,NULL,NULL,NULL,'2023-09-25 02:12:58','2023-09-25 02:12:58',NULL),
(8,4,NULL,NULL,NULL,NULL,NULL,NULL,'2023-09-25 02:12:58','2023-09-25 02:12:58',NULL),
(9,4,NULL,'2023-09-27','Next Procedure',1300.00,200.00,'Dr. Belo','2023-09-25 02:12:58','2023-09-25 02:22:20',NULL),
(10,4,NULL,NULL,NULL,NULL,NULL,NULL,'2023-09-25 02:12:58','2023-09-25 02:12:58',NULL);

/*Table structure for table `faqs` */

DROP TABLE IF EXISTS `faqs`;

CREATE TABLE `faqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sequence` int DEFAULT NULL,
  `question` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `answer` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `faqs` */

insert  into `faqs`(`id`,`sequence`,`question`,`answer`,`created_at`,`updated_at`,`deleted_at`) values 
(1,1,'How often should I visit the dental clinic for check-ups?','It is recommended to visit your dentist every six months for regular check-ups and cleanings. However, the dentist may suggest more frequent visits if you have specific dental issues.','2023-09-29 10:24:45','2023-09-29 10:24:45',NULL),
(2,2,'What are some essential oral hygiene practices for maintaining healthy teeth and gums?','Proper oral hygiene includes brushing your teeth at least twice a day with fluoride toothpaste, flossing daily, and using an antimicrobial mouthwash. It\'s also crucial to maintain a balanced diet and limit sugary snacks and beverages.','2023-09-29 10:25:25','2023-09-30 05:10:19',NULL),
(3,3,'Is it necessary to use a specific type of toothbrush or toothpaste?','It\'s best to use a soft-bristle toothbrush to avoid damaging your gums and tooth enamel. Fluoride toothpaste is recommended for most people to help prevent tooth decay. However, your dentist may recommend specialized products based on your individual needs.','2023-09-29 10:36:29','2023-09-29 10:48:04',NULL),
(4,4,'How can I prevent gum disease?','To prevent gum disease, it\'s essential to practice good oral hygiene, which includes regular brushing, flossing, and professional cleanings. Avoiding tobacco products, managing stress, and maintaining a balanced diet can also help prevent gum disease.','2023-09-29 14:19:03','2023-09-29 14:19:03',NULL),
(5,5,'What should I do if I have bad breath?','Bad breath can be caused by various factors, including poor oral hygiene, dry mouth, or underlying dental issues. Maintaining good oral hygiene, staying hydrated, and avoiding tobacco and strong-smelling foods can help. If the problem persists, consult your dentist for a thorough evaluation.','2023-09-29 14:19:30','2023-09-29 14:19:30',NULL),
(6,6,'How can I manage tooth sensitivity?','Tooth sensitivity can result from various factors, including enamel erosion or gum recession. Using toothpaste designed for sensitive teeth and avoiding extreme temperatures in food and drinks can help. Consult your dentist for personalized advice.','2023-09-29 14:19:56','2023-09-29 14:19:56',NULL),
(7,7,'Are there specific oral hygiene tips for children and seniors?','Yes, both children and seniors have unique oral care needs. Children should start dental visits early, avoid excessive sugar intake, and use age-appropriate toothbrushes. Seniors may need to address issues like dry mouth and denture care. Consult with your dentist for age-specific guidance.','2023-09-29 14:20:24','2023-09-29 14:20:24',NULL),
(8,8,'What should I do in case of a dental emergency?','Dental emergencies, such as a broken tooth or severe toothache, require prompt attention. Contact your dentist immediately or seek emergency dental care. In the meantime, you can rinse your mouth with warm water and use over-the-counter pain relief if needed.','2023-09-29 14:20:52','2023-09-29 14:20:52',NULL),
(9,9,'Is it safe to use over-the-counter teeth whitening products?','While over-the-counter whitening products are generally safe when used as directed, it\'s advisable to consult your dentist before starting any teeth whitening regimen. They can recommend the most appropriate approach based on your dental health.','2023-09-29 14:21:20','2023-09-29 14:21:20',NULL),
(10,10,'How can I schedule an appointment at the dental clinic?','You can schedule an appointment by calling the dental clinic directly or using our online appointment booking system. Our dental clinic also accept walk-in patients, but it\'s best to call ahead or book online to ensure availability.','2023-09-29 14:21:44','2023-09-29 14:21:44',NULL);

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_resets_table',1),
(3,'2023_04_16_160402_create_patients_table',1),
(4,'2023_04_18_181731_create_services_table',1),
(9,'2023_04_21_151414_create_health_records_table',2),
(11,'2023_04_21_204225_create_availed_services_table',3),
(12,'2023_09_29_091057_create_faqs_table',4),
(13,'2023_04_22_170628_create_appointments_table',5);

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `token_expired_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `patients` */

DROP TABLE IF EXISTS `patients`;

CREATE TABLE `patients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `fname` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `mname` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `lname` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `birthdate` date DEFAULT NULL,
  `gender` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `path` text COLLATE utf8mb3_unicode_ci,
  `directory` text COLLATE utf8mb3_unicode_ci,
  `filename` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `patients_user_id_foreign` (`user_id`),
  CONSTRAINT `patients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `patients` */

insert  into `patients`(`id`,`user_id`,`fname`,`mname`,`lname`,`address`,`birthdate`,`gender`,`path`,`directory`,`filename`,`created_at`,`updated_at`,`deleted_at`) values 
(1,2,'Sample',NULL,'Patient','Lomboy Binmaley',NULL,NULL,NULL,NULL,NULL,'2023-09-22 15:09:39','2023-09-22 15:09:39',NULL),
(2,3,'Sample',NULL,'Patient','Lomboy Binmaley',NULL,NULL,NULL,NULL,NULL,'2023-09-22 15:10:19','2023-09-22 15:10:19',NULL),
(3,4,'Sample','Patient','Number 3','Lomboy Binmaley','1996-05-14','male',NULL,NULL,NULL,'2023-09-22 15:23:04','2023-09-22 15:23:04',NULL);

/*Table structure for table `services` */

DROP TABLE IF EXISTS `services`;

CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `price` double(8,2) DEFAULT NULL,
  `description` text COLLATE utf8mb3_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `services` */

insert  into `services`(`id`,`name`,`type`,`price`,`description`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Pasta Taas Baba',NULL,NULL,'Pasta whole set of teeth','2023-09-22 15:29:10','2023-09-22 15:29:10',NULL);

/*Table structure for table `treatment_records` */

DROP TABLE IF EXISTS `treatment_records`;

CREATE TABLE `treatment_records` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `patient_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned DEFAULT NULL,
  `school_office` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `diagnosis` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `plan_summary` text COLLATE utf8mb3_unicode_ci,
  `panoramic` tinyint(1) NOT NULL DEFAULT '0',
  `photo` tinyint(1) NOT NULL DEFAULT '0',
  `ceph` tinyint(1) NOT NULL DEFAULT '0',
  `cast` tinyint(1) NOT NULL DEFAULT '0',
  `tmj` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `treatment_records_patient_id_foreign` (`patient_id`),
  CONSTRAINT `treatment_records_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `treatment_records` */

insert  into `treatment_records`(`id`,`patient_id`,`service_id`,`school_office`,`diagnosis`,`plan_summary`,`panoramic`,`photo`,`ceph`,`cast`,`tmj`,`created_at`,`updated_at`,`deleted_at`) values 
(4,3,NULL,'PSU Lingayen','Deep Cavity','Sample Treatment Plan',1,0,0,0,1,'2023-09-22 18:09:21','2023-09-25 02:22:20',NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `contact_number` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`username`,`type`,`email`,`contact_number`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`,`deleted_at`) values 
(1,'Super User','admin','super_user','admin@projectx.com',NULL,'2023-09-21 14:05:08','$2y$10$pcNiCQTkfkeg0A03PySDxe1FeYftWEYUM9A4dAd8TSGf2WDCLSQhm',NULL,'2023-09-21 00:00:00','2023-09-21 00:00:00',NULL),
(2,'Sample Patient','sample.patient','patient','samplepatient@gmail.com','(0946)397-8618',NULL,'$2y$10$pcNiCQTkfkeg0A03PySDxe1FeYftWEYUM9A4dAd8TSGf2WDCLSQhm',NULL,'2023-09-22 15:09:39','2023-09-22 15:09:39',NULL),
(3,'Sample Patient','sample.patient2','patient','samplepatient2@gmail.com','(0946)397-8618',NULL,'$2y$10$lZf1FTyIAI0.Go89GDVL8O.DwO1stJuCvjwDybGJivTBDakU1q.J2',NULL,'2023-09-22 15:10:19','2023-09-22 15:10:19',NULL),
(4,'Sample Number 3','sample.patient3','patient','samplepatient3@gmail.com','(0946)397-8618',NULL,'$2y$10$d3bus.3Bgem.ZDTd7q54nuRZru.Y.OZ8tW3Osj3BXF6JOWFWfbXvW',NULL,'2023-09-22 15:23:04','2023-09-22 15:23:04',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
