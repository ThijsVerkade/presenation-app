<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connected Devices</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 1rem; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; }
        th { background-color: #eee; }
    </style>
</head>
<body>
<h1>Connected Devices</h1>

@if (session('success'))
    <p style="color: green">{{ session('success') }}</p>
@endif

<h2>Unregistered Devices</h2>
<table>
    <tr>
        <th>MAC</th>
        <th>Action</th>
    </tr>
    @foreach ($devices as $device)
        <tr>
            <td>{{ $device['device_id'] }}</td>
            <td>
                <form method="POST" action="{{ route('admin.devices.register') }}">
                    @csrf
                    <input type="hidden" name="mac" value="{{ $device['device_id'] }}">
                    <input type="text" name="name" placeholder="Optional name">
                    <button type="submit">Register</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>

<h2>Registered Devices</h2>
<table>
    <tr>
        <th>Name</th>
        <th>MAC</th>
        <th>Registered At</th>
        <th>Send Slide</th>
    </tr>
    @foreach ($devices as $r)
        <tr>
            <td>{{ $r->name ?? '-' }}</td>
            <td>{{ $r->device_id }}</td>
            <td>{{ $r->created_at }}</td>
            <td>
                <form method="POST" action="{{ route('admin.devices.send') }}" class="inline">
                    @csrf
                    <input type="hidden" name="mac" value="{{ $r->device_id }}">
                    <select  name="slide">
                        <option value="">Select Slide</option>
                        <option value="/media/slide1.jpg">media/slide1.jpg</option>
                        <option value="/media/slide2.jpg">media/slide2.jpg</option>
                        <option value="/media/slide3.jpg">media/slide3.jpg</option>
                        <option value="/media/slide4.jpg">media/slide4.jpg</option>
                    </select>
                    <input type="text" name="slide" placeholder="/media/slide1.jpg" required>
                    <button type="submit">Send</button>
                </form>
            </td>
        </tr>
    @endforeach
</table>
</body>
</html>
