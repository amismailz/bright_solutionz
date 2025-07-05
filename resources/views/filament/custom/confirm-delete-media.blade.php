<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/basiclightbox@5.0.4/dist/basicLightbox.min.css">
<script src="https://cdn.jsdelivr.net/npm/basiclightbox@5.0.4/dist/basicLightbox.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .filepond--root .filepond--list-scroller
    {
        mask: linear-gradient(180deg, #000 calc(100% - .5em), transparent);
        overflow-x: hidden;
        height: 218px;
        overflow-y: scroll;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const observer = new MutationObserver(() => {
            document.querySelectorAll('.filepond--action-revert-item-processing').forEach(btn => {
                if (!btn.dataset.confirmAttached) {
                    btn.dataset.confirmAttached = true;

                    const handler = function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        Swal.fire({
                            title: 'هل أنت متأكد؟',
                            text: 'لن تتمكن من التراجع عن الحذف!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'نعم، احذف',
                            cancelButtonText: 'إلغاء',
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                btn.removeEventListener('pointerdown', handler);

                                btn.click();
                            }
                        });
                    };

                    btn.addEventListener('pointerdown', handler, true);
                }
            });
            document.querySelectorAll('.filepond--action-remove-item').forEach(btn => {
                if (!btn.dataset.confirmAttached) {
                    btn.dataset.confirmAttached = true;

                    const handler = function (e) {
                        e.preventDefault();
                        e.stopPropagation();
                        e.stopImmediatePropagation();

                        Swal.fire({
                            title: 'هل أنت متأكد؟',
                            text: 'لن تتمكن من التراجع عن الحذف!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'نعم، احذف',
                            cancelButtonText: 'إلغاء',
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                btn.removeEventListener('pointerdown', handler);

                                btn.click();
                            }
                        });
                    };

                    btn.addEventListener('pointerdown', handler, true);
                }
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const observer = new MutationObserver(() => {
            document.querySelectorAll('.filepond--file:not([data-preview-enhanced])').forEach(fileEl => {
                const canvasWrapper = fileEl.querySelector('.filepond--image-preview-wrapper, .filepond--image-bitmap');
                if (!canvasWrapper || fileEl.dataset.previewEnhanced) return;

                const canvas = canvasWrapper.querySelector('canvas');
                if (!canvas) return;

                const dataURL = canvas.toDataURL('image/png');
                if (!dataURL) return;

                // الصورة المعروضة في FilePond
                const previewImg = document.createElement('img');
                previewImg.src = dataURL;
                previewImg.style.maxWidth = '100%';
                previewImg.style.cursor = 'zoom-in';

                // استبدال canvas بالصورة
                canvasWrapper.innerHTML = '';
                canvasWrapper.appendChild(previewImg);

                fileEl.dataset.previewEnhanced = 'true';

                // عند الضغط افتح basicLightbox
                previewImg.addEventListener('click', () => {
                    basicLightbox.create(`
                        <img src="${dataURL}" style="max-width: 100%; max-height: 90vh;" />
                    `).show();
                });
            });
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true,
        });
    });
</script>
