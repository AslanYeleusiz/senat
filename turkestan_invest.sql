-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Май 14 2025 г., 13:08
-- Версия сервера: 10.5.17-MariaDB
-- Версия PHP: 8.0.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `turkestan_invest`
--

-- --------------------------------------------------------

--
-- Структура таблицы `audans`
--

CREATE TABLE `audans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `audans`
--

INSERT INTO `audans` (`id`, `name`) VALUES
(2, 'Областной акимат'),
(3, 'Түркістан'),
(4, 'Түлкібас');

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2025_05_03_205326_create_roles_table', 1),
(2, '2025_05_03_205317_create_audans_table', 2),
(8, '2025_05_03_205126_create_users_table', 3),
(15, '2019_12_14_000001_create_personal_access_tokens_table', 5),
(16, '2025_05_13_111633_create_project_types_table', 5),
(17, '2025_05_04_175214_create_projects_table', 6);

-- --------------------------------------------------------

--
-- Структура таблицы `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `projects`
--

CREATE TABLE `projects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `audan_id` bigint(20) UNSIGNED NOT NULL,
  `project_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `invest_sum` int(11) NOT NULL,
  `people_count` int(11) NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `curator` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `images` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`images`)),
  `is_archived` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `projects`
--

INSERT INTO `projects` (`id`, `name`, `description`, `country`, `audan_id`, `project_type`, `invest_sum`, `people_count`, `phone`, `curator`, `file`, `images`, `is_archived`, `created_at`, `updated_at`) VALUES
(2, 'Балаларға арналған саябақ', 'Балаларға арналған саябақ — бұл балалардың қауіпсіз әрі көңілді уақыт өткізуіне арналған арнайы жабдықталған ашық кеңістік. Мұндай саябақтарда әртүрлі жас топтарына лайықталған ойын алаңдары, әткеншектер, сырғанақтар, құмсалғыштар және қозғалыс белсенділігін дамытатын тренажерлер болады. Сонымен қатар, заманауи саябақтарда жұмсақ қауіпсіз жабын, көлеңке түсіретін шатырлар, орындықтар мен жарық шамдары орнатылады. Кейбір саябақтарда балалармен бірге ата-аналар да демала алатын жайлы аймақтар қарастырылады.  Саябақтың басты мақсаты — балалардың физикалық дамуын, қиялын, әлеуметтік қарым-қатынасын қолдау және табиғатпен байланысты арттыру. Қалалық ортада мұндай орындар — отбасылар үшін өте маңызды. Балалар таза ауада уақыт өткізіп, құрдастарымен ойнап, жаңа дағдыларды меңгереді.  Саябақ жобасы дайындалған кезде қауіпсіздік стандарттары, экологиялық талаптар, сондай-ақ мүгедек балалардың қажеттіліктері де ескерілуі тиіс. Осындай инклюзивті саябақтар — барша балаға тең мүмкіндік береді. Сонымен қатар, бұл — тұрғындардың өмір сүру сапасын арттыруға бағытталған қоғамдық маңызы бар бастама.', 'Қазақстан', 3, '1', 12000000, 30, '+7(775) 549-89-11', 'Абдуманап Нурасыл', '1747126038_2bA6e.doc', '[\"6699639_w600_h600_6699639.webp\",\"1747125328_XRE4F.jpg\",\"1747125328_3R5Ba.png\"]', 0, '2025-05-13 11:35:28', '2025-05-13 11:47:18');

-- --------------------------------------------------------

--
-- Структура таблицы `project_types`
--

CREATE TABLE `project_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `project_types`
--

INSERT INTO `project_types` (`id`, `name`) VALUES
(1, 'GreenField'),
(2, 'Локализация'),
(3, 'Расширение');

-- --------------------------------------------------------

--
-- Структура таблицы `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `privilege` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `roles`
--

INSERT INTO `roles` (`id`, `name`, `privilege`) VALUES
(1, 'Супер администратор', 1),
(2, 'Turkistan Invest', 1),
(3, 'Районный акимат', 1),
(4, 'Аким / зам акима', 1),
(5, 'Администратор IT', 1),
(6, 'Инвестор', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fio` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `iin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `real_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `audan_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `job_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `autobiography_link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_investor` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `fio`, `phone`, `mail`, `iin`, `password`, `real_password`, `audan_id`, `role_id`, `job_title`, `autobiography_link`, `is_investor`, `created_at`, `updated_at`) VALUES
(1, 'Аслан Елеусіз Асанұлы', '+7(775) 549-89-11', 'askon039@gmail.com', '020914500302', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'зфыыцщкв', 3, 5, 'ИТ специалист', NULL, 0, NULL, NULL),
(2, 'ТОО Qazybek Qoishygara', '+7(775) 549-89-12', 'askon039@gmail.com', '020914500303', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'password', 3, 6, 'Сениор', NULL, 0, NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `audans`
--
ALTER TABLE `audans`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Индексы таблицы `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `project_types`
--
ALTER TABLE `project_types`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `audans`
--
ALTER TABLE `audans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `projects`
--
ALTER TABLE `projects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `project_types`
--
ALTER TABLE `project_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
