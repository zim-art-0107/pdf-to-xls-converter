<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF to Excel Converter Pro</title>
    <style>
        :root {
            --bg-color: #0f172a;
            --card-bg: #1e293b;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --accent-primary: #3b82f6;
            --accent-hover: #2563eb;
            --success: #10b981;
            --error: #ef4444;
            --border: #334155;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(circle at 50% 0%, #1e293b 0%, transparent 70%);
        }

        .container {
            width: 100%;
            max-width: 600px;
            padding: 2rem;
        }

        .card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 3rem 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--border);
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .upload-area {
            border: 2px dashed var(--border);
            border-radius: 16px;
            padding: 4rem 2rem;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            background: rgba(15, 23, 42, 0.3);
        }

        .upload-area:hover, .upload-area.dragover {
            border-color: var(--accent-primary);
            background: rgba(59, 130, 246, 0.05);
        }

        .upload-icon {
            width: 64px;
            height: 64px;
            margin: 0 auto 1rem;
            background: var(--accent-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 0 20px rgba(59, 130, 246, 0.4);
        }

        .upload-icon svg {
            width: 32px;
            height: 32px;
        }

        .file-input {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .btn {
            background: var(--accent-primary);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 2rem;
            width: 100%;
            display: none;
        }

        .btn:hover {
            background: var(--accent-hover);
        }

        .file-name {
            margin-top: 1rem;
            font-weight: 500;
            color: var(--accent-primary);
            display: none;
        }

        .loading {
            display: none;
            margin-top: 2rem;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(255, 255, 255, 0.1);
            border-left-color: var(--accent-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1rem;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        #result {
            margin-top: 2rem;
            padding: 1rem;
            border-radius: 12px;
            display: none;
            font-weight: 500;
        }

        .success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .error {
            background: rgba(239, 68, 68, 0.1);
            color: var(--error);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .download-btn {
            display: inline-block;
            background: var(--success);
            color: white;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            margin-top: 1rem;
            font-weight: 600;
            transition: opacity 0.3s;
        }
        .download-btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>PDF to Excel Converter</h1>
        <p>Extract tables from your PDFs into fully editable Excel spreadsheets instantly. 100% free and runs locally on your machine.</p>

        <form id="uploadForm" enctype="multipart/form-data">
            <div class="upload-area" id="dropZone">
                <div class="upload-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <h3 style="margin-bottom: 0.5rem; color: var(--text-primary);">Drag & Drop your PDF here</h3>
                <span style="color: var(--text-secondary); font-size: 0.9rem;">or click to browse files</span>
                <input type="file" name="pdf_file" id="pdfFileInput" class="file-input" accept="application/pdf">
                <div class="file-name" id="fileNameDisplay"></div>
            </div>

            <button type="submit" class="btn" id="submitBtn">Convert to Excel</button>
        </form>

        <div class="loading" id="loadingSection">
            <div class="spinner"></div>
            <p>Analyzing PDF and extracting tables... Please wait.</p>
        </div>

        <div id="result"></div>
    </div>
</div>

<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('pdfFileInput');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const submitBtn = document.getElementById('submitBtn');
    const uploadForm = document.getElementById('uploadForm');
    const loadingSection = document.getElementById('loadingSection');
    const resultDiv = document.getElementById('result');

    // Drag and drop visual cues
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
    });

    // Handle file selection
    fileInput.addEventListener('change', handleFileSelect);
    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if(files.length > 0) {
            fileInput.files = files;
            handleFileSelect();
        }
    });

    function handleFileSelect() {
        if(fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if(file.type !== 'application/pdf') {
                showResult('Please select a valid PDF file.', 'error');
                submitBtn.style.display = 'none';
                return;
            }
            fileNameDisplay.textContent = 'Selected: ' + file.name;
            fileNameDisplay.style.display = 'block';
            submitBtn.style.display = 'block';
            resultDiv.style.display = 'none';
        }
    }

    // Handle form submission
    uploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        if(fileInput.files.length === 0) return;

        // UI updates
        submitBtn.style.display = 'none';
        loadingSection.style.display = 'block';
        resultDiv.style.display = 'none';

        const formData = new FormData();
        formData.append('pdf_file', fileInput.files[0]);

        try {
            const response = await fetch('process.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if(data.success) {
                resultDiv.className = 'success';
                resultDiv.innerHTML = `
                    <p>Conversion successful!</p>
                    <a href="${data.file_url}" class="download-btn" download>Download Excel File</a>
                `;
            } else {
                throw new Error(data.message || 'Conversion failed due to an unknown error.');
            }
        } catch (error) {
            resultDiv.className = 'error';
            resultDiv.innerHTML = `<p>${error.message}</p>`;
            submitBtn.style.display = 'block';
        } finally {
            loadingSection.style.display = 'none';
            resultDiv.style.display = 'block';
        }
    });

    function showResult(message, type) {
        resultDiv.className = type;
        resultDiv.innerHTML = `<p>${message}</p>`;
        resultDiv.style.display = 'block';
    }
</script>

</body>
</html>
