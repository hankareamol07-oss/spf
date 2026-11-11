<section class="spf-map-section" id="learning-map" aria-labelledby="island-map-heading">
    <div class="spf-section-heading">
        <div><span class="spf-eyebrow">प्रत्येक पाऊल, एक नवी गोष्ट</span><h2 id="island-map-heading">माझे अध्ययन बेट</h2></div>
        <span class="spf-map-key"><i class="bi bi-star-fill" aria-hidden="true"></i> पूर्ण <i class="bi bi-lock-fill" aria-hidden="true"></i> पुढील टप्पा</span>
    </div>
    <div class="spf-map-subjects" role="group" aria-label="नकाशासाठी विषय निवडा">
        @foreach ($learningJourneys as $journey)
            <button type="button" class="spf-subject-choice" data-journey-target="journey-{{ $journey['subject']->id }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}" aria-controls="journey-{{ $journey['subject']->id }}">
                <span aria-hidden="true">{{ match ($journey['subject']->code) { 'MARATHI' => 'अ', 'ENGLISH' => 'ABC', default => '१२३' } }}</span>
                {{ $journey['subject']->name_marathi ?: $journey['subject']->name }}
            </button>
        @endforeach
    </div>
    @forelse ($learningJourneys as $journey)
        <div id="journey-{{ $journey['subject']->id }}" class="spf-ocean" data-journey-panel @if(!$loop->first) hidden @endif role="region" aria-label="{{ $journey['subject']->name_marathi }} अध्ययन नकाशा">
            <div class="spf-ocean-heading"><i class="bi bi-compass" aria-hidden="true"></i><span>{{ $journey['subject']->name_marathi }} साहस</span><small>छोटे टप्पे, मोठी प्रगती</small></div>
            @if ($journey['steps']->isEmpty())
                <p class="spf-map-empty">या विषयासाठी अद्याप कौशल्ये प्रकाशित केलेली नाहीत.</p>
            @else
                <ol class="spf-island-path">
                    @foreach ($journey['steps'] as $step)
                        <li class="spf-island-step spf-island-{{ $step['state'] }}">
                            <div class="spf-island">
                                @include('components.student-island')
                                @if ($step['state'] === 'completed')
                                    <span class="spf-earned-stars" aria-label="कौशल्य पूर्ण"><i class="bi bi-star-fill" aria-hidden="true"></i><i class="bi bi-star-fill" aria-hidden="true"></i><i class="bi bi-star-fill" aria-hidden="true"></i></span>
                                @endif
                                @if ($step['state'] !== 'locked' && $step['activity'] && $enrollment)
                                    <form action="{{ route('practice.start', $step['activity']) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="spf-level-button" aria-describedby="level-label-{{ $step['skill']->id }}" aria-label="{{ $step['state'] === 'completed' ? 'पुन्हा सराव' : 'सराव सुरू करा' }}: {{ $step['skill']->name_marathi ?: $step['skill']->name }}">
                                            @if ($step['state'] === 'completed')<i class="bi bi-check-lg" aria-hidden="true"></i>@else{{ $loop->iteration }}@endif
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="spf-level-button" disabled aria-describedby="level-label-{{ $step['skill']->id }}">
                                        @if ($step['state'] === 'locked')<i class="bi bi-lock-fill" aria-hidden="true"></i>@else{{ $loop->iteration }}@endif
                                    </button>
                                @endif
                            </div>
                            <div class="spf-island-caption" id="level-label-{{ $step['skill']->id }}">
                                <strong>{{ $step['skill']->name_marathi ?: $step['skill']->name }}</strong>
                                <span>
                                    @if ($step['state'] === 'locked')आधीचा टप्पा पूर्ण करूया
                                    @elseif (! $enrollment)सक्रिय प्रवेशासाठी मार्गदर्शकाशी संपर्क करा
                                    @elseif (! $step['activity'])या कौशल्याचा सराव अद्याप प्रकाशित नाही
                                    @elseif ($step['state'] === 'completed')पूर्ण झाले · पुन्हा सराव करूया
                                    @else तयार आहेस? सुरू करूया!@endif
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ol>
                <div class="spf-map-finish"><i class="bi bi-trophy-fill" aria-hidden="true"></i><strong>प्रत्येक प्रयत्न महत्त्वाचा आहे!</strong><span>शिकत राहा, पुढे जात राहा.</span></div>
            @endif
        </div>
    @empty
        <div class="alert alert-info">विषय उपलब्ध झाल्यावर तुझा अध्ययन नकाशा येथे दिसेल.</div>
    @endforelse
</section>
