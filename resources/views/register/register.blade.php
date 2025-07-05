<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ __('User Registration') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('images/fav.png') }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ asset('images/fav.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="{{ asset('css/user-registration.css') }}" rel="stylesheet">

</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">

    <div class="container px-3">
        <div class="auth-card text-center mx-auto" style="max-width: 640px;">
            <img src="{{ asset('images/logo_rayan.png') }}" alt="Logo" class="mb-4" style="max-height: 80px;">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @elseif ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form method="POST" action="{{ route('register.store') }}" class="text-start">
                @csrf
                @foreach ([
        'name' => 'Name',
        'username' => 'User Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'password' => 'Password',
    ] as $field => $label)
                    <div class="mb-3">
                        <label for="{{ $field }}" class="form-label">
                            {{ __($label) }} <span class="text-danger">*</span>
                        </label>
                        @if ($field === 'password')
                            <input type="password" autocomplete id="{{ $field }}" name="{{ $field }}"
                                class="form-control" required>
                        @else
                            <input type="text" id="{{ $field }}" name="{{ $field }}"
                                value="{{ old($field) }}" class="form-control" required>
                        @endif
                    </div>
                @endforeach


                {{-- Role --}}


                <div class="mb-3">
                    <label for="role" class="form-label">
                        {{ __('Role') }} <span class="text-danger">*</span>
                    </label>
                    <select name="role" id="role" class="form-select" required>
                        @foreach ($allowedRoles as $label => $roleId)
                            <option value="{{ $label }}"
                                {{ old('role', strtoupper($user->role ?? '')) == $label ? 'selected' : '' }}>
                                {{ __($roleLabels[$roleId]) }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- Association --}}
                <div class="mb-3">
                    <label for="association_id" class="form-label">{{ __('Association') }} <span
                            class="text-danger">*</span></label>
                    <select name="association_id" id="association_id" class="form-select" required>
                        <option value="">{{ __('Please Select') }}</option>
                        @foreach ($associations as $association)
                            <option value="{{ $association->id }}"
                                {{ old('association_id') == $association->id ? 'selected' : '' }}>
                                {{ __($association->name) }}</option>
                        @endforeach
                    </select>
                </div>




                {{-- Range --}}
                <div class="mb-3">
                    <label for="range_id" class="form-label">{{ __('Range') }} <span
                            class="text-danger">*</span></label>
                    <select name="range_id" id="range_id" class="form-select" required>
                        <option value="">{{ __('Please Select') }}</option>

                        @foreach ($ranges as $range)
                            <option value="{{ $range->id }}" {{ old('range_id') == $range->id ? 'selected' : '' }}>
                                {{ __($range->name) }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Points --}}
                <div class="mb-3">
                    <label for="point_ids" class="form-label">
                        {{ __('Points') }} <span class="text-danger">*</span>
                    </label>
                    <div class="position-relative d-flex">
                        <select name="point_ids[]" id="point_ids" class="form-select rounded-end-0" multiple>
                            <option value="">{{ __('Please Select') }}</option>
                            @foreach ($points as $point)
                                <option value="{{ $point->id }}"
                                    {{ collect(old('point_ids'))->contains($point->id) ? 'selected' : '' }}>
                                    {{ __($point->name) }}</option>
                            @endforeach
                        </select>

                    </div>
                </div>



                {{-- <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                <label class="form-check-label" for="terms">{{ __('I agree to the terms and conditions') }}</label>
            </div> --}}

                <button type="submit" class="btn submit-btn w-100">{{ __('Register') }}</button>
                {{--
                <p class="mt-4 text-muted text-center">
                    {{ __('Already have an account?') }} <a href="{{ url('dashboard') }}"
                        class="text-primary text-decoration-none">{{ __('Login') }}</a>
                </p> --}}
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="{{ asset('js/doctor-registration.js') }}"></script> --}}
    <script>
        $(function() {
            $('#point_ids').select2({
                width: '100%',
                dir: 'rtl',
                placeholder: '{{ __('Please Select') }}'
            });

        });


        $(function() {
            function toggleRangePoints() {
                const role = $('#role').val()?.toUpperCase(); // Normalize to uppercase

                if (role === 'SUPERVISOR') {
                    $('#range_id').closest('.mb-3').hide();
                    $('#point_ids').closest('.mb-3').hide();

                    $('#range_id').prop('required', false).prop('disabled', true);
                    $('#point_ids').prop('required', false).prop('disabled', true);
                } else {
                    $('#range_id').closest('.mb-3').show();
                    $('#point_ids').closest('.mb-3').show();

                    $('#range_id').prop('required', true).prop('disabled', false);
                    $('#point_ids').prop('required', true).prop('disabled', false);
                }
            }

            // Run on page load
            toggleRangePoints();

            // Run on change
            $('#role').on('change', toggleRangePoints);
        });
        $(function() {
            // Initialize Select2
            $('#point_ids').select2({
                width: '100%',
                dir: 'rtl',
                placeholder: '{{ __('Please Select') }}'
            });

            function loadPoints() {
                const rangeId = $('#range_id').val();
                const associationId = $('#association_id').val();

                if (!rangeId || !associationId) {
                    $('#point_ids').empty().trigger('change'); // clear if missing
                    return;
                }

                $.ajax({
                    url: "{{ route('points.filter') }}",
                    data: {
                        range_id: rangeId,
                        association_id: associationId
                    },
                    success: function(data) {
                        // Clear existing options
                        $('#point_ids').empty();

                        // Add a placeholder empty option
                        $('#point_ids').append(new Option('{{ __('Please Select') }}', ''));

                        // Populate new options
                        $.each(data, function(id, name) {
                            $('#point_ids').append(new Option(name, id));
                        });

                        // Trigger change so Select2 updates
                        $('#point_ids').trigger('change');
                    },
                    error: function() {
                        // Optionally handle error
                        $('#point_ids').empty().trigger('change');
                    }
                });
            }

            // Call loadPoints when range or association changes
            $('#range_id, #association_id').on('change', loadPoints);

            // Optionally, call once on page load if you want initial points loaded
            loadPoints();
        });
    </script>

</body>

</html>
