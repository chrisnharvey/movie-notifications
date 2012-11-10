
	<div class="wrapper">
        <div class="mainContent_full">
          <div class="row-1 section">
            <?=$this->load->view('partials/login/main_left')?>
            <div class="box login_box">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="title">
                          <h3>Login</h3>
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
                                            	
                                            		<?=form_open('login', array('class' => 'login'))?>
         												
         												<ul>
         													<li><label for="identity">Email or username: </label><input size="30" type="text" name="identity" id="identity"></li>
         													<?=form_error('identity')?>
         													<li><label for="password">Password: </label><input size="30" type="password" name="password" id="password"></li>
         													<?=form_error('password')?>
         													
         													<? if(isset($error)): ?>
         															<p><?=$error?></p>
         													<? endif; ?>
         													
         													<? if($show_recaptcha): ?>
         														<div class="recaptcha_holder">
	         														<li><?=$recaptcha_html?></li>
	         														<?=form_error('recaptcha_response_field')?>
         														</div>
         													<? endif; ?>
         													<div class="button_container">
         														<button type="button" id="link" href="<?=site_url('login/forgot')?>"><span><em><b>forgot your password</b></em></span></button><button type="submit"><span><em><b>login</b></em></span></button>
         													</div>
         												</ul>
                                            		
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