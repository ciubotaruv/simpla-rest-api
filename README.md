
#Requirements
PHP > 5.5
Simpla cms = any version
Configured timezone in php.ini, like: date.timezone ="Europe/Kiev"

#Description
REST api for Simpla CMS based on Laravel Framework

#Installation

Add to .htaccess file: RewriteRule ^rest/(.*)$ lib/RESTfull/v1/api/public/index.php [L,QSA]
Copy code to lib/RESTfull/
