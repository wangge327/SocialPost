{{ view("includes/head", $data); }}
<style>
.content{
	width: 900px;
    margin: 0 auto;
}
.pull-right {
    display: none;
}
</style>
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}

    <div class="content">
        <div class="page-title">
            <h3>Check in</h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if( $page == "Credit Card")
                    <?php include "credit_card_form.php" ?>
                @elseif( $page == "Paypal")
                    <?php include "paypal_form.php" ?>
                @elseif( $page == "confirm.php")
                    <?php include "confirm.php" ?>
                @else
                    <div class="light-checkin-pre table-responsive p-b-3em">
                        <div class="checkin-form1">
                            <input class="form-control" id="lname" placeholder="Last Name" type="text" value="{{ $item->lname}}" aria-invalid="false" >
                            <input class="form-control" id="pin" placeholder="Pin" type="text" value="{{ $item->pin}}" aria-invalid="false" >
                            <span>If you forgot Pin please Reset one.</span>
                            <button class="btn" data-toggle="collapse" data-target="#demo">Reset Pin</button>

							<div id="demo" class="collapse">
							<input class="form-control" id="reset_lname" placeholder="Last Name" type="text" value="{{ $item->lname}}" aria-invalid="false" >
                            <input class="form-control" id="reset_email" placeholder="Email" type="text" value="{{ $item->pin}}" aria-invalid="false" >
							<button type="button" class="btn btn-primary reset-pin" data-toggle="modal" >Send Email</button>
							</div>
                            
                            <button type="button" class="btn btn-primary next-step" data-toggle="modal" >Next </button>
                        </div>

                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}


    
    <form id="target" action="" method="get">    	
    	<input type="hidden" id="token" name="token" value="<?php echo $_GET['token'] ?>" >
    	<input type="hidden" id="uid" name="uid" value="0" >  
    </form>

    <script>
        let token='{{$checkin_token}}';
    	@if($checkin_done == "done")
    	    setTimeout(function(){ logout(); }, 3000);
    	@endif
        $(document).ready(function() {
            $( ".next-step" ).click(function() {
            	var lname = $("#lname").val();
            	var pin = $("#pin").val();
            	$.ajax({        		
					url: "/checkin",
					type: "get",
					data: {
						lname : lname,
						pin : pin
					},
					success: function(data){
						if(data  == "-1"){
							swal("User not found!", "Last name and Pin code is incorrect!", "error");
						}							
						else{
							$("#uid").val(data);
							$( "#target" ).submit();
						}
					}
				});
				//location.replace("/checkin?token=<?php echo $_REQUEST['token'] ?>&step=final");
			});

			$( ".reset-pin" ).click(function() {
				var reset_lname = $("#reset_lname").val();
            	var reset_email = $("#reset_email").val();
            	$.ajax({        		
					url: "/checkin",
					type: "get",
					data: {
						"reset_pin" : true,
						"reset_lname" : reset_lname,
						"reset_email" : reset_email,
						"token" : token
					},
					success: function(data){
						console.log(data);
						if(data == "-1"){
							swal("User not found!", "Last name and Pin code is incorrect!", "error");
						}
						if(data  == "1"){
							swal("Pin Reset!", "New Pin code was sent to your email", "success");
						}
					}
				});
			});
			
        });
        let baseUrl = '<?=url("");?>';
        let csrf='<?=csrf_token();?>';
        
        function logout(){
			location.replace("/checkin?token="+token);
		}
    </script>


    <script src="<?= url(""); ?>assets/js/room.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    
    
</body>

</html>
