<x-filament-panels::page>
    @if($stage)
        <div class="space-y-6">
            {{-- Stage Header --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center justify-between w-full">
                        <div class="flex-1">
                            <h2 class="text-xl font-semibold">
                                {{ $stage->title }}
                            </h2>
                            <x-filament::badge color="info" class="mt-2">
                                Stage {{ $stage->order_no }}
                            </x-filament::badge>
                        </div>
                    </div>
                </x-slot>

                <x-slot name="description">
                    <div class="flex items-center gap-2">
                        <x-filament::icon 
                            icon="heroicon-m-folder" 
                            class="w-4 h-4"
                        />
                        <span>{{ $stage->project->title ?? 'Unknown Project' }}</span>
                    </div>
                </x-slot>

                {{-- Stage Status and Information --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    {{-- Status Card --}}
                    <x-filament::card>
                        <div class="text-center">
                            @php
                                // For now, using static status - can be made dynamic later
                                $status = 'in_progress'; // This should come from student progress
                                $statusConfig = [
                                    'not_started' => ['color' => 'gray', 'icon' => 'heroicon-o-clock', 'label' => 'Not Started'],
                                    'in_progress' => ['color' => 'blue', 'icon' => 'heroicon-o-play', 'label' => 'In Progress'],
                                    'submitted' => ['color' => 'yellow', 'icon' => 'heroicon-o-paper-airplane', 'label' => 'Submitted'],
                                    'reviewed' => ['color' => 'purple', 'icon' => 'heroicon-o-eye', 'label' => 'Reviewed'],
                                    'completed' => ['color' => 'green', 'icon' => 'heroicon-o-check-circle', 'label' => 'Completed']
                                ];
                                $currentStatus = $statusConfig[$status] ?? $statusConfig['not_started'];
                            @endphp
                            
                            <div class="mb-2">
                                <span class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium rounded-full
                                    @if($currentStatus['color'] === 'gray') bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300
                                    @elseif($currentStatus['color'] === 'blue') bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300
                                    @elseif($currentStatus['color'] === 'yellow') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300
                                    @elseif($currentStatus['color'] === 'purple') bg-purple-100 text-purple-700 dark:bg-purple-900 dark:text-purple-300
                                    @elseif($currentStatus['color'] === 'green') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300
                                    @endif">
                                    <x-filament::icon
                                        :icon="$currentStatus['icon']"
                                        class="w-4 h-4"
                                    />
                                    {{ $currentStatus['label'] }}
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Current Status</p>
                        </div>
                    </x-filament::card>

                    {{-- Difficulty Level --}}
                    <x-filament::card>
                        <div class="text-center">
                            @php
                                $difficulty = $stage->project->difficulty ?? 'beginner';
                                $difficultyConfig = [
                                    'beginner' => ['color' => 'green', 'label' => 'Beginner'],
                                    'intermediate' => ['color' => 'yellow', 'label' => 'Intermediate'],
                                    'advanced' => ['color' => 'red', 'label' => 'Advanced']
                                ];
                                $difficultyInfo = $difficultyConfig[$difficulty] ?? $difficultyConfig['beginner'];
                            @endphp
                            
                            <div class="mb-2">
                                <x-filament::badge :color="$difficultyInfo['color']" size="lg">
                                    {{ $difficultyInfo['label'] }}
                                </x-filament::badge>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Difficulty Level</p>
                        </div>
                    </x-filament::card>

                    {{-- Estimated Time --}}
                    <x-filament::card>
                        <div class="text-center">
                            <div class="mb-2">
                                <div class="flex items-center justify-center gap-1 text-lg font-semibold text-gray-900 dark:text-white">
                                    <x-filament::icon 
                                        icon="heroicon-o-clock" 
                                        class="w-5 h-5 text-gray-500"
                                    />
                                    {{ $stage->estimated_time ?? '30' }} min
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Estimated Time</p>
                        </div>
                    </x-filament::card>
                </div>

                {{-- Learning Objectives --}}
                @if($stage->learning_objectives ?? false)
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                            <x-filament::icon 
                                icon="heroicon-o-academic-cap" 
                                class="w-4 h-4"
                            />
                            Learning Objectives
                        </h4>
                        <div class="bg-blue-50 dark:bg-blue-950 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                            <ul class="text-sm text-blue-800 dark:text-blue-200 space-y-1">
                                @foreach(explode("\n", $stage->learning_objectives) as $objective)
                                    @if(trim($objective))
                                        <li class="flex items-start gap-2">
                                            <span class="text-blue-500 mt-1">•</span>
                                            <span>{{ trim($objective) }}</span>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Prerequisites (if any) --}}
                @if($stage->prerequisites ?? false)
                    <div class="mb-6">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                            <x-filament::icon 
                                icon="heroicon-o-exclamation-triangle" 
                                class="w-4 h-4 text-amber-500"
                            />
                            Prerequisites
                        </h4>
                        <div class="bg-amber-50 dark:bg-amber-950 rounded-lg p-4 border border-amber-200 dark:border-amber-800">
                            <p class="text-sm text-amber-800 dark:text-amber-200">
                                {{ $stage->prerequisites }}
                            </p>
                        </div>
                    </div>
                @endif
            </x-filament::section>

            {{-- Learning Material Section --}}
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-filament::icon 
                            icon="heroicon-m-document-text" 
                            class="w-5 h-5"
                        />
                        <span>Learning Material</span>
                    </div>
                </x-slot>

                @if($stage->instructions)
                    <div class="prose prose-sm dark:prose-invert max-w-none">
                        <x-filament::card>
                            <div class="prose-content">
                                <style>
                                    /* Base typography */
                                    .prose-content {
                                        line-height: 1.7;
                                        color: #374151;
                                    }
                                    .dark .prose-content {
                                        color: #d1d5db;
                                    }

                                    /* Headings */
                                    .prose-content h2 {
                                        color: #1f2937;
                                        font-size: 1.75rem;
                                        font-weight: 700;
                                        margin-top: 2.5rem;
                                        margin-bottom: 1.25rem;
                                        border-bottom: 2px solid #e5e7eb;
                                        padding-bottom: 0.5rem;
                                    }
                                    .dark .prose-content h2 {
                                        color: #f9fafb;
                                        border-bottom-color: #374151;
                                    }
                                    
                                    .prose-content h3 {
                                        color: #1f2937;
                                        font-size: 1.375rem;
                                        font-weight: 600;
                                        margin-top: 2rem;
                                        margin-bottom: 1rem;
                                    }
                                    .dark .prose-content h3 {
                                        color: #f3f4f6;
                                    }

                                    /* Paragraphs */
                                    .prose-content p {
                                        margin-bottom: 1.25rem;
                                        line-height: 1.7;
                                    }

                                    /* Lists */
                                    .prose-content ul, .prose-content ol {
                                        margin: 1.25rem 0;
                                        padding-left: 1.5rem;
                                    }
                                    .prose-content ul {
                                        list-style-type: disc;
                                    }
                                    .prose-content ol {
                                        list-style-type: decimal;
                                    }
                                    .prose-content li {
                                        margin-bottom: 0.5rem;
                                        line-height: 1.6;
                                    }
                                    .prose-content li p {
                                        margin-bottom: 0.5rem;
                                    }
                                    .prose-content li:last-child {
                                        margin-bottom: 0;
                                    }

                                    /* Nested lists */
                                    .prose-content ul ul, .prose-content ol ol, 
                                    .prose-content ul ol, .prose-content ol ul {
                                        margin-top: 0.5rem;
                                        margin-bottom: 0.5rem;
                                    }
                                    .prose-content ul ul {
                                        list-style-type: circle;
                                    }

                                    /* Code styling */
                                    .prose-content code {
                                        background-color: #f1f5f9;
                                        color: #1e293b;
                                        padding: 0.125rem 0.375rem;
                                        border-radius: 0.25rem;
                                        font-family: 'Courier New', Consolas, 'Liberation Mono', monospace;
                                        font-size: 0.875rem;
                                        font-weight: 500;
                                    }
                                    .dark .prose-content code {
                                        background-color: #374151;
                                        color: #e5e7eb;
                                    }

                                    .prose-content pre {
                                        background-color: #f8fafc;
                                        border: 1px solid #e2e8f0;
                                        border-radius: 0.5rem;
                                        padding: 1.25rem;
                                        overflow-x: auto;
                                        font-family: 'Courier New', Consolas, 'Liberation Mono', monospace;
                                        font-size: 0.875rem;
                                        line-height: 1.6;
                                        margin: 1.5rem 0;
                                    }
                                    .dark .prose-content pre {
                                        background-color: #1f2937;
                                        border-color: #374151;
                                        color: #e5e7eb;
                                    }
                                    .prose-content pre code {
                                        background-color: transparent;
                                        padding: 0;
                                        border-radius: 0;
                                        color: inherit;
                                    }

                                    /* Strong/Bold text */
                                    .prose-content strong {
                                        font-weight: 700;
                                        color: #1f2937;
                                    }
                                    .dark .prose-content strong {
                                        color: #f9fafb;
                                    }

                                    /* Emphasis/Italic text */
                                    .prose-content em {
                                        font-style: italic;
                                    }

                                    /* Blockquotes */
                                    .prose-content blockquote {
                                        border-left: 4px solid #3b82f6;
                                        padding-left: 1rem;
                                        margin: 1.5rem 0;
                                        font-style: italic;
                                        color: #6b7280;
                                        background-color: #f8fafc;
                                        padding: 1rem;
                                        border-radius: 0.375rem;
                                    }
                                    .dark .prose-content blockquote {
                                        border-left-color: #60a5fa;
                                        color: #9ca3af;
                                        background-color: #1f2937;
                                    }

                                    /* Links */
                                    .prose-content a {
                                        color: #3b82f6;
                                        text-decoration: underline;
                                        text-decoration-color: #93c5fd;
                                        text-underline-offset: 2px;
                                    }
                                    .prose-content a:hover {
                                        color: #1d4ed8;
                                        text-decoration-color: #3b82f6;
                                    }
                                    .dark .prose-content a {
                                        color: #60a5fa;
                                        text-decoration-color: #3b82f6;
                                    }
                                    .dark .prose-content a:hover {
                                        color: #93c5fd;
                                        text-decoration-color: #60a5fa;
                                    }

                                    /* Spacing adjustments */
                                    .prose-content > *:first-child {
                                        margin-top: 0;
                                    }
                                    .prose-content > *:last-child {
                                        margin-bottom: 0;
                                    }
                                </style>
                                {!! $stage->instructions !!}
                            </div>
                        </x-filament::card>
                    </div>
                @else
                    <div class="text-center py-12">
                        <x-filament::icon 
                            icon="heroicon-o-document-text" 
                            class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-4"
                        />
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            No instructions available for this stage.
                        </p>
                    </div>
                @endif
            </x-filament::section>

            {{-- AI Quiz Section --}}
            <x-quiz-section :questions="$questions" />

            {{-- Navigation Actions --}}
            <x-filament::section>
                <div class="flex justify-between items-center">
                    <x-filament::button
                        color="gray"
                        icon="heroicon-m-arrow-left"
                        wire:click="previousStage"
                        :disabled="!$this->hasPreviousStage()"
                    >
                        Previous Stage
                    </x-filament::button>

                    <x-filament::button
                        color="primary"
                        icon-position="after"
                        icon="heroicon-m-arrow-right"
                        wire:click="nextStage"
                        :disabled="!$this->hasNextStage()"
                    >
                        Next Stage
                    </x-filament::button>
                </div>
            </x-filament::section>

        </div>

    @else
        {{-- No Stage Available --}}
        <x-filament::section>
            <div class="text-center py-12">
                <x-filament::icon 
                    icon="heroicon-o-document-text" 
                    class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4"
                />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    No Stage Content Available
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                    There are no stages available to display at the moment.
                </p>
                <x-filament::button
                    color="primary"
                    tag="a"
                    href="{{ route('filament.admin.resources.projects.index') }}"
                >
                    Back to Projects
                </x-filament::button>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>