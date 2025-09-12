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
		/* Global dark scrollbar theme for all pages - More specific and forced */
		*, 
		*:before, 
		*:after,
		html,
		body,
		div,
		section,
		article,
		main,
		aside,
		nav,
		table,
		tbody,
		thead,
		tr,
		td,
		th,
		ul,
		ol,
		li,
		pre,
		code,
		textarea,
		select,
		.overflow-auto,
		.overflow-y-auto,
		.overflow-x-auto,
		.overflow-scroll,
		.overflow-y-scroll,
		.overflow-x-scroll {
			/* Firefox - neutral dark colors */
			scrollbar-width: auto !important;
			scrollbar-color: #666666 #2d2d2d !important;
		}
		
		/* Webkit browsers - neutral dark theme with higher specificity */
		*::-webkit-scrollbar,
		*:before::-webkit-scrollbar,
		*:after::-webkit-scrollbar,
		html::-webkit-scrollbar,
		body::-webkit-scrollbar,
		div::-webkit-scrollbar,
		section::-webkit-scrollbar,
		article::-webkit-scrollbar,
		main::-webkit-scrollbar,
		aside::-webkit-scrollbar,
		nav::-webkit-scrollbar,
		table::-webkit-scrollbar,
		tbody::-webkit-scrollbar,
		thead::-webkit-scrollbar,
		tr::-webkit-scrollbar,
		td::-webkit-scrollbar,
		th::-webkit-scrollbar,
		ul::-webkit-scrollbar,
		ol::-webkit-scrollbar,
		li::-webkit-scrollbar,
		pre::-webkit-scrollbar,
		code::-webkit-scrollbar,
		textarea::-webkit-scrollbar,
		select::-webkit-scrollbar,
		.overflow-auto::-webkit-scrollbar,
		.overflow-y-auto::-webkit-scrollbar,
		.overflow-x-auto::-webkit-scrollbar,
		.overflow-scroll::-webkit-scrollbar,
		.overflow-y-scroll::-webkit-scrollbar,
		.overflow-x-scroll::-webkit-scrollbar {
			width: auto !important;
			height: auto !important;
			background-color: #2d2d2d !important;
		}
		
		*::-webkit-scrollbar-track,
		*:before::-webkit-scrollbar-track,
		*:after::-webkit-scrollbar-track,
		html::-webkit-scrollbar-track,
		body::-webkit-scrollbar-track,
		div::-webkit-scrollbar-track,
		section::-webkit-scrollbar-track,
		article::-webkit-scrollbar-track,
		main::-webkit-scrollbar-track,
		aside::-webkit-scrollbar-track,
		nav::-webkit-scrollbar-track,
		table::-webkit-scrollbar-track,
		tbody::-webkit-scrollbar-track,
		thead::-webkit-scrollbar-track,
		tr::-webkit-scrollbar-track,
		td::-webkit-scrollbar-track,
		th::-webkit-scrollbar-track,
		ul::-webkit-scrollbar-track,
		ol::-webkit-scrollbar-track,
		li::-webkit-scrollbar-track,
		pre::-webkit-scrollbar-track,
		code::-webkit-scrollbar-track,
		textarea::-webkit-scrollbar-track,
		select::-webkit-scrollbar-track,
		.overflow-auto::-webkit-scrollbar-track,
		.overflow-y-auto::-webkit-scrollbar-track,
		.overflow-x-auto::-webkit-scrollbar-track,
		.overflow-scroll::-webkit-scrollbar-track,
		.overflow-y-scroll::-webkit-scrollbar-track,
		.overflow-x-scroll::-webkit-scrollbar-track {
			background: #2d2d2d !important;
			border-radius: 0 !important;
		}
		
		*::-webkit-scrollbar-thumb,
		*:before::-webkit-scrollbar-thumb,
		*:after::-webkit-scrollbar-thumb,
		html::-webkit-scrollbar-thumb,
		body::-webkit-scrollbar-thumb,
		div::-webkit-scrollbar-thumb,
		section::-webkit-scrollbar-thumb,
		article::-webkit-scrollbar-thumb,
		main::-webkit-scrollbar-thumb,
		aside::-webkit-scrollbar-thumb,
		nav::-webkit-scrollbar-thumb,
		table::-webkit-scrollbar-thumb,
		tbody::-webkit-scrollbar-thumb,
		thead::-webkit-scrollbar-thumb,
		tr::-webkit-scrollbar-thumb,
		td::-webkit-scrollbar-thumb,
		th::-webkit-scrollbar-thumb,
		ul::-webkit-scrollbar-thumb,
		ol::-webkit-scrollbar-thumb,
		li::-webkit-scrollbar-thumb,
		pre::-webkit-scrollbar-thumb,
		code::-webkit-scrollbar-thumb,
		textarea::-webkit-scrollbar-thumb,
		select::-webkit-scrollbar-thumb,
		.overflow-auto::-webkit-scrollbar-thumb,
		.overflow-y-auto::-webkit-scrollbar-thumb,
		.overflow-x-auto::-webkit-scrollbar-thumb,
		.overflow-scroll::-webkit-scrollbar-thumb,
		.overflow-y-scroll::-webkit-scrollbar-thumb,
		.overflow-x-scroll::-webkit-scrollbar-thumb {
			background: #666666 !important;
			border-radius: 6px !important;
			border: 1px solid #2d2d2d !important;
		}
		
		*::-webkit-scrollbar-thumb:hover,
		*:before::-webkit-scrollbar-thumb:hover,
		*:after::-webkit-scrollbar-thumb:hover,
		html::-webkit-scrollbar-thumb:hover,
		body::-webkit-scrollbar-thumb:hover,
		div::-webkit-scrollbar-thumb:hover,
		section::-webkit-scrollbar-thumb:hover,
		article::-webkit-scrollbar-thumb:hover,
		main::-webkit-scrollbar-thumb:hover,
		aside::-webkit-scrollbar-thumb:hover,
		nav::-webkit-scrollbar-thumb:hover,
		table::-webkit-scrollbar-thumb:hover,
		tbody::-webkit-scrollbar-thumb:hover,
		thead::-webkit-scrollbar-thumb:hover,
		tr::-webkit-scrollbar-thumb:hover,
		td::-webkit-scrollbar-thumb:hover,
		th::-webkit-scrollbar-thumb:hover,
		ul::-webkit-scrollbar-thumb:hover,
		ol::-webkit-scrollbar-thumb:hover,
		li::-webkit-scrollbar-thumb:hover,
		pre::-webkit-scrollbar-thumb:hover,
		code::-webkit-scrollbar-thumb:hover,
		textarea::-webkit-scrollbar-thumb:hover,
		select::-webkit-scrollbar-thumb:hover,
		.overflow-auto::-webkit-scrollbar-thumb:hover,
		.overflow-y-auto::-webkit-scrollbar-thumb:hover,
		.overflow-x-auto::-webkit-scrollbar-thumb:hover,
		.overflow-scroll::-webkit-scrollbar-thumb:hover,
		.overflow-y-scroll::-webkit-scrollbar-thumb:hover,
		.overflow-x-scroll::-webkit-scrollbar-thumb:hover {
			background: #777777 !important;
		}
		
		*::-webkit-scrollbar-thumb:active,
		*:before::-webkit-scrollbar-thumb:active,
		*:after::-webkit-scrollbar-thumb:active,
		html::-webkit-scrollbar-thumb:active,
		body::-webkit-scrollbar-thumb:active,
		div::-webkit-scrollbar-thumb:active,
		section::-webkit-scrollbar-thumb:active,
		article::-webkit-scrollbar-thumb:active,
		main::-webkit-scrollbar-thumb:active,
		aside::-webkit-scrollbar-thumb:active,
		nav::-webkit-scrollbar-thumb:active,
		table::-webkit-scrollbar-thumb:active,
		tbody::-webkit-scrollbar-thumb:active,
		thead::-webkit-scrollbar-thumb:active,
		tr::-webkit-scrollbar-thumb:active,
		td::-webkit-scrollbar-thumb:active,
		th::-webkit-scrollbar-thumb:active,
		ul::-webkit-scrollbar-thumb:active,
		ol::-webkit-scrollbar-thumb:active,
		li::-webkit-scrollbar-thumb:active,
		pre::-webkit-scrollbar-thumb:active,
		code::-webkit-scrollbar-thumb:active,
		textarea::-webkit-scrollbar-thumb:active,
		select::-webkit-scrollbar-thumb:active,
		.overflow-auto::-webkit-scrollbar-thumb:active,
		.overflow-y-auto::-webkit-scrollbar-thumb:active,
		.overflow-x-auto::-webkit-scrollbar-thumb:active,
		.overflow-scroll::-webkit-scrollbar-thumb:active,
		.overflow-y-scroll::-webkit-scrollbar-thumb:active,
		.overflow-x-scroll::-webkit-scrollbar-thumb:active {
			background: #888888 !important;
		}
		
		*::-webkit-scrollbar-corner,
		*:before::-webkit-scrollbar-corner,
		*:after::-webkit-scrollbar-corner,
		html::-webkit-scrollbar-corner,
		body::-webkit-scrollbar-corner,
		div::-webkit-scrollbar-corner,
		section::-webkit-scrollbar-corner,
		article::-webkit-scrollbar-corner,
		main::-webkit-scrollbar-corner,
		aside::-webkit-scrollbar-corner,
		nav::-webkit-scrollbar-corner,
		table::-webkit-scrollbar-corner,
		tbody::-webkit-scrollbar-corner,
		thead::-webkit-scrollbar-corner,
		tr::-webkit-scrollbar-corner,
		td::-webkit-scrollbar-corner,
		th::-webkit-scrollbar-corner,
		ul::-webkit-scrollbar-corner,
		ol::-webkit-scrollbar-corner,
		li::-webkit-scrollbar-corner,
		pre::-webkit-scrollbar-corner,
		code::-webkit-scrollbar-corner,
		textarea::-webkit-scrollbar-corner,
		select::-webkit-scrollbar-corner,
		.overflow-auto::-webkit-scrollbar-corner,
		.overflow-y-auto::-webkit-scrollbar-corner,
		.overflow-x-auto::-webkit-scrollbar-corner,
		.overflow-scroll::-webkit-scrollbar-corner,
		.overflow-y-scroll::-webkit-scrollbar-corner,
		.overflow-x-scroll::-webkit-scrollbar-corner {
			background: #2d2d2d !important;
		}
		
		/* Force dark color scheme globally */
		html {
			color-scheme: dark !important;
		}
		
		body {
			color-scheme: dark !important;
		}

		[x-cloak] {
		  display: none !important;
		}

		/* Hide number input spinners/arrows */
		input[type=number]::-webkit-outer-spin-button,
		input[type=number]::-webkit-inner-spin-button {
			-webkit-appearance: none !important;
			margin: 0 !important;
		}

		/* Firefox */
		input[type=number] {
			-moz-appearance: textfield !important;
		}
	</style>

	{{-- Script Head --}}
	@stack('script-head')
</head>
<body class="bg-neutral-900 text-white font-poppins">

	{{-- Sidebar --}}
	@include('partials.sidebar.sidebar')

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