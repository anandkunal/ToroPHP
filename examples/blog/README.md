# Blog

A very simple blog written with Toro.

## Notes

- This example presents a way you can organize your Toro web application
- Articles have already been included in a SQL dump, comments can be added via the article interface
- Articles and comments are stored in a MySQL database - please see installation below
- Markdown is used for post and comment formatting


## Install

1. Copy this directory to your server/virtual host root
2. Grab a copy of `toro.php` from the repository and store in the `lib/` directory
3. Create a database called `toroblog` and execute the `toroblog.sql` dump
4. Adjust your MySQL database/user information in `lib/mysql.php`