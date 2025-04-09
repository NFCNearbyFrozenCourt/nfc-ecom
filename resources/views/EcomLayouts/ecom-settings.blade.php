@extends('layouts.app')

@section('title', __( 'E-Commerce Settings'))

@section('content')

<section class="content-header no-print">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">E-Commerce Settings</h1>
</section>

<section class="content no-print">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="box">
        <div class="box-body">
            <form action="{{ route('update.ecom.settings') }}" method="POST">
                @csrf

                <div class="col-sm-4">
                    <!-- Product MRP -->
                    <div class="form-group">
                        <h4>General</h4>
                        <div class="checkbox">
                            <label>
                                <input class="input-icheck" 
                                       name="product_mrp" 
                                       type="checkbox" 
                                       value="1" 
                                       {{ $settings->product_mrp ? 'checked' : '' }}>
                                Enable Product MRP
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-sm-8">
                    <!-- Login Methods -->
                    <div class="form-group">
                        <h4>Login Methods <span class="text-danger">*</span></h4>
                        <div class="checkbox">
                            <label>
                                <input class="input-icheck" 
                                       name="whatsapp_login" 
                                       type="checkbox" 
                                       value="1" 
                                       {{ $settings->whatsapp_login ? 'checked' : '' }}>
                                Enable WhatsApp Login
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input class="input-icheck" 
                                       name="firebase_login" 
                                       type="checkbox" 
                                       value="1" 
                                       {{ $settings->firebase_login ? 'checked' : '' }}>
                                Enable Firebase Login
                            </label>
                        </div>
                        <small class="text-muted">At least one login method is required</small>
                    </div>
                </div>

                <div class="clearfix"></div>
                <div style="margin-top: 20px;">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</section>

@stop

@section('javascript')
@includeIf('sales_order.common_js')

<script>
    $(document).ready(function(){
        $('.input-icheck').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
@endsection