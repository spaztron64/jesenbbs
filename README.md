# JesenBBS

JesenBBS is the software powering the AkiChannel message board. It is a fully server-side PHP 8.0 + MySQL/MariaDB web application written to provide end users the ability to host a message board akin to 5ch and Futaba Channel, with a spice of dial-up (PC communication era) BBS permission levels so as to allow more precise permission control of both unregistered (anonymous/guest) and registered users. Initially written as a graduation project, it has now been made available as open-source.

The program has the following features:
- Localization support
- Account registration and login
- A level-based permission system, ranging from 0 to 9
- Interactive creation, editing, and deletion of boards/categories and users
- Posting messages with support for up to 4 attached images and/or compressed archive files
- Simple and clean UI design that ensures a decent user experience, as well as an efficient use of bandwidth
- Board whitelisting for registered users, so as to let the user choose which boards they want to see on the left-side list
- Compatibility with a wide range of web browsers (Confirmed fully usable with text-based browsers like Lynx, and even ancient browsers like IE 5.01 and NetScape 4)

## Setup

First ensure that you have the following:
- PHP 8.0 (later versions have not been thoroughly tested yet, try at your own discretion)
- MariaDB 10.4 or later
- A web server that supports .htaccess rules, like Apache HTTP Server

Once you have the prerequisites met, put the program into your desired directory. By default, it is configured for installation into a directory named "akichannel", so if you use a different directory, you'll need to change the AKICH_ROOT define in includes.php, as well as the .htaccess rules.

You'll then want to set up the database. Set the necessary parameters in config/config.php, create the appropriate database, and import the akichannel_db.sql file for all the necessary tables. If all goes well, you should now be able to visit your JesenBBS instance.

Since a first-time installer hasn't been created yet, you'll need to manually register your admin user. First, register your account through the application's registration page. You will likely get an SMTP related error, which you can safely ignore. Log into your database's shell, and for the "user" table NULL out the "user_parameters" column of your admin user, and set it's "user_permission_level" to 9. You should now be able to log into your admin user and access the superuser menu for further administration.

## Known issues

Please note that I do not consider this software fully complete, let alone production ready. The following things need further work:
- Tightening of security
- Complete localization of all strings and streamlining of the localization process
- Query optimization
- Locking down the superuser menu (you might wanna use .htpasswd for now)
- Invalidating page cache where necessary
- Creating a telnet/SSH text-based frontend
- Repaying tech debt... lots and lots of tech debt
- and lots more
