<div class="bg-white mg-top-20 padding-10">
    <div class="row text-align-center margin-0">Payment History</div>
    <div class="dash-transaction-history">
        <div class="each-row">
            <div class="each-cell">Type</div>
            <div class="each-cell">Amount</div>
            <div class="each-cell">Time</div>
            <div class="each-cell">
                @if($user->role == "user")
                Status
                @else
                Receipt
                @endif
            </div>
        </div>
        @foreach ($invoice as $each_invoice )
        <div class="each-row">
            <div class="each-cell">{{$each_invoice->payment_mode}}</div>
            <div class="each-cell">{{$each_invoice->price}}</div>
            <div class="each-cell">{{date("Y-m-d H:i", strtotime($each_invoice->created_at))}}</div>
            <div class="each-cell">
                @if($user->role == "user")
                {{$each_invoice->status}}
                @else
                <a href="{{url('PrintController@invoicePrint').$each_invoice->id}}" class="btn btn-success" style="padding: 1px 20px!important;">Print</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>