import requests
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

session = requests.Session()
base = 'http://52.66.73.149/spf'

# Check 1: Home page
r_home = session.get(f'{base}/')
assert 'पुरस्कार' not in r_home.text, "Home page contains पुरस्कार!"
print("✓ 1. Home page (/spf/): Completely clean of '(राष्ट्रीय ICT पुरस्कार विजेते)'")

# Check 2: Login and check /games
r_login = session.get(f'{base}/login')
token = re.search(r'name="_token" value="([^"]+)"', r_login.text).group(1)
session.post(f'{base}/login', data={'_token': token, 'email': 'amolhankare04@gmail.com', 'password': 'password'})

r_games = session.get(f'{base}/games')
assert 'पुरस्कार' not in r_games.text, "/games contains पुरस्कार!"
print("✓ 2. Games page (/spf/games): Completely clean of 'पुरस्कार'")

# Check 3: Fellowship reports index
r_rep = session.get(f'{base}/fellowship/reports')
assert 'पुरस्कार' not in r_rep.text, "/fellowship/reports contains पुरस्कार!"
print("✓ 3. Reports index (/spf/fellowship/reports): Completely clean of 'पुरस्कार'")

# Check 4: September report show
r_show = session.get(f'{base}/fellowship/reports/4')
assert 'पुरस्कार' not in r_show.text, "Report show contains पुरस्कार!"
print("✓ 4. Report show (/spf/fellowship/reports/4): Completely clean of 'पुरस्कार'")

# Check 5: September report print
r_print = session.get(f'{base}/fellowship/reports/4/print')
assert 'पुरस्कार' not in r_print.text, "Report print contains पुरस्कार!"
print("✓ 5. Report print (/spf/fellowship/reports/4/print): Completely clean of 'पुरस्कार'")

print("\n🎉 ALL 5 LIVE SERVER PAGES ARE 100% VERIFIED CLEAN!")
