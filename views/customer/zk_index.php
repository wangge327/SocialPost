{{ view("includes/head", $data); }}
<body>

{{ view("includes/header", $data); }}
{{ view("includes/sidebar", $data); }}

<div class="content">
    <?php include "profile_header.php" ?>
    <div class="row">
        <div class="col-md-6">
            <div class="light-card team-card-info text-center">
                <h4>Security Card Number</h4>
                <p>{{$student->cardNo}}</p>
                <form action="{{ url('Zk@index')}}?cmd=new_key" method="post">
                    <input type="hidden" name="csrf-token" value="{{ csrf_token(); }}"/>
                    <div class="form-group" style="margin-bottom: 0px">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="number" class="form-control" placeholder="Input card number" name="card_number">
                            </div>
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-success">Assign Card</button>
                                <a class="btn btn-default" href="{{ url('Zk@index')}}?cmd=disable_key">Remove Card</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-6">
            <div class="light-card team-card-info text-center">
                <h4>Status </h4>
                <p><span
                @if ( $student->zk_status =='Disable' )
                    class="text-darker"
                @else
                    class="text-success"
                @endif
                data-toggle="tooltip" data-placement="top" title="{{$student->zk_status}}"><i class="ion-ios-circle-filled"></i></span>&nbsp;&nbsp;&nbsp;{{$student->zk_status}}</p>
                @if ( $student->zk_status =='Disable' )
                    <a class="btn btn-success" href="{{ url('Zk@index')}}?cmd={{$student->zk_status}}">Set This Student Enable</a>
                @else
                    <a class="btn btn-default" href="{{ url('Zk@index')}}?cmd={{$student->zk_status}}">Set This Student Disable</a>
                @endif
            </div>
        </div>
    </div>

</div>
<!-- footer -->
{{ view("includes/footer"); }}

</body>
</html>