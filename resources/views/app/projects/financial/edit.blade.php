<x-app-layout>
    <a href="{{ route('projects.index') }}" style="float: right; margin-right: 50px; margin-top: 10px; text-decoration: none; color: black; display: flex; align-items: center;">
        <img src="{{ asset('images/direction.png') }}" alt="Back" style="width: 20px; height: 20px; margin-right: 5px;">Back
    </a>
    <div class="container mt-5">
        <div class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
            <div class="card-header" style="background-color: #296D98; color: white; padding: 10px;">
                <h4 class="mb-0 text-center"><strong>Edit Financial Accomplishment</strong></h4>
            </div>
            <div class="card-body p-2">
                <form action="{{ route('projects.updateFourthTab', $financial->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label for="project_id" class="form-label" style="font-size: small;"><strong>Project ID:</strong></label>
                            <input type="text" class="form-control border" id="project_id" name="project_id" value="{{ $financial->project_id }}" readonly style="font-size: small;">
                        </div>
                        <div class="col-md-9">
                            <label for="project_name" class="form-label" style="font-size: small;"><strong>Project Name:</strong></label>
                            <input type="text" class="form-control border" id="project_name" name="project_name" value="{{ $financial->project_name }}" readonly style="font-size: small;">
                        </div>
                        <div class="col-md-3">
                            <label for="orig_budget" class="form-label" style="font-size: small;"><strong>Amount in Original Currency: <span style="color: red;">*</span></strong></label>
                            <input type="number"
                                class="form-control border @error('orig_budget') is-invalid @enderror"
                                id="orig_budget"
                                name="orig_budget"
                                value="{{ old('orig_budget', $financial->orig_budget) }}"
                                required
                                step="0.01"
                                style="font-size: small;">
                            @error('orig_budget')
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: '{{ $message }}'
                                })
                            </script>
                            @enderror
                            <div class="orig-budget-feedback"></div>
                            <script>
                                const origBudgetInput = document.getElementById('orig_budget');
                                const origBudgetFeedback = document.querySelector('.orig-budget-feedback');

                                origBudgetInput.addEventListener('input', function() {
                                    const origBudget = origBudgetInput.value;

                                    if (origBudget.length > 0) {
                                        if (!isNaN(origBudget) && origBudget > 0) {
                                            origBudgetFeedback.textContent =  Number(origBudget).toLocaleString('en-US', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
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
                        </div>
                        <div class="col-md-3">
                            <label for="currency" class="form-label" style="font-size: small;"><strong>Currency:</strong></label>
                            <select class="form-select border @error('currency') is-invalid @enderror" id="currency" name="currency" style="font-size: small;">
                                <option value="">-- Select Currency --</option>
                                @foreach (DB::table('ref_currency')->select('currency_desc')->orderBy('currency_desc')->get() as $currency)
                                <option value="{{ $currency->currency_desc }}" {{ old('currency', $financial->currency) == $currency->currency_desc ? 'selected' : '' }}>
                                    {{ $currency->currency_desc }}
                                </option>
                                @endforeach
                            </select>

                            @error('currency')
                            <div class="text-danger mt-1" style="font-size: small;">
                                {{ $message }}
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: '{{ $message }}'
                                    });
                                });
                            </script>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="rate" class="form-label" style="font-size: small;"><strong>Exchange Rate: <span style="color: red;">*</span></strong></label>
                            <input type="number"
                                class="form-control border @error('rate') is-invalid @enderror"
                                id="rate"
                                name="rate"
                                value="{{ old('rate', $financial->rate) }}"
                                required
                                step="0.01"
                                style="font-size: small;">
                            @error('rate')
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: '{{ $message }}'
                                })
                            </script>
                            @enderror
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a valid exchange rate.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="budget" class="form-label" style="font-size: small;"><strong>
                                    Total Project Cost (in Php): <span style="color: red">*</span></strong></label>
                            <input type="number"
                                class="form-control border @error('budget') is-invalid @enderror"
                                id="budget"
                                name="budget"
                                value="{{ old('budget', $financial->budget) }}"
                                required
                                readonly
                                step="0.01"
                                style="font-size: small;">
                            @error('budget')
                            <script>
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Invalid Budget',
                                    text: '{{ $message }}'
                                })
                            </script>
                            @enderror
                            <div class="budget-feedback"></div>
                        </div>
                        <script>
                            // Get references to inputs
                            const budgetInput = document.getElementById('budget');
                            const budgetFeedback = document.querySelector('.budget-feedback');

                            function calculateBudget() {
                                const origBudget = parseFloat(document.getElementById('orig_budget').value) || 0;
                                const rate = parseFloat(document.getElementById('rate').value) || 1;
                                const total = origBudget * rate;
                                budgetInput.value = total.toFixed(2);

                                // Update feedback
                                if (total > 0) {
                                    budgetFeedback.textContent = 'Total Project Cost: ₱' + total.toLocaleString('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                    budgetFeedback.style.color = 'green';
                                } else {
                                    budgetFeedback.textContent = '';
                                }
                            }

                            // Calculate budget when either orig_budget or rate changes
                            document.getElementById('orig_budget').addEventListener('input', calculateBudget);
                            document.getElementById('rate').addEventListener('input', calculateBudget);

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
                                value="{{ old($field, $financial->$field) }}"
                                min="0"
                                step="0.01"
                                style="font-size: small;">
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
                            const {
                                {
                                    $field
                                }
                            }
                            Input = document.getElementById('{{ $field }}');
                            const {
                                {
                                    $field
                                }
                            }
                            Feedback = document.createElement('div');

                            {
                                {
                                    $field
                                }
                            }
                            Feedback.className = '{{ $field }}-feedback';

                            {
                                {
                                    $field
                                }
                            }
                            Input.parentNode.insertBefore({
                                    {
                                        $field
                                    }
                                }
                                Feedback, {
                                    {
                                        $field
                                    }
                                }
                                Input.nextSibling);

                            {
                                {
                                    $field
                                }
                            }
                            Input.addEventListener('input', function() {
                                const {
                                    {
                                        $field
                                    }
                                } = {
                                    {
                                        $field
                                    }
                                }
                                Input.value;

                                if ({
                                        {
                                            $field
                                        }
                                    }.length > 0) {
                                    if (!isNaN({
                                            {
                                                $field
                                            }
                                        }) && {
                                            {
                                                $field
                                            }
                                        } > 0) {
                                        {
                                            {
                                                $field
                                            }
                                        }
                                        Feedback.textContent = '₱' + Number({
                                            {
                                                $field
                                            }
                                        }).toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                        {
                                            {
                                                $field
                                            }
                                        }
                                        Feedback.style.color = 'green';
                                    } else {
                                        {
                                            {
                                                $field
                                            }
                                        }
                                        Feedback.textContent = 'Invalid amount';
                                        {
                                            {
                                                $field
                                            }
                                        }
                                        Feedback.style.color = 'red';
                                    }
                                } else {
                                    {
                                        {
                                            $field
                                        }
                                    }
                                    Feedback.textContent = '';
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
                                value="{{ old('disbursement', $financial->disbursement) }}">
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
                                        disbursementFeedback.textContent = '₱' + Number(disbursement).toLocaleString('en-US', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
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
                    </div>
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary">Update Financial Accomplishment</button>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>