import os
from PIL import Image, ImageDraw, ImageFont, ImageFilter

# Target A4 size at 150 DPI: 1240 x 1754
canvas_w, canvas_h = 1240, 1754
canvas = Image.new('RGB', (canvas_w, canvas_h), '#f8f9fa')
draw = ImageDraw.Draw(canvas)

# Fonts
try:
    font_title = ImageFont.truetype("arialbd.ttf", 28)
    font_sub = ImageFont.truetype("arial.ttf", 16)
    font_badge = ImageFont.truetype("arialbd.ttf", 14)
except:
    font_title = ImageFont.load_default()
    font_sub = ImageFont.load_default()
    font_badge = ImageFont.load_default()

# 1. Header Banner
draw.rectangle([(0, 0), (canvas_w, 80)], fill='#1a237e')
draw.text((40, 18), "Student Activity & Digital Assessment Portal", fill='#ffffff', font=font_title)
draw.text((40, 52), "Live on AWS Cloud: http://52.66.73.149/spf | Z.P. School No. 2 Kuchi, Dist. Sangli", fill='#bbdefb', font=font_sub)

# Right badge
draw.rounded_rectangle([(canvas_w - 240, 20), (canvas_w - 40, 60)], radius=20, fill='#ff6f00')
draw.text((canvas_w - 220, 28), "● Live Cloud Server", fill='#ffffff', font=font_badge)

# Load captured screens
img_dash = Image.open('c:/xampp/htdocs/spf/cap_dashboard.png')
img_games = Image.open('c:/xampp/htdocs/spf/cap_games.png')
img_students = Image.open('c:/xampp/htdocs/spf/cap_students.png')
img_reports = Image.open('c:/xampp/htdocs/spf/cap_reports.png')

# 2. Main Dashboard & Activity Card (Upper area)
# Crop dashboard header & overview
crop_dash = img_dash.crop((60, 60, 1220, 480)).resize((1160, 400), Image.LANCZOS)
canvas.paste(crop_dash, (40, 100))
draw.rectangle([(40, 100), (1200, 500)], outline='#c5cae9', width=2)

# Label 1
draw.rounded_rectangle([(50, 110), (350, 140)], radius=15, fill='#283593')
draw.text((65, 116), "१. स्टुडन्ट ॲक्टिव्हिटी डॅशबोर्ड", fill='#ffffff', font=font_badge)

# 3. Middle Left: Games & AppleMath Showcase
crop_games = img_games.crop((60, 120, 1220, 720)).resize((565, 450), Image.LANCZOS)
canvas.paste(crop_games, (40, 520))
draw.rectangle([(40, 520), (605, 970)], outline='#c5cae9', width=2)

# Label 2
draw.rounded_rectangle([(50, 530), (410, 560)], radius=15, fill='#c62828')
draw.text((65, 536), "२. AppleMath व इयत्ता ४ थी खेळ", fill='#ffffff', font=font_badge)

# 4. Middle Right: Student Roster & Registration
crop_students = img_students.crop((60, 60, 1220, 660)).resize((565, 450), Image.LANCZOS)
canvas.paste(crop_students, (635, 520))
draw.rectangle([(635, 520), (1200, 970)], outline='#c5cae9', width=2)

# Label 3
draw.rounded_rectangle([(645, 530), (965, 560)], radius=15, fill='#2e7d32')
draw.text((660, 536), "३. इयत्ता ४ थी विद्यार्थी नोंदणी यादी", fill='#ffffff', font=font_badge)

# 5. Bottom: Fellowship Progress & Analytics Reports
crop_reports = img_reports.crop((60, 60, 1220, 760)).resize((1160, 680), Image.LANCZOS)
canvas.paste(crop_reports, (40, 990))
draw.rectangle([(40, 990), (1200, 1670)], outline='#c5cae9', width=2)

# Label 4
draw.rounded_rectangle([(50, 1000), (450, 1030)], radius=15, fill='#6a1b9a')
draw.text((65, 1006), "४. फेलोशिप डिजिटल प्रगती मापन व अहवाल", fill='#ffffff', font=font_badge)

# Footer bar
draw.rectangle([(0, canvas_h - 60), (canvas_w, canvas_h)], fill='#e8eaf6')
draw.text((40, canvas_h - 45), "शरद पवार इन्स्पायर फेलोशिप २०२६-२७ | फेलो: श्री. अमोल किसन हंकारे (SPF-ID: SPFEDU-0415) | मेंतॉर: श्री. सोमनाथ वाळके सर", fill='#37474f', font=font_sub)
draw.text((canvas_w - 280, canvas_h - 45), "थेट लिंक: http://52.66.73.149/spf", fill='#0d47a1', font=font_badge)

out_path = 'c:/xampp/htdocs/spf/report_page2_evidence.png'
canvas.save(out_path, quality=95)
print('Saved collage to', out_path, 'Size:', os.path.getsize(out_path))
