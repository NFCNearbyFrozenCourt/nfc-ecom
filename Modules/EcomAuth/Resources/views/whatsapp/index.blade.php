@extends('layouts.app')

@section('content')


<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">WhatsApp Business API Settings</h1>
</section>

<!-- Main content -->
<section class="content no-print">
    <div class="tw-max-w-3xl tw-mx-auto">
        <div class="tw-bg-white tw-shadow-md tw-rounded-lg tw-p-6">
            <h2 class="tw-text-lg tw-font-semibold tw-mb-4">Update WhatsApp API Settings</h2>

            <form action="{{ route('whatsapp.update') }}" method="POST">
                @csrf
                @method('POST')

                @foreach($env as $key => $value)
                    <div class="tw-mb-4">
                        <label class="tw-block tw-text-gray-700 tw-font-medium">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                        <input type="text" style="background: white" name="{{ $key }}" value="{{ $value }}" class="tw-w-full tw-border tw-rounded-lg tw-p-2">
                    </div>
                @endforeach

                <button type="submit" class="tw-bg-blue-600 tw-text-white tw-px-4 tw-py-2 tw-rounded-lg tw-font-medium hover:tw-bg-blue-700">
                    Update Settings
                </button>
            </form>
        </div>
    </div>
</section>
<!-- /.content -->

<!-- Toast Notification -->
@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.success("{{ session('success') }}");
        });
    </script>
@endif

@if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            toastr.error("{{ session('error') }}");
        });
    </script>
@endif

@stop