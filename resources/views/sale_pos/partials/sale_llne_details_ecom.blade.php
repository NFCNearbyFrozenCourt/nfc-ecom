
<div class="row" style="
overflow: hidden;
">
    @foreach($sell->sell_lines as $sell_line)
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-green text-white">
                    <strong>#{{ $loop->iteration }}</strong>
                </div>
                <div class="card-body">
                    <h5 class="card-title">{{ $sell_line->product->name }}</h5>
                    @if($sell_line->product->type == 'variable')
                        <p class="card-text">
                            {{ $sell_line->variations->product_variation->name ?? ''}} - 
                            {{ $sell_line->variations->name ?? ''}}
                        </p>
                    @endif
                    <p class="card-text">SKU: {{ $sell_line->variations->sub_sku ?? ''}}</p>
                    @php
                        $brand = $sell_line->product->brand;
                    @endphp
                    @if(!empty($brand->name))
                        <p class="card-text">Brand: {{$brand->name}}</p>
                    @endif
                    <p class="card-text">Quantity: <span class="display_currency pull-right" data-is_quantity="true">{{ $sell_line->quantity }}</span> 
                        {{$sell_line->product->unit->short_name}}</p>
                        <script>console.log(<?php echo $sell_line?>);</script>
                    <p class="card-text">Unit Price: <span class="display_currency pull-right" data-currency_symbol="true">{{ $sell_line->unit_price_before_discount }}</span></p>
                    <p class="card-text">Discount: <span class="display_currency pull-right" data-currency_symbol="true">{{ $sell_line->get_discount_amount() }}</span>
                        @if($sell_line->line_discount_type == 'percentage') ({{$sell_line->line_discount_amount}}%) @endif</p>
                    <p class="card-text">Tax: <span class="display_currency pull-right" data-currency_symbol="true">{{ $sell_line->item_tax }}</span></p>
                    <p class="card-text">Total: <span class="display_currency pull-right" data-currency_symbol="true">{{ $sell_line->quantity * $sell_line->unit_price_inc_tax }}</span></p>
                </div>
            </div>
        </div>
    @endforeach
</div>
