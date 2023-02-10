<div class="bg-white padding-15 mg-top-20">
    <div class="row text-align-center">
        <h4>今天 Facebook 帖子历史</h4>
    </div>
    <div class="dash-transaction-history  vf-history">
        <div class="each-row">
            <div class="each-cell">标题</div>
            <div class="each-cell">博客</div>
            <div class="each-cell">时间</div>
        </div>
        @foreach($return_fine_history as $each_fine_history)
        <a class="fetch-display-click" data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}|fine_history_id:{{$each_fine_history->id}}" url="<?= url("Customer@fine_history"); ?>" holder=".holder-add-fine" modal="#add_fine" style="cursor: pointer;">
            <div class="each-row">
                <div class="each-cell">Fine</div>
                <div class="each-cell">${{$each_fine_history->total_amount}}&nbsp;</div>
                <div class="each-cell">{{date("Y-m-d H:i", strtotime($each_fine_history->created_at))}}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<div class="bg-white padding-15 mg-top-20">
    <div class="row text-align-center">
        <h4>今天 Twitter 帖子历史</h4>
    </div>
    <div class="dash-transaction-history  vf-history">
        <div class="each-row">
            <div class="each-cell">标题</div>
            <div class="each-cell">博客</div>
            <div class="each-cell">时间</div>
        </div>
        @foreach($return_fine_history as $each_fine_history)
        <a class="fetch-display-click" data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}|fine_history_id:{{$each_fine_history->id}}" url="<?= url("Customer@fine_history"); ?>" holder=".holder-add-fine" modal="#add_fine" style="cursor: pointer;">
            <div class="each-row">
                <div class="each-cell">Fine</div>
                <div class="each-cell">${{$each_fine_history->total_amount}}&nbsp;</div>
                <div class="each-cell">{{date("Y-m-d H:i", strtotime($each_fine_history->created_at))}}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>

<div class="bg-white padding-15 mg-top-20">
    <div class="row text-align-center">
        <h4>今天 Youtube 帖子历史</h4>
    </div>
    <div class="dash-transaction-history  vf-history">
        <div class="each-row">
            <div class="each-cell">标题</div>
            <div class="each-cell">博客</div>
            <div class="each-cell">时间</div>
        </div>
        @foreach($return_fine_history as $each_fine_history)
        <a class="fetch-display-click" data="customerid:{{ $student->id }}|csrf-token:{{ csrf_token() }}|fine_history_id:{{$each_fine_history->id}}" url="<?= url("Customer@fine_history"); ?>" holder=".holder-add-fine" modal="#add_fine" style="cursor: pointer;">
            <div class="each-row">
                <div class="each-cell">Fine</div>
                <div class="each-cell">${{$each_fine_history->total_amount}}&nbsp;</div>
                <div class="each-cell">{{date("Y-m-d H:i", strtotime($each_fine_history->created_at))}}</div>
            </div>
        </a>
        @endforeach
    </div>
</div>