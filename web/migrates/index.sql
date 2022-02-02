CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `phoneNumber` string UNIQUE,
  `firstName` string,
  `lastName` string,
  `password` varchar(255),
  `lastLoginAt` int,
  `createdAt` int,
  `updatedAt` int
);

CREATE TABLE `roles` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` string,
  `code` string,
  `createdAt` int,
  `updatedAt` int
);

CREATE TABLE `files` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` string,
  `url` string,
  `path` string,
  `mime` string,
  `size` string,
  `createdAt` int,
  `updatedAt` int
);

CREATE TABLE `users_photos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `userId` int,
  `photoId` int
);

CREATE TABLE `users_roles` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `roleId` int,
  `userId` int
);

CREATE TABLE `users_push_tokens` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `pushToken` string,
  `userId` int
);

CREATE TABLE `users_refresh` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `userId` int,
  `refreshToken` string,
  `expiredAt` int
);

ALTER TABLE `users_photos` ADD FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

ALTER TABLE `users_photos` ADD FOREIGN KEY (`photoId`) REFERENCES `files` (`id`);

ALTER TABLE `users_roles` ADD FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

ALTER TABLE `users_roles` ADD FOREIGN KEY (`roleId`) REFERENCES `roles` (`id`);

ALTER TABLE `users_push_tokens` ADD FOREIGN KEY (`userId`) REFERENCES `users` (`id`);

ALTER TABLE `users_refresh` ADD FOREIGN KEY (`userId`) REFERENCES `users` (`id`);
