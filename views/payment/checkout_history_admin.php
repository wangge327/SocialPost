<div class="row">
    <div class="col-md-12">
        <div class="light-card table-responsive p-b-3em">
            <table class="table display companies-list" id="data-table">
                <thead>
                    <tr>
                        <th class="text-center w-70"></th>
                        <th>Student Name</th>
                        <th>Fine Type</th>
                        <th>Amount</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @if ( count($checkout_history) > 0 )
                    @foreach ( $checkout_history as $each_checkout_history )
                        @foreach($each_checkout_history['fine_history'] as $each_fine_history)
                            <tr>
                                <td class="text-center"></td>
                                <td><strong>{{ $each_checkout_history['student']->fname }} {{ $each_checkout_history['student']->lname }}</strong> </td>
                                <td>{{ $each_fine_history['fine_type'] }}</td>
                                <td><strong>${{ $each_fine_history['fine_amount'] }}</strong></td>
                                <td><strong>{{ $each_checkout_history['checkout']->created_at}}</strong> </td>

                            </tr>
                        @endforeach
                    @endforeach
                    @else
                    <tr>
                        <td colspan="9" class="text-center">It's empty here</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
