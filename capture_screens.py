from playwright.sync_api import sync_playwright

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    
    # Context 1: Public Home Page
    page = browser.new_page(viewport={'width': 1280, 'height': 850})
    page.goto('http://52.66.73.149/spf/')
    page.wait_for_load_state('networkidle')
    page.screenshot(path='c:/xampp/htdocs/spf/screen_home.png')
    print('Home screen captured')

    # Context 2: Login as Amol Hankare (SuperAdmin / Mentee)
    page.goto('http://52.66.73.149/spf/login')
    page.fill('input[name="email"]', 'amolhankare04@gmail.com')
    page.fill('input[name="password"]', 'password')
    page.click('button[type="submit"]')
    page.wait_for_load_state('networkidle')
    print('Logged in as Amol Hankare, URL:', page.url)
    
    # 1. Games showcase (AppleMath & Class 4 Games)
    page.goto('http://52.66.73.149/spf/games')
    page.wait_for_load_state('networkidle')
    page.screenshot(path='c:/xampp/htdocs/spf/cap_games.png')
    print('Games captured')
    
    # 2. Fellowship Reports Hub (September 2026 Fellowship Progress & CCE Analytics)
    page.goto('http://52.66.73.149/spf/fellowship/reports/4')
    page.wait_for_load_state('networkidle')
    page.screenshot(path='c:/xampp/htdocs/spf/cap_reports.png')
    print('Reports captured')

    # Context 3: Login as School Admin to capture full Student Roster
    page.goto('http://52.66.73.149/spf/login')
    # If already logged in, logout or clear cookies
    browser_context = browser.new_context(viewport={'width': 1280, 'height': 850})
    page2 = browser_context.new_page()
    page2.goto('http://52.66.73.149/spf/login')
    page2.fill('input[name="email"]', 'school-admin@example.test')
    page2.fill('input[name="password"]', 'password')
    page2.click('button[type="submit"]')
    page2.wait_for_load_state('networkidle')
    
    # 3. Students Roster
    page2.goto('http://52.66.73.149/spf/students')
    page2.wait_for_load_state('networkidle')
    page2.screenshot(path='c:/xampp/htdocs/spf/cap_students.png')
    print('Students captured')

    # 4. Student Practice / Activity Dashboard
    page2.goto('http://52.66.73.149/spf/school/dashboard')
    page2.wait_for_load_state('networkidle')
    page2.screenshot(path='c:/xampp/htdocs/spf/cap_dashboard.png')
    print('Dashboard captured')

    browser.close()
print('All 4 live portal screenshots re-captured with authentic data!')
