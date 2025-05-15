<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Your App')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <style>
        /* Your existing CSS styles */
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

        /* Styling for toast container for stacking */
        #toast-container {
            position: fixed;
            bottom: 1.25rem; /* Tailwind default for bottom-5 */
            right: 1.25rem; /* Tailwind default for right-5 */
            z-index: 1000; /* Ensure it's above other content */
            display: flex;
            flex-direction: column;
            gap: 0.5rem; /* Space between stacked toasts */
        }

        .toast {
            /* Base styles for your toast, initially hidden */
            display: none; /* Initially hidden */
            opacity: 0; /* For smooth transition */
            transition: opacity 0.3s ease-in-out;
        }

        .toast.show {
            display: flex; /* Show the toast */
            opacity: 1; /* Fade it in */
        }
    </style>
    @stack('styles')
</head>
<body>

    @yield('content')

    <!-- Toast Container for Stacking -->
    <div id="toast-container">
        {{-- Toasts will be added here dynamically --}}
    </div>
    <!-- End Toast Container -->

    @vite('resources/js/app.js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script type="module">
        // Function to show a toast notification
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');

            // Create a new toast element
            const toast = document.createElement('div');
            toast.classList.add('toast', 'flex', 'items-center', 'w-full', 'max-w-xs', 'p-4', 'mb-4', 'text-gray-500', 'bg-white', 'rounded-lg', 'shadow-sm', 'dark:text-gray-400', 'dark:bg-gray-800');

            let iconSvg = '';
            let iconBgClass = '';
            let iconTextColorClass = '';

            if (type === 'success') {
                iconSvg = `
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                    </svg>
                `;
                iconBgClass = 'bg-green-100 dark:bg-green-800';
                iconTextColorClass = 'text-green-500 dark:text-green-200';
            }
            // You can add more conditions for other toast types (error, warning, info)
            // else if (type === 'error') { ... }

            toast.innerHTML = `
                <div class="inline-flex items-center justify-center shrink-0 w-8 h-8 rounded-lg ${iconBgClass} ${iconTextColorClass}">
                    ${iconSvg}
                    <span class="sr-only">${type} icon</span>
                </div>
                <div class="ms-3 text-sm font-normal">${message}</div>
                <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700" aria-label="Close">
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            `;

            // Append the new toast to the container
            toastContainer.appendChild(toast);

            // Use Flowbite's Dismiss component if you want its click-to-dismiss functionality
            // Make sure Flowbite is initialized if not already done.
            // const dismiss = new Dismiss(toast.querySelector('[data-dismiss-target]'), toast); // If you add data-dismiss-target

            // Add 'show' class to trigger the fade-in transition
            setTimeout(() => {
                toast.classList.add('show');
            }, 10); // Small delay to allow element to be added before transition

            // Auto-hide the toast after a few seconds
            setTimeout(() => {
                toast.classList.remove('show'); // Start fade-out
                // Remove the toast from the DOM after transition
                toast.addEventListener('transitionend', () => {
                    toast.remove();
                });
            }, 5000); // Hide after 5 seconds (adjust as needed)

            // Add event listener for the close button
            toast.querySelector('button[aria-label="Close"]').addEventListener('click', () => {
                toast.classList.remove('show'); // Start fade-out
                toast.addEventListener('transitionend', () => {
                    toast.remove();
                });
            });
        }

        // Make the showToast function globally available
        window.showToast = showToast;

        // Your existing Echo and like button logic can be specific to the page
        // or moved here if it's a core part of your application.
        document.addEventListener('DOMContentLoaded', () => {
            // Listen for the event using Laravel Echo
            window.Echo.channel('public-like-channel')
                .listen('.like.updated', (e) => {
                    console.log("like +1");
                    // Show a success toast
                    showToast('Like count updated!');
                });
        });
    </script>
    @stack('scripts')
</body>
</html>
