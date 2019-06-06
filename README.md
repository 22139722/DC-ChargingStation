# DC-ChargingStation-
Installation

Set up environnement

1.  Download EasyPHP and install
    https://www.easyphp.org/save-easyphp-devserver-latest.php

2.  Restart the computer

3.  Copy and paste the station_project folder to :
    windows: C:\Program Files (x86)\EasyPHP-Devserver-17\eds-www

4.  Run EasyPHP

5.  In the icons of the taskbar at the bottom right. Click with the left button on the EasyPHP icon

6.  In the menu, click on "Open dashboard"

7.  Start HTTP SERVER

8.  Download MysSQL installer and run it : https://dev.mysql.com/get/Downloads/MySQLInstaller/mysql-installer-community-8.0.15.0.msi

    Make sure that MySQL Workbench is in installed feature
    For the credentials at the end of the setup, choose :
    Username: root
    Password: root

9.  Open MySQL Workbench by searching in the windows Menu (seach key: Workbench)

8.  Open Local instance and set the user and the password

9.  Copy the sql script from files in charging_db folder (charging_db_chargings and charging_db_users) and paste them in the sql tab, and execute the script (by clicking in the execute button - yellow lightning button)

10. In the EasyPHP dashboard, click on "Portable Directory", and click on station_project
