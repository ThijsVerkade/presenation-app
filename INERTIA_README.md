# Inertia.js with Vue.js Setup

This document provides information about the Inertia.js and Vue.js setup in this Laravel application.

## Overview

Inertia.js has been integrated with Vue.js to provide a modern, SPA-like experience while maintaining the simplicity of server-side routing. This setup allows you to build your frontend using Vue.js components while leveraging Laravel's routing and controllers.

## Directory Structure

- `resources/js/Pages/` - Contains Vue components that correspond to pages in your application
- `resources/views/app.blade.php` - The root template that includes the necessary scripts and styles
- `app/Http/Middleware/HandleInertiaRequests.php` - Middleware that handles Inertia requests and responses

## Creating New Pages

To create a new page:

1. Create a new Vue component in the `resources/js/Pages/` directory
2. Add a route in `routes/web.php` that returns an Inertia response:

```php
Route::get('/your-route', function () {
    return Inertia::render('YourComponent', [
        // Props to pass to the component
        'prop1' => 'value1',
    ]);
});
```

## Passing Data to Components

You can pass data from your controller to your Vue components as props:

```php
return Inertia::render('YourComponent', [
    'users' => User::all(),
    'settings' => $settings,
]);
```

Then access this data in your Vue component:

```vue
<script setup>
defineProps({
    users: Array,
    settings: Object,
});
</script>
```

## Shared Data

Common data that should be available to all components is defined in the `share` method of the `HandleInertiaRequests` middleware.

## Links and Forms

Use Inertia's Link component and form helpers for navigation and form submissions:

```
// script setup
import { Link } from '@inertiajs/vue3';

// template
<Link href="/dashboard">Dashboard</Link>
```

## Building Assets

Run the following command to build your assets:

```bash
npm run build
```

For development, use:

```bash
npm run dev
```

## Additional Resources

- [Inertia.js Documentation](https://inertiajs.com/)
- [Vue.js Documentation](https://vuejs.org/)
