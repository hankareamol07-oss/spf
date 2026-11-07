import paramiko

ssh = paramiko.SSHClient()
ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy())
ssh.connect('52.66.73.149', username='ubuntu', key_filename=r'd:\SMS\school-management-key-v2.pem')

cmd = 'grep -rn "राष्ट्रीय ICT" /var/www/html/spf/resources /var/www/html/spf/storage/framework/views /var/www/html/spf/public /var/www/html/spf/app'
stdin, stdout, stderr = ssh.exec_command(cmd)
print('Matches on EC2 in resources/storage/public/app:')
out = stdout.read().decode('utf-8')
print(out if out else 'NONE (Clean!)')

cmd_clear = 'cd /var/www/html/spf && sudo -u www-data php artisan view:clear && sudo -u www-data php artisan cache:clear'
stdin, stdout, stderr = ssh.exec_command(cmd_clear)
print('Artisan clear:')
print(stdout.read().decode('utf-8'))

ssh.close()
