<x-layouts.app :title="'मूल्यांकन संपादन — '.$student->user->name">
    <div class="mb-3 d-flex flex-wrap justify-content-between align-items-center gap-2">
        <a class="btn btn-outline-secondary btn-sm" href="{{ route('hpc.show', ['student' => $student, 'term' => $term]) }}">
            <i class="bi bi-arrow-left me-1"></i>मागे जा (Back to Card)
        </a>
        <form method="POST" action="{{ route('hpc.sync-student', ['student' => $student, 'term' => $term]) }}">
            @csrf
            <button type="submit" class="btn btn-warning btn-sm fw-bold">
                <i class="bi bi-arrow-repeat me-1"></i>पूर्व व उत्तर चाचणीनुसार स्वयं-भरा (Auto-fill from Tests)
            </button>
        </form>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-primary text-white p-4">
            <h1 class="h3 fw-bold mb-1">३६०° समग्र मूल्यांकन नोंद / संपादन</h1>
            <p class="mb-0 text-white-50">
                विद्यार्थी: <strong>{{ $student->user->name }}</strong> (हजेरी क्र.: {{ $student->student_number ?? 'STU-'.str_pad($student->id, 4, '0', STR_PAD_LEFT) }}) · इयत्ता ४ थी · सत्र {{ $term }}
            </p>
        </div>

        <form method="POST" action="{{ route('hpc.update', ['student' => $student, 'term' => $term]) }}" class="p-4 bg-white">
            @csrf
            <input type="hidden" name="term" value="{{ $term }}">

            @php
                $self = $evaluation->self_assessment ?? [];
                $peer = $evaluation->peer_assessment ?? [];
                $teacher = $evaluation->teacher_assessment ?? [];
                $mentor = $evaluation->mentor_guidance ?? [];
                $parent = $evaluation->parent_feedback ?? [];
            @endphp

            <!-- Tab 1: Self-Assessment (स्वयं-मूल्यांकन) -->
            <fieldset class="border rounded-4 p-4 mb-4">
                <legend class="float-none w-auto px-3 text-primary fw-bold h5">
                    <span class="fs-4 me-1">🧒</span>स्तंभ १: स्वयं-मूल्यांकन (Self-Assessment)
                </legend>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">मला शाळेत यायला आवडते का?</label>
                        <select name="self_likes_school" class="form-select">
                            <option value="yes" @selected(($self['likes_school'] ?? 'yes') === 'yes')>😊 होय, आनंदाने!</option>
                            <option value="some" @selected(($self['likes_school'] ?? '') === 'some')>😐 काही प्रमाणात</option>
                            <option value="no" @selected(($self['likes_school'] ?? '') === 'no')>🙁 नाही</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">माझा आवडता विषय</label>
                        <input type="text" name="self_favorite_subject" class="form-control" value="{{ old('self_favorite_subject', $self['favorite_subject'] ?? 'गणित व भाषा') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">माझा आवडता डिजिटल खेळ</label>
                        <select name="self_favorite_game" class="form-select">
                            @foreach ([
                                'AppleMath - सफरचंद गणित',
                                'संख्यारेषा उडी (Number Line Jump)',
                                'स्थानिक किंमत घर (Place Value House)',
                                'अपूर्णांक पिझ्झा (Fraction Pizza)',
                                'नाणी व नोटा बाजार (Currency Challenge)',
                                'मराठी शब्दसंग्रह खेळ'
                            ] as $gameTitle)
                                <option value="{{ $gameTitle }}" @selected(($self['favorite_game'] ?? '') === $gameTitle)>{{ $gameTitle }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">मी मित्रांना अभ्यासात मदत करतो का?</label>
                        <select name="self_helps_classmates" class="form-select">
                            <option value="always" @selected(($self['helps_classmates'] ?? 'always') === 'always')>नेहमी मदत करतो</option>
                            <option value="sometimes" @selected(($self['helps_classmates'] ?? '') === 'sometimes')>कधीकधी</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">माझा आजचा संकल्प / ध्येय</label>
                        <input type="text" name="self_my_goal" class="form-control" value="{{ old('self_my_goal', $self['my_goal'] ?? '') }}">
                    </div>
                </div>
            </fieldset>

            <!-- Tab 2: Peer-Assessment (सहाध्यायी मूल्यांकन) -->
            <fieldset class="border rounded-4 p-4 mb-4">
                <legend class="float-none w-auto px-3 text-success fw-bold h5">
                    <span class="fs-4 me-1">🤝</span>स्तंभ २: सहाध्यायी मूल्यांकन (Peer Assessment)
                </legend>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">मूल्यांकन करणारा सहाध्यायी मित्र</label>
                        <select name="peer_student_id" class="form-select">
                            @foreach ($classmates as $cm)
                                <option value="{{ $cm->id }}" @selected($evaluation->peer_student_id == $cm->id)>
                                    {{ $cm->user->name }} ({{ $cm->student_number ?? 'STU-'.$cm->id }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">मित्राचे नाव (दर्शनी नाव)</label>
                        <input type="text" name="peer_name" class="form-control" value="{{ old('peer_name', $peer['peer_name'] ?? '') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">गटात मिळून काम करतो का?</label>
                        <select name="peer_works_in_group" class="form-select">
                            <option value="yes" @selected(($peer['works_in_group'] ?? 'yes') === 'yes')>होय, सहकार्याने</option>
                            <option value="some" @selected(($peer['works_in_group'] ?? '') === 'some')>काही प्रमाणात</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">वस्तू व खेळ वाटून घेतो का?</label>
                        <select name="peer_shares_resources" class="form-select">
                            <option value="yes" @selected(($peer['shares_resources'] ?? 'yes') === 'yes')>होय, नेहमी</option>
                            <option value="some" @selected(($peer['shares_resources'] ?? '') === 'some')>काही वेळा</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">सर्वांशी आदराने बोलतो का?</label>
                        <select name="peer_speaks_politely" class="form-select">
                            <option value="yes" @selected(($peer['speaks_politely'] ?? 'yes') === 'yes')>होय, प्रेमाने</option>
                            <option value="some" @selected(($peer['speaks_politely'] ?? '') === 'some')>काही वेळा</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">मित्राची सर्वात आवडलेली गोष्ट</label>
                        <input type="text" name="peer_best_quality" class="form-control" value="{{ old('peer_best_quality', $peer['best_quality'] ?? '') }}">
                    </div>
                </div>
            </fieldset>

            <!-- Tab 3: Teacher & Mentor Assessment (शिक्षक व मेंतॉर मूल्यांकन) -->
            <fieldset class="border rounded-4 p-4 mb-4">
                <legend class="float-none w-auto px-3 text-dark fw-bold h5">
                    <span class="fs-4 me-1">👨‍🏫</span>स्तंभ ३: शिक्षक व मेंतॉर मूल्यांकन (SCERT निकष)
                </legend>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">जाणीवजागृती स्तर (Awareness)</label>
                        <select name="teacher_awareness_level" class="form-select">
                            <option value="pragat" @selected(($teacher['awareness_level'] ?? '') === 'pragat')>प्रगत (Advanced)</option>
                            <option value="praveen" @selected(($teacher['awareness_level'] ?? '') === 'praveen')>प्रवीण (Proficient)</option>
                            <option value="prarambhik" @selected(($teacher['awareness_level'] ?? '') === 'prarambhik')>प्रारंभिक (Beginner)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">संवेदनशीलता स्तर (Sensitivity)</label>
                        <select name="teacher_sensitivity_level" class="form-select">
                            <option value="pragat" @selected(($teacher['sensitivity_level'] ?? '') === 'pragat')>प्रगत (Advanced)</option>
                            <option value="praveen" @selected(($teacher['sensitivity_level'] ?? '') === 'praveen')>प्रवीण (Proficient)</option>
                            <option value="prarambhik" @selected(($teacher['sensitivity_level'] ?? '') === 'prarambhik')>प्रारंभिक (Beginner)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">सर्जनशीलता स्तर (Creativity)</label>
                        <select name="teacher_creativity_level" class="form-select">
                            <option value="pragat" @selected(($teacher['creativity_level'] ?? '') === 'pragat')>प्रगत (Advanced)</option>
                            <option value="praveen" @selected(($teacher['creativity_level'] ?? '') === 'praveen')>प्रवीण (Proficient)</option>
                            <option value="prarambhik" @selected(($teacher['creativity_level'] ?? '') === 'prarambhik')>प्रारंभिक (Beginner)</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold">एकूण FLN नैपुण्य स्तर</label>
                        <select name="overall_fln_level" class="form-select">
                            <option value="NIPUN_PROFICIENT" @selected(($evaluation->overall_fln_level ?? '') === 'NIPUN_PROFICIENT')>निपुण / प्रवीण (Level 3)</option>
                            <option value="PROGRESSING" @selected(($evaluation->overall_fln_level ?? '') === 'PROGRESSING')>प्रगतीपथावर (Level 2)</option>
                            <option value="FOUNDATIONAL" @selected(($evaluation->overall_fln_level ?? '') === 'FOUNDATIONAL')>पायाभूत (Level 1)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">गणित FLN स्तर</label>
                        <select name="teacher_fln_math_level" class="form-select">
                            <option value="NIPUN_PROFICIENT" @selected(($teacher['fln_math_level'] ?? '') === 'NIPUN_PROFICIENT')>निपुण / प्रवीण (Level 3)</option>
                            <option value="PROGRESSING" @selected(($teacher['fln_math_level'] ?? '') === 'PROGRESSING')>प्रगतीपथावर (Level 2)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">भाषा FLN स्तर</label>
                        <select name="teacher_fln_language_level" class="form-select">
                            <option value="NIPUN_PROFICIENT" @selected(($teacher['fln_language_level'] ?? '') === 'NIPUN_PROFICIENT')>निपुण / प्रवीण (Level 3)</option>
                            <option value="PROGRESSING" @selected(($teacher['fln_language_level'] ?? '') === 'PROGRESSING')>प्रगतीपथावर (Level 2)</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">विद्यार्थ्याची बलस्थाने (Strengths)</label>
                        <textarea name="teacher_strengths" class="form-control" rows="2">{{ old('teacher_strengths', $teacher['strengths'] ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">सुधारणेची क्षेत्रे (Areas for Improvement)</label>
                        <textarea name="teacher_areas_for_improvement" class="form-control" rows="2">{{ old('teacher_areas_for_improvement', $teacher['areas_for_improvement'] ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">शिक्षकाचा अभिप्राय (श्री. अमोल किसन हंकारे)</label>
                        <textarea name="teacher_remarks" class="form-control" rows="2">{{ old('teacher_remarks', $teacher['teacher_remarks'] ?? '') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">मेंतॉर निरीक्षण व मार्गदर्शन (मा. श्री. सोमनाथ वाळके सर)</label>
                        <textarea name="mentor_observation" class="form-control" rows="2">{{ old('mentor_observation', $mentor['observation'] ?? '') }}</textarea>
                    </div>
                </div>
            </fieldset>

            <!-- Tab 4: Parent Feedback (पालक अभिप्राय) -->
            <fieldset class="border rounded-4 p-4 mb-4">
                <legend class="float-none w-auto px-3 text-info fw-bold h5">
                    <span class="fs-4 me-1">👨‍👩‍👧</span>स्तंभ ४: पालक सहभाग व अभिप्राय
                </legend>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">पालकांचे नाव</label>
                        <input type="text" name="parent_name" class="form-control" value="{{ old('parent_name', $parent['parent_name'] ?? $student->guardian_name ?? 'पालक') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">घरी नियमित वाचन व गृहअध्ययन करतो का?</label>
                        <select name="parent_home_reading_habit" class="form-select">
                            <option value="regular" @selected(($parent['home_reading_habit'] ?? 'regular') === 'regular')>होय, नियमित करतो</option>
                            <option value="occasional" @selected(($parent['home_reading_habit'] ?? '') === 'occasional')>काही वेळा</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">पालकांचे निरीक्षण व अभिप्राय</label>
                        <textarea name="parent_remarks" class="form-control" rows="2">{{ old('parent_remarks', $parent['parent_remarks'] ?? '') }}</textarea>
                    </div>
                </div>
            </fieldset>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('hpc.show', ['student' => $student, 'term' => $term]) }}" class="btn btn-outline-secondary">रद्द करा</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold">
                    <i class="bi bi-save me-1"></i>मूल्यांकन जतन करा (Save 360° HPC)
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
