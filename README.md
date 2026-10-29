# PDF to XLS Converter

A lightweight web‑based tool that converts a PDF invoice (or similar document) to an Excel (`.xlsx`) file using **Aspose.PDF for Python via .NET**.

## Features
- Command‑line conversion (`converter.py`) that takes an input PDF and output XLSX path.
- Automatic removal of the Aspose evaluation watermark from the generated workbook.
- Simple PHP front‑end (`index.php` / `process.php`) for uploading PDFs through a web browser.
- Uploaded PDFs are stored in the `uploads/` folder; generated Excel files are placed in `outputs/`.

## Technology Stack
- **Python 3** (`converter.py`)
- **Aspose.PDF for Python via .NET** – licensed library for accurate PDF‑to‑Excel conversion.
- **OpenPyXL** – used to clean up watermark rows.
- **PHP** – minimal web interface.

## Prerequisites
1. **Python 3.8+**
2. **Java Runtime** – required by Aspose.PDF.
3. Install the Python dependencies:
   ```powershell
   pip install aspose-pdf openpyxl
   ```
4. Ensure the Aspose license file (`Aspose.PDF.lic`) is placed in the script directory if you have a commercial license (otherwise the free evaluation version works with a watermark that the script tries to remove).

## Local Usage (CLI)
```powershell
python converter.py <input.pdf> <output.xlsx>
```
Example:
```powershell
python converter.py "c:\\laragon\\www\\pdf to xls\\uploads\\sample.pdf" "c:\\laragon\\www\\pdf to xls\\outputs\\sample.xlsx"
```
The script will print `Success: <output.xlsx>` on success.

## Web Usage
1. Place the project folder under your web server root (e.g., `c:\\laragon\\www\\pdf to xls`).
2. Access `http://localhost/pdf%20to%20xls/` in a browser – you will see a simple upload form.
3. Choose a PDF file and submit; the server will run `converter.py` and return a download link for the generated Excel file.

## Security & Limitations
- No authentication – intended for local or trusted environments only.
- The script deletes the first few rows of each sheet that contain the word **Evaluation** or **Aspose** to strip the watermark; this may inadvertently remove legitimate data if your PDFs contain those words in the first rows.
- Large PDFs may take several seconds to process.
- The PHP front‑end relies on the `exec` function to run the Python script; ensure the web server has permission to execute it.

## Project Structure
```
pdf to xls/
├─ converter.py      # CLI conversion script
├─ index.php         # Upload form
├─ process.php       # Handles upload & conversion
├─ uploads/          # Uploaded PDFs (git‑ignored)
├─ outputs/          # Generated XLSX files (git‑ignored)
├─ .gitignore        # Ignored files/folders
└─ README.md         # This documentation
```

## License
This is a personal utility project. Feel free to fork, modify, and use it as you wish.
