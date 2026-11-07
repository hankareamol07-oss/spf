import subprocess

key = r"d:\SMS\school-management-key-v2.pem"
host = "ubuntu@52.66.73.149"

conf = """Alias /spf /var/www/html/spf/public
Alias /student-portal /var/www/html/spf/public

<Directory /var/www/html/spf/public>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted

    <FilesMatch \\.php$>
        SetHandler "proxy:unix:/run/php/php8.4-fpm.sock|fcgi://localhost"
    </FilesMatch>
</Directory>
"""

# Copy conf via ssh stdin
proc = subprocess.Popen(["ssh", "-i", key, "-o", "StrictHostKeyChecking=no", host, "sudo tee /etc/apache2/conf-available/spf.conf > /dev/null"], stdin=subprocess.PIPE, text=True)
proc.communicate(input=conf)
print("Config written.")

reload_cmd = "sudo a2enconf spf && sudo apache2ctl configtest && sudo systemctl reload apache2"
res = subprocess.run(["ssh", "-i", key, "-o", "StrictHostKeyChecking=no", host, reload_cmd], capture_output=True, text=True)
print("Reload result:", res.stdout, res.stderr)
