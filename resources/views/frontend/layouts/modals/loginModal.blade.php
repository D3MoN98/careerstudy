<style>
    .modal-dialog {
        margin: 1.75em auto;
        min-height: calc(100vh - 60px);
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    #myModal .close {
        position: absolute;
        right: 0.3rem;
    }

    .g-recaptcha div {
        margin: auto;
    }

    .modal-body .contact_form input[type='radio'] {
        width: auto;
        height: auto;
    }
    .modal-body .contact_form textarea{
        background-color: #eeeeee;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 10px;
        width: 100%;
        border: none
    }
    .select2.select2-container{
        width: 100%!important;
    }


    @media (max-width: 768px) {
        .modal-dialog {
            min-height: calc(100vh - 20px);
        }

        #myModal .modal-body {
            padding: 15px;
        }
    }

</style>
<?php
//$fields = json_decode(config('registration_fields'));
//$inputs = ['text','number','date','gender'];
//dd($fields);
?>
@if(!auth()->check())

    <div class="modal fade" id="myModal" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">


                <!-- Modal Header -->
                <div class="modal-header backgroud-style">

                    <div class="gradient-bg"></div>
                    <div class="popup-logo">
                        <img src="{{asset("storage/logos/".config('logo_popup'))}}" alt="">
                    </div>
                    <div class="popup-text text-center">
                        <h2>@lang('labels.frontend.modal.my_account') </h2>
                        <p>@lang('labels.frontend.modal.login_register')</p>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>

                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="tab-content">
                        <div class="tab-pane container active" id="login">

                            <span class="error-response text-danger"></span>
                            <span class="success-response text-success">{{(session()->get('flash_success'))}}</span>
                            <form class="contact_form" id="loginForm" action="{{route('frontend.auth.login.post')}}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf
                                <a href="#" class="go-register float-left text-info pl-0">
                                    @lang('labels.frontend.modal.new_user_note')
                                </a>
                                <div class="form-group contact-info mb-2">

                                    {{ html()->email('email')
                                        ->class('form-control mb-0')
                                        ->placeholder(__('validation.attributes.frontend.email'))
                                        ->attribute('maxlength', 191)
                                        }}
                                    <span id="login-email-error" class="text-danger"></span>

                                </div>
                                <div class="form-group contact-info mb-2">
                                    {{ html()->password('password')
                                                     ->class('form-control mb-0')
                                                     ->placeholder(__('validation.attributes.frontend.password'))
                                                    }}
                                    <span id="login-password-error" class="text-danger"></span>

                                    <a class="text-info p-0 d-block text-right my-2"
                                       href="{{ route('frontend.auth.password.reset') }}">@lang('labels.frontend.passwords.forgot_password')</a>

                                </div>

                                @if(config('access.captcha.registration'))
                                    <div class="contact-info mb-2 text-center">
                                        {{ no_captcha()->display() }}
                                        {{ html()->hidden('captcha_status', 'true') }}
                                        <span id="login-captcha-error" class="text-danger"></span>

                                    </div><!--col-->
                                @endif

                                <div class="nws-button text-center white text-capitalize">
                                    <button type="submit"
                                            value="Submit">@lang('labels.frontend.modal.login_now')</button>
                                </div>

                            </form>

                            <div id="socialLinks" class="text-center">
                            </div>

                        </div>

                        <div class="tab-pane container fade" id="register">

                            <form id="registerForm" class="contact_form"
                                  action="#"
                                  method="post">
                                {!! csrf_field() !!}
                                <a href="#"
                                   class="go-login float-right text-info pr-0">@lang('labels.frontend.modal.already_user_note')</a>
                                <div class="form-group contact-info mb-2">


                                    {{ html()->text('first_name')
                                        ->class('form-control mb-0')
                                        ->placeholder(__('validation.attributes.frontend.first_name'))
                                        ->attribute('maxlength', 191)->required() }}
                                    <span id="first-name-error" class="text-danger"></span>
                                </div>
                                <div class="form-group contact-info mb-2">
                                    {{ html()->text('last_name')
                                      ->class('form-control mb-0')
                                      ->placeholder(__('validation.attributes.frontend.last_name'))
                                      ->attribute('maxlength', 191)->required() }}
                                    <span id="last-name-error" class="text-danger"></span>

                                </div>

                                <div class="form-group contact-info mb-2">
                                    {{ html()->email('email')
                                       ->class('form-control mb-0')
                                       ->placeholder(__('validation.attributes.frontend.email'))
                                       ->attribute('maxlength', 191)->required()
                                       }}
                                    <span id="email-error" class="text-danger invalid-feedback d-block"></span>

                                </div>
                                <div class="form-group contact-info mb-2">
                                    {{ html()->password('password')
                                        ->class('form-control mb-0')
                                        ->placeholder(__('validation.attributes.frontend.password'))
                                         }}
                                    <span id="password-error" class="text-danger"></span>
                                </div>
                                <div class="form-group contact-info mb-2">
                                    {{ html()->password('password_confirmation')
                                        ->class('form-control mb-0')
                                        ->placeholder(__('validation.attributes.frontend.password_confirmation'))
                                         }}
                                </div>
                                @if(config('registration_fields') != NULL)
                                    @php
                                        $fields = json_decode(config('registration_fields'));
                                        $inputs = ['text','number','date'];
                                    @endphp
                                    @foreach($fields as $item)
                                        @if(in_array($item->type,$inputs))
                                            <div class="form-group contact-info mb-2">
                                                <input type="{{$item->type}}" class="form-control mb-0" value="{{old($item->name)}}" name="{{$item->name}}"
                                                       placeholder="{{__('labels.backend.general_settings.user_registration_settings.fields.'.$item->name)}}" required>
                                                @if ($item->name == "phone")
                                                <span id="phone-error-backend" class="text-danger invalid-feedback d-block"></span>

                                                <small class="form-text text-muted">
                                                    Please avoid any pre 0 or +91 phone code, only enter your 10 digit phone number
                                                </small>
                                                @endif
                                            </div>
                                        @elseif($item->type == 'radio')
                                            <div class="form-group contact-info mb-2">
                                                <label class="radio-inline mr-3 mb-0">
                                                    <input type="radio" name="{{$item->name}}" value="male" required> {{__('validation.attributes.frontend.male')}}
                                                </label>
                                                <label class="radio-inline mr-3 mb-0">
                                                    <input type="radio" name="{{$item->name}}" value="female" required> {{__('validation.attributes.frontend.female')}}
                                                </label>
                                                <label class="radio-inline mr-3 mb-0">
                                                    <input type="radio" name="{{$item->name}}" value="other" required> {{__('validation.attributes.frontend.other')}}
                                                </label>
                                            </div>
                                        @elseif($item->type == 'textarea')
                                            <div class="form-group contact-info mb-2">

                                            <textarea name="{{$item->name}}" placeholder="{{__('labels.backend.general_settings.user_registration_settings.fields.'.$item->name)}}" class="form-control mb-0" required>{{old($item->name)}}</textarea>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                                <div class="form-group contact-info mb-2">
                                    @php
                                        $colleges = App\Models\College::all();
                                    @endphp
                                    <select name="college_id" class="form-control mb-0 select2-tag-college" required>
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
                                    <select name="college_stream_id" class="form-control select2-tag-college-stream" required>
                                        <option></option>
                                        @foreach ($college_streams as $college_stream)
                                        <option value="{{ $college_stream->id }}">{{ $college_stream->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group contact-info mb-2">
                                    <select name="semester" id="" class="form-control select2" required>
                                        <option></option>
                                        @for ($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>

                                @if(config('access.captcha.registration'))
                                    <div class="contact-info mt-3 text-center">
                                        {{ no_captcha()->display() }}
                                        {{ html()->hidden('captcha_status', 'true')->id('captcha_status') }}
                                        <span id="captcha-error" class="text-danger"></span>

                                    </div><!--col-->
                                @endif


                                <div class="contact-info mb-2 mx-auto w-50 py-4">
                                    <div class="nws-button text-center white text-capitalize">
                                        <button id="registerButton" type="submit"
                                                value="Submit">@lang('labels.frontend.modal.register_now')</button>
                                    </div>
                                </div>


                                <a href="{{ route('frontend.auth.teacher.register') }}"
                                   class="fgo-register float-left text-info mt-2">
                                    @lang('labels.teacher.teacher_register')
                                </a>
                            </form>
                        </div>

                        <div class="tab-pane container fade" id="otpVerification">

                            <span class="error-response text-danger"></span>
                            <span class="success-response text-success">{{(session()->get('flash_success'))}}</span>
                            <form class="contact_form" id="otpVerificationForm" action="{{route('frontend.auth.otp_verification')}}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="contact_number">
                                <input type="hidden" name="user_id">

                                
                                <div class="form-group contact-info mb-2">
                                    <input type="number" name="otp" class="form-control mb-0" id="" maxlength="4" minlength="4" min="0" max="9999" placeholder="Enter your OTP">
                                </div>

                                <div class="nws-button text-center white text-capitalize">
                                    <button type="submit"
                                            value="Submit">Submit OTP</button>
                                </div>


                                @php $otp_data = session('otp_data') @endphp
                                    
                                <span style="display: {{ time() - $otp_data['created_at'] < 10*60 ? 'block' : 'none' }}" class="text-info p-0 text-right my-2 otp-expire-time">{{ 10*60 - (time() - $otp_data['created_at']) }}</span>
                                <a style="display: {{ time() - $otp_data['created_at'] > 10*60 ? 'block' : 'none' }}" class="text-info p-0 text-right my-2 resend-otp"
                                href="#">Resend Otp</a>
                                    
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@push('after-scripts')


    @if (session('openModel'))
        <script>
            $('#myModal').modal('show');
        </script>
    @endif


    @if(config('access.captcha.registration'))
        {{ no_captcha()->script() }}

    @endif


    {{-- select2 script --}}
    <script>
        $(document).ready(function() {
            $(".select2").select2({
                dropdownParent: $("#myModal  .modal-content"),
                placeholder: "Select a semester"
            });
            
            $(".select2-tag-college").select2({
                dropdownParent: $("#myModal  .modal-content"),
                tags: true,
                placeholder: "Select a college"
            });
            
            $(".select2-tag-college-stream").select2({
                dropdownParent: $("#myModal  .modal-content"),
                tags: true,
                placeholder: "Select a stream"
            });
        });

    </script>


    {{-- ajax submit script --}}

    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $(document).ready(function () {
                $(document).on('click', '.go-login', function () {
                    $('#register').removeClass('active').addClass('fade')
                    $('#login').addClass('active').removeClass('fade')

                });
                $(document).on('click', '.go-register', function () {
                    $('#login').removeClass('active').addClass('fade')
                    $('#register').addClass('active').removeClass('fade')
                });

                $(document).on('click', '#openLoginModal', function (e) {
                    $.ajax({
                        type: "GET",
                        url: "{{route('frontend.auth.login')}}",
                        success: function (response) {
                            $('#socialLinks').html(response.socialLinks)
                            $('#myModal').modal('show');
                        },
                    });
                });

                $('#loginForm').on('submit', function (e) {
                    e.preventDefault();

                    var $this = $(this);
                    $('.success-response').empty();
                    $('.error-response').empty();

                    $.ajax({
                        type: $this.attr('method'),
                        url: $this.attr('action'),
                        data: $this.serializeArray(),
                        dataType: $this.data('type'),
                        success: function (response) {
                            $('#login-email-error').empty();
                            $('#login-password-error').empty();
                            $('#login-captcha-error').empty();

                            if (response.errors) {
                                if (response.errors.email) {
                                    $('#login-email-error').html(response.errors.email[0]);
                                }
                                if (response.errors.password) {
                                    $('#login-password-error').html(response.errors.password[0]);
                                }

                                var captcha = "g-recaptcha-response";
                                if (response.errors[captcha]) {
                                    $('#login-captcha-error').html(response.errors[captcha][0]);
                                }
                            }
                            if (response.success) {
                                $('#loginForm')[0].reset();
                                if (response.redirect == 'back') {
                                    location.reload();
                                } else {
                                    window.location.href = "{{route('admin.dashboard')}}"
                                }
                            }
                        },
                        error: function (jqXHR) {
                            var response = $.parseJSON(jqXHR.responseText);
                            console.log(response)
                            if (!response.success && response.message) {
                                $('#login').find('span.error-response').html(response.message)
                            }else if (!response.success && !response.otp) {
                                $('#loginForm')[0].reset();
                                $('#login').removeClass('active').addClass('fade')
                                $('.error-response').empty();
                                $('#otpVerification').addClass('active').removeClass('fade')
                                $("#otpVerificationForm input[name='contact_number']").val(response.contact_number);
                                $("#otpVerificationForm input[name='user_id']").val(response.user_id);
                                $('.success-response').empty().html("Plase verify your number before login. OTP has been sent to your phone number " + response.contact_number_preview);

                                remaining_interval(response.otp_expire_time);
                            }
                           
                        }
                    });
                });

                $(document).on('submit','#registerForm', function (e) {
                    e.preventDefault();
                    console.log('he')
                    var $this = $(this);

                    $.ajax({
                        type: $this.attr('method'),
                        url: "{{  route('frontend.auth.register.post')}}",
                        data: $this.serializeArray(),
                        dataType: $this.data('type'),
                        success: function (response) {
                            $('#first-name-error').empty()
                            $('#last-name-error').empty()
                            $('#email-error').empty()
                            $('#phone-error').empty()
                            $('#password-error').empty()
                            $('#captcha-error').empty()
                            if (response.errors) {
                                if (response.errors.first_name) {
                                    $('#first-name-error').html(response.errors.first_name[0]);
                                }
                                if (response.errors.last_name) {
                                    $('#last-name-error').html(response.errors.last_name[0]);
                                }
                                if (response.errors.email) {
                                    $('#email-error').html(response.errors.email[0]);
                                }
                                
                                if (response.errors.phone) {
                                    $('#phone-error-backend').html(response.errors.phone[0]);
                                }
                                if (response.errors.password) {
                                    $('#password-error').html(response.errors.password[0]);
                                }

                                var captcha = "g-recaptcha-response";
                                if (response.errors[captcha]) {
                                    $('#captcha-error').html(response.errors[captcha][0]);
                                }
                            }
                            if (response.success) {
                                $('#registerForm')[0].reset();
                                $('#register').removeClass('active').addClass('fade')
                                $('.error-response').empty();
                                $('#otpVerification').addClass('active').removeClass('fade')
                                $("#otpVerificationForm input[name='contact_number']").val(response.contact_number);
                                $("#otpVerificationForm input[name='user_id']").val(response.user_id);
                                $('.success-response').empty().html("OTP has been sent to your phone number " + response.contact_number_preview);

                                remaining_interval(response.otp_expire_time);
                            }
                        }
                    });
                });


                $('#otpVerificationForm').on('submit', function (e) {
                    e.preventDefault();

                    var $this = $(this);
                    $('.success-response').empty();
                    $('.error-response').empty();

                    $.ajax({
                        type: $this.attr('method'),
                        url: $this.attr('action'),
                        data: $this.serializeArray(),
                        dataType: $this.data('type'),
                        success: function (response) {
                            if (response.success) {
                                $('#otpVerificationForm')[0].reset();
                                $('#otpVerification').removeClass('active').addClass('fade')
                                $('.error-response').empty();
                                $('#login').addClass('active').removeClass('fade')
                                $('.success-response').empty().html("Your mobile number has been verified.");
                            }
                        },
                        error: function (jqXHR) {
                            var response = $.parseJSON(jqXHR.responseText);
                            if (!response.success && !response.otp_confirmed) {
                                $('#otpVerificationForm')[0].reset();
                                $('.success-response').empty();
                                $('.error-response').empty().html("OTP is not valid, plase enter a valid OTP or You can resend OTP by below link");
                            }
                            if (response.message) {
                                $('#login').find('span.error-response').html(response.message)
                            }
                        }
                    });
                });

                $(document).on('click', '.resend-otp', function(e){
                    e.preventDefault();

                    $.ajax({
                        type: 'POST',
                        url: "{{route('frontend.auth.resend_otp')}}",
                        data: $('#otpVerificationForm').serializeArray(),
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                $('#otpVerificationForm')[0].reset();
                                $("#otpVerificationForm input[name='contact_number']").val(response.contact_number);
                                $("#otpVerificationForm input[name='user_id']").val(response.user_id);
                                $('.success-response').empty().html("OTP has been sent to your phone number " + response.contact_number_preview);
                                $('.resend-otp').hide()

                                remaining_interval(response.otp_expire_time);
                            }
                        },
                        error: function (jqXHR) {
                            var response = $.parseJSON(jqXHR.responseText);
                        }
                    });
                })
            });

        });
    </script>

    {{-- extra script --}}
    <script>
        // interval function
        function remaining_interval(time) {
            var remaining_secs = time;

            $('.otp-expire-time').css('display', 'block');
            $('.resend-otp').hide();

            var interval = setInterval(() => {
                remaining_secs -= 1

                var minutes = Math.floor(remaining_secs / 60);
                var seconds = remaining_secs - minutes * 60;

                $('.otp-expire-time').html(str_pad_left(minutes,'0',2)+':'+str_pad_left(seconds,'0',2));
                if (remaining_secs < 1) {
                    $('.otp-expire-time').hide();
                    $('.resend-otp').show();
                    clearInterval(interval);
                }
            }, 1000);
        }

        function str_pad_left(string,pad,length) {
            return (new Array(length+1).join(pad)+string).slice(-length);
        }
    </script>


    @if (session('otp_data'))
        @php
            $otp_data = session('otp_data');
        @endphp
        @if (time() - $otp_data['created_at'] < 10*60)
        <script>
            remaining_interval({{ 10*60 - (time() - $otp_data['created_at']) }})
        </script>
        @endif
    @endif

    {{-- validation script --}}
    <script>
        $('#registerForm').validate({
            errorElement: 'span',
            errorClass: 'invalid-feedback',
            highlight: function ( element, errorClass, validClass ) { 
                $( element ).addClass( "is-invalid" ).removeClass( "invalid-feedback" ); 
            },
            rules : {
                password : {
                    minlength : 5
                },
                password_confirmation : {
                    minlength : 5,
                    equalTo : "#password"
                }
            },
            errorPlacement: function (error, element) {
                if (element.hasClass("select2-hidden-accessible")) {
                    error.insertAfter($(element).parents('.form-group').find('.select2-container'));
                } else if(element.attr("type") == "radio") {
                    error.insertAfter($(element).parents('.form-group').find('label').last());
                    error.addClass('d-block');
                } else {
                    error.insertAfter($(element));
                }
            }
        })

        $('input[name="phone"]').keypress(function(e) {
            if ($(this).val().length > 9) {
                return false;
            }
            var a = [];
            var k = e.which;

            for (i = 48; i < 58; i++)
                a.push(i);

            if (!(a.indexOf(k)>=0))
                e.preventDefault();
        });

        var counter = 0;        
        document.querySelector('input[name="otp"]').addEventListener('keypress', function (e) {
            if (this.value.length > 3) {
                e.preventDefault();
                e.stopPropagation();
                return false
            }
        });

    </script>

@endpush
