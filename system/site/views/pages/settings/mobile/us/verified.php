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
												<p>Your mobile has been verified. Your text notifications will be sent to <strong><?=$this->user_m->meta('mobile_number')?></strong>. You can change your notification settings in the Notification Settings menu.</p>
                                            	<p><button type="button" id="link" href="<?=site_url('settings/mobile/us')?>?cancel=1&mobile=<?=$this->user_m->meta('mobile_number')?>"><span><em><b>delete this mobile</b></em></span></button></p>
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