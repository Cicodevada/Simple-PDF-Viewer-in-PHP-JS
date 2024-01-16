<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizador de PDF</title>
    <style>
    body {
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    #viewer {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
    }
    #loading-message {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 20px;
        border: 1px solid #ccc;
        display: none;
        z-index: 9999;
    }
    </style>
</head>
<body>
    <div id="loading-message">Carregando...</div>
    <div id="viewer"></div>

    <!-- Inclua o pdf.js do CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.9.359/pdf.min.js"></script>
    
    <script>
        var pdfPath = decodeURIComponent('<?php echo $_GET["pdf"];?>');
        var loadingMessage = document.getElementById('loading-message');

        async function loadPDF(pdfPath) {
            loadingMessage.style.display = 'block';

            const pdfDoc = await pdfjsLib.getDocument(pdfPath).promise;
            const numPages = pdfDoc.numPages;

            for (let pageNum = 1; pageNum <= numPages; pageNum++) {
                const page = await pdfDoc.getPage(pageNum);
                const scale = 1.5;
                const viewport = page.getViewport({ scale });

                const canvas = document.createElement('canvas');
                canvas.style.display = 'block';
                canvas.style.margin = '0 auto';
                canvas.height = viewport.height;
                canvas.width = viewport.width;
                canvas.style.width = '98%';
                canvas.style.border = '1px solid black';
                canvas.style.marginBottom = '5px';

                document.getElementById('viewer').appendChild(canvas);

                await page.render({ canvasContext: canvas.getContext('2d'), viewport });
            }

            loadingMessage.style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadPDF(pdfPath);
        });
    </script>
</body>
</html>
