<div id="media-picker-modal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.8); z-index: 10000; padding: 2rem;">
    <div style="background: var(--bg-card); max-width: 1000px; margin: 0 auto; border-radius: 1rem; height: 80vh; display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--border);">
        <div style="padding: 1.5rem; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0;">Select Media</h3>
            <button type="button" onclick="closeMediaPicker()" style="background: none; border: none; color: var(--text-secondary); font-size: 1.5rem; cursor: pointer;">×</button>
        </div>
        
        <div id="media-picker-content" style="flex: 1; overflow-y: auto; padding: 1.5rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 1rem;">
            <!-- Media items will be loaded here via JS -->
            <div style="grid-column: 1/-1; text-align: center; padding: 3rem;">Loading gallery...</div>
        </div>

        <div style="padding: 1rem; border-top: 1px solid var(--border); display: flex; justify-content: flex-end; gap: 1rem; background: var(--bg-dark);">
            <button type="button" onclick="closeMediaPicker()" class="btn btn-outline">Cancel</button>
            <button type="button" id="confirm-media-selection" class="btn btn-primary" disabled>Select Image</button>
        </div>
    </div>
</div>

<script>
    let selectedMediaId = null;
    let selectedMediaUrl = null;

    function openMediaPicker() {
        document.getElementById('media-picker-modal').style.display = 'block';
        loadMediaItems();
    }

    function closeMediaPicker() {
        document.getElementById('media-picker-modal').style.display = 'none';
    }

    async function loadMediaItems() {
        const container = document.getElementById('media-picker-content');
        try {
            const response = await fetch('{{ route('admin.api.media.index') }}');
            const items = await response.json();
            
            if (items.length === 0) {
                container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: var(--text-secondary);">No media found. Upload some assets first.</div>';
                return;
            }

            container.innerHTML = items.map(item => `
                <div class="media-pick-item" onclick="selectMedia(this, ${item.id}, '${item.url}')" style="cursor: pointer; border: 2px solid transparent; border-radius: 0.5rem; overflow: hidden; height: 120px; background: #1e293b; display: flex; align-items: center; justify-content: center; position: relative;">
                    <img src="${item.url}" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                </div>
            `).join('');
        } catch (error) {
            container.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 3rem; color: var(--danger);">Failed to load media.</div>';
        }
    }

    function selectMedia(el, id, url) {
        document.querySelectorAll('.media-pick-item').forEach(item => item.style.borderColor = 'transparent');
        el.style.borderColor = 'var(--accent)';
        selectedMediaId = id;
        selectedMediaUrl = url;
        document.getElementById('confirm-media-selection').disabled = false;
    }

    document.getElementById('confirm-media-selection').onclick = function() {
        if (selectedMediaId) {
            // This event will be handled by the parent form
            window.dispatchEvent(new CustomEvent('mediaSelected', { detail: { id: selectedMediaId, url: selectedMediaUrl } }));
            closeMediaPicker();
        }
    };
</script>

<style>
    .media-pick-item:hover { border-color: rgba(59, 130, 246, 0.5) !important; }
</style>
