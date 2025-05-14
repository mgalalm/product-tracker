
from pptx import Presentation
from pptx.util import Inches, Pt
from pptx.enum.text import PP_ALIGN
from pptx.dml.color import RGBColor
from pptx.enum.dml import MSO_THEME_COLOR
from pptx.chart.data import CategoryChartData
from pptx.enum.chart import XL_CHART_TYPE

# Initialize presentation
prs = Presentation()

# Helper function to set text properties
def set_text_properties(run, font_size, bold=False, color=None):
    run.font.size = Pt(font_size)
    run.font.bold = bold
    if color:
        run.font.color.rgb = color
    run.font.name = 'Calibri'

# Slide 1: Title Slide
slide_layout = prs.slide_layouts[0]  # Title Slide
slide = prs.slides.add_slide(slide_layout)
title = slide.shapes.title
subtitle = slide.placeholders[1]
title.text = "Promotion Case: Principal Release Engineer"
set_text_properties(title.text_frame.paragraphs[0].runs[0], 32, bold=True, color=RGBColor(0, 85, 102))

title.text_frame.paragraphs[0].alignment = PP_ALIGN.CENTER
subtitle.text = "[Your Name], Senior Release Engineer\nApril 29, 2025\nDriving Global Release Excellence"
set_text_properties(subtitle.text_frame.paragraphs[0].runs[0], 20, color=RGBColor(0, 0, 0))
subtitle.text_frame.paragraphs[2].runs[0].font.italic = True
set_text_properties(subtitle.text_frame.paragraphs[2].runs[0], 20, color=RGBColor(0, 85, 102))
subtitle.text_frame.paragraphs[0].alignment = PP_ALIGN.CENTER

# Slide 2: Introduction
slide_layout = prs.slide_layouts[1]  # Title and Content
slide = prs.slides.add_slide(slide_layout)
title = slide.shapes.title
title.text = "Introduction"
set_text_properties(title.text_frame.paragraphs[0].runs[0], 24, color=RGBColor(0, 85, 102))
content = slide.shapes.placeholders[1].text_frame
content.text = "• Current role: Senior Release Engineer with [X years] of experience\n• Expertise in cloud computing, backed by Master’s Degree and AWS certifications\n• With ~100 releases and ~700 consultations annually across global divisions, I am prepared to lead at a principal level"
for p in content.paragraphs:
    set_text_properties(p.runs[0], 18)

# Slide 3: Role of a Principal Release Engineer
slide = prs.slides.add_slide(slide_layout)
title = slide.shapes.title
title.text = "Role of a Principal Release Engineer"
set_text_properties(title.text_frame.paragraphs[0].runs[0], 24, color=RGBColor(0, 85, 102))
content = slide.shapes.placeholders[1].text_frame
content.text = "• Oversee release pipelines, ensure reliability, mentor teams, drive innovation\n• Align with company goals: Seamless global deployments, high reliability, security, efficiency\n• The Principal role is critical for scalable, secure software delivery worldwide"
for p in content.paragraphs:
    set_text_properties(p.runs[0], 18)

# Slide 4: My Qualifications
slide = prs.slides.add_slide(slide_layout)
title = slide.shapes.title
title.text = "My Qualifications"
set_text_properties(title.text_frame.paragraphs[0].runs[0], 24, color=RGBColor(0, 85, 102))
content = slide.shapes.placeholders[1].text_frame
content.text = "• Master’s Degree in Computer Science (Cloud Computing), First-Class Honours\n• 3 AWS Certifications, including AWS Certified Solutions Architect\n• [X years] in release engineering, managing CI/CD pipelines\n• Expertise in automation, cross-regional coordination, cloud-based release strategies\n• Proven ability to guide release managers and deliver high-frequency releases globally"
for p in content.paragraphs:
    set_text_properties(p.runs[0], 18)

# Slide 5: Key Achievements
slide_layout = prs.slide_layouts[3]  # Two Content
slide = prs.slides.add_slide(slide_layout)
title = slide.shapes.title
title.text = "Key Achievements"
set_text_properties(title.text_frame.paragraphs[0].runs[0], 24, color=RGBColor(0, 85, 102))
# Left column: Text
content = slide.placeholders[1].text_frame
content.text = "• Delivered ~100 releases/year across multiple projects with consistency\n• Provided ~700 consultations/year (2-3 daily) to release managers in EMEA, Vision Care NA, Vision Care EMEA, ONE EMEA MD, Vision Care ASPAC, CARTCIO, etc.\n• Modernized CI/CD pipeline using string replacements instead of shell scripts, saving significant time per deployment and enhancing security\n• Implemented PR auto-validation, triggering builds on PR open and every push, saving time over manual Jenkins tasks\n• Integrated code coverage into validation/deployment builds, ensuring >75% coverage for ASkG decommissioning, saving 1-2 hours per test cycle"
for p in content.paragraphs:
    set_text_properties(p.runs[0], 16)
# Right column: Bar Chart
chart_data = CategoryChartData()
chart_data.categories = ['Releases', 'Consultations']
chart_data.add_series('Annual Impact', (100, 700))
from pptx.chart.data import ChartData

chart_data = ChartData()
chart_data.categories = ['Releases', 'Consultations']
chart_data.add_series('Annual Impact', (100, 700))

x, y, cx, cy = Inches(4), Inches(2), Inches(4), Inches(3)  # Define chart position and size
chart = slide.shapes.add_chart(XL_CHART_TYPE.COLUMN_CLUSTERED, x, y, cx, cy, chart_data).chart
chart.has_title = True
chart.chart_title.text_frame.text = "Annual Impact"
set_text_properties(chart.chart_title.text_frame.paragraphs[0].runs[0], 16, color=RGBColor(0, 85, 102))

# Slide 6: Leadership and Impact
slide = prs.slides.add_slide(slide_layout)
title = slide.shapes.title
title.text = "Leadership and Impact"
set_text_properties(title.text_frame.paragraphs[0].runs[0], 24, color=RGBColor(0, 85, 102))
content = slide.shapes.placeholders[1].text_frame
content.text = "• Proposed and led pipeline modernization, PR auto-validation, and code coverage integration, adopted organization-wide\n• Guided release managers globally, standardizing processes across divisions\n• Coordinated with EMEA, NA, ASPAC, and other teams for alignment\n• My consultations and initiatives have empowered teams to deliver secure, efficient releases"
for p in content.paragraphs:
    set_text_properties(p.runs[0], 18)

# Slide 7: Vision for the Role
slide = prs.slides.add_slide(slide_layout)
title = slide.shapes.title
title.text = "Vision for the Role"
set_text_properties(title.text_frame.paragraphs[0].runs[0], 24, color=RGBColor(0, 85, 102))
content = slide.shapes.placeholders[1].text_frame
content.text = "• Innovate: Scale code coverage, PR auto-validation, and pipeline modernization with AWS-driven automation\n• Mentor: Empower release managers with advanced cloud and quality practices\n• Strategize: Align ~100 releases/year with business goals (reliability, speed, security)\n• I aim to enhance our global release strategy, supporting ~700+ consultations"
for p in content.paragraphs:
    set_text_properties(p.runs[0], 18)

# Slide 8: Why Me?
slide = prs.slides.add_slide(slide_layout)
title = slide.shapes.title
title.text = "Why Me?"
set_text_properties(title.text_frame.paragraphs[0].runs[0], 24, color=RGBColor(0, 85, 102))
content = slide.shapes.placeholders[1].text_frame
content.text = "• Proven expertise: Delivered ~100 releases and ~700 consultations/year\n• Credentials: Master’s in Cloud Computing, 3 AWS Certifications\n• Innovation: Led pipeline modernization, PR auto-validation, and code coverage integration\n• Leadership: Guided global teams, ready to mentor at a principal level\n• I am ready to elevate release engineering as Principal Release Engineer\n\nThank you for considering my promotion. Questions?"
for i, p in enumerate(content.paragraphs):
    if p.runs:
        set_text_properties(p.runs[0], 18 if i < 5 else 18, color=RGBColor(0, 85, 102) if i == 6 else None)
        if i == 6:
            p.runs[0].font.italic = True

# Save the presentation
prs.save("Promotion_to_Principal_Release_Engineer.pptx")
