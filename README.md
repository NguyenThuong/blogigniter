blogigniter
===========

A simple blog with Codeigniter 2 & MySQL Made with <3 from Paris, France.

/!\ Warning it is a beta version /!\


How to use ?

1) Import "blogigniter.sql" file in a database called "blogigniter".

2) Make sure you can connect to your MySQL database in the file :
application/config/database.php (lines 51, 52 & 53)

3) Unzip blogigniter in your www folder
Acces to blogigniter front from : 
http://127.0.0.1/blogigniter or http://localhost/blogigniter

4) For using the backoffice, create in the table "user" a new profil user :
- login ("u_login")
- password ("u_password") in md5 encode
- level ("u_level") to "1" (admin level)
Try to connect to your administration : http://localhost/blogigniter/admin


More informations about Codeigniter : http://ellislab.com/codeigniter/user-guide