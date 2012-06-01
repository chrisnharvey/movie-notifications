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
                          <h3>Notification Settings</h3>
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
                                            	<? if($this->session->userdata('verified') == '1'): ?>
													<?=form_open()?>
													<?=validation_errors()?>
													
													<p>
														I would like to be notified via
														<?=form_dropdown('notify_via', $notify_via, ($this->input->post('notify_via')) ? $this->input->post('notify_via') : ($this->user_m->meta('notify_via') ? $this->user_m->meta('notify_via') : 'email'))?>
													</p>
													
													<p>I would like to be notified 
													<?=form_dropdown('days_to_notify', $days_to_notify, ($this->input->post('days_to_notify')) ? $this->input->post('days_to_notify') : ($this->user_m->meta('days_to_notify') ? $this->user_m->meta('days_to_notify') : '7'))?>
													before the movie is released
													</p>
													
													<p>I would like to be notified between
													<?=form_dropdown('notify_start', $notify_start, ($this->input->post('notify_start') !== FALSE) ? $this->input->post('notify_start') : ($this->user_m->meta('notify_start') ? $this->user_m->meta('notify_start') : '9'))?>
													and 
													<?=form_dropdown('notify_end', $notify_end, ($this->input->post('notify_end') !== FALSE) ? $this->input->post('notify_end') : ($this->user_m->meta('notify_end') ? $this->user_m->meta('notify_end') : '19'))?>
													</p>
													
													<button type="submit"><span><em><b>update settings</b></em></span></button>
													
													<?=form_close()?>
												<? else: ?>
													<?=$this->load->view('errors/email_not_verified')?>
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