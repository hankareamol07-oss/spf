import docx
import os
import sys
import shutil

sys.stdout.reconfigure(encoding='utf-8')

from docx.shared import Inches, Pt, RGBColor
from docx.enum.text import WD_ALIGN_PARAGRAPH
from docx.enum.table import WD_TABLE_ALIGNMENT
from docx.oxml import OxmlElement, parse_xml
from docx.oxml.ns import nsdecls, qn

doc = docx.Document()

# Page Setup: A4 with tight margins to fit Page 1 perfectly without spilling over
for section in doc.sections:
    section.page_width = Inches(8.27)
    section.page_height = Inches(11.69)
    section.top_margin = Inches(0.55)
    section.bottom_margin = Inches(0.55)
    section.left_margin = Inches(0.65)
    section.right_margin = Inches(0.65)

def add_marathi_run(paragraph, text, size=10.5, bold=False, color_rgb=(0,0,0), underline=False, italic=False):
    run = paragraph.add_run(text)
    run.font.name = 'Nirmala UI'
    run.font.size = Pt(size)
    run.font.bold = bold
    run.font.underline = underline
    run.font.italic = italic
    run.font.color.rgb = RGBColor(*color_rgb)
    rPr = run._r.get_or_add_rPr()
    rFonts = parse_xml(f'<w:rFonts {nsdecls("w")} w:ascii="Nirmala UI" w:hAnsi="Nirmala UI" w:cs="Nirmala UI"/>')
    rPr.append(rFonts)
    return run

def set_cell_background(cell, fill_hex):
    tcPr = cell._tc.get_or_add_tcPr()
    shd = parse_xml(f'<w:shd {nsdecls("w")} w:fill="{fill_hex}"/>')
    tcPr.append(shd)

def set_cell_borders(cell, top='none', bottom='none', left='none', right='none', color='CCCCCC', sz='4'):
    tcPr = cell._tc.get_or_add_tcPr()
    borders_xml = f'<w:tcBorders {nsdecls("w")}>'
    for side, val in [('top', top), ('bottom', bottom), ('left', left), ('right', right)]:
        if val == 'none':
            borders_xml += f'<w:{side} w:val="none"/>'
        else:
            borders_xml += f'<w:{side} w:val="{val}" w:sz="{sz}" w:space="0" w:color="{color}"/>'
    borders_xml += '</w:tcBorders>'
    tcPr.append(parse_xml(borders_xml))

# ==========================================
# PAGE 1: OFFICIAL MONTHLY REPORT
# ==========================================

# 1. Main Title
p_title = doc.add_paragraph()
p_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_title.paragraph_format.space_before = Pt(0)
p_title.paragraph_format.space_after = Pt(10)
add_marathi_run(p_title, "फेलोशिप प्रकल्पाच्या कार्यवाहीबाबत सप्टेंबर 2026 चा अहवाल", size=15, bold=True, color_rgb=(169, 50, 38))

# 2. Mentee & Mentor Row
table_mm = doc.add_table(rows=1, cols=2)
table_mm.alignment = WD_TABLE_ALIGNMENT.CENTER
table_mm.autofit = False

cell_left = table_mm.rows[0].cells[0]
cell_right = table_mm.rows[0].cells[1]
cell_left.width = Inches(3.45)
cell_right.width = Inches(3.45)

for cell in [cell_left, cell_right]:
    set_cell_borders(cell, 'none', 'none', 'none', 'none')

p_mentee = cell_left.paragraphs[0]
p_mentee.alignment = WD_ALIGN_PARAGRAPH.LEFT
p_mentee.paragraph_format.space_after = Pt(2)
add_marathi_run(p_mentee, "Mentee : ", size=10.5, bold=True, color_rgb=(81, 46, 95))
add_marathi_run(p_mentee, "श्री. अमोल किसन हंकारे", size=10.5, bold=True, color_rgb=(40, 53, 147))

p_mentor = cell_right.paragraphs[0]
p_mentor.alignment = WD_ALIGN_PARAGRAPH.RIGHT
p_mentor.paragraph_format.space_after = Pt(2)
add_marathi_run(p_mentor, "Mentor : ", size=10.5, bold=True, color_rgb=(81, 46, 95))
add_marathi_run(p_mentor, "मा. श्री. सोमनाथ वाळके सर", size=10.5, bold=True, color_rgb=(40, 53, 147))

# 3. Subject Line
p_sub = doc.add_paragraph()
p_sub.alignment = WD_ALIGN_PARAGRAPH.LEFT
p_sub.paragraph_format.space_before = Pt(4)
p_sub.paragraph_format.space_after = Pt(10)
add_marathi_run(p_sub, "फेलोशिप प्रकल्पाचा विषय : ", size=10.5, bold=True, color_rgb=(21, 67, 96))
add_marathi_run(p_sub, "“डिजिटल खेळाच्या माध्यमातून शैक्षणिक प्रगती मापन”", size=10.5, bold=True, color_rgb=(11, 83, 148))

# 4. Heading: अहवाल
p_ahwal = doc.add_paragraph()
p_ahwal.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_ahwal.paragraph_format.space_before = Pt(4)
p_ahwal.paragraph_format.space_after = Pt(8)
add_marathi_run(p_ahwal, "अहवाल", size=12.5, bold=True, color_rgb=(14, 102, 85), underline=True)

# 5. Body Paragraph 1
p_p1 = doc.add_paragraph()
p_p1.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
p_p1.paragraph_format.line_spacing = 1.25
p_p1.paragraph_format.space_after = Pt(6)
add_marathi_run(p_p1, "    सदर फेलोशिप प्रकल्पांतर्गत इयत्ता चौथीच्या विद्यार्थ्यांच्या मूलभूत गणित व भाषा संकल्पनांचे स्वयंअध्ययन, डिजिटल खेळांद्वारे सक्षमीकरण आणि सातत्यपूर्ण, वस्तुनिष्ठ व विश्वासाह्र मूल्यांकन करण्याच्या उद्देशाने प्रकल्पाचे नियोजन करण्यात आले आहे. प्रकल्पाच्या प्राथमिक टप्प्यात विद्यार्थ्यांच्या क्षमतांचे विश्लेषण करून स्वतंत्र वेब प्रणाली व 'Student Activity & Learning Portal' (स्टुडन्ट ॲक्टिव्हिटी व डिजिटल असेसमेंट पोर्टल) विकसित करण्यात आले आहे.", size=10.5, color_rgb=(33, 33, 33))

# 6. Body Paragraph 2
p_p2 = doc.add_paragraph()
p_p2.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
p_p2.paragraph_format.line_spacing = 1.25
p_p2.paragraph_format.space_after = Pt(8)
add_marathi_run(p_p2, "    जिल्हा परिषद प्राथमिक शाळा नं. २ कुची, ता. कवठेमहांकाळ, जि. सांगली या कार्यस्थळावरील इयत्ता चौथीच्या सर्व विद्यार्थ्यांची डिजिटल पोर्टलवर नोंदणी (Student Registration) पूर्ण करण्यात आली आहे. त्यानुसार विद्यार्थ्यांची शैक्षणिक प्रगती, कौशल्ये, उपक्रमांतील सहभाग व इतर आवश्यक नोंदी डिजिटल स्वरूपात संकलित करण्यासाठी 'AppleMath' (सफरचंद बेरीज व वजाबाकी), स्थानिक किंमत घर (हजार, शतक, दशक, एकक), संख्यारेषा बेडूक उडी, अपूर्णांक पिझ्झा, नाणी-नोटा बाजार तसेच मराठी भाषा व व्याकरणाचे ३३ परस्परसंवादी डिजिटल खेळ उपलब्ध करून देण्यात आले आहेत.", size=10.5, color_rgb=(33, 33, 33))

# 7. PROMINENT LIVE PORTAL CREDENTIALS & QR CODE BOX (2 Columns)
table_box = doc.add_table(rows=1, cols=2)
table_box.alignment = WD_TABLE_ALIGNMENT.CENTER
table_box.autofit = False

c_info = table_box.rows[0].cells[0]
c_qr = table_box.rows[0].cells[1]
c_info.width = Inches(5.1)
c_qr.width = Inches(1.8)

# Shading and borders
set_cell_background(c_info, "F0F4F8")
set_cell_background(c_qr, "F0F4F8")
set_cell_borders(c_info, top='single', bottom='single', left='single', right='none', color='1565C0', sz='12')
set_cell_borders(c_qr, top='single', bottom='single', left='none', right='single', color='1565C0', sz='12')

# Left Info Cell Content
p_box_title = c_info.paragraphs[0]
p_box_title.paragraph_format.space_before = Pt(3)
p_box_title.paragraph_format.space_after = Pt(2)
add_marathi_run(p_box_title, "🌐 थेट लाइव्ह पोर्टल लिंक (Active Live Cloud Portal):", size=10.5, bold=True, color_rgb=(183, 28, 28))

p_box_url = c_info.add_paragraph()
p_box_url.paragraph_format.space_after = Pt(3)
add_marathi_run(p_box_url, "👉 थेट लिंक: ", size=9.5, bold=True, color_rgb=(13, 71, 161))
add_marathi_run(p_box_url, "http://52.66.73.149/spf", size=10, bold=True, color_rgb=(13, 71, 161), underline=True)
add_marathi_run(p_box_url, "  (SSL: https://52-66-73-149.sslip.io/spf)", size=8.5, italic=True, color_rgb=(100, 116, 139))

p_box_creds_head = c_info.add_paragraph()
p_box_creds_head.paragraph_format.space_after = Pt(2)
add_marathi_run(p_box_creds_head, "🔑 पोर्टल लॉगिन तपशील (User IDs & Passwords):", size=9.5, bold=True, color_rgb=(46, 125, 50))

p_box_creds = c_info.add_paragraph()
p_box_creds.paragraph_format.line_spacing = 1.15
p_box_creds.paragraph_format.space_after = Pt(3)
# 1. Mentee Amol Hankare
add_marathi_run(p_box_creds, "• Mentee / फेलो (श्री. अमोल किसन हंकारे): ", size=9, bold=True, color_rgb=(33, 33, 33))
add_marathi_run(p_box_creds, "User ID: ", size=9, color_rgb=(55, 71, 79))
add_marathi_run(p_box_creds, "amolhankare04@gmail.com", size=9, bold=True, color_rgb=(13, 71, 161))
add_marathi_run(p_box_creds, " | Password: ", size=9, color_rgb=(55, 71, 79))
add_marathi_run(p_box_creds, "password\n", size=9, bold=True, color_rgb=(198, 40, 40))

# 2. Mentor Somnath Walke
add_marathi_run(p_box_creds, "• Mentor / मार्गदर्शक (मा. श्री. सोमनाथ वाळके सर): ", size=9, bold=True, color_rgb=(33, 33, 33))
add_marathi_run(p_box_creds, "User ID: ", size=9, color_rgb=(55, 71, 79))
add_marathi_run(p_box_creds, "somnathwalke@gmail.com", size=9, bold=True, color_rgb=(13, 71, 161))
add_marathi_run(p_box_creds, " | Password: ", size=9, color_rgb=(55, 71, 79))
add_marathi_run(p_box_creds, "password\n", size=9, bold=True, color_rgb=(198, 40, 40))

# 3. Student Demo
add_marathi_run(p_box_creds, "• विद्यार्थी चाचणी (Student Demo): ", size=9, bold=True, color_rgb=(33, 33, 33))
add_marathi_run(p_box_creds, "User ID: ", size=9, color_rgb=(55, 71, 79))
add_marathi_run(p_box_creds, "student1@example.test", size=9, bold=True, color_rgb=(13, 71, 161))
add_marathi_run(p_box_creds, " | Password: ", size=9, color_rgb=(55, 71, 79))
add_marathi_run(p_box_creds, "password", size=9, bold=True, color_rgb=(198, 40, 40))

p_box_host = c_info.add_paragraph()
p_box_host.paragraph_format.space_after = Pt(2)
add_marathi_run(p_box_host, "☁️ Amazon AWS EC2 Cloud Server (24×7 चालू व कार्यरत)", size=8.5, italic=True, color_rgb=(100, 116, 139))

# Right QR Cell Content
p_qr_img = c_qr.paragraphs[0]
p_qr_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_qr_img.paragraph_format.space_before = Pt(3)
p_qr_img.paragraph_format.space_after = Pt(2)
qr_path = 'c:/xampp/htdocs/spf/portal_qr.png'
if os.path.exists(qr_path):
    run_qr = p_qr_img.add_run()
    run_qr.add_picture(qr_path, width=Inches(1.25))

p_qr_txt = c_qr.add_paragraph()
p_qr_txt.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_qr_txt.paragraph_format.space_after = Pt(2)
add_marathi_run(p_qr_txt, "📱 मोबाईलवर QR कोड\nस्कॅन करून थेट पहा", size=8.5, bold=True, color_rgb=(13, 71, 161))

# Spacer
p_sp = doc.add_paragraph()
p_sp.paragraph_format.space_before = Pt(4)
p_sp.paragraph_format.space_after = Pt(0)

# 8. Body Paragraph 3
p_p3 = doc.add_paragraph()
p_p3.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
p_p3.paragraph_format.line_spacing = 1.25
p_p3.paragraph_format.space_after = Pt(6)
add_marathi_run(p_p3, "    डिजिटल खेळांच्या माध्यमातून विद्यार्थ्यांची अचूकता (Accuracy %), खेळासाठी लागणारा वेळ (Time Spent) आणि प्राप्त स्तर (निपुण प्रगत / प्रगतीपथावर / सराव आवश्यक) याची स्वयंचलित विश्लेषणात्मक नोंद पोर्टलवर संकलित होत आहे. यामुळे पारंपरिक पद्धतीऐवजी डिजिटल खेळांच्या माध्यमातून भयमुक्त व आनंददायी वातावरणात विद्यार्थ्यांचे सातत्यपूर्ण सर्वंकष मूल्यमापन (CCE / FLN Progress) साध्य होत आहे.", size=10.5, color_rgb=(33, 33, 33))

# 9. Body Paragraph 4
p_p4 = doc.add_paragraph()
p_p4.alignment = WD_ALIGN_PARAGRAPH.JUSTIFY
p_p4.paragraph_format.line_spacing = 1.25
p_p4.paragraph_format.space_after = Pt(10)
add_marathi_run(p_p4, "    सदर प्रकल्पाची पुढील कार्यवाही मार्गदर्शक मा. श्री. सोमनाथ वाळके सर यांच्या मार्गदर्शनानुसार नियोजनबद्ध पद्धतीने पूर्ण करून प्रकल्पाची उद्दिष्टे व अध्ययन निष्पत्ती १००% साध्य करण्यात येत आहेत.", size=10.5, color_rgb=(33, 33, 33))

# 10. Signatures Row
table_sig = doc.add_table(rows=1, cols=2)
table_sig.alignment = WD_TABLE_ALIGNMENT.CENTER
table_sig.autofit = False

c_sig_left = table_sig.rows[0].cells[0]
c_sig_right = table_sig.rows[0].cells[1]
c_sig_left.width = Inches(3.45)
c_sig_right.width = Inches(3.45)

for c in [c_sig_left, c_sig_right]:
    set_cell_borders(c, 'none', 'none', 'none', 'none')

p_sl = c_sig_left.paragraphs[0]
p_sl.alignment = WD_ALIGN_PARAGRAPH.LEFT
add_marathi_run(p_sl, "श्री. अमोल किसन हंकारे\n", size=10.5, bold=True, color_rgb=(0, 0, 0))
add_marathi_run(p_sl, "(फेलो / Mentee)\nजि. प. शाळा नं. २ कुची, सांगली", size=9.5, color_rgb=(100, 100, 100))

p_sr = c_sig_right.paragraphs[0]
p_sr.alignment = WD_ALIGN_PARAGRAPH.RIGHT
add_marathi_run(p_sr, "मा. श्री. सोमनाथ वाळके सर\n", size=10.5, bold=True, color_rgb=(0, 0, 0))
add_marathi_run(p_sr, "(मार्गदर्शक / Mentor)\nशरद पवार इन्स्पायर फेलोशिप", size=9.5, color_rgb=(100, 100, 100))

# ==========================================
# PAGE 2: EVIDENCE & LIVE PORTAL SCREENSHOTS
# ==========================================
doc.add_page_break()

p_p2_title = doc.add_paragraph()
p_p2_title.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_p2_title.paragraph_format.space_before = Pt(0)
p_p2_title.paragraph_format.space_after = Pt(4)
add_marathi_run(p_p2_title, "प्रकल्पाचे छायाचित्र पुरावे व पोर्टल स्क्रीनशॉट्स", size=13.5, bold=True, color_rgb=(21, 67, 96))

p_p2_sub = doc.add_paragraph()
p_p2_sub.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_p2_sub.paragraph_format.space_after = Pt(8)
add_marathi_run(p_p2_sub, "AWS क्लाउड सर्व्हरवर लाइव्ह असणाऱ्या 'Student Activity & Learning Portal' ची प्रत्यक्ष छायाचित्रे (http://52.66.73.149/spf)", size=9, color_rgb=(80, 80, 80))

# Insert the composite evidence image
p_img = doc.add_paragraph()
p_img.alignment = WD_ALIGN_PARAGRAPH.CENTER
p_img.paragraph_format.space_before = Pt(0)
p_img.paragraph_format.space_after = Pt(0)

evidence_path = 'c:/xampp/htdocs/spf/report_page2_evidence.png'
doc.add_picture(evidence_path, width=Inches(6.8))

fellowship_folder_path = 'c:/xampp/htdocs/spf/Sharad Pawar fellowship/मासिक कार्य अहवाल  माहे सप्टेंबर २०२६.docx'
final_path = 'c:/xampp/htdocs/spf/मासिक कार्य अहवाल  माहे सप्टेंबर २०२६ - Final.docx'
root_path = 'c:/xampp/htdocs/spf/मासिक कार्य अहवाल  माहे सप्टेंबर २०२६.docx'
artifact_path = 'C:/Users/amolh/.gemini/antigravity/brain/22ad70de-c68b-4db6-be57-9c03b17722d3/मासिक कार्य अहवाल  माहे सप्टेंबर २०२६.docx'

doc.save(fellowship_folder_path)
print("Saved to:", fellowship_folder_path)

doc.save(final_path)
print("Saved to:", final_path)

try:
    doc.save(root_path)
    print("Saved to:", root_path)
except Exception as e:
    print("Note: Root path locked, saved to Final path instead:", e)

try:
    shutil.copy2(fellowship_folder_path, artifact_path)
    print("Saved to artifact directory:", artifact_path)
except Exception as e:
    print("Artifact copy note:", e)

print("DOCX generation completed successfully!")
