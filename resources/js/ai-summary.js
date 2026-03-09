document.addEventListener('DOMContentLoaded', function() {
    const suggestBtn = document.getElementById('suggest-summary');
    const summaryArea = document.getElementById('summary');

    if (suggestBtn) {
        suggestBtn.addEventListener('click', async function() {
            // TinyMCE support
            const content = typeof tinymce !== 'undefined' ? tinymce.get(0).getContent() : document.querySelector('.editor').value;

            if (!content || content.length < 100) {
                alert('Please write some content first (at least 100 characters).');
                return;
            }

            suggestBtn.innerText = '⌛ Thinking...';
            suggestBtn.disabled = true;

            try {
                const response = await fetch('{{ route("admin.ai.summarize") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ content: content })
                });

                const data = await response.json();
                if (data.summary) {
                    summaryArea.value = data.summary;
                } else {
                    alert('Could not generate summary.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while generating the summary.');
            } finally {
                suggestBtn.innerText = '✨ Suggest with AI';
                suggestBtn.disabled = false;
            }
        });
    }
});
