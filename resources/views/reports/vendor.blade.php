<x-app-layout>
    <div
        class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
        <div class="w-full max-w-4xl mx-auto">
            @include('navigation.header')

            <form id="feedback-form" class="bg-white rounded-lg w-full max-w-md mx-auto p-6 shadow-lg">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Give Feedback</h2>

                <!-- Rating Selection -->
                <div class="mb-6">
                    <p class="text-gray-700 mb-2">What do you think of the editing tool?</p>
                    <div class="flex justify-between gap-2">
                        <button type="button" class="rating-button p-3 rounded-full transition" data-rating="1"
                            aria-label="Very Sad">
                            🙁
                        </button>
                        <button type="button" class="rating-button p-3 rounded-full transition" data-rating="2"
                            aria-label="Sad">
                            😞
                        </button>
                        <button type="button" class="rating-button p-3 rounded-full transition" data-rating="3"
                            aria-label="Neutral">
                            😐
                        </button>
                        <button type="button" class="rating-button p-3 rounded-full transition" data-rating="4"
                            aria-label="Happy">
                            😊
                        </button>
                        <button type="button" class="rating-button p-3 rounded-full transition" data-rating="5"
                            aria-label="Very Happy">
                            😃
                        </button>
                    </div>
                    <input type="hidden" id="rating-value" name="rating" value="">
                </div>

                <!-- Feedback Text -->
                <div class="mb-6">
                    <label for="feedback-text" class="block text-gray-700 mb-2">Your thoughts:</label>
                    <textarea id="feedback-text" name="comment" class="w-full border rounded-lg p-3 text-gray-700" rows="4"
                        placeholder="Share your thoughts..."></textarea>
                </div>

                <!-- Follow-up Permission -->
                <div class="mb-6">
                    <p class="text-gray-700 mb-2">May we follow up on your feedback?</p>
                    <div class="flex gap-4">
                        <label class="flex items-center cursor-pointer space-x-2">
                            <input type="radio" name="follow_up" value="yes" checked>
                            <span>Yes</span>
                        </label>
                        <label class="flex items-center cursor-pointer space-x-2">
                            <input type="radio" name="follow_up" value="no">
                            <span>No</span>
                        </label>
                    </div>
                </div>


                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 bg-blue-600 text-white py-2 rounded-md hover:bg-blue-700 transition">Send</button>
                    <button type="button" id="cancel-btn"
                        class="flex-1 border border-gray-300 text-gray-700 py-2 rounded-md hover:bg-gray-100 transition">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const feedbackForm = document.getElementById('feedback-form');
            if (!feedbackForm) {
                console.error("Feedback form not found!");
                return;
            }

            const ratingButtons = document.querySelectorAll('.rating-button');
            const ratingInput = document.createElement('input');
            ratingInput.type = 'hidden';
            ratingInput.name = 'rating';
            feedbackForm.appendChild(ratingInput);

            ratingButtons.forEach(button => {
                button.addEventListener('click', () => {
                    ratingButtons.forEach(btn => btn.classList.remove('bg-blue-500', 'text-white',
                        'scale-110'));
                    button.classList.add('bg-blue-500', 'text-white',
                        'scale-110'); // Darker blue and slight enlargement
                    ratingInput.value = button.dataset.rating;
                });
            });

            feedbackForm.addEventListener('submit', (e) => {
                e.preventDefault();

                const followUpValue = document.querySelector('input[name="follow_up"]:checked');
                if (!followUpValue) {
                    alert("Please select whether we can follow up.");
                    return;
                }

                const formData = new FormData(feedbackForm);
                formData.append('follow-up', followUpValue.value);

                // Debugging: log form data
                for (let pair of formData.entries()) {
                    console.log(pair[0], pair[1]); // Check if follow-up is included
                }

                fetch("{{ route('reports.store') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                        feedbackForm.reset();
                        resetRatingSelection(); // Reset rating buttons
                    })
                    .catch(error => console.error('Error:', error));
            });

            // Reset form when cancel button is clicked
            document.getElementById('cancel-btn').addEventListener('click', () => {
                feedbackForm.reset();
                resetRatingSelection(); // Also reset rating buttons
            });

            function resetRatingSelection() {
                ratingButtons.forEach(btn => btn.classList.remove('bg-blue-500', 'text-white', 'scale-110'));
                ratingInput.value = ""; // Clear rating input
            }
        });
    </script>

</x-app-layout>
