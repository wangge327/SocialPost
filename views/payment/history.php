{{ view("includes/head", $data); }}
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <h3>Payment History </h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class=""></th>
                                <th>Time</th>
                                <th>Order Id</th>
                                <th>Price</th>
                                <th>Payment Mode</th>
                                <th>Payment Option</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Card Number</th>
<!--                                <th class="text-center w-70">Action</th>-->
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($invoices) > 0 )
                            @foreach ( $invoices as $index => $item )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $item->created_at }}</strong></td>
                                <td>{{ $item->order_id }}</td>
                                <td><strong>${{ $item->price }}</strong></td>
                                <td>{{ $item->payment_mode }}</td>
                                <td>{{ $item->payment_option }}</td>
                                <th>
                                    @if ($user->role!='user')
                                    <a href="{{url('Customer@profile').$item->student_id}}">
                                        {{ $item->name }}
                                    </a>
                                    @else
                                        {{ $item->name }}
                                    @endif
                                </th>
                                <td class="text-center">{{ $item->email}}</td>
                                <td class="text-center">{{ $item->card_number}}</td>
<!--                                <td class="text-center">-->
<!--                                    <div class="dropdown">-->
<!--                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>-->
<!--                                        <ul class="dropdown-menu" role="menu">-->
<!--                                            <li role="presentation">-->
<!--                                                <a class="send-to-server-click"  data="invoice_id:{{ $item->id }}|table:users|csrf-token:{{ csrf_token() }}" url="--><?//=url("CC@refundInvoice");?><!--" warning-title="Are you sure?" warning-message="This payment will be refunded." warning-button="Continue" loader="true" href="">Refund</a>-->
<!--                                            </li>-->
<!--                                        </ul>-->
<!--                                    </div>-->
<!--                                </td>-->
                            </tr>
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
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    @if ( count($invoices) > 0 )
    <script>
        $(document).ready(function() {
            $('#data-table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'csvHtml5'
                ]
            });
            $(".input-daterange").datepicker({
                todayHighlight: !0,
                format: 'yyyy-mm-dd'
            });
        });
        let baseUrl = '<?=url("");?>';
        let csrf='<?=csrf_token();?>';
    </script>
    @endif

    <script src="<?= url(""); ?>assets/js/room.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>
