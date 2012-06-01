
	<div class="wrapper">
        <div class="searchContent">
          <div class="row-1 section">
            <?=$this->load->view('partials/login/reset_left')?>
            <div class="box login_box">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="title">
                          <h3>Change your password</h3>
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
                                            	<? if(isset($message)): ?>
                                            		Thank you, your password has now been changed. you may now <?=anchor('login', 'login')?> using your new password.
                                            	<? else: ?>
                                            		<?=form_open(NULL, array('class' => 'login reset'))?>
         												
         												<ul>
         													<? if(!empty($errors)): ?>
         														<? if($this->session->flashdata('message')): ?>
         															<?=$this->session->flashdata('message')?>
         														<? else: ?>
         															<li>The username or password you entered is incorrect</li>
         														<? endif; ?>
         													<? endif; ?>
         													<li><label for="new_password">New password: </label><input size="30" type="password" name="new_password" id="new_password"></li>
         													<?=form_error('new_password')?><?=isset($errors['new_password']) ? $errors['new_password'] : ''?>
         													<li><label for="login">Confirm new password: </label><input size="30" type="password" name="confirm_new_password" id="confirm_new_password"></li>
         													<?=form_error('confirm_new_password')?><?=isset($errors['confirm_new_password']) ? $errors['confirm_new_password'] : ''?>
         													<div class="button_container">
         														<button type="submit"><span><em><b>change your passowrd</b></em></span></button>
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