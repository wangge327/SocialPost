<?php include "includes/head.php" ?>
<body>
<!-- header start -->
{{ view("includes/header", $data); }}
<!-- sidebar -->
{{ view("includes/sidebar", $data); }}

<div class="content">
    <div class="page-title">
        <h3>Action Logs</h3>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="light-card table-responsive p-b-3em">
                <div class="custom-filter" style="display: none">
                    <label style="margin-left: 20px">
                        Action Type:
                        <select id="action_type-select">
                            <option value="">All</option>
                            <option value="Profile">Profile</option>
                            <option value="Student">Student</option>
                            <option value="Room">Room</option>
                            <option value="Drawer">Drawer</option>
                            <option value="Fine">Fine</option>
                        </select>
                    </label>
                </div>
                <table class="table display companies-list" id="data-table">
                    <thead>
                    <tr>
                        <th>Person</th>
                        <th>Action Type</th>
                        <th>Action Sub Type</th>
                        <th>Action Content</th>
                        <th>Action Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if ( count($action_log) > 0 )
                        @foreach ( $action_log as $index => $item )
                        <tr class="room-record">
                            <td><strong>{{ $item->person }}</strong></td>
                            <td>{{ $item->action_type }}</td>
                            <td>{{ $item->action_sub_type }}</td>
                            <td>{{ $item->action_content }}</td>
                            <td>{{ $item->created_at }}</td>
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

@if ( count($action_log) > 0 )
<script>
    $(document).ready(function() {

        $('#data-table').DataTable({
            dom: 'Bfrtip',
            "bSort" : true,
            buttons: [
                'excelHtml5'
            ],
            "order": [[ 4, "desc" ]]
        });
        $(".dataTables_filter").append($(".custom-filter"));
        $(".custom-filter").show();

        $('#action_type-select').on('change',function(){
            $('#data-table').DataTable().search(
                $('#action_type-select').val()
            ).draw();
        });
    });
</script>
@endif

</body>

</html>
