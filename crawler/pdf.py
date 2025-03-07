import PyPDF2
import os

year = input("Enter the year: ")
# Define the folder path
pdf_folder = os.path.expanduser(f"~/Dropbox/Adan/Adnan 3/{year}_join/")  
output_pdf = os.path.join(pdf_folder, f"{year}.pdf")

def merge_pdfs(folder, output_file):
    merger = PyPDF2.PdfMerger()
    
    # Get all PDF files in the folder, sorted alphabetically
    pdf_files = sorted([f for f in os.listdir(folder) if f.endswith(".pdf")])

    if not pdf_files:
        print("❌ No PDF files found in the folder.")
        return

    for pdf in pdf_files:
        merger.append(os.path.join(folder, pdf))

    merger.write(output_file)
    merger.close()
    print(f"✅ Merged PDFs saved as: {output_file}")

# Run the function
merge_pdfs(pdf_folder, output_pdf)
