<div class="tab-pane fade" id="financial" role="tabpanel" aria-labelledby="financial-tab">
    <div class="container">
        <div id="input-fields" class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
            <form action="{{ route('projects.storeFourthTab') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #296D98; color: white; padding: 10px;">
                    <div class="flex-grow-1 text-center">
                        <h4 class="mb-0"><strong>Financial Accomplishments</strong></h4>
                    </div>
                    <div class="text-end">
                        <button type="reset" class="btn btn-secondary btn-sm" title="Clear Inputs">
                            <i class="fa fa-eraser" aria-hidden="true"></i> Clear
                        </button>
                    </div>
                </div>

                <div class="card-body p-2">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label for="project_id" class="form-label" style="font-size: small;"><strong>Project ID:</strong></label>
                            <input type="text" class="form-control border" id="project_id" name="project_id" required style="font-size: small;" pattern="\w{2}-\w{2}-\w{2}-\w{2}" title="Format: XX-XX-XX-XX" readonly value="{{ old('project_id', $project_id ?? '') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a project ID in the format XX-XX-XX-XX.
                            </div>
                        </div>
                        <script>
                            const input = document.getElementById('project_id');

                            input.addEventListener('input', function() {
                                // Remove all non-digit characters
                                let value = this.value.replace(/\D/g, '');

                                // Format the value as 00-00-00-00
                                if (value.length > 8) {
                                    value = value.slice(0, 8); // Limit to 8 digits
                                }
                                const formattedValue = value.replace(/(\d{2})(?=\d)/g, '$1-').slice(0, 14); // Add hyphens

                                this.value = formattedValue;
                            });
                        </script>
                        <div class="col-md-9">
                            <label for="project_name" class="form-label" style="font-size: small;"><strong>Project Name:</strong></label>
                            <input type="text" class="form-control border" id="project_name" name="project_name" required style="font-size: small;" readonly value="{{ old('project_name', $project_name ?? '') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a project name.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="orig_budget" class="form-label" style="font-size: small;"><strong>Amount in Original Currency: <span style="color: red;">*</span></strong></label>
                            <input type="number"
                                class="form-control border @error('orig_budget') is-invalid @enderror"
                                id="orig_budget"
                                name="orig_budget"
                                required
                                step="0.01"
                                style="font-size: small;"
                                value="{{ old('orig_budget') }}">
                            @error('orig_budget')
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: '{{ $message }}'
                                })
                            </script>
                            @enderror
                        </div>
                        <script>
                            const origBudgetInput = document.getElementById('orig_budget');
                            const origBudgetFeedback = document.createElement('div');

                            origBudgetFeedback.className = 'orig-budget-feedback';

                            origBudgetInput.parentNode.insertBefore(origBudgetFeedback, origBudgetInput.nextSibling);

                            origBudgetInput.addEventListener('input', function() {
                                const origBudget = origBudgetInput.value;

                                if (origBudget.length > 0) {
                                    if (!isNaN(origBudget) && origBudget > 0) {
                                        origBudgetFeedback.textContent = Number(origBudget).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                        origBudgetFeedback.style.color = 'green';
                                    } else {
                                        origBudgetFeedback.textContent = 'Invalid amount';
                                        origBudgetFeedback.style.color = 'red';
                                    }
                                } else {
                                    origBudgetFeedback.textContent = '';
                                }
                            });
                        </script>
                        <div class="col-md-3">
                            <label for="currency" class="form-label" style="font-size: small;"><strong>Currency:</strong></label>
                            <select class="form-select border @error('currency') is-invalid @enderror" id="currency" name="currency" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Select Currency --</option>
                                @foreach (DB::table('ref_currency')->select('currency_desc')->get() as $currency)
                                <option value="{{ $currency->currency_desc }}" {{ old('currency') == $currency->currency_desc ? 'selected' : '' }}>{{ $currency->currency_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select a currency.
                            </div>
                            @error('currency')
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: '{{ $message }}'
                                })
                            </script>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="rate" class="form-label" style="font-size: small;"><strong>Exchange Rate: <span style="color: red;">*</span></strong></label>
                            <input type="number" class="form-control border @error('rate') is-invalid @enderror" id="rate" name="rate" required style="font-size: small;" value="{{ old('rate') }}">
                            @error('rate')
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: '{{ $message }}'
                                })
                            </script>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="budget" class="form-label" style="font-size: small;"><strong>Total Project Cost (in Php): <span style="color: red">*</span></strong></label>
                            <input type="number"
                                class="form-control border @error('budget') is-invalid @enderror"
                                id="budget"
                                name="budget"
                                required
                                readonly
                                step="0.01"
                                style="font-size: small;"
                                value="{{ old('budget') }}">
                            @error('budget')
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Invalid Budget',
                                    text: '{{ $message }}'
                                })
                            </script>
                            @enderror
                        </div>
                        <script>
                            // Use different variable name to avoid redeclaration
                            const origBudgetCalcInput = document.getElementById('orig_budget');
                            const rateInput = document.getElementById('rate');
                            const budgetInput = document.getElementById('budget');
                            const budgetFeedback = document.createElement('div');

                            budgetFeedback.className = 'budget-feedback';
                            budgetInput.parentNode.insertBefore(budgetFeedback, budgetInput.nextSibling);

                            function calculateBudget() {
                                const origBudget = parseFloat(origBudgetCalcInput.value) || 0;
                                const rate = parseFloat(rateInput.value) || 1;
                                const total = origBudget * rate;
                                budgetInput.value = total.toFixed(2);

                                if (total > 0) {
                                    budgetFeedback.textContent = 'Total Project Cost: ₱' + Number(total).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                    budgetFeedback.style.color = 'green';
                                } else {
                                    budgetFeedback.textContent = '';
                                    budgetFeedback.style.color = 'white';
                                }
                            }

                            // Calculate budget when either orig_budget or rate changes
                            origBudgetCalcInput.addEventListener('input', calculateBudget);
                            rateInput.addEventListener('input', calculateBudget);

                            // Initial calculation
                            calculateBudget();
                        </script>
                        @foreach([
                        'lp' => 'Loan Proceeds (in Php)',
                        'gp' => 'Grant Proceeds (in Php)',
                        'gph_counterpart' => 'GPH Counterpart (in Php)',
                        ] as $field => $label)
                        <div class="col-md-3">
                            <label for="{{ $field }}" class="form-label" style="font-size: small;"><strong>{{ $label }}:</strong></label>
                            <input type="number"
                                class="form-control border @error($field) is-invalid @enderror"
                                id="{{ $field }}"
                                name="{{ $field }}"
                                min="0"
                                step="0.01"
                                style="font-size: small;"
                                value="{{ old($field) }}">
                            @error($field)
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: '{{ $message }}'
                                })
                            </script>
                            @enderror
                        </div>
                        <script>
                            const {{ $field }}Input = document.getElementById('{{ $field }}');
                            const {{ $field }}Feedback = document.createElement('div');

                            {{ $field }}Feedback.className = '{{ $field }}-feedback';

                            {{ $field }}Input.parentNode.insertBefore({{ $field }}Feedback, {{ $field }}Input.nextSibling);

                            {{ $field }}Input.addEventListener('input', function() {
                                const {{ $field }} = {{ $field }}Input.value;

                                if ({{ $field }}.length > 0) {
                                    if (!isNaN({{ $field }}) && {{ $field }} > 0) {
                                        {{ $field }}Feedback.textContent = '₱' + Number({{ $field }}).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                        {{ $field }}Feedback.style.color = 'green';
                                    } else {
                                        {{ $field }}Feedback.textContent = 'Invalid amount';
                                        {{ $field }}Feedback.style.color = 'red';
                                    }
                                } else {
                                    {{ $field }}Feedback.textContent = '';
                                }
                            });
                        </script>
                        @endforeach

                        <div class="col-md-3">
                            <label for="disbursement" class="form-label" style="font-size: small;"><strong>Disbursement (in Php): <span style="color: red">*</span></strong></label>
                            <input type="number"
                                class="form-control border @error('disbursement') is-invalid @enderror"
                                id="disbursement"
                                name="disbursement"
                                required
                                min="0"
                                step="0.01"
                                style="font-size: small;"
                                value="{{ old('disbursement') }}">
                            @error('disbursement')
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: '{{ $message }}'
                                })
                            </script>
                            @enderror
                        </div>
                        <script>
                            const disbursementInput = document.getElementById('disbursement');
                            const disbursementFeedback = document.createElement('div');

                            disbursementFeedback.className = 'disbursement-feedback';

                            disbursementInput.parentNode.insertBefore(disbursementFeedback, disbursementInput.nextSibling);

                            disbursementInput.addEventListener('input', function() {
                                const disbursement = disbursementInput.value;

                                if (disbursement.length > 0) {
                                    if (!isNaN(disbursement) && disbursement > 0) {
                                        disbursementFeedback.textContent = '₱' + Number(disbursement).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                        disbursementFeedback.style.color = 'green';
                                    } else {
                                        disbursementFeedback.textContent = 'Invalid amount';
                                        disbursementFeedback.style.color = 'red';
                                    }
                                } else {
                                    disbursementFeedback.textContent = '';
                                }
                            });
                        </script>

                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-success btn-sm" name="add" title="Add New Financial Accomplishment">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Financial Accomplishment
                            </button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="col-md-12 text-center mt-3 mb-2">
                    <h4 style="font-size: 1.2rem; font-weight: bold;">Financial Accomplishments</h4>
                </div>
                <table class="table table-striped table-bordered" id="financial-table" style="width: 100%;">
                    <thead style="border-top: 1px solid #ccc;">
                        <tr class="text-center">
                            <th class="text-center">Action</th>
                            <!-- <th class="text-center">ID</th> -->
                            <th class="text-center">Project ID</th>
                            <th class="text-center">Project Name</th>
                            <th class="text-center">Amount in Original Currency</th>
                            <th class="text-center">Currency</th>
                            <th class="text-center">Exchange Rate</th>
                            <th class="text-center">Total Project Cost (in Php)</th>
                            <th class="text-center">Loan Proceeds (in Php)</th>
                            <th class="text-center">Grant Proceeds (in Php)</th>
                            <th class="text-center">GPH Counterpart (in Php)</th>
                            <th class="text-center">Disbursement (in Php)</th>
                            <th class="text-center">% Financial Accomplishment</th>
                            <th class="text-center">Date Encoded</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($financials->sortByDesc('created_at') as $financial)
                        <tr class="financial-row"
                            data-project-id="{{ $financial->project_id }}"
                            data-project-name="{{ $financial->project_name }}"
                            data-orig-budget="{{ $financial->orig_budget }}"
                            data-currency="{{ $financial->currency }}"
                            data-rate="{{ $financial->rate }}"
                            data-budget="{{ $financial->budget }}"
                            data-orig-budget="{{ $financial->orig_budget }}"
                            data-lp="{{ $financial->lp }}"
                            data-gp="{{ $financial->gp }}"
                            data-gph-counterpart="{{ $financial->gph_counterpart }}"
                            data-disbursement="{{ $financial->disbursement }}"
                            data-p-disbursement="{{ $financial->p_disbursement }}">
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2" style="align-items: center;">
                                    @if(Auth::user()->userlevel == -1 || Auth::user()->userlevel == 2 || Auth::user()->userlevel == 5)
                                    <a href="{{ route('projects.editFourthTab', $financial->id) }}" title="Edit Financial" role="button" aria-label="Edit Financial" style="margin-top: 3px;">
                                        <img src="{{ asset('images/edit.png') }}" width="15" height="15" alt="Edit">
                                    </a>
                                    @endif
                                    @if(Auth::user()->userlevel == -1)
                                    <form action="{{ route('projects.destroyFinancial', $financial->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-button" title="Delete Financial" style="background: none; border: none; padding-top: 7px; margin-top: 3px;">
                                            <img src="{{ asset('images/delete.png') }}" width="15" height="18" alt="Delete">
                                        </button>
                                    </form>
                                </div>
                                <script>
                                    document.querySelectorAll('.delete-button').forEach(button => {
                                        button.addEventListener('click', function(event) {
                                            event.preventDefault();
                                            const form = this.closest('.delete-form');
                                            Swal.fire({
                                                title: '<span style="font-size: medium;">Are you sure you want to delete this Financial Accomplishment permanently?</span>',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                cancelButtonText: '<span style="font-size: smaller;">Cancel</span>',
                                                confirmButtonText: '<span style="font-size: smaller;">Yes, delete it!</span>'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    form.submit();
                                                }
                                            });
                                        });
                                    });
                                </script>
                                @endif
                            </td>
                            <!-- <td class="text-center">{{ $financial->id }}</td> -->
                            <td class="text-center">{{ $financial->project_id }}</td>
                            <td class="text-center">{{ $financial->project_name }}</td>
                            <td class="text-center">{{ number_format($financial->orig_budget, 2, '.', ',') }}</td>
                            <td class="text-center">{{ $financial->currency }}</td>
                            <td class="text-center">{{ number_format($financial->rate, 2, '.', ',') }}</td>
                            <td class="text-center">{{ number_format($financial->budget, 2, '.', ',') }}</td>
                            <td class="text-center">{{ number_format($financial->lp, 2, '.', ',') }}</td>
                            <td class="text-center">{{ number_format($financial->gp, 2, '.', ',') }}</td>
                            <td class="text-center">{{ number_format($financial->gph_counterpart, 2, '.', ',') }}</td>
                            <td class="text-center">{{ number_format($financial->disbursement, 2, '.', ',') }}</td>
                            <td class="text-center">{{ $financial->p_disbursement }}%</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($financial->created_at)->format('m-d-Y h:i A') }}</td>
                        </tr>
                        @empty
                        <!-- <tr>
                            <td colspan="11" class="text-center">No data available</td>
                        </tr> -->
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
