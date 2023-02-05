{{ view("includes/head", $data); }}
<body>
    <!-- header start -->
    {{ view("includes/header", $data); }}
    <!-- sidebar -->
    {{ view("includes/sidebar", $data); }}

    <div class="content">
        <div class="page-title">
            <h3>Payment Status </h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="light-card table-responsive p-b-3em payment-status">
                	
                </div>
            </div>

        </div>
    </div>

    <!-- footer -->
    {{ view("includes/footer"); }}

    @if ( count($invoices) > 0 )
    <script>
        $(document).ready(function() {
            
        });
        let baseUrl = '<?=url("");?>';
        let csrf='<?=csrf_token();?>';
    </script>
    @endif
    <script src="<?= url(""); ?>assets/js/room.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>
