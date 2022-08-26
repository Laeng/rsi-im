<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
        @routes
		@vite
	</head>
	<body class="antialiased font-sans bg-gray-200 dark:bg-[#273247]">
		@inertia
	</body>
</html>
