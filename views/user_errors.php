<?php include "includes/head.php" ?>

<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <h3>Import Students Errors</h3>
            <div class="row student">
                <div class="col-md-3">
                    <form class="simcy-form page-actions lower" method="post" loader="true" action="<?= url("Member@ImportAll"); ?>">
                        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}">
                        <button class="btn btn-primary"><i class="ion-plus-round"></i> Import All
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em">
                    <table class="table display companies-list" id="data-table">
                        <thead>
                            <tr>
                                <th class=""></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th class="text-center">Employer</th>
                                <th class="text-center">Sponsor</th>
                                <th class="text-center">Error Message</th>
                                <th class="text-center w-70">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ( count($customers) > 0 )
                            @foreach ( $customers as $index => $customer )
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td><strong>{{ $customer['user']->fname }} {{ $customer['user']->lname }}</strong></td>
                                <td><strong>{{ $customer['user']->email }}</strong></td>
                                <td>{{ $customer['user']->address }}</td>
                                <td class="text-center">{{ $customer['user']->employer }}</td>
                                <td class="text-center">{{ $customer['user']->sponsor }}</td>
                                <td class="text-center">{{ $customer['user']->error_message }}</td>
                                <td class="text-center">
                                    <div class="dropdown">
                                        <span class="company-action dropdown-toggle" data-toggle="dropdown"><i class="ion-ios-more"></i></span>
                                        <ul class="dropdown-menu" role="menu">
                                            <li role="presentation">
                                                <a class="fetch-display-click" data="customerid:{{ $customer['user']->id }}|table:users_errors|csrf-token:{{ csrf_token() }}" url="<?= url("Customer@updateview"); ?>" holder=".update-holder" modal="#update" href="">Edit</a>
                                                <!--                                                <a class="send-to-server-click"  data="customerid:{{ $customer['user']->id }}|csrf-token:{{ csrf_token() }}" url="--><? //=url("Customer@delete");
                                                                                                                                                                                                                            ?><!--" warning-title="Are you sure?" warning-message="This student's data will be deleted." warning-button="Continue" loader="true" href="">Delete</a>-->
                                            </li>
                                        </ul>
                                    </div>
                                </td>
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

    <!-- Update User Modal -->
    <div class="modal fade" id="update" role="dialog">
        <div class="close-modal" data-dismiss="modal">&times;</div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update Student Data</h4>
                </div>
                <form class="update-holder simcy-form" id="update-customer-form" action="<?= url("Customer@update"); ?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                    <div class="loader-box">
                        <div class="circle-loader"></div>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <!-- footer -->
    {{ view("includes/footer"); }}


    @if ( count($customers) > 0 )
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
        });
        let baseUrl = '<?= url(""); ?>';
        let csrf = '<?= csrf_token(); ?>';
    </script>
    @endif

    <script src="<?= url(""); ?>assets/js/room.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>