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
											<p>Lovely, now we'll need to verify that the mobile number <strong><?=$this->user_m->meta('mobile_number')?></strong> belongs to you.</p> 
											Please send a text message to
											<code>88147</code>
											with this as the message
											<code>JOIN MOVIENOTIFS</code>
											
											<div><button id="link" type="button" href="<?=site_url('settings/mobile/us')?>?cancel=1&mobile=<?=$this->user_m->meta('mobile_number')?>"><span><em><b>cancel verification</b></em></span></button></div>
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