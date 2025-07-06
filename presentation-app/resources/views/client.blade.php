<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client View</title>
    @vite('resources/js/app.js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            background-color: black;
        }

        #slide, #video-slide {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        #video-slide {
            display: none;
        }
    </style>
</head>
<body>
<img id="slide" src="" alt="Slide" />
<video id="video-slide" autoplay muted loop playsinline></video>

<script>
    function generateUUID() {
        // Fallback UUID v4 generator
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
            const r = Math.random() * 16 | 0;
            const v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    if (!localStorage.getItem('device_id')) {
        localStorage.setItem('device_id', generateUUID());
    }
    const deviceId = localStorage.getItem('device_id');

    // Function to check if URL is an MP4 file
    function isMP4(url) {
        return url.toLowerCase().endsWith('.mp4');
    }

    // Function to update slide content based on URL
    function updateSlide(url) {
        const imgElement = document.getElementById('slide');
        const videoElement = document.getElementById('video-slide');

        if (isMP4(url)) {
            // It's a video
            videoElement.src = url;
            videoElement.style.display = 'block';
            imgElement.style.display = 'none';
        } else {
            // It's an image
            imgElement.src = url;
            imgElement.style.display = 'block';
            videoElement.style.display = 'none';
        }
    }

    // Register device with backend
    fetch('/register-device', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            device_id: deviceId,
            name: navigator.userAgent
        })
    })
        .then(response => response.json())
        .then(data => {
            console.log('Device registered:', data.device);
            if (data.device.slide_url) {
                updateSlide(data.device.slide_url);
            }
        })
        .catch(e => console.error('Device registration failed', e));

    // Listen for slide update events
    document.addEventListener('DOMContentLoaded', () => {
        window.Echo.channel('slides')
            .listen('SlideChanged', (e) => {
                console.log(e)
                if (e.mac === deviceId) {
                    updateSlide(e.slideUrl);
                }
            });
    });
</script>
</body>
</html>
