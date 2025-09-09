{{-- Sidebar --}}
<aside x-data 
	   class="fixed top-0 left-0 h-full bg-neutral-800 border-r border-neutral-700/50 z-40 flex flex-col transition-all duration-300 ease-in-out w-64 text-sm"
	   :class="$store.sidebar.open ? 'translate-x-0' : '-translate-x-full'">
	<div class="px-4 py-3 flex-shrink-0 flex justify-end" x-show="$store.sidebar.open" x-transition>
		<button @click="$store.sidebar.toggle()" class="relative px-2 py-2 rounded-xl transition-colors hover:bg-neutral-700/80 text-white">
			<svg class="h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M17 2H7C5.67392 2 4.40215 2.52678 3.46447 3.46447C2.52678 4.40215 2 5.67392 2 7V17C2 18.3261 2.52678 19.5979 3.46447 20.5355C4.40215 21.4732 5.67392 22 7 22H17C18.3261 22 19.5979 21.4732 20.5355 20.5355C21.4732 19.5979 22 18.3261 22 17V7C22 5.67392 21.4732 4.40215 20.5355 3.46447C19.5979 2.52678 18.3261 2 17 2ZM20 17C20 17.7956 19.6839 18.5587 19.1213 19.1213C18.5587 19.6839 17.7956 20 17 20H7C6.20435 20 5.44129 19.6839 4.87868 19.1213C4.31607 18.5587 4 17.7956 4 17V7C4 6.20435 4.31607 5.44129 4.87868 4.87868C5.44129 4.31607 6.20435 4 7 4H17C17.7956 4 18.5587 4.31607 19.1213 4.87868C19.6839 5.44129 20 6.20435 20 7V17Z" fill="currentColor"/>
			<path d="M11 20V4H9V20H11Z" fill="currentColor"/>
			</svg>
		</button>
	</div>

	{{-- Navigation Menu --}}
	<nav x-show="$store.sidebar.open" x-transition class="py-4 px-3 space-y-1 flex-1 overflow-y-auto pb-20 custom-scrollbar" style="scrollbar-width: thin; scrollbar-color: rgba(156, 163, 175, 0.3) transparent;">
		@if(isset($menuItems) && count($menuItems) > 0)
			@foreach($menuItems as $menuItem)
				{{-- Add separator if specified --}}
				@if(isset($menuItem['separator']) && $menuItem['separator'])
					<div class="pt-3 border-t border-neutral-700/50"></div>
				@endif

				@if(isset($menuItem['submenu']))
					{{-- Menu with submenu --}}
					<div x-data="{ open: false }">
						<button @click="open = !open" class="w-full flex text-left items-center justify-between px-3 py-2 rounded-lg text-neutral-300 hover:bg-neutral-700/80 hover:text-white">
							<div class="flex items-center space-x-3">
								@if(isset($menuItem['icon']))
									<i class="{{ $menuItem['icon'] }} text-sm w-4"></i>
								@endif
								<span class="font-normal">{{ $menuItem['label'] }}</span>
							</div>
							<i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
						</button>
						<div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="ml-4 mt-1 space-y-0.5">
							@foreach($menuItem['submenu'] as $subItem)
								<a href="{{ isset($subItem['route']) ? route($subItem['route']) : '#' }}" 
								   class="block px-3 py-2 rounded-lg text-neutral-400 hover:bg-neutral-700/80 hover:text-white transition-colors">
									<span>{{ $subItem['label'] }}</span>
								</a>
							@endforeach
						</div>
					</div>
				@else
					{{-- Single menu item --}}
					<a href="{{ isset($menuItem['route']) ? route($menuItem['route']) : '#' }}" 
					   class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors {{ 
					   		isset($menuItem['active_pattern']) && request()->is($menuItem['active_pattern']) ? 'bg-orange-600 text-white' : 'text-neutral-300 hover:bg-neutral-700/80 hover:text-white' 
					   	}}">
						@if(isset($menuItem['icon']))
							<i class="{{ $menuItem['icon'] }} text-sm w-4"></i>
						@endif
						<span class="font-medium">{{ $menuItem['label'] }}</span>
					</a>
				@endif
			@endforeach
		@else
			{{-- Fallback menu jika tidak ada role atau menu --}}
			<div class="px-3 py-2 text-neutral-400 text-sm">
				<i class="fas fa-exclamation-triangle mr-2"></i>
				Tidak ada menu yang tersedia
			</div>
		@endif
	</nav>

	{{-- User Info at Bottom --}}
	<div x-show="$store.sidebar.open" x-transition class="flex-shrink-0 p-4 bg-neutral-800">
		<div x-data="{ userMenuOpen: false }" class="relative">
			<button @click="userMenuOpen = !userMenuOpen" class="w-full flex items-center justify-between p-2 rounded-lg hover:bg-neutral-700/80">
				<div class="flex items-center space-x-3">
					<div class="w-8 h-8 bg-orange-600 rounded-full flex items-center justify-center">
						<span class="text-sm font-medium text-white">
							{{ $currentUser ? strtoupper(substr($currentUser->name, 0, 1)) : 'U' }}
						</span>
					</div>
					<div class="text-left">
						<p class="text-white text-sm font-medium">
							{{ $currentUser->role->display_name ?? 'User' }}
						</p>
						<p class="text-neutral-400 text-xs">
							{{ $currentUser->email ?? 'user@example.com' }}
						</p>
					</div>
				</div>
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
