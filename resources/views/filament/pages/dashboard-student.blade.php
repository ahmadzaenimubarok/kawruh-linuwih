<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Welcome Section --}}
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 dark:from-blue-600 dark:to-purple-700 rounded-lg p-6 text-white">
            <h2 class="text-2xl font-bold mb-2">Welcome to Kawruh Linuwih</h2>
            <p class="text-blue-100 dark:text-blue-200">Discover and start learning from our collection of educational projects. Choose a project that matches your skill level and begin your learning journey.</p>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <x-filament::card>
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <x-heroicon-o-star class="w-6 h-6 text-green-600 dark:text-green-700" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-muted">Beginner Projects</p>
                        <p class="text-2xl font-semibold text-heading">
                            {{ \App\Models\Project::where('difficulty_level', 'beginner')->count() }}
                        </p>
                    </div>
                </div>
            </x-filament::card>

            
            <x-filament::card>
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                        <x-heroicon-o-fire class="w-6 h-6 text-yellow-600 dark:text-yellow-700" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-muted">Intermediate Projects</p>
                        <p class="text-2xl font-semibold text-heading">{{ \App\Models\Project::where('difficulty_level', 'intermediate')->count() }}</p>
                    </div>
                </div>
            </x-filament::card>
            
            <x-filament::card>
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                        <x-heroicon-o-bolt class="w-6 h-6 text-red-600 dark:text-red-700" />
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-muted">Advanced Projects</p>
                        <p class="text-2xl font-semibold text-heading">{{ \App\Models\Project::where('difficulty_level', 'advanced')->count() }}</p>
                    </div>
                </div>
            </x-filament::card>
        </div>

        {{-- Projects Table --}}
        <x-filament::section>
            <x-slot name="heading">
                Available Projects
            </x-slot>

            <x-slot name="description">
                Browse and start learning from our collection of educational projects
            </x-slot>
            
            {{ $this->table }}
        </x-filament::section>
    </div>
</x-filament-panels::page>
