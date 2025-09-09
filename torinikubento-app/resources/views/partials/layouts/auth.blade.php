<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Login</title>
	
	{{-- Google Fonts - Poppins --}}
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	
	{{-- CDN Tailwindcss --}}
	<script src="https://cdn.tailwindcss.com"></script>
	<script>
		tailwind.config = {
			theme: {
				extend: {
					fontFamily: {
						'poppins': ['Poppins', 'sans-serif'],
					}
				}
			}
		}
	</script>

	{{-- AOS CSS --}}
	<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

	{{-- Alpine.js --}}
	<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

	{{-- CSS Style --}}
	@stack('css')

	{{-- Script Head --}}
	@stack('script-head')
</head>
<body class="bg-neutral-900 text-white">

	{{-- Contents --}}
	<main>
		@yield('main-content')
	</main>

	{{-- Script Body --}}
	@stack('script-body')
	
	{{-- AOS JS --}}
	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script>
		AOS.init();
	</script>
	
</body>
</html>