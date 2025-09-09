<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>@yield('title', 'Dashboard') - TorinikuBento</title>
	
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
<body class="bg-neutral-900 text-white font-poppins">

	{{-- Sidebar --}}
	@include('partials.sidebar.sidebarOwner')

	{{-- Main Content --}}
	<main class="ml-64 min-h-screen">
		{{-- Top Header --}}
		<header class="bg-neutral-900 border-b border-neutral-700/50 px-6 py-4">
			<div class="flex items-center justify-between">
				<div>
					<h1 class="text-2xl font-bold text-white">@yield('page-title', 'Dashboard')</h1>
					<p class="text-neutral-400 text-sm">@yield('page-subtitle', 'Welcome back to your dashboard')</p>
				</div>
				
				{{-- Header Actions --}}
				<div class="flex items-center space-x-4">
					{{-- Notifications --}}
					<button class="relative p-2 rounded-xl bg-neutral-800/50 hover:bg-neutral-800 transition-colors">
						<svg class="w-5 h-5 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
							<path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
						</svg>
						<span class="absolute -top-1 -right-1 w-3 h-3 bg-orange-500 rounded-full"></span>
					</button>

					{{-- Search --}}
					<div class="relative">
						<input type="text" placeholder="Search..." class="bg-neutral-800/50 border border-neutral-700/50 rounded-xl px-4 py-2 pl-10 text-sm text-white placeholder-neutral-400 focus:outline-none focus:border-orange-500/50">
						<svg class="absolute left-3 top-2.5 w-4 h-4 text-neutral-400" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
						</svg>
					</div>
				</div>
			</div>
		</header>

		{{-- Content Area --}}
		<div class="p-6">
			@yield('main-content')
		</div>
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
