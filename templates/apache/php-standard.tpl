<VirtualHost *:80>

    ServerName {{server_name}}
    DocumentRoot /mooti/repositories/{{repository_path}}/public

    <Directory /mooti/repositories/{{repository_path}}/public>
        Require all granted

        Options Indexes FollowSymlinks
        AllowOverride None

        RewriteEngine On
        RewriteCond %{REQUEST_FILENAME} !-f
        RewriteRule ^(.*)$ {{document_root}} [QSA,L]
    </Directory>

    LogLevel info
    ErrorLog /var/log/apache2/{{server_name}}.error.log

</VirtualHost>