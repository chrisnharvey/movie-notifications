
	<div class="wrapper">
        <div class="searchContent">
          <div class="row-1 section">
            <?=$this->load->view('partials/login/forgot_left')?>
            <div class="box login_box">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="title">
                          <h3>Enter your email or username</h3>
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
                                            	<? if (isset($message)): ?>
                                            		Thank you, you should receive an email shortly with instructions on how you can reset your password.
                                            	<? else: ?>
                                            		<?=form_open('login/forgot', array('class' => 'login'))?>
                             												<ul>
                             													<? if ( ! empty($errors)): ?>
                             														<? if ($this->session->flashdata('message')): ?>
                             															<?=$this->session->flashdata('message')?>
                             														<? else: ?>
                             															<li>The username you entered is incorrect</li>
                             														<? endif; ?>
                             													<? endif; ?>
                             													<li><label for="identity">Email or username: </label><input class="form-text" size="30" type="text" name="identity" id="identity"></li>
                             													<?=form_error('identity')?><?=isset($errors['identity']) ? $errors['identity'] : ''?>
                             													<div class="recaptcha_holder">
                             														<li><?=$recaptcha_html?></li>
                             														<?=form_error('recaptcha_response_field')?>
                         														</div>
                    	         												<div class="button_container">
                    																    <button type="submit"><span><em><b>reset my password</b></em></span></button>
                    	                               </div>
                             												</ul>
                                            		<?=form_close()?>
                                            	<? endif; ?>
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