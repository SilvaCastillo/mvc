module.exports = {
    content: [
        './templates/**/*.twig',
        './assets/**/*.{js,ts,jsx,tsx}',
    ],
    safelist: [
        'text-blue-600',
        'bg-red-500',
        'text-3xl',
        'font-bold',
        'p-4'
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}