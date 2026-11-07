import requests
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

session = requests.Session()
base = 'http://52.66.73.149/spf'

r = session.get(f'{base}/login')
token = re.search(r'name="_token" value="([^"]+)"', r.text).group(1)

session.post(f'{base}/login', data={
    '_token': token,
    'email': 'school-admin@example.test',
    'password': 'password'
})

resp = session.get(f'{base}/students')
print('school-admin on /students:', resp.status_code, 'len:', len(resp.text))
title = re.search(r'<title>(.*?)</title>', resp.text)
print('Title:', title.group(1) if title else '')
