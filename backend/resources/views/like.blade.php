@extends('layouts.app')

@section('title', 'Live Like Count')

@section('content')
    <div class="container">
        <h1>Live Like Count</h1>
        <div id="like-count">0</div>
        <button id="like-button">Like</button>
    </div>
@endsection

@push('scripts')
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            const likeCountElement = document.getElementById('like-count');
            const likeButton = document.getElementById('like-button');

            // Note: The Echo listener for the toast is now in the layout.
            // You can keep this one if you also need to update the count directly here.
             window.Echo.channel('public-like-channel')
                .listen('.like.updated', (e) => { // Use the broadcastAs name with a dot prefix
                    likeCountElement.textContent = e.likeCount;
                    // The showToast is called from the layout script
                });


            // Handle the button click
            likeButton.addEventListener('click', async () => {
                try {
                    const response = await fetch('/like', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (data.status !== 'success') {
                        console.error('Failed to like:', data);
                        // Optionally show an error toast
                        // window.showToast('Failed to like item.', 'error');
                    }

                } catch (error) {
                    console.error('Error sending like request:', error);
                    // Optionally show an error toast
                    // window.showToast('An error occurred while liking.', 'error');
                }
            });
        });
    </script>
@endpush
