import requests
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

session = requests.Session()
base = 'http://52.66.73.149/spf'

r = session.get(f'{base}/login')
token = re.search(r'name="_token" value="([^"]+)"', r.text).group(1)

r_login = session.post(f'{base}/login', data={
    '_token': token,
    'email': 'amolhankare04@gmail.com',
    'password': 'password'
})

urls = [
    '/dashboard',
    '/games',
    '/fellowship/reports',
    '/fellowship/reports/4',
    '/fellowship/reports/4/print',
    '/students',
    '/school/dashboard',
    '/platform/dashboard',
    '/reports'
]

for u in urls:
    resp = session.get(f'{base}{u}')
    title = re.search(r'<title>(.*?)</title>', resp.text)
    title_str = title.group(1) if title else 'No title'
    print(f"{u:30} -> {resp.status_code} (length: {len(resp.text)}) Title: {title_str}")
