import requests
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

session = requests.Session()
base = 'http://52.66.73.149/spf'

# 1. Get login page to extract CSRF token
r = session.get(f'{base}/login')
token_match = re.search(r'name="_token" value="([^"]+)"', r.text)
token = token_match.group(1) if token_match else ''
print('CSRF token:', token[:15] + '...')

# 2. Login as Amol Hankare
r_login = session.post(f'{base}/login', data={
    '_token': token,
    'email': 'amolhankare04@gmail.com',
    'password': 'password'
}, allow_redirects=True)

print('Login response status:', r_login.status_code)
print('Redirected to:', r_login.url)
assert 'अमोल' in r_login.text or 'हंकारे' in r_login.text or 'dashboard' in r_login.url, 'Dashboard missing'
print('✓ Amol Hankare login OK!')

# 3. Test Games page
r_games = session.get(f'{base}/games')
print('Games page status:', r_games.status_code)
assert 'AppleMath' in r_games.text, 'AppleMath missing on games page'
print('✓ Class 4 Games and AppleMath accessible on EC2 OK!')

# 4. Test Fellowship Reports page
r_reports = session.get(f'{base}/fellowship/reports')
print('Fellowship reports page status:', r_reports.status_code)
assert 'सप्टेंबर' in r_reports.text or 'अहवाल' in r_reports.text, 'Reports page missing Marathi text'
print('✓ Fellowship Reports page accessible on EC2 OK!')

# 5. Test September 2026 Print/View
r_sept = session.get(f'{base}/fellowship/reports/4/print')
print('September report print status:', r_sept.status_code)
assert 'सप्टेंबर' in r_sept.text, 'September print view missing'
print('✓ September Report print view accessible on EC2 OK!')
