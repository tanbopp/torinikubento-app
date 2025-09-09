{{-- Top Header --}}
<header class="bg-neutral-900 border-b border-neutral-700/50 px-6 py-4">
	<div class="flex items-center justify-between">
		<div>
			<h1 class="text-xl font-semibold text-white">@yield('page-title', 'Dashboard')</h1>
		</div>
		
		{{-- Notification Menu --}}
		<div class="flex items-center space-x-4">
			<div class="relative" x-data="{ open: false }">
				<button @click="open = !open" class="relative bg-neutral-800 hover:bg-neutral-700 px-3 py-3 rounded-lg transition-colors">
					<svg class="w-5 h-5 text-neutral-300" fill="currentColor" viewBox="0 0 20 20">
						<path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
					</svg>
					{{-- Notification Badge --}}
					<span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
				</button>
				
				{{-- Notification Dropdown --}}
				<div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-80 bg-neutral-800 border border-neutral-700 rounded-lg shadow-lg z-50">
					<div class="px-4 py-3 border-b border-neutral-700">
						<h3 class="text-base font-semibold text-white">Notifikasi</h3>
					</div>
					
					{{-- Notification List --}}
					<div class="max-h-72 overflow-y-auto">
						{{-- Sample notifications --}}
						<div class="px-3 py-2.5 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors">
							<div class="flex items-center space-x-2.5">
								<div class="w-1.5 h-1.5 bg-orange-500 rounded-full flex-shrink-0"></div>
								<div class="flex-1 min-w-0">
									<div class="flex items-center justify-between">
										<p class="text-xs font-medium text-white truncate">Pesanan Baru #001</p>
										<span class="text-xs text-neutral-500 ml-2 flex-shrink-0">2m</span>
									</div>
									<p class="text-xs text-neutral-400 truncate mt-0.5">Meja 5 - Ayam Teriyaki Bento</p>
								</div>
							</div>
						</div>
						
						<div class="px-3 py-2.5 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors">
							<div class="flex items-center space-x-2.5">
								<div class="w-1.5 h-1.5 bg-green-500 rounded-full flex-shrink-0"></div>
								<div class="flex-1 min-w-0">
									<div class="flex items-center justify-between">
										<p class="text-xs font-medium text-white truncate">Pembayaran Berhasil</p>
										<span class="text-xs text-neutral-500 ml-2 flex-shrink-0">5m</span>
									</div>
									<p class="text-xs text-neutral-400 truncate mt-0.5">Pesanan #045 telah berhasil</p>
								</div>
							</div>
						</div>
						
						<div class="px-3 py-2.5 border-b border-neutral-700 hover:bg-neutral-700/30 transition-colors">
							<div class="flex items-center space-x-2.5">
								<div class="w-1.5 h-1.5 bg-blue-500 rounded-full flex-shrink-0"></div>
								<div class="flex-1 min-w-0">
									<div class="flex items-center justify-between">
										<p class="text-xs font-medium text-white truncate">Stok Rendah</p>
										<span class="text-xs text-neutral-500 ml-2 flex-shrink-0">10m</span>
									</div>
									<p class="text-xs text-neutral-400 truncate mt-0.5">Ayam Teriyaki tersisa 5 porsi</p>
								</div>
							</div>
						</div>
					</div>
					
					{{-- View All Button --}}
					<div class="p-3 border-t border-neutral-700">
						<a href="{{ route('notifications.index') }}" class="w-full bg-orange-600 hover:bg-orange-700 text-white text-xs font-medium py-1.5 px-3 rounded-md transition-colors text-center block">
							Lihat Semua Notifikasi
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>
