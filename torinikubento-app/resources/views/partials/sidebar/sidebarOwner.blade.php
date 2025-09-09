{{-- Sidebar --}}
<aside class="fixed top-0 left-0 w-64 h-full bg-neutral-800 border-r border-neutral-700/50 z-40 flex flex-col">
	{{-- Logo --}}
	<div class="p-6 border-b border-neutral-700/50 flex-shrink-0">
		<div>
			<h2 class="text-lg font-bold text-white">TorinikuBento</h2>
			<p class="text-neutral-400 text-xs">Owner Dashboard</p>
		</div>
	</div>

	{{-- Navigation Menu --}}
	<nav class="py-4 px-3 space-y-1 flex-1 overflow-y-auto pb-20 custom-scrollbar" style="scrollbar-width: thin; scrollbar-color: rgba(156, 163, 175, 0.3) transparent;">
		{{-- Dashboard --}}
		<a href="/dashboard" class="block px-3 py-2 rounded-lg {{ request()->is('dashboard') ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700 hover:text-white' }}">
			<span class="font-medium">Dashboard</span>
		</a>

		{{-- Keuangan & Laporan --}}
		<div x-data="{ open: false }">
			<button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-neutral-300 hover:bg-neutral-700 hover:text-white">
				<span class="font-medium">Keuangan & Laporan</span>
				<i class="fas fa-chevron-down text-xs" :class="{ 'rotate-180': open }"></i>
			</button>
			<div x-show="open" class="ml-4 mt-1 space-y-0.5">
				<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
					<span>Laporan Penjualan</span>
				</a>
				<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
					<span>Laporan Pengeluaran</span>
				</a>
				<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
					<span>Laba Rugi</span>
				</a>
				<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
					<span>Analitik Tren</span>
				</a>
			</div>
		</div>

		{{-- Manajemen --}}
		<div x-data="{ open: false }">
			<button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-neutral-300 hover:bg-neutral-700 hover:text-white">
				<span class="font-medium">Manajemen</span>
				<i class="fas fa-chevron-down text-xs" :class="{ 'rotate-180': open }"></i>
			</button>
			<div x-show="open" class="ml-4 mt-1 space-y-0.5">
				<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
					<span>Karyawan</span>
				</a>
				<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
					<span>Menu & Harga</span>
				</a>
				<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
					<span>Promo & Loyalty</span>
				</a>
				<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
					<span>Supplier & Bahan Baku</span>
				</a>
			</div>
		</div>

		{{-- Monitoring Real-time --}}
		<a href="#" class="block px-3 py-2 rounded-lg text-neutral-300 hover:bg-neutral-700 hover:text-white">
			<span class="font-medium">Monitoring Live</span>
		</a>

		{{-- Pengaturan --}}
		<div class="pt-3 border-t border-neutral-700/50">
			<div x-data="{ open: false }">
				<button @click="open = !open" class="w-full flex items-center justify-between px-3 py-2 rounded-lg text-neutral-300 hover:bg-neutral-700 hover:text-white">
					<span class="font-medium">Pengaturan</span>
					<i class="fas fa-chevron-down text-xs" :class="{ 'rotate-180': open }"></i>
				</button>
				<div x-show="open" class="ml-4 mt-1 space-y-0.5">
					<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
						<span>Hak Akses</span>
					</a>
					<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
						<span>Pengaturan Sistem</span>
					</a>
					<a href="#" class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700 hover:text-white">
						<span>Backup & Restore</span>
					</a>
				</div>
			</div>
		</div>
	</nav>

	{{-- User Info at Bottom --}}
	<div class="flex-shrink-0 p-4 border-t border-neutral-700/50 bg-neutral-800">
		<div x-data="{ userMenuOpen: false }" class="relative">
			<button @click="userMenuOpen = !userMenuOpen" class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-neutral-700">
				<div class="flex items-center space-x-3">
					<div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center">
						<span class="text-sm font-medium text-white">O</span>
					</div>
					<div class="text-left">
						<p class="text-white text-sm font-medium">Owner</p>
						<p class="text-neutral-400 text-xs">owner@toriniku.com</p>
					</div>
				</div>
				<i class="fas fa-ellipsis-h text-neutral-400"></i>
			</button>
			
			{{-- User Dropdown Menu --}}
			<div x-show="userMenuOpen" @click.away="userMenuOpen = false" 
				 x-transition:enter="transition ease-out duration-100"
				 x-transition:enter-start="transform opacity-0 scale-95"
				 x-transition:enter-end="transform opacity-100 scale-100"
				 x-transition:leave="transition ease-in duration-75"
				 x-transition:leave-start="transform opacity-100 scale-100"
				 x-transition:leave-end="transform opacity-0 scale-95"
				 class="absolute bottom-16 left-0 right-0 mb-2 bg-neutral-700 rounded-lg border border-neutral-600 shadow-lg">
				<div class="py-2">
					<a href="#" class="flex items-center px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-600 hover:text-white">
						<i class="fas fa-user w-4 mr-3"></i>
						Profil Saya
					</a>
					<a href="#" class="flex items-center px-4 py-2 text-sm text-neutral-300 hover:bg-neutral-600 hover:text-white">
						<i class="fas fa-cog w-4 mr-3"></i>
						Pengaturan Akun
					</a>
					<div class="border-t border-neutral-600 my-1"></div>
					<form action="{{ route('logout') }}" method="POST" class="block">
						@csrf
						<button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-red-400 hover:bg-neutral-600 hover:text-red-300">
							<i class="fas fa-sign-out-alt w-4 mr-3"></i>
							Keluar
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</aside>
