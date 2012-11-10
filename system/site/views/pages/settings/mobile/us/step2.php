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
                          <h3><a href="/settings/mobile/uk">Looking for the UK mobile settings?</a>Mobile Settings (US)</h3>
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
												<p>Lovely, we have just sent a text message to <strong><?=$this->user_m->meta('mobile_number')?></strong> with a verification code. When you receive the message, please enter the code below.</p>
													<fieldset>
										            	<label>Verification Code: <input name="code" type="text" class="text" maxlength="8"></label>
										            	<?=form_error('code')?>
										            	<button type="submit"><span><em><b>verify mobile</b></em></span></button> <button id="link" type="button" href="<?=site_url('settings/mobile/us')?>?cancel=1&mobile=<?=$this->user_m->meta('mobile_number')?>"><span><em><b>cancel verification</b></em></span></button>
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