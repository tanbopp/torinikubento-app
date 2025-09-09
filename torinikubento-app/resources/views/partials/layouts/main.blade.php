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
	
	{{-- Alpine.js Global Store for Sidebar --}}
	<script>
		document.addEventListener('alpine:init', () => {
			Alpine.store('sidebar', {
				open: true,
				initialized: false,
				toggle() {
					this.open = !this.open
				},
				init() {
					this.initialized = true
				}
			})
		})
	</script>

	{{-- Font Awesome --}}
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

	{{-- CSS Style --}}
	@stack('css')

	{{-- CSS Custom --}}
	<style>
		::-webkit-scrollbar-track {
			background: transparent !important;
		}
	</style>

	{{-- Script Head --}}
	@stack('script-head')
</head>
<body class="bg-neutral-900 text-white font-poppins">

	{{-- Sidebar --}}
	@include('partials.sidebar.sidebarOwner')

	{{-- Main Content --}}
	<main x-data="{ init() { $store.sidebar.init() } }" x-init="init()" 
		  class="min-h-screen ml-64" 
		  :class="{
			'transition-all duration-300 ease-in-out': $store.sidebar.initialized,
			'ml-64': $store.sidebar.open,
			'ml-0': !$store.sidebar.open
		  }">
		{{-- Include Header --}}
		@include('partials.header.main')

		{{-- Content Area --}}
		<div>
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