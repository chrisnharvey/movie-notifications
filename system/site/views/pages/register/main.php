
	<div class="wrapper">
        <div class="mainContent_full">
          <div class="row-1 section">
            <?=$this->load->view('partials/register/main_left')?>
            <div class="box login_box">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="title">
                          <h3>Register</h3>
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
                                            	
                                            		<?=form_open('register', array('class' => 'login'))?>
         												
         												<ul>
         													<li><label for="username">Username: </label><input size="30" type="text" name="username" id="username" value="<?=set_value('username')?>"></li>
         													<?=form_error('username')?>
         													<li><label for="email">Email address: </label><input size="30" type="text" name="email" id="email" value="<?=set_value('email')?>"></li>
         													<?=form_error('email')?>
         													<li><label for="password">Password: </label><input autocomplete="off" class="form-text" size="30" type="password" name="password" id="password"></li>
         													<?=form_error('password')?>
         													<li><label for="confirm_password">Confirm password: </label><input autocomplete="off" size="30" type="password" name="confirm_password" id="confirm_password"></li>
         													<?=form_error('password')?>
     														<div class="recaptcha_holder">
         														<li><?=$recaptcha_html?></li>
         														<?=form_error('recaptcha_response_field')?>
     														</div>
         													<div class="button_container">
         														<button type="submit"><span><em><b>register</b></em></span></button>
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