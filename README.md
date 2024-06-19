# website link
https://rizlogin.selfmade.one/

# SQL injection and XSS vulnerability prevented in user login
- SQL injection is prevented by implementing prepared statements to query the Database
- XSS is prevented by displaying data as html entities

# Additional Security measures
- Authorize the webpage everytime it reloads by checking parameters like user ip and user agent are not changed
- It is done to prevent the attacker from using the session by cookie hijacking