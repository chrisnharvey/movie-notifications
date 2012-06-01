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
											<p>So you want to setup your mobile to receive Movie Notifications alerts. Thats great, first we'll need your mobile number, please enter it below.</p>
												<fieldset>
									            	<label>Mobile Number: <input name="mobile" type="text" class="text" maxlength="15" value="<?=set_value('mobile', NULL)?>"></label>
									            	<?=form_error('mobile')?>
									            	<button type="submit"><span><em><b>next step</b></em></span></button>
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