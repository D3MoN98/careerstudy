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
                                <div class="stepper-item active">
                                    <div class="step-counter">2</div>
                                    <div class="step-name">Email Verfification</div>
                                </div>
                                <div class="stepper-item">
                                    <div class="step-counter">3</div>
                                    <div class="step-name">KYC Verfification</div>
                                </div>
                                <div class="stepper-item">
                                    <div class="step-counter">4</div>
                                    <div class="step-name">Approve</div>
                                </div>
                            </div>

                            <div class="row mt-5">
                                <div class="col-12">
                                    <p class="text-center font-weight-bold">
                                        AN EMAIL HAS BEEN SENT TO YOUR MAIL FROM {{ config('APP_NAME') }} TO YOUR MAIL ADDRESS WITH A LINK TO VERIFY YOUR ACCOUNT. YOU MUST VERIFY YOUR YOUR ACCOUNT TO CONTINUE NEXT STEP.
                                    </p>
                                </div>
                            </div>
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
