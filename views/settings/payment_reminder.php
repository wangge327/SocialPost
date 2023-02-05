<div id="payment_reminders" class="tab-pane fade">
    <h4>Payment Reminder Emails</h4>
    <p>Once a new student has created their account they are migrated to the payment page however there is nothing preventing them from closing down their user session without making any form of payment. <br>
        If this occurs the client would like to send out Payment reminder emails. </p>
    <form class="simcy-form" id="setting-reminder-form" action="<?=url("Settings@updatepaymentreminders");?>" data-parsley-validate="" loader="true" method="POST">
        <input type="hidden" name="csrf-token" value="{{ csrf_token() }}" />
        <div class="form-group">
            <div class="row">
                <div class="col-md-12">
                    @if( $company->reminders == "On" )
                    <input type="checkbox" id="enable-reminders" class="switch" name="reminders" value="On" checked />
                    @else
                    <input type="checkbox" id="enable-reminders" class="switch" name="reminders" value="Off" />
                    @endif
                    <label for="enable-reminders">Enable reminders</label>
                </div>
            </div>
        </div>
        @if( $company->reminders == "On" )
        <div class="panel-group reminders-holder" id="accordion">
            @else
            <div class="panel-group reminders-holder" id="accordion" style="display: none;">
                @endif
                @if( count($payment_reminders) > 0 )
                @foreach ($payment_reminders as $index => $reminder)
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @if( $index >  0 )
                        <span class="delete-reminder" data-toggle="tooltip" title="Remove reminder"><i class="ion-ios-trash"></i></span>
                        @endif
                        <h4 class="panel-title">
                        <a data-parent="#accordion" data-toggle="collapse" href="#p_collapse{{ $index + 1 }}">Reminder #<span class="count">{{ $index + 1 }}</span></a>
                        </h4>
                    </div>
                    @if( $index ==  0 )
                    <div class="panel-collapse collapse in" id="p_collapse{{ $index + 1 }}">
                        @else
                        <div class="panel-collapse collapse" id="p_collapse{{ $index + 1 }}">
                            @endif
                            <div class="panel-body">
                                <div class="remider-item">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <input type="hidden" name="count[]" value="1">
                                                <label>Email subject</label> <input class="form-control" name="subject[]" placeholder="Email subject" required type="text" value="{{ $reminder->subject }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                            @if( $index ==  0 )
                                            	<label>Minutes after the student has closed their session without</label>
                                            @else
                                            	<label>Hours after the student has closed their session without.</label> 
                                            @endif                                            
                                            	<input class="form-control" name="hours[]" min="1" required type="number" value="{{ $reminder->hours }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label>Message</label>
                                                <textarea class="form-control" name="message[]" required rows="9">{{ $reminder->message }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button class="btn btn-default add-reminder" type="button">Add reminder</button>
                            <button class="btn btn-primary" type="submit">Save Changes</button>
                        </div>
                    </div>
                </div>
    </form>

</div>