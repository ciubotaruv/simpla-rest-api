#<b>Requirements</b> <br>
PHP     > <i>5.5</i> <br>
Simpla CMS = <i>any version</i> <br>
Configured timezone in php.ini, like: date.timezone ="Europe/Kiev"

#<b>Description</b> <br>
REST api for <a href="http://simplacms.ru/">Simpla CMS</a> based on Laravel Framework</a> <br>

#<b>Installation</b> <br>
Add to .htaccess file: RewriteRule ^rest/(.*)$ v1/api/public/index.php [L,QSA]
Copy code to lib/RESTfull/
