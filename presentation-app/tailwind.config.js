/** @type {import('tailwindcss').Config} */
module.exports = {
    prefix: 'u-',
    important: true,
    darkMode: ['variant', ['@media (prefers-color-scheme: dark) { &:not(.light *) }', '&:is(.dark *)']],
    theme: {
        colors: {
            white: 'rgb(255,255,255)',
            black: 'rgb(0,0,0)',
            faded: 'var(--neutral-400)',
            primary: 'var(--primary)',
            secondary: 'var(--secondary)',
            danger: 'var(--danger)',
            warning: 'var(--warning)',
            success: 'var(--success)',
            neutral: {
                DEFAULT: 'var(--neutral-000)',
                50: 'var(--neutral-50)',
                100: 'var(--neutral-100)',
                200: 'var(--neutral-200)',
                300: 'var(--neutral-300)',
                400: 'var(--neutral-400)',
                500: 'var(--neutral-500)',
                600: 'var(--neutral-600)',
                700: 'var(--neutral-700)',
                800: 'var(--neutral-800)',
                900: 'var(--neutral-900)',
                950: 'var(--neutral-950)'
            }
        },
        screens: {
            xs: '393px',
            sm: '640px',
            md: '768px',
            lg: '1024px',
            xl: '1280px',
            '2xl': '1536px'
        },
        fontSize: {
            xs: '0.75rem',
            sm: '0.875rem',
            base: '1rem',
            lg: '1.125rem',
            xl: '1.25rem',
            '2xl': '1.5rem',
            '3xl': '1.875rem',
            '4xl': '2.25rem',
            '5xl': '3rem',
            '6xl': '3.75rem',
            '7xl': '4.5rem',
            '8xl': '6rem'
        },
        fontFamily: {
            sans: ['Inter', 'sans-serif'],
            body: ['Inter', 'sans-serif'],
            heading: ['Roboto', 'sans-serif'],
            special: ['Courier New', 'monospace'],
        }
    },
    content: ['./resources/views/**/*.blade.php', './resources/js/**/*.vue'],
    plugins: [require('@tailwindcss/typography')]
};
