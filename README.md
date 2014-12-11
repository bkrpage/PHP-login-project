php-Login-System
================

Second year Web Development assignement - a simple login page with control panel. Using PHP to 
contact the MySQLi database(s), structure as below:

Table: users
+-------------+---------------+----------+-----+
|    Name     |    Datatpe    | Nullable | PK  |
+-------------+---------------+----------+-----+
| u_email     | varchar(255)  | NO       | YES |
| u_password  | varchar(255)  | NO       | NO  |
+-------------+---------------+----------+-----+

Table: user_details
+-------------+--------------+----------+-----+---------------+
|    Name     |   Datatpe    | Nullable | PK  |      FK       |
+-------------+--------------+----------+-----+---------------+
| u_email     | varchar(255) | NO       | YES | users.u_email |
| u_firstname | text         | NO       | NO  | NO            |
| u_surname   | text         | NO       | NO  | NO            |
| u_address1  | varchar(255) | NO       | NO  | NO            |
| u_address2  | text         | YES      | NO  | NO            |
| u_address3  | text         | YES      | NO  | NO            |
| u_post_code | varchar(255) | NO       | NO  | NO            |
| u_country   | text         | NO       | NO  | NO            |
| u_phone     | varchar(255) | NO       | NO  | NO            |
| u_sec_q     | varchar(255) | NO       | NO  | NO            |
| u_sec_a     | varchar(255) | NO       | NO  | NO            |
+-------------+--------------+----------+-----+---------------+

These are not true exported MySQL tables, however when i get access to the database, I will export them to plain text
