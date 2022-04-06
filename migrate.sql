alter table `notices` add `category` enum('notice', 'job') not null default 'notice';
alter table `colleges` add `type` enum('college', 'university') not null default 'college' after `name`;
alter table `college_streams` add `college_id` bigint unsigned null after `name`;
alter table `college_streams` add constraint `college_streams_college_id_foreign` foreign key (`college_id`) references `colleges` (`id`) on delete cascade;
alter table `college_streams` add index `college_streams_college_id_index`(`college_id`);
create table `programme_classes` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';
alter table `students` add `college_type` enum('college', 'university') null after `user_id`, add `programme_class_id` bigint unsigned null after `semester`, add `category_id` varchar(191) null after `programme_class_id`, add `honour_passcourse` enum('honours', 'pass_course') null after `programme_class_id`;
alter table `students` add constraint `students_programme_class_id_foreign` foreign key (`programme_class_id`) references `programme_classes` (`id`) on delete cascade;
alter table `students` add index `students_programme_class_id_index`(`programme_class_id`);
alter table `courses` add `college_type` enum('college', 'university') null after `category_id`, add `programme_class_id` bigint unsigned null after `semester`, add `honour_passcourse` enum('honours', 'pass_course') null after `programme_class_id`;
alter table `courses` add constraint `courses_programme_class_id_foreign` foreign key (`programme_class_id`) references `programme_classes` (`id`) on delete cascade;
alter table `courses` add index `courses_programme_class_id_index`(`programme_class_id`);