<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Live Like Count</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f0f0f0;
        }
        .container {
            text-align: center;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        #like-count {
            font-size: 2em;
            margin-bottom: 20px;
            color: #333;
        }
        button {
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Live Like Count</h1>
        <div id="like-count">0</div>
        <button id="like-button">Like</button>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            const likeCountElement = document.getElementById('like-count');
            const likeButton = document.getElementById('like-button');

            // Listen for the event using Laravel Echo
            window.Echo.channel('public-like-channel')
                .listen('.like.updated', (e) => { // Use the broadcastAs name with a dot prefix
                    likeCountElement.textContent = e.likeCount;
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
                    }

                } catch (error) {
                    console.error('Error sending like request:', error);
                }
            });
        });
    </script>
</body>
</html>
