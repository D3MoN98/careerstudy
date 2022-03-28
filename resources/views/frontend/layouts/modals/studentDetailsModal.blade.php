<style>
    .modal-dialog {
        margin: 1.75em auto;
        min-height: calc(100vh - 60px);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .g-recaptcha div {
        margin: auto;
    }

    .modal-body .contact_form input[type='radio'] {
        width: auto;
        height: auto;
    }

    .modal-body .contact_form textarea {
        background-color: #eeeeee;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 10px;
        width: 100%;
        border: none
    }

    .select2.select2-container {
        width: 100% !important;
    }


    @media (max-width: 768px) {
        .modal-dialog {
            min-height: calc(100vh - 20px);
        }

        #studentDetailsModal .modal-body {
            padding: 15px;
        }
    }

</style>

@if (auth()->check())
    <div class="modal fade" id="studentDetailsModal" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">


                <!-- Modal Header -->
                <div class="modal-header backgroud-style">

                    <div class="gradient-bg"></div>
                    <div class="popup-logo">
                        <img src="{{ asset('storage/logos/' . config('logo_popup')) }}" alt="">
                    </div>
                    <div class="popup-text text-center">
                        <h2>Student Details</h2>
                    </div>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <p class="success-response text-success font-weight-bold my-2">{{(session()->get('flash_success'))}}</span>

                    <form id="studentDetailsForm" class="contact_form"
                        action="{{ route('frontend.auth.student-details') }}" method="post">
                        {!! csrf_field() !!}
                        <div class="form-group contact-info mb-2">
                            @php
                                $colleges = App\Models\College::all();
                            @endphp
                            <select name="college_id" id="college_id" class="form-control mb-0 select2-tag-college" required>
                                <option></option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}">{{ $college->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group contact-info mb-2">
                            @php
                                $college_streams = App\Models\CollegeStream::all();
                            @endphp
                            <select name="college_stream_id" id="college_stream_id" class="form-control select2-tag-college-stream" required>
                                <option></option>
                                @foreach ($college_streams as $college_stream)
                                    <option value="{{ $college_stream->id }}">{{ $college_stream->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group contact-info mb-2">
                            <select name="semester" id="semester" class="form-control select2" required>
                                <option></option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <p class="text-info font-weight-bold">Please fillout this form before go to dashboard</p>


                        <div class="contact-info mb-2 mx-auto w-50 py-4">
                            <div class="nws-button text-center white text-capitalize">
                                <button id="registerButton" type="submit" value="Submit">Submit</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@push('after-scripts')
    <script>
        $('#studentDetailsModal').modal({
            backdrop: "static",
            keyboard: false
        });
    </script>

    {{-- select2 script --}}
    <script>
        $(document).ready(function() {
            $(".select2").select2({
                dropdownParent: $("#studentDetailsModal  .modal-content"),
                placeholder: "Select a semester"
            });

            $(".select2-tag-college").select2({
                dropdownParent: $("#studentDetailsModal  .modal-content"),
                tags: true,
                placeholder: "Select a college"
            });

            $(".select2-tag-college-stream").select2({
                dropdownParent: $("#studentDetailsModal  .modal-content"),
                tags: true,
                placeholder: "Select a stream"
            });
        });

        $('#studentDetailsForm').validate({
            errorElement: 'span',
            errorClass: 'invalid-feedback',
            highlight: function(element, errorClass, validClass) {
                $(element).addClass("is-invalid").removeClass("invalid-feedback");
            },
            errorPlacement: function(error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    error.insertAfter($(element).parents('.form-group').find('.select2-container'));
                } else if (element.attr("type") == "radio") {
                    error.insertAfter($(element).parents('.form-group').find('label').last());
                    error.addClass('d-block');
                } else {
                    error.insertAfter($(element));
                }
            }
        })
    </script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        $('#studentDetailsForm').on('submit', function(e) {
            e.preventDefault();

            var $this = $(this);
            $('.success-response').empty();

            $.ajax({
                type: $this.attr('method'),
                url: $this.attr('action'),
                data: $this.serializeArray(),
                dataType: $this.data('type'),
                success: function(response) {
                    if (response.success) {
                        $('.success-response').html("Details successfully inserted");

                        $("#college_id").empty().trigger('change')
                        $("#college_stream_id").empty().trigger('change')
                        $("#semester").empty().trigger('change')

                        setTimeout(() => {
                            window.location.reload();
                        }, 3500);
                    }
                },
                error: function(jqXHR) {

                }
            });
        });
    </script>
@endpush
