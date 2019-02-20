# Marginalia

Marginalia is a simple markdown note taking application written using a mix of php, ajax, html, css, and javascript. It runs on an Apache web server, and uses a mysql database for storage.
 
**Page Example (with formatting)**  
![](https://raw.githubusercontent.com/Silversunset01/Marginalia/master/Screenshots/Marginalia.PNG)  

**New Page (example)**  
![](https://raw.githubusercontent.com/Silversunset01/Marginalia/master/Screenshots/Marginalia-new.PNG)  

**Edit Page (example - with preview pane visible)**  
![](https://raw.githubusercontent.com/Silversunset01/Marginalia/master/Screenshots/Marginalia-edit.PNG)  

# Database Info
The file requires two tables

### **table name: Notes**  

Name | Type | Collation | Attributes | Null | Default | Extra
:-|:-|:-|:-|:-|:-|:-
ID | int(11) | | | No | None | AUTO_INCREMENT
owner | varchar(50) | utf8_general_ci | | No | None | 
notebook | varchar(50) | utf8_general_ci | | Yes| None | 
Title | varchar(50) | utf8_general_ci | | Yes | None | 
lastUpdated | timestamp |  | | No | CURRENT_TIMESTAMP| 
MDText | longtext | utf8mb4_general_ci | | Yes| None | 
HTMLText | longtext | utf8mb4_general_ci | | Yes | None | 
Tag | varchar(50) | utf8_general_ci | | Yes | None | 
Trash | int(11) | | | No | 0| 

<br/>

### **table name: Users**  

Name | Type | Collation | Attributes | Null | Default | Extra
:-|:-|:-|:-|:-|:-|:-
ID | int(11) | | | No | None | AUTO_INCREMENT
username | varchar(50) | utf8_general_ci | | No | None | 
password | varchar(255) | utf8_general_ci | | Yes| None | 
createdAt | timestamp |  | | No | CURRENT_TIMESTAMP | 
styleOption | varchar(50) | utf8_general_ci | No | style.css

# Instructions
In the future better installation instructions will be written.   
For now the general instructions are:  
1. copy the `Marginalia` folder to your domain
2. replicate the two tables referenced above
3. assign a user with privileges to `SELECT,INSERT,UPDATE,DELETE,CREATE,INDEX,ALTER,CREATE TEMPORARY TABLES,CREATE VIEW,EVENT,TRIGGER,SHOW VIEW,CREATE ROUTINE,ALTER ROUTINE,EXECUTE`
4. rename `template.config.php` to `config.php` and update the values in the file with the correct information for your database
5. navigate to yourdomain.com/marginalia/register.php and create a user account 
    * when this is done you will want to edit the `register.php` file to UN-COMMENT lines 3-7 (remove the // on the front of each line) - this will prevent others from creating an account 
	
# Note
This is a heavy WIP project, mostly for me to learn the languages it uses. There is going to be code that is not pretty or clean, and there is probably some leftover commenting or files that i forgot to remove. I have been using this program live for four+ months, and it does work so any coding weirdness is fine and will be worked out in subsequent updates.