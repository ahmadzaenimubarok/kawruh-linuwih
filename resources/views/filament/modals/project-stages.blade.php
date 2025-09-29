<div class="space-y-4">
    @if($stages->count() > 0)
        <div class="grid gap-4 mb-4">
            @foreach($stages as $stage)
                @php
                    // Get student progress for this stage
                    $studentProject = \App\Models\StudentProject::where('user_id', $currentUserId)
                        ->where('project_id', $project->id)
                        ->first();
                    
                    $studentStage = null;
                    $status = 'not_started';
                    
                    if ($studentProject) {
                        $studentStage = \App\Models\StudentProjectStage::where('student_project_id', $studentProject->id)
                            ->where('stage_id', $stage->id)
                            ->first();
                        
                        if ($studentStage) {
                            $status = $studentStage->status;
                        }
                    }
                    
                    // Define status colors and icons
                    $statusConfig = [
                        'not_started' => ['color' => 'gray', 'icon' => 'heroicon-o-clock', 'label' => 'Not Started'],
                        'in_progress' => ['color' => 'blue', 'icon' => 'heroicon-o-play', 'label' => 'In Progress'],
                        'submitted' => ['color' => 'yellow', 'icon' => 'heroicon-o-paper-airplane', 'label' => 'Submitted'],
                        'reviewed' => ['color' => 'purple', 'icon' => 'heroicon-o-eye', 'label' => 'Reviewed'],
                        'completed' => ['color' => 'green', 'icon' => 'heroicon-o-check-circle', 'label' => 'Completed']
                    ];
                    
                    $currentStatus = $statusConfig[$status] ?? $statusConfig['not_started'];
                @endphp
                
                <x-filament::card>
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-flex items-center justify-center w-8 h-8 bg-blue-100 text-gray-900 rounded-full text-sm font-semibold">
                                    {{ $stage->order_no }}
                                </span>
                                <h3 class="text-lg font-semibold">
                                    {{ $stage->title }}
                                </h3>
                                
                                <!-- Status Badge -->
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full
                                    @if($currentStatus['color'] === 'gray') bg-gray-100 text-gray-600
                                    @elseif($currentStatus['color'] === 'blue') bg-blue-100 text-blue-600
                                    @elseif($currentStatus['color'] === 'yellow') bg-yellow-100 text-yellow-600
                                    @elseif($currentStatus['color'] === 'purple') bg-purple-100 text-purple-600
                                    @elseif($currentStatus['color'] === 'green') bg-green-100 text-green-600
                                    @endif">
                                    <x-filament::icon
                                        :icon="$currentStatus['icon']"
                                        class="w-3 h-3"
                                    />
                                    {{ $currentStatus['label'] }}
                                </span>
                            </div>
                            
                            <!-- Additional Status Information -->
                            @if($studentStage)
                                <div class="mt-3 space-y-2">
                                    @if($studentStage->submission_link)
                                        <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <x-filament::icon
                                                icon="heroicon-o-link"
                                                class="w-4 h-4"
                                            />
                                            <span>Submission:</span>
                                            <a href="{{ $studentStage->submission_link }}" 
                                               target="_blank" 
                                               class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200 underline">
                                                View Submission
                                            </a>
                                        </div>
                                    @endif
                                    
                                    @if($studentStage->feedback)
                                        <div class="p-3 bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800">
                                            <div class="flex items-start gap-2">
                                                <x-filament::icon
                                                    icon="heroicon-o-chat-bubble-left-ellipsis"
                                                    class="w-4 h-4 text-amber-600 dark:text-amber-400 mt-0.5"
                                                />
                                                <div>
                                                    <p class="text-sm font-medium text-amber-800 dark:text-amber-200">Feedback:</p>
                                                    <p class="text-sm text-amber-700 dark:text-amber-300 mt-1">{{ $studentStage->feedback }}</p>
                                                    @if($studentStage->reviewed_at)
                                                        <p class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                                                            Reviewed on {{ $studentStage->reviewed_at->format('M d, Y') }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="ml-4 flex-shrink-0">
                            <div class="flex items-center gap-2">
                                <x-filament::icon
                                    icon="heroicon-o-academic-cap"
                                    class="w-5 h-5 text-gray-400"
                                />
                                <span class="text-sm">
                                    Stage {{ $stage->order_no }}
                                </span>
                            </div>
                        </div>
                    </div>
                </x-filament::card>
            @endforeach
        </div>
        
        @php
            // Calculate progress statistics
            $totalStages = $stages->count();
            $completedStages = 0;
            $inProgressStages = 0;
            $notStartedStages = 0;
            
            $studentProject = \App\Models\StudentProject::where('user_id', $currentUserId)
                ->where('project_id', $project->id)
                ->first();
            
            if ($studentProject) {
                foreach ($stages as $stage) {
                    $studentStage = \App\Models\StudentProjectStage::where('student_project_id', $studentProject->id)
                        ->where('stage_id', $stage->id)
                        ->first();
                    
                    if ($studentStage) {
                        if ($studentStage->status === 'completed') {
                            $completedStages++;
                        } elseif (in_array($studentStage->status, ['in_progress', 'submitted', 'reviewed'])) {
                            $inProgressStages++;
                        } else {
                            $notStartedStages++;
                        }
                    } else {
                        $notStartedStages++;
                    }
                }
            } else {
                $notStartedStages = $totalStages;
            }
            
            $progressPercentage = $totalStages > 0 ? round(($completedStages / $totalStages) * 100) : 0;
        @endphp
        
        <x-filament::card>
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <x-filament::icon
                        icon="heroicon-o-chart-bar"
                        class="w-5 h-5 text-blue-600 dark:text-blue-400"
                    />
                    <h4 class="text-sm font-semibold">Progress Overview</h4>
                </div>
                
                <!-- Progress Bar -->
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Overall Progress</span>
                        <span class="font-medium text-gray-800 dark:text-gray-900">{{ $progressPercentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                             style="width: {{ $progressPercentage }}%"></div>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="flex justify-between items-center bg-gray-100 rounded-lg p-6">
                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-green-600 mb-1">{{ $completedStages }}</div>
                        <div class="text-sm text-gray-600">Completed</div>
                    </div>
                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-blue-600 mb-1">{{ $inProgressStages }}</div>
                        <div class="text-sm text-gray-600">In Progress</div>
                    </div>
                    <div class="text-center flex-1">
                        <div class="text-2xl font-bold text-gray-600 mb-1">{{ $notStartedStages }}</div>
                        <div class="text-sm text-gray-600">Not Started</div>
                    </div>
                </div>
                
                <div class="pt-2 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Total Stages:</strong> {{ $totalStages }} stages available for this project
                    </p>
                </div>
            </div>
        </x-filament::card>
    @else
        <x-filament::card>
            <div class="text-center py-8">
                <x-filament::icon
                    icon="heroicon-o-document-text"
                    class="w-12 h-12 text-gray-400 mx-auto mb-4"
                />
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    No Stages Available
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    This project doesn't have any stages configured yet.
                </p>
            </div>
        </x-filament::card>
    @endif
</div>
