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
                          <h3>Account Settings</h3>
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
												<p class="success">Your account settings have been updated</p>
											<? elseif(isset($success) AND !$success): ?>
												<p class="failure">There was an error whilst updating your account settings</p>
											<? endif; ?>
												<fieldset>
									            	<label>Email: <input name="email" type="text" class="text" value="<?=set_value('email', $this->session->userdata('email'))?>"></label>
									            	<?=form_error('email')?>
									            	<label>Timezone: <?=timezone_menu(($timezone = $this->user_m->meta('timezone')) ? $timezone : 'UTC')?></label>
									            	<?=form_error('timezones')?>
									            	<label>Password: <input name="password" type="password" class="text"></label>
									           		<?=form_error('password')?>
									           		<button type="submit"><span><em><b>change settings</b></em></span></button>
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