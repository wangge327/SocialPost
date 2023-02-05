<div class="modal fade" id="cancel_lease_modal" role="dialog" style="background-color: #fff;">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cancel Lease</h4>
            </div>
            <form class="holder-cancel-lease simcy-form" action="<?=url("Customer@updateCancelLease");?>" data-parsley-validate="" loader="true" method="POST" enctype="multipart/form-data">
                <div class="loader-box"><div class="circle-loader"></div></div>
            </form>
        </div>
    </div>
</div>