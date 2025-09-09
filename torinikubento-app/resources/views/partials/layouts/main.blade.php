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

	{{-- Font Awesome --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
					<p class="text-neutral-400 text-sm">@yield('page-subtitle', 'Selamat datang di panel admin')</p>
				</div>
				
				{{-- User Profile Menu --}}
				<div class="flex items-center space-x-4">
					<div class="relative" x-data="{ open: false }">
						<button @click="open = !open" class="flex items-center space-x-3 bg-neutral-800 hover:bg-neutral-700 px-4 py-2 rounded-lg transition-colors">
							<div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-red-600 rounded-full flex items-center justify-center">
								<span class="text-sm font-medium">A</span>
							</div>
							<span class="text-white">Admin</span>
							<svg class="w-4 h-4 text-neutral-400" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
							</svg>
						</button>
						
						{{-- Dropdown Menu --}}
						<div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-neutral-800 border border-neutral-700 rounded-lg shadow-lg z-50">
							<div class="py-2">
								<a href="#" class="block px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-700 hover:text-white transition-colors">
									<i class="fas fa-user mr-2"></i> Profile
								</a>
								<a href="#" class="block px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-700 hover:text-white transition-colors">
									<i class="fas fa-cog mr-2"></i> Settings
								</a>
								<div class="border-t border-neutral-700 my-1"></div>
								<a href="#" class="block px-4 py-2 text-sm text-red-400 hover:bg-neutral-700 hover:text-red-300 transition-colors">
									<i class="fas fa-sign-out-alt mr-2"></i> Logout
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</header>

		{{-- Content Area --}}
		<div class="p-6">
			@yield('main-content')
		</div>
	</main>

	{{-- AOS JS --}}
	<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
	<script>
		AOS.init({
			duration: 800,
			easing: 'ease-in-out',
			once: true
		});
	</script>

	{{-- Script Body --}}
	@stack('script-body')
	
</body>
</html>