<div class="row">
    <div class="col-md-12">
        <label>Total Amount of Fine : ${{ $totalFineFee }}</label>
        <div class="light-card table-responsive p-b-3em">
            <table class="table display companies-list" id="data-table">
                <thead>
                    <tr>
                        <th class="text-center w-70"></th>
                        <th>Fine Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if(!empty($fine_history))
                        @foreach($fine_history as $each_fine_history)
                            <tr>
                                <td class="text-center"></td>
                                <td>{{ $each_fine_history['fine_type'] }}</td>
                                <td><strong>${{ $each_fine_history['fine_amount'] }}</strong></td>
                                <td><strong>{{ $each_fine_history['fine_history']->created_at}}</strong> </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
