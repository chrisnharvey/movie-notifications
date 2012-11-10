<div class="wrapper">
	<? $this->load->view('pages/settings/_global'); ?>
	<div class="mainContent">
          <div class="row-1 section">
            <!-- .box -->
            <div class="box">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="title">
                          <h3>Password Settings</h3>
                        </div>
                        <!-- .box1 -->
                        <div class="box1">
                          <div class="border-top">
                            <div class="border-right">
                              <div class="border-bot">
                                <div class="border-left">
                                  <div class="left-top-corner">
                                    <div class="right-top-corner">
                                      <div class="right-bot-corner">
                                        <div class="left-bot-corner">
                                          <div class="inner">
                                            <div class="wrapper">
												
											<?=form_open()?>
											<? if(isset($success) AND $success): ?>
												<p class="success">Your password has been changed</p>
											<? elseif(isset($success) AND !$success): ?>
												<p class="failure">There was an error whilst changing your password</p>
											<? endif; ?>
												<fieldset>
									            	<label>Your current password: <input autocomplete="off" name="password" type="password" class="text"></label>
									            	<?=form_error('password')?>
									            	<label>Your new password: <input autocomplete="off" name="new_password" type="password" class="text"></label>
									            	<?=form_error('new_password')?>
									            	<label>Confirm your new password: <input autocomplete="off" name="verify_password" type="password" class="text"></label>
									            	<?=form_error('verify_password')?>
									            	<button type="submit"><span><em><b>change password</b></em></span></button>
									            </fieldset>
											<?=form_close()?>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /.box1 -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box -->
          </div>
      </div>
</div>