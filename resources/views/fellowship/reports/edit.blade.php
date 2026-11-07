<x-layouts.app title="{{ $report->month_name_marathi }} - अहवाल संपादन">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('fellowship.reports.index') }}">फेलोशिप अहवाल केंद्र</a></li>
                <li class="breadcrumb-item"><a href="{{ route('fellowship.reports.show', $report) }}">{{ $report->month_name_marathi }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">संपादन</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold portal-brand mb-0">{{ $report->month_name_marathi }} - अहवाल माहिती संपादन</h1>
        <p class="text-secondary small mb-0">अहवालातील आठवडेनिहाय तपशील, प्रमुख फलनिष्पत्ती व मेंटॉर शेरा अद्ययावत करा.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('fellowship.reports.update', $report) }}">
        @csrf
        @method('PUT')

        <div class="card portal-card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h2 class="h5 mb-0 fw-bold text-primary">१. अहवाल स्थिती व मूलभूत माहिती</h2>
            </div>
            <div class="card-body px-4">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="status">अहवाल स्थिती (Status)</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="draft" @selected(old('status', $report->status) === 'draft')>मसुदा (Draft)</option>
                            <option value="submitted" @selected(old('status', $report->status) === 'submitted')>सादर केले (Submitted)</option>
                            <option value="approved" @selected(old('status', $report->status) === 'approved')>तपासले व मंजूर (Approved)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="report_date">अहवाल दिनांक</label>
                        <input class="form-control" id="report_date" name="report_date" type="date" value="{{ old('report_date', $report->report_date?->format('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="place">ठिकाण</label>
                        <input class="form-control" id="place" name="place" type="text" value="{{ old('place', $report->place) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="card portal-card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h2 class="h5 mb-0 fw-bold text-primary">२. आठवडेनिहाय प्रत्यक्ष कार्य तपशील</h2>
            </div>
            <div class="card-body px-4">
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="week1_2_title">१ ला व २ रा आठवडा - शीर्षक</label>
                    <input class="form-control" id="week1_2_title" name="week1_2_title" type="text" value="{{ old('week1_2_title', $report->week1_2_title) }}" required>
                    <label class="form-label fw-semibold mt-2" for="week1_2_description">१ ला व २ रा आठवडा - सविस्तर कार्य तपशील</label>
                    <textarea class="form-control" id="week1_2_description" name="week1_2_description" rows="5" required>{{ old('week1_2_description', $report->week1_2_description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="week3_4_title">३ रा व ४ था आठवडा - शीर्षक</label>
                    <input class="form-control" id="week3_4_title" name="week3_4_title" type="text" value="{{ old('week3_4_title', $report->week3_4_title) }}" required>
                    <label class="form-label fw-semibold mt-2" for="week3_4_description">३ रा व ४ था आठवडा - सविस्तर कार्य तपशील</label>
                    <textarea class="form-control" id="week3_4_description" name="week3_4_description" rows="5" required>{{ old('week3_4_description', $report->week3_4_description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card portal-card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h2 class="h5 mb-0 fw-bold text-primary">३. निरीक्षणे, आव्हाने व पुढील नियोजन</h2>
            </div>
            <div class="card-body px-4">
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="key_outcomes">प्रमुख निरीक्षणे व फलनिष्पत्ती (Key Outcomes)</label>
                    <textarea class="form-control" id="key_outcomes" name="key_outcomes" rows="4">{{ old('key_outcomes', $report->key_outcomes) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="challenges_and_solutions">आव्हाने व केलेली उपाययोजना</label>
                    <textarea class="form-control" id="challenges_and_solutions" name="challenges_and_solutions" rows="3">{{ old('challenges_and_solutions', $report->challenges_and_solutions) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold" for="next_month_plan">पुढील महिन्याचे नियोजन</label>
                    <textarea class="form-control" id="next_month_plan" name="next_month_plan" rows="3">{{ old('next_month_plan', $report->next_month_plan) }}</textarea>
                </div>
            </div>
        </div>

        <div class="card portal-card mb-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h2 class="h5 mb-0 fw-bold text-primary">४. मेंटॉरचा शेरा व अभिप्राय (Mentor Review)</h2>
            </div>
            <div class="card-body px-4">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label fw-semibold" for="mentor_remarks">मेंटॉर शेरा / अभिप्राय</label>
                        <textarea class="form-control" id="mentor_remarks" name="mentor_remarks" rows="3">{{ old('mentor_remarks', $report->mentor_remarks) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold" for="mentor_status">मेंटॉर पडताळणी स्थिती</label>
                        <select class="form-select" id="mentor_status" name="mentor_status">
                            <option value="pending" @selected(old('mentor_status', $report->mentor_status) === 'pending')>प्रलंबित (Pending)</option>
                            <option value="approved" @selected(old('mentor_status', $report->mentor_status) === 'approved')>मंजूर व प्रमाणित (Approved)</option>
                            <option value="revisions_requested" @selected(old('mentor_status', $report->mentor_status) === 'revisions_requested')>दुरुस्ती आवश्यक (Revisions)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-5">
            <a class="btn btn-outline-secondary" href="{{ route('fellowship.reports.show', $report) }}">
                रद्द करा
            </a>
            <button class="btn btn-primary btn-lg px-4" type="submit">
                <i class="bi bi-save me-1"></i> बदल जतन करा (Save Changes)
            </button>
        </div>
    </form>
</x-layouts.app>
