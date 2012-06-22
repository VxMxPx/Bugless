Bugless by Avrelia
=================

An open source bug tracker.

Server Configuration
--------------------
Only `profile/public` directory should be accessible through URL. If you'll change default folder's names or order,
then update `bugless/config/define.php` file and/or `profile/public/index.php`.

**Important!**
In directory `profile` create folders `database` and `log` and make sure they're writable.

Installation
------------
After you downloaded project and configure project on your server, you need to run `http://your-domain/install`,
after installation is finished the log will be displayed. Check for any errors or warnings (displayed in orange or red).
If everything went find, you can just refresh the page and you'll be redirected to dashboard.

First Login
-----------
After installation the default root user is created, to login use following information:
- username: **root@localhost**
- password: **root**

**Don't forget to change your default username and password.**

Build
-----
If you'll edit raw css or javaScript (in Bugless directory), you'll have to build those files. 
To build them, run script `./.build` from Bugless root directory.
You need to have node.js (npm) installed, and following components available:
- stylus
- uglify-js

The mentioned script will keep runing and checking files for any changes, until you terminate it.