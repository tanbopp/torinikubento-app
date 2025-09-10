{{-- 
    Filter Menu Component (Context Menu variant specifically for filters)
    
    Usage:
    <x-ui.filter-menu id="filter-1" width="w-72">
        <div class="p-4">Filter content here</div>
    </x-ui.filter-menu>
--}}

@props([
    'id' => 'filter-menu-' . uniqid(),
    'width' => 'w-72',
    'buttonText' => 'Filter',
    'buttonIcon' => 'fas fa-filter',
    'hasActiveFilters' => false,
    'filterTitle' => 'Filter Produk',
    'filterSubtitle' => 'Pilih kategori dan status produk',
    'clearUrl' => null
])

<div class="relative">
    {{-- Filter Button --}}
    <button type="button" 
            onclick="toggleFilterMenu('{{ $id }}')"
            class="aspect-square h-8 flex justify-center items-center hover:bg-neutral-700 text-white rounded-lg transition-colors text-sm font-medium whitespace-nowrap flex items-center space-x-2 relative">
        <svg class="{{ $buttonIcon }} h-4 {{ $hasActiveFilters ? 'text-orange-400' : 'text-white' }} transition-colors" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M6.5 7C6.5 6.17157 5.82843 5.5 5 5.5C4.17157 5.5 3.5 6.17157 3.5 7C3.5 7.82843 4.17157 8.5 5 8.5C5.82843 8.5 6.5 7.82843 6.5 7ZM8.5 7C8.5 8.933 6.933 10.5 5 10.5C3.067 10.5 1.5 8.933 1.5 7C1.5 5.067 3.067 3.5 5 3.5C6.933 3.5 8.5 5.067 8.5 7Z" fill="currentColor"/>
        <path d="M21.5 6C22.0523 6 22.5 6.44772 22.5 7C22.5 7.55228 22.0523 8 21.5 8H11.5C10.9477 8 10.5 7.55228 10.5 7C10.5 6.44772 10.9477 6 11.5 6H21.5Z" fill="currentColor"/>
        <path d="M17.5 17C17.5 16.1716 18.1716 15.5 19 15.5C19.8284 15.5 20.5 16.1716 20.5 17C20.5 17.8284 19.8284 18.5 19 18.5C18.1716 18.5 17.5 17.8284 17.5 17ZM15.5 17C15.5 18.933 17.067 20.5 19 20.5C20.933 20.5 22.5 18.933 22.5 17C22.5 15.067 20.933 13.5 19 13.5C17.067 13.5 15.5 15.067 15.5 17Z" fill="currentColor"/>
        <path d="M2.5 16C1.94772 16 1.5 16.4477 1.5 17C1.5 17.5523 1.94772 18 2.5 18H12.5C13.0523 18 13.5 17.5523 13.5 17C13.5 16.4477 13.0523 16 12.5 16H2.5Z" fill="currentColor"/>
        </svg>
    </button>

    {{-- Filter Menu Dropdown --}}
    <div id="filter-menu-{{ $id }}" 
         class="hidden fixed z-50 {{ $width }} bg-neutral-800 rounded-xl shadow-2xl border border-neutral-700/30"
         style="top: 0; left: 0; color-scheme: dark;">
        {{-- Fixed Header --}}
        <div class="px-4 py-2 border-b border-neutral-700/30 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-white">{{ $filterTitle }}</p>
                <p class="text-xs text-neutral-400 mt-0.5">{{ $filterSubtitle }}</p>
            </div>
            @if($hasActiveFilters && $clearUrl)
                <button type="button" 
                        onclick="clearAllFilters('{{ $clearUrl }}')"
                        class="text-neutral-400 aspect-square h-6 rounded-full bg-neutral-700 hover:text-neutral-300 transition-colors"
                        title="Hapus Semua Filter">
                    <i class="fas fa-times text-sm"></i>
                </button>
            @endif
        </div>
        {{-- Flexible Content Container --}}
        <div class="overflow-y-auto custom-scrollbar">
            {{ $slot }}
        </div>
    </div>
</div>

@once
    @push('styles')
    <style>
        /* Prevent glitching during scroll */
        [id^="filter-menu-"] {
            will-change: transform, opacity;
            contain: layout style paint;
        }
        
        [id^="filter-menu-"] > div {
            will-change: scroll-position;
            contain: size layout style paint;
        }
        
        /* Stable scrolling with proper scrollbar positioning */
        .custom-scrollbar {
            overflow-anchor: none;
            overscroll-behavior: contain;
        }
        
        /* Ensure scrollbar is inside the container with proper styling */
        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(64, 64, 64, 0.2);
            border-radius: 4px;
            margin: 4px 0;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(115, 115, 115, 0.6);
            border-radius: 4px;
            border: none;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(115, 115, 115, 0.8);
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:active {
            background: rgba(115, 115, 115, 1);
        }
        
        /* Firefox scrollbar */
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(115, 115, 115, 0.6) rgba(64, 64, 64, 0.2);
        }
    </style>
    @endpush
    
    @push('script-body')
    <script>
        // Filter Menu functionality
        let activeFilterMenuId = null;
        let activeFilterMenuButton = null;

        // Function to close all context menus (table action menus, etc)
        function closeAllContextMenus() {
            // Close table context menus
            document.querySelectorAll('[id^="context-menu-"]').forEach(menu => {
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            });
            
            // Close any other dropdown menus
            document.querySelectorAll('.dropdown-menu, .context-menu, [id*="dropdown"], [id*="menu"]').forEach(menu => {
                if (menu.id !== `filter-menu-${activeFilterMenuId}` && !menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                }
            });
        }

        // Function to close all filter menus with animation
        function closeAllFilterMenusWithAnimation() {
            const activeMenus = document.querySelectorAll('[id^="filter-menu-"]:not(.hidden)');
            
            if (activeMenus.length === 0) {
                // Reset active tracking immediately if no menus are open
                activeFilterMenuId = null;
                activeFilterMenuButton = null;
                return;
            }

            activeMenus.forEach(menu => {
                // Set exit animation
                menu.style.transition = 'opacity 0.15s cubic-bezier(0.4, 0, 0.6, 1), transform 0.15s cubic-bezier(0.4, 0, 0.6, 1)';
                menu.style.opacity = '0';
                menu.style.transform = 'translateY(-8px) scale(0.95)';
                
                // Clean up after animation
                setTimeout(() => {
                    menu.classList.add('hidden');
                    // Reset all styles completely
                    menu.style.transition = '';
                    menu.style.transform = '';
                    menu.style.opacity = '';
                    menu.style.position = '';
                    menu.style.top = '';
                    menu.style.left = '';
                    menu.style.zIndex = '';
                    menu.style.transformOrigin = '';
                    menu.style.removeProperty('max-height');
                    menu.style.removeProperty('height');
                    
                    // Reset scroll container
                    const scrollContainer = menu.querySelector('.custom-scrollbar');
                    if (scrollContainer) {
                        scrollContainer.style.removeProperty('max-height');
                        scrollContainer.style.removeProperty('height');
                    }
                }, 150);
            });
            
            // Reset active tracking after starting animation
            setTimeout(() => {
                activeFilterMenuId = null;
                activeFilterMenuButton = null;
            }, 150);
        }

        // Function to close all filter menus immediately (without animation)
        function closeAllFilterMenus() {
            document.querySelectorAll('[id^="filter-menu-"]').forEach(menu => {
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    // Clean up styles immediately
                    menu.style.transition = '';
                    menu.style.transform = '';
                    menu.style.opacity = '';
                    menu.style.position = '';
                    menu.style.top = '';
                    menu.style.left = '';
                    menu.style.zIndex = '';
                    menu.style.transformOrigin = '';
                    menu.style.removeProperty('max-height');
                    menu.style.removeProperty('height');
                    
                    // Also reset scroll container height constraints
                    const scrollContainer = menu.querySelector('.custom-scrollbar');
                    if (scrollContainer) {
                        scrollContainer.style.removeProperty('max-height');
                        scrollContainer.style.removeProperty('height');
                    }
                }
            });
            
            // Reset active tracking
            activeFilterMenuId = null;
            activeFilterMenuButton = null;
        }

        function toggleFilterMenu(menuId) {
            const button = event.target.closest('button');
            const dropdown = document.getElementById('filter-menu-' + menuId);
            const isHidden = dropdown.classList.contains('hidden');
            
            // Close all other menus first (including context menus) with animation
            closeAllContextMenus();
            if (activeFilterMenuId && activeFilterMenuId !== menuId) {
                closeAllFilterMenusWithAnimation();
                // Wait a bit before opening new menu to avoid conflicts
                setTimeout(() => openFilterMenu(menuId, button, dropdown), 100);
            } else if (isHidden) {
                openFilterMenu(menuId, button, dropdown);
            } else {
                // Close current menu with animation
                closeAllFilterMenusWithAnimation();
            }
        }

        function openFilterMenu(menuId, button, dropdown) {
            // Set active tracking
            activeFilterMenuId = menuId;
            activeFilterMenuButton = button;
            
            // Position dropdown
            positionFilterMenu(button, dropdown);
            
            // Reset any existing transitions and transforms
            dropdown.style.transition = 'none';
            dropdown.style.transform = '';
            dropdown.style.opacity = '';
            
            // Show dropdown with slide-in effect
            dropdown.classList.remove('hidden');
            dropdown.style.opacity = '0';
            dropdown.style.transform = 'translateY(-8px) scale(0.95)';
            
            // Force reflow to ensure initial state is applied
            dropdown.offsetHeight;
            
            // Animate in
            requestAnimationFrame(() => {
                dropdown.style.transition = 'opacity 0.2s cubic-bezier(0.16, 1, 0.3, 1), transform 0.2s cubic-bezier(0.16, 1, 0.3, 1)';
                dropdown.style.opacity = '1';
                dropdown.style.transform = 'translateY(0) scale(1)';
            });
        }

        function positionFilterMenu(button, dropdown) {
            // Get button position relative to viewport
            const rect = button.getBoundingClientRect();
            const viewportWidth = window.innerWidth;
            const viewportHeight = window.innerHeight;
            
            // Get dropdown dimensions (temporarily show to measure)
            const wasHidden = dropdown.classList.contains('hidden');
            if (wasHidden) {
                dropdown.style.visibility = 'hidden';
                dropdown.style.position = 'fixed';
                dropdown.style.top = '-9999px';
                dropdown.style.left = '-9999px';
                dropdown.classList.remove('hidden');
                
                // Remove any height constraints during measurement
                const scrollContainer = dropdown.querySelector('.custom-scrollbar');
                if (scrollContainer) {
                    scrollContainer.style.removeProperty('max-height');
                    scrollContainer.style.removeProperty('height');
                }
                dropdown.style.removeProperty('max-height');
                dropdown.style.removeProperty('height');
            }
            
            const dropdownRect = dropdown.getBoundingClientRect();
            const dropdownWidth = dropdownRect.width;
            let dropdownHeight = dropdownRect.height;
            
            // Hide again if it was hidden
            if (wasHidden) {
                dropdown.classList.add('hidden');
                dropdown.style.visibility = 'visible';
                dropdown.style.position = '';
                dropdown.style.top = '';
                dropdown.style.left = '';
            }
            
            // Margin from viewport edges
            const margin = 16;
            
            // Calculate available space in all directions
            const spaceBelow = viewportHeight - rect.bottom - margin;
            const spaceAbove = rect.top - margin;
            const spaceRight = viewportWidth - rect.right - margin;
            const spaceLeft = rect.left - margin;
            
            let finalPosition = {
                top: 0,
                left: 0,
                transformOrigin: 'top left',
                maxHeight: null
            };
            
            // Determine best vertical position
            let verticalPosition = 'below'; // default
            let availableHeight = spaceBelow;
            
            // If dropdown is taller than space below, check if above is better
            if (dropdownHeight > spaceBelow && spaceAbove > spaceBelow) {
                verticalPosition = 'above';
                availableHeight = spaceAbove;
            }
            
            // If still not enough space in preferred position, try side positions
            if (dropdownHeight > availableHeight) {
                const sideSpaceHeight = viewportHeight - (2 * margin);
                
                // Check if we can position to the right or left
                if (spaceRight >= dropdownWidth && sideSpaceHeight >= dropdownHeight) {
                    verticalPosition = 'side-right';
                    availableHeight = sideSpaceHeight;
                } else if (spaceLeft >= dropdownWidth && sideSpaceHeight >= dropdownHeight) {
                    verticalPosition = 'side-left';
                    availableHeight = sideSpaceHeight;
                }
            }
            
            // Calculate final position based on chosen placement
            switch (verticalPosition) {
                case 'above':
                    finalPosition.top = Math.max(margin, rect.top - Math.min(dropdownHeight, availableHeight) - 8);
                    finalPosition.left = Math.max(margin, Math.min(rect.right - dropdownWidth, viewportWidth - dropdownWidth - margin));
                    finalPosition.transformOrigin = 'bottom right';
                    break;
                    
                case 'side-right':
                    finalPosition.top = Math.max(margin, Math.min(rect.top, viewportHeight - Math.min(dropdownHeight, availableHeight) - margin));
                    finalPosition.left = rect.right + 8;
                    finalPosition.transformOrigin = 'top left';
                    break;
                    
                case 'side-left':
                    finalPosition.top = Math.max(margin, Math.min(rect.top, viewportHeight - Math.min(dropdownHeight, availableHeight) - margin));
                    finalPosition.left = rect.left - dropdownWidth - 8;
                    finalPosition.transformOrigin = 'top right';
                    break;
                    
                default: // 'below'
                    finalPosition.top = rect.bottom + 8;
                    finalPosition.left = Math.max(margin, Math.min(rect.right - dropdownWidth, viewportWidth - dropdownWidth - margin));
                    finalPosition.transformOrigin = 'top right';
                    break;
            }
            
            // Only set max-height if dropdown would go off screen
            if (dropdownHeight > availableHeight) {
                finalPosition.maxHeight = Math.max(200, availableHeight - 32); // 32px buffer
            }
            
            // Apply final positioning
            dropdown.style.position = 'fixed';
            dropdown.style.top = finalPosition.top + 'px';
            dropdown.style.left = finalPosition.left + 'px';
            dropdown.style.zIndex = '9999';
            dropdown.style.transformOrigin = finalPosition.transformOrigin;
            
            // Apply height constraint only if needed
            if (finalPosition.maxHeight) {
                const scrollContainer = dropdown.querySelector('.custom-scrollbar');
                if (scrollContainer) {
                    scrollContainer.style.setProperty('max-height', finalPosition.maxHeight + 'px', 'important');
                }
            }
        }

        // Reposition active filter menu on scroll or resize
        function repositionActiveFilterMenu() {
            if (activeFilterMenuId && activeFilterMenuButton) {
                const dropdown = document.getElementById('filter-menu-' + activeFilterMenuId);
                if (dropdown && !dropdown.classList.contains('hidden')) {
                    // Remove any previous height constraints before repositioning
                    dropdown.style.removeProperty('max-height');
                    const scrollContainer = dropdown.querySelector('.custom-scrollbar');
                    if (scrollContainer) {
                        scrollContainer.style.removeProperty('max-height');
                    }
                    
                    requestAnimationFrame(() => {
                        positionFilterMenu(activeFilterMenuButton, dropdown);
                    });
                }
            }
        }

        // Add event listeners for scroll and resize with more aggressive throttling
        let filterRepositionTimeout = null;
        let lastRepositionTime = 0;
        
        function throttledFilterReposition() {
            const now = Date.now();
            const timeSinceLastReposition = now - lastRepositionTime;
            
            // Only reposition if enough time has passed (reduce glitch during scroll)
            if (timeSinceLastReposition < 100) return;
            
            if (filterRepositionTimeout) return;
            filterRepositionTimeout = requestAnimationFrame(() => {
                repositionActiveFilterMenu();
                lastRepositionTime = Date.now();
                filterRepositionTimeout = null;
            });
        }

        // Add event listeners with more selective targeting
        window.addEventListener('resize', throttledFilterReposition);
        
        // Only listen to document scroll, not all scroll events
        document.addEventListener('scroll', function(event) {
            // Only reposition on document/window scroll, not internal element scroll
            if (event.target === document || event.target === document.documentElement) {
                throttledFilterReposition();
            }
        }, true);

        // Close filter menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!event.target.closest('.relative')) {
                // Use animated close when clicking outside
                closeAllFilterMenusWithAnimation();
            }
        });

        // Clear all filters function
        function clearAllFilters(clearUrl) {
            if (clearUrl) {
                window.location.href = clearUrl;
            }
        }

        // Realtime filter update function
        function updateFiltersRealtime() {
            const filterMenus = document.querySelectorAll('[id^="filter-menu-"]');
            
            filterMenus.forEach(menu => {
                const form = menu.closest('form') || document.querySelector('form');
                if (form) {
                    // Create FormData from the form
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    
                    // Get current URL without parameters
                    const baseUrl = window.location.pathname;
                    
                    // Build new URL with filter parameters
                    const newUrl = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;
                    
                    // Update URL and reload
                    window.location.href = newUrl;
                }
            });
        }

        // Prevent filter menu from closing when clicking inside
        document.addEventListener('click', function(event) {
            if (event.target.closest('[id^="filter-menu-"]')) {
                event.stopPropagation();
            }
        });

        // Prevent scroll events inside filter menu from bubbling up
        document.addEventListener('wheel', function(event) {
            const filterMenu = event.target.closest('[id^="filter-menu-"]');
            if (filterMenu && !filterMenu.classList.contains('hidden')) {
                // Find the actual scrollable container
                const scrollContainer = event.target.closest('.custom-scrollbar') || 
                                      filterMenu.querySelector('.custom-scrollbar');
                
                if (scrollContainer) {
                    // Check scroll boundaries
                    const isScrollingUp = event.deltaY < 0;
                    const isScrollingDown = event.deltaY > 0;
                    const canScrollUp = scrollContainer.scrollTop > 0;
                    const canScrollDown = scrollContainer.scrollTop < (scrollContainer.scrollHeight - scrollContainer.clientHeight - 1);
                    
                    // Prevent page scroll when at boundaries
                    if ((isScrollingUp && !canScrollUp) || (isScrollingDown && !canScrollDown)) {
                        event.preventDefault();
                    }
                    
                    // Always stop propagation to prevent triggering reposition
                    event.stopPropagation();
                } else {
                    // If no scroll container, prevent all scrolling
                    event.preventDefault();
                    event.stopPropagation();
                }
            }
        }, { passive: false });

        // Auto-submit form when filter values change (realtime filtering)
        document.addEventListener('change', function(event) {
            if (event.target.closest('[id^="filter-menu-"]') && 
                (event.target.tagName === 'SELECT' || event.target.type === 'checkbox' || event.target.type === 'radio')) {
                // Add visual feedback
                event.target.style.opacity = '0.7';
                
                // Small delay to allow UI feedback, then update filters
                setTimeout(() => {
                    updateFiltersRealtime();
                }, 150);
            }
        });

        // Also listen for input changes for text fields
        document.addEventListener('input', function(event) {
            if (event.target.closest('[id^="filter-menu-"]') && 
                (event.target.type === 'text' || event.target.type === 'search')) {
                // Debounce text input for better performance
                clearTimeout(event.target.debounceTimeout);
                event.target.debounceTimeout = setTimeout(() => {
                    updateFiltersRealtime();
                }, 500);
            }
        });

        // Override any existing context menu toggle functions to close filter menus
        const originalToggleContextMenu = window.toggleContextMenu;
        if (typeof originalToggleContextMenu === 'function') {
            window.toggleContextMenu = function(...args) {
                closeAllFilterMenusWithAnimation();
                return originalToggleContextMenu.apply(this, args);
            };
        }

        // Close filter menus on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && activeFilterMenuId) {
                closeAllFilterMenusWithAnimation();
                event.preventDefault();
                event.stopPropagation();
            }
        });

        // Add global functions to close filter menus
        window.closeAllFilterMenus = closeAllFilterMenus;
        window.closeAllFilterMenusWithAnimation = closeAllFilterMenusWithAnimation;
    </script>
    @endpush
@endonce
