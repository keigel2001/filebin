<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_multipaste extends CI_Migration {

	public function up()
	{
		if ($this->db->dbdriver == 'postgre')
		{
			$this->db->query('
				CREATE TABLE "multipaste" (
					"url_id" varchar(255) NOT NULL,
					"multipaste_id" serial,
					"user_id" integer NOT NULL,
					"date" integer NOT NULL,
					PRIMARY KEY ("url_id")
				);
				CREATE INDEX "multipaste_user_idx" ON "multipaste" ("user_id");
				CREATE UNIQUE INDEX "multipaste_id_idx" ON "multipaste" ("multipaste_id");
			');

			$this->db->query('
				CREATE TABLE "multipaste_file_map" (
					"multipaste_id" integer NOT NULL,
					"file_url_id" varchar(255) NOT NULL,
					"sort_order" serial,
					PRIMARY KEY ("sort_order")
				);
				CREATE UNIQUE INDEX "multipaste_file_map_idx"
					ON "multipaste_file_map" ("multipaste_id", "file_url_id");
				CREATE INDEX "multipaste_file_map_file_idx" ON "multipaste_file_map" ("file_url_id");
				ALTER TABLE "multipaste_file_map"
					ADD CONSTRAINT "multipaste_file_map_id_fkey" FOREIGN KEY ("multipaste_id")
						REFERENCES "multipaste"("multipaste_id") ON DELETE CASCADE ON UPDATE CASCADE;
				ALTER TABLE "multipaste_file_map"
					ADD CONSTRAINT "multipaste_file_map_file_id_fkey" FOREIGN KEY ("file_url_id")
						REFERENCES "files"("id") ON DELETE CASCADE ON UPDATE CASCADE
			');
		}
		else
		{
			$this->db->query('
			CREATE TABLE `multipaste` (
				`url_id` varchar(255) NOT NULL,
				`multipaste_id` int(11) NOT NULL AUTO_INCREMENT,
				`user_id` int(11) NOT NULL,
				`date` int(11) NOT NULL,
				PRIMARY KEY (`url_id`),
				UNIQUE KEY `multipaste_id` (`multipaste_id`),
				KEY `user_id` (`user_id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8;');

			$this->db->query('
			CREATE TABLE `multipaste_file_map` (
				`multipaste_id` int(11) NOT NULL,
				`file_url_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
				`sort_order` int(10) unsigned NOT NULL AUTO_INCREMENT,
				PRIMARY KEY (`sort_order`),
				UNIQUE KEY `multipaste_id` (`multipaste_id`,`file_url_id`),
				KEY `multipaste_file_map_ibfk_2` (`file_url_id`),
				CONSTRAINT `multipaste_file_map_ibfk_1` FOREIGN KEY (`multipaste_id`) REFERENCES `multipaste` (`multipaste_id`) ON DELETE CASCADE ON UPDATE CASCADE,
				CONSTRAINT `multipaste_file_map_ibfk_2` FOREIGN KEY (`file_url_id`) REFERENCES `files` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;');
		}
	}

	public function down()
	{
		show_error("downgrade not supported");
	}
}
