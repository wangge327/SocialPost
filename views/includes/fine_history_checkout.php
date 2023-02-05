<div class="bg-white padding-15 mg-top-20">
    <div class="row text-align-center"><h4>Fine history</h4></div>
    <div class="dash-transaction-history  vf-history">
        <div class="each-row">
            <div class="each-cell">Type</div>
            <div class="each-cell">Amount</div>
            <div class="each-cell">Date</div>
        </div>
        @foreach ($return_fine_history as $each_fine_history )
        <div class="each-row">
            <div class="each-cell">{{$each_fine_history['fine_type']}}&nbsp;</div>
            <div class="each-cell">${{$each_fine_history['fine_amount']}}&nbsp;</div>
            <div class="each-cell">{{$each_fine_history['fine_history']->created_at}}</div>
        </div>
        @endforeach
    </div>
</div>