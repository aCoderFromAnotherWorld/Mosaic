<script>
    (() => {
        const shareModal = document.getElementById('share-modal');
        if (!shareModal) {
            return;
        }

        const shareSearchInput = shareModal.querySelector('[data-share-search]');
        const sharePersonItems = Array.from(shareModal.querySelectorAll('[data-share-person]'));
        const shareCheckboxes = Array.from(shareModal.querySelectorAll('.share-person-checkbox'));
        const shareSubmitButton = shareModal.querySelector('[data-share-submit]');
        const shareFeedback = shareModal.querySelector('#share-feedback');
        const shareCloseElements = Array.from(shareModal.querySelectorAll('[data-share-close]'));
        const shareTriggers = Array.from(document.querySelectorAll('[data-share-trigger]'));

        if (!shareSubmitButton || !shareFeedback) {
            return;
        }

        let currentShareUrl = null;

        const setShareFeedback = (message, isSuccess = false) => {
            shareFeedback.textContent = message;
            shareFeedback.classList.remove('hidden', 'text-red-600', 'text-green-600');
            shareFeedback.classList.add(isSuccess ? 'text-green-600' : 'text-red-600');
        };

        const clearShareFeedback = () => {
            shareFeedback.textContent = '';
            shareFeedback.classList.add('hidden');
            shareFeedback.classList.remove('text-red-600', 'text-green-600');
        };

        const setShareSubmitting = (isSubmitting) => {
            shareSubmitButton.disabled = isSubmitting;
            shareSubmitButton.textContent = isSubmitting ? 'Sharing...' : 'Share Post';
        };

        const resetShareModal = () => {
            clearShareFeedback();
            if (shareSearchInput) {
                shareSearchInput.value = '';
            }
            sharePersonItems.forEach(item => item.classList.remove('hidden'));
            shareCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            setShareSubmitting(false);
        };

        const closeShareModal = () => {
            shareModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            resetShareModal();
        };

        const openShareModal = (shareUrl) => {
            currentShareUrl = shareUrl;
            resetShareModal();
            shareModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            if (shareSearchInput) {
                shareSearchInput.focus();
            }
        };

        window.closeShareModal = closeShareModal;

        shareCloseElements.forEach(element => {
            element.addEventListener('click', closeShareModal);
        });

        shareTriggers.forEach(trigger => {
            trigger.addEventListener('click', () => {
                const url = trigger.dataset.shareUrl;
                if (!url) {
                    console.error('Share URL missing for trigger:', trigger);
                    return;
                }
                openShareModal(url);
            });
        });

        if (shareSearchInput) {
            shareSearchInput.addEventListener('input', function () {
                const query = this.value.trim().toLowerCase();
                sharePersonItems.forEach(item => {
                    const name = item.getAttribute('data-name') || '';
                    item.classList.toggle('hidden', !name.includes(query));
                });
            });
        }

        shareSubmitButton.addEventListener('click', () => {
            if (!currentShareUrl) {
                setShareFeedback('Unable to share this post right now. Please try again.');
                return;
            }

            const selectedRecipients = shareCheckboxes
                .filter(checkbox => checkbox.checked)
                .map(checkbox => checkbox.value);

            if (selectedRecipients.length === 0) {
                setShareFeedback('Please select at least one person to share with.');
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (!csrfToken) {
                setShareFeedback('Unable to share right now. Please refresh the page and try again.');
                return;
            }

            setShareSubmitting(true);
            clearShareFeedback();

            fetch(currentShareUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ recipients: selectedRecipients }),
            })
                .then(async response => {
                    const data = await response.json();
                    if (!response.ok || !data.success) {
                        throw new Error(data.message || 'Failed to share post.');
                    }
                    setShareFeedback(data.message || 'Post shared successfully.', true);
                    setTimeout(() => {
                        closeShareModal();
                    }, 1200);
                })
                .catch(error => {
                    console.error(error);
                    setShareFeedback(error.message || 'Failed to share post. Please try again.');
                })
                .finally(() => {
                    setShareSubmitting(false);
                });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && !shareModal.classList.contains('hidden')) {
                closeShareModal();
            }
        });
    })();
</script>
