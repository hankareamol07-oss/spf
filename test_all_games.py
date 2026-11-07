import requests
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

session = requests.Session()
base = 'http://52.66.73.149/spf'

# 1. Login as Amol Hankare
r_login_page = session.get(f'{base}/login')
token = re.search(r'name="_token" value="([^"]+)"', r_login_page.text).group(1)

session.post(f'{base}/login', data={
    '_token': token,
    'email': 'amolhankare04@gmail.com',
    'password': 'password'
})

# 2. Get games list
r_games = session.get(f'{base}/games')
game_token = re.search(r'name="_token" value="([^"]+)"', r_games.text).group(1)

test_codes = [
    'ADDITION_ADVENTURE',
    'SUBTRACTION_ADVENTURE',
    'PLACE_VALUE_HOUSE',
    'NUMBER_LINE_JUMP',
    'FRACTION_PIZZA',
    'SHOPPING_GAME',
    'MULTIPLICATION_SPACE_MISSION',
    'DIVISION_SHARING_GAME',
    'PICTURE_WORD_MATCH',
    'SYNONYM_PAIRS'
]

passed = 0
failed = 0

for code in test_codes:
    start_url = f'{base}/games/{code}/start'
    r_start = session.post(start_url, data={'_token': game_token}, allow_redirects=False)
    loc = r_start.headers.get('Location')
    if r_start.status_code == 302 and loc:
        r_show = session.get(loc)
        if r_show.status_code == 200 and 'खेळ' in r_show.text:
            print(f"✓ Game {code:30s} -> Started & Loaded OK (HTTP 200)")
            passed += 1
        else:
            print(f"✗ Game {code:30s} -> Show returned {r_show.status_code}")
            failed += 1
    else:
        print(f"✗ Game {code:30s} -> Start returned {r_start.status_code}")
        failed += 1

print(f"\nResult: {passed} passed, {failed} failed.")
