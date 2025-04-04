
@extends('layouts.app')

@section('title', __( 'lang_v1.sales_order'))
@section('content')


<style>
    .dataTables_length,
    .dataTables_filter,
    .dataTables_info,
    .dataTables_paginate {
        display: none;
    }
    .sales-card {
        width: 100%;
        margin-bottom: 20px;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background: #fff;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    }
</style>
<script src="{{ asset('js/Ecom-common.js?v=' . $asset_v) }}"></script>

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">Ecom Sales</h1>
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('sell_list_filter_location_id', __('purchase.business_location') . ':') !!}
                {!! Form::select('sell_list_filter_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all') ]); !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('sell_list_filter_customer_id', __('contact.customer') . ':') !!}
                {!! Form::select('sell_list_filter_customer_id', $customers, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('so_list_filter_status', __('sale.status') . ':') !!}
                {!! Form::select('so_list_filter_status', $sales_order_statuses, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
            </div>
        </div>
        @if(!empty($shipping_statuses))
            <div class="col-md-3">
                <div class="form-group">
                    {!! Form::label('so_list_shipping_status', __('lang_v1.shipping_status') . ':') !!}
                    {!! Form::select('so_list_shipping_status', $shipping_statuses, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
                </div>
            </div>
        @endif
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('sell_list_filter_date_range', __('report.date_range') . ':') !!}
                {!! Form::text('sell_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
            </div>
        </div>
    @endcomponent
    @component('components.widget', ['class' => 'box-primary'])
        @can('so.create')
            @slot('tool')
                <div class="box-tools">
                    <a class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-sm tw-text-white" href="{{action([\App\Http\Controllers\SellController::class, 'create'])}}?sale_type=sales_order">
                    <i class="fa fa-plus"></i> @lang('lang_v1.add_sales_order')</a>
                </div>
            @endslot
        @endcan
        @if(auth()->user()->can('so.view_own') || auth()->user()->can('so.view_all'))
        <div id="sales-orders-container">
            <!-- Sales orders will be loaded here dynamically -->
        </div>
        @endif
    @endcomponent
    <div class="modal fade edit_pso_status_modal" tabindex="-1" role="dialog"></div>
</section>
<!-- /.content -->
@stop
@section('javascript')
@includeIf('sales_order.common_js')


<script type="text/javascript">
$(document).ready(function() {

    let end = moment();
    let start = moment().subtract(2, 'days'); // Subtract 2 days to get 3 days total (today + 2 previous days)
    
    // Initialize the date range picker with default values
    $('#sell_list_filter_date_range').daterangepicker(
        {
            ...dateRangeSettings, // Spread the existing settings
            startDate: start,
            endDate: end
        },
        function (start, end) {
            $('#sell_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            loadSalesOrders();
        }
    );

    $('#sell_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#sell_list_filter_date_range').val('');
        loadSalesOrders(); // Call our function instead of sell_table.ajax.reload()
    });

    function loadSalesOrders() {
        // Get filter values
        let params = {
            sale_type: 'sales_order'
        };
        
        // Add date filter if set
        if($('#sell_list_filter_date_range').val()) {
            let start = $('#sell_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
            let end = $('#sell_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
            params.start_date = start;
            params.end_date = end;
        }
        
        // Add other filters
        if($('#sell_list_filter_location_id').length) {
            params.location_id = $('#sell_list_filter_location_id').val();
        }
        
        params.customer_id = $('#sell_list_filter_customer_id').val();
        
        if ($('#so_list_filter_status').length) {
            params.status = $('#so_list_filter_status').val();
        }
        
        if ($('#so_list_shipping_status').length) {
            params.shipping_status = $('#so_list_shipping_status').val();
        }
        
        if($('#created_by').length) {
            params.created_by = $('#created_by').val();
        }
        
        $.ajax({
            url: '/sells',
            type: 'GET',
            data: params,
            success: function(response) {
                let ordersContainer = $('#sales-orders-container');
                ordersContainer.empty();

                response.data.sort((a, b) => {
        const parseDate = (str) => {
            const [datePart, timePart] = str.split(' ');
            const [day, month, year] = datePart.split('-');
            return new Date(`${year}-${month}-${day}T${timePart}:00`);
        };

        return parseDate(b.transaction_date) - parseDate(a.transaction_date);
    });
    
                response.data.forEach(order => {
                    let actionHtml = order.action;
                    let idMatch = actionHtml.match(/\/pos\/(\d+)/); // Extract ID from '/pos/{id}'
                    if (!idMatch) {
                        idMatch = actionHtml.match(/\/sells\/(\d+)/); // Try extracting from '/sells/{id}' if needed
                    }

                    let url = idMatch ? idMatch[1] : 'Unknown'; // Get ID if found, else 'Unknown'
                    let newUrl = `/show-ecom/${url}`;
                    // Replace the base URL
                    let card = `<div class="sales-card" onclick="window.location.href='${newUrl}'" style="cursor: pointer;">
                        <h4>Order No: ${order.invoice_no}</h4>
                        <p><strong>Date:</strong> ${order.transaction_date}</p>
                        <p><strong>Customer:</strong> ${order.conatct_name}</p>
                        <p><strong>Contact:</strong> ${order.mobile}</p>
                        <p><strong>Location:</strong> ${order.business_location}</p>
                        <p><strong>Status:</strong> ${order.status}</p>
                        <p><strong>Shipping Status:</strong> ${order.shipping_status}</p>
                        <p><strong>Quantity Remaining:</strong> ${order.so_qty_remaining}</p>
                        <p><strong>Added By:</strong> ${order.added_by}</p>
                    </div>`;
                    ordersContainer.append(card);
                });
            }
        });
    }
    
    // Add event listeners for all filters
    $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #created_by, #so_list_filter_status, #so_list_shipping_status', function() {
        loadSalesOrders();
    });
    
    // Initial load
    loadSalesOrders();
});</script>

@endsection
