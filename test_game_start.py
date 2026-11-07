import requests
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

session = requests.Session()
base = 'http://52.66.73.149/spf'

# 1. Login
r_login_page = session.get(f'{base}/login')
token = re.search(r'name="_token" value="([^"]+)"', r_login_page.text).group(1)

r_login = session.post(f'{base}/login', data={
    '_token': token,
    'email': 'amolhankare04@gmail.com',
    'password': 'password'
})

# 2. Go to /games
r_games = session.get(f'{base}/games')
game_token = re.search(r'name="_token" value="([^"]+)"', r_games.text).group(1)

target_action = f'{base}/games/ADDITION_ADVENTURE/start'
print(f"Submitting to: {target_action}")
r_start = session.post(target_action, data={'_token': game_token}, allow_redirects=False)
print("Start status:", r_start.status_code)
print("Start headers Location:", r_start.headers.get('Location'))

if 'Location' in r_start.headers:
    loc = r_start.headers['Location']
    r_dest = session.get(loc)
    print(f"Destination: {loc} -> Status: {r_dest.status_code}")
    print("Page title:", re.findall(r'<title>(.*?)</title>', r_dest.text))
    print("Page preview:\n", r_dest.text[:500])
else:
    print("Response text:\n", r_start.text[:500])
