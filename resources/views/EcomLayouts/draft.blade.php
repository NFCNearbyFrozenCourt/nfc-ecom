@extends('layouts.app')
@section('title', __( 'Ecom Drafts'))
@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('Ecom Drafts')</h1>
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('sell_list_filter_location_id',  __('purchase.business_location') . ':') !!}
                {!! Form::select('sell_list_filter_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all') ]); !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('sell_list_filter_customer_id',  __('contact.customer') . ':') !!}
                {!! Form::select('sell_list_filter_customer_id', $customers, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('sell_list_filter_date_range', __('report.date_range') . ':') !!}
                {!! Form::text('sell_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                {!! Form::label('created_by',  __('report.user') . ':') !!}
                {!! Form::select('created_by', $sales_representative, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
            </div>
        </div>
    @endcomponent

    @component('components.widget', ['class' => 'box-primary'])
        
        <div id="draft-cards" class="row"></div>
    @endcomponent
</section>

@stop
@section('javascript')
<script type="text/javascript">
$(document).ready(function(){
    function loadDrafts() {

        $.ajax({
            url: '/sells/draft-dt?is_quotation=0',
            method: 'GET',
            success: function(response) {
                let drafts = response.data;
                
                drafts.sort((a, b) => {
        const parseDate = (str) => {
            const [datePart, timePart] = str.split(' ');
            const [day, month, year] = datePart.split('-');
            // Ensure proper date format for comparison: YYYY-MM-DD
            return new Date(`${year}-${month}-${day}${timePart ? 'T' + timePart : ''}`);
        };

        return parseDate(b.transaction_date) - parseDate(a.transaction_date);
    });
                
                let cardsHtml = '';
                drafts.forEach(draft => {
                    let actionHtml = draft.action;
            let idMatch = actionHtml.match(/\/pos\/(\d+)/); // Extract ID from '/pos/{id}'
            
            if (!idMatch) {
                idMatch = actionHtml.match(/\/sells\/(\d+)/); // Try extracting from '/sells/{id}' if needed
            }

            let url = idMatch ? idMatch[1] : 'Unknown'; // Get ID if found, else 'Unknown'
            let newUrl = `/sells/editEcom/${url}`;

                    cardsHtml += `
                        <div class="col-md-4">
                            <div class="card tw-shadow-lg tw-rounded-lg tw-bg-white tw-p-4 tw-mb-4" onclick="window.location.href='${newUrl}'">
                                <div class="card-body">
                                    <h5 class="tw-font-bold">${draft.invoice_no}</h5>
                                    <p><strong>@lang('messages.date'):</strong> ${draft.transaction_date}</p>
                                    <p><strong>@lang('sale.customer_name'):</strong> ${draft.conatct_name}</p>
                                    <p><strong>@lang('lang_v1.contact_no'):</strong> ${draft.mobile}</p>
                                    <p><strong>@lang('sale.location'):</strong> ${draft.business_location}</p>
                                    <p><strong>@lang('lang_v1.total_items'):</strong> ${draft.total_items}</p>
                                    <p><strong>@lang('lang_v1.added_by'):</strong> ${draft.added_by}</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                $('#draft-cards').html(cardsHtml);
            }
        });
    }
    
    loadDrafts();
    $(document).on('change', '#sell_list_filter_location_id, #sell_list_filter_customer_id, #created_by', function() {
        loadDrafts();
    });
});

document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".cart-item").forEach(item => {
        item.addEventListener("click", function () {
            const dataHref = this.getAttribute("data-href");
            if (dataHref) {
                window.location.href = dataHref;
            }
        });
    });
});
</script>


@endsection
