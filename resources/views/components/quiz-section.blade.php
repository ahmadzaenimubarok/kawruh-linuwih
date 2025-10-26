{{-- AI Quiz Component --}}
<x-filament::section>
    <x-slot name="heading">
        <div class="flex items-center gap-2">
            <x-filament::icon 
                icon="heroicon-m-academic-cap" 
                class="w-5 h-5"
            />
            <span>Knowledge Quiz</span>
        </div>
    </x-slot>

    <x-slot name="description">
        Test your understanding of this stage's material with our interactive quiz.
    </x-slot>

    <div x-data="{
        quizStarted: false,
        quizCompleted: false,
        currentQuestion: 0,
        selectedAnswer: null,
        answers: [],
        score: 0,
        showResults: false,
        isGenerating: false,
        questions: $wire.entangle('questions'),
        
        get totalQuestions() {
            return this.questions.length;
        },
        
        get currentQuestionData() {
            return this.questions[this.currentQuestion];
        },
        
        get progressPercentage() {
            return ((this.currentQuestion + 1) / this.totalQuestions) * 100;
        },
        
        get scorePercentage() {
            return Math.round((this.score / this.totalQuestions) * 100);
        },
        
        async startQuiz() {
            // Set loading state
            this.isGenerating = true;
            
            try {
                // Call Livewire method to generate questions
                await $wire.generateQuestion();
                
                // Start quiz after questions are generated
                this.quizStarted = true;
                this.quizCompleted = false;
                this.resetAnswers();
            } catch (error) {
                console.error('Error generating questions:', error);
                alert('Failed to generate questions. Please try again.');
            } finally {
                this.isGenerating = false;
            }
        },
        
        resetAnswers() {
            this.answers = new Array(this.totalQuestions).fill(null);
            this.selectedAnswer = null;
            this.currentQuestion = 0;
            this.score = 0;
            this.showResults = false;
        },
        
        selectAnswer(index) {
            this.selectedAnswer = index;
        },
        
        isAnswerSelected(index) {
            return this.selectedAnswer === index;
        },
        
        canGoNext() {
            return this.selectedAnswer !== null && this.currentQuestion < this.totalQuestions - 1;
        },
        
        canFinish() {
            return this.selectedAnswer !== null && this.currentQuestion === this.totalQuestions - 1;
        },
        
        nextQuestion() {
            if (this.selectedAnswer === null) return;
            
            // Save current answer
            this.answers[this.currentQuestion] = this.selectedAnswer;
            
            // Move to next question
            if (this.currentQuestion < this.totalQuestions - 1) {
                this.currentQuestion++;
                this.selectedAnswer = this.answers[this.currentQuestion];
            }
        },
        
        previousQuestion() {
            if (this.currentQuestion > 0) {
                // Save current answer
                this.answers[this.currentQuestion] = this.selectedAnswer;
                
                // Move to previous question
                this.currentQuestion--;
                this.selectedAnswer = this.answers[this.currentQuestion];
            }
        },
        
        finishQuiz() {
            if (this.selectedAnswer === null) return;
            
            // Save last answer
            this.answers[this.currentQuestion] = this.selectedAnswer;
            
            // Calculate score
            this.calculateScore();
            
            // Show results
            this.quizCompleted = true;
            this.showResults = true;
        },
        
        calculateScore() {
            this.score = 0;
            for (let i = 0; i < this.totalQuestions; i++) {
                if (this.answers[i] === this.questions[i].correctIndex) {
                    this.score++;
                }
            }
        },
        
        isCorrectAnswer(questionIndex, answerIndex) {
            return this.questions[questionIndex].correctIndex === answerIndex;
        },
        
        getUserAnswer(questionIndex) {
            return this.answers[questionIndex];
        },
        
        resetQuiz() {
            this.quizStarted = false;
            this.quizCompleted = false;
            this.showResults = false;
            this.resetAnswers();
        }
    }" class="space-y-6">
        {{-- Quiz Start Screen --}}
        <div x-show="!quizStarted && !quizCompleted" class="text-center py-8">
            <div class="bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-950 dark:to-primary-900 rounded-xl p-8 border border-primary-200 dark:border-primary-800 shadow-sm">
                <x-filament::icon 
                    icon="heroicon-o-puzzle-piece" 
                    class="w-16 h-16 mx-auto text-primary-600 dark:text-primary-400 mb-4"
                />
                <h3 class="text-xl font-semibold mb-2">
                    Ready to Test Your Knowledge?
                </h3>
                <p class="mb-6">
                    This quiz contains 5 multiple-choice questions to assess your understanding of the material.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 text-sm">
                    <div class="flex items-center justify-center gap-2 text-primary-700 dark:text-primary-300 bg-white/50 dark:bg-gray-800/50 rounded-lg py-2 px-3">
                        <x-filament::icon icon="heroicon-o-clock" class="w-4 h-4" />
                        <span class="font-medium">~5 minutes</span>
                    </div>
                    <div class="flex items-center justify-center gap-2 text-success-700 dark:text-success-300 bg-white/50 dark:bg-gray-800/50 rounded-lg py-2 px-3">
                        <x-filament::icon icon="heroicon-o-question-mark-circle" class="w-4 h-4" />
                        <span class="font-medium">5 questions</span>
                    </div>
                    <div class="flex items-center justify-center gap-2 text-warning-700 dark:text-warning-300 bg-white/50 dark:bg-gray-800/50 rounded-lg py-2 px-3">
                        <x-filament::icon icon="heroicon-o-star" class="w-4 h-4" />
                        <span class="font-medium">Multiple choice</span>
                    </div>
                </div>
                <x-filament::button
                    color="primary"
                    size="lg"
                    ::icon="isGenerating ? 'heroicon-m-arrow-path' : 'heroicon-m-play'"
                    ::disabled="isGenerating"
                    @click="startQuiz()"
                >
                    <span x-show="!isGenerating">Start Quiz</span>
                    <span x-show="isGenerating">Generating Questions...</span>
                </x-filament::button>
            </div>
        </div>

        {{-- Quiz Questions --}}
        <div x-show="quizStarted && !quizCompleted" class="space-y-6">
            {{-- Progress Bar --}}
            <div class="bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-6 shadow-inner">
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 dark:from-primary-600 dark:to-primary-500 h-3 rounded-full transition-all duration-500 ease-out shadow-sm" 
                     :style="`width: ${((currentQuestion + 1) / totalQuestions) * 100}%`"></div>
            </div>

            {{-- Question Counter --}}
            <div class="flex justify-between items-center mb-4">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                    Question <span class="text-primary-600 dark:text-primary-400 font-bold" x-text="currentQuestion + 1"></span> of <span x-text="totalQuestions"></span>
                </span>
                <x-filament::badge color="primary" size="md">
                    <span x-text="`Answered: ${answers.filter(a => a !== null).length}/${totalQuestions}`"></span>
                </x-filament::badge>
            </div>

            {{-- Current Question --}}
            <x-filament::card class="shadow-md border-2 border-gray-100 dark:border-gray-800">
                <div class="space-y-5">
                    <div class="bg-gradient-to-r from-primary-50 to-transparent dark:from-primary-950 dark:to-transparent rounded-lg p-4 border-l-4 border-primary-500">
                        <h4 class="text-lg font-semibold" x-text="currentQuestionData.question"></h4>
                    </div>
                    
                    <div class="space-y-3">
                        <template x-for="(option, index) in currentQuestionData.options" :key="index">
                            <label class="flex items-start gap-3 p-4 rounded-lg border-2 cursor-pointer transition-all duration-200 hover:shadow-md hover:scale-[1.01]"
                                   :class="isAnswerSelected(index)
                                       ? 'border-primary-500 bg-primary-50 dark:bg-primary-950/50 shadow-md ring-2 ring-primary-500/20' 
                                       : 'border-gray-200 dark:border-gray-700 hover:border-primary-300 dark:hover:border-primary-700 hover:bg-gray-50 dark:hover:bg-gray-800/50'"
                                   @click="selectAnswer(index)">
                                <input type="radio" 
                                       :name="`question_${currentQuestion}`"
                                       :value="index"
                                       :checked="isAnswerSelected(index)"
                                       class="mt-1 text-primary-600 focus:ring-primary-500 dark:focus:ring-primary-600 cursor-pointer">
                                <span class="font-medium flex-1" x-text="option"></span>
                            </label>
                        </template>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                        <x-filament::button
                            color="gray"
                            icon="heroicon-m-arrow-left"
                            @click="previousQuestion()"
                            x-show="currentQuestion > 0"
                        >
                            Previous
                        </x-filament::button>
                        
                        <div class="flex gap-2 ml-auto">
                            <x-filament::button
                                color="primary"
                                icon-position="after"
                                icon="heroicon-m-arrow-right"
                                @click="nextQuestion()"
                                x-show="currentQuestion < totalQuestions - 1"
                                ::disabled="!canGoNext()"
                            >
                                Next Question
                            </x-filament::button>
                            
                            <x-filament::button
                                color="success"
                                icon="heroicon-m-check"
                                @click="finishQuiz()"
                                x-show="currentQuestion === totalQuestions - 1"
                                ::disabled="!canFinish()"
                            >
                                Finish Quiz
                            </x-filament::button>
                        </div>
                    </div>
                </div>
            </x-filament::card>
        </div>

        {{-- Quiz Results --}}
        <div x-show="quizCompleted" class="text-center py-8">
            <x-filament::card class="shadow-lg border-2 border-gray-100 dark:border-gray-800">
                <div class="space-y-6">
                    {{-- Results Header --}}
                    <div>
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 transition-all duration-300"
                             ::class="scorePercentage >= 80 
                                 ? 'bg-gradient-to-br from-yellow-100 to-yellow-200 dark:from-yellow-900/50 dark:to-yellow-800/50' 
                                 : scorePercentage >= 60 
                                     ? 'bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/50 dark:to-primary-800/50' 
                                     : 'bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700'">
                            <x-filament::icon 
                                icon="heroicon-o-trophy" 
                                class="w-12 h-12 transition-colors duration-300"
                                ::class="scorePercentage >= 80 
                                    ? 'text-yellow-600 dark:text-yellow-400' 
                                    : scorePercentage >= 60 
                                        ? 'text-primary-600 dark:text-primary-400' 
                                        : 'text-gray-500 dark:text-gray-400'"
                            />
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Quiz Completed!
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300 mb-2">
                            You scored <span class="font-bold text-primary-600 dark:text-primary-400 text-2xl" x-text="score"></span> out of <span class="font-semibold text-lg" x-text="totalQuestions"></span>
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            (<span class="font-semibold" x-text="scorePercentage"></span>%)
                        </p>
                    </div>

                    {{-- Score Badge --}}
                    <div class="flex justify-center">
                        <div x-show="scorePercentage >= 80" class="animate-bounce">
                            <x-filament::badge 
                                size="lg"
                                color="success"
                            >
                                Excellent! 🎉
                            </x-filament::badge>
                        </div>
                        <div x-show="scorePercentage >= 60 && scorePercentage < 80">
                            <x-filament::badge 
                                size="lg"
                                color="primary"
                            >
                                Good Job! 👍
                            </x-filament::badge>
                        </div>
                        <div x-show="scorePercentage < 60">
                            <x-filament::badge 
                                size="lg"
                                color="warning"
                            >
                                Keep Learning! 📚
                            </x-filament::badge>
                        </div>
                    </div>

                    {{-- Performance Message --}}
                    <div class="rounded-lg p-4 border-2 transition-colors duration-300"
                         ::class="scorePercentage >= 80 
                             ? 'bg-success-50 dark:bg-success-950/30 border-success-200 dark:border-success-800' 
                             : scorePercentage >= 60 
                                 ? 'bg-primary-50 dark:bg-primary-950/30 border-primary-200 dark:border-primary-800' 
                                 : 'bg-warning-50 dark:bg-warning-950/30 border-warning-200 dark:border-warning-800'">
                        <p class="text-sm font-medium transition-colors duration-300"
                           ::class="scorePercentage >= 80 
                               ? 'text-success-800 dark:text-success-200' 
                               : scorePercentage >= 60 
                                   ? 'text-primary-800 dark:text-primary-200' 
                                   : 'text-warning-800 dark:text-warning-200'">
                            <span x-show="scorePercentage >= 80">Outstanding work! You have a solid understanding of the material.</span>
                            <span x-show="scorePercentage >= 60 && scorePercentage < 80">Good effort! You understand most of the concepts well.</span>
                            <span x-show="scorePercentage < 60">Consider reviewing the material again to strengthen your understanding.</span>
                        </p>
                    </div>
                    
                    {{-- Review Answers Section --}}
                    <div class="text-left space-y-3 max-h-96 overflow-y-auto border-t border-gray-200 dark:border-gray-700 pt-4">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-3 sticky top-0 bg-white dark:bg-gray-900 py-2">Review Your Answers:</h4>
                        <template x-for="(question, qIndex) in questions" :key="qIndex">
                            <div class="border rounded-lg p-4 transition-colors"
                                 ::class="getUserAnswer(qIndex) === question.correctIndex 
                                     ? 'border-success-300 dark:border-success-700 bg-success-50 dark:bg-success-950/20' 
                                     : 'border-danger-300 dark:border-danger-700 bg-danger-50 dark:bg-danger-950/20'">
                                <div class="flex items-start gap-2 mb-2">
                                    <x-filament::icon 
                                        icon="heroicon-m-check-circle"
                                        class="w-5 h-5 flex-shrink-0 mt-0.5"
                                        ::class="getUserAnswer(qIndex) === question.correctIndex 
                                            ? 'text-success-600 dark:text-success-400' 
                                            : 'text-danger-600 dark:text-danger-400'"
                                        x-show="getUserAnswer(qIndex) === question.correctIndex"
                                    />
                                    <x-filament::icon 
                                        icon="heroicon-m-x-circle"
                                        class="w-5 h-5 flex-shrink-0 mt-0.5 text-danger-600 dark:text-danger-400"
                                        x-show="getUserAnswer(qIndex) !== question.correctIndex"
                                    />
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-2" x-text="`Q${qIndex + 1}: ${question.question}`"></p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">
                                            <span class="font-semibold">Your answer:</span> 
                                            <span x-text="question.options[getUserAnswer(qIndex)]"></span>
                                        </p>
                                        <p class="text-xs mb-2" 
                                           ::class="getUserAnswer(qIndex) === question.correctIndex 
                                               ? 'text-success-700 dark:text-success-300' 
                                               : 'text-danger-700 dark:text-danger-300'">
                                            <span class="font-semibold">Correct answer:</span> 
                                            <span x-text="question.options[question.correctIndex]"></span>
                                        </p>
                                        <p class="text-xs text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 rounded p-2 border border-gray-200 dark:border-gray-700">
                                            <span class="font-semibold">💡 Explanation:</span> 
                                            <span x-text="question.explanation"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex justify-center gap-3 pt-2">
                        <x-filament::button
                            color="gray"
                            icon="heroicon-m-arrow-path"
                            @click="resetQuiz()"
                        >
                            Retake Quiz
                        </x-filament::button>
                        
                        <x-filament::button
                            color="success"
                            icon="heroicon-m-arrow-right"
                            icon-position="after"
                            @click="$wire.nextStage()"
                            x-show="scorePercentage >= 60"
                        >
                            Continue to Next Stage
                        </x-filament::button>
                    </div>
                </div>
            </x-filament::card>
        </div>
    </div>
</x-filament::section>