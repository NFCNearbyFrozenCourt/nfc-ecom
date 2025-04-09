@extends('layouts.app')

@section('title', __('Payment Transactions'))

@section('content')
    <section class="content-header no-print">
        <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Payment Transactions</h1>
    </section>

    <section class="content no-print">
        @component('components.widget', ['class' => 'box-primary', 'title' => __('Payment Transactions')])
            <div class="table-responsive">
                <table class="table table-bordered table-striped bg-white" id="payments-table">
                    <thead>
                        <tr>
                            <th>@lang('ID')</th>
                            <th>@lang('User ID')</th>
                            <th>@lang('Order ID')</th>
                            <th>@lang('Request')</th>
                            <th>@lang('Response')</th>
                            <th>@lang('Status')</th>
                            <th>@lang('Created At')</th>
                            <th>@lang('Updated At')</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcomponent
    </section>
@endsection

@section('javascript')
<script>
    $(document).ready(function() {
        function formatJsonDisplay(jsonString) {
            try {
                const parsedJson = typeof jsonString === 'string' ? JSON.parse(jsonString) : jsonString;
                const jsonStr = JSON.stringify(parsedJson, null, 2);
                return jsonStr.length > 100 ? jsonStr.substring(0, 100) + '...' : jsonStr;
            } catch (e) {
                return '<span class="text-danger">Invalid JSON</span>';
            }
        }

        var payments_table = $('#payments-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('cashfree.api.transactions') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'user_id', name: 'user_id' },
                { data: 'order_id', name: 'order_id' },
                { 
                    data: 'request',
                    name: 'request',
                    render: function(data) {
                        if (!data) return '<span class="text-muted">No Data</span>';
                        try {
                            let parsedData = typeof data === 'string' ? JSON.parse(data) : data;
                            let formattedJson = JSON.stringify(parsedData, null, 2);
                            let displayText = formattedJson.length > 100 ? formattedJson.substring(0, 100) + '...' : formattedJson;
                            return `<span class='json-preview' data-json='${encodeURIComponent(JSON.stringify(parsedData))}'>${displayText}</span>`;
                        } catch (e) {
                            return '<span class="text-danger">Invalid JSON</span>';
                        }
                    }
                },
                { 
                    data: 'response',
                    name: 'response',
                    render: function(data) {
                        if (!data) return '<span class="text-muted">No Data</span>';
                        try {
                            let parsedData = typeof data === 'string' ? JSON.parse(data) : data;
                            let formattedJson = JSON.stringify(parsedData, null, 2);
                            let displayText = formattedJson.length > 100 ? formattedJson.substring(0, 100) + '...' : formattedJson;
                            return `<span class='json-preview' data-json='${encodeURIComponent(JSON.stringify(parsedData))}'>${displayText}</span>`;
                        } catch (e) {
                            return '<span class="text-danger">Invalid JSON</span>';
                        }
                    }
                },
                { 
                    data: 'status', 
                    name: 'status',
                    render: function(data) {
                        return data == 1 
                            ? '<span class="text-success">Success</span>' 
                            : '<span class="text-warning">Pending</span>';
                    }
                },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' },
            ]
        });

        $(document).on('click', '.json-preview', function() {
            let fullData = decodeURIComponent($(this).data('json'));
            try {
                let formattedJson = JSON.stringify(JSON.parse(fullData), null, 2);
                $('#jsonModalBody').html(`<pre>${formattedJson}</pre>`);
                $('#jsonModal').modal('show');
            } catch (e) {
                alert('Invalid JSON');
            }
        });
    });
</script>

<div class="modal fade" id="jsonModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">JSON Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="jsonModalBody"></div>
        </div>
    </div>
</div>

<style>
    .json-preview {
        cursor: pointer;
        color: blue;
        text-decoration: underline;
    }
    pre {
        white-space: pre-wrap;
    }
</style>
@endsection
