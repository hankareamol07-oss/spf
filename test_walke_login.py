import requests
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

session = requests.Session()
base = 'http://52.66.73.149/spf'

r_login_page = session.get(f'{base}/login')
token = re.search(r'name="_token" value="([^"]+)"', r_login_page.text).group(1)

r_login = session.post(f'{base}/login', data={
    '_token': token,
    'email': 'somnathwalke@gmail.com',
    'password': 'password'
}, allow_redirects=True)

print("Somnath Walke login status:", r_login.status_code)
print("Redirect URL:", r_login.url)
assert r_login.status_code == 200, "Login failed!"
print("✓ Somnath Walke login verified successfully!")
