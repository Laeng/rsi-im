const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
		'./resources/**/*.blade.php',
		'./resources/**/*.ts',
		'./resources/**/*.vue',
	],
    darkMode: 'media',
	theme: {
        extend: {
            fontFamily: {
                sans: ['Pretendard', ...defaultTheme.fontFamily.sans],
            },
        },
    },
	plugins: [

    ],
}
