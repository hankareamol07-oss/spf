import urllib.request
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')
url = 'https://api.github.com/users/hankareamol07-oss/repos'
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
try:
    with urllib.request.urlopen(req) as res:
        data = json.loads(res.read().decode())
        print(f"Total repos found: {len(data)}")
        for r in data:
            print(f"- {r['name']}: {r['html_url']}")
except Exception as e:
    print('Error:', e)
