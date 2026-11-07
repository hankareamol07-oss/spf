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
print("Cookies after login:", session.cookies.get_dict())

# 2. Get games page
r_games = session.get(f'{base}/games')
game_token = re.search(r'name="_token" value="([^"]+)"', r_games.text).group(1)

# Let's test starting each game
for game_code in ['ADDITION_ADVENTURE', 'PLACE_VALUE_CASTLE', 'NUMBER_LINE_FROG']:
    start_url = f'{base}/games/{game_code}/start'
    print(f"\n--- Testing {game_code} ---")
    r_start = session.post(start_url, data={'_token': game_token}, allow_redirects=False)
    print("Start status:", r_start.status_code)
    loc = r_start.headers.get('Location')
    print("Redirect Location:", loc)
    if loc:
        r_show = session.get(loc, allow_redirects=False)
        print("Show page status:", r_show.status_code)
        print("Show headers:", dict(r_show.headers))
        if r_show.status_code == 302:
            print("Show redirect Location:", r_show.headers.get('Location'))
            r_show_dest = session.get(r_show.headers.get('Location'))
            print("Followed show redirect status:", r_show_dest.status_code)
        elif r_show.status_code == 404:
            print("Show returned 404! Body preview:")
            print(r_show.text[:1000])
