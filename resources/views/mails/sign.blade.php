@extends('layouts.master')

@section('css')
<style>
    :root {
        --primary-color: #0d6efd;
        --sidebar-width: 320px;
    }
    .editor-container {
        display: flex;
        background: #f8f9fa;
        min-height: calc(100vh - 150px);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .editor-sidebar {
        width: var(--sidebar-width);
        background: #fff;
        border-inline-end: 1px solid #e9ecef;
        padding: 20px;
        z-index: 100;
    }
    .editor-content {
        flex-grow: 1;
        padding: 30px;
        background: #525659; /* لون مشابه لـ Chrome PDF viewer */
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
        overflow-y:scroll;
        max-height:1400px;
    }
    .pdf-page-wrapper {
        background: white;
        margin-bottom: 25px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        position: relative;
        transition: transform 0.2s;
    }
    .pdf-page-wrapper.active {
        outline: 4px solid var(--primary-color);
        transform: scale(1.01);
    }
    .canvas-container {
        position: absolute !important;
        top: 0;
        left: 0;
        z-index: 10;
    }
    .tool-group {
        background: #f1f3f5;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
    }
    .tool-title {
        font-weight: 700;
        font-size: 0.9rem;
        margin-bottom: 12px;
        color: #495057;
        display: block;
    }
    #signaturePad {
        background: #fff;
        cursor: crosshair;
        border: 2px dashed #ccc;
        border-radius: 4px;
    }
    .loading-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(255,255,255,0.8);
        display: flex; align-items: center; justify-content: center;
        z-index: 9999;
    }
    i{
        padding-left:10px !important;
    }
</style>
@endsection

@section('page-header')
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <h4 class="content-title mb-0">تعديل الجواب</h4>
    </div>
</div>
@endsection

@section('content')
<div id="loader" class="loading-overlay d-none">
    <div class="spinner-border text-primary" role="status"></div>
    <span class="ms-2">جاري معالجة الملف...</span>
</div>

<div class="editor-container">
    {{-- Sidebar --}}
    <aside class="editor-sidebar">
        <div class="tool-group">
            <span class="tool-title"><i class="fas fa-font me-2"></i>إضافة نص</span>
            <input type="text" id="textInput" class="form-control mb-2" placeholder="اكتب النص هنا...">
            <div class="d-flex gap-2">
                <input type="color" id="textColor" class="form-control form-control-color w-25" value="#000000">
                <button class="btn btn-primary flex-grow-1" id="addTextBtn">إضافة</button>
            </div>
        </div>

        <div class="tool-group">
            <span class="tool-title"><i class="fas fa-signature me-2"></i>التوقيعات الجاهزة</span>
            <select id="signatureList" class="form-control">   
                <option value="">اختر توقيع...</option>
                @foreach($signatures as $sig)
                    <option value="{{ asset('storage/' . $sig->file_path) }}">{{ $sig->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="tool-group text-center">
            <span class="tool-title"><i class="fas fa-pen-nib me-2"></i>توقيع يدوي</span>
            <button class="btn btn-outline-success w-100" id="openSignaturePad">فتح لوحة التوقيع</button>
        </div>

        <hr>

        <div class="d-grid gap-2">
            <button class="btn btn-warning text-white" id="undoBtn"><i class="fas fa-undo me-2"></i>تراجع</button>
            <button class="btn btn-danger" id="clearBtn"><i class="fas fa-trash-alt me-2"></i>مسح الصفحة</button>
            <button class="btn btn-dark btn-lg mt-3" id="saveBtn"><i class="fas fa-save me-2"></i>حفظ وإرسال</button>
        </div>
    </aside>

    {{-- Viewer Area --}}
    <main class="editor-content" id="pdfContainer">
        </main>
</div>

{{-- Signature Modal --}}
<div class="modal fade" id="signatureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">توقيع جديد</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <canvas id="signaturePad" width="450" height="200"></canvas>
                <div class="mt-2 text-muted small">ارسم توقيعك بالماوس أو اللمس</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" id="clearPad">مسح</button>
                <button type="button" class="btn btn-success" id="saveSignature">تأكيد التوقيع</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf-lib/1.17.1/pdf-lib.min.js"></script>

<script>
    // الإعدادات العامة
    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';
    
    let activeCanvas = null;
    const fabricCanvases = [];
    const pdfUrl = @json($fileUrl);
    let sigPad;

    // 1. تحميل الـ PDF ورسمه
    async function initEditor() {
        const loadingTask = pdfjsLib.getDocument(pdfUrl);
        const pdf = await loadingTask.promise;
        const container = document.getElementById('pdfContainer');

        for (let i = 1; i <= pdf.numPages; i++) {
            const page = await pdf.getPage(i);
            const viewport = page.getViewport({ scale: 1.5 });

            // إنشاء العناصر
            const wrapper = document.createElement('div');
            wrapper.className = 'pdf-page-wrapper';
            wrapper.id = `page-wrapper-${i}`;
            wrapper.style.width = `${viewport.width}px`;
            wrapper.style.height = `${viewport.height}px`;

            const pdfCanvas = document.createElement('canvas');
            const fabricCanvasEl = document.createElement('canvas');
            
            wrapper.appendChild(pdfCanvas);
            wrapper.appendChild(fabricCanvasEl);
            container.appendChild(wrapper);

            // رسم الـ PDF الأصلي كخلفية
            const context = pdfCanvas.getContext('2d');
            pdfCanvas.width = viewport.width;
            pdfCanvas.height = viewport.height;
            await page.render({ canvasContext: context, viewport: viewport }).promise;

            // تحويل الكانفاس العلوي لـ Fabric.js
            const fCanvas = new fabric.Canvas(fabricCanvasEl, {
                width: viewport.width,
                height: viewport.height,
                selection: true
            });

            fabricCanvases.push(fCanvas);

            // تفعيل الصفحة بمجرد الضغط عليها
            fCanvas.on('mouse:down', () => setActivePage(fCanvas, wrapper));

            // تعيين أول صفحة كنشطة افتراضياً
            if (i === 1) setActivePage(fCanvas, wrapper);
        }
    }

    function setActivePage(canvas, wrapper) {
        activeCanvas = canvas;
        document.querySelectorAll('.pdf-page-wrapper').forEach(el => el.classList.remove('active'));
        wrapper.classList.add('active');
    }

    // 2. إضافة النص
    document.getElementById('addTextBtn').addEventListener('click', () => {
        const text = document.getElementById('textInput').value;
        const color = document.getElementById('textColor').value;
        
        if (!text) return;
        if (!activeCanvas) return alert("من فضلك اختر صفحة أولاً");

        const textObj = new fabric.Textbox(text, {
            left: 50,
            top: 50,
            fill: color,
            fontSize: 24,
            width: 200
        });

        activeCanvas.add(textObj).setActiveObject(textObj);
        activeCanvas.renderAll();
    });

    // 3. التوقيعات الجاهزة
    document.getElementById('signatureList').addEventListener('change', function() {
        if (!this.value || !activeCanvas) return;

        fabric.Image.fromURL(this.value, (img) => {
            img.scaleToWidth(150);
            activeCanvas.add(img).centerObject(img).setActiveObject(img);
            activeCanvas.renderAll();
        }, { crossOrigin: 'anonymous' });
        
        this.value = ''; // Reset select
    });

    // 4. التوقيع اليدوي (Signature Pad)
    document.getElementById('openSignaturePad').addEventListener('click', () => {
        const myModal = new bootstrap.Modal(document.getElementById('signatureModal'));
        myModal.show();
        
        const canvas = document.getElementById('signaturePad');
        if (!sigPad) {
            sigPad = new SignaturePad(canvas);
        } else {
            sigPad.clear();
        }
    });

    document.getElementById('clearPad').addEventListener('click', () => sigPad && sigPad.clear());

    document.getElementById('saveSignature').addEventListener('click', () => {
        if (sigPad.isEmpty()) return;

        const dataURL = sigPad.toDataURL();
        fabric.Image.fromURL(dataURL, (img) => {
            img.scaleToWidth(150);
            activeCanvas.add(img).centerObject(img).setActiveObject(img);
            activeCanvas.renderAll();
        });

        bootstrap.Modal.getInstance(document.getElementById('signatureModal')).hide();
    });

    // 5. أدوات التحكم (Undo & Clear)
    document.getElementById('undoBtn').addEventListener('click', () => {
        if (!activeCanvas) return;
        const objects = activeCanvas.getObjects();
        if (objects.length > 0) {
            activeCanvas.remove(objects[objects.length - 1]);
        }
    });

    document.getElementById('clearBtn').addEventListener('click', () => {
        if (activeCanvas && confirm("هل أنت متأكد من مسح كافة الإضافات في هذه الصفحة؟")) {
            activeCanvas.clear();
        }
    });

    // 6. الحفظ النهائي (Logic الاحترافي)
    document.getElementById('saveBtn').addEventListener('click', async () => {
        document.getElementById('loader').classList.remove('d-none');
        
        try {
            const { PDFDocument } = PDFLib;
            const existingPdfBytes = await fetch(pdfUrl).then(res => res.arrayBuffer());
            const pdfDoc = await PDFDocument.load(existingPdfBytes);
            const pages = pdfDoc.getPages();

            for (let i = 0; i < fabricCanvases.length; i++) {
                const fCanvas = fabricCanvases[i];
                if (fCanvas.getObjects().length === 0) continue;

                // تحويل طبقة الرسم لـ PNG شفافة
                const dataUrl = fCanvas.toDataURL({ format: 'png', multiplier: 2 });
                const pngImage = await pdfDoc.embedPng(dataUrl);
                
                const page = pages[i];
                const { width, height } = page.getSize();

                // رسم طبقة الإضافات فوق صفحة الـ PDF
                page.drawImage(pngImage, {
                    x: 0,
                    y: 0,
                    width: width,
                    height: height,
                });
            }

            const pdfBytes = await pdfDoc.save();
            const blob = new Blob([pdfBytes], { type: 'application/pdf' });
            
            // إرسال الملف للسيرفر
            const formData = new FormData();
            formData.append('file', blob, 'signed_document.pdf');
            formData.append('_token', "{{ csrf_token() }}");

            const response = await fetch("{{ route('mail.sign.save', $file->id) }}", {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (result.redirect) {
                window.location.href = result.redirect;
            } else {
                alert("تم الحفظ بنجاح");
                location.reload();
            }

        } catch (error) {
            console.error(error);
            alert("حدث خطأ أثناء الحفظ");
        } finally {
            document.getElementById('loader').classList.add('d-none');
        }
    });

    // تشغيل المحرر
    initEditor();
</script>
@endsection