import subprocess
import shutil
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

edge_path = r"C:\Program Files (x86)\Microsoft\Edge\Application\msedge.exe"
html_path = r"c:\xampp\htdocs\spf\september_report_v2.html"
pdf_path1 = r"c:\xampp\htdocs\spf\मासिक कार्य अहवाल  माहे सप्टेंबर २०२६.pdf"
pdf_path2 = r"c:\xampp\htdocs\spf\Sharad Pawar fellowship\मासिक कार्य अहवाल  माहे सप्टेंबर २०२६.pdf"

cmd = [
    edge_path,
    "--headless",
    "--disable-gpu",
    "--no-pdf-header-footer",
    "--run-all-compositor-stages-before-draw",
    f"--print-to-pdf={pdf_path1}",
    f"file:///{html_path.replace(os.sep, '/')}"
]

print("Rendering PDF with headless Edge...")
res = subprocess.run(cmd, capture_output=True)
print("Render exit code:", res.returncode)

if os.path.exists(pdf_path1):
    print(f"PDF generated: {pdf_path1} ({os.path.getsize(pdf_path1)/(1024):.1f} KB)")
    shutil.copy2(pdf_path1, pdf_path2)
    print(f"Copied to: {pdf_path2}")
else:
    print("PDF generation failed!")
