<!-- Upload file Modal -->
<div class="modal fade" id="updateSignature" role="dialog">
    <div class="close-modal" data-dismiss="modal">&times;</div>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="sign_dig_title">Adopt Signature </h4>
            </div>
            <ul class="head-links">
                <li type="capture" class="active"><a data-toggle="tab" href="#text">Style</a></li>
                <!--        <li type="upload"><a data-toggle="tab" href="#upload">Upload</a></li>-->
                <li type="draw"><a data-toggle="tab" href="#draw">Draw</a></li>
            </ul>
            <div class="modal-body">
                <div class="tab-content">
                    <div id="text" class="tab-pane fade in active">
                        <form>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label id="sign_dlg_label">Type your signature</label>
                                        <input type="text" class="form-control signature-input" name=""
                                               placeholder="Type your signature" maxlength="18"
                                               value="{{ $request->user_name }}">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Select font</label>
                                        <select class="form-control signature-font" name="">
                                            <option value="Meie Script">Meie Script</option>
                                            <!--                                      <option value="Lato">Lato</option>-->
                                            <option value="Miss Fajardose">Miss Fajardose</option>

                                            <option value="Petit Formal Script">Petit Formal Script</option>
                                            <option value="Niconne">Niconne</option>
                                            <option value="Rochester">Rochester</option>
                                            <option value="Tangerine">Tangerine</option>
                                            <option value="Great Vibes">Great Vibes</option>
                                            <option value="Berkshire Swash">Berkshire Swash</option>
                                            <option value="Sacramento">Sacramento</option>
                                            <option value="Dr Sugiyama">Dr Sugiyama</option>
                                            <option value="League Script">League Script</option>
                                            <option value="Courgette">Courgette</option>
                                            <option value="Pacifico">Pacifico</option>
                                            <option value="Cookie">Cookie</option>
                                            <option value="Grand Hotel">Grand Hotel</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Weight</label>
                                        <select class="form-control signature-weight" name="">
                                            <option value="normal">Regular</option>
                                            <option value="bold">Bold</option>
                                            <option value="lighter">Lighter</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Color</label>
                                        <input class="form-control signature-color jscolor { valueElement:null,borderRadius:'1px', borderColor:'#e6eaee',value:'000000',zIndex:'99999', onFineChange:'updateSignatureColor(this)'}"
                                               readonly="">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Style</label>
                                        <select class="form-control signature-style" name="">
                                            <option value="normal">Regular</option>
                                            <option value="italic">Italic</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="divider"></div>
                        <h4 class="text-center">Preview</h4>
                        <div class="text-signature-preview">
                            <div class="text-signature" id="text-signature" style="color: #000000">
                                <span>{{$request->user_name }}</span>
                            </div>
                        </div>

                    </div>
                    <div id="upload" class="tab-pane fade">
                        <p>Upload your signature if you already have it.</p>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Upload your signature</label>
                                    <input type="file" name="signatureupload" class="croppie" crop-width="400"
                                           crop-height="150">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="draw" class="tab-pane fade text-center">
                        <p>Draw your signature.</p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="signature-holder">
                                    <div class="signature-body">
                                        @if ( empty( $user->signature ) )
                                        <img src="<?= url(""); ?>uploads/signatures/demo.png" class="img-responsive">
                                        @else
                                        <img src="<?= url(""); ?>uploads/signatures/{{ $user->signature }}"
                                             class="img-responsive">
                                        @endif
                                    </div>
                                </div>
                                <div class="signature-btn-holder">
                                    <button type="button" class="btn btn-primary edit-draw" data-toggle="modal"
                                            data-target="#editDrawModal" data-backdrop="static" data-keyboard="false">
                                        Adopt Signature
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary save-signature" id="sign_dlg_button">Save Signature</button>
            </div>
        </div>

    </div>
</div>
