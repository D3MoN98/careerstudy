@extends('frontend'.(session()->get('display_type') == "rtl"?"-rtl":"").'.layouts.app'.config('theme_layout'))

@section('title', app_name() . ' | ' . __('labels.teacher.teacher_register_box_title'))

@section('content')
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold">{{__('labels.teacher.teacher_register_box_title')}}</h2>
                </div>
            </div>
        </div>
    </section>
    <section id="about-page" class="about-page-section pb-0">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card  border-0">
                        <div class="card-body">

                            <div class="stepper-wrapper">
                                <div class="stepper-item completed">
                                    <div class="step-counter">1</div>
                                    <div class="step-name">Personal Details</div>
                                </div>
                                <div class="stepper-item completed">
                                    <div class="step-counter">2</div>
                                    <div class="step-name">Email Verfification</div>
                                </div>
                                <div class="stepper-item active">
                                    <div class="step-counter">3</div>
                                    <div class="step-name">KYC Verfification</div>
                                </div>
                                <div class="stepper-item">
                                    <div class="step-counter">4</div>
                                    <div class="step-name">Approve</div>
                                </div>
                            </div>


                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="list-inline list-style-none">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            {{ html()->form('POST', route('frontend.auth.teacher.register.kyc.post'))->acceptsFiles()->class('form-horizontal')->open() }}
                            {!! csrf_field() !!}

                            <div class="row">

                                <div class="col-12  mt-3 mb-2">
                                    <h3>{{ __('validation.attributes.frontend.kyc_information') }}</h3>

                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ html()->label(__('validation.attributes.frontend.subject_specialist'))->for('subject_specialist') }}

                                        {{ html()->text('subject_specialist')
                                            ->class('form-control')
                                            ->placeholder(__('validation.attributes.frontend.subject_specialist'))
                                            ->attribute('maxlength', 191)
                                            ->required() }}
                                    </div><!--form-group-->
                                </div><!--col-->

                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ html()->label(__('validation.attributes.frontend.qualification_details'))->for('qualification_details') }}

                                        {{ html()->text('qualification_details')
                                            ->class('form-control')
                                            ->placeholder(__('validation.attributes.frontend.qualification_details'))
                                            ->attribute('maxlength', 191)
                                            ->required() }}
                                    </div><!--form-group-->
                                </div><!--col-->
                                
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ html()->label(__('validation.attributes.frontend.teaching_details'))->for('teaching_details') }}

                                        {{ html()->text('teaching_details')
                                            ->class('form-control')
                                            ->placeholder(__('validation.attributes.frontend.teaching_details'))
                                            ->attribute('maxlength', 191)
                                            ->required() }}
                                    </div><!--form-group-->
                                </div><!--col-->

                            </div><!--row-->



                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ html()->label(__('validation.attributes.frontend.profile_photo'))->for('profile_photo') }}

                                        <input type="file" class="form-control d-inline-block" name="profile_photo" id="profile_photo" required>


                                    </div><!--form-group-->
                                </div><!--col-->
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ html()->label(__('validation.attributes.frontend.signature'))->for('signature') }}

                                        <input type="file" class="form-control d-inline-block" name="signature" id="signature" required>

                                    </div><!--form-group-->
                                </div><!--col-->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ html()->label(__('validation.attributes.frontend.qualification_certificate'))->for('qualification_certificate') }}

                                        <input type="file" class="form-control d-inline-block" name="qualification_certificate" id="qualification_certificate" required>

                                    </div><!--form-group-->
                                </div><!--col-->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ html()->label(__('validation.attributes.frontend.bank_passbook'))->for('bank_passbook') }}

                                        <input type="file" class="form-control d-inline-block" name="bank_passbook" id="signature" required>

                                    </div><!--form-group-->
                                </div><!--col-->

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ html()->label(__('validation.attributes.frontend.resume'))->for('resume') }}

                                        <input type="file" class="form-control d-inline-block" name="resume" id="resume" required>

                                    </div><!--form-group-->
                                </div><!--col-->
                            </div>



                            <div class="row">
                                <div class="col-12 mt-3 mb-2">
                                    <h3>{{ __('validation.attributes.frontend.payment_information') }}</h3>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        {{ html()->label(__('labels.teacher.payment_details')) }}

                                        <select class="form-control" name="payment_method" id="payment_method" required>
                                            <option value="bank" {{ old('payment_method') == 'bank'?'selected':'' }}>{{ trans('labels.teacher.bank') }}</option>
                                            <option value="paypal" {{ old('payment_method') == 'paypal'?'selected':'' }}>{{ trans('labels.teacher.paypal') }}</option>
                                        </select>
                                    </div><!--form-group-->
                                </div><!--col-->
                            </div><!--row-->

                            <div class="bank_details">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ html()->label(__('labels.teacher.bank_details.name')) }}

                                            {{ html()->text('bank_name')
                                                ->class('form-control')
                                                ->placeholder(__('labels.teacher.bank_details.name')) }}
                                        </div><!--form-group-->
                                    </div><!--col-->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ html()->label(__('labels.teacher.bank_details.ifsc_code')) }}

                                            {{ html()->text('ifsc_code')
                                                ->class('form-control')
                                                ->placeholder(__('labels.teacher.bank_details.ifsc_code')) }}
                                        </div><!--form-group-->
                                    </div><!--col-->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ html()->label(__('labels.teacher.bank_details.account')) }}

                                            {{ html()->text('account_number')
                                                ->class('form-control')
                                                ->placeholder(__('labels.teacher.bank_details.account')) }}
                                        </div><!--form-group-->
                                    </div><!--col-->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {{ html()->label(__('labels.teacher.bank_details.holder_name')) }}

                                            {{ html()->text('account_name')
                                                ->class('form-control')
                                                ->placeholder(__('labels.teacher.bank_details.holder_name')) }}
                                        </div><!--form-group-->
                                    </div><!--col-->
                                </div><!--row-->
                            </div>

                            <div class="paypal_details">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            {{ html()->label(__('labels.teacher.paypal_email')) }}

                                            {{ html()->text('paypal_email')
                                                ->class('form-control')
                                                ->placeholder(__('labels.teacher.paypal_email')) }}
                                        </div><!--form-group-->
                                    </div><!--col-->
                                </div><!--row-->
                            </div>


                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ html()->label(__('validation.attributes.frontend.upi_id'))->for('upi_id') }}
    
                                        {{ html()->text('upi_id')
                                            ->class('form-control')
                                            ->placeholder(__('validation.attributes.frontend.upi_id'))}}
                                    </div><!--form-group-->
                                </div><!--col-->
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <p class="font-weight-bold">
                                        <label for="infomation_confirmation">
                                            <input type="checkbox" name="infomation_confirmation" value="1" id="infomation_confirmation" required>
                                            I hereby declare that the information provided by me above is true and correct to the best of my knowledge and belief.
                                        </label>
                                    </p>
                                    <p class="font-weight-bold">
                                        <label for="infomation_confirmation">
                                            <input type="checkbox" name="terms_and_conditions_confirmation" value="1" id="infomation_confirmation" required>
                                            I have read and agree to the <a href="https://careerstudy.in/terms-conditions">Terms &amp; Conditions</a>
                                        </label>
                                    </p>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col">
                                    <div class="form-group mb-0 text-center mt-4 clearfix">
                                        <button class="btn btn-info mx-auto btn-lg" type="submit">{{__('labels.frontend.modal.register_now')}}</button>
                                    </div><!--form-group-->
                                </div><!--col-->
                            </div><!--row-->
                            {{ html()->form()->close() }}
                        </div><!-- card-body -->
                    </div><!-- card -->
                </div>
            </div><!-- row -->
        </div>
    </section>
@endsection
@push('after-scripts')
@if(old('payment_method') && old('payment_method') == 'bank')
<script>
    $('.paypal_details').hide();
    $('.bank_details').show();
</script>
@elseif(old('payment_method') && old('payment_method') == 'paypal')
<script>
    $('.paypal_details').show();
    $('.bank_details').hide();
</script>
@else
<script>
    $('.paypal_details').hide();
</script>
@endif
<script>
    $(document).on('change', '#payment_method', function(){
        if($(this).val() === 'bank'){
            $('.paypal_details').hide();
            $('.bank_details').show();
        }else{
            $('.paypal_details').show();
            $('.bank_details').hide();
        }
    });
</script>
@endpush
